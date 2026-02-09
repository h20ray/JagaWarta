<?php
/**
 * Top Featured Section (no slider).
 *
 * Expects: $args['slider_ids'] = array<int>, $args['side_ids'] = array<int> (2 posts)
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$slider_ids = isset( $args['slider_ids'] ) ? array_map( 'intval', (array) $args['slider_ids'] ) : array();
$side_ids   = isset( $args['side_ids'] ) ? array_map( 'intval', (array) $args['side_ids'] ) : array();
?>
<section class="bg-surface">
	<div class="mx-auto max-w-screen-xl px-4 py-6">
		<div class="grid gap-4 lg:grid-cols-[2fr,1fr]">
			<div>
				<?php get_template_part( 'template-parts/home/hero-slider', null, array( 'ids' => $slider_ids ) ); ?>
			</div>

			<div class="grid gap-4 lg:grid-rows-2">
				<?php if ( ! empty( $side_ids[0] ) ) : ?>
					<?php get_template_part( 'template-parts/cards/card-compact-overlay', null, array( 'post_id' => $side_ids[0] ) ); ?>
				<?php endif; ?>

				<?php if ( ! empty( $side_ids[1] ) ) : ?>
					<?php get_template_part( 'template-parts/cards/card-compact-overlay', null, array( 'post_id' => $side_ids[1] ) ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
