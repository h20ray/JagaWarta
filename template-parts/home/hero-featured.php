<?php
/**
 * Hero Featured (single post, no slider)
 * Expects: $args['post_id'] (int)
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_id = isset( $args['post_id'] ) ? (int) $args['post_id'] : 0;
if ( ! $post_id ) {
	return;
}

$permalink  = get_permalink( $post_id );
$title      = get_the_title( $post_id );
$excerpt    = wp_strip_all_tags( get_the_excerpt( $post_id ) );
$date_iso   = get_the_date( DATE_W3C, $post_id );
$date_human = get_the_date( '', $post_id );

$category = get_the_category( $post_id );
$cat      = $category ? $category[0] : null;

$read_time = function_exists( 'jagawarta_read_time_label' )
	? jagawarta_read_time_label( $post_id )
	: '';
?>
<section class="bg-surface" aria-label="<?php esc_attr_e( 'Featured Story', 'jagawarta' ); ?>">
	<div class="mx-auto max-w-screen-xl px-4 py-6">
		<article class="relative overflow-hidden rounded-md bg-surface-high ring-1 ring-outline-variant">
			<a href="<?php echo esc_url( $permalink ); ?>" class="block focus:outline-none">
				<div class="relative jw-media-wrap">
					<?php
					$display = function_exists( 'jagawarta_get_post_display_image' ) ? jagawarta_get_post_display_image( $post_id ) : array( 'attachment_id' => 0, 'url' => '' );
					if ( ! empty( $display['url'] ) ) :
						if ( ! empty( $display['attachment_id'] ) ) :
							jagawarta_the_image(
								$display['attachment_id'],
								array(
									'lcp'   => true,
									'size'  => 'large',
									'sizes' => '100vw',
									'class' => 'h-hero-mobile w-full object-cover sm:h-hero-sm lg:h-hero-lg',
								)
							);
						else :
							?>
							<img src="<?php echo esc_url( $display['url'] ); ?>" alt="" loading="eager" fetchpriority="high" decoding="async" class="h-hero-mobile w-full object-cover sm:h-hero-sm lg:h-hero-lg" />
							<?php
						endif;
						?>
						<div class="absolute inset-0 bg-scrim/40"></div>
						<?php if ( $cat ) : ?>
							<div class="jw-chip-overlay">
								<?php jagawarta_the_category_chip( $cat, array( 'size' => 'small', 'show_link' => false ) ); ?>
							</div>
						<?php endif; ?>
					<?php else : ?>
						<div class="h-hero-mobile w-full bg-surface-high sm:h-hero-sm lg:h-hero-lg"></div>
					<?php endif; ?>

					<div class="absolute inset-0 flex items-end">
						<div class="w-full p-5 sm:p-6 lg:p-8">
							<div class="flex flex-wrap items-center gap-2">
								<time datetime="<?php echo esc_attr( $date_iso ); ?>" class="text-label-small text-on-surface-variant">
									<?php echo esc_html( $date_human ); ?>
								</time>

								<?php if ( $read_time ) : ?>
									<span aria-hidden="true" class="text-on-surface-variant">•</span>
									<span class="text-label-small text-on-surface-variant">
										<?php echo esc_html( $read_time ); ?>
									</span>
								<?php endif; ?>
							</div>

							<h1 class="mt-3 max-w-3xl text-headline-large text-on-surface sm:text-display-small lg:text-display-medium">
								<?php echo esc_html( $title ); ?>
							</h1>

							<?php if ( $excerpt ) : ?>
								<p class="mt-3 max-w-prose text-body-large text-on-surface-variant line-clamp-2">
									<?php echo esc_html( $excerpt ); ?>
								</p>
							<?php endif; ?>

							<div class="mt-4 inline-flex items-center rounded-sm bg-primary-container px-3 py-2 text-body-medium text-on-primary-container">
								<?php esc_html_e( 'Read story', 'jagawarta' ); ?>
							</div>
						</div>
					</div>
				</div>
			</a>
		</article>
	</div>
</section>
