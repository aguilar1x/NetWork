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
$es_admin = ($currentUser['rol'] === 1); // Solo los admin pueden crear, editar y eliminar

// Procesar formulario de nueva oferta
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publicar_oferta'])) {

    // CREAR OFERTA (solo administradores)
    if ($es_admin) {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $requisitos = $_POST['requisitos'] ?? '';
        $beneficios = $_POST['beneficios'] ?? '';
        $nivel = $_POST['nivel'] ?? '';
        $modalidad = $_POST['modalidad'] ?? '';
        $publicado_por = $_POST['publicado_por'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $presupuesto = $_POST['presupuesto'] ?? '';

        // Validar que todos los campos estén llenos
        if (
            !empty($nombre) && !empty($descripcion) && !empty($requisitos) && !empty($beneficios) && !empty($nivel)
            && !empty($modalidad) && !empty($publicado_por) && !empty($fecha) && !empty($presupuesto)
        ) {
            // Insertar la oferta en la base de datos
            $stmt = $conn->prepare("INSERT INTO oferta (id_categoria, id_estado, nombre, descripcion, requisitos, beneficios, nivel, modalidad, publicado_por, fecha, presupuesto) VALUES 
                (?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssi", $nombre, $descripcion, $requisitos, $beneficios, $nivel, 
            $modalidad, $publicado_por, $fecha, $presupuesto);

            if ($stmt->execute()) {
                $mensaje = 'Oferta publicada exitosamente';
            } else {
                $error = 'Error al publicar la oferta';
            }
        } else {
            $error = 'Complete todos los campos';
        }
    }
}

// Obtener ofertas con nombre de categoría
$result = $conn->query("SELECT o.*, c.nombre AS categoria FROM oferta o LEFT JOIN categoria c ON c.id = o.id_categoria");
$ofertas = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Datos de ejemplo para ofertas (esto vendría de la base de datos)
/*$ofertas = [
    [
        'id' => 1,
        'titulo' => 'Desarrollador Frontend React',
        'categoria' => 'Desarrollo Web',
        'nivel' => 'Intermedio',
        'presupuesto' => '$300',
        'tipo' => 'Remoto',
        'descripcion' => 'Buscamos desarrollador frontend con experiencia en React para proyecto de 3 meses.',
        'requisitos' => [
            'Experiencia mínima 1 año con React',
            'Conocimiento de REST APIs',
            'Trabajo remoto'
        ],
        'beneficios' => 'Pago puntual, flexibilidad horaria.',
        'publicado_por' => 'TechStartup Co.',
        'fecha' => '2024-01-15'
    ],
    [
        'id' => 2,
        'titulo' => 'Diseñador gráfico para branding',
        'categoria' => 'Diseño Gráfico',
        'nivel' => 'Básico',
        'presupuesto' => '$150',
        'tipo' => 'Presencial',
        'descripcion' => 'Necesitamos diseñador para crear identidad visual completa.',
        'requisitos' => [
            'Experiencia en Adobe Creative Suite',
            'Portfolio de branding',
            'Disponibilidad presencial'
        ],
        'beneficios' => 'Proyecto creativo, oportunidad de crecimiento.',
        'publicado_por' => 'Marketing Agency',
        'fecha' => '2024-01-14'
    ],
    [
        'id' => 3,
        'titulo' => 'Copywriter para blog de tecnología',
        'categoria' => 'Escritura',
        'nivel' => 'Avanzado',
        'presupuesto' => '$600',
        'tipo' => 'Híbrido',
        'descripcion' => 'Buscamos redactor especializado en contenido tecnológico.',
        'requisitos' => [
            'Experiencia en copywriting tech',
            'SEO knowledge',
            'Portafolio de artículos'
        ],
        'beneficios' => 'Proyecto a largo plazo, buen pago.',
        'publicado_por' => 'Tech Blog Inc.',
        'fecha' => '2024-01-13'
    ]
];*/

// Filtros
$categoria_filtro = $_GET['categoria'] ?? '';
$nivel_filtro = $_GET['nivel'] ?? '';
$presupuesto_filtro = $_GET['presupuesto'] ?? '';
$tipo_filtro = $_GET['tipo'] ?? '';
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

        <!-- FILTROS para búsqueda -->
        <section class="filter-section">
            <h3><i class="bi bi-funnel me-2"></i>Buscar ofertas freelance</h3>
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select id="categoria" class="form-select" name="categoria">
                        <option value="">Todas</option>
                        <option value="desarrollo-web" <?php echo $categoria_filtro === 'desarrollo-web' ? 'selected' : ''; ?>>Desarrollo Web</option>
                        <option value="diseno-grafico" <?php echo $categoria_filtro === 'diseno-grafico' ? 'selected' : ''; ?>>Diseño Gráfico</option>
                        <option value="marketing" <?php echo $categoria_filtro === 'marketing' ? 'selected' : ''; ?>>Marketing</option>
                        <option value="escritura" <?php echo $categoria_filtro === 'escritura' ? 'selected' : ''; ?>>Escritura</option>
                        <option value="otro" <?php echo $categoria_filtro === 'otro' ? 'selected' : ''; ?>>Otro</option>
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
                <h3><i class="bi bi-briefcase me-2"></i>Ofertas disponibles</h3>
                <span class="badge bg-secondary"><?php echo count($ofertas); ?> ofertas encontradas</span>
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
                                    <strong><?php echo htmlspecialchars($oferta['publicado_por']); ?></strong>
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
                        <option value="desarrollo-web">Desarrollo Web</option>
                        <option value="diseno-grafico">Diseño Gráfico</option>
                        <option value="marketing">Marketing</option>
                        <option value="escritura">Escritura</option>
                        <option value="otro">Otro</option>
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
                            <?php foreach ($oferta['requisitos'] as $requisitos): ?>
                                <li><?php echo htmlspecialchars($requisitos); ?></li>
                            <?php endforeach; ?>
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
                                <div><strong><?php echo htmlspecialchars($oferta['publicado_por']); ?></strong></div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Fecha publicación</small>
                                <div><strong><?php echo date('d/m/Y', strtotime($oferta['fecha'])); ?></strong></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" onclick="aplicarOferta(<?php echo $oferta['id']; ?>)">
                            <i class="bi bi-send me-2"></i>Aplicar a esta oferta
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Footer -->
    <footer class="footer text-center mt-1"></footer>

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