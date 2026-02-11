import Splide from '@splidejs/splide';

document.addEventListener('DOMContentLoaded', () => {
	const el = document.querySelector('[data-hero-slider="true"]');
	if (el) {
		const autoplay = el.dataset.heroSliderAutoplay === 'true';
		// 1. Setup Classes for Splide Requirements
		if (!el.classList.contains('splide')) {
			el.classList.add('splide');
		}

		const viewport = el.querySelector('.jw-hero-slider__viewport');
		if (viewport && !viewport.classList.contains('splide__track')) {
			viewport.classList.add('splide__track');
		}

		const list = el.querySelector('.jw-hero-slider__list');
		const slideCount = list ? list.querySelectorAll('.jw-hero-slider__slide').length : 0;
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
			type: slideCount > 1 ? 'loop' : 'slide',
			perPage: 1,
			perMove: 1,
			gap: 0,
			padding: 0,
			arrows: false,
			pagination: slideCount > 1, // Let Splide generate dots when needed
			autoplay,
			interval: 5000,
			speed: 600,
			easing: 'cubic-bezier(0.25, 1, 0.5, 1)',
			keyboard: 'focused',
			pauseOnHover: true,
			drag: slideCount > 1,
			dragMinThreshold: {
				mouse: 8,
				touch: 16,
			},
			classes: {
				pagination: 'splide__pagination jw-hero-slider__dots',
				page: 'splide__pagination__page jw-hero-slider__dot',
			},
			reducedMotion: {
				speed: 0,
				rewindSpeed: 0,
				autoplay: false,
			},
		});

		slider.mount();

		const updateAriaState = () => {
			const activeIndex = slider.index;
			const slides = el.querySelectorAll('.jw-hero-slider__slide');
			slides.forEach((slide, index) => {
				const isActive = index === activeIndex;
				slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
			});
		};

		slider.on('mounted', updateAriaState);
		slider.on('move', updateAriaState);
		slider.on('updated', updateAriaState);

		// 3. Bind Custom Arrows
		const prevBtn = el.querySelector('[data-hero-slider-prev]');
		const nextBtn = el.querySelector('[data-hero-slider-next]');

		if (prevBtn) {
			prevBtn.addEventListener('click', () => slider.go('<'));
			prevBtn.addEventListener('keydown', event => {
				if (event.key === 'ArrowLeft') {
					event.preventDefault();
					slider.go('<');
				}
			});
		}
		if (nextBtn) {
			nextBtn.addEventListener('click', () => slider.go('>'));
			nextBtn.addEventListener('keydown', event => {
				if (event.key === 'ArrowRight') {
					event.preventDefault();
					slider.go('>');
				}
			});
		}
	}
});
