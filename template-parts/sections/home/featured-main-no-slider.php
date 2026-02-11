<?php
/**
 * Home featured main area rendered as non-slider hero stack.
 * Expects: $args['ids'] = array<int> post IDs.
 *
 * Delegates to `template-parts/hero/hero-slider` in non-slider mode
 * so the hero layout stays consistent with the block version.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ids = isset( $args['ids'] ) ? array_values( array_filter( array_map( 'intval', (array) $args['ids'] ) ) ) : array();

if ( empty( $ids ) ) {
	return;
}

jagawarta_part(
	'template-parts/hero/hero-slider',
	null,
	array(
		'post_ids' => $ids,
		'slider'   => false,
	)
);
