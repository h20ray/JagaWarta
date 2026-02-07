<?php
/**
 * Conditional enqueue — base CSS/JS always; slider etc. only when needed.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'jagawarta_enqueue_assets', 5 );
add_action( 'wp_head', 'jagawarta_head_lcp_preload', 1 );

function jagawarta_head_lcp_preload(): void {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	$url = jagawarta_lcp_preload_url();
	if ( ! $url ) {
		return;
	}
	echo '<link rel="preload" as="image" href="' . esc_url( $url ) . '" />' . "\n";
}

function jagawarta_asset_version( string $path ): string {
	if ( ! file_exists( $path ) ) {
		return JAGAWARTA_VERSION;
	}
	return substr( md5_file( $path ), 0, 8 );
}

function jagawarta_enqueue_assets(): void {
	$dir = JAGAWARTA_DIR . '/assets/dist';
	$uri = JAGAWARTA_URI . '/assets/dist';

	wp_enqueue_style(
		'jagawarta-google-font',
		'https://fonts.googleapis.com/css2?family=Google+Sans+Flex:wght@100..900&display=swap',
		array(),
		null
	);

	$tokens_css = $dir . '/tokens.css';
	if ( file_exists( $tokens_css ) ) {
		wp_enqueue_style(
			'jagawarta-tokens',
			$uri . '/tokens.css',
			array( 'jagawarta-google-font' ),
			jagawarta_asset_version( $tokens_css )
		);
	}
	$main_css = $dir . '/main.css';
	if ( file_exists( $main_css ) ) {
		wp_enqueue_style(
			'jagawarta-main',
			$uri . '/main.css',
			array( 'jagawarta-tokens' ),
			jagawarta_asset_version( $main_css )
		);
	}

	$main_js = $dir . '/main.js';
	if ( file_exists( $main_js ) ) {
		wp_enqueue_script(
			'jagawarta-main',
			$uri . '/main.js',
			array(),
			jagawarta_asset_version( $main_js ),
			array( 'strategy' => 'defer' )
		);
	}

	if ( is_front_page() ) {
		jagawarta_enqueue_hero_splide( $dir, $uri );
	} elseif ( jagawarta_needs_slider() ) {
		jagawarta_enqueue_slider( $dir, $uri );
	}
	if ( jagawarta_needs_ticker() ) {
		jagawarta_enqueue_ticker( $dir, $uri );
	}

	if ( is_archive() ) {
		$load_more_js = $dir . '/load-more.js';
		if ( file_exists( $load_more_js ) ) {
			wp_enqueue_script(
				'jagawarta-load-more',
				$uri . '/load-more.js',
				array(),
				jagawarta_asset_version( $load_more_js ),
				array( 'strategy' => 'defer' )
			);
			wp_localize_script( 'jagawarta-load-more', 'jagawarta_vars', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'jagawarta_nonce' ),
			) );
		}
	}
}

function jagawarta_needs_ticker(): bool {
	if ( is_front_page() && get_theme_mod( 'jagawarta_ticker_on_front', true ) ) {
		return true;
	}
	if ( is_singular() ) {
		$post = get_post();
		if ( $post && has_block( 'jagawarta/breaking-ticker', $post ) ) {
			return true;
		}
	}
	return false;
}

function jagawarta_enqueue_ticker( string $dir, string $uri ): void {
	$ticker_css = $dir . '/ticker.css';
	if ( file_exists( $ticker_css ) ) {
		wp_enqueue_style(
			'jagawarta-ticker',
			$uri . '/ticker.css',
			array( 'jagawarta-main' ),
			jagawarta_asset_version( $ticker_css )
		);
	}
	$alpine_js = $dir . '/alpine.js';
	if ( file_exists( $alpine_js ) ) {
		wp_enqueue_script(
			'jagawarta-alpine',
			$uri . '/alpine.js',
			array(),
			jagawarta_asset_version( $alpine_js ),
			array( 'strategy' => 'defer' )
		);
	}
}

function jagawarta_needs_slider(): bool {
	if ( is_singular() ) {
		$post = get_post();
		if ( $post && has_block( 'jagawarta/hero', $post ) ) {
			return true;
		}
	}
	return false;
}

function jagawarta_enqueue_hero_splide( string $dir, string $uri ): void {
	$slider_css = $dir . '/slider.css';
	$hero_js    = $dir . '/hero-splide.js';
	if ( file_exists( $slider_css ) ) {
		wp_enqueue_style(
			'jagawarta-slider',
			$uri . '/slider.css',
			array( 'jagawarta-main' ),
			jagawarta_asset_version( $slider_css )
		);
	}
	$splide_md3_css = $dir . '/splide-md3.css';
	if ( file_exists( $splide_md3_css ) ) {
		wp_enqueue_style(
			'jagawarta-splide-md3',
			$uri . '/splide-md3.css',
			array( 'jagawarta-slider' ),
			jagawarta_asset_version( $splide_md3_css )
		);
	}
	if ( file_exists( $hero_js ) ) {
		wp_enqueue_script(
			'jagawarta-hero-splide',
			$uri . '/hero-splide.js',
			array(),
			jagawarta_asset_version( $hero_js ),
			array( 'strategy' => 'defer' )
		);
	}
}

function jagawarta_enqueue_slider( string $dir, string $uri ): void {
	$slider_css = $dir . '/slider.css';
	$slider_js  = $dir . '/slider.js';
	if ( file_exists( $slider_css ) ) {
		wp_enqueue_style(
			'jagawarta-slider',
			$uri . '/slider.css',
			array( 'jagawarta-main' ),
			jagawarta_asset_version( $slider_css )
		);
	}
	if ( file_exists( $slider_js ) ) {
		wp_enqueue_script(
			'jagawarta-slider',
			$uri . '/slider.js',
			array(),
			jagawarta_asset_version( $slider_js ),
			array( 'strategy' => 'defer' )
		);
	}
}
