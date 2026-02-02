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
<footer class="border-t border-outline-variant bg-surface-container-highest mt-spacing-24" role="contentinfo">
	<div class="layout-content py-spacing-16">
		<!-- Footer Grid -->
		<div class="grid grid-cols-1 md:grid-cols-4 gap-spacing-8 mb-spacing-12">
			<!-- About -->
			<div>
				<h3 class="text-title-medium text-on-surface mb-spacing-4"><?php bloginfo( 'name' ); ?></h3>
				<p class="text-body-small text-on-surface-variant leading-relaxed">
					<?php bloginfo( 'description' ); ?>
				</p>
			</div>

			<!-- Categories -->
			<div>
				<h4 class="text-title-small text-on-surface mb-spacing-4"><?php esc_html_e( 'Categories', 'jagawarta' ); ?></h4>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-categories',
					'container'      => false,
					'menu_class'     => 'space-y-2',
					'fallback_cb'    => function() {
						$categories = get_categories( array( 'number' => 5 ) );
						if ( $categories ) {
							echo '<ul class="space-y-2">';
							foreach ( $categories as $cat ) {
								echo '<li><a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="text-body-small text-on-surface-variant hover:text-primary transition-colors duration-short">' . esc_html( $cat->name ) . '</a></li>';
							}
							echo '</ul>';
						}
					},
					'link_before'    => '<span class="text-body-small text-on-surface-variant hover:text-primary transition-colors duration-short">',
					'link_after'     => '</span>',
				) );
				?>
			</div>

			<!-- Quick Links -->
			<div>
				<h4 class="text-title-small text-on-surface mb-spacing-4"><?php esc_html_e( 'Quick Links', 'jagawarta' ); ?></h4>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-links',
					'container'      => false,
					'menu_class'     => 'space-y-2',
					'fallback_cb'    => function() {
						echo '<ul class="space-y-2">';
						echo '<li><a href="' . esc_url( home_url( '/about' ) ) . '" class="text-body-small text-on-surface-variant hover:text-primary transition-colors duration-short">' . esc_html__( 'About', 'jagawarta' ) . '</a></li>';
						echo '<li><a href="' . esc_url( home_url( '/contact' ) ) . '" class="text-body-small text-on-surface-variant hover:text-primary transition-colors duration-short">' . esc_html__( 'Contact', 'jagawarta' ) . '</a></li>';
						echo '<li><a href="' . esc_url( home_url( '/privacy-policy' ) ) . '" class="text-body-small text-on-surface-variant hover:text-primary transition-colors duration-short">' . esc_html__( 'Privacy Policy', 'jagawarta' ) . '</a></li>';
						echo '</ul>';
					},
					'link_before'    => '<span class="text-body-small text-on-surface-variant hover:text-primary transition-colors duration-short">',
					'link_after'     => '</span>',
				) );
				?>
			</div>

			<!-- Social / Connect -->
			<div>
				<h4 class="text-title-small text-on-surface mb-spacing-4"><?php esc_html_e( 'Connect', 'jagawarta' ); ?></h4>
				<div class="flex gap-spacing-3">
					<!-- Add social links here when configured -->
					<p class="text-body-small text-on-surface-variant">
						<?php esc_html_e( 'Follow us on social media', 'jagawarta' ); ?>
					</p>
				</div>
			</div>
		</div>

		<!-- Copyright -->
		<div class="border-t border-outline-variant pt-spacing-6 text-center">
			<p class="text-body-small text-on-surface-variant">
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> 
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-primary hover:text-on-surface transition-colors duration-short ease-standard">
					<?php bloginfo( 'name' ); ?>
				</a>
				<?php esc_html_e( '— Built with performance in mind', 'jagawarta' ); ?>
			</p>
		</div>
	</div>
</footer>
