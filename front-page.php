<?php
/**
 * Front page — Top featured split (hero + side cards) + Latest stories.
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}
get_header();

$hero_count = (int)get_theme_mod('jagawarta_hero_count', 5);
$hero_count = max(1, min($hero_count, 10));

$slider_ids = jagawarta_get_featured_slider_ids($hero_count);
if (empty($slider_ids)) {
	$slider_ids = jagawarta_get_latest_ids_excluding($hero_count, array());
}
$side_ids = jagawarta_get_latest_ids_excluding(4, $slider_ids);
$exclude_ids = array_unique(array_merge($slider_ids, $side_ids));
$latest_ids = jagawarta_get_latest_ids_excluding(9, $exclude_ids);
?>

<main id="main" class="site-main">
	<?php if (!empty($slider_ids)): ?>
		<?php jagawarta_part('template-parts/sections/home/featured-lead-with-secondary', null, array(
		'slider_ids' => $slider_ids,
		'side_ids' => $side_ids,
	)); ?>
	<?php
endif; ?>

	<?php jagawarta_part('template-parts/sections/home/latest-stories-grid', null, array(
	'title' => __('Latest stories', 'jagawarta'),
	'ids' => $latest_ids,
)); ?>
</main>

<?php
get_footer();
