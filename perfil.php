<?php
/* 
========================================
PÁGINA DE PERFIL DEL USUARIO
========================================
Esta página muestra y permite editar el perfil
del usuario autenticado
*/

require_once 'app/models/user.php';

// Verificar si el usuario está logueado
if (!User::isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Obtener información del usuario actual
$currentUser = User::getCurrentUser();
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
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="inicio.php">NetWork</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="inicio.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aprender.php">Aprender</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="freelance.php">Freelance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="network.php">Network</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($currentUser['nombre']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="perfil.php">Mi Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="learn-hero-profile">
        <div class="container learn-content text-center">
            <h1 class="display-4 fw-bold">Mi Perfil</h1>
            <p class="lead">Gestiona tu información personal y configuración de cuenta</p>
        </div>
    </section>

    <div class="container">
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
                        <p id="nombreUsuario"><?php echo htmlspecialchars($currentUser['nombre']); ?></p>
                    </div>
                    <div class="pb-3">
                        <label class="form-label fw-bold">Usuario</label>
                        <p id="usuarioLogin"><?php echo htmlspecialchars($currentUser['usuario']); ?></p>
                    </div>
                    <div class="pb-3">
                        <label class="form-label fw-bold">Rol</label>
                        <p id="rolUsuario"><?php echo htmlspecialchars($currentUser['rol']); ?></p>
                    </div>
                    <div class="pb-3">
                        <label class="form-label fw-bold">Correo electrónico</label>
                        <p id="correoUsuario">—</p>
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

            <main class="flex-grow-1">
                <section class="learn-hero">
                    <div class="container learn-content text-center">
                        <h2 class="h3 fw-bold mb-4">Configuración de Notificaciones</h2>
                        <p class="lead mb-4">Personaliza cómo y cuándo recibes notificaciones</p>
                    </div>
                </section>

                <div class="container mt-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="learn-category-card">
                                <h4 class="mb-3">Notificaciones por Email</h4>
                                
                                <div class="position-relative mb-3">
                                    <p class="mb-1">Nuevos cursos disponibles</p>
                                    <div class="form-check form-switch switch1">
                                        <input class="form-check-input" type="checkbox" id="notifCursos" checked>
                                    </div>
                                </div>

                                <div class="position-relative mb-3">
                                    <p class="mb-1">Ofertas de trabajo freelance</p>
                                    <div class="form-check form-switch switch2">
                                        <input class="form-check-input" type="checkbox" id="notifTrabajos" checked>
                                    </div>
                                </div>

                                <div class="position-relative mb-3">
                                    <p class="mb-1">Nuevas conexiones en la red</p>
                                    <div class="form-check form-switch switch3">
                                        <input class="form-check-input" type="checkbox" id="notifConexiones">
                                    </div>
                                </div>

                                <div class="position-relative mb-3">
                                    <p class="mb-1">Recordatorios de eventos</p>
                                    <div class="form-check form-switch switch4">
                                        <input class="form-check-input" type="checkbox" id="notifEventos" checked>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="learn-benefit-card">
                                <i class="bi bi-shield-check learn-benefit-icon"></i>
                                <h4 class="mb-3">Seguridad</h4>
                                <p class="text-muted mb-4">Tu cuenta está protegida con autenticación segura.</p>
                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                    Cambiar Contraseña
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
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
    </div>

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

    <script src="./js/perfil.js"></script>
    <script>
        function guardarPerfil() {
            // Aquí implementarías la lógica para guardar el perfil
            alert('Perfil actualizado correctamente (Función a implementar)');
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
        }

        function cambiarContrasena() {
            const current = document.getElementById('currentPassword').value;
            const newPass = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmPassword').value;

            if (newPass !== confirm) {
                alert('Las contraseñas no coinciden');
                return;
            }

            // Aquí implementarías la lógica para cambiar la contraseña
            alert('Contraseña cambiada correctamente (Función a implementar)');
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('passwordModal')).hide();
        }

        function descargarDatos() {
            // Aquí implementarías la lógica para descargar datos
            alert('Preparando descarga de datos... (Función a implementar)');
        }
    </script>
</body>

</html>
