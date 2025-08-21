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
	$redirect_url = 'admin-eventos.php';
	
	if ($accion === 'crear') {
		$id_estado = (int)($_POST['id_estado'] ?? 1);
		$nombre = trim($_POST['nombre'] ?? '');
		$descripcion = trim($_POST['descripcion'] ?? '');
		$fecha_hora = trim($_POST['fecha_hora'] ?? '');
		$ubicacion = trim($_POST['ubicacion'] ?? '');
		$tipo = trim($_POST['tipo'] ?? 'Presencial');
		$asistentes = (int)($_POST['asistentes'] ?? 0);
		
		if ($nombre && $descripcion && $fecha_hora && $ubicacion) {
			$stmt = $conn->prepare("INSERT INTO evento (id_estado, nombre, descripcion, fecha_hora, ubicacion) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param('issss', $id_estado, $nombre, $descripcion, $fecha_hora, $ubicacion);
			if ($stmt->execute()) {
				$redirect_url .= '?mensaje=' . urlencode('Evento creado correctamente');
			} else {
				$redirect_url .= '?error=' . urlencode('Error al crear el evento');
			}
		} else {
			$redirect_url .= '?error=' . urlencode('Complete todos los campos obligatorios');
		}
		header('Location: ' . $redirect_url);
		exit();
	}
	
	if ($accion === 'editar') {
		$id = (int)($_POST['id'] ?? 0);
		$id_estado = (int)($_POST['id_estado'] ?? 1);
		$nombre = trim($_POST['nombre'] ?? '');
		$descripcion = trim($_POST['descripcion'] ?? '');
		$fecha_hora = trim($_POST['fecha_hora'] ?? '');
		$ubicacion = trim($_POST['ubicacion'] ?? '');
		
		if ($id && $nombre && $descripcion && $fecha_hora && $ubicacion) {
			$stmt = $conn->prepare("UPDATE evento SET id_estado=?, nombre=?, descripcion=?, fecha_hora=?, ubicacion=? WHERE id=?");
			$stmt->bind_param('issssi', $id_estado, $nombre, $descripcion, $fecha_hora, $ubicacion, $id);
			if ($stmt->execute()) {
				$redirect_url .= '?mensaje=' . urlencode('Evento actualizado correctamente');
			} else {
				$redirect_url .= '?error=' . urlencode('Error al actualizar el evento');
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
			$stmt = $conn->prepare("DELETE FROM evento WHERE id = ?");
			$stmt->bind_param('i', $id);
			if ($stmt->execute()) {
				$redirect_url .= '?mensaje=' . urlencode('Evento eliminado correctamente');
			} else {
				$redirect_url .= '?error=' . urlencode('Error al eliminar el evento');
			}
		} else {
			$redirect_url .= '?error=' . urlencode('ID de evento no válido');
		}
		header('Location: ' . $redirect_url);
		exit();
	}
}

// Obtener mensajes de la URL (después de redirección)
$mensaje = $_GET['mensaje'] ?? '';
$error = $_GET['error'] ?? '';

// Datos auxiliares
$estados = [];
$resEst = $conn->query("SELECT id, tipo_estado FROM estado ORDER BY id");
if ($resEst) $estados = $resEst->fetch_all(MYSQLI_ASSOC);

// Eventos
$eventos = [];
$q = $conn->query("SELECT e.*, est.tipo_estado FROM evento e LEFT JOIN estado est ON est.id=e.id_estado ORDER BY e.fecha_hora DESC");
if ($q) $eventos = $q->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - Eventos</title>
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
			<h1 class="h3 mb-1 text-white">Panel administrativo • Eventos</h1>
			<p class="mb-0 text-white-50">Gestiona la creación, edición y estado de los eventos de networking.</p>
		</div>
		<div class="d-flex gap-2">
			<a class="btn btn-outline-light" href="network.php"><i class="bi bi-arrow-left me-1"></i> Volver</a>
			<button class="btn btn-admin btn-sm" data-bs-toggle="modal" data-bs-target="#createEventModal"><i class="bi bi-plus-lg me-1"></i> Crear evento</button>
		</div>
	</div>

	<?php if (!empty($mensaje)): ?>
	<div class="alert alert-success py-2 alert-dismissible fade show" id="successAlert">
		<?php echo htmlspecialchars($mensaje); ?>
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
	<?php endif; ?>
	<?php if (!empty($error)): ?>
	<div class="alert alert-danger py-2 alert-dismissible fade show" id="errorAlert">
		<?php echo htmlspecialchars($error); ?>
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
	<?php endif; ?>

	<div class="card admin-card">
		<div class="card-body">
			<div class="d-flex justify-content-between align-items-center mb-2">
				<h5 class="card-title mb-0">Listado de eventos</h5>
				<span class="text-muted small">Total: <?php echo count($eventos); ?></span>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-hover align-middle admin-table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nombre</th>
							<th>Descripción</th>
							<th>Fecha y Hora</th>
							<th>Ubicación</th>
							<th>Estado</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($eventos as $e): ?>
						<tr>
							<td><?php echo (int)$e['id']; ?></td>
							<td><?php echo htmlspecialchars($e['nombre']); ?></td>
							<td>
								<?php 
								$desc = htmlspecialchars($e['descripcion']);
								echo strlen($desc) > 50 ? substr($desc, 0, 50) . '...' : $desc;
								?>
							</td>
							<td>
								<?php 
								$fecha = new DateTime($e['fecha_hora']);
								echo $fecha->format('d/m/Y H:i');
								?>
							</td>
							<td><?php echo htmlspecialchars($e['ubicacion']); ?></td>
							<td>
								<?php $isActivo = strtolower((string)($e['tipo_estado'] ?? '')) === 'activo'; ?>
								<span class="badge rounded-pill <?php echo $isActivo ? 'bg-success' : 'bg-secondary'; ?>">
									<?php echo htmlspecialchars($e['tipo_estado'] ?? ''); ?>
								</span>
							</td>
							<td>
								<div class="d-flex gap-2 justify-content-center">
									<button class="btn btn-admin btn-sm" data-bs-toggle="modal" data-bs-target="#editRow<?php echo (int)$e['id']; ?>">
										<i class="bi bi-pencil"></i>
									</button>
									<form method="POST" onsubmit="return confirm('¿Eliminar evento #<?php echo (int)$e['id']; ?>?');" class="d-inline">
										<input type="hidden" name="accion" value="eliminar">
										<input type="hidden" name="id" value="<?php echo (int)$e['id']; ?>">
										<button class="btn btn-sm btn-danger" type="submit">
											<i class="bi bi-trash"></i>
										</button>
									</form>
								</div>
							</td>
						</tr>
						<!-- Modal editar fila -->
						<div class="modal fade" id="editRow<?php echo (int)$e['id']; ?>" tabindex="-1">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Editar evento #<?php echo (int)$e['id']; ?></h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
									</div>
									<div class="modal-body">
										<form method="POST">
											<input type="hidden" name="accion" value="editar">
											<input type="hidden" name="id" value="<?php echo (int)$e['id']; ?>">
											<div class="row g-3">
												<div class="col-md-6">
													<label class="form-label">Estado</label>
													<select class="form-select" name="id_estado" required>
														<?php foreach ($estados as $est): ?>
															<option value="<?php echo (int)$est['id']; ?>" <?php echo ((int)$est['id'] === (int)$e['id_estado']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($est['tipo_estado']); ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-md-6">
													<label class="form-label">Fecha y Hora</label>
													<input class="form-control" type="datetime-local" name="fecha_hora" value="<?php echo date('Y-m-d\TH:i', strtotime($e['fecha_hora'])); ?>" required>
												</div>
												<div class="col-12">
													<label class="form-label">Nombre del evento</label>
													<input class="form-control" name="nombre" value="<?php echo htmlspecialchars($e['nombre']); ?>" required>
												</div>
												<div class="col-12">
													<label class="form-label">Descripción</label>
													<textarea class="form-control" name="descripcion" rows="4" required><?php echo htmlspecialchars($e['descripcion']); ?></textarea>
												</div>
												<div class="col-12">
													<label class="form-label">Ubicación</label>
													<input class="form-control" name="ubicacion" value="<?php echo htmlspecialchars($e['ubicacion']); ?>" required>
												</div>
											</div>
											<div class="text-end mt-3">
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
	
	// Auto-ocultar mensajes y limpiar URL
	document.addEventListener('DOMContentLoaded', function() {
		const successAlert = document.getElementById('successAlert');
		const errorAlert = document.getElementById('errorAlert');
		
		// Limpiar parámetros de la URL después de mostrar mensajes
		if (successAlert || errorAlert) {
			// Remover parámetros de la URL sin recargar la página
			const url = new URL(window.location);
			url.searchParams.delete('mensaje');
			url.searchParams.delete('error');
			window.history.replaceState({}, document.title, url.toString());
		}
		
		// Auto-ocultar mensajes de éxito después de 5 segundos
		if (successAlert) {
			setTimeout(function() {
				const bsAlert = new bootstrap.Alert(successAlert);
				bsAlert.close();
			}, 5000);
		}
		
		// Auto-ocultar mensajes de error después de 8 segundos
		if (errorAlert) {
			setTimeout(function() {
				const bsAlert = new bootstrap.Alert(errorAlert);
				bsAlert.close();
			}, 8000);
		}
	});
</script>

<footer class="footer text-center mt-1"></footer>
<script src="./js/components/header.js"></script>
<script src="./js/components/footer.js"></script>
<script src="./js/index.js"></script>

<!-- Modal crear evento -->
<div class="modal fade" id="createEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear nuevo evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="accion" value="crear">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="id_estado" required>
                                <?php foreach ($estados as $est): ?>
                                    <option value="<?php echo (int)$est['id']; ?>"><?php echo htmlspecialchars($est['tipo_estado']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha y Hora</label>
                            <input class="form-control" type="datetime-local" name="fecha_hora" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nombre del evento</label>
                            <input class="form-control" name="nombre" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="4" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ubicación</label>
                            <input class="form-control" name="ubicacion" required>
                        </div>
                    </div>
                    <div class="text-end mt-3">
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
