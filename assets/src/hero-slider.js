import Splide from '@splidejs/splide';

document.addEventListener('DOMContentLoaded', () => {
	const el = document.querySelector('[data-hero-slider="true"]');
	if (el) {
		// 1. Setup Classes for Splide Requirements
		if (!el.classList.contains('splide')) {
			el.classList.add('splide');
		}

		const viewport = el.querySelector('.jw-hero-slider__viewport');
		if (viewport && !viewport.classList.contains('splide__track')) {
			viewport.classList.add('splide__track');
		}

		const list = el.querySelector('.jw-hero-slider__list');
		if (list) {
			if (!list.classList.contains('splide__list')) {
				list.classList.add('splide__list');
			}
			const slides = list.querySelectorAll('.jw-hero-slider__slide');
			slides.forEach(slide => {
				if (!slide.classList.contains('splide__slide')) {
					slide.classList.add('splide__slide');
				}
			});
		}

		// 2. Initialize Splide
		// We disable default arrows to use our custom PHP-rendered ones
		const slider = new Splide(el, {
			type: 'loop',
			perPage: 1,
			perMove: 1,
			gap: '1rem',
			padding: { right: '0' },
			arrows: false,
			pagination: true, // Let Splide generate dots
			autoplay: false,
			interval: 5000,
			pauseOnHover: true,
			classes: {
				pagination: 'splide__pagination jw-hero-slider__dots',
				page: 'splide__pagination__page jw-hero-slider__dot',
			},
			breakpoints: {
				768: {
					gap: '1rem',
					padding: { right: '2rem' }
				}
			}
		});

		slider.mount();

		// 3. Bind Custom Arrows
		const prevBtn = el.querySelector('[data-hero-slider-prev]');
		const nextBtn = el.querySelector('[data-hero-slider-next]');

		if (prevBtn) {
			prevBtn.addEventListener('click', () => slider.go('<'));
		}
		if (nextBtn) {
			nextBtn.addEventListener('click', () => slider.go('>'));
		}
	}
});
