<?php
/**
 * Related posts — receives pre-fetched post IDs in args.
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
<section class="layout-section-tight border-t border-outline-variant" aria-labelledby="related-heading">
	<h2 id="related-heading" class="section-heading"><?php esc_html_e( 'Related', 'jagawarta' ); ?></h2>
	<ul class="grid gap-6 list-none m-0 p-0 sm:grid-cols-3">
		<?php
		foreach ( $post_ids as $pid ) {
			$post = get_post( $pid );
			if ( ! $post ) {
				continue;
			}
			setup_postdata( $post );
			?>
			<li class="flex">
				<?php get_template_part( 'template-parts/post-card' ); ?>
			</li>
			<?php
		}
		wp_reset_postdata();
		?>
	</ul>
</section>
