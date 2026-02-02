<?php
/**
 * Front page — Top split (slider + side cards) + Latest stories.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$slider_ids = jagawarta_get_featured_slider_ids( 5 );
if ( empty( $slider_ids ) ) {
	$slider_ids = jagawarta_get_latest_ids_excluding( 5, array() );
}
$side_ids     = jagawarta_get_latest_ids_excluding( 2, $slider_ids );
$exclude_ids  = array_unique( array_merge( $slider_ids, $side_ids ) );
$latest_ids   = jagawarta_get_latest_ids_excluding( 9, $exclude_ids );
?>

<main id="main" class="site-main">
	<?php if ( ! empty( $slider_ids ) ) : ?>
		<?php get_template_part( 'template-parts/home/top-split', null, array(
			'slider_ids' => $slider_ids,
			'side_ids'   => $side_ids,
		) ); ?>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/home/first-grid', null, array(
		'title' => __( 'Latest stories', 'jagawarta' ),
		'ids'   => $latest_ids,
	) ); ?>
</main>

<?php
get_footer();
