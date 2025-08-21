<?php
/* 
========================================
PÁGINA DE FREELANCE
========================================
Esta página muestra ofertas de trabajo freelance
y permite a los usuarios buscar y aplicar a proyectos
*/

require_once 'app/models/user.php';
require_once 'app/config/db.php';

// Verificar si el usuario está logueado
if (!User::isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Obtener información del usuario actual
$currentUser = User::getCurrentUser();
$es_admin = ($currentUser['rol'] === 1);

// Procesar formulario de nueva oferta
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publicar_oferta'])) {
    $tituloOferta = trim($_POST['tituloOferta'] ?? '');
    $categoriaOferta = $_POST['categoriaOferta'] ?? '';
    $nivelOferta = $_POST['nivelOferta'] ?? '';
    $presupuestoOferta = $_POST['presupuestoOferta'] ?? '';
    $tipoOferta = $_POST['tipoOferta'] ?? '';
    $descripcionOferta = trim($_POST['descripcionOferta'] ?? '');

    // Validar que todos los campos estén llenos
    if (!empty($tituloOferta) && !empty($categoriaOferta) && !empty($nivelOferta) && 
        !empty($presupuestoOferta) && !empty($tipoOferta) && !empty($descripcionOferta)) {
        
        // Obtener el ID de la categoría
        $stmt = $conn->prepare("SELECT id FROM categoria WHERE nombre = ?");
        $stmt->bind_param("s", $categoriaOferta);
        $stmt->execute();
        $result = $stmt->get_result();
        $categoria = $result->fetch_assoc();
        
        if ($categoria) {
            // Insertar la oferta en la base de datos
            $stmt = $conn->prepare("INSERT INTO oferta (id_usuario, id_categoria, id_estado, nombre, descripcion, nivel, modalidad, publicado_por, fecha, presupuesto) VALUES (?, ?, 1, ?, ?, ?, ?, ?, CURDATE(), ?)");
            $stmt->bind_param("iisssssd", 
                $currentUser['id'],
                $categoria['id'],
                $tituloOferta,
                $descripcionOferta,
                $nivelOferta,
                $tipoOferta,
                $currentUser['nombre'],
                $presupuestoOferta
            );

            if ($stmt->execute()) {
                $mensaje = 'Oferta publicada exitosamente';
            } else {
                $error = 'Error al publicar la oferta: ' . $conn->error;
            }
        } else {
            $error = 'Categoría no válida';
        }
    } else {
        $error = 'Complete todos los campos obligatorios';
    }
}

// Procesar edición de oferta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_oferta'])) {
    $id_oferta = $_POST['id_oferta'] ?? '';
    $tituloOferta = trim($_POST['tituloOferta'] ?? '');
    $categoriaOferta = $_POST['categoriaOferta'] ?? '';
    $nivelOferta = $_POST['nivelOferta'] ?? '';
    $presupuestoOferta = $_POST['presupuestoOferta'] ?? '';
    $tipoOferta = $_POST['tipoOferta'] ?? '';
    $descripcionOferta = trim($_POST['descripcionOferta'] ?? '');

    // Verificar que el usuario sea el propietario de la oferta o admin
    $stmt = $conn->prepare("SELECT id_usuario FROM oferta WHERE id = ?");
    $stmt->bind_param("i", $id_oferta);
    $stmt->execute();
    $result = $stmt->get_result();
    $oferta = $result->fetch_assoc();

    if ($oferta && ($oferta['id_usuario'] == $currentUser['id'] || $es_admin)) {
        if (!empty($tituloOferta) && !empty($categoriaOferta) && !empty($nivelOferta) && 
            !empty($presupuestoOferta) && !empty($tipoOferta) && !empty($descripcionOferta)) {
            
            // Obtener el ID de la categoría
            $stmt = $conn->prepare("SELECT id FROM categoria WHERE nombre = ?");
            $stmt->bind_param("s", $categoriaOferta);
            $stmt->execute();
            $result = $stmt->get_result();
            $categoria = $result->fetch_assoc();
            
            if ($categoria) {
                // Actualizar la oferta
                $stmt = $conn->prepare("UPDATE oferta SET id_categoria = ?, nombre = ?, descripcion = ?, nivel = ?, modalidad = ?, presupuesto = ? WHERE id = ?");
                $stmt->bind_param("issssdi", 
                    $categoria['id'],
                    $tituloOferta,
                    $descripcionOferta,
                    $nivelOferta,
                    $tipoOferta,
                    $presupuestoOferta,
                    $id_oferta
                );

                if ($stmt->execute()) {
                    $mensaje = 'Oferta actualizada exitosamente';
                } else {
                    $error = 'Error al actualizar la oferta: ' . $conn->error;
                }
            } else {
                $error = 'Categoría no válida';
            }
        } else {
            $error = 'Complete todos los campos obligatorios';
        }
    } else {
        $error = 'No tienes permisos para editar esta oferta';
    }
}

