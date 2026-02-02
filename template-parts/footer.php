<?php
/**
 * Site footer.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php get_template_part( 'template-parts/newsletter-slot', null, array( 'slot' => 'footer' ) ); ?>
<footer class="border-t border-outline-variant bg-surface-low mt-12" role="contentinfo">
	<div class="layout-content layout-section text-center text-body-small text-on-surface-variant">
		&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-primary hover:text-on-surface transition-colors duration-short ease-standard"><?php bloginfo( 'name' ); ?></a>
	</div>
</footer>
