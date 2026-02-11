<?php
/**
 * Blog index (when front page is static).
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}
get_header();

$ticker_on = (bool)get_theme_mod('jagawarta_ticker_on_front', true);
$ticker_count = (int)get_theme_mod('jagawarta_ticker_count', 5);
$breaking_ids = (is_front_page() && $ticker_on) ? jagawarta_get_breaking_posts($ticker_count) : array();
?>

<main id="main" class="site-main layout-content layout-section flex flex-col gap-10">
	<?php if (!empty($breaking_ids)): ?>
		<?php jagawarta_part('template-parts/breaking-ticker', null, array('post_ids' => $breaking_ids)); ?>
	<?php
endif; ?>
	<header class="pb-4 border-b border-outline-variant mb-2">
		<h1 class="text-headline-medium font-sans text-on-surface"><?php single_post_title(); ?></h1>
	</header>
	<?php if (have_posts()): ?>
		<ol id="ajax-post-container" class="jw-post-list" role="list">
			<?php
	$index = 1;
	while (have_posts()) {
		the_post();
		jagawarta_part('template-parts/cards/lists/ranked-post-item', null, array('index' => $index));
		$index++;
	}
?>
		</ol>
		<?php
	global $wp_query;
	jagawarta_part('template-parts/pagination', null, array(
		'total_posts' => (int)$wp_query->found_posts,
	));
?>
	<?php
else: ?>
		<?php jagawarta_part('template-parts/content', 'none'); ?>
	<?php
endif; ?>
</main>

<?php
get_footer();
