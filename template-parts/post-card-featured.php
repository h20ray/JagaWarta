<?php
/**
 * Featured post card — 1–2 per page, larger. Expects global $post.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_id = get_the_ID();
$cat     = null;
$cats    = get_the_category( $post_id );
if ( ! empty( $cats ) ) {
	$cat = $cats[0];
}
?>
<article class="group flex flex-col h-full rounded-lg bg-surface-low shadow-elevation-1 transition-all duration-medium ease-emphasized hover:bg-surface-high hover:shadow-elevation-2 overflow-hidden">
	<a href="<?php the_permalink(); ?>" class="flex flex-col h-full focus:outline-none focus-visible:ring-2 focus-visible:ring-primary" aria-label="<?php the_title_attribute(); ?>">
		<div class="overflow-hidden aspect-[16/9] bg-surface-mid flex-shrink-0">
			<?php
			jagawarta_the_post_display_image( $post_id, array(
				'lcp'   => false,
				'class' => 'h-full w-full object-cover transition-transform duration-long ease-standard group-hover:scale-105',
			) );
			?>
		</div>
		<div class="flex flex-col flex-grow p-spacing-6">
			<?php if ( $cat ) : ?>
				<div class="mb-spacing-3">
					<?php jagawarta_the_category_chip( $cat, array( 'size' => 'medium' ) ); ?>
				</div>
			<?php endif; ?>
			<h2 class="mb-spacing-3 text-headline-medium leading-tight text-on-surface group-hover:text-primary transition-colors duration-short">
				<?php the_title(); ?>
			</h2>
			<div class="line-clamp-2 text-body-medium text-on-surface-variant mb-spacing-4">
				<?php the_excerpt(); ?>
			</div>
			<div class="mt-auto text-label-small text-on-surface-variant">
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			</div>
		</div>
	</a>
</article>
