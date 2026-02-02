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

$hero_count    = (int) get_theme_mod( 'jagawarta_hero_count', 5 );
$latest_count  = (int) get_theme_mod( 'jagawarta_latest_count', 10 );
$ticker_count  = (int) get_theme_mod( 'jagawarta_ticker_count', 5 );
$ticker_on     = (bool) get_theme_mod( 'jagawarta_ticker_on_front', true );
$section_count = (int) get_theme_mod( 'jagawarta_section_count', 3 );

$featured_ids  = jagawarta_get_featured_posts( $hero_count );
$latest_query  = jagawarta_get_latest( $latest_count );
$breaking_ids  = $ticker_on ? jagawarta_get_breaking_posts( $ticker_count ) : array();
?>

<main id="main" class="site-main layout-content layout-section flex flex-col gap-12 sm:gap-14">
	<?php if ( $ticker_on && ! empty( $breaking_ids ) ) : ?>
		<?php get_template_part( 'template-parts/breaking-ticker', null, array( 'post_ids' => $breaking_ids ) ); ?>
	<?php endif; ?>
	<?php if ( ! empty( $featured_ids ) ) : ?>
		<section class="flex flex-col gap-6 sm:gap-8">
			<?php
			$hero_slider = (bool) get_theme_mod( 'jagawarta_front_hero_slider', true );
			if ( $hero_slider ) {
				get_template_part( 'template-parts/hero-slider', null, array( 'post_ids' => $featured_ids, 'slider' => true ) );
			} else {
				get_template_part( 'template-parts/hero', null, array( 'featured_id' => (int) $featured_ids[0] ) );
			}
			?>
			<?php get_template_part( 'template-parts/ad-slot', null, array( 'slot' => 'after-hero' ) ); ?>
		</section>
	<?php else : ?>
		<?php get_template_part( 'template-parts/hero', null, array() ); ?>
		<?php get_template_part( 'template-parts/ad-slot', null, array( 'slot' => 'after-hero' ) ); ?>
	<?php endif; ?>
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
