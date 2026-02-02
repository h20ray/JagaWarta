<?php
/**
 * Blog index (when front page is static).
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$ticker_on    = (bool) get_theme_mod( 'jagawarta_ticker_on_front', true );
$ticker_count = (int) get_theme_mod( 'jagawarta_ticker_count', 5 );
$breaking_ids = ( is_front_page() && $ticker_on ) ? jagawarta_get_breaking_posts( $ticker_count ) : array();
?>

<main id="main" class="site-main layout-content layout-section flex flex-col gap-10">
	<?php if ( ! empty( $breaking_ids ) ) : ?>
		<?php get_template_part( 'template-parts/breaking-ticker', null, array( 'post_ids' => $breaking_ids ) ); ?>
	<?php endif; ?>
	<header class="pb-4 border-b border-outline-variant mb-2">
		<h1 class="text-headline-medium font-serif text-on-surface"><?php single_post_title(); ?></h1>
	</header>
	<?php if ( have_posts() ) : ?>
		<div class="grid gap-8 sm:gap-10">
			<?php
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content', get_post_type() );
			}
			?>
		</div>
		<?php get_template_part( 'template-parts/pagination' ); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</main>

<?php
get_footer();
