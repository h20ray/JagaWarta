<?php
/**
 * Compact Overlay Card (side column)
 * Expects: $args['post_id'] (int)
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_id   = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$permalink = get_permalink( $post_id );
$title     = get_the_title( $post_id );
$date_iso  = get_the_date( DATE_W3C, $post_id );
$date_human = get_the_date( '', $post_id );
$category  = get_the_category( $post_id );
$cat       = $category ? $category[0] : null;
?>
<article class="relative overflow-hidden rounded-md bg-surface-high ring-1 ring-outline-variant">
	<a href="<?php echo esc_url( $permalink ); ?>" class="block h-full focus:outline-none">
		<div class="absolute inset-0">
			<?php if ( has_post_thumbnail( $post_id ) ) : ?>
				<?php
				echo get_the_post_thumbnail(
					$post_id,
					'medium_large',
					array(
						'class'    => 'h-full w-full object-cover',
						'loading'  => 'lazy',
						'decoding' => 'async',
					)
				);
				?>
			<?php endif; ?>
			<div class="absolute inset-0 bg-scrim/40"></div>
		</div>

		<div class="relative flex h-full flex-col justify-end p-4">
			<div class="flex items-center gap-2">
				<?php if ( $cat ) : ?>
					<span class="inline-flex items-center rounded-sm bg-secondary-container px-2 py-1 text-[0.75rem] leading-5 text-on-secondary-container">
						<?php echo esc_html( $cat->name ); ?>
					</span>
				<?php endif; ?>
			</div>

			<h3 class="mt-2 text-[1.125rem] leading-6 text-on-surface">
				<?php echo esc_html( $title ); ?>
			</h3>

			<div class="mt-2 flex items-center gap-2 text-[0.75rem] leading-5 text-on-surface-variant">
				<time datetime="<?php echo esc_attr( $date_iso ); ?>"><?php echo esc_html( $date_human ); ?></time>
				<span aria-hidden="true">•</span>
				<span><?php echo esc_html( '6 min read' ); ?></span>
			</div>
		</div>
	</a>
</article>
