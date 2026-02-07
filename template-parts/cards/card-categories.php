<?php
/**
 * Card for category/archive/search/author lists. Expects $args['post_id'] or global $post.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id   = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$category  = get_the_category( $post_id );
$cat       = $category ? $category[0] : null;
$date_iso  = get_the_date( DATE_W3C, $post_id );
$date_hr   = function_exists( 'jagawarta_format_date' ) ? jagawarta_format_date( $post_id ) : get_the_date( '', $post_id );
$author_id = (int) get_post_field( 'post_author', $post_id );
$author    = $author_id ? get_the_author_meta( 'display_name', $author_id ) : '';
?>
<article class="group relative flex flex-col h-full min-w-0 overflow-hidden rounded-md bg-surface-low shadow-elevation-1 transition-all duration-medium ease-emphasized hover:bg-surface-high hover:shadow-elevation-3">
	<a href="<?php the_permalink(); ?>" class="flex flex-col h-full focus:outline-none focus:ring-2 focus:ring-primary rounded-md" aria-label="<?php the_title_attribute(); ?>">
		<div class="overflow-hidden rounded-t-md aspect-video bg-surface-low shrink-0">
			<?php jagawarta_the_post_display_image( $post_id, array( 'lcp' => false, 'class' => 'h-full w-full object-cover transition-transform duration-short ease-in group-hover:scale-105' ) ); ?>
		</div>

		<div class="relative flex flex-col flex-grow px-spacing-10 pt-spacing-10 pb-spacing-10">

			<h3 class="mb-spacing-4 text-title-large font-normal leading-tight text-on-surface group-hover:text-primary transition-colors duration-short">
				<?php the_title(); ?>
			</h3>

			<div class="mt-auto flex items-center justify-between gap-spacing-2 pt-spacing-4 text-label-medium text-on-surface-variant">
				<span>
					<?php if ( $author ) : ?>
						<?php echo esc_html( $author ); ?>
						<span class="mx-1" aria-hidden="true">·</span>
					<?php endif; ?>
					<time datetime="<?php echo esc_attr( $date_iso ); ?>"><?php echo esc_html( $date_hr ); ?></time>
				</span>
			</div>

			<div aria-hidden="true" class="absolute bottom-spacing-10 right-spacing-10 flex text-on-surface opacity-0 transition-all duration-short ease-in group-hover:opacity-100 group-hover:right-spacing-5 group-hover:text-primary">
				<?php jagawarta_svg_arrow_right(); ?>
			</div>
		</div>
	</a>
</article>
