<?php
/* 
========================================
PÁGINA DE CURSOS Y APRENDIZAJE
========================================
Esta página muestra los cursos disponibles y permite
a los usuarios explorar y buscar contenido educativo
*/

require_once 'app/models/user.php';

// Verificar si el usuario está logueado
if (!User::isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Obtener información del usuario actual
$currentUser = User::getCurrentUser();

// Datos estáticos hasta tener la db hecha
$cursos = [
    [
        'id' => 1,
        'titulo' => 'React desde Cero a Experto',
        'categoria' => 'Desarrollo Web',
        'imagen' => './img/react.jpg',
        'duracion' => '15 horas',
        'rating' => '4.8',
        'estudiantes' => '2.5k',
        'precio' => '$49.99',
        'progreso' => '75',
        'descripcion' => 'Domina React.js y crea aplicaciones web modernas con las mejores prácticas.'
    ],
    [
        'id' => 2,
        'titulo' => 'Diseño UX/UI con Figma',
        'categoria' => 'Diseño UX/UI',
        'imagen' => './img/figma.jpg',
        'duracion' => '12 horas',
        'rating' => '4.6',
        'estudiantes' => '1.8k',
        'precio' => '$39.99',
        'progreso' => '60',
        'descripcion' => 'Aprende a crear interfaces modernas y experiencias de usuario excepcionales.'
    ],
    [
        'id' => 3,
        'titulo' => 'Marketing Digital Completo',
        'categoria' => 'Marketing Digital',
        'imagen' => './img/marketingdigital.jpg',
        'duracion' => '20 horas',
        'rating' => '4.9',
        'estudiantes' => '3.2k',
        'precio' => '$59.99',
        'progreso' => '85',
        'descripcion' => 'Estrategias actualizadas de marketing digital y growth hacking.'
    ]
];

// Filtros de búsqueda
$categoria_filtro = $_GET['categoria'] ?? '';
$busqueda = $_GET['busqueda'] ?? '';
?>

<!DOCTYPE html>
<html>

<head>
    <title>NetWork - Aprender</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <!-- Navegación -->
    <header></header>

    <main>
        <!-- Hero Section -->
        <section class="learn-hero">
            <div class="container learn-content">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-3 fw-bold mb-4">Descubre tu potencial digital</h1>
                        <p class="lead mb-5">Explora cursos diseñados por expertos para impulsar tu carrera en tecnología, diseño y negocios digitales.</p>
                        <form class="d-flex gap-3 mb-4" method="GET">
                            <input type="search" name="busqueda" class="learn-search" placeholder="¿Qué habilidad quieres dominar?" value="<?php echo htmlspecialchars($busqueda); ?>">
                            <button class="learn-btn-primary px-4" type="submit">
                                <i class="bi bi-search me-2"></i>Explorar
                            </button>
                        </form>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block">
                        <img src="./img/man-horizon.jpg" alt="Aprendizaje" class="img-fluid rounded-4" style="transform: perspective(1000px) rotateY(-15deg); box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                    </div>
                </div>
            </div>
        </section>

        <!-- Categorías -->
        <section class="learn-categories">
            <div class="container">
                <h2 class="text-center h1 mb-5">Rutas de aprendizaje</h2>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="learn-category-card h-100">
                            <i class="bi bi-code-square learn-category-icon"></i>
                            <h3 class="h4 mb-3">Desarrollo Web</h3>
                            <p class="mb-4 text-muted">Domina las tecnologías más demandadas en desarrollo frontend y backend.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>120+ cursos</span>
                                <a href="?categoria=desarrollo-web" class="text-decoration-none">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="learn-category-card h-100">
                            <i class="bi bi-brush learn-category-icon"></i>
                            <h3 class="h4 mb-3">Diseño UX/UI</h3>
                            <p class="mb-4 text-muted">Crea experiencias digitales intuitivas y atractivas.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>85+ cursos</span>
                                <a href="?categoria=diseno-ux-ui" class="text-decoration-none">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="learn-category-card h-100">
                            <i class="bi bi-graph-up learn-category-icon"></i>
                            <h3 class="h4 mb-3">Marketing Digital</h3>
                            <p class="mb-4 text-muted">Estrategias efectivas para el crecimiento digital.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>95+ cursos</span>
                                <a href="?categoria=marketing-digital" class="text-decoration-none">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="learn-category-card h-100">
                            <i class="bi bi-briefcase learn-category-icon"></i>
                            <h3 class="h4 mb-3">Negocios Digitales</h3>
                            <p class="mb-4 text-muted">Transforma ideas en negocios digitales exitosos.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>150+ cursos</span>
                                <a href="?categoria=negocios-digitales" class="text-decoration-none">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cursos -->
        <section class="learn-courses">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h2 class="h1">Cursos destacados</h2>
                    <div class="d-flex gap-3">
                        <select class="learn-search" style="width: auto" onchange="filterCourses(this)">
                            <option value="">Nivel</option>
                            <option>Principiante</option>
                            <option>Intermedio</option>
                            <option>Avanzado</option>
                        </select>
                        <select class="learn-search" style="width: auto" onchange="filterCourses(this)">
                            <option value="">Duración</option>
                            <option>Corto (0-5h)</option>
                            <option>Medio (5-10h)</option>
                            <option>Largo (10h+)</option>
                        </select>
                    </div>
                </div>

                <div class="row g-4" id="coursesContainer">
                    <?php foreach ($cursos as $curso): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="learn-course-card">
                            <div class="learn-course-image">
                                <img src="<?php echo htmlspecialchars($curso['imagen']); ?>" alt="<?php echo htmlspecialchars($curso['titulo']); ?>">
                                <span class="learn-course-badge"><?php echo htmlspecialchars($curso['categoria']); ?></span>
                            </div>
                            <div class="learn-course-content">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted"><i class="bi bi-clock me-2"></i><?php echo htmlspecialchars($curso['duracion']); ?></span>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning me-1"></i>
                                        <span><?php echo htmlspecialchars($curso['rating']); ?> (<?php echo htmlspecialchars($curso['estudiantes']); ?>)</span>
                                    </div>
                                </div>
                                <h5 class="mb-3"><?php echo htmlspecialchars($curso['titulo']); ?></h5>
                                <p class="text-muted mb-4"><?php echo htmlspecialchars($curso['descripcion']); ?></p>
                                <div class="learn-course-progress mb-4">
                                    <div class="learn-course-progress-bar" style="width: <?php echo htmlspecialchars($curso['progreso']); ?>%"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0"><?php echo htmlspecialchars($curso['precio']); ?></span>
                                    <button class="learn-btn-outline" data-bs-toggle="modal" data-bs-target="#courseModal<?php echo $curso['id']; ?>">Ver más</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Beneficios -->
        <section class="learn-benefits">
            <div class="container">
                <h2 class="text-center h1 mb-5">¿Por qué elegir NetWork?</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="learn-benefit-card">
                            <i class="bi bi-laptop learn-benefit-icon"></i>
                            <h4 class="mb-3">Aprendizaje flexible</h4>
                            <p class="text-muted">Estudia a tu ritmo, desde cualquier lugar y en cualquier momento.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="learn-benefit-card">
                            <i class="bi bi-people learn-benefit-icon"></i>
                            <h4 class="mb-3">Instructores expertos</h4>
                            <p class="text-muted">Aprende de profesionales con experiencia real en la industria.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="learn-benefit-card">
                            <i class="bi bi-award learn-benefit-icon"></i>
                            <h4 class="mb-3">Certificación profesional</h4>
                            <p class="text-muted">Obtén certificados reconocidos para impulsar tu carrera.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modales de cursos -->
        <?php foreach ($cursos as $curso): ?>
        <div class="modal fade learn-modal" id="courseModal<?php echo $curso['id']; ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <span class="learn-course-badge mb-2"><?php echo htmlspecialchars($curso['categoria']); ?></span>
                                <h3 class="mb-3"><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                                <div class="d-flex align-items-center mb-4">
                                    <div class="me-4">
                                        <i class="bi bi-star-fill text-warning me-1"></i>
                                        <span><?php echo htmlspecialchars($curso['rating']); ?></span>
                                    </div>
                                    <span class="text-muted"><?php echo htmlspecialchars($curso['estudiantes']); ?> estudiantes</span>
                                </div>
                                <p class="mb-4"><?php echo htmlspecialchars($curso['descripcion']); ?> Este curso te llevará desde los conceptos básicos hasta técnicas avanzadas.</p>
                                
                                <h5 class="mb-3">Lo que aprenderás</h5>
                                <ul class="learn-feature-list list-unstyled">
                                    <li><i class="bi bi-check2-circle"></i>Fundamentos y conceptos básicos</li>
                                    <li><i class="bi bi-check2-circle"></i>Técnicas avanzadas y mejores prácticas</li>
                                    <li><i class="bi bi-check2-circle"></i>Proyectos prácticos y casos reales</li>
                                    <li><i class="bi bi-check2-circle"></i>Certificación al completar el curso</li>
                                </ul>
                            </div>
                            <div class="col-lg-4">
                                <div class="learn-price-card">
                                    <h4 class="mb-4"><?php echo htmlspecialchars($curso['precio']); ?></h4>
                                    <ul class="list-unstyled mb-4">
                                        <li class="mb-3"><i class="bi bi-clock me-2"></i><?php echo htmlspecialchars($curso['duracion']); ?> de video</li>
                                        <li class="mb-3"><i class="bi bi-file-text me-2"></i>5 proyectos prácticos</li>
                                        <li class="mb-3"><i class="bi bi-infinity me-2"></i>Acceso de por vida</li>
                                        <li class="mb-3"><i class="bi bi-award me-2"></i>Certificado</li>
                                    </ul>
                                    <button class="learn-btn-primary w-100 mb-2" onclick="enrollCourse(<?php echo $curso['id']; ?>)">Inscribirme ahora</button>
                                    <button class="learn-btn-outline w-100" onclick="addToCart(<?php echo $curso['id']; ?>)">Añadir al carrito</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </main>

    <!-- Footer -->
    <footer class="footer text-center mt-1"></footer>

    <!-- Scripts -->
    <script>
        // Pasar datos del usuario a JavaScript
        window.currentUser = {
            nombre: '<?php echo htmlspecialchars($currentUser['nombre']); ?>'
        };
    </script>
    <script src="./js/components/header.js"></script>
    <script src="./js/components/footer.js"></script>
    <script src="./js/index.js"></script>
    <script src="./js/aprender.js"></script>
</body>

</html>
