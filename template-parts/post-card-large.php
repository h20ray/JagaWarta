<?php
/**
 * Large post card — hero. Expects global $post.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<a href="<?php the_permalink(); ?>" class="block group rounded-md border border-outline-variant bg-surface-mid p-4 shadow-elevation-1 hover:shadow-elevation-2 hover:border-outline transition-all duration-short ease-standard">
	<?php
	$display_img = jagawarta_get_post_display_image( get_post() );
	if ( ! empty( $display_img['url'] ) ) :
		?>
		<div class="jagawarta-img-wrap jagawarta-img-wrap--hero mb-3">
			<?php jagawarta_the_post_display_image( get_post(), array( 'lcp' => true ) ); ?>
		</div>
	<?php endif; ?>
	<h2 class="text-headline-small text-on-surface group-hover:text-primary group-hover:underline transition-colors duration-short ease-standard"><?php the_title(); ?></h2>
	<p class="mt-1 text-label-medium text-on-surface-variant"><?php echo esc_html( jagawarta_format_date() ); ?></p>
	<?php
	if ( apply_filters( 'jagawarta_show_view_count_in_cards', true ) ) {
		$views = jagawarta_view_count_display( get_the_ID() );
		if ( '' !== $views ) {
			$count = (int) str_replace( ',', '', $views );
			echo '<p class="mt-0.5 text-label-small text-on-surface-variant">' . esc_html( sprintf( _n( '%s view', '%s views', $count, 'jagawarta' ), $views ) ) . '</p>';
		}
	}
	?>
	<?php if ( has_excerpt() ) : ?>
		<p class="mt-2 text-body-medium text-on-surface-variant"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
	<?php endif; ?>
</a>
