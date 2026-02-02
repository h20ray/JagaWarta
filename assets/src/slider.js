import Splide from '@splidejs/splide';

const el = document.querySelector('[data-jagawarta-hero-slider]');
if (el) {
  new Splide(el, {
    type: 'loop',
    perPage: 1,
    perMove: 1,
    gap: '1rem',
    padding: { right: '2rem' },
    breakpoints: {
      768: { perPage: 1 },
      1024: { perPage: 1 },
    },
    arrows: true,
    pagination: true,
    autoplay: true,
    interval: 5000,
    pauseOnHover: true,
  }).mount();
}
