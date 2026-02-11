<?php
/**
 * First Grid / Latest Stories
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
<section class="bg-surface" aria-labelledby="first-grid-heading">
	<div class="mx-auto max-w-screen-xl px-spacing-4 pb-spacing-8">
		<div class="flex items-end justify-between">
			<h2 id="first-grid-heading" class="text-title-large text-on-surface">
				<?php echo esc_html($title); ?>
			</h2>

			<?php
			$posts_page_id = (int) get_option( 'page_for_posts' );
			if ( $posts_page_id ) {
				$view_all_url = get_permalink( $posts_page_id );
			} elseif ( 'posts' === get_option( 'show_on_front' ) ) {
				$view_all_url = home_url( '/' );
			} else {
				$view_all_url = get_post_type_archive_link( 'post' );
			}
			$view_all_url = $view_all_url ?: home_url( '/' );
			?>
			<a href="<?php echo esc_url( $view_all_url ); ?>"
				class="text-body-medium text-primary underline-offset-2 hover:underline focus:underline">
				<?php esc_html_e('View all', 'jagawarta'); ?>
			</a>
		</div>

		<div class="mt-spacing-4 grid gap-spacing-4 sm:grid-cols-2 lg:grid-cols-3">
			<?php foreach ($ids as $post_id): ?>
				<?php get_template_part('template-parts/cards/card-default', null, array('post_id' => $post_id)); ?>
			<?php
endforeach; ?>
		</div>
	</div>
</section>
