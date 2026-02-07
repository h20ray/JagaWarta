<?php
/**
 * 3-up featured section below category hero on archive.
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

$featured_query = new WP_Query( array(
	'post__in'            => $post_ids,
	'posts_per_page'      => 3,
	'ignore_sticky_posts' => true,
	'orderby'             => 'post__in',
	'no_found_rows'       => true,
) );
?>

<section aria-label="<?php esc_attr_e( 'Featured stories', 'jagawarta' ); ?>" class="layout-content w-full pt-spacing-16 pb-spacing-8">
	<div class="grid grid-cols-1 md:grid-cols-3 gap-spacing-6">
		<?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
			<?php get_template_part( 'template-parts/cards/card-categories-below-hero' ); ?>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
</section>
