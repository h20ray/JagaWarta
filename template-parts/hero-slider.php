<?php
/**
 * Hero slider or grid — multiple featured posts. Load only when theme mod slider/grid is on.
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
	?>
	<section class="hero hero--slider -mx-4 sm:-mx-6" aria-label="<?php esc_attr_e( 'Featured', 'jagawarta' ); ?>">
		<div data-jagawarta-hero-slider class="splide">
			<div class="splide__track">
				<ul class="splide__list">
					<?php
					foreach ( $post_ids as $pid ) {
						$post = get_post( $pid );
						if ( ! $post ) {
							continue;
						}
						setup_postdata( $post );
						?>
						<li class="splide__slide">
							<?php get_template_part( 'template-parts/post-card-large' ); ?>
						</li>
						<?php
						wp_reset_postdata();
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
	<section class="hero grid gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3 lg:gap-8" aria-label="<?php esc_attr_e( 'Featured', 'jagawarta' ); ?>">
		<div class="min-w-0 lg:col-span-2">
			<?php
			$post = get_post( $first_id );
			if ( $post ) {
				setup_postdata( $post );
				get_template_part( 'template-parts/post-card-large' );
				wp_reset_postdata();
			}
			?>
		</div>
		<?php if ( ! empty( $rest ) ) : ?>
			<div class="grid min-w-0 gap-4 sm:grid-cols-2 lg:grid-cols-1">
				<?php
				foreach ( $rest as $pid ) {
					$post = get_post( $pid );
					if ( ! $post ) {
						continue;
					}
					setup_postdata( $post );
					?>
					<div><?php get_template_part( 'template-parts/post-card', null, array( 'post_id' => $pid ) ); ?></div>
					<?php
					wp_reset_postdata();
				}
				?>
			</div>
		<?php endif; ?>
	</section>
	<?php
endif;