// Procesar eliminación de oferta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_oferta'])) {
    $id_oferta = $_POST['id_oferta'] ?? '';

    // Verificar que el usuario sea el propietario de la oferta o admin
    $stmt = $conn->prepare("SELECT id_usuario FROM oferta WHERE id = ?");
    $stmt->bind_param("i", $id_oferta);
    $stmt->execute();
    $result = $stmt->get_result();
    $oferta = $result->fetch_assoc();

    if ($oferta && ($oferta['id_usuario'] == $currentUser['id'] || $es_admin)) {
        $stmt = $conn->prepare("DELETE FROM oferta WHERE id = ?");
        $stmt->bind_param("i", $id_oferta);

        if ($stmt->execute()) {
            $mensaje = 'Oferta eliminada exitosamente';
        } else {
            $error = 'Error al eliminar la oferta: ' . $conn->error;
        }
    } else {
        $error = 'No tienes permisos para eliminar esta oferta';
    }
}

// Obtener ofertas con nombre de categoría y usuario
$query = "SELECT o.*, c.nombre AS categoria, u.nombre AS nombre_usuario 
          FROM oferta o 
          LEFT JOIN categoria c ON c.id = o.id_categoria 
          LEFT JOIN usuarios u ON u.id = o.id_usuario 
          WHERE o.id_estado = 1 
          ORDER BY o.fecha DESC";
$result = $conn->query($query);
$ofertas = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Obtener categorías para el formulario
$categorias_result = $conn->query("SELECT nombre FROM categoria WHERE id_estado = 1");
$categorias = $categorias_result ? $categorias_result->fetch_all(MYSQLI_ASSOC) : [];

// Filtros
$categoria_filtro = $_GET['categoria'] ?? '';
$nivel_filtro = $_GET['nivel'] ?? '';
$presupuesto_filtro = $_GET['presupuesto'] ?? '';
$tipo_filtro = $_GET['tipo'] ?? '';

