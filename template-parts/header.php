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
<header id="site-header" class="jw-site-header sticky top-0 z-50 bg-surface shadow-elevation-2 transition-shadow duration-short ease-standard relative" role="banner" data-jagawarta-header data-jagawarta-nav>
	<div class="w-full h-full px-4 md:px-12 flex items-center justify-between pointer-events-auto relative">
		<div id="header-main-content" class="w-full h-full flex items-center justify-between relative">
			<!-- Left: Menu & Nav -->
			<div class="flex items-center justify-start flex-1 md:gap-8 min-w-0">
				<button id="mobile-menu-btn" type="button" data-jagawarta-nav-toggle class="md:hidden inline-flex h-9 w-9 items-center justify-center rounded-full text-on-surface hover:bg-surface-high focus:outline-none focus-visible:ring-2 focus-visible:ring-primary header-transition transition-opacity duration-short" aria-controls="mobile-nav-panel" aria-expanded="false" aria-label="<?php esc_attr_e('Menu', 'jagawarta'); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
				</button>
				
				<div id="header-logo" class="flex-shrink-0 z-50 transition-all duration-short absolute left-1/2 -translate-x-1/2 md:static md:translate-x-0">
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
						<img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" class="object-contain" style="height: 32px; width: auto; max-width: 200px;">
						<span class="text-title-large font-normal text-on-surface-variant tracking-tight group-hover:text-primary transition-colors">
							<?php bloginfo('name'); ?>
						</span>
					</a>
				</div>

				<nav id="header-nav" class="hidden md:flex transition-all duration-expand ease-[cubic-bezier(0.2,0.0,0,1.0)] transform translate-x-0" aria-label="<?php esc_attr_e('Primary', 'jagawarta'); ?>">
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
		$active_class = $is_front ? ' font-medium text-primary bg-surface-high border-b-2 border-primary' : '';
		echo '<li><a href="' . esc_url(home_url('/')) . '" class="jw-nav-link px-4 py-2 text-title-small font-normal text-on-surface hover:text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard inline-block' . $active_class . '">' . esc_html__('Home', 'jagawarta') . '</a></li>';
		foreach ($categories as $cat) {
			$active = is_category($cat->term_id);
			$active_class = $active ? ' font-medium text-primary bg-surface-high border-b-2 border-primary' : '';
			echo '<li><a href="' . esc_url(get_category_link($cat->term_id)) . '" class="jw-nav-link px-4 py-2 text-title-small font-normal text-on-surface hover:text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard inline-block' . $active_class . '">' . esc_html($cat->name) . '</a></li>';
		}
	}
}
?>
					</ul>
				</nav>
			</div>

			<!-- Right: Search & Toggle -->
			<div class="flex items-center gap-2 justify-end">
				<div id="header-search-container" class="transition-all duration-long ease-[cubic-bezier(0.2,0.0,0,1.0)]">
					<div id="header-search-inner" class="relative flex items-center w-auto h-full transition-all duration-long">
						<form role="search" method="get" class="flex items-center w-full h-full" action="<?php echo esc_url(home_url('/')); ?>">
							<label for="header-search" class="sr-only"><?php esc_html_e('Search', 'jagawarta'); ?></label>
							<div id="search-wrapper" class="relative flex items-center h-9 rounded-full transition-[background-color,box-shadow,padding,width,height] duration-short ease-[cubic-bezier(0.2,0.0,0,1.0)] group cursor-pointer w-9 justify-center px-0 bg-transparent border-0 hover:bg-surface-high text-on-surface md:w-auto md:justify-start md:px-4 md:bg-surface-container-low md:border md:border-outline-variant md:text-on-surface-variant md:hover:bg-surface-container md:hover:border-outline">
								<svg id="search-icon" class="h-6 w-6 text-current flex-shrink-0 group-hover:text-primary transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
								<span id="search-placeholder-text" class="hidden md:block ml-3 text-body-medium text-current select-none whitespace-nowrap transition-opacity duration-medium group-hover:text-on-surface">Search</span>
								<input type="search" id="header-search" name="s" class="absolute inset-0 w-full h-full bg-transparent pl-14 pr-12 text-body-medium text-on-surface placeholder-transparent outline-none opacity-0 pointer-events-none transition-opacity duration-medium delay-short" placeholder="<?php esc_attr_e('Search', 'jagawarta'); ?>" autocomplete="off" />
								<button type="button" id="search-close-btn" class="hidden absolute right-3 top-1/2 -translate-y-1/2 p-2 text-on-surface-variant hover:text-on-surface rounded-full hover:bg-surface-variant outline-none focus:outline-none focus:bg-surface-variant transition-all opacity-0" onmousedown="event.preventDefault();">
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
		echo '<li role="none"><a role="menuitem" href="' . esc_url(home_url('/')) . '" class="jw-nav-link font-normal' . $active_class . '">' . esc_html__('Home', 'jagawarta') . '</a></li>';
		foreach ($categories as $cat) {
			$active = is_category($cat->term_id);
			$active_class = $active ? ' font-medium text-primary bg-surface-high' : '';
			echo '<li role="none"><a role="menuitem" href="' . esc_url(get_category_link($cat->term_id)) . '" class="jw-nav-link font-normal' . $active_class . '">' . esc_html($cat->name) . '</a></li>';
		}
	}
}
?>
				</ul>
			</nav>
		</div>

	<script>
		(function() {
			const nav = document.getElementById('header-nav');
			const logo = document.getElementById('header-logo');
			const menuBtn = document.getElementById('mobile-menu-btn');
			const searchContainer = document.getElementById('header-search-container');
			const searchWrapper = document.getElementById('search-wrapper');
			const searchInput = document.getElementById('header-search');
			const closeBtn = document.getElementById('search-close-btn');
			const resultsContainer = document.getElementById('search-results');
			const ajaxUrl = "<?php echo esc_url(admin_url('admin-ajax.php')); ?>";
			let debounceTimer;

			const searchPlaceholder = document.getElementById('search-placeholder-text');

			const BORDER_FADE_MS = 100;
			const EXPAND_DURATION_MS = 200;

			function enableSearch() {
				const pillWidth = searchWrapper.getBoundingClientRect().width;
				searchWrapper.style.width = pillWidth + 'px';
				searchWrapper.style.minWidth = pillWidth + 'px';

				nav.classList.add('opacity-0', '-translate-x-8', 'pointer-events-none');
				if(logo) logo.classList.add('opacity-0', 'pointer-events-none');
				if(menuBtn) menuBtn.classList.add('opacity-0', 'pointer-events-none');

				// Phase 1: fade border and placeholder
				searchWrapper.classList.add('search-border-fade', 'border-transparent', 'shadow-elevation-1');
				// Remove desktop border class if present
				searchWrapper.classList.remove('md:border-outline-variant'); 
				
				searchPlaceholder.classList.add('opacity-0');

				// Phase 2: expand
				setTimeout(function() {
					// Removed justify-end and ml-4 manipulation for new layout
					searchContainer.classList.add('absolute', 'left-1/2', '-translate-x-1/2', 'z-40', 'w-auto', 'h-full', 'flex', 'items-center', 'justify-center', 'pointer-events-none');

					searchWrapper.style.width = '';
					searchWrapper.style.minWidth = '';
					searchWrapper.classList.remove('search-border-fade', 'border', 'border-transparent', 'shadow-elevation-1');
					
					// Remove all responsive styling classes to reset to expanded state
					searchWrapper.classList.remove(
						'w-9', 'justify-center', 'px-0', 'bg-transparent', 'border-0', 'hover:bg-surface-high', 'text-on-surface',
						'md:w-auto', 'md:justify-start', 'md:px-4', 'md:bg-surface-container-low', 'md:border', 'md:border-outline-variant', 'md:text-on-surface-variant', 'md:hover:bg-surface-container', 'md:hover:border-outline'
					);

					// Add expanded classes
					searchWrapper.classList.add('w-[90vw]', 'md:w-search-width', 'h-12', 'bg-surface', 'shadow-elevation-2', 'border-0', 'pl-6', 'pr-0', 'cursor-text', 'pointer-events-auto', 'text-on-surface');

					searchInput.classList.remove('opacity-0', 'pointer-events-none');
					searchInput.classList.add('opacity-100', 'pointer-events-auto');
					closeBtn.classList.remove('hidden', 'opacity-0');
					closeBtn.classList.add('flex', 'opacity-100');
					searchInput.focus();
				}, BORDER_FADE_MS);
			}

			function disableSearch() {
				nav.classList.remove('opacity-0', '-translate-x-8', 'pointer-events-none');
				if(logo) logo.classList.remove('opacity-0', 'pointer-events-none');
				if(menuBtn) menuBtn.classList.remove('opacity-0', 'pointer-events-none');

				// Removed justify-end and ml-4 re-addition
				searchContainer.classList.remove('absolute', 'left-1/2', '-translate-x-1/2', 'z-40', 'w-auto', 'h-full', 'flex', 'items-center', 'justify-center', 'pointer-events-none');

				// Phase 1: collapse
				searchWrapper.style.width = '';
				searchWrapper.style.minWidth = '';
				searchWrapper.classList.remove('w-[90vw]', 'md:w-search-width', 'h-12', 'bg-surface', 'shadow-elevation-2', 'border-0', 'pl-6', 'pr-0', 'cursor-text', 'pointer-events-auto', 'text-on-surface');
				
				// Restore responsive classes
				searchWrapper.classList.add(
					'w-9', 'justify-center', 'px-0', 'bg-transparent', 'border-0', 'hover:bg-surface-high', 'text-on-surface', 'cursor-pointer',
					'md:w-auto', 'md:justify-start', 'md:px-4', 'md:bg-surface-container-low', 'md:border', 'md:text-on-surface-variant', 'md:hover:bg-surface-container', 'md:hover:border-outline'
				);
				
				// Initially transparent border for fade in
				searchWrapper.classList.add('border-transparent');

				// Phase 2: fade border in
				setTimeout(function() {
					searchWrapper.classList.add('search-border-fade');
					searchWrapper.classList.remove('border-transparent');
					// Only add visible border color on desktop
					searchWrapper.classList.add('md:border-outline-variant');
					
					setTimeout(function() {
						searchWrapper.classList.remove('search-border-fade');
					}, BORDER_FADE_MS);
				}, EXPAND_DURATION_MS);

				searchPlaceholder.classList.remove('opacity-0');
				searchInput.classList.add('opacity-0', 'pointer-events-none');
				searchInput.classList.remove('opacity-100', 'pointer-events-auto');
				closeBtn.classList.add('hidden', 'opacity-0');
				closeBtn.classList.remove('flex', 'opacity-100');
				searchInput.value = '';
				resultsContainer.classList.add('hidden');
				resultsContainer.innerHTML = '';
			}

			// Trigger on Wrapper Click (if closed)
			searchWrapper.addEventListener('click', function(e) {
				// We can check if input is invisible or wrapper key class
				if (searchInput.classList.contains('opacity-0')) {
					enableSearch();
				}
			});
			
			// Trigger on Placeholder Click
			searchPlaceholder.addEventListener('click', function(e) {
				e.stopPropagation();
				enableSearch();
			});

			closeBtn.addEventListener('click', function(e) {
				e.stopPropagation(); // Prevent wrapper click
				disableSearch();
			});
			
			// Close behavior
			document.addEventListener('keydown', (e) => {
				if (e.key === 'Escape') disableSearch();
			});
			
			document.addEventListener('click', function(e) {
				// Close if clicking outside wrapper AND wrapper is expanded (input is visible)
				if (!searchWrapper.contains(e.target) && !searchInput.classList.contains('opacity-0')) {
					disableSearch();
				}
			});

			// --- Live Search Logic (AJAX) ---
			function renderSkeleton() {
				const skeletonHtml = Array(3).fill(0).map(() => `
					<div class="flex items-center gap-4 p-3 animate-pulse">
						<div class="w-12 h-12 rounded-md bg-surface-variant flex-shrink-0"></div>
						<div class="flex flex-col flex-1 gap-2">
							<div class="h-4 bg-surface-variant rounded w-3/4"></div>
							<div class="h-3 bg-surface-variant rounded w-1/4"></div>
						</div>
					</div>
				`).join('');
				
				resultsContainer.innerHTML = `<div class="p-2">${skeletonHtml}</div>`;
				resultsContainer.classList.remove('hidden');
				resultsContainer.classList.add('animate-fade-in'); 
			}

			function renderResults(data, isFallback = false) {
				const results = Array.isArray(data) ? data : []; // Safety
				
				if ( results.length === 0 ) {
					resultsContainer.innerHTML = '<div class="p-4 text-body-medium text-on-surface-variant text-center">No results found.</div>';
					resultsContainer.classList.remove('hidden');
					return;
				}

				let headerHtml = '';
				if (isFallback) {
					headerHtml = '<div class="px-4 py-2 text-label-medium text-on-surface-variant border-b border-outline-variant">No results found. Recommended:</div>';
				}

				const html = results.map(item => `
					<a href="${item.url}" class="flex items-center gap-4 p-3 hover:bg-surface-container-high rounded-lg transition-colors group decoration-none border-none outline-none">
						${item.thumb ? `<img src="${item.thumb}" alt="" class="w-12 h-12 rounded-md object-cover bg-surface-container-highest flex-shrink-0 shadow-sm">` : ''}
						<div class="flex flex-col">
							<span class="text-body-medium font-medium text-on-surface line-clamp-2 leading-snug group-hover:text-primary transition-colors">${item.title}</span>
							<span class="text-label-small text-on-surface-variant mt-0.5">${item.date}</span>
						</div>
					</a>
				`).join('');

				resultsContainer.innerHTML = `<div class="p-1 flex flex-col border-none outline-none">${headerHtml}${html}</div>`;
				resultsContainer.classList.remove('hidden');
			}

			function performSearch(term) {
				if (term.length < 3) {
					resultsContainer.classList.add('hidden');
					return;
				}
				renderSkeleton();
				fetch(`${ajaxUrl}?action=jagawarta_live_search&s=${encodeURIComponent(term)}`)
					.then(response => response.json())
					.then(res => {
						// Structure is now: { success: true, data: { data: [...], is_fallback: bool } }
						if (res.success && res.data) {
							// If we changed structure, res.data IS the array from wp_send_json_success(array(...))
							// My php: wp_send_json_success( array( 'data' => ..., 'is_fallback' => ...) )
							// So JS res.data matches that PHP array.
							const posts = res.data.data;
							const isFallback = res.data.is_fallback;
							renderResults(posts, isFallback);
						} else {
							renderResults([]); 
						}
					})
					.catch(() => {
						resultsContainer.classList.add('hidden');
					});
			}

			searchInput.addEventListener('input', function(e) {
				clearTimeout(debounceTimer);
				debounceTimer = setTimeout(() => performSearch(e.target.value), 300);
			});
		})();

		(function() {
			const navMenu = document.querySelector('#header-nav .jw-nav-menu');
			if (!navMenu) return;

			const menuItems = navMenu.querySelectorAll('.menu-item-has-children');
			let openItem = null;

			function closeMegaMenu() {
				if (openItem) {
					openItem.classList.remove('is-open');
					openItem.querySelector('a').setAttribute('aria-expanded', 'false');
					openItem = null;
				}
			}

			function openMegaMenu(item) {
				closeMegaMenu();
				item.classList.add('is-open');
				item.querySelector('a').setAttribute('aria-expanded', 'true');
				openItem = item;
			}

			menuItems.forEach(item => {
				const link = item.querySelector('a');
				if (!link) return;

				link.setAttribute('aria-expanded', 'false');
				link.setAttribute('aria-haspopup', 'true');

				link.addEventListener('mouseenter', () => {
					openMegaMenu(item);
				});

				item.addEventListener('mouseleave', () => {
					closeMegaMenu();
				});

				link.addEventListener('click', () => {
					closeMegaMenu();
				});

				link.addEventListener('keydown', (e) => {
					if (e.key === 'Enter' || e.key === ' ') {
						e.preventDefault();
						if (item.classList.contains('is-open')) {
							closeMegaMenu();
						} else {
							openMegaMenu(item);
						}
					} else if (e.key === 'Escape') {
						closeMegaMenu();
						link.focus();
					} else if (e.key === 'ArrowDown') {
						e.preventDefault();
						openMegaMenu(item);
						const firstSubItem = item.querySelector('.jw-mega-menu a');
						if (firstSubItem) firstSubItem.focus();
					}
				});
			});

			document.addEventListener('click', (e) => {
				if (!navMenu.contains(e.target)) {
					closeMegaMenu();
				}
			});

			document.addEventListener('keydown', (e) => {
				if (e.key === 'Escape' && openItem) {
					closeMegaMenu();
				}
			});
		})();
	</script>
</header>
