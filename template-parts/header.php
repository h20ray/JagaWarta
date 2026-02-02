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
<header class="sticky top-0 z-50 border-b border-outline-variant bg-surface transition-shadow duration-short ease-standard" role="banner" data-jagawarta-header>
	<div class="layout-content flex h-spacing-20 items-center justify-between">
		<div class="flex items-center gap-spacing-4">
			<button type="button" data-jagawarta-nav-toggle class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-full text-on-surface hover:bg-surface-high focus:outline-none focus-visible:ring-2 focus-visible:ring-primary" aria-controls="nav-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'jagawarta' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
			</button>

			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-title-large font-medium text-on-surface hover:text-primary focus:outline-none focus:underline" rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<nav data-jagawarta-nav class="flex items-center gap-spacing-4" aria-label="<?php esc_attr_e( 'Primary', 'jagawarta' ); ?>">
			<!-- Desktop Menu -->
			<ul id="nav-menu" data-jagawarta-nav-menu class="hidden md:flex items-center gap-spacing-1 list-none m-0 p-0" role="menubar">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'depth'          => 1,
						'link_before'    => '<span class="nav-link px-3 py-2 text-title-small text-on-surface hover:text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard inline-block">',
						'link_after'     => '</span>',
					) );
				} else {
					$categories = get_categories( array( 'number' => 6, 'orderby' => 'count', 'order' => 'DESC' ) );
					$is_front   = is_front_page();
					if ( $categories ) {
						echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="nav-link px-3 py-2 text-title-small text-on-surface hover:text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard inline-block' . ( $is_front ? ' font-semibold text-primary bg-surface-high' : '' ) . '">' . esc_html__( 'Home', 'jagawarta' ) . '</a></li>';
						foreach ( $categories as $cat ) {
							$active = is_category( $cat->term_id );
							echo '<li><a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="nav-link px-3 py-2 text-title-small text-on-surface hover:text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard inline-block' . ( $active ? ' font-semibold text-primary bg-surface-high' : '' ) . '">' . esc_html( $cat->name ) . '</a></li>';
						}
					}
				}
				?>
			</ul>

			<!-- Search Action -->
			<div class="relative flex items-center">
				<form role="search" method="get" class="flex items-center" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label for="header-search" class="sr-only"><?php esc_html_e( 'Search', 'jagawarta' ); ?></label>
					<div class="group relative flex items-center rounded-full border border-outline-variant bg-surface-container-low px-4 py-2 transition-all hover:bg-surface-container focus-within:bg-surface-container focus-within:ring-2 focus-within:ring-primary">
						<svg class="h-5 w-5 text-on-surface-variant" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
						<input type="search" id="header-search" name="s" class="ml-2 w-24 bg-transparent text-body-large text-on-surface placeholder-on-surface-variant outline-none transition-all focus:w-48" placeholder="<?php esc_attr_e( 'Search', 'jagawarta' ); ?>" />
					</div>
				</form>
			</div>
		</nav>
	</div>
</header>
