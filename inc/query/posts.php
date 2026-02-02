<?php
/**
 * Query helpers — no N+1; single query per section.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jagawarta_get_featured_posts( int $count = 5 ): array {
	$q = new WP_Query( array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $count,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => false,
		'orderby'             => 'date',
		'fields'              => 'ids',
	) );
	return $q->posts;
}

function jagawarta_get_posts_by_category( $term_id_or_slug, int $count = 5 ): array {
	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $count,
		'no_found_rows'  => true,
		'orderby'        => 'date',
		'fields'         => 'ids',
	);
	if ( is_numeric( $term_id_or_slug ) ) {
		$args['cat'] = (int) $term_id_or_slug;
	} else {
		$args['category_name'] = $term_id_or_slug;
	}
	$q = new WP_Query( $args );
	return $q->posts;
}

function jagawarta_get_related_posts( int $post_id, int $count = 3 ): array {
	$cats = get_the_category( $post_id );
	$cat_ids = $cats ? array_map( function ( $c ) { return $c->term_id; }, $cats ) : array();
	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $count,
		'post__not_in'   => array( $post_id ),
		'no_found_rows'  => true,
		'orderby'        => 'date',
		'fields'         => 'ids',
	);
	if ( ! empty( $cat_ids ) ) {
		$args['tax_query'] = array( array( 'taxonomy' => 'category', 'field' => 'term_id', 'terms' => $cat_ids ) );
	}
	$q = new WP_Query( $args );
	return $q->posts;
}

function jagawarta_get_latest( int $count = 10 ): WP_Query {
	return new WP_Query( array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $count,
		'no_found_rows'  => true,
		'orderby'        => 'date',
	) );
}

function jagawarta_get_breaking_posts( int $count = 5, $category_slug = '' ): array {
	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $count,
		'no_found_rows'  => true,
		'orderby'        => 'date',
		'fields'         => 'ids',
	);
	if ( ! empty( $category_slug ) ) {
		$args['category_name'] = $category_slug;
	}
	$q = new WP_Query( $args );
	return $q->posts;
}
