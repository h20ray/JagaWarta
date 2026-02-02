<?php
/**
 * Overlay Card (large)
 * Expects: $args['post_id'] (int), $args['is_lcp'] (bool) optional
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_id = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$is_lcp  = ! empty( $args['is_lcp'] );

$permalink   = get_permalink( $post_id );
$title       = get_the_title( $post_id );
$excerpt     = wp_strip_all_tags( get_the_excerpt( $post_id ) );
$date_iso    = get_the_date( DATE_W3C, $post_id );
$date_human  = get_the_date( '', $post_id );
$category    = get_the_category( $post_id );
$cat         = $category ? $category[0] : null;
?>
<article class="relative min-h-[18rem] lg:min-h-[28rem]">
	<a href="<?php echo esc_url( $permalink ); ?>" class="block h-full focus:outline-none">
		<div class="absolute inset-0">
			<?php if ( has_post_thumbnail( $post_id ) ) : ?>
				<?php
				echo get_the_post_thumbnail(
					$post_id,
					'large',
					array(
						'class'          => 'h-full w-full object-cover',
						'loading'        => $is_lcp ? 'eager' : 'lazy',
						'fetchpriority'  => $is_lcp ? 'high' : 'auto',
						'decoding'       => 'async',
					)
				);
				?>
			<?php endif; ?>
			<div class="absolute inset-0 bg-scrim/40"></div>
		</div>

		<div class="relative flex h-full flex-col justify-end p-5 lg:p-6">
			<div class="flex items-center gap-2">
				<?php if ( $cat ) : ?>
					<span class="inline-flex items-center rounded-sm bg-secondary-container px-2 py-1 text-[0.75rem] leading-5 text-on-secondary-container">
						<?php echo esc_html( $cat->name ); ?>
					</span>
				<?php endif; ?>
			</div>

			<h2 class="mt-3 text-[2rem] leading-tight text-on-surface lg:text-[2.5rem]">
				<?php echo esc_html( $title ); ?>
			</h2>

			<?php if ( $excerpt ) : ?>
				<p class="mt-2 max-w-prose text-[1rem] leading-7 text-on-surface-variant">
					<?php echo esc_html( $excerpt ); ?>
				</p>
			<?php endif; ?>

			<div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-[0.875rem] leading-6 text-on-surface-variant">
				<time datetime="<?php echo esc_attr( $date_iso ); ?>"><?php echo esc_html( $date_human ); ?></time>
				<span aria-hidden="true">•</span>
				<span><?php echo esc_html( '6 min read' ); ?></span>
			</div>
		</div>
	</a>
</article>
