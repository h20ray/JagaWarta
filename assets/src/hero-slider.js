function initHeroSlider() {
	const roots = document.querySelectorAll('[data-hero-slider="true"]');
	if (!roots.length) return;

	roots.forEach((root) => {
		const slides = Array.from(root.querySelectorAll('[data-hero-slide]'));
		if (slides.length <= 1) {
			// Nothing to slide, let markup render as-is.
			return;
		}

		let current = 0;

		const prevButton = root.querySelector('[data-hero-slider-prev]');
		const nextButton = root.querySelector('[data-hero-slider-next]');
		const dotsContainer = root.querySelector('[data-hero-slider-dots]');

		// Create dots
		if (dotsContainer) {
			dotsContainer.innerHTML = ''; // Clear existing
			slides.forEach((_, index) => {
				const dot = document.createElement('button');
				dot.type = 'button';
				dot.classList.add('jw-hero-slider__dot');
				dot.setAttribute('aria-label', `Go to slide ${index + 1}`);
				dot.addEventListener('click', (e) => {
					e.preventDefault();
					showSlide(index);
				});
				dotsContainer.appendChild(dot);
			});
		}
		const dots = dotsContainer ? Array.from(dotsContainer.children) : [];

		function showSlide(index) {
			if (!slides.length) return;

			const max = slides.length - 1;
			const nextIndex = Math.max(0, Math.min(index, max));

			slides.forEach((slideEl, idx) => {
				const isActive = idx === nextIndex;
				if (isActive) {
					// CSS transition handles visibility/opacity
					slideEl.setAttribute('aria-hidden', 'false');
				} else {
					slideEl.setAttribute('aria-hidden', 'true');
				}
			});

			// Update dots
			if (dots.length) {
				dots.forEach((dot, idx) => {
					if (idx === nextIndex) {
						dot.classList.add('jw-hero-slider__dot--active');
						dot.setAttribute('aria-current', 'true');
					} else {
						dot.classList.remove('jw-hero-slider__dot--active');
						dot.removeAttribute('aria-current');
					}
				});
			}

			current = nextIndex;
		}

		function goNext() {
			const nextIndex = current + 1 >= slides.length ? 0 : current + 1;
			showSlide(nextIndex);
		}

		function goPrev() {
			const nextIndex = current - 1 < 0 ? slides.length - 1 : current - 1;
			showSlide(nextIndex);
		}

		if (prevButton) {
			prevButton.addEventListener('click', (event) => {
				event.preventDefault();
				goPrev();
			});
		}

		if (nextButton) {
			nextButton.addEventListener('click', (event) => {
				event.preventDefault();
				goNext();
			});
		}

		// Keyboard navigation: left/right arrows when focus is inside the slider.
		root.addEventListener('keydown', (event) => {
			const key = event.key;
			if (key !== 'ArrowLeft' && key !== 'ArrowRight') return;

			const activeElement = document.activeElement;
			if (!activeElement || !root.contains(activeElement)) return;

			event.preventDefault();
			if (key === 'ArrowLeft') {
				goPrev();
			} else if (key === 'ArrowRight') {
				goNext();
			}
		});

		// Ensure the initially marked active slide is respected.
		const initialIndexAttr = slides.findIndex((slideEl) => slideEl.getAttribute('aria-hidden') === 'false');
		if (initialIndexAttr >= 0) {
			showSlide(initialIndexAttr);
		} else {
			showSlide(0);
		}
	});
}

document.addEventListener('DOMContentLoaded', initHeroSlider);
