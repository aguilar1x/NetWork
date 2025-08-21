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
	header('Location: aprender.php');
	exit();
}

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$accion = $_POST['accion'] ?? '';
	if ($accion === 'crear') {
		$id_categoria = (int)($_POST['id_categoria'] ?? 0);
		$nombre = trim($_POST['nombre'] ?? '');
		$descripcion = trim($_POST['descripcion'] ?? '');
		$precio = (float)($_POST['precio'] ?? 0);
		$tiempo = (int)($_POST['tiempo_horas'] ?? 0);
		$imagen = trim($_POST['imagen'] ?? '');
		if ($id_categoria && $nombre && $descripcion && $precio >= 0 && $tiempo >= 0 && $imagen) {
			$stmt = $conn->prepare("INSERT INTO curso (id_categoria, id_estado, nombre, descripcion, precio, tiempo_horas, imagen) VALUES (?, 1, ?, ?, ?, ?, ?)");
			$stmt->bind_param('issdis', $id_categoria, $nombre, $descripcion, $precio, $tiempo, $imagen);
			if ($stmt->execute()) $mensaje = 'Curso creado correctamente'; else $error = 'Error al crear el curso';
		} else {
			$error = 'Complete todos los campos';
		}
	}
	if ($accion === 'editar') {
		$id = (int)($_POST['id'] ?? 0);
		$id_categoria = (int)($_POST['id_categoria'] ?? 0);
		$id_estado = (int)($_POST['id_estado'] ?? 1);
		$nombre = trim($_POST['nombre'] ?? '');
		$descripcion = trim($_POST['descripcion'] ?? '');
		$precio = (float)($_POST['precio'] ?? 0);
		$tiempo = (int)($_POST['tiempo_horas'] ?? 0);
		$imagen = trim($_POST['imagen'] ?? '');
		if ($id && $id_categoria && $id_estado && $nombre && $descripcion && $imagen) {
			$stmt = $conn->prepare("UPDATE curso SET id_categoria=?, id_estado=?, nombre=?, descripcion=?, precio=?, tiempo_horas=?, imagen=? WHERE id=?");
			$stmt->bind_param('iissdisi', $id_categoria, $id_estado, $nombre, $descripcion, $precio, $tiempo, $imagen, $id);
			if ($stmt->execute()) $mensaje = 'Curso actualizado'; else $error = 'Error al actualizar';
		} else {
			$error = 'Complete todos los campos';
		}
	}
	if ($accion === 'eliminar') {
		$id = (int)($_POST['id'] ?? 0);
		if ($id) {
			$stmt = $conn->prepare("DELETE FROM curso WHERE id = ?");
			$stmt->bind_param('i', $id);
			if ($stmt->execute()) $mensaje = 'Curso eliminado'; else $error = 'Error al eliminar';
		}
	}
}

// Datos auxiliares
$categorias = [];
$resCat = $conn->query("SELECT id, nombre FROM categoria ORDER BY nombre");
if ($resCat) $categorias = $resCat->fetch_all(MYSQLI_ASSOC);

$estados = [];
$resEst = $conn->query("SELECT id, tipo_estado FROM estado ORDER BY id");
if ($resEst) $estados = $resEst->fetch_all(MYSQLI_ASSOC);

// Cursos
$cursos = [];
$q = $conn->query("SELECT c.*, cat.nombre AS nombre_categoria, e.tipo_estado FROM curso c LEFT JOIN categoria cat ON cat.id=c.id_categoria LEFT JOIN estado e ON e.id=c.id_estado ORDER BY c.id DESC");
if ($q) $cursos = $q->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - Cursos</title>
	<link href="./css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
	<script src="./js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="./css/style.css">
