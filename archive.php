<?php
/**
 * Archive (category, tag, date, author).
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}
get_header();

$obj = get_queried_object();
$cat_name = is_category() ? single_cat_title('', false) : get_the_archive_title();
set_query_var('breadcrumb_context', 'archive');
?>

<main id="main" class="jw-archive site-main layout-content max-w-page-max flex flex-col text-on-surface">
	<?php get_template_part('template-parts/breadcrumb'); ?>
	<header class="jw-archive-header flex flex-col max-w-content-max pt-0">
		<div class="mb-spacing-2">
			<?php the_archive_title('<h1 class="jw-archive-title tracking-tight text-on-surface">', '</h1>'); ?>
		</div>
		
		<?php if (get_the_archive_description()): ?>
			<div class="jw-archive-desc text-on-surface-variant max-w-content-max">
				<?php the_archive_description(); ?>
			</div>
		<?php
else: ?>
			<p class="jw-archive-desc text-on-surface-variant max-w-content-max">
				<?php printf(esc_html__('Latest news and updates about %s at JagaWarta.', 'jagawarta'), esc_html($cat_name)); ?>
			</p>
		<?php
endif; ?>
	</header>

	<?php if (have_posts()): ?>
		
		<?php
	if (have_posts()) {
		the_post(); // Hero
?>
			<div class="mb-spacing-10">
				<?php get_template_part('template-parts/hero/hero-category'); ?>
			</div>
			<?php
	}


	$more_ids = array();
	while (count($more_ids) < 3 && have_posts()) {
		the_post();
		$more_ids[] = get_the_ID();
	}
	if (!empty($more_ids)) {
		get_template_part('template-parts/category-3up', null, array('post_ids' => $more_ids));
	}
?>

		<div class="jw-archive-section flex items-center gap-spacing-4">
			<h2 class="jw-archive-section-title text-on-surface">
				<?php printf(esc_html__('Latest %s news', 'jagawarta'), esc_html($cat_name)); ?>
			</h2>
			<div class="h-px bg-outline-variant flex-grow"></div>
		</div>

		<ul id="ajax-post-container" class="grid gap-spacing-4 list-none m-0 p-0 sm:grid-cols-2 lg:grid-cols-3">
			<?php
	while (have_posts()) {
		the_post();
?>
				<li class="flex h-full"><?php get_template_part('template-parts/cards/card-categories'); ?></li>
				<?php
	}
?>
		</ul>

		<?php get_template_part('template-parts/pagination'); ?>


	<?php
else: ?>
		<?php get_template_part('template-parts/content', 'none'); ?>
	<?php
endif; ?>
</main>

<?php
get_footer();
