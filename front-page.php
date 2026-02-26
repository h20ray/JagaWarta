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
$latest_count = (int)get_theme_mod('jagawarta_latest_count', 10);
$latest_count = max(1, min($latest_count, 30));
$tab_post_count = (int)get_theme_mod('jagawarta_front_tab_post_count', 10);
$tab_post_count = max(1, min($tab_post_count, 30));
$ticker_on = (bool)get_theme_mod('jagawarta_ticker_on_front', true);
$ticker_count = (int)get_theme_mod('jagawarta_ticker_count', 5);
$breaking_ids = $ticker_on ? jagawarta_get_breaking_posts($ticker_count) : array();

$slider_ids = jagawarta_get_featured_slider_ids($hero_count);
if (empty($slider_ids)) {
	$slider_ids = jagawarta_get_latest_ids_excluding($hero_count, array());
}
$side_ids = jagawarta_get_latest_ids_excluding(4, $slider_ids);
$exclude_ids = array_unique(array_merge($slider_ids, $side_ids));
$latest_ids = jagawarta_get_latest_ids_excluding($latest_count, $exclude_ids);
$exclude_ids = array_unique(array_merge($exclude_ids, $latest_ids));

$tab_categories_raw = (string)get_theme_mod('jagawarta_front_tab_categories', '');
$tab_categories = array();
$tab_slugs = preg_split('/\s*,\s*/', strtolower($tab_categories_raw));
$tab_slugs = is_array($tab_slugs) ? array_values(array_filter(array_map('sanitize_title', $tab_slugs))) : array();

if (!empty($tab_slugs)) {
	$terms = get_terms(array(
		'taxonomy' => 'category',
		'slug' => $tab_slugs,
		'hide_empty' => true,
	));
	if (!is_wp_error($terms) && !empty($terms)) {
		$by_slug = array();
		foreach ($terms as $term) {
			$by_slug[$term->slug] = $term;
		}
		foreach ($tab_slugs as $slug) {
			if (isset($by_slug[$slug])) {
				$tab_categories[] = $by_slug[$slug];
			}
		}
	}
}

if (empty($tab_categories)) {
	$tab_categories = get_categories(array(
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => true,
	));
}
?>

<main id="main" class="site-main">
	<?php if (!empty($breaking_ids)): ?>
		<?php jagawarta_part('template-parts/breaking-ticker', null, array('post_ids' => $breaking_ids)); ?>
	<?php endif; ?>

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

	<?php if (!empty($tab_categories)) : ?>
		<?php jagawarta_part('template-parts/sections/home/category-vertical-tabs', null, array(
		'categories' => $tab_categories,
		'post_count' => $tab_post_count,
	)); ?>
	<?php endif; ?>
</main>

<?php
get_footer();
