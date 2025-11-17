import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// AJAX add-to-cart handler
document.addEventListener('DOMContentLoaded', function () {
	const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

	function showToast(message, success = true) {
		const t = document.createElement('div');
		t.textContent = message;
		t.style.cssText = `
			position: fixed;
			bottom: 24px;
			right: 24px;
			padding: 8px 16px;
			border-radius: 4px;
			box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
			border: 2px solid ${success ? '#16a34a' : '#dc2626'};
			background-color: ${success ? '#16a34a' : '#dc2626'};
			color: white;
			font-weight: 600;
			z-index: 50;
		`;
		document.body.appendChild(t);
		setTimeout(() => t.remove(), 3000);
	}

	async function submitAddToCart(form) {
		const action = form.getAttribute('action');
		const fd = new FormData(form);

		try {
			const res = await fetch(action, {
				method: 'POST',
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'X-CSRF-TOKEN': token || ''
				},
				body: fd
			});

			// try parse JSON
			let data = null;
			try { data = await res.json(); } catch (e) { /* not JSON */ }

			if (res.ok) {
				const count = data?.cart_count ?? null;
				if (count !== null) {
					const el = document.getElementById('cart-count');
					if (el) el.textContent = count;
				}
				showToast(data?.message ?? 'Added to cart');
			} else {
				showToast(data?.message ?? 'Could not add to cart', false);
			}
		} catch (err) {
			showToast('Network error', false);
			console.error(err);
		}
	}

	document.body.addEventListener('submit', function (e) {
		const form = e.target;
		if (form.classList && form.classList.contains('js-add-to-cart')) {
			e.preventDefault();
			submitAddToCart(form);
		}
	});
});
