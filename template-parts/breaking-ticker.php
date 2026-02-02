<?php
/**
 * Breaking news ticker. Receives post_ids in args.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_ids = isset( $args['post_ids'] ) && is_array( $args['post_ids'] ) ? $args['post_ids'] : array();
if ( empty( $post_ids ) ) {
	return;
}
?>
<section class="breaking-ticker border-b border-outline-variant bg-tertiary-container py-2 overflow-hidden" aria-label="<?php esc_attr_e( 'Breaking news', 'jagawarta' ); ?>" x-data="{ paused: false }" @mouseenter="paused = true" @mouseleave="paused = false" @focusin="paused = true" @focusout="paused = false">
	<div class="breaking-ticker__wrap flex" data-jagawarta-ticker>
		<div class="breaking-ticker__inner flex gap-8 whitespace-nowrap" :class="{ 'ticker-paused': paused }">
			<?php
			foreach ( array_merge( $post_ids, $post_ids ) as $pid ) {
				$post = get_post( $pid );
				if ( ! $post ) {
					continue;
				}
				?>
				<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="breaking-ticker__item text-label-large text-on-secondary-container hover:text-primary transition-colors duration-short ease-standard">
					<?php echo esc_html( get_the_title( $post ) ); ?>
				</a>
				<?php
			}
			?>
		</div>
	</div>
</section>
