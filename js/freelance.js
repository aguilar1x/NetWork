// filtro.js

document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('filterForm');

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
});
