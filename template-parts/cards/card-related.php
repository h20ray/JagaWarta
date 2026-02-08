<?php
/**
 * Card for "More like this" section. Expects global $post.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cat      = get_the_category();
$cat      = ! empty( $cat ) ? $cat[0] : null;
$date_iso = get_the_date( DATE_W3C );
$date_hr  = function_exists( 'jagawarta_format_date' ) ? jagawarta_format_date( get_the_ID() ) : get_the_date();
$author   = get_the_author();
?>
<article class="group relative flex flex-col h-full min-w-0 overflow-hidden rounded-md bg-surface-low shadow-elevation-1 transition-all duration-medium ease-emphasized hover:bg-surface-high hover:shadow-elevation-3">
	<a href="<?php the_permalink(); ?>" class="flex flex-col h-full focus:outline-none focus:ring-2 focus:ring-primary rounded-md" aria-label="<?php the_title_attribute(); ?>">
		<div class="overflow-hidden rounded-t-md aspect-video bg-surface-low shrink-0 jw-media-wrap">
			<?php jagawarta_the_post_display_image( get_the_ID(), array( 'class' => 'h-full w-full object-cover transition-transform duration-short ease-in group-hover:scale-105' ) ); ?>
			<?php if ( $cat ) : ?>
				<div class="jw-chip-overlay">
					<?php jagawarta_the_category_chip( $cat, array( 'size' => 'small', 'show_link' => false ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="relative flex flex-col flex-grow px-spacing-10 pt-spacing-4 pb-spacing-10">
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

			<div aria-hidden="true" class="absolute bottom-spacing-10 right-spacing-10 flex text-on-surface transition-all duration-short ease-in group-hover:right-spacing-5 group-hover:text-primary">
				<?php jagawarta_svg_arrow_right(); ?>
			</div>
		</div>
	</a>
</article>
