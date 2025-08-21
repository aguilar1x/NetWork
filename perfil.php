<?php
/* 
========================================
PÁGINA DE PERFIL DEL USUARIO
========================================
Esta página muestra y permite editar el perfil
del usuario autenticado
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

// Cargar datos (usuarios + rol)
$userRow = null;
if ($currentUser && isset($currentUser['id'])) {
    $stmt = $conn->prepare("SELECT u.nombre, u.usuario, u.correo, r.nombre AS rol_nombre FROM usuarios u LEFT JOIN rol r ON r.id = u.id_rol WHERE u.id = ?");
    $stmt->bind_param('i', $currentUser['id']);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        $userRow = $res->fetch_assoc();
    }
}

$displayName = $userRow['nombre'] ?? ($currentUser['nombre'] ?? '');
$displayRole = $userRow['rol_nombre'] ?? ($currentUser['rol'] ?? '');
$initial = strtoupper(substr($displayName, 0, 1));
?>

<!DOCTYPE html>
<html>

<head>
    <title>NetWork - Mi Perfil</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header></header>

    <section class="learn-hero-profile">
        <div class="container learn-content text-center">
            <h1 class="display-4 fw-bold">Mi Perfil</h1>
            <p class="lead">Gestiona tu información personal y configuración de cuenta</p>
        </div>
    </section>

    <section class="container">
        <div class="layout-container">
            <aside class="sidebar info-section">
                <i class="bi bi-gear settings-icon" role="button" data-bs-toggle="modal" data-bs-target="#settingsModal"></i>
                
                <section>
                    <h3 class="text-center">Información Personal</h3>
                </section>

                <hr class="my-3">

                <section class="mt-4">
                    <div class="pb-3">
                        <label class="form-label fw-bold">Nombre Completo</label>
                        <p id="nombreUsuario"><?php echo htmlspecialchars($userRow['nombre'] ?? $currentUser['nombre'] ?? ''); ?></p>
                    </div>
                    <div class="pb-3">
                        <label class="form-label fw-bold">Usuario</label>
                        <p id="usuarioLogin"><?php echo htmlspecialchars($userRow['usuario'] ?? $currentUser['usuario'] ?? ''); ?></p>
                    </div>
                    <div class="pb-3">
                        <label class="form-label fw-bold">Rol</label>
                        <p id="rolUsuario"><?php echo htmlspecialchars($userRow['rol_nombre'] ?? ($currentUser['rol'] ?? '')); ?></p>
                    </div>
                    <div class="pb-3">
                        <label class="form-label fw-bold">Correo electrónico</label>
                        <p id="correoUsuario"><?php echo htmlspecialchars($userRow['correo'] ?? '—'); ?></p>
                    </div>
                    <div class="pb-3">
                        <label class="form-label fw-bold">Edad</label>
                        <p id="edadUsuario">—</p>
                    </div>
                    <div class="pb-3">
                        <label class="form-label fw-bold">Ubicación</label>
                        <p id="ubicacionUsuario">—</p>
                    </div>
                </section>
            </aside>

            <main class="flex-grow-1 pb-5">
                <div class="container mt-2">
                    <div class="row g-4 align-items-stretch">
                        <div class="col-md-7">
                            <div class="learn-category-card h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="mb-0">Configuración de Notificaciones</h4>
                                    <span class="text-muted small">Email</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center border rounded-3 p-3">
                                            <div>
                                                <div class="fw-semibold">Nuevos cursos disponibles</div>
                                                <div class="text-muted small">Resumen semanal</div>
                                            </div>
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" id="notifCursos" checked>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center border rounded-3 p-3">
                                            <div>
                                                <div class="fw-semibold">Ofertas de trabajo freelance</div>
                                                <div class="text-muted small">Propuestas relevantes</div>
                                            </div>
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" id="notifTrabajos" checked>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center border rounded-3 p-3">
                                            <div>
                                                <div class="fw-semibold">Nuevas conexiones en la red</div>
                                                <div class="text-muted small">Alertas de invitaciones</div>
                                            </div>
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" id="notifConexiones">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center border rounded-3 p-3">
                                            <div>
                                                <div class="fw-semibold">Recordatorios de eventos</div>
                                                <div class="text-muted small">Antes de iniciar</div>
                                            </div>
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" id="notifEventos" checked>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="learn-benefit-card mb-4">
                                <i class="bi bi-shield-check learn-benefit-icon"></i>
                                <h4 class="mb-3">Seguridad</h4>
                                <p class="text-muted mb-4">Tu cuenta está protegida con autenticación segura.</p>
                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                    Cambiar Contraseña
                                </button>
                            </div>
                            <div class="learn-benefit-card">
                                <i class="bi bi-download learn-benefit-icon"></i>
                                <h4 class="mb-3">Datos</h4>
                                <p class="text-muted mb-4">Descarga una copia de toda tu información.</p>
                                <button class="btn btn-outline-secondary" onclick="descargarDatos()">
                                    Descargar Datos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </section>

    <!-- Modal de Configuración -->
    <div class="modal fade" id="settingsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Información Personal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm">
                        <div class="mb-3">
                            <label for="editNombre" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="editNombre" value="<?php echo htmlspecialchars($currentUser['nombre']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="editCorreo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="editCorreo" placeholder="tu@email.com">
                        </div>
                        <div class="mb-3">
                            <label for="editEdad" class="form-label">Edad</label>
                            <input type="number" class="form-control" id="editEdad" min="18" max="99">
                        </div>
                        <div class="mb-3">
                            <label for="editUbicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="editUbicacion" placeholder="Ciudad, País">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarPerfil()">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Cambio de Contraseña -->
    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Contraseña Actual</label>
                            <input type="password" class="form-control" id="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="confirmPassword" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="cambiarContrasena()">Cambiar Contraseña</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer text-center mt-1"></footer>

    <script>
        // Pasar datos del usuario a JavaScript
        window.currentUser = {
            nombre: '<?php echo htmlspecialchars($currentUser['nombre']); ?>'
        };
    </script>
    <script src="./js/components/footer.js"></script>
    <script src="./js/components/header.js"></script>
    <script src="./js/index.js"></script>
    <script src="./js/perfil.js"></script>
</body>

</html>
