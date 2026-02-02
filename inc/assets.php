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

function jagawarta_enqueue_assets(): void {
	$dir = JAGAWARTA_DIR . '/assets/dist';
	$uri = JAGAWARTA_URI . '/assets/dist';

	$tokens_css = $dir . '/tokens.css';
	if ( file_exists( $tokens_css ) ) {
		wp_enqueue_style(
			'jagawarta-tokens',
			$uri . '/tokens.css',
			array(),
			(string) filemtime( $tokens_css )
		);
	}
	$main_css = $dir . '/main.css';
	if ( file_exists( $main_css ) ) {
		wp_enqueue_style(
			'jagawarta-main',
			$uri . '/main.css',
			array( 'jagawarta-tokens' ),
			(string) filemtime( $main_css )
		);
	}

	$main_js = $dir . '/main.js';
	if ( file_exists( $main_js ) ) {
		wp_enqueue_script(
			'jagawarta-main',
			$uri . '/main.js',
			array(),
			(string) filemtime( $main_js ),
			array( 'strategy' => 'defer' )
		);
	}

	if ( jagawarta_needs_slider() ) {
		jagawarta_enqueue_slider( $dir, $uri );
	}
	if ( jagawarta_needs_ticker() ) {
		jagawarta_enqueue_ticker( $dir, $uri );
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
			(string) filemtime( $ticker_css )
		);
	}
	$alpine_js = $dir . '/alpine.js';
	if ( file_exists( $alpine_js ) ) {
		wp_enqueue_script(
			'jagawarta-alpine',
			$uri . '/alpine.js',
			array(),
			(string) filemtime( $alpine_js ),
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
	if ( is_front_page() && get_theme_mod( 'jagawarta_front_hero_slider', true ) ) {
		return true;
	}
	return false;
}

function jagawarta_enqueue_slider( string $dir, string $uri ): void {
	$slider_css = $dir . '/slider.css';
	$slider_js  = $dir . '/slider.js';
	if ( file_exists( $slider_css ) ) {
		wp_enqueue_style(
			'jagawarta-slider',
			$uri . '/slider.css',
			array( 'jagawarta-main' ),
			(string) filemtime( $slider_css )
		);
	}
	if ( file_exists( $slider_js ) ) {
		wp_enqueue_script(
			'jagawarta-slider',
			$uri . '/slider.js',
			array(),
			(string) filemtime( $slider_js ),
			array( 'strategy' => 'defer' )
		);
	}
}
