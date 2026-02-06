<?php
/**
 * Post card for "More like this" section below single post.
 * Uses loop context (the_post() already called in related-posts).
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cat = get_the_category();
$cat = ! empty( $cat ) ? $cat[0] : null;
?>
<article class="group flex flex-col h-full min-w-0">
	<a href="<?php the_permalink(); ?>" class="flex flex-col h-full focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-xl" aria-label="<?php the_title_attribute(); ?>">
		<div class="mb-spacing-4 overflow-hidden rounded-xl aspect-[16/9] bg-surface-low">
			<?php jagawarta_the_post_display_image( get_the_ID(), array( 'class' => 'h-full w-full object-cover transition-transform duration-medium ease-standard group-hover:scale-105' ) ); ?>
		</div>

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
