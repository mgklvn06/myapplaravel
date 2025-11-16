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
		t.className = `fixed bottom-6 right-6 px-4 py-2 rounded shadow-lg ${success ? 'bg-green-600 text-white' : 'bg-red-600 text-white'}`;
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
