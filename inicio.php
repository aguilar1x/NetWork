<?php
/* 
==============================
PÁGINA DE INICIO
==============================
Esta es la página de bienvenida que se muestra 
cuando el usuario está autenticado
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
    <title>NetWork - Inicio</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <section class="img-fondo">
        <nav class="navbar-inicio">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h4 class="text-white mb-0">Bienvenido, <?php echo htmlspecialchars($currentUser['nombre']); ?>!</h4>
                </div>
                <ul class="d-flex gap-3 list-unstyled mb-0">
                        <a class="nav-link-inicio" href="logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Contenido principal de bienvenida -->
        <div class="container text-center text-white py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">¡Bienvenido a NetWork!</h1>
                    <p class="lead mb-5">Tu plataforma integral para aprender, trabajar como freelancer y hacer networking profesional.</p>
                    
                    <div class="row g-4 mt-4">
                        <div class="col-md-4">
                            <div class="bg-white bg-opacity-10 p-4 rounded-3 h-100">
                                <h4 class="mb-3">📚 Aprender</h4>
                                <p class="mb-3">Explora cursos y desarrolla nuevas habilidades</p>
                                <a href="aprender.php" class="btn btn-outline-light">Explorar Cursos</a>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="bg-white bg-opacity-10 p-4 rounded-3 h-100">
                                <h4 class="mb-3">💼 Freelance</h4>
                                <p class="mb-3">Encuentra proyectos y oportunidades de trabajo</p>
                                <a href="freelance.php" class="btn btn-outline-light">Ver Ofertas</a>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="bg-white bg-opacity-10 p-4 rounded-3 h-100">
                                <h4 class="mb-3">🌐 Network</h4>
                                <p class="mb-3">Conecta con otros profesionales</p>
                                <a href="network.php" class="btn btn-outline-light">Hacer Networking</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
      <script src="./js/index.js"></script>
  </body>

</html>
