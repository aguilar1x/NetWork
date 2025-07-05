// Componente de header para NetWork

// Crear el HTML del header
function createHeader() {
    const headerHTML = `
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="network.html">NetWork</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="perfil.html">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="aprender.html">Aprender</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="freelance.html">Freelance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="soporte.html">Soporte</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search" />
                        <button type="button" class="btn btn-outline-light">Buscar</button>
                    </form>
                </div>
            </div>
        </nav>
    `;
    return headerHTML;
}

// Función para insertar el header en la página
function insertHeader() {
    console.log('Insertando header...');
    const headerElement = document.querySelector('header');
    if (headerElement) {
        headerElement.innerHTML = createHeader();
        console.log('Header insertado correctamente');
    } else {
        console.error('Elemento header no encontrado');
    }
}