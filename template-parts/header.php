<?php
/**
 * Site header / nav.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<header class="border-b border-outline-variant bg-surface-high" role="banner">
	<div class="layout-content flex items-center justify-between py-3 sm:py-4">
		<?php if ( has_custom_logo() ) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-title-large text-primary hover:text-on-surface-variant transition-colors duration-short ease-standard" rel="home"><?php bloginfo( 'name' ); ?></a>
		<?php endif; ?>
		<nav data-jagawarta-nav class="flex items-center gap-4" aria-label="<?php esc_attr_e( 'Primary', 'jagawarta' ); ?>">
			<button type="button" data-jagawarta-nav-toggle class="md:hidden btn" aria-controls="nav-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'jagawarta' ); ?></button>
			<ul id="nav-menu" data-jagawarta-nav-menu class="hidden md:flex gap-6 list-none m-0 p-0 items-center [&_a]:text-on-surface [&_a:hover]:text-primary [&_a]:transition-colors [&_a]:duration-short [&_a]:ease-standard" role="menubar">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'     => false,
					'items_wrap'    => '%3$s',
					'fallback_cb'   => 'jagawarta_fallback_menu',
				) );
				?>
			</ul>
			<form role="search" method="get" class="flex items-center gap-1" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label for="header-search" class="screen-reader-text"><?php esc_html_e( 'Search', 'jagawarta' ); ?></label>
				<input type="search" id="header-search" name="s" class="input w-28 md:w-36" placeholder="<?php esc_attr_e( 'Search…', 'jagawarta' ); ?>" />
				<button type="submit" class="btn btn-filled"><?php esc_html_e( 'Search', 'jagawarta' ); ?></button>
			</form>
		</nav>
	</div>
</header>
