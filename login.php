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
<html>

<head>
    <title>Login - NetWork</title>
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
                <a href="registro.php" class="auth-nav-link">
                    <strong>Regístrate</strong>
                </a>
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="auth-main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 col-lg-4">
                    <div class="auth-card-clean">
                        <div class="text-center mb-4">
                            <h1 class="auth-title-clean">Iniciar Sesión</h1>
                            <p class="auth-subtitle-clean">Accede a tu cuenta de NetWork</p>
                        </div>

                        <!-- Mostrar mensaje de error si existe -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="POST" class="auth-form-clean">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">
                                    <i class="bi bi-person me-2 text-primary"></i>Usuario
                                </label>
                                <input type="text" id="usuario" name="usuario" class="form-control form-control-clean" 
                                       placeholder="Ingresa tu usuario" required 
                                       value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>" />
                            </div>

                            <div class="mb-4">
                                <label for="contrasena" class="form-label">
                                    <i class="bi bi-lock me-2 text-primary"></i>Contraseña
                                </label>
                                <input type="password" id="contrasena" name="contrasena" class="form-control form-control-clean" 
                                       placeholder="Ingresa tu contraseña" required />
                            </div>

                            <button type="submit" class="btn btn-primary-clean w-100 mb-3">
                                Iniciar Sesión
                            </button>
                        </form>

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                ¿No tienes cuenta? 
                                <a href="registro.php" class="link-primary">Regístrate aquí</a>
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