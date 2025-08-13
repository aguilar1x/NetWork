<?php
/* 
========================================
PÁGINA DE INICIO
========================================
Esta es la página principal que muestra
información sobre NetWork y permite acceso
*/

// Verificar si el usuario está logueado para mostrar contenido apropiado
require_once 'app/models/user.php';
$userLoggedIn = User::isLoggedIn();
$currentUser = null;

if ($userLoggedIn) {
    $currentUser = User::getCurrentUser();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>NetWork - Plataforma Profesional</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header>
        <div class="background-img">
            <nav class="navbar-inicio">
                <a class="navbar-brand-inicio" href="inicio.php">NetWork</a>
                <div class="container-fluid d-flex justify-content-end">
                    <ul class="d-flex gap-3 list-unstyled mb-0">
                        <?php if ($userLoggedIn): ?>
                            <!-- Menú para usuarios autenticados -->
                            <li>
                                <a class="nav-link-inicio" href="logout.php">Cerrar Sesión</a>
                            </li>
                        <?php else: ?>
                            <!-- Menú para visitantes -->
                            <li>
                                <a class="nav-link-inicio" href="registro.php">Regístrate</a>
                            </li>
                            <li>
                                <a class="nav-link-inicio" href="login.php">Inicia Sesión</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
            <div class="container">
                <?php if ($userLoggedIn): ?>
                    <h2 class="img-text">
                        ¡Bienvenido de nuevo, <?php echo htmlspecialchars($currentUser['nombre']); ?>! 
                        Continúa construyendo tu futuro profesional.
                    </h2>
                <?php else: ?>
                    <h2 class="img-text">
                        ¡Únete a nuestra comunidad y empieza a construir tu futuro profesional hoy mismo!
                    </h2>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="index">
        <?php if ($userLoggedIn): ?>
            <!-- Contenido para usuarios autenticados -->
            <section class="index-network">
                <div class="container text-center">
                    <h1>¡Continúa tu crecimiento profesional!</h1>
                    <p class="main-text mt-5">
                        Explora nuevas oportunidades, aprende habilidades demandadas y conecta con 
                        profesionales de tu industria.
                    </p>
                    <div class="d-flex gap-3 justify-content-center mt-5">
                        <a class="learn-btn-primary" href="aprender.php" role="button">
                            <i class="bi bi-graduation-cap me-2"></i>Ver Cursos
                        </a>
                        <a class="learn-btn-outline" href="freelance.php" role="button">
                            <i class="bi bi-briefcase me-2"></i>Buscar Proyectos
                        </a>
                        <a class="learn-btn-outline" href="network.php" role="button">
                            <i class="bi bi-people me-2"></i>Hacer Networking
                        </a>
                    </div>
                </div>
            </section>

            <section class="index-benefits">
                <div class="container">
                    <h2 class="text-center mb-5">Tus estadísticas</h2>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="learn-benefit-card text-center">
                                <i class="bi bi-graph-up learn-benefit-icon"></i>
                                <h4 class="mb-3">Tu Progreso</h4>
                                <div class="h2 text-primary mb-2">3</div>
                                <p class="text-muted">Cursos completados</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="learn-benefit-card text-center">
                                <i class="bi bi-people learn-benefit-icon"></i>
                                <h4 class="mb-3">Tu Red</h4>
                                <div class="h2 text-primary mb-2">47</div>
                                <p class="text-muted">Conexiones profesionales</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="learn-benefit-card text-center">
                                <i class="bi bi-briefcase learn-benefit-icon"></i>
                                <h4 class="mb-3">Proyectos</h4>
                                <div class="h2 text-primary mb-2">12</div>
                                <p class="text-muted">Proyectos completados</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php else: ?>
            <!-- Contenido para visitantes -->
            <section class="index-network">
                <div class="container text-center">
                    <h1>¿Qué es NetWork?</h1>
                    <p class="main-text mt-5">
                        NetWork es una aplicación web diseñada para mejorar la empleabilidad en los jóvenes.
                        Su objetivo es proporcionarles un espacio dedicado para construir sus perfiles
                        profesionales desde cero. Esta plataforma combina características de una red social
                        profesional, un tablón de anuncios de trabajos freelance y un módulo de mentoría y
                        capacitación, todo dentro de un entorno accesible y seguro.
                    </p>
                    <h3 class="mt-5">¿Te interesa?</h3>
                    <div class="d-flex gap-3 justify-content-center mt-5">
                        <a class="learn-btn-primary" href="registro.php" role="button">Regístrate Gratis</a>
                        <a class="learn-btn-outline" href="login.php" role="button">Inicia Sesión</a>
                    </div>
                </div>
            </section>

            <section class="index-benefits">
                <div class="container">
                    <h2 class="text-center mb-5">¿Por qué elegir NetWork?</h2>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="learn-benefit-card text-center">
                                <i class="bi bi-graduation-cap learn-benefit-icon"></i>
                                <h4 class="mb-3">Aprende Nuevas Habilidades</h4>
                                <p class="text-muted">
                                    Accede a cursos especializados en tecnología, diseño, marketing y más.
                                    Desarrolla las competencias que demanda el mercado laboral actual.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="learn-benefit-card text-center">
                                <i class="bi bi-briefcase learn-benefit-icon"></i>
                                <h4 class="mb-3">Encuentra Trabajo Freelance</h4>
                                <p class="text-muted">
                                    Conecta con empresas que buscan talento. Explora proyectos freelance
                                    que se adapten a tu experiencia y horario disponible.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="learn-benefit-card text-center">
                                <i class="bi bi-people learn-benefit-icon"></i>
                                <h4 class="mb-3">Haz Networking</h4>
                                <p class="text-muted">
                                    Construye tu red profesional conectando con otros especialistas,
                                    mentores y emprendedores en tu área de interés.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-4">
                        <div class="col-md-6">
                            <div class="learn-category-card h-100">
                                <i class="bi bi-shield-check learn-category-icon"></i>
                                <h3 class="h4 mb-3">Plataforma Segura</h3>
                                <p class="mb-4 text-muted">
                                    Tu información está protegida con los más altos estándares de seguridad.
                                    Ambiente confiable para desarrollar tu carrera profesional.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="learn-category-card h-100">
                                <i class="bi bi-clock learn-category-icon"></i>
                                <h3 class="h4 mb-3">Flexibilidad Total</h3>
                                <p class="mb-4 text-muted">
                                    Aprende y trabaja a tu ritmo. Accede desde cualquier dispositivo,
                                    en cualquier momento que te sea conveniente.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <h3 class="mb-4">¿Listo para comenzar?</h3>
                        <p class="lead mb-4">Únete a miles de profesionales que ya están construyendo su futuro con NetWork</p>
                        <a class="learn-btn-primary btn-lg" href="registro.php" role="button">
                            Crear Cuenta Gratuita <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Testimonios -->
            <section class="learn-categories">
                <div class="container">
                    <h2 class="text-center mb-5">Lo que dicen nuestros usuarios</h2>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="learn-course-card text-center">
                                <div class="learn-course-content">
                                    <div class="mb-3">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    </div>
                                    <p class="mb-3">"NetWork me ayudó a encontrar mi primer trabajo como desarrollador. Los cursos son excelentes y la comunidad muy activa."</p>
                                    <h6 class="mb-0">- María González</h6>
                                    <small class="text-muted">Desarrolladora Frontend</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="learn-course-card text-center">
                                <div class="learn-course-content">
                                    <div class="mb-3">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    </div>
                                    <p class="mb-3">"Gracias a NetWork conseguí varios proyectos freelance que me permitieron trabajar mientras estudiaba."</p>
                                    <h6 class="mb-0">- Carlos Ruiz</h6>
                                    <small class="text-muted">Diseñador UX/UI</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="learn-course-card text-center">
                                <div class="learn-course-content">
                                    <div class="mb-3">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    </div>
                                    <p class="mb-3">"La plataforma es muy intuitiva y me conectó con profesionales de mi área. Altamente recomendada."</p>
                                    <h6 class="mb-0">- Ana Torres</h6>
                                    <small class="text-muted">Marketing Digital</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>NetWork</h5>
                    <p>Tu plataforma para el crecimiento profesional y el networking efectivo.</p>
                    <div class="social-links">
                        <a href="#" class="me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="me-2"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="me-2"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="me-2"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h6>Plataforma</h6>
                    <ul class="list-unstyled">
                        <?php if (!$userLoggedIn): ?>
                            <li><a href="registro.php">Registrarse</a></li>
                            <li><a href="login.php">Iniciar Sesión</a></li>
                        <?php endif; ?>
                        <li><a href="aprender.php">Cursos</a></li>
                        <li><a href="freelance.php">Freelance</a></li>
                    </ul>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h6>Recursos</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Guías</a></li>
                        <li><a href="#">Webinars</a></li>
                        <li><a href="#">Eventos</a></li>
                    </ul>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h6>Soporte</h6>
                    <ul class="list-unstyled">
                        <li><a href="soporte.php">Centro de Ayuda</a></li>
                        <li><a href="#">Contacto</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Estado del Sistema</a></li>
                    </ul>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h6>Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">Términos de Uso</a></li>
                        <li><a href="#">Privacidad</a></li>
                        <li><a href="#">Cookies</a></li>
                        <li><a href="#">Seguridad</a></li>
                    </ul>
                </div>
            </div>
            
            <hr>
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">© 2024 NetWork. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">Hecho con ❤️ para impulsar tu carrera profesional</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="./js/index.js"></script>
</body>

</html>
