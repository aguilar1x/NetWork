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
<html lang="es">
<head>
    <title>Registro - NetWork</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="registro-container">
        <div class="registro-card">
            <div class="registro-header">
                <h2>Crear Cuenta</h2>
                <p>Únete a NetWork y conecta con el mundo profesional</p>
            </div>

            <!-- Mostrar mensaje de error si existe -->
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <!-- Mostrar mensaje de éxito si existe -->
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <!-- Formulario de registro -->
            <form method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required 
                           value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" placeholder="Tu nombre completo">
                </div>

                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" class="form-control" required 
                           value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>" placeholder="Nombre de usuario único">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" placeholder="tu@email.com">
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" class="form-control" required 
                           placeholder="Mínimo 6 caracteres">
                </div>

                <div class="form-group">
                    <label for="confirmar_contrasena">Confirmar Contraseña:</label>
                    <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" class="form-control" required 
                           placeholder="Confirma tu contraseña">
                </div>

                <button type="submit" class="btn btn-registro">Crear Cuenta</button>
            </form>

            <div class="login-link">
                <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>

    <script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>
