<?php
/**
 * Site header / nav.
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}
?>
<header id="site-header" class="jw-site-header sticky top-0 z-50 bg-surface-low shadow-elevation-1 border-b border-outline-variant transition-shadow duration-short ease-standard relative" role="banner" data-jagawarta-header data-jagawarta-nav>
	<div class="w-full h-full px-4 md:px-6 flex items-center pointer-events-auto relative" style="min-height: 64px;">
		<div id="header-main-content" class="relative z-40 w-full h-full grid grid-cols-[1fr_auto] gap-4 items-center">
			<div class="flex items-center gap-2 md:gap-6 min-w-0 order-1">
				<button id="mobile-menu-btn" type="button" data-jagawarta-nav-toggle class="md:hidden inline-flex h-12 w-12 items-center justify-center rounded-full text-on-surface hover:bg-surface-high focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 transition-opacity duration-short flex-shrink-0" aria-controls="mobile-nav-panel" aria-expanded="false" aria-label="<?php esc_attr_e('Menu', 'jagawarta'); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
				</button>
				<div id="header-logo" class="flex-shrink-0 z-50 transition-all duration-short absolute left-1/2 -translate-x-1/2 md:static md:left-auto md:translate-x-0">
					<a href="<?php echo esc_url(home_url('/')); ?>" class="group flex items-center gap-3 focus:outline-none" rel="home">
						<?php
$logo_url = get_template_directory_uri() . '/assets/images/logo_jwid_color.svg';
if (has_custom_logo()) {
	$custom_logo_id = get_theme_mod('custom_logo');
	$image = wp_get_attachment_image_src($custom_logo_id, 'full');
	if ($image) {
		$logo_url = $image[0];
	}
}
?>
						<img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" class="object-contain h-8 w-auto max-w-[200px]">
						<span class="text-title-large font-normal text-on-surface-variant tracking-tight group-hover:text-primary transition-colors hidden sm:inline">
							<?php bloginfo('name'); ?>
						</span>
					</a>
				</div>
				<nav id="header-nav" class="hidden md:flex md:ml-8 transition-all duration-expand ease-[cubic-bezier(0.2,0.0,0,1.0)] transform translate-x-0" aria-label="<?php esc_attr_e('Primary', 'jagawarta'); ?>">
					<ul class="jw-nav-menu flex items-center gap-1 list-none m-0 p-0" role="menubar">
						<?php
if (has_nav_menu('primary')) {
	wp_nav_menu(array(
		'theme_location' => 'primary',
		'container' => false,
		'items_wrap' => '%3$s',
		'depth' => 0,
		'walker' => new JagaWarta_Nav_Walker(),
	));
}
else {
	$categories = get_categories(array('number' => 5, 'orderby' => 'count', 'order' => 'DESC'));
	$is_front = is_front_page();
	if ($categories) {
		$active_class = $is_front ? ' font-medium text-primary bg-surface-high' : '';
		echo '<li><a href="' . esc_url(home_url('/')) . '" class="jw-nav-link px-3 py-2 text-label-large font-normal text-on-surface hover:text-primary hover:bg-surface-high rounded-full transition-all duration-short ease-standard inline-block' . $active_class . '">' . esc_html__('Home', 'jagawarta') . '</a></li>';
		foreach ($categories as $cat) {
			$active = is_category($cat->term_id);
			$active_class = $active ? ' font-medium text-primary bg-surface-high' : '';
			echo '<li><a href="' . esc_url(get_category_link($cat->term_id)) . '" class="jw-nav-link px-3 py-2 text-label-large font-normal text-on-surface hover:text-primary hover:bg-surface-high rounded-full transition-all duration-short ease-standard inline-block' . $active_class . '">' . esc_html($cat->name) . '</a></li>';
		}
	}
}
?>
					</ul>
				</nav>
			</div>

			<div class="flex items-center gap-2 justify-end min-w-0 order-2">
				<div id="header-search-container" class="transition-all duration-long ease-[cubic-bezier(0.2,0.0,0,1.0)]">
					<div id="header-search-inner" class="relative flex items-center w-auto h-full transition-all duration-long">
						<form role="search" method="get" class="flex items-center w-full h-full" action="<?php echo esc_url(home_url('/')); ?>">
							<label for="header-search" class="sr-only"><?php esc_html_e('Search', 'jagawarta'); ?></label>
							<div id="search-wrapper" class="relative flex items-center h-12 w-12 rounded-full transition-[background-color,box-shadow,padding,width,height] duration-short ease-[cubic-bezier(0.2,0.0,0,1.0)] group cursor-pointer justify-center px-0 bg-transparent border-0 hover:bg-surface-high text-on-surface md:w-auto md:justify-start md:px-4 md:bg-surface-low md:border md:border-outline-variant md:text-on-surface-variant md:hover:bg-surface-mid md:hover:border-outline">
								<svg id="search-icon" class="h-6 w-6 min-h-6 min-w-6 text-current flex-shrink-0 group-hover:text-primary transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" aria-hidden="true"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
								<span id="search-placeholder-text" class="hidden md:block ml-3 text-body-medium text-current select-none whitespace-nowrap transition-opacity duration-medium group-hover:text-on-surface">Search</span>
								<input type="search" id="header-search" name="s" class="absolute inset-0 w-full h-full bg-transparent pl-14 pr-12 text-body-medium text-on-surface placeholder-transparent outline-none opacity-0 pointer-events-none transition-opacity duration-medium delay-short" placeholder="<?php esc_attr_e('Search', 'jagawarta'); ?>" autocomplete="off" />
								<button type="button" id="search-close-btn" class="hidden absolute right-3 top-1/2 -translate-y-1/2 p-2 text-on-surface-variant hover:text-on-surface rounded-full hover:bg-surface-mid outline-none focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 transition-all opacity-0" onmousedown="event.preventDefault();">
									<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
								</button>

								
								<div id="search-results" class="hidden absolute top-full left-0 w-full bg-surface shadow-elevation-3 rounded-xl mt-2 overflow-hidden z-50 border-none outline-none max-h-results-max overflow-y-auto"></div>
							</div>
						</form>
					</div>
				</div>
				<div class="flex items-center">
					<?php jagawarta_part('template-parts/components/dark-mode-toggle'); ?>
				</div>
			</div>
		</div>

		<div id="search-scrim" data-jagawarta-search-scrim class="jw-search-scrim" hidden aria-hidden="true"></div>
		<div data-jagawarta-nav-scrim class="jw-nav-scrim md:hidden" hidden aria-hidden="true"></div>
		<div id="mobile-nav-panel" data-jagawarta-nav-panel class="jw-mobile-nav-panel md:hidden" hidden aria-hidden="true">
			<nav class="jw-mobile-nav-inner" aria-label="<?php esc_attr_e('Mobile', 'jagawarta'); ?>">
				<ul data-jagawarta-nav-menu class="jw-nav-menu jw-mobile-nav-list" role="menu">
					<?php
if (has_nav_menu('primary')) {
	wp_nav_menu(array(
		'theme_location' => 'primary',
		'container' => false,
		'items_wrap' => '%3$s',
		'depth' => 0,
		'walker' => new JagaWarta_Mobile_Nav_Walker(),
	));
}
else {
	$categories = get_categories(array('number' => 8, 'orderby' => 'count', 'order' => 'DESC'));
	$is_front = is_front_page();
	if ($categories) {
		$active_class = $is_front ? ' font-medium text-primary bg-surface-high' : '';
		echo '<li role="none"><a role="menuitem" href="' . esc_url(home_url('/')) . '" class="jw-nav-link font-normal rounded-full' . $active_class . '">' . esc_html__('Home', 'jagawarta') . '</a></li>';
		foreach ($categories as $cat) {
			$active = is_category($cat->term_id);
			$active_class = $active ? ' font-medium text-primary bg-surface-high' : '';
			echo '<li role="none"><a role="menuitem" href="' . esc_url(get_category_link($cat->term_id)) . '" class="jw-nav-link font-normal rounded-full' . $active_class . '">' . esc_html($cat->name) . '</a></li>';
		}
	}
}
?>
				</ul>
			</nav>
		</div>
</header>
