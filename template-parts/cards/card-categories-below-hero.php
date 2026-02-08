<?php
/**
 * Card used below category hero on archive (3-up section). Expects global $post.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id   = get_the_ID();
$category  = get_the_category( $post_id );
$cat       = $category ? $category[0] : null;

?>
<article <?php post_class( 'group relative flex flex-col h-full min-w-0 overflow-hidden rounded-md bg-surface-low border border-outline-variant shadow-elevation-1 transition-all duration-medium ease-emphasized hover:bg-surface-high hover:shadow-elevation-3' ); ?>>
	<a href="<?php the_permalink(); ?>" class="flex flex-col h-full focus:outline-none focus:ring-2 focus:ring-primary rounded-md" aria-label="<?php the_title_attribute(); ?>">
		<div class="relative flex flex-col flex-grow px-spacing-10 pt-spacing-6 pb-spacing-6">
			<h3 class="text-title-large font-normal leading-tight text-on-surface group-hover:text-primary transition-colors duration-short">
				<?php the_title(); ?>
			</h3>

		</div>

		<div class="overflow-hidden rounded-b-md aspect-card-below-hero bg-surface-low shrink-0 mt-auto jw-media-wrap">
			<?php
			jagawarta_the_post_display_image(
				$post_id,
				array(
					'lcp'   => false,
					'class' => 'h-full w-full object-cover transition-transform duration-short ease-in group-hover:scale-105',
				)
			);
			?>
			<?php if ( $cat ) : ?>
				<div class="jw-chip-overlay">
					<?php jagawarta_the_category_chip( $cat, array( 'size' => 'small', 'show_link' => false ) ); ?>
				</div>
			<?php endif; ?>
		</div>

	</a>
</article>
