<?php
/**
 * Hero Slider (manual)
 * Expects: $args['ids'] = array<int>
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$ids = isset( $args['ids'] ) ? array_values( array_filter( array_map( 'intval', (array) $args['ids'] ) ) ) : array();
if ( empty( $ids ) ) {
	return;
}
$splide_opts = array(
	'type'       => 'slide',
	'perPage'    => 1,
	'pagination' => true,
	'arrows'     => true,
	'drag'       => true,
	'rewind'     => true,
	'autoplay'   => false,
	'speed'      => 450,
);
?>
<div class="rounded-md bg-surface-high ring-1 ring-outline-variant overflow-hidden">
	<section
		class="splide js-hero-splide"
		aria-label="<?php esc_attr_e( 'Featured stories', 'jagawarta' ); ?>"
		data-splide="<?php echo esc_attr( wp_json_encode( $splide_opts ) ); ?>">
		<div class="splide__track">
			<ul class="splide__list">
				<?php foreach ( $ids as $i => $post_id ) : ?>
					<li class="splide__slide">
						<?php get_template_part( 'template-parts/cards/post-card-overlay', null, array(
							'post_id' => $post_id,
							'is_lcp'  => ( 0 === $i ),
						) ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
</div>
