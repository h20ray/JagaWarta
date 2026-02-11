<?php
/**
 * Query helpers — no N+1; single query per section.
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Get featured posts for hero slider (tag: featured)
 *
 * @param int $limit
 * @return int[] post IDs
 */
function jagawarta_get_featured_slider_ids(int $limit = 5): array
{
	$limit = max(1, min($limit, 10));

	$q = new WP_Query(array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $limit,
		'no_found_rows' => true,
		'ignore_sticky_posts' => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => true,
		'fields' => 'ids',
		'tax_query' => array(
				array(
				'taxonomy' => 'post_tag',
				'field' => 'slug',
				'terms' => array('featured'),
			),
		),
	));

	return array_map('intval', $q->posts ? $q->posts : array());
}

/**
 * Get latest posts excluding some IDs.
 *
 * @param int   $limit
 * @param int[] $exclude_ids
 * @return int[] post IDs
 */
function jagawarta_get_latest_ids_excluding(int $limit = 2, array $exclude_ids = array()): array
{
	$limit = max(1, min($limit, 10));
	$exclude_ids = array_values(array_filter(array_map('intval', $exclude_ids)));

	$q = new WP_Query(array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $limit,
		'post__not_in' => $exclude_ids,
		'no_found_rows' => true,
		'ignore_sticky_posts' => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => true,
		'fields' => 'ids',
	));

	return array_map('intval', $q->posts ? $q->posts : array());
}

/**
 * Get featured posts (generic — sticky/recent).
 *
 * @param int $count
 * @return int[] post IDs
 */
function jagawarta_get_featured_posts(int $count = 5): array
{
	$q = new WP_Query(array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $count,
		'no_found_rows' => true,
		'ignore_sticky_posts' => false,
		'orderby' => 'date',
		'fields' => 'ids',
	));
	return $q->posts;
}

/**
 * Get posts by category.
 *
 * @param int|string $term_id_or_slug
 * @param int        $count
 * @return int[] post IDs
 */
function jagawarta_get_posts_by_category($term_id_or_slug, int $count = 5): array
{
	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $count,
		'no_found_rows' => true,
		'orderby' => 'date',
		'fields' => 'ids',
	);
	if (is_numeric($term_id_or_slug)) {
		$args['cat'] = (int)$term_id_or_slug;
	}
	else {
		$args['category_name'] = $term_id_or_slug;
	}
	$q = new WP_Query($args);
	return $q->posts;
}

/**
 * Get related posts by shared categories.
 *
 * @param int $post_id
 * @param int $count
 * @return int[] post IDs
 */
function jagawarta_get_related_posts(int $post_id, int $count = 3): array
{
	$cats = get_the_category($post_id);
	$cat_ids = $cats ? array_map(function ($c) {
		return $c->term_id; }, $cats) : array();
	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $count,
		'post__not_in' => array($post_id),
		'no_found_rows' => true,
		'orderby' => 'date',
		'fields' => 'ids',
	);
	if (!empty($cat_ids)) {
		$args['tax_query'] = array(array('taxonomy' => 'category', 'field' => 'term_id', 'terms' => $cat_ids));
	}
	$q = new WP_Query($args);
	return $q->posts;
}

/**
 * Get breaking/ticker posts.
 *
 * @param int    $count
 * @param string $category_slug
 * @return int[] post IDs
 */
function jagawarta_get_breaking_posts(int $count = 5, $category_slug = ''): array
{
	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $count,
		'no_found_rows' => true,
		'orderby' => 'date',
		'fields' => 'ids',
	);
	if (!empty($category_slug)) {
		$args['category_name'] = $category_slug;
	}
	$q = new WP_Query($args);
	return $q->posts;
}
