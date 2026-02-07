<?php
/**
 * Ajax Live Search Handler
 *
 * Lightweight search endpoint for the header search bar.
 * Optimizes query for performance (no_found_rows, limited fields).
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Live Search Request
 */
function jagawarta_live_search_handler() {
	// 1. Validation
	// Public search doesn't strictly need nonce for reading, but good practice if we had one. 
	// For speed/caching compatibility on frontend, we might skip nonce verify if just reading public posts.
	
	$s = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

	if ( empty( $s ) || strlen( $s ) < 3 ) {
		wp_send_json_success( array() ); // Empty result for short queries
	}

	// 2. Query Optimization
	$args = array(
		's'                   => $s,
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 5,
		'no_found_rows'       => true,   // Skip pagination count (Fast)
		'update_post_meta_cache' => true, // We need thumbnail
		'update_post_term_cache' => false, // We don't need categories
		'ignore_sticky_posts' => true,
	);

	$query = new WP_Query( $args );
	$results = array();
	$is_fallback = false;

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$id = get_the_ID();
			$results[] = array(
				'title' => get_the_title(),
				'url'   => get_permalink(),
				'thumb' => has_post_thumbnail() ? get_the_post_thumbnail_url( $id, 'thumbnail' ) : '',
				'date'  => get_the_date( 'M j, Y' ),
			);
		}
		wp_reset_postdata();
	} else {
		// Fallback: 3 Latest Posts
		$is_fallback = true;
		$fallback_args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);
		$fallback_query = new WP_Query( $fallback_args );
		
		if ( $fallback_query->have_posts() ) {
			while ( $fallback_query->have_posts() ) {
				$fallback_query->the_post();
				$id = get_the_ID();
				$results[] = array(
					'title' => get_the_title(),
					'url'   => get_permalink(),
					'thumb' => has_post_thumbnail() ? get_the_post_thumbnail_url( $id, 'thumbnail' ) : '',
					'date'  => get_the_date( 'M j, Y' ),
				);
			}
			wp_reset_postdata();
		}
	}

	// 3. Output (JSON)
	wp_send_json_success( array(
		'data'        => $results,
		'is_fallback' => $is_fallback,
	) );
}

add_action( 'wp_ajax_jagawarta_live_search', 'jagawarta_live_search_handler' );
add_action( 'wp_ajax_nopriv_jagawarta_live_search', 'jagawarta_live_search_handler' );
