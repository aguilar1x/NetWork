(function(){
	const basePath = window.location.pathname.replace(/[^\/]*$/, '');

	// Recarga la página cuando se agrega un curso al carrito
	window.addToCart = function(courseId) {
		fetch(basePath + 'app/controllers/cart-controller.php?action=add', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: 'course_id=' + encodeURIComponent(courseId)
		}).then(async r => {
			const text = await r.text();
			let data;
			try { data = JSON.parse(text); } catch (e) { console.error('Respuesta no JSON del carrito:', text); throw e; }
			if (data && data.success) {
				const badge = document.getElementById('cart-count');
				if (badge) badge.textContent = data.count;
				if (window.showToast) window.showToast('Curso agregado al carrito', 'success');
				setTimeout(() => window.location.reload(), 500);
			} else {
				if (window.showToast) window.showToast('No se pudo agregar al carrito', 'danger');
			}
		}).catch(() => {
			console.error('Error de red al agregar al carrito');
			if (window.showToast) window.showToast('Error de red al agregar al carrito', 'danger');
		});
	}

	const listEl = document.getElementById('cart-list');
	const subtotalEl = document.getElementById('subtotal');
	const taxEl = document.getElementById('tax');
	const grandEl = document.getElementById('grand-total');
	const discountEl = document.getElementById('discount');
	const couponEl = document.getElementById('coupon');
	const applyCouponBtn = document.getElementById('apply-coupon');
	let discount = 0;
	function parseMoney(text){ return Number(String(text).replace(/[^0-9.]/g,'')||0); }
	function fmt(n){ return '$' + n.toFixed(2); }
	function recalc(){
		if (!listEl) return;
		let subtotal = 0;
		listEl.querySelectorAll('.cart-item').forEach(item => {
			const priceText = item.querySelector('.fw-semibold').textContent;
			subtotal += parseMoney(priceText);
		});
		if (subtotalEl) subtotalEl.textContent = fmt(subtotal);
		if (discountEl) discountEl.textContent = '-' + fmt(discount);
		const taxable = Math.max(subtotal - discount, 0);
		if (taxEl) taxEl.textContent = fmt(taxable * 0.21);
		if (grandEl) grandEl.textContent = fmt(taxable * 1.21);
	}
	document.querySelectorAll('.btn-remove').forEach(btn => {
		btn.addEventListener('click', function(e){
			e.preventDefault();
			const item = this.closest('.cart-item');
			const id = item?.getAttribute('data-id');
			if (!id) return;
			fetch(basePath + 'app/controllers/cart-controller.php?action=remove', {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: 'course_id=' + encodeURIComponent(id)
			}).then(r => r.json()).then(d => {
				if (d && d.success) {
					item.remove();
					const badge = document.getElementById('cart-count');
					if (badge) badge.textContent = d.count;
					if (window.showToast) window.showToast('Curso eliminado del carrito', 'success');
					recalc();
					// Recargar siempre para actualizar la sección de recomendados
					setTimeout(() => window.location.reload(), 500);
				} else {
					if (window.showToast) window.showToast('No se pudo eliminar el curso', 'danger');
				}
			});
		});
	});
	const clearBtn = document.getElementById('btn-clear');
	if (clearBtn) clearBtn.addEventListener('click', function(){
		fetch(basePath + 'app/controllers/cart-controller.php?action=clear', { method: 'POST' })
			.then(r => r.json()).then(d => {
				if (d && d.success) {
					if (window.showToast) window.showToast('Carrito vaciado', 'success');
					setTimeout(() => window.location.reload(), 600);
				} else {
					if (window.showToast) window.showToast('No se pudo vaciar el carrito', 'danger');
				}
			});
	});

	if (applyCouponBtn) applyCouponBtn.addEventListener('click', function(){
		const code = (couponEl?.value || '').trim().toUpperCase();
		if (!code) return;
		const subtotal = parseMoney(subtotalEl.textContent);
		if (code === 'NETWORK10') {
			discount = Math.min(subtotal * 0.10, subtotal);
			if (window.showToast) window.showToast('Cupón aplicado: 10% de descuento', 'success');
		} else if (code === 'NETWORK20') {
			discount = Math.min(subtotal * 0.20, subtotal);
			if (window.showToast) window.showToast('Cupón aplicado: 20% de descuento', 'success');
		} else {
			discount = 0;
			if (window.showToast) window.showToast('Cupón inválido', 'danger');
		}
		recalc();
	});
	recalc();
})();

document.addEventListener('DOMContentLoaded', function(){
	const scrollers = document.querySelectorAll('.rec-scroll');
	scrollers.forEach(scrollEl => {
		const container = scrollEl.closest('.position-relative');
		const prevBtn = container?.querySelector('.rec-prev');
		const nextBtn = container?.querySelector('.rec-next');
		const step = 300;
		if (prevBtn) prevBtn.addEventListener('click', () => scrollEl.scrollBy({ left: -step, behavior: 'smooth' }));
		if (nextBtn) nextBtn.addEventListener('click', () => scrollEl.scrollBy({ left: step, behavior: 'smooth' }));
	});
});


