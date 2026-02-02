<?php
/**
 * Post card — used in loops. Expects global $post or $args['post_id'].
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_id = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$permalink = get_permalink( $post_id );
$title    = get_the_title( $post_id );
$date_iso = get_the_date( DATE_W3C, $post_id );
$date_hr  = get_the_date( '', $post_id );
$category = get_the_category( $post_id );
$cat      = $category ? $category[0] : null;
?>
<article class="group rounded-md bg-surface-low ring-1 ring-outline-variant transition duration-short ease-standard hover:bg-surface-high focus-within:bg-surface-high focus-within:ring-2 focus-within:ring-outline">
	<a href="<?php echo esc_url( $permalink ); ?>" class="block p-4 focus:outline-none">
		<?php
		$card_img = jagawarta_get_post_display_image( $post_id );
		if ( ! empty( $card_img['url'] ) ) :
			?>
			<div class="mb-3 overflow-hidden rounded-md">
				<?php jagawarta_the_post_display_image( $post_id, array( 'lcp' => false, 'class' => 'object-cover' ) ); ?>
			</div>
		<?php endif; ?>

		<div class="flex items-center gap-2">
			<?php if ( $cat ) : ?>
				<span class="inline-flex items-center rounded-sm bg-secondary-container px-2 py-1 text-[0.75rem] leading-5 text-on-secondary-container">
					<?php echo esc_html( $cat->name ); ?>
				</span>
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( $date_iso ); ?>" class="text-[0.75rem] leading-5 text-on-surface-variant">
				<?php echo esc_html( $date_hr ); ?>
			</time>
		</div>

		<h3 class="mt-2 text-[1rem] leading-6 text-on-surface group-hover:underline">
			<?php echo esc_html( $title ); ?>
		</h3>

		<p class="mt-2 line-clamp-2 text-[0.875rem] leading-6 text-on-surface-variant">
			<?php echo esc_html( wp_strip_all_tags( get_the_excerpt( $post_id ) ) ); ?>
		</p>
		<?php
		if ( apply_filters( 'jagawarta_show_view_count_in_cards', true ) ) {
			$views = jagawarta_view_count_display( $post_id );
			if ( '' !== $views ) {
				$count = (int) str_replace( ',', '', $views );
				echo '<p class="mt-1 text-[0.75rem] leading-5 text-on-surface-variant">' . esc_html( sprintf( _n( '%s view', '%s views', $count, 'jagawarta' ), $views ) ) . '</p>';
			}
		}
		?>
	</a>
</article>
