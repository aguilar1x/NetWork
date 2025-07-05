// Funcionalidad para la página de aprendizaje

document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const nivelFilter = document.getElementById('nivelFilter');
    const duracionFilter = document.getElementById('duracionFilter');
    const courseCards = document.querySelectorAll('.course-card');

    // Animación de entrada inicial
    function animateOnScroll() {
        const elements = document.querySelectorAll('.category-card, .course-card');
        elements.forEach((element, index) => {
            const rect = element.getBoundingClientRect();
            const isVisible = rect.top <= window.innerHeight - 100;
            
            if (isVisible) {
                // Añadir delay progresivo para efecto cascada
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }

    // Configurar elementos para animación
    document.querySelectorAll('.category-card, .course-card').forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'all 0.5s ease-out';
    });

    // Escuchar scroll para animaciones
    window.addEventListener('scroll', animateOnScroll);
    // Trigger inicial
    animateOnScroll();

    // Manejar búsqueda principal con animación
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const searchTerm = searchForm.querySelector('input[type="search"]').value.toLowerCase();
        
        courseCards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const description = card.querySelector('.card-text').textContent.toLowerCase();
            const categoria = card.querySelector('.badge').textContent.toLowerCase();
            
            const matches = title.includes(searchTerm) || 
                          description.includes(searchTerm) || 
                          categoria.includes(searchTerm);
            
            // Animar salida/entrada de cards
            const cardContainer = card.closest('.col-md-6');
            if (cardContainer) {
                if (!matches) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        cardContainer.style.display = 'none';
                    }, 300);
                } else {
                    cardContainer.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 50);
                }
            }
        });
    });

    // Función para filtrar cursos con animaciones
    function filterCourses() {
        const nivelValue = nivelFilter.value.toLowerCase();
        const duracionValue = duracionFilter.value.toLowerCase();

        courseCards.forEach(card => {
            const duracionText = card.querySelector('.text-muted').textContent.toLowerCase();
            let matchNivel = !nivelValue || duracionText.includes(nivelValue);
            
            let matchDuracion = true;
            if (duracionValue) {
                if (duracionValue.includes('corto')) {
                    matchDuracion = parseInt(duracionText) <= 5;
                } else if (duracionValue.includes('medio')) {
                    matchDuracion = parseInt(duracionText) > 5 && parseInt(duracionText) <= 10;
                } else if (duracionValue.includes('largo')) {
                    matchDuracion = parseInt(duracionText) > 10;
                }
            }

            const cardContainer = card.closest('.col-md-6');
            if (cardContainer) {
                if (matchNivel && matchDuracion) {
                    cardContainer.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateX(0)';
                    }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateX(50px)';
                    setTimeout(() => {
                        cardContainer.style.display = 'none';
                    }, 300);
                }
            }
        });
    }

    // Escuchar cambios en los filtros
    nivelFilter.addEventListener('change', filterCourses);
    duracionFilter.addEventListener('change', filterCourses);

    // Manejar clicks en botones "Ver detalles"
    document.querySelectorAll('.btn-custom').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.course-card');
            const modal = new bootstrap.Modal(document.getElementById('courseModal'));
            
            // Obtener datos del curso
            const title = card.querySelector('.card-title').textContent;
            const description = card.querySelector('.card-text').textContent;
            const categoria = card.querySelector('.badge').textContent;
            const duracion = card.querySelector('.text-muted').textContent;
            const rating = card.querySelector('.rating-stars').textContent;
            const precio = card.querySelector('.h5').textContent;
            const imagen = card.querySelector('img').src;

            // Actualizar contenido del modal
            const modalEl = document.getElementById('courseModal');
            modalEl.querySelector('.modal-title').textContent = title;
            modalEl.querySelector('img').src = imagen;
            modalEl.querySelector('.modal-body p').textContent = description;
            
            // Animar apertura del modal
            modalEl.addEventListener('show.bs.modal', function() {
                this.querySelector('.modal-content').style.transform = 'scale(0.7)';
                this.querySelector('.modal-content').style.opacity = '0';
            });

            modalEl.addEventListener('shown.bs.modal', function() {
                this.querySelector('.modal-content').style.transition = 'all 0.3s ease-out';
                this.querySelector('.modal-content').style.transform = 'scale(1)';
                this.querySelector('.modal-content').style.opacity = '1';
            });

            modal.show();
        });
    });

    // Efecto parallax suave en el hero
    window.addEventListener('scroll', function() {
        const heroImage = document.querySelector('.hero-section img');
        if (heroImage) {
            const scrolled = window.pageYOffset;
            heroImage.style.transform = `translateY(${scrolled * 0.4}px)`;
        }
    });
}); 