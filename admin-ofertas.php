<?php
require_once 'app/models/user.php';
require_once 'app/config/db.php';

if (!User::isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$currentUser = User::getCurrentUser();
$es_admin = false;
if (!empty($currentUser['id'])) {
    $stmt = $conn->prepare("SELECT id_rol FROM usuarios WHERE id = ?");
    $stmt->bind_param('i', $currentUser['id']);
    if ($stmt->execute()) {
        $row = $stmt->get_result()->fetch_assoc();
        $es_admin = ((int)($row['id_rol'] ?? 0) === 1);
    }
}
if (!$es_admin) {
    header('Location: network.php');
    exit();
}

$mensaje = '';
$error = '';

// Procesar acciones POST y redirigir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $redirect_url = 'admin-ofertas.php';
    
         if ($accion === 'crear') {
         $id_usuario = (int)($_POST['id_usuario'] ?? $currentUser['id']);
         $id_categoria = (int)($_POST['id_categoria'] ?? 1);
         $id_estado = 1; // Activo por defecto
         $nombre = trim($_POST['nombre'] ?? '');
         $descripcion = trim($_POST['descripcion'] ?? '');
         $nivel = trim($_POST['nivel'] ?? '');
         $modalidad = trim($_POST['modalidad'] ?? '');
         $presupuesto = (float)($_POST['presupuesto'] ?? 0);
         
         if ($nombre && $descripcion && $nivel && $modalidad && $presupuesto > 0) {
             $stmt = $conn->prepare("INSERT INTO oferta (id_usuario, id_categoria, id_estado, nombre, descripcion, nivel, modalidad, publicado_por, fecha, presupuesto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), ?)");
             $stmt->bind_param('iiisssssd', $id_usuario, $id_categoria, $id_estado, $nombre, $descripcion, $nivel, $modalidad, $currentUser['nombre'], $presupuesto);
             if ($stmt->execute()) {
                 $redirect_url .= '?mensaje=' . urlencode('Oferta creada correctamente');
             } else {
                 $redirect_url .= '?error=' . urlencode('Error al crear la oferta');
             }
         } else {
             $redirect_url .= '?error=' . urlencode('Complete todos los campos obligatorios');
         }
         header('Location: ' . $redirect_url);
         exit();
     }
    
         if ($accion === 'editar') {
         $id = (int)($_POST['id'] ?? 0);
         $id_usuario = (int)($_POST['id_usuario'] ?? $currentUser['id']);
         $id_categoria = (int)($_POST['id_categoria'] ?? 1);
         $id_estado = 1; // Activo por defecto
         $nombre = trim($_POST['nombre'] ?? '');
         $descripcion = trim($_POST['descripcion'] ?? '');
         $nivel = trim($_POST['nivel'] ?? '');
         $modalidad = trim($_POST['modalidad'] ?? '');
         $presupuesto = (float)($_POST['presupuesto'] ?? 0);
         
         if ($id && $nombre && $descripcion && $nivel && $modalidad && $presupuesto > 0) {
             $stmt = $conn->prepare("UPDATE oferta SET id_usuario=?, id_categoria=?, id_estado=?, nombre=?, descripcion=?, nivel=?, modalidad=?, publicado_por=?, presupuesto=? WHERE id=?");
             $stmt->bind_param('iiisssssdi', $id_usuario, $id_categoria, $id_estado, $nombre, $descripcion, $nivel, $modalidad, $currentUser['nombre'], $presupuesto, $id);
             if ($stmt->execute()) {
                 $redirect_url .= '?mensaje=' . urlencode('Oferta actualizada correctamente');
             } else {
                 $redirect_url .= '?error=' . urlencode('Error al actualizar la oferta');
             }
         } else {
             $redirect_url .= '?error=' . urlencode('Complete todos los campos obligatorios');
         }
         header('Location: ' . $redirect_url);
         exit();
     }
    
    if ($accion === 'eliminar') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM oferta WHERE id = ?");
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $redirect_url .= '?mensaje=' . urlencode('Oferta eliminada correctamente');
            } else {
                $redirect_url .= '?error=' . urlencode('Error al eliminar la oferta');
            }
        } else {
            $redirect_url .= '?error=' . urlencode('ID de oferta no válido');
        }
        header('Location: ' . $redirect_url);
        exit();
    }
}

// Obtener mensajes de la URL (después de redirección)
$mensaje = $_GET['mensaje'] ?? '';
$error = $_GET['error'] ?? '';

// Obtener ofertas con información de usuario y categoría
$query = "SELECT o.*, c.nombre AS categoria, u.nombre AS nombre_usuario, e.tipo_estado 
          FROM oferta o 
          LEFT JOIN categoria c ON c.id = o.id_categoria 
          LEFT JOIN usuarios u ON u.id = o.id_usuario 
          LEFT JOIN estado e ON e.id = o.id_estado 
          ORDER BY o.fecha DESC";
$result = $conn->query($query);
$ofertas = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Obtener categorías para el formulario
$categorias_result = $conn->query("SELECT id, nombre FROM categoria WHERE id_estado = 1");
$categorias = $categorias_result ? $categorias_result->fetch_all(MYSQLI_ASSOC) : [];

// Obtener usuarios para el formulario
$usuarios_result = $conn->query("SELECT id, nombre FROM usuarios WHERE id_estado = 1");
$usuarios = $usuarios_result ? $usuarios_result->fetch_all(MYSQLI_ASSOC) : [];

