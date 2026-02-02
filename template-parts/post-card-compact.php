<?php
/**
 * Compact list item — horizontal row (thumb + title + meta). Expects global $post.
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
<article class="group flex flex-row gap-spacing-4 rounded-lg bg-surface-low transition-colors duration-short hover:bg-surface-high">
	<a href="<?php the_permalink(); ?>" class="flex flex-row gap-spacing-4 flex-1 min-w-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-lg" aria-label="<?php the_title_attribute(); ?>">
		<div class="flex-shrink-0 w-24 aspect-[4/3] overflow-hidden rounded-md bg-surface-mid">
			<?php
			jagawarta_the_post_display_image( $post_id, array(
				'lcp'   => false,
				'class' => 'h-full w-full object-cover',
			) );
			?>
		</div>
		<div class="flex flex-col justify-center min-w-0 py-spacing-2">
			<h3 class="text-title-medium leading-snug text-on-surface group-hover:text-primary transition-colors duration-short line-clamp-2">
				<?php the_title(); ?>
			</h3>
			<div class="mt-1 text-label-small text-on-surface-variant">
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			</div>
		</div>
	</a>
</article>