</head>
<body>
<header></header>
<main class="container py-4">
	<div class="admin-hero rounded-4 p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
		<div>
			<h1 class="h3 mb-1 text-white">Panel administrativo • Cursos</h1>
			<p class="mb-0 text-white-50">Gestiona la creación, edición y estado de los cursos.</p>
		</div>
		<div class="d-flex gap-2">
			<a class="btn btn-outline-light" href="aprender.php"><i class="bi bi-arrow-left me-1"></i> Volver</a>
			<button class="btn btn-admin btn-sm" data-bs-toggle="modal" data-bs-target="#createCourseModal"><i class="bi bi-plus-lg me-1"></i> Crear curso</button>
		</div>
	</div>

	<?php if (!empty($mensaje)): ?><div class="alert alert-success py-2"><?php echo htmlspecialchars($mensaje); ?></div><?php endif; ?>
	<?php if (!empty($error)): ?><div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

	<div class="card mb-4 admin-card d-none">
		<div class="card-body">
			<h5 class="card-title">Crear nuevo curso</h5>
			<form method="POST" class="row g-2">
				<input type="hidden" name="accion" value="crear">
				<div class="col-md-3">
					<label class="form-label">Categoría</label>
					<select class="form-select" name="id_categoria" required>
						<?php foreach ($categorias as $cat): ?>
							<option value="<?php echo (int)$cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Nombre</label>
					<input class="form-control" name="nombre" required>
				</div>
				<div class="col-md-3">
					<label class="form-label">Precio</label>
					<input class="form-control" type="number" step="0.01" name="precio" required>
				</div>
				<div class="col-md-3">
					<label class="form-label">Horas</label>
					<input class="form-control" type="number" name="tiempo_horas" required>
				</div>
				<div class="col-12">
					<label class="form-label">Descripción</label>
					<textarea class="form-control" name="descripcion" rows="2" required></textarea>
				</div>
				<div class="col-12">
					<label class="form-label">Imagen (URL)</label>
					<input class="form-control" name="imagen" required>
				</div>
				<div class="col-12 text-end mt-2">
					<button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg"></i> Crear</button>
				</div>
			</form>
		</div>
	</div>

	<div class="card admin-card">
		<div class="card-body">
			<div class="d-flex justify-content-between align-items-center mb-2">
				<h5 class="card-title mb-0">Listado de cursos</h5>
				<span class="text-muted small">Total: <?php echo count($cursos); ?></span>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-hover align-middle admin-table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nombre</th>
							<th>Categoría</th>
							<th>Estado</th>
							<th>Precio</th>
							<th>Horas</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($cursos as $c): ?>
						<tr>
							<td><?php echo (int)$c['id']; ?></td>
							<td><?php echo htmlspecialchars($c['nombre']); ?></td>
							<td><?php echo htmlspecialchars($c['nombre_categoria'] ?? ''); ?></td>
							<td>
								<?php $isActivo = strtolower((string)($c['tipo_estado'] ?? '')) === 'activo'; ?>
								<span class="badge rounded-pill <?php echo $isActivo ? 'bg-success' : 'bg-secondary'; ?>">
									<?php echo htmlspecialchars($c['tipo_estado'] ?? ''); ?>
								</span>
							</td>
							<td>$<?php echo number_format((float)$c['precio'], 2); ?></td>
							<td><?php echo (int)$c['tiempo_horas']; ?></td>
							<td class="d-flex gap-2">
								<button class="btn btn-admin btn-sm" data-bs-toggle="modal" data-bs-target="#editRow<?php echo (int)$c['id']; ?>"><i class="bi bi-pencil"></i></button>
								<form method="POST" onsubmit="return confirm('¿Eliminar curso #<?php echo (int)$c['id']; ?>?');">
									<input type="hidden" name="accion" value="eliminar">
									<input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
									<button class="btn btn-sm btn-danger" type="submit"><i class="bi bi-trash"></i></button>
								</form>
							</td>
						</tr>
						<!-- Modal editar fila -->
						<div class="modal fade" id="editRow<?php echo (int)$c['id']; ?>" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Editar curso #<?php echo (int)$c['id']; ?></h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
									</div>
									<div class="modal-body">
										<form method="POST">
											<input type="hidden" name="accion" value="editar">
											<input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
											<div class="mb-2">
												<label class="form-label">Categoría</label>
												<select class="form-select" name="id_categoria" required>
													<?php foreach ($categorias as $cat): ?>
														<option value="<?php echo (int)$cat['id']; ?>" <?php echo ((int)$cat['id'] === (int)$c['id_categoria']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['nombre']); ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="mb-2">
												<label class="form-label">Estado</label>
												<select class="form-select" name="id_estado" required>
													<?php foreach ($estados as $e): ?>
														<option value="<?php echo (int)$e['id']; ?>" <?php echo ((int)$e['id'] === (int)$c['id_estado']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($e['tipo_estado']); ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="mb-2">
												<label class="form-label">Nombre</label>
												<input class="form-control" name="nombre" value="<?php echo htmlspecialchars($c['nombre']); ?>" required>
											</div>
											<div class="mb-2">
												<label class="form-label">Descripción</label>
												<textarea class="form-control" name="descripcion" rows="3" required><?php echo htmlspecialchars($c['descripcion']); ?></textarea>
											</div>
											<div class="row g-2">
												<div class="col">
													<label class="form-label">Precio</label>
													<input class="form-control" type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($c['precio']); ?>" required>
												</div>
												<div class="col">
													<label class="form-label">Horas</label>
													<input class="form-control" type="number" name="tiempo_horas" value="<?php echo (int)$c['tiempo_horas']; ?>" required>
												</div>
											</div>
											<div class="mb-2">
												<label class="form-label">Imagen (URL)</label>
												<input class="form-control" name="imagen" value="<?php echo htmlspecialchars($c['imagen']); ?>" required>
											</div>
											<div class="text-end">
												<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
												<button class="btn btn-primary" type="submit">Guardar</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</main>

<script>
	window.currentUser = {
		nombre: '<?php echo htmlspecialchars($currentUser['nombre']); ?>'
	};
</script>

<footer class="footer text-center mt-1"></footer>
<script src="./js/components/header.js"></script>
<script src="./js/components/footer.js"></script>
<script src="./js/index.js"></script>

<!-- Modal crear curso -->
<div class="modal fade" id="createCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear nuevo curso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="accion" value="crear">
                    <div class="mb-2">
                        <label class="form-label">Categoría</label>
                        <select class="form-select" name="id_categoria" required>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo (int)$cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="row g-2">
                        <div class="col">
                            <label class="form-label">Precio</label>
                            <input class="form-control" type="number" step="0.01" name="precio" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Horas</label>
                            <input class="form-control" type="number" name="tiempo_horas" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Imagen (URL)</label>
                        <input class="form-control" name="imagen" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-admin">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</body>
</html>

