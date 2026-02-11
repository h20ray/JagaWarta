<?php
/**
 * Pagination / Load More
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}

$total_posts = isset($args['total_posts']) ? (int)$args['total_posts'] : 0;
if ($total_posts <= 20) {
	return;
}

$ppp_ajax = 10;
$max_pages = 1 + (int)ceil(($total_posts - 20) / $ppp_ajax);

// Prepare archive args for AJAX (simplified for home/blog)
$archive_args = array();
if (is_category()) {
	$archive_args['cat'] = get_queried_object_id();
}
elseif (is_tag()) {
	$archive_args['tag_id'] = get_queried_object_id();
}
elseif (is_author()) {
	$archive_args['author'] = get_queried_object_id();
}
?>

<div class="mt-12 flex justify-center">
	<button id="load-more-btn"
		class="btn-tonal"
		data-page="2"
		data-max="<?php echo esc_attr($max_pages); ?>"
		data-archive='<?php echo esc_attr(wp_json_encode($archive_args)); ?>'>
		<?php esc_html_e('Load more', 'jagawarta'); ?>
	</button>
</div>
