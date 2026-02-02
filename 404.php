<?php
/**
 * 404.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>

<main id="main" class="site-main layout-content max-w-2xl layout-section py-16 text-center">
	<h1 class="text-headline-large font-serif text-on-surface"><?php esc_html_e( 'Page not found', 'jagawarta' ); ?></h1>
	<p class="mt-4 text-body-medium text-on-surface-variant"><?php esc_html_e( 'The page you are looking for does not exist.', 'jagawarta' ); ?></p>
	<p class="mt-6">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-primary font-medium underline"><?php esc_html_e( 'Go home', 'jagawarta' ); ?></a>
	</p>
</main>

<?php
get_footer();
