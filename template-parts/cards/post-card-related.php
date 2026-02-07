<?php
/**
 * Post card for "More like this" section (Google blog Related stories style).
 * Uses loop context (the_post() already called in related-posts).
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
		<div class="overflow-hidden rounded-t-md aspect-video bg-surface-low shrink-0">
			<?php jagawarta_the_post_display_image( get_the_ID(), array( 'class' => 'h-full w-full object-cover transition-transform duration-short ease-in group-hover:scale-105' ) ); ?>
		</div>

		<div class="relative flex flex-col flex-grow px-spacing-10 pt-spacing-5 pb-spacing-10">
			<?php if ( $cat ) : ?>
				<div class="mb-spacing-3">
					<span class="text-label-large font-medium uppercase tracking-wide text-on-surface-variant">
						<?php echo esc_html( $cat->name ); ?>
					</span>
				</div>
			<?php endif; ?>

			<h3 class="mb-spacing-3 text-title-large font-normal leading-tight text-on-surface group-hover:text-primary transition-colors duration-short">
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
			
			<!-- Sliding Arrow Icon -->
			<div aria-hidden="true" class="absolute bottom-spacing-10 right-spacing-10 flex text-on-surface transition-all duration-short ease-in group-hover:right-spacing-5 group-hover:text-primary">
				<svg viewBox="0 0 32 32" class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg">
					<polygon points="16,0 13.2,2.8 24.3,14 0,14 0,18 24.3,18 13.2,29.2 16,32 32,16"/>
				</svg>
			</div>
		</div>
	</a>
</article>
