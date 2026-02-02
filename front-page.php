<?php
/**
 * Front page — hero + sections + latest.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$latest_count   = (int) get_theme_mod( 'jagawarta_latest_count', 10 );
$ticker_count   = (int) get_theme_mod( 'jagawarta_ticker_count', 5 );
$ticker_on      = (bool) get_theme_mod( 'jagawarta_ticker_on_front', true );
$section_count  = (int) get_theme_mod( 'jagawarta_section_count', 3 );

$slider_ids = function_exists( 'jagawarta_get_featured_slider_ids' ) ? jagawarta_get_featured_slider_ids( 5 ) : array();
if ( empty( $slider_ids ) && function_exists( 'jagawarta_get_latest_ids_excluding' ) ) {
	$slider_ids = jagawarta_get_latest_ids_excluding( 5, array() );
}
$side_ids = function_exists( 'jagawarta_get_latest_ids_excluding' ) ? jagawarta_get_latest_ids_excluding( 2, $slider_ids ) : array();

$latest_query  = jagawarta_get_latest( $latest_count );
$breaking_ids  = $ticker_on ? jagawarta_get_breaking_posts( $ticker_count ) : array();
?>

<main id="main" class="site-main layout-content layout-section flex flex-col gap-12 sm:gap-14">
	<?php if ( $ticker_on && ! empty( $breaking_ids ) ) : ?>
		<?php get_template_part( 'template-parts/breaking-ticker', null, array( 'post_ids' => $breaking_ids ) ); ?>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/home/top-featured', null, array( 'slider_ids' => $slider_ids, 'side_ids' => $side_ids ) ); ?>
	<?php get_template_part( 'template-parts/ad-slot', null, array( 'slot' => 'after-hero' ) ); ?>
	<section class="grid gap-8 sm:gap-10 md:grid-cols-2 lg:grid-cols-3 lg:gap-x-10" aria-label="<?php esc_attr_e( 'Category sections', 'jagawarta' ); ?>">
		<?php
		$categories = get_categories( array( 'number' => max( 1, $section_count ), 'orderby' => 'count', 'order' => 'DESC' ) );
		foreach ( $categories as $cat ) :
			$section_ids = jagawarta_get_posts_by_category( $cat->term_id, 4 );
			if ( empty( $section_ids ) ) {
				continue;
			}
			?>
			<?php get_template_part( 'template-parts/section-grid', null, array( 'category' => $cat, 'post_ids' => $section_ids ) ); ?>
		<?php endforeach; ?>
	</section>
	<section class="layout-section-tight border-t border-outline-variant" aria-labelledby="latest-heading">
		<h2 id="latest-heading" class="section-heading"><?php esc_html_e( 'Latest', 'jagawarta' ); ?></h2>
		<?php get_template_part( 'template-parts/latest-list', null, array( 'query' => $latest_query ) ); ?>
	</section>
</main>

<?php
get_footer();
