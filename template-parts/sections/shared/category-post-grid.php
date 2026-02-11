<?php
/**
 * Section grid — category + post list. Receives category and post_ids in args.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$category = isset( $args['category'] ) ? $args['category'] : null;
$post_ids = isset( $args['post_ids'] ) && is_array( $args['post_ids'] ) ? $args['post_ids'] : array();
if ( ! $category || empty( $post_ids ) ) {
	return;
}
$term_link = get_category_link( $category->term_id );
?>
<section class="section-grid flex flex-col min-w-0" aria-labelledby="section-<?php echo esc_attr( (string) $category->term_id ); ?>">
	<h2 id="section-<?php echo esc_attr( (string) $category->term_id ); ?>" class="section-heading">
		<a href="<?php echo esc_url( $term_link ); ?>" class="text-primary hover:underline"><?php echo esc_html( $category->name ); ?></a>
	</h2>
	<ul class="grid gap-4 list-none m-0 p-0 sm:grid-cols-2">
		<?php
		foreach ( $post_ids as $pid ) {
			$post = get_post( $pid );
			if ( ! $post ) {
				continue;
			}
			setup_postdata( $post );
			?>
			<li class="flex"><?php jagawarta_part( 'template-parts/cards/archive/post-grid-item' ); ?></li>
			<?php
			wp_reset_postdata();
		}
		?>
	</ul>
	<p class="mt-3 text-sm">
		<a href="<?php echo esc_url( $term_link ); ?>" class="text-on-surface-variant hover:text-primary hover:underline"><?php esc_html_e( 'View all', 'jagawarta' ); ?></a>
	</p>
</section>
