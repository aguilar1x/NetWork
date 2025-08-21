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
                        <li class="nav-item">
                            <a class="nav-link" href="soporte.php">Soporte</a>
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
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="carrito.php">
                                <i class="bi bi-cart3 fs-5"></i>
                                <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                0
                                </span>
                            </a>
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
        // Sincronizar contador de carrito
        try {
            const basePath = window.location.pathname.replace(/[^\/]*$/, '');
            fetch(basePath + 'app/controllers/cart-controller.php?action=count')
                .then(r => r.json())
                .then(d => {
                    if (d && d.success) {
                        const badge = document.getElementById('cart-count');
                        if (badge) badge.textContent = d.count;
                    }
                });
        } catch (e) {
            // noop
        }

        // Asegurar contenedor para toasts
        (function ensureToastContainer() {
            if (!document.getElementById('toast-container')) {
                const container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'd-flex flex-column align-items-end';
                container.style.position = 'fixed';
                container.style.top = '1rem';
                container.style.right = '1rem';
                container.style.zIndex = '1080';
                container.style.gap = '0.5rem';
                document.body.appendChild(container);
            }
        })();

        // Función global para mostrar toasts
        if (typeof window.showToast !== 'function') {
            window.showToast = function(message, variant = 'success', delayMs = 2000) {
                const container = document.getElementById('toast-container');
                if (!container) return;
                const toastEl = document.createElement('div');
                toastEl.className = `toast align-items-center text-bg-${variant} border-0`;
                toastEl.setAttribute('role', 'alert');
                toastEl.setAttribute('aria-live', 'assertive');
                toastEl.setAttribute('aria-atomic', 'true');
                toastEl.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                `;
                container.appendChild(toastEl);
                try {
                    const toast = new bootstrap.Toast(toastEl, { delay: delayMs });
                    toast.show();
                    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
                } catch (e) {
                    alert(message);
                    toastEl.remove();
                }
            }
        }

        // Fallback: definir addToCart si no existe (para onclick inline)
        if (typeof window.addToCart !== 'function') {
            window.addToCart = function(courseId) {
                const basePath = window.location.pathname.replace(/[^\/]*$/, '');
                fetch(basePath + 'app/controllers/cart-controller.php?action=add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `course_id=${encodeURIComponent(courseId)}`
                })
                .then(async r => {
                    const text = await r.text();
                    let data;
                    try { data = JSON.parse(text); } catch (e) {
                        console.error('Respuesta no JSON del carrito:', text);
                        throw e;
                    }
                    if (data && data.success) {
                        const badge = document.getElementById('cart-count');
                        if (badge) badge.textContent = data.count;
                        if (typeof window.showToast === 'function') window.showToast('Curso agregado al carrito', 'success');
                        if (/\/carrito\.php$/i.test(window.location.pathname)) {
                            setTimeout(() => window.location.reload(), 500);
                        }
                    } else {
                        console.error('No se pudo agregar al carrito');
                        if (typeof window.showToast === 'function') window.showToast('No se pudo agregar al carrito', 'danger');
                    }
                })
                .catch(() => {
                    console.error('Error de red al agregar al carrito');
                    if (typeof window.showToast === 'function') window.showToast('Error de red al agregar al carrito', 'danger');
                });
            }
        }
    } else {
        console.error('Elemento header no encontrado');
    }
}