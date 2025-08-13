<?php
/* 
========================================
PÁGINA DE NETWORKING
========================================
Esta página permite a los usuarios conectar
con otros profesionales y hacer networking
*/

require_once 'app/models/user.php';

// Verificar si el usuario está logueado
if (!User::isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Obtener información del usuario actual
$currentUser = User::getCurrentUser();

// Datos de ejemplo para perfiles profesionales (esto vendría de la base de datos)
$profesionales = [
    [
        'id' => 1,
        'nombre' => 'Ana García',
        'profesion' => 'Desarrolladora Frontend',
        'empresa' => 'Tech Solutions',
        'ubicacion' => 'Madrid, España',
        'experiencia' => '5 años',
        'skills' => ['React', 'JavaScript', 'CSS', 'HTML'],
        'avatar' => 'img/avatar.jpg',
        'descripcion' => 'Desarrolladora Frontend especializada en React y tecnologías modernas.',
        'conexiones' => 150,
        'proyectos' => 23
    ],
    [
        'id' => 2,
        'nombre' => 'Carlos Ruiz',
        'profesion' => 'Diseñador UX/UI',
        'empresa' => 'Design Studio',
        'ubicacion' => 'Barcelona, España',
        'experiencia' => '3 años',
        'skills' => ['Figma', 'Adobe XD', 'Prototyping', 'User Research'],
        'avatar' => 'img/avatar.jpg',
        'descripcion' => 'Diseñador UX/UI con pasión por crear experiencias digitales excepcionales.',
        'conexiones' => 89,
        'proyectos' => 15
    ],
    [
        'id' => 3,
        'nombre' => 'Laura Fernández',
        'profesion' => 'Marketing Digital Manager',
        'empresa' => 'Growth Agency',
        'ubicacion' => 'Valencia, España',
        'experiencia' => '7 años',
        'skills' => ['SEM', 'SEO', 'Analytics', 'Content Strategy'],
        'avatar' => 'img/avatar.jpg',
        'descripcion' => 'Experta en marketing digital con enfoque en growth hacking y estrategia.',
        'conexiones' => 245,
        'proyectos' => 42
    ],
    [
        'id' => 4,
        'nombre' => 'Miguel Torres',
        'profesion' => 'Full Stack Developer',
        'empresa' => 'Startup Inc.',
        'ubicacion' => 'Sevilla, España',
        'experiencia' => '4 años',
        'skills' => ['Node.js', 'Python', 'MongoDB', 'AWS'],
        'avatar' => 'img/avatar.jpg',
        'descripcion' => 'Desarrollador Full Stack con experiencia en arquitecturas escalables.',
        'conexiones' => 120,
        'proyectos' => 18
    ]
];

// Eventos de networking próximos
$eventos = [
    [
        'id' => 1,
        'titulo' => 'Tech Meetup Madrid',
        'fecha' => '2024-02-15',
        'hora' => '19:00',
        'ubicacion' => 'Madrid, España',
        'tipo' => 'Presencial',
        'asistentes' => 45,
        'descripcion' => 'Encuentro mensual de profesionales tech en Madrid.'
    ],
    [
        'id' => 2,
        'titulo' => 'Webinar: Future of UX Design',
        'fecha' => '2024-02-20',
        'hora' => '18:30',
        'ubicacion' => 'Online',
        'tipo' => 'Virtual',
        'asistentes' => 120,
        'descripcion' => 'Discusión sobre las tendencias futuras en diseño UX.'
    ],
    [
        'id' => 3,
        'titulo' => 'Startup Networking Barcelona',
        'fecha' => '2024-02-25',
        'hora' => '20:00',
        'ubicacion' => 'Barcelona, España',
        'tipo' => 'Presencial',
        'asistentes' => 67,
        'descripcion' => 'Networking para emprendedores y profesionales de startups.'
    ]
];

// Procesar búsqueda
$busqueda = $_GET['busqueda'] ?? '';
$profesion_filtro = $_GET['profesion'] ?? '';
$ubicacion_filtro = $_GET['ubicacion'] ?? '';
?>

<!DOCTYPE html>
<html>

<head>
    <title>NetWork - Networking</title>
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
                        <a class="nav-link active" href="network.php">Network</a>
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

    <main class="container my-4">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold">Red Profesional</h1>
            <p class="lead text-muted">Conecta con profesionales de tu industria y expande tu red</p>
        </div>

        <!-- Estadísticas del usuario -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <h3>47</h3>
                    <p class="mb-0">Conexiones</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h3>12</h3>
                    <p class="mb-0">Proyectos Colaborativos</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h3>8</h3>
                    <p class="mb-0">Eventos Asistidos</p>
                </div>
            </div>
        </div>

        <!-- Búsqueda y filtros -->
        <section class="search-section">
            <h3><i class="bi bi-search me-2"></i>Encuentra profesionales</h3>
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="busqueda" class="form-control" placeholder="Buscar por nombre o habilidades..." value="<?php echo htmlspecialchars($busqueda); ?>">
                </div>
                <div class="col-md-3">
                    <select name="profesion" class="form-select">
                        <option value="">Todas las profesiones</option>
                        <option value="desarrollador" <?php echo $profesion_filtro === 'desarrollador' ? 'selected' : ''; ?>>Desarrollador</option>
                        <option value="diseñador" <?php echo $profesion_filtro === 'diseñador' ? 'selected' : ''; ?>>Diseñador</option>
                        <option value="marketing" <?php echo $profesion_filtro === 'marketing' ? 'selected' : ''; ?>>Marketing</option>
                        <option value="manager" <?php echo $profesion_filtro === 'manager' ? 'selected' : ''; ?>>Manager</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="ubicacion" class="form-select">
                        <option value="">Todas las ubicaciones</option>
                        <option value="madrid" <?php echo $ubicacion_filtro === 'madrid' ? 'selected' : ''; ?>>Madrid</option>
                        <option value="barcelona" <?php echo $ubicacion_filtro === 'barcelona' ? 'selected' : ''; ?>>Barcelona</option>
                        <option value="valencia" <?php echo $ubicacion_filtro === 'valencia' ? 'selected' : ''; ?>>Valencia</option>
                        <option value="sevilla" <?php echo $ubicacion_filtro === 'sevilla' ? 'selected' : ''; ?>>Sevilla</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </form>
        </section>

        <div class="row">
            <!-- Columna principal - Perfiles -->
            <div class="col-lg-8">
                <h3 class="mb-4"><i class="bi bi-people me-2"></i>Profesionales destacados</h3>
                
                <div class="row">
                    <?php foreach ($profesionales as $profesional): ?>
                    <div class="col-md-6 mb-4">
                        <div class="profile-card">
                            <div class="d-flex align-items-center mb-3">
                                <img src="<?php echo htmlspecialchars($profesional['avatar']); ?>" alt="Avatar" class="avatar-lg me-3">
                                <div>
                                    <h5 class="mb-1"><?php echo htmlspecialchars($profesional['nombre']); ?></h5>
                                    <p class="text-muted mb-1"><?php echo htmlspecialchars($profesional['profesion']); ?></p>
                                    <small class="text-muted">
                                        <i class="bi bi-building me-1"></i><?php echo htmlspecialchars($profesional['empresa']); ?>
                                    </small>
                                </div>
                            </div>
                            
                            <p class="text-muted mb-3"><?php echo htmlspecialchars($profesional['descripcion']); ?></p>
                            
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Habilidades:</small>
                                <?php foreach ($profesional['skills'] as $skill): ?>
                                <span class="skill-badge"><?php echo htmlspecialchars($skill); ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <small class="text-muted d-block">Conexiones</small>
                                    <strong><?php echo htmlspecialchars($profesional['conexiones']); ?></strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Proyectos</small>
                                    <strong><?php echo htmlspecialchars($profesional['proyectos']); ?></strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Experiencia</small>
                                    <strong><?php echo htmlspecialchars($profesional['experiencia']); ?></strong>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-fill" onclick="conectar(<?php echo $profesional['id']; ?>)">
                                    <i class="bi bi-person-plus me-1"></i>Conectar
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#profileModal<?php echo $profesional['id']; ?>">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Columna lateral - Eventos -->
            <div class="col-lg-4">
                <h4 class="mb-4"><i class="bi bi-calendar-event me-2"></i>Próximos eventos</h4>
                
                <?php foreach ($eventos as $evento): ?>
                <div class="event-card">
                    <h6 class="fw-bold mb-2"><?php echo htmlspecialchars($evento['titulo']); ?></h6>
                    <p class="mb-2 text-muted"><?php echo htmlspecialchars($evento['descripcion']); ?></p>
                    
                    <div class="small mb-2">
                        <i class="bi bi-calendar me-1"></i>
                        <?php echo date('d/m/Y', strtotime($evento['fecha'])); ?> - <?php echo htmlspecialchars($evento['hora']); ?>
                    </div>
                    
                    <div class="small mb-2">
                        <i class="bi bi-geo-alt me-1"></i>
                        <?php echo htmlspecialchars($evento['ubicacion']); ?>
                        <span class="badge bg-<?php echo $evento['tipo'] === 'Virtual' ? 'info' : 'success'; ?> ms-2">
                            <?php echo htmlspecialchars($evento['tipo']); ?>
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-people me-1"></i><?php echo htmlspecialchars($evento['asistentes']); ?> asistentes
                        </small>
                        <button class="btn btn-outline-primary btn-sm" onclick="asistirEvento(<?php echo $evento['id']; ?>)">
                            Asistir
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="text-center mt-3">
                    <a href="eventos.php" class="btn btn-outline-primary">Ver todos los eventos</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Modales para perfiles detallados -->
    <?php foreach ($profesionales as $profesional): ?>
    <div class="modal fade" id="profileModal<?php echo $profesional['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perfil Profesional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img src="<?php echo htmlspecialchars($profesional['avatar']); ?>" alt="Avatar" class="avatar-lg mb-3">
                        <h4><?php echo htmlspecialchars($profesional['nombre']); ?></h4>
                        <p class="text-muted"><?php echo htmlspecialchars($profesional['profesion']); ?> en <?php echo htmlspecialchars($profesional['empresa']); ?></p>
                        <p><i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($profesional['ubicacion']); ?></p>
                    </div>
                    
                    <h6>Acerca de</h6>
                    <p><?php echo htmlspecialchars($profesional['descripcion']); ?> Con <?php echo htmlspecialchars($profesional['experiencia']); ?> de experiencia en el sector.</p>
                    
                    <h6>Habilidades</h6>
                    <div class="mb-3">
                        <?php foreach ($profesional['skills'] as $skill): ?>
                        <span class="skill-badge"><?php echo htmlspecialchars($skill); ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <h5><?php echo htmlspecialchars($profesional['conexiones']); ?></h5>
                            <small class="text-muted">Conexiones</small>
                        </div>
                        <div class="col-4">
                            <h5><?php echo htmlspecialchars($profesional['proyectos']); ?></h5>
                            <small class="text-muted">Proyectos</small>
                        </div>
                        <div class="col-4">
                            <h5><?php echo htmlspecialchars($profesional['experiencia']); ?></h5>
                            <small class="text-muted">Experiencia</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="conectar(<?php echo $profesional['id']; ?>)">
                        <i class="bi bi-person-plus me-2"></i>Conectar
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="enviarMensaje(<?php echo $profesional['id']; ?>)">
                        <i class="bi bi-chat me-2"></i>Enviar mensaje
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

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
</body>

</html>