// Obtener estados para el formulario
$estados_result = $conn->query("SELECT id, tipo_estado FROM estado");
$estados = $estados_result ? $estados_result->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Ofertas | NetWork</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <header></header>

    <main class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-briefcase me-2"></i>Administrar Ofertas</h1>
            <a href="network.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver al Dashboard
            </a>
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

        <!-- Formulario para crear nueva oferta -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Crear Nueva Oferta</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="accion" value="crear">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Título de la oferta *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej: Desarrollador React para e-commerce">
                        </div>
                        <div class="col-md-6">
                            <label for="id_categoria" class="form-label">Categoría *</label>
                            <select class="form-select" id="id_categoria" name="id_categoria" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?php echo $categoria['id']; ?>">
                                        <?php echo htmlspecialchars($categoria['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="nivel" class="form-label">Nivel requerido *</label>
                            <select class="form-select" id="nivel" name="nivel" required>
                                <option value="">Seleccione...</option>
                                <option value="basico">Básico</option>
                                <option value="intermedio">Intermedio</option>
                                <option value="avanzado">Avanzado</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="presupuesto" class="form-label">Presupuesto (USD) *</label>
                            <input type="number" class="form-control" id="presupuesto" name="presupuesto" min="0" required placeholder="300">
                        </div>
                        <div class="col-md-4">
                            <label for="modalidad" class="form-label">Tipo de trabajo *</label>
                            <select class="form-select" id="modalidad" name="modalidad" required>
                                <option value="">Seleccione...</option>
                                <option value="remoto">Remoto</option>
                                <option value="presencial">Presencial</option>
                                <option value="hibrido">Híbrido</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción del proyecto *</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required placeholder="Describe detalladamente el proyecto, requisitos y expectativas..."></textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle me-2"></i>Crear Oferta
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de ofertas -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list me-2"></i>Lista de Ofertas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Categoría</th>
                                <th>Usuario</th>
                                <th>Nivel</th>
                                <th>Presupuesto</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ofertas as $oferta): ?>
                                <tr>
                                    <td><?php echo $oferta['id']; ?></td>
                                    <td><?php echo htmlspecialchars($oferta['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($oferta['categoria']); ?></td>
                                    <td><?php echo htmlspecialchars($oferta['nombre_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($oferta['nivel']); ?></td>
                                    <td>$<?php echo number_format($oferta['presupuesto'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $oferta['tipo_estado'] === 'Activo' ? 'success' : 'secondary'; ?>">
                                            <?php echo htmlspecialchars($oferta['tipo_estado']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($oferta['fecha'])); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" onclick="editarOferta(<?php echo $oferta['id']; ?>)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="eliminarOferta(<?php echo $oferta['id']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal para editar oferta -->
    <div class="modal fade" id="editarOfertaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Oferta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editarOfertaForm">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id" id="editar_id">
                                         <div class="modal-body">
                         <div class="row g-3">
                             <div class="col-md-6">
                                 <label for="editar_nombre" class="form-label">Título de la oferta *</label>
                                 <input type="text" class="form-control" id="editar_nombre" name="nombre" required placeholder="Ej: Desarrollador React para e-commerce">
                             </div>
                             <div class="col-md-6">
                                 <label for="editar_id_categoria" class="form-label">Categoría *</label>
                                 <select class="form-select" id="editar_id_categoria" name="id_categoria" required>
                                     <?php foreach ($categorias as $categoria): ?>
                                         <option value="<?php echo $categoria['id']; ?>">
                                             <?php echo htmlspecialchars($categoria['nombre']); ?>
                                         </option>
                                     <?php endforeach; ?>
                                 </select>
                             </div>
                             <div class="col-md-4">
                                 <label for="editar_nivel" class="form-label">Nivel requerido *</label>
                                 <select class="form-select" id="editar_nivel" name="nivel" required>
                                     <option value="basico">Básico</option>
                                     <option value="intermedio">Intermedio</option>
                                     <option value="avanzado">Avanzado</option>
                                 </select>
                             </div>
                             <div class="col-md-4">
                                 <label for="editar_presupuesto" class="form-label">Presupuesto (USD) *</label>
                                 <input type="number" class="form-control" id="editar_presupuesto" name="presupuesto" min="0" required placeholder="300">
                             </div>
                             <div class="col-md-4">
                                 <label for="editar_modalidad" class="form-label">Tipo de trabajo *</label>
                                 <select class="form-select" id="editar_modalidad" name="modalidad" required>
                                     <option value="remoto">Remoto</option>
                                     <option value="presencial">Presencial</option>
                                     <option value="hibrido">Híbrido</option>
                                 </select>
                             </div>
                             <div class="col-12">
                                 <label for="editar_descripcion" class="form-label">Descripción del proyecto *</label>
                                 <textarea class="form-control" id="editar_descripcion" name="descripcion" rows="4" required placeholder="Describe detalladamente el proyecto, requisitos y expectativas..."></textarea>
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

    <footer class="footer text-center mt-1"></footer>

    <!-- Formulario oculto para eliminar -->
    <form method="POST" id="eliminarForm" style="display: none;">
        <input type="hidden" name="accion" value="eliminar">
        <input type="hidden" name="id" id="eliminar_id">
    </form>

    <script src="./js/components/header.js"></script>
    <script src="./js/components/footer.js"></script>
    <script src="./js/index.js"></script>
    
    <script>
        function editarOferta(id) {
            // Aquí deberías cargar los datos de la oferta con AJAX
            // Por simplicidad, por ahora solo mostramos el modal
            document.getElementById('editar_id').value = id;
            const modal = new bootstrap.Modal(document.getElementById('editarOfertaModal'));
            modal.show();
        }

        function eliminarOferta(id) {
            if (confirm('¿Estás seguro de que quieres eliminar esta oferta?')) {
                document.getElementById('eliminar_id').value = id;
                document.getElementById('eliminarForm').submit();
            }
        }
    </script>
</body>
</html>
