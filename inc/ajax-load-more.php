<?php
/**
 * AJAX handler for loading more posts.
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}

function jagawarta_load_more_posts()
{
	check_ajax_referer('jagawarta_nonce', 'nonce');

	$page = isset($_POST['page']) ? absint($_POST['page']) : 2;
	$archive = isset($_POST['archive']) ? json_decode(stripslashes($_POST['archive']), true) : array();

	// Logic: First 20 are loaded by main query. AJAX loads 10 each.
	$ppp = 10;
	$offset = 20 + (($page - 2) * $ppp);

	$args = array_merge($archive, array(
		'post_type' => 'post',
		'posts_per_page' => $ppp,
		'offset' => $offset,
		'post_status' => 'publish',
		'no_found_rows' => true,
	));

	$query = new WP_Query($args);

	if ($query->have_posts()) {
		$index = $offset + 1;
		// Detect if we are in an archive context based on common query vars
		$is_archive = !empty($archive['category_name']) || !empty($archive['cat']) || !empty($archive['tag']) || !empty($archive['author']) || !empty($archive['year']);

		while ($query->have_posts()) {
			$query->the_post();
			if ($is_archive) {
				echo '<li class="flex h-full">';
				jagawarta_part('template-parts/cards/archive/post-grid-item');
				echo '</li>';
			}
			else {
				jagawarta_part('template-parts/cards/lists/ranked-post-item', null, array('index' => $index));
			}
			$index++;
		}
		wp_reset_postdata();
	}

	die();
}
add_action('wp_ajax_jagawarta_load_more', 'jagawarta_load_more_posts');
add_action('wp_ajax_nopriv_jagawarta_load_more', 'jagawarta_load_more_posts');
