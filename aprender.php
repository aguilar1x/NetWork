<?php
/* 
========================================
PÁGINA DE CURSOS Y APRENDIZAJE
========================================
Esta página muestra los cursos disponibles y permite
a los usuarios explorar y buscar contenido educativo
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
$es_admin = ($currentUser['rol'] === 1); // Solo los admin pueden crear, editar y eliminar

// Procesar las acciones del formulario (crear, editar, eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $entidad = $_POST['entidad'] ?? ''; // categoria o curso

    // -------- CRUD CATEGORIAS --------
    if ($entidad === 'categoria' && $es_admin) {
        // CREAR CATEGORIA (solo administradores)
        if ($accion === 'crear') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';

            // Validar que todos los campos estén llenos
            if (!empty($nombre) && !empty($descripcion)) {
                // Insertar la categoria en la base de datos
                $stmt = $conn->prepare("INSERT INTO categoria (id_estado, nombre, descripcion) VALUES (1, ?, ?)");
                $stmt->bind_param("ss", $nombre, $descripcion);

                if ($stmt->execute()) {
                    $mensaje = 'Categoría creada exitosamente';
                } else {
                    $error = 'Error al crear la categoría';
                }
            } else {
                $error = 'Complete todos los campos';
            }
        }

        // EDITAR CATEGORIA EXISTENTE (solo administradores)
        if ($accion === 'editar') {
            $id = $_POST['id'] ?? '';
            $estado = $_POST['id_estado'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';

            // Validar que todos los campos estén llenos
            if (
                !empty($id) && !empty($estado) && !empty($nombre) && !empty($descripcion)) {
                // Actualizar la categoria en la base de datos
                $stmt = $conn->prepare("UPDATE categoria SET id_estado = ?, nombre = ?, descripcion = ? WHERE id = ?");
                $stmt->bind_param("issi", $estado, $nombre, $descripcion, $id);

                if ($stmt->execute()) {
                    $mensaje = 'Categoría actualizada exitosamente';
                } else {
                    $error = 'Error al actualizar la categoría';
                }
            }
        }

        // ELIMINAR CATEGORIA (solo administradores)
        if ($accion === 'eliminar') {
            $id = $_POST['id'] ?? '';
            if (!empty($id)) {
                // Eliminar la categoria de la base de datos
                $stmt = $conn->prepare("DELETE FROM categoria WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    $mensaje = 'Categoría eliminada exitosamente';
                } else {
                    $error = 'Error al eliminar la categoría';
                }
            }
        }
    }

    // -------- CRUD CURSOS --------
    if ($entidad === 'curso' && $es_admin) {
        // CREAR NUEVO CURSO (solo administradores)
        if ($accion === 'crear') {
            $categoria = $_POST['id_categoria'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = $_POST['precio'] ?? '';
            $tiempo = $_POST['tiempo_horas'] ?? '';
            $imagen = $_POST['imagen'] ?? '';

            // Validar que todos los campos estén llenos
            if (!empty($categoria) && !empty($nombre) && !empty($descripcion) && !empty($precio) && !empty($tiempo) && !empty($imagen)) {
                // Insertar la cita en la base de datos
                $stmt = $conn->prepare("INSERT INTO curso (id_categoria, id_estado, nombre, descripcion, precio, tiempo_horas, imagen) 
            VALUES (?, 1, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issdis", $categoria, $nombre, $descripcion, $precio, $tiempo, $imagen);

                if ($stmt->execute()) {
                    $mensaje = 'Curso creado exitosamente';
                } else {
                    $error = 'Error al crear el curso';
                }
            } else {
                $error = 'Complete todos los campos';
            }
        }

        // EDITAR CURSO EXISTENTE (solo administradores)
        if ($accion === 'editar') {
            $id = $_POST['id'] ?? '';
            $categoria = $_POST['id_categoria'] ?? '';
            $estado = $_POST['id_estado'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = $_POST['precio'] ?? '';
            $tiempo = $_POST['tiempo_horas'] ?? '';
            $imagen = $_POST['imagen'] ?? '';

            // Validar que todos los campos estén llenos
            if (
                !empty($id) && !empty($categoria) && !empty($estado) && !empty($nombre) && !empty($descripcion) && !empty($precio)
                && !empty($tiempo) && !empty($imagen)) {
                // Actualizar el curso en la base de datos
                $stmt = $conn->prepare("UPDATE curso SET id_categoria = ?, id_estado = ?, nombre = ?, descripcion = ?, precio = ?, tiempo_horas = ?, imagen = ? WHERE id = ?");
                $stmt->bind_param("iissdisi", $categoria, $estado, $nombre, $descripcion, $precio, $tiempo, $imagen, $id);

                if ($stmt->execute()) {
                    $mensaje = 'Curso actualizado exitosamente';
                } else {
                    $error = 'Error al actualizar el curso';
                }
            }
        }

        // ELIMINAR CURSO (solo administradores)
        if ($accion === 'eliminar') {
            $id = $_POST['id'] ?? '';
            if (!empty($id)) {
                // Eliminar la cita de la base de datos
                $stmt = $conn->prepare("DELETE FROM curso WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    $mensaje = 'Curso eliminado exitosamente';
                } else {
                    $error = 'Error al eliminar el curso';
                }
            }
        }
    }
}

// Obtener todas las categorias para mostrar
$result = $conn->query("SELECT * FROM categoria");
$categorias = $result->fetch_all(MYSQLI_ASSOC);

// Obtener todas los cursos para mostrar con el nombre de la categoría
$result = $conn->query("
    SELECT c.*, cat.nombre as nombre_categoria 
    FROM curso c 
    LEFT JOIN categoria cat ON c.id_categoria = cat.id
");
$cursos = $result->fetch_all(MYSQLI_ASSOC);

// Datos estáticos hasta tener la db hecha
/*$cursos = [
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
];*/

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
                    <?php foreach ($categorias as $categoria): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="learn-category-card h-100">
                                <i class="bi bi-code-square learn-category-icon"></i>
                                <h3 class="h4 mb-3"><?php echo htmlspecialchars($categoria['nombre']); ?></h3>
                                <p class="mb-4 text-muted"><?php echo htmlspecialchars($categoria['descripcion']); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="?categoria=desarrollo-web" class="text-decoration-none">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Categorías -->
        <!-- <section class="learn-categories">
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
        </section> -->

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
                                    <img src="<?php echo htmlspecialchars($curso['imagen']); ?>" alt="<?php echo htmlspecialchars($curso['nombre_categoria'] ?? $curso['nombre']); ?>">
                                    <span class="learn-course-badge"><?php echo htmlspecialchars($curso['nombre_categoria'] ?? 'Sin categoría'); ?></span>
                                </div>
                                <div class="learn-course-content">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted"><i class="bi bi-clock me-2"></i><?php echo htmlspecialchars($curso['tiempo_horas']); ?> horas</span>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-star-fill text-warning me-1"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-3"><?php echo htmlspecialchars($curso['nombre']); ?></h5>
                                    <p class="text-muted mb-4"><?php echo htmlspecialchars($curso['descripcion']); ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0">$<?php echo htmlspecialchars($curso['precio']); ?></span>
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
                                    <span class="learn-course-badge mb-2"><?php echo htmlspecialchars($curso['nombre_categoria'] ?? 'Sin categoría'); ?></span>
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