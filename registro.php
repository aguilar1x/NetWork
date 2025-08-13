<?php
/* 
========================================
PÁGINA DE REGISTRO DEL SISTEMA
========================================
Esta página permite a los usuarios registrarse
Si ya están logueados, los redirige a inicio
*/

require_once 'app/models/user.php';
require_once 'app/config/db.php';

// Si el usuario ya está logueado, llevarlo al inicio
if (User::isLoggedIn()) {
    header('Location: inicio.php');
    exit();
}

$error = '';
$success = '';

// Procesar el formulario de registro cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';
    
    // Validaciones
    if (empty($nombre) || empty($usuario) || empty($email) || empty($contrasena) || empty($confirmar_contrasena)) {
        $error = 'Todos los campos son obligatorios';
    } elseif ($contrasena !== $confirmar_contrasena) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($contrasena) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El email no es válido';
    } else {
        // Intentar registrar usuario
        try {
            $db = Database::connect();
            
            // Verificar si el usuario o email ya existen
            $stmt = $db->prepare("SELECT id FROM usuarios WHERE usuario = ? OR email = ?");
            $stmt->bind_param("ss", $usuario, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = 'El usuario o email ya están registrados';
            } else {
                // Registrar nuevo usuario
                $password_hash = password_hash($contrasena, PASSWORD_DEFAULT);
                $rol = 'usuario'; // rol por defecto
                
                $stmt = $db->prepare("INSERT INTO usuarios (nombre, usuario, email, contrasena, rol, fecha_registro) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param("sssss", $nombre, $usuario, $email, $password_hash, $rol);
                
                if ($stmt->execute()) {
                    $success = 'Usuario registrado exitosamente. Ahora puedes iniciar sesión.';
                } else {
                    $error = 'Error al registrar usuario. Inténtalo de nuevo.';
                }
            }
        } catch (Exception $e) {
            $error = 'Error en el servidor. Inténtalo más tarde.';
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registro - NetWork</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="auth-body">
    <!-- Header -->
    <header class="auth-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="index.php" class="auth-brand">
                    NetWork
                </a>
                <a href="login.php" class="auth-nav-link">
                    <strong>Inicia Sesión</strong>
                </a>
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="auth-main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="auth-card-clean">
                        <div class="text-center mb-4">
                            <h1 class="auth-title-clean">Crear tu cuenta</h1>
                            <p class="auth-subtitle-clean">Únete a NetWork y conecta con profesionales</p>
                        </div>

                        <!-- Mostrar mensaje de error si existe -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <!-- Mostrar mensaje de éxito si existe -->
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <form method="POST" class="auth-form-clean">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="bi bi-person me-2 text-primary"></i>Nombre completo
                                </label>
                                <input type="text" id="nombre" name="nombre" class="form-control form-control-clean" 
                                       placeholder="Tu nombre completo" required 
                                       value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" />
                            </div>

                            <div class="mb-3">
                                <label for="usuario" class="form-label">
                                    <i class="bi bi-at me-2 text-primary"></i>Nombre de usuario
                                </label>
                                <input type="text" id="usuario" name="usuario" class="form-control form-control-clean" 
                                       placeholder="Nombre de usuario único" required 
                                       value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>" />
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-2 text-primary"></i>Correo electrónico
                                </label>
                                <input type="email" id="email" name="email" class="form-control form-control-clean" 
                                       placeholder="ejemplo@correo.com" required 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" />
                            </div>

                            <div class="mb-3">
                                <label for="contrasena" class="form-label">
                                    <i class="bi bi-lock me-2 text-primary"></i>Contraseña
                                </label>
                                <input type="password" id="contrasena" name="contrasena" class="form-control form-control-clean" 
                                       placeholder="Mínimo 6 caracteres" minlength="6" required />
                            </div>

                            <div class="mb-4">
                                <label for="confirmar_contrasena" class="form-label">
                                    <i class="bi bi-lock me-2 text-primary"></i>Confirmar contraseña
                                </label>
                                <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" class="form-control form-control-clean" 
                                       placeholder="Repite tu contraseña" minlength="6" required />
                            </div>

                            <button type="submit" class="btn btn-primary-clean w-100 mb-3">
                                Registrarse
                            </button>
                        </form>

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                ¿Ya tienes cuenta? 
                                <a href="login.php" class="link-primary">Inicia sesión aquí</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="./js/bootstrap.bundle.min.js"></script>
</body>

</html>
