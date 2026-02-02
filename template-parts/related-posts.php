<?php
/**
 * Single: Related Posts (Google Blog Style).
 * - 3 Column Grid
 * - ~42px gap (using gap-10 / 2.5rem or gap-11)
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

<section aria-labelledby="related-heading" class="mx-auto max-w-screen-xl px-spacing-4">
	<h2 id="related-heading" class="sr-only">
		<?php esc_html_e( 'Related stories', 'jagawarta' ); ?>
	</h2>
	
	<div class="text-headline-small text-on-surface mb-spacing-8 font-medium">
		<?php esc_html_e( 'More like this', 'jagawarta' ); ?>
	</div>

	<div class="grid grid-cols-1 md:grid-cols-3 gap-x-10 gap-y-10">
		<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
			<?php
			$cat = get_the_category();
			$cat = ! empty( $cat ) ? $cat[0] : null;
			?>
			<article class="group flex flex-col h-full">
				<a href="<?php the_permalink(); ?>" class="flex flex-col h-full focus:outline-none">
					<!-- Image -->
					<div class="mb-spacing-4 overflow-hidden rounded-xl aspect-[16/9] bg-surface-low">
						<?php jagawarta_the_post_display_image( get_the_ID(), array( 'class' => 'h-full w-full object-cover transition-transform duration-medium ease-standard group-hover:scale-105' ) ); ?>
					</div>

					<!-- Content -->
					<div class="flex flex-col flex-grow">
						<?php if ( $cat ) : ?>
							<div class="mb-spacing-3">
								<?php jagawarta_the_category_chip( $cat, array( 'size' => 'small' ) ); ?>
							</div>
						<?php endif; ?>

						<h3 class="mb-spacing-2 text-title-large leading-tight text-on-surface group-hover:text-primary transition-colors duration-short">
							<?php the_title(); ?>
						</h3>

						<div class="mt-auto pt-spacing-2 text-label-medium text-on-surface-variant">
							<span class="font-bold text-on-surface">
								<?php echo esc_html( get_the_author() ); ?>
							</span>
						</div>
					</div>
				</a>
			</article>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
</section>
