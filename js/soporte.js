document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('soporteForm');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const nombre = document.getElementById('nombre').value.trim();
    const correo = document.getElementById('correo').value.trim();
    const asunto = document.getElementById('asunto').value;
    const mensaje = document.getElementById('mensaje').value.trim();

    if (!nombre || !correo || !asunto || !mensaje) {
      alert('Por favor, completa todos los campos.');
      return;
    }

    // Simulación de envío exitoso
    alert('Gracias por contactarnos. Te responderemos pronto.');
    form.reset();
  });
});
