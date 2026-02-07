<?php
/**
 * AJAX handler for loading more posts.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jagawarta_load_more_posts() {
	check_ajax_referer( 'jagawarta_nonce', 'nonce' );

	$page    = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
	$archive = isset( $_POST['archive'] ) ? json_decode( stripslashes( $_POST['archive'] ), true ) : array();

	$args = array_merge( $archive, array(
		'paged'          => $page,
		'post_status'    => 'publish',
		'posts_per_page' => 6,
		'no_found_rows'  => true,
	) );

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<li class="flex h-full">';
			get_template_part( 'template-parts/cards/card-categories' );
			echo '</li>';
		}
		wp_reset_postdata();
	}

	die();
}
add_action( 'wp_ajax_jagawarta_load_more', 'jagawarta_load_more_posts' );
add_action( 'wp_ajax_nopriv_jagawarta_load_more', 'jagawarta_load_more_posts' );
