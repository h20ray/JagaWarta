<?php
/**
 * Hero slider or grid (block/front). Expects $args['post_ids'], $args['slider'].
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_ids = isset( $args['post_ids'] ) && is_array( $args['post_ids'] ) ? $args['post_ids'] : array();
$slider   = ! empty( $args['slider'] );
if ( empty( $post_ids ) ) {
	return;
}

if ( $slider ) :
	$slider_options = array(
		'type'        => 'loop',
		'perPage'     => 1,
		'perMove'     => 1,
		'gap'         => 0,
		'padding'     => 0,
		'arrows'      => true,
		'pagination'  => true,
		'autoplay'    => false,
		'pauseOnHover'=> true,
		'drag'        => true,
		'speed'       => 650,
		'easing'      => 'cubic-bezier(0.2, 0.0, 0, 1.0)',
		'trimSpace'   => true,
		'rewind'      => true,
	);
	?>
	<section class="hero hero--slider" aria-label="<?php esc_attr_e( 'Featured', 'jagawarta' ); ?>">
		<div class="splide js-hero-splide jw-hero-slider" data-splide="<?php echo esc_attr( wp_json_encode( $slider_options ) ); ?>">
			<div class="splide__track">
				<ul class="splide__list">
					<?php
					$slide_index = 0;
					foreach ( $post_ids as $pid ) {
						$post = get_post( $pid );
						if ( ! $post ) {
							continue;
						}
						setup_postdata( $post );
						?>
						<li class="splide__slide">
							<?php
							get_template_part(
								'template-parts/cards/card-overlay',
								null,
								array(
									'post_id' => $pid,
									'is_lcp'  => 0 === $slide_index,
								)
							);
							?>
						</li>
						<?php
						wp_reset_postdata();
						$slide_index++;
					}
					?>
				</ul>
			</div>
		</div>
	</section>
	<?php
else :
	$first_id = $post_ids[0];
	$rest     = array_slice( $post_ids, 1, 4 );
	?>
	<section class="hero grid gap-spacing-4 sm:gap-spacing-6 md:grid-cols-2 lg:grid-cols-3 lg:gap-spacing-8" aria-label="<?php esc_attr_e( 'Featured', 'jagawarta' ); ?>">
		<div class="min-w-0 lg:col-span-2">
			<?php
			$post = get_post( $first_id );
			if ( $post ) {
				setup_postdata( $post );
				get_template_part( 'template-parts/cards/card-hero' );
				wp_reset_postdata();
			}
			?>
		</div>
		<?php if ( ! empty( $rest ) ) : ?>
			<div class="grid min-w-0 gap-spacing-4 sm:grid-cols-2 lg:grid-cols-1">
				<?php
				foreach ( $rest as $pid ) {
					$post = get_post( $pid );
					if ( ! $post ) {
						continue;
					}
					setup_postdata( $post );
					?>
					<div><?php get_template_part( 'template-parts/cards/card-categories', null, array( 'post_id' => $pid ) ); ?></div>
					<?php
					wp_reset_postdata();
				}
				?>
			</div>
		<?php endif; ?>
	</section>
	<?php
endif;
