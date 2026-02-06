<?php
/**
 * Post card for category/archive/search/author lists.
 * Uses loop context (get_the_ID()) or optional $args['post_id'].
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id   = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$category  = get_the_category( $post_id );
$cat       = $category ? $category[0] : null;
$read_time = function_exists( 'jagawarta_read_time_label' ) ? jagawarta_read_time_label( $post_id ) : '';
?>
<article class="group flex flex-col h-full overflow-hidden rounded-xl bg-surface-low shadow-elevation-1 transition-all duration-medium ease-emphasized hover:bg-surface-high hover:shadow-elevation-3 hover:-translate-y-1">
	<a href="<?php the_permalink(); ?>" class="flex flex-col h-full focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-xl" aria-label="<?php the_title_attribute(); ?>">
		<div class="overflow-hidden rounded-t-xl aspect-[16/9] bg-surface-low shrink-0">
			<?php
			jagawarta_the_post_display_image( $post_id, array(
				'lcp'   => false,
				'class' => 'h-full w-full object-cover transition-transform duration-medium ease-emphasized group-hover:scale-105',
			) );
			?>
		</div>

		<div class="flex flex-col flex-grow p-spacing-6">
			<div class="flex flex-wrap items-center gap-x-spacing-2 gap-y-1 text-label-medium text-on-surface-variant mb-spacing-3">
				<?php if ( $cat ) : ?>
					<?php jagawarta_the_category_chip( $cat, array( 'size' => 'medium' ) ); ?>
				<?php endif; ?>
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $post_id ) ); ?>">
					<?php echo esc_html( jagawarta_format_date( $post_id ) ); ?>
				</time>
				<?php if ( $read_time ) : ?>
					<span aria-hidden="true">·</span>
					<span><?php echo esc_html( $read_time ); ?></span>
				<?php endif; ?>
			</div>

			<h3 class="mb-spacing-3 text-headline-small leading-tight text-on-surface group-hover:text-primary transition-colors duration-short underline decoration-outline-variant decoration-2 underline-offset-2">
				<?php the_title(); ?>
			</h3>

			<div class="line-clamp-3 text-body-medium text-on-surface-variant">
				<?php the_excerpt(); ?>
			</div>
		</div>
	</a>
</article>
