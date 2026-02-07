<?php
/**
 * Register theme blocks.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'jagawarta_register_blocks' );

function jagawarta_register_blocks(): void {
	$blocks = array(
		'hero'             => array( 'render' => 'render_hero' ),
		'section-grid'     => array( 'render' => 'render_section_grid' ),
		'related-posts'    => array( 'render' => 'render_related_posts' ),
		'breaking-ticker'  => array( 'render' => 'render_breaking_ticker' ),
	);
	foreach ( $blocks as $name => $opts ) {
		$path = JAGAWARTA_DIR . '/inc/blocks/' . $name;
		if ( ! is_dir( $path ) ) {
			continue;
		}
		$block_json = $path . '/block.json';
		if ( ! file_exists( $block_json ) ) {
			continue;
		}
		$args = array();
		if ( ! empty( $opts['render'] ) && function_exists( $opts['render'] ) ) {
			$args['render_callback'] = $opts['render'];
		}
		register_block_type( $path, $args );
	}
}

function render_hero( array $attributes, string $content, WP_Block $block ): string {
	$count    = isset( $attributes['count'] ) ? (int) $attributes['count'] : (int) get_theme_mod( 'jagawarta_hero_count', 5 );
	$slider   = isset( $attributes['slider'] ) ? (bool) $attributes['slider'] : true;
	$post_ids = jagawarta_get_featured_posts( $count );
	ob_start();
	if ( ! empty( $post_ids ) && $slider ) {
		get_template_part( 'template-parts/hero/hero-slider', null, array( 'post_ids' => $post_ids, 'slider' => true ) );
	} elseif ( ! empty( $post_ids ) ) {
		get_template_part( 'template-parts/hero/hero-default', null, array( 'featured_id' => (int) $post_ids[0] ) );
	} else {
		get_template_part( 'template-parts/hero/hero-default', null, array() );
	}
	return ob_get_clean();
}

function render_section_grid( array $attributes, string $content, WP_Block $block ): string {
	$slug = isset( $attributes['categorySlug'] ) ? $attributes['categorySlug'] : '';
	$count = isset( $attributes['count'] ) ? (int) $attributes['count'] : 4;
	if ( empty( $slug ) ) {
		return '';
	}
	$cat = get_category_by_slug( $slug );
	if ( ! $cat ) {
		return '';
	}
	$post_ids = jagawarta_get_posts_by_category( $cat->term_id, $count );
	if ( empty( $post_ids ) ) {
		return '';
	}
	ob_start();
	get_template_part( 'template-parts/section-grid', null, array( 'category' => $cat, 'post_ids' => $post_ids ) );
	return ob_get_clean();
}

function render_related_posts( array $attributes, string $content, WP_Block $block ): string {
	$post_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
	$count = isset( $attributes['count'] ) ? (int) $attributes['count'] : 3;
	if ( ! $post_id ) {
		return '';
	}
	$post_ids = jagawarta_get_related_posts( $post_id, $count );
	if ( empty( $post_ids ) ) {
		return '';
	}
	ob_start();
	get_template_part( 'template-parts/related-posts', null, array( 'post_ids' => $post_ids ) );
	return ob_get_clean();
}

function render_breaking_ticker( array $attributes, string $content, WP_Block $block ): string {
	$count  = isset( $attributes['count'] ) ? (int) $attributes['count'] : 5;
	$slug   = isset( $attributes['categorySlug'] ) ? trim( (string) $attributes['categorySlug'] ) : '';
	$post_ids = jagawarta_get_breaking_posts( $count, $slug );
	if ( empty( $post_ids ) ) {
		return '';
	}
	ob_start();
	get_template_part( 'template-parts/breaking-ticker', null, array( 'post_ids' => $post_ids ) );
	return ob_get_clean();
}
