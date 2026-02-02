<?php
/**
 * News queries (performance-first)
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get featured posts for hero slider (tag: featured)
 *
 * @param int $limit
 * @return int[] post IDs
 */
function jagawarta_get_featured_slider_ids( int $limit = 5 ): array {
	$limit = max( 1, min( $limit, 10 ) );

	$q = new WP_Query( array(
		'post_type'              => 'post',
		'posts_per_page'         => $limit,
		'no_found_rows'          => true,
		'ignore_sticky_posts'    => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => true,
		'fields'                 => 'ids',
		'tax_query'              => array(
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => array( 'featured' ),
			),
		),
	) );

	return array_map( 'intval', $q->posts ? $q->posts : array() );
}

/**
 * Get latest posts excluding some IDs
 *
 * @param int   $limit
 * @param int[] $exclude_ids
 * @return int[] post IDs
 */
function jagawarta_get_latest_ids_excluding( int $limit = 2, array $exclude_ids = array() ): array {
	$limit       = max( 1, min( $limit, 10 ) );
	$exclude_ids = array_values( array_filter( array_map( 'intval', $exclude_ids ) ) );

	$q = new WP_Query( array(
		'post_type'              => 'post',
		'posts_per_page'         => $limit,
		'post__not_in'           => $exclude_ids,
		'no_found_rows'          => true,
		'ignore_sticky_posts'    => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => true,
		'fields'                 => 'ids',
	) );

	return array_map( 'intval', $q->posts ? $q->posts : array() );
}
