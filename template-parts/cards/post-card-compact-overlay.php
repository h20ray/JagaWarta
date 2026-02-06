<?php
/**
 * Compact Overlay Card (right column)
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

$category = get_the_category( $post_id );
$cat      = $category ? $category[0] : null;

$read_time = function_exists( 'jagawarta_read_time_label' )
	? jagawarta_read_time_label( $post_id )
	: '';
?>
<article class="relative overflow-hidden rounded-md bg-surface-high ring-1 ring-outline-variant">
	<a href="<?php echo esc_url( $permalink ); ?>" class="block h-full focus:outline-none">
		<div class="relative h-[14rem] sm:h-[16rem] lg:h-full">
			<?php
			$display = function_exists( 'jagawarta_get_post_display_image' ) ? jagawarta_get_post_display_image( $post_id ) : array( 'attachment_id' => 0, 'url' => '' );
			if ( ! empty( $display['url'] ) ) :
				if ( ! empty( $display['attachment_id'] ) ) :
					jagawarta_the_image(
						$display['attachment_id'],
						array(
							'lcp'   => false,
							'size'  => 'medium_large',
							'sizes' => '(max-width: 1024px) 100vw, 320px',
							'class' => 'h-full w-full object-cover',
						)
					);
				else :
					?>
					<img src="<?php echo esc_url( $display['url'] ); ?>" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover" />
					<?php
				endif;
			else :
				?>
				<div class="h-full w-full bg-surface-high"></div>
			<?php endif; ?>

			<div class="absolute inset-0 bg-scrim/45"></div>

			<div class="absolute inset-0 flex items-end">
				<div class="w-full p-4 sm:p-5 lg:p-6">
					<div class="flex flex-wrap items-center gap-2">
						<?php if ( $cat ) : ?>
							<span class="inline-flex items-center rounded-sm bg-secondary-container px-2 py-1 text-[0.75rem] leading-5 text-on-secondary-container">
								<?php echo esc_html( $cat->name ); ?>
							</span>
						<?php endif; ?>
					</div>

					<h3 class="mt-2 text-[1.125rem] leading-6 text-on-surface line-clamp-3">
						<?php echo esc_html( $title ); ?>
					</h3>

					<div class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-[0.75rem] leading-5 text-on-surface-variant">
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
