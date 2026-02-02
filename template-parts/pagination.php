<?php
/**
 * Pagination.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$prev = get_previous_posts_link();
$next = get_next_posts_link();
if ( ! $prev && ! $next ) {
	return;
}
?>
<nav class="mt-8 flex justify-center gap-4 text-label-medium" aria-label="<?php esc_attr_e( 'Posts navigation', 'jagawarta' ); ?>">
	<?php if ( $prev ) : ?>
		<span class="prev"><?php echo wp_kses_post( $prev ); ?></span>
	<?php endif; ?>
	<?php if ( $next ) : ?>
		<span class="next"><?php echo wp_kses_post( $next ); ?></span>
	<?php endif; ?>
</nav>
