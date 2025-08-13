<?php
/* 
========================================
PÁGINA DE LOGIN DEL SISTEMA
========================================
Esta página permite a los usuarios iniciar sesión
Si ya están logueados, los redirige a la página principal
*/

require_once 'app/models/user.php';

// Si el usuario ya está logueado, llevarlo directo al inicio
if (User::isLoggedIn()) {
    header('Location: inicio.php');
    exit();
}

$error = '';

// Procesar el formulario de login cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    
    // Validar que se llenaron ambos campos
    if (!empty($usuario) && !empty($contrasena)) {
        $user = new User();
        
        // Intentar hacer login
        if ($user->login($usuario, $contrasena)) {
            // Login exitoso, redirigir a la página principal
            header('Location: inicio.php');
            exit();
        } else {
            // Login falló, mostrar error
            $error = 'Usuario o contraseña incorrectos';
        }
    } else {
        $error = 'Complete todos los campos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Login - Network</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h2></h2>
            <p>Iniciar Sesión</p>
        </div>

        <!-- Mostrar mensaje de error si existe -->
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Formulario de login -->
        <form method="POST">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required 
                       value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>

            <button type="submit" class="btn-login">Ingresar</button>
        </form>
        
        <div class="text-center mt-3">
            <p>¿No tienes cuenta? <a href="registro.php" class="login-link">Regístrate aquí</a></p>
        </div>
    </div>
</body>
</html>