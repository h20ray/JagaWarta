<?php
/**
 * Home latest stories grid section.
 * Expects:
 * - $args['title'] string
 * - $args['ids'] array<int>
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}
$title = isset($args['title']) ? (string)$args['title'] : __('Latest stories', 'jagawarta');
$ids = isset($args['ids']) ? array_values(array_filter(array_map('intval', (array)$args['ids']))) : array();
if (empty($ids)) {
	return;
}
?>
<section class="bg-surface" aria-labelledby="latest-stories-heading">
	<div class="mx-auto max-w-screen-xl px-4 pb-8">
		<div class="flex items-end justify-between">
			<h2 id="latest-stories-heading" class="text-title-large text-on-surface">
				<?php echo esc_html($title); ?>
			</h2>

			<?php $view_all_url = jagawarta_get_posts_page_url(); ?>
			<a href="<?php echo esc_url($view_all_url); ?>"
				class="text-body-medium text-primary underline-offset-2 hover:underline focus:underline">
				<?php esc_html_e('View all', 'jagawarta'); ?>
			</a>
		</div>

		<div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
			<?php foreach ($ids as $post_id): ?>
				<?php get_template_part('template-parts/cards/shared/post-default', null, array('post_id' => $post_id)); ?>
			<?php
endforeach; ?>
		</div>
	</div>
</section>
