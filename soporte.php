<?php
/* 
========================================
PÁGINA DE SOPORTE
========================================
Esta página proporciona ayuda y soporte
a los usuarios de la plataforma
*/

require_once 'app/models/user.php';

// Verificar si el usuario está logueado
if (!User::isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Obtener información del usuario actual
$currentUser = User::getCurrentUser();

// Procesar formulario de contacto
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_mensaje'])) {
    // Aquí se procesaría el mensaje de contacto
    $mensaje = 'Mensaje enviado correctamente. Te responderemos pronto.';
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>NetWork - Centro de Soporte</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
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
                    <li class="nav-item">
                        <a class="nav-link active" href="soporte.php">Soporte</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($currentUser['nombre']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="perfil.php">Mi Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero -->
        <section class="learn-hero soporte">
            <div class="container learn-content text-center">
                <h1 class="display-4 fw-bold mb-3">Centro de Soporte</h1>
                <p class="lead">Encuentra respuestas, aprende a usar NetWork o repórtanos un problema.</p>
            </div>
        </section>

        <div class="container my-5">
            <?php if ($mensaje): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($mensaje); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Preguntas frecuentes -->
                <div class="col-lg-8">
                    <h2 class="text-center mb-4">Preguntas Frecuentes</h2>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    ¿Cómo me registro en NetWork?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Para registrarte en NetWork, haz clic en el botón "Regístrate" en la página principal. 
                                    Completa el formulario con tu información personal y sigue las instrucciones para 
                                    verificar tu cuenta.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    ¿Cómo puedo encontrar trabajo freelance?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ve a la sección "Freelance" en el menú principal. Ahí podrás filtrar ofertas por 
                                    categoría, nivel de experiencia y presupuesto. También puedes crear alertas para 
                                    recibir notificaciones de nuevas ofertas que coincidan con tu perfil.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                    ¿Los cursos tienen certificación?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Sí, todos nuestros cursos incluyen un certificado de finalización reconocido por la 
                                    industria. Una vez completes el 100% del curso y apruebes las evaluaciones, 
                                    recibirás tu certificado digital.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                    ¿Cómo funciona el networking?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    En la sección "Network" puedes conectar con otros profesionales, buscar por ubicación 
                                    o área de expertise, y participar en eventos de networking. También puedes unirte a 
                                    grupos de interés y participar en discusiones profesionales.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                                    ¿Cómo cambio mi contraseña?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ve a tu perfil haciendo clic en tu nombre en la esquina superior derecha, luego 
                                    selecciona "Mi Perfil". En la sección de seguridad encontrarás la opción para 
                                    cambiar tu contraseña.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario de contacto -->
                <div class="col-lg-4">
                    <div class="learn-category-card h-100">
                        <h3 class="text-center mb-4">¿Necesitas más ayuda?</h3>
                        <p class="text-center text-muted mb-4">
                            Si no encontraste la respuesta que buscas, contáctanos directamente.
                        </p>

                        <form method="POST">
                            <input type="hidden" name="enviar_mensaje" value="1">
                            
                            <div class="mb-3">
                                <label for="asunto" class="form-label">Asunto</label>
                                <select class="form-select" id="asunto" name="asunto" required>
                                    <option value="">Selecciona un tema...</option>
                                    <option value="tecnico">Problema técnico</option>
                                    <option value="cuenta">Problema con mi cuenta</option>
                                    <option value="cursos">Consulta sobre cursos</option>
                                    <option value="freelance">Consulta sobre freelance</option>
                                    <option value="facturacion">Problemas de facturación</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="mensaje" class="form-label">Mensaje</label>
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="4" 
                                         placeholder="Describe tu problema o consulta..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="prioridad" class="form-label">Prioridad</label>
                                <select class="form-select" id="prioridad" name="prioridad">
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send me-2"></i>Enviar Mensaje
                            </button>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <h5>Otras formas de contacto</h5>
                            <div class="d-flex justify-content-center gap-3 mt-3">
                                <a href="mailto:soporte@network.com" class="btn btn-outline-primary">
                                    <i class="bi bi-envelope"></i>
                                </a>
                                <a href="tel:+1234567890" class="btn btn-outline-primary">
                                    <i class="bi bi-telephone"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary">
                                    <i class="bi bi-chat"></i>
                                </a>
                            </div>
                            <small class="text-muted d-block mt-2">
                                Horario de atención: Lun-Vie 9:00-18:00
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recursos adicionales -->
            <section class="mt-5">
                <h2 class="text-center mb-4">Recursos Útiles</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="learn-benefit-card text-center">
                            <i class="bi bi-book learn-benefit-icon"></i>
                            <h4 class="mb-3">Guía de Usuario</h4>
                            <p class="text-muted mb-4">Aprende paso a paso cómo usar todas las funciones de NetWork.</p>
                            <a href="#" class="btn btn-outline-primary">Ver Guía</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="learn-benefit-card text-center">
                            <i class="bi bi-play-circle learn-benefit-icon"></i>
                            <h4 class="mb-3">Video Tutoriales</h4>
                            <p class="text-muted mb-4">Videos explicativos sobre las principales características.</p>
                            <a href="#" class="btn btn-outline-primary">Ver Videos</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="learn-benefit-card text-center">
                            <i class="bi bi-people learn-benefit-icon"></i>
                            <h4 class="mb-3">Comunidad</h4>
                            <p class="text-muted mb-4">Únete a nuestra comunidad y resuelve dudas con otros usuarios.</p>
                            <a href="#" class="btn btn-outline-primary">Unirse</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script src="./js/soporte.js"></script>
</body>

</html>
