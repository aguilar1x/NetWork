// Componente de header para NetWork

// Crear el HTML del header
function createHeader(userName = 'Usuario') {
    const headerHTML = `
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
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> ${userName}
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
    `;
    return headerHTML;
}

// Función para insertar el header en la página
function insertHeader(userName) {
    console.log('Insertando header...');
    const headerElement = document.querySelector('header');
    if (headerElement) {
        // Si hay datos de usuario global, usarlos
        const userToDisplay = userName || window.currentUser?.nombre || 'Usuario';
        headerElement.innerHTML = createHeader(userToDisplay);
        console.log('Header insertado correctamente');
    } else {
        console.error('Elemento header no encontrado');
    }
}