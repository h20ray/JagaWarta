<?php
/**
 * Single: Related Posts (Google Blog Style).
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_ids = isset( $args['post_ids'] ) ? $args['post_ids'] : array();

if ( empty( $post_ids ) ) {
	return;
}

$related_query = new WP_Query( array(
	'post__in'            => $post_ids,
	'posts_per_page'      => 3,
	'ignore_sticky_posts' => true,
	'orderby'             => 'post__in',
	'no_found_rows'       => true,
) );
?>

<section aria-labelledby="related-heading" class="mx-auto max-w-wide-max border-t border-outline-variant mt-spacing-12 pt-spacing-10 px-spacing-4">
	<h2 id="related-heading" class="sr-only">
		<?php esc_html_e( 'Related stories', 'jagawarta' ); ?>
	</h2>

	<div class="text-headline-small text-on-surface mb-spacing-6 font-medium">
		<?php esc_html_e( 'More like this', 'jagawarta' ); ?>
	</div>

	<div class="grid grid-cols-1 md:grid-cols-3 gap-spacing-10">
		<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
			<?php jagawarta_part( 'template-parts/cards/related/related-story' ); ?>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
</section>
