import Splide from '@splidejs/splide';

function initHeroSplide() {
	const el = document.querySelector( '.js-hero-splide' );
	if ( ! el ) return;

	const opts = el.dataset.splide ? JSON.parse( el.dataset.splide ) : {};
	opts.autoplay = false;

	new Splide( el, opts ).mount();
}

document.addEventListener( 'DOMContentLoaded', initHeroSplide );
