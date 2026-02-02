<?php
/**
 * Single: categories, tags.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$cats = get_the_category();
$tags = get_the_tags();
if ( empty( $cats ) && empty( $tags ) ) {
	return;
}
?>
<footer class="mt-8 border-t border-outline-variant pt-6 text-body-small text-on-surface-variant">
	<?php if ( ! empty( $cats ) ) : ?>
		<p class="mb-2">
			<?php esc_html_e( 'Categories:', 'jagawarta' ); ?>
			<?php the_category( ', ' ); ?>
		</p>
	<?php endif; ?>
	<?php if ( ! empty( $tags ) ) : ?>
		<p><?php the_tags( esc_html__( 'Tags: ', 'jagawarta' ), ', ' ); ?></p>
	<?php endif; ?>
</footer>
