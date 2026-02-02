<?php
/**
 * Single: Article Footer (Google Blog Style).
 * Categories and tags with "POSTED IN:" heading.
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
<footer class="mt-spacing-12 pt-spacing-6 border-t border-outline-variant">
	<?php if ( ! empty( $cats ) ) : ?>
		<div class="mb-spacing-6">
			<div class="text-label-medium uppercase tracking-wide text-on-surface-variant mb-spacing-3">
				<?php esc_html_e( 'Posted In:', 'jagawarta' ); ?>
			</div>
			<div class="flex flex-wrap gap-3">
				<?php foreach ( $cats as $cat ) : ?>
					<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="text-primary hover:underline text-body-medium">
						<?php echo esc_html( $cat->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
	
	<?php if ( ! empty( $tags ) ) : ?>
		<div>
			<div class="text-label-medium uppercase tracking-wide text-on-surface-variant mb-spacing-3">
				<?php esc_html_e( 'Tags:', 'jagawarta' ); ?>
			</div>
			<div class="flex flex-wrap gap-3">
				<?php foreach ( $tags as $tag ) : ?>
					<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="text-primary hover:underline text-body-medium">
						<?php echo esc_html( $tag->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</footer>