// Aplicar filtros si están establecidos
if ($categoria_filtro || $nivel_filtro || $presupuesto_filtro || $tipo_filtro) {
    $where_conditions = ["o.id_estado = 1"];
    $params = [];
    $types = "";

    if ($categoria_filtro) {
        $where_conditions[] = "c.nombre = ?";
        $params[] = $categoria_filtro;
        $types .= "s";
    }

    if ($nivel_filtro) {
        $where_conditions[] = "o.nivel = ?";
        $params[] = $nivel_filtro;
        $types .= "s";
    }

    if ($tipo_filtro) {
        $where_conditions[] = "o.modalidad = ?";
        $params[] = $tipo_filtro;
        $types .= "s";
    }

    if ($presupuesto_filtro) {
        $where_conditions[] = "o.presupuesto <= ?";
        $params[] = $presupuesto_filtro;
        $types .= "d";
    }

    $where_clause = implode(" AND ", $where_conditions);
    $query = "SELECT o.*, c.nombre AS categoria, u.nombre AS nombre_usuario 
              FROM oferta o 
              LEFT JOIN categoria c ON c.id = o.id_categoria 
              LEFT JOIN usuarios u ON u.id = o.id_usuario 
              WHERE $where_clause 
              ORDER BY o.fecha DESC";

    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $ofertas = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>NetWork - Freelance</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header></header>

    <main class="container my-4">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold">Oportunidades Freelance</h1>
            <p class="lead text-muted">Encuentra proyectos perfectos para tus habilidades</p>
        </div>

        <?php if ($mensaje): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($mensaje); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- FILTROS para búsqueda -->
        <section class="filter-section">
            <h3><i class="bi bi-funnel me-2"></i>Buscar ofertas freelance</h3>
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select id="categoria" class="form-select" name="categoria">
                        <option value="">Todas</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['nombre']); ?>" <?php echo $categoria_filtro === $cat['nombre'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="nivel" class="form-label">Nivel</label>
                    <select id="nivel" class="form-select" name="nivel">
                        <option value="">Todos</option>
                        <option value="basico" <?php echo $nivel_filtro === 'basico' ? 'selected' : ''; ?>>Básico</option>
                        <option value="intermedio" <?php echo $nivel_filtro === 'intermedio' ? 'selected' : ''; ?>>Intermedio</option>
                        <option value="avanzado" <?php echo $nivel_filtro === 'avanzado' ? 'selected' : ''; ?>>Avanzado</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="presupuesto" class="form-label">Presupuesto (USD)</label>
                    <select id="presupuesto" class="form-select" name="presupuesto">
                        <option value="">Cualquiera</option>
                        <option value="0-100" <?php echo $presupuesto_filtro === '0-100' ? 'selected' : ''; ?>>0 - 100</option>
                        <option value="100-500" <?php echo $presupuesto_filtro === '100-500' ? 'selected' : ''; ?>>100 - 500</option>
                        <option value="500-1000" <?php echo $presupuesto_filtro === '500-1000' ? 'selected' : ''; ?>>500 - 1000</option>
                        <option value="1000+" <?php echo $presupuesto_filtro === '1000+' ? 'selected' : ''; ?>>Más de 1000</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="tipo" class="form-label">Tipo de trabajo</label>
                    <select id="tipo" class="form-select" name="tipo">
                        <option value="">Todos</option>
                        <option value="remoto" <?php echo $tipo_filtro === 'remoto' ? 'selected' : ''; ?>>Remoto</option>
                        <option value="presencial" <?php echo $tipo_filtro === 'presencial' ? 'selected' : ''; ?>>Presencial</option>
                        <option value="hibrido" <?php echo $tipo_filtro === 'hibrido' ? 'selected' : ''; ?>>Híbrido</option>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>Buscar
                    </button>
                    <a href="freelance.php" class="btn btn-secondary ms-2">
                        <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                    </a>
                </div>
            </form>
        </section>

        <!-- LISTADO DE OFERTAS -->
        <section id="offersList">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0"><i class="bi bi-briefcase me-2"></i>Ofertas disponibles</h3>
                <div class="d-flex align-items-center gap-2">
                    <?php if ($es_admin): ?>
                    <a href="admin-ofertas.php" class="btn btn-admin btn-sm">
                        <i class="bi bi-gear me-2"></i>Admin Ofertas
                    </a>
                    <?php endif; ?>
                    <span class="badge bg-secondary"><?php echo count($ofertas); ?> ofertas encontradas</span>
                </div>
            </div>

            <?php foreach ($ofertas as $oferta): ?>
                <article class="offer-card">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="mb-0 me-3"><?php echo htmlspecialchars($oferta['nombre']); ?></h5>
                                <span class="badge-categoria"><?php echo htmlspecialchars($oferta['categoria']); ?></span>
                            </div>
                            <p class="text-muted mb-3"><?php echo htmlspecialchars($oferta['descripcion']); ?></p>
                            <div class="row">
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Nivel</small>
                                    <strong><?php echo htmlspecialchars($oferta['nivel']); ?></strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Tipo</small>
                                    <strong><?php echo htmlspecialchars($oferta['modalidad']); ?></strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Publicado por</small>
                                    <strong><?php echo htmlspecialchars($oferta['nombre_usuario']); ?></strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Fecha</small>
                                    <strong><?php echo date('d/m/Y', strtotime($oferta['fecha'])); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <div class="price-highlight mb-3">$<?php echo htmlspecialchars($oferta['presupuesto']); ?></div>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#offerModal<?php echo $oferta['id']; ?>">
                                    <i class="bi bi-eye me-1"></i>Ver detalles
                                </button>
                                <button class="btn btn-success btn-sm" onclick="aplicarOferta(<?php echo $oferta['id']; ?>)">
                                    <i class="bi bi-send me-1"></i>Aplicar
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <!-- Publicar oferta -->
        <section class="publish-section">
            <h3><i class="bi bi-plus-circle me-2"></i>Publicar una oferta freelance</h3>
            <p class="text-muted mb-4">¿Tienes un proyecto? Publícalo y encuentra al freelancer perfecto</p>

            <form method="POST" class="row g-3">
                <input type="hidden" name="publicar_oferta" value="1">

                <div class="col-md-6">
                    <label for="tituloOferta" class="form-label">Título de la oferta *</label>
                    <input type="text" class="form-control" id="tituloOferta" name="tituloOferta" required placeholder="Ej: Desarrollador React para e-commerce">
                </div>
                <div class="col-md-6">
                    <label for="categoriaOferta" class="form-label">Categoría *</label>
                    <select id="categoriaOferta" class="form-select" name="categoriaOferta" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['nombre']); ?>">
                                <?php echo htmlspecialchars($cat['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="nivelOferta" class="form-label">Nivel requerido *</label>
                    <select id="nivelOferta" class="form-select" name="nivelOferta" required>
                        <option value="">Seleccione...</option>
                        <option value="basico">Básico</option>
                        <option value="intermedio">Intermedio</option>
                        <option value="avanzado">Avanzado</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="presupuestoOferta" class="form-label">Presupuesto (USD) *</label>
                    <input type="number" class="form-control" id="presupuestoOferta" name="presupuestoOferta" min="0" required placeholder="300">
                </div>

                <div class="col-md-4">
                    <label for="tipoOferta" class="form-label">Tipo de trabajo *</label>
                    <select id="tipoOferta" class="form-select" name="tipoOferta" required>
                        <option value="">Seleccione...</option>
                        <option value="remoto">Remoto</option>
                        <option value="presencial">Presencial</option>
                        <option value="hibrido">Híbrido</option>
                    </select>
                </div>

                <div class="col-12">
                    <label for="descripcionOferta" class="form-label">Descripción del proyecto *</label>
                    <textarea id="descripcionOferta" class="form-control" name="descripcionOferta" rows="4" required placeholder="Describe detalladamente el proyecto, requisitos y expectativas..."></textarea>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-upload me-2"></i>Publicar oferta
                    </button>
                </div>
            </form>
        </section>
    </main>

    <!-- Modales para detalles de ofertas -->
    <?php foreach ($ofertas as $oferta): ?>
        <div class="modal fade" id="offerModal<?php echo $oferta['id']; ?>" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo htmlspecialchars($oferta['nombre']); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <span class="badge-categoria"><?php echo htmlspecialchars($oferta['categoria']); ?></span>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <span class="price-highlight">$<?php echo htmlspecialchars($oferta['presupuesto']); ?></span>
                            </div>
                        </div>

                        <h6>Descripción del proyecto</h6>
                        <p><?php echo htmlspecialchars($oferta['descripcion']); ?></p>

                        <h6>Requisitos</h6>
                        <ul>
                            <?php $requisitos = json_decode($oferta['requisitos'], true);
                            foreach ($requisitos as $req) {
                                echo "<li>" . htmlspecialchars($req) . "</li>";
                            } ?>
                        </ul>

                        <h6>Beneficios</h6>
                        <p><?php echo htmlspecialchars($oferta['beneficios']); ?></p>

                        <hr>

                        <div class="row">
                            <div class="col-md-3">
                                <small class="text-muted">Nivel requerido</small>
                                <div><strong><?php echo htmlspecialchars($oferta['nivel']); ?></strong></div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Modalidad</small>
                                <div><strong><?php echo htmlspecialchars($oferta['modalidad']); ?></strong></div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Publicado por</small>
                                <div><strong><?php echo htmlspecialchars($oferta['nombre_usuario']); ?></strong></div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Fecha publicación</small>
                                <div><strong><?php echo date('d/m/Y', strtotime($oferta['fecha'])); ?></strong></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success" onclick="aplicarOferta(<?php echo $oferta['id']; ?>)">
                            <i class="bi bi-send me-1"></i>Aplicar
                        </button>
                        <?php if ($oferta['id_usuario'] == $currentUser['id'] || $es_admin): ?>
                            <button type="button" class="btn btn-warning" onclick="editarOferta(<?php echo $oferta['id']; ?>)">
                                <i class="bi bi-pencil me-1"></i>Editar
                            </button>
                            <button type="button" class="btn btn-danger" onclick="eliminarOferta(<?php echo $oferta['id']; ?>)">
                                <i class="bi bi-trash me-1"></i>Eliminar
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Modal para editar oferta -->
    <div class="modal fade" id="editarOfertaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar oferta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form method="POST" id="editarOfertaForm">
                    <input type="hidden" name="editar_oferta" value="1">
                    <input type="hidden" name="id_oferta" id="editar_id_oferta">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="editar_tituloOferta" class="form-label">Título de la oferta *</label>
                                <input type="text" class="form-control" id="editar_tituloOferta" name="tituloOferta" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editar_categoriaOferta" class="form-label">Categoría *</label>
                                <select id="editar_categoriaOferta" class="form-select" name="categoriaOferta" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat['nombre']); ?>">
                                            <?php echo htmlspecialchars($cat['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="editar_nivelOferta" class="form-label">Nivel requerido *</label>
                                <select id="editar_nivelOferta" class="form-select" name="nivelOferta" required>
                                    <option value="">Seleccione...</option>
                                    <option value="basico">Básico</option>
                                    <option value="intermedio">Intermedio</option>
                                    <option value="avanzado">Avanzado</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="editar_presupuestoOferta" class="form-label">Presupuesto (USD) *</label>
                                <input type="number" class="form-control" id="editar_presupuestoOferta" name="presupuestoOferta" min="0" required>
                            </div>

                            <div class="col-md-4">
                                <label for="editar_tipoOferta" class="form-label">Tipo de trabajo *</label>
                                <select id="editar_tipoOferta" class="form-select" name="tipoOferta" required>
                                    <option value="">Seleccione...</option>
                                    <option value="remoto">Remoto</option>
                                    <option value="presencial">Presencial</option>
                                    <option value="hibrido">Híbrido</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="editar_descripcionOferta" class="form-label">Descripción del proyecto *</label>
                                <textarea id="editar_descripcionOferta" class="form-control" name="descripcionOferta" rows="4" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Formulario oculto para eliminar oferta -->
    <form method="POST" id="eliminarOfertaForm" style="display: none;">
        <input type="hidden" name="eliminar_oferta" value="1">
        <input type="hidden" name="id_oferta" id="eliminar_id_oferta">
    </form>

    <!-- Footer -->
    <footer class="footer text-center mt-1"></footer>

    <!-- Scripts -->
    <script>
        // Pasar datos del usuario a JavaScript
        window.currentUser = {
            nombre: '<?php echo htmlspecialchars($currentUser['nombre']); ?>'
        };
    </script>

    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/freelance.js"></script>
    <script src="./js/components/header.js"></script>
    <script src="./js/components/footer.js"></script>
    <script src="./js/index.js"></script>


</body>
</html>