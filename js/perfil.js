(function(){
	window.guardarPerfil = function() {
		alert('Perfil actualizado correctamente (Función a implementar)');
		const modalEl = document.getElementById('settingsModal');
		if (modalEl) {
			const inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
			inst.hide();
		}
	}

	window.cambiarContrasena = function() {
		const current = document.getElementById('currentPassword')?.value || '';
		const newPass = document.getElementById('newPassword')?.value || '';
		const confirm = document.getElementById('confirmPassword')?.value || '';
		if (newPass !== confirm) {
			alert('Las contraseñas no coinciden');
			return;
		}
		alert('Contraseña cambiada correctamente (Función a implementar)');
		const modalEl = document.getElementById('passwordModal');
		if (modalEl) {
			const inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
			inst.hide();
		}
	}

	window.descargarDatos = function() {
		alert('Preparando descarga de datos... (Función a implementar)');
	}
})();


