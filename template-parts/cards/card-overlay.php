<?php
/**
 * Large overlay card (hero slider). Expects $args['post_id'], optional $args['is_lcp'].
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_id   = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$is_lcp    = ! empty( $args['is_lcp'] );
$permalink = get_permalink( $post_id );
$title     = get_the_title( $post_id );
$excerpt   = wp_strip_all_tags( get_the_excerpt( $post_id ) );
$date_iso  = get_the_date( DATE_W3C, $post_id );
$date_human = get_the_date( '', $post_id );

$category = get_the_category( $post_id );
$cat      = $category ? $category[0] : null;

$read_time = function_exists( 'jagawarta_read_time_label' )
	? jagawarta_read_time_label( $post_id )
	: '';
?>
<article class="relative">
	<a href="<?php echo esc_url( $permalink ); ?>" class="block focus:outline-none">
		<div class="relative h-hero-mobile sm:h-hero-sm lg:h-hero-lg">
			<?php
			$display = function_exists( 'jagawarta_get_post_display_image' ) ? jagawarta_get_post_display_image( $post_id ) : array( 'attachment_id' => 0, 'url' => '' );
			if ( ! empty( $display['url'] ) ) :
				if ( ! empty( $display['attachment_id'] ) ) :
					jagawarta_the_image(
						$display['attachment_id'],
						array(
							'lcp'   => $is_lcp,
							'size'  => 'large',
							'sizes' => '(max-width: 1024px) 100vw, 1024px',
							'class' => 'h-full w-full object-cover',
						)
					);
				else :
					?>
					<img src="<?php echo esc_url( $display['url'] ); ?>" alt="" loading="<?php echo $is_lcp ? 'eager' : 'lazy'; ?>" <?php echo $is_lcp ? 'fetchpriority="high" ' : ''; ?>decoding="async" class="h-full w-full object-cover" />
					<?php
				endif;
			else :
				?>
				<div class="h-full w-full bg-surface-high"></div>
			<?php endif; ?>

			<div class="absolute inset-0 bg-scrim/45"></div>

			<div class="absolute inset-0 flex items-end">
				<div class="w-full min-h-40 p-spacing-5 sm:min-h-48 sm:p-spacing-6 lg:min-h-56 lg:p-spacing-8">
					<div class="flex flex-wrap items-center gap-spacing-2">
						<?php if ( $cat ) : ?>
							<span class="inline-flex items-center rounded-sm bg-secondary-container px-spacing-2 py-spacing-1 text-label-small text-on-secondary-container">
								<?php echo esc_html( $cat->name ); ?>
							</span>
						<?php endif; ?>
					</div>

					<h2 class="mt-spacing-3 max-w-3xl text-headline-large text-on-surface line-clamp-3 sm:text-display-small lg:text-display-medium">
						<?php echo esc_html( $title ); ?>
					</h2>

					<?php if ( $excerpt ) : ?>
						<p class="mt-spacing-3 max-w-prose text-body-large text-on-surface-variant line-clamp-2">
							<?php echo esc_html( $excerpt ); ?>
						</p>
					<?php endif; ?>

					<div class="mt-spacing-3 flex flex-wrap items-center gap-x-spacing-3 gap-y-spacing-1 text-body-medium text-on-surface-variant">
						<time datetime="<?php echo esc_attr( $date_iso ); ?>"><?php echo esc_html( $date_human ); ?></time>
						<?php if ( $read_time ) : ?>
							<span aria-hidden="true">•</span>
							<span><?php echo esc_html( $read_time ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</a>
</article>
