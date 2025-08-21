// freelance.js

document.addEventListener('DOMContentLoaded', function () {
    // Filtros
    const form = document.getElementById('filterForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const categoria = document.getElementById('categoria').value;
            const nivel = document.getElementById('nivel').value;
            const presupuesto = document.getElementById('presupuesto').value;
            const tipo = document.getElementById('tipo').value;

            document.querySelectorAll('.offer-card').forEach(function (card) {
                const matchCategoria = !categoria || card.dataset.categoria === categoria;
                const matchNivel = !nivel || card.dataset.nivel === nivel;
                const matchTipo = !tipo || card.dataset.tipo === tipo;

                const cardPresupuesto = parseInt(card.dataset.presupuesto);
                let matchPresupuesto = true;

                if (presupuesto === '0-100') {
                    matchPresupuesto = cardPresupuesto <= 100;
                } else if (presupuesto === '100-500') {
                    matchPresupuesto = cardPresupuesto > 100 && cardPresupuesto <= 500;
                } else if (presupuesto === '500-1000') {
                    matchPresupuesto = cardPresupuesto > 500 && cardPresupuesto <= 1000;
                } else if (presupuesto === '1000+') {
                    matchPresupuesto = cardPresupuesto > 1000;
                }

                // Mostrar u ocultar la tarjeta según los filtros
                if (matchCategoria && matchNivel && matchTipo && matchPresupuesto) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});

// Función para aplicar a una oferta
function aplicarOferta(idOferta) {
    if (confirm('¿Estás seguro de que quieres aplicar a esta oferta?')) {
        // Aquí puedes implementar la lógica para aplicar a la oferta
        // Por ejemplo, enviar una solicitud AJAX o redirigir a una página de aplicación
        alert('¡Aplicación enviada! El empleador será notificado.');
    }
}

// Función para editar una oferta
function editarOferta(idOferta) {
    // Obtener los datos de la oferta (esto se puede hacer con AJAX o pasando los datos como atributos)
    // Por ahora, mostraremos el modal de edición
    const modal = new bootstrap.Modal(document.getElementById('editarOfertaModal'));
    
    // Aquí deberías cargar los datos de la oferta en el formulario
    // Por simplicidad, por ahora solo mostramos el modal
    document.getElementById('editar_id_oferta').value = idOferta;
    
    // Cargar datos de la oferta (esto se puede mejorar con AJAX)
    // Por ahora, el usuario tendrá que llenar manualmente los campos
    
    modal.show();
}

// Función para eliminar una oferta
function eliminarOferta(idOferta) {
    if (confirm('¿Estás seguro de que quieres eliminar esta oferta? Esta acción no se puede deshacer.')) {
        document.getElementById('eliminar_id_oferta').value = idOferta;
        document.getElementById('eliminarOfertaForm').submit();
    }
}

// Función para cargar datos de oferta en el modal de edición
function cargarDatosOferta(idOferta, titulo, categoria, nivel, presupuesto, tipo, descripcion) {
    document.getElementById('editar_id_oferta').value = idOferta;
    document.getElementById('editar_tituloOferta').value = titulo;
    document.getElementById('editar_categoriaOferta').value = categoria;
    document.getElementById('editar_nivelOferta').value = nivel;
    document.getElementById('editar_presupuestoOferta').value = presupuesto;
    document.getElementById('editar_tipoOferta').value = tipo;
    document.getElementById('editar_descripcionOferta').value = descripcion;
}
