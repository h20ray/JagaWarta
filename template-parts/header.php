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
		<div id="header-main-content" class="w-full h-full flex items-center justify-between">
			<div class="flex items-center gap-8 md:gap-12 flex-1 justify-start">
				<button type="button" data-jagawarta-nav-toggle class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-full text-on-surface hover:bg-surface-high focus:outline-none focus-visible:ring-2 focus-visible:ring-primary header-transition" aria-controls="mobile-nav-panel" aria-expanded="false" aria-label="<?php esc_attr_e('Menu', 'jagawarta'); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
				</button>
				<div id="header-logo" class="flex-shrink-0 relative z-50">
					<?php if (has_custom_logo()): ?>
						<?php the_custom_logo(); ?>
					<?php
else: ?>
						<a href="<?php echo esc_url(home_url('/')); ?>" class="text-title-large font-medium text-on-surface hover:text-primary focus:outline-none focus:underline" rel="home">
							<?php bloginfo('name'); ?>
						</a>
					<?php
endif; ?>
				</div>
				<nav id="header-nav" class="hidden md:flex transition-all duration-expand ease-[cubic-bezier(0.2,0.0,0,1.0)] transform translate-x-0" aria-label="<?php esc_attr_e('Primary', 'jagawarta'); ?>">
					<ul class="jw-nav-menu flex items-center gap-1 list-none m-0 p-0" role="menubar">
						<?php
if (has_nav_menu('primary')) {
	wp_nav_menu(array(
		'theme_location' => 'primary',
		'container' => false,
		'items_wrap' => '%3$s',
		'depth' => 1,
		'link_before' => '<span class="jw-nav-link px-4 py-2 text-title-small text-on-surface hover:text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard inline-block">',
		'link_after' => '</span>',
	));
}
else {
	$categories = get_categories(array('number' => 5, 'orderby' => 'count', 'order' => 'DESC'));
	$is_front = is_front_page();
	if ($categories) {
		echo '<li><a href="' . esc_url(home_url('/')) . '" class="jw-nav-link px-4 py-2 text-title-small text-on-surface hover:text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard inline-block' . ($is_front ? ' font-semibold text-primary bg-surface-high' : '') . '">' . esc_html__('Home', 'jagawarta') . '</a></li>';
		foreach ($categories as $cat) {
			$active = is_category($cat->term_id);
			echo '<li><a href="' . esc_url(get_category_link($cat->term_id)) . '" class="jw-nav-link px-4 py-2 text-title-small text-on-surface hover:text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard inline-block' . ($active ? ' font-semibold text-primary bg-surface-high' : '') . '">' . esc_html($cat->name) . '</a></li>';
		}
	}
}
?>
					</ul>
				</nav>
			</div>
			<div id="header-search-container" class="flex items-center justify-end ml-4 transition-all duration-long ease-[cubic-bezier(0.2,0.0,0,1.0)]">
				<div id="header-search-inner" class="relative flex items-center w-auto h-full transition-all duration-long">
					<form role="search" method="get" class="flex items-center w-full h-full" action="<?php echo esc_url(home_url('/')); ?>">
						<label for="header-search" class="sr-only"><?php esc_html_e('Search', 'jagawarta'); ?></label>
						<div id="search-wrapper" class="relative flex items-center h-10 rounded-full border border-outline-variant bg-surface-container-low px-4 py-1.5 cursor-text hover:bg-surface-container hover:border-outline w-auto transition-[background-color,box-shadow,padding,width,height] duration-short ease-[cubic-bezier(0.2,0.0,0,1.0)] group">
							<svg id="search-icon" class="h-6 w-6 text-on-surface-variant flex-shrink-0 group-hover:text-primary transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>							<span id="search-placeholder-text" class="ml-3 text-body-medium text-on-surface-variant select-none whitespace-nowrap transition-opacity duration-medium group-hover:text-on-surface">Search</span>
							<input type="search" id="header-search" name="s" class="absolute inset-0 w-full h-full bg-transparent pl-14 pr-12 text-body-medium text-on-surface placeholder-transparent outline-none opacity-0 pointer-events-none transition-opacity duration-medium delay-short" placeholder="<?php esc_attr_e('Search', 'jagawarta'); ?>" autocomplete="off" />
							<button type="button" id="search-close-btn" class="hidden absolute right-3 top-1/2 -translate-y-1/2 p-2 text-on-surface-variant hover:text-on-surface rounded-full hover:bg-surface-variant outline-none focus:outline-none focus:bg-surface-variant transition-all opacity-0" onmousedown="event.preventDefault();">
								<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
							</button>

							
							<div id="search-results" class="hidden absolute top-full left-0 w-full bg-surface shadow-elevation-3 rounded-xl mt-2 overflow-hidden z-50 border-none outline-none max-h-results-max overflow-y-auto"></div>
						</div>
					</form>
				</div>
			</div>
			<div class="flex items-center gap-2 ml-3">
				<?php get_template_part('template-parts/components/dark-mode-toggle'); ?>
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
		'depth' => 1,
		'link_before' => '<span class="jw-nav-link">',
		'link_after' => '</span>',
	));
}
else {
	$categories = get_categories(array('number' => 8, 'orderby' => 'count', 'order' => 'DESC'));
	$is_front = is_front_page();
	if ($categories) {
		echo '<li role="none"><a role="menuitem" href="' . esc_url(home_url('/')) . '" class="jw-nav-link' . ($is_front ? ' font-semibold text-primary bg-surface-high' : '') . '">' . esc_html__('Home', 'jagawarta') . '</a></li>';
		foreach ($categories as $cat) {
			$active = is_category($cat->term_id);
			echo '<li role="none"><a role="menuitem" href="' . esc_url(get_category_link($cat->term_id)) . '" class="jw-nav-link' . ($active ? ' font-semibold text-primary bg-surface-high' : '') . '">' . esc_html($cat->name) . '</a></li>';
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

				// Phase 1: fade border and placeholder together (pill stays on the right; nothing "drags" to center)
				searchWrapper.classList.add('search-border-fade', 'border-transparent', 'shadow-elevation-1');
				searchWrapper.classList.remove('border-outline-variant');
				searchPlaceholder.classList.add('opacity-0');

				// Phase 2: move to center and expand; then show input
				setTimeout(function() {
					searchContainer.classList.remove('justify-end', 'ml-4');
					searchContainer.classList.add('absolute', 'left-1/2', '-translate-x-1/2', 'z-40', 'w-auto', 'h-full', 'flex', 'items-center', 'justify-center', 'pointer-events-none');

					searchWrapper.style.width = '';
					searchWrapper.style.minWidth = '';
					searchWrapper.classList.remove('search-border-fade', 'border', 'border-transparent', 'shadow-elevation-1');
					searchWrapper.classList.remove('w-auto', 'bg-surface-container-low', 'px-4', 'cursor-text');
					searchWrapper.classList.add('w-[90vw]', 'md:w-search-width', 'h-12', 'bg-surface', 'shadow-elevation-2', 'border-0', 'pl-6', 'pr-0', 'cursor-text', 'pointer-events-auto');

					searchInput.classList.remove('opacity-0', 'pointer-events-none');
					searchInput.classList.add('opacity-100', 'pointer-events-auto');
					closeBtn.classList.remove('hidden', 'opacity-0');
					closeBtn.classList.add('flex', 'opacity-100');
					searchInput.focus();
				}, BORDER_FADE_MS);
			}

			function disableSearch() {
				nav.classList.remove('opacity-0', '-translate-x-8', 'pointer-events-none');
				searchContainer.classList.add('justify-end', 'ml-4');
				searchContainer.classList.remove('absolute', 'left-1/2', '-translate-x-1/2', 'z-40', 'w-auto', 'h-full', 'flex', 'items-center', 'justify-center', 'pointer-events-none');

				// Phase 1: collapse to normal size with border still invisible (border-transparent)
				searchWrapper.style.width = '';
				searchWrapper.style.minWidth = '';
				searchWrapper.classList.remove('w-[90vw]', 'md:w-search-width', 'h-12', 'bg-surface', 'shadow-elevation-2', 'border-0', 'pl-6', 'pr-0', 'cursor-text', 'pointer-events-auto');
				searchWrapper.classList.add('w-auto', 'bg-surface-container-low', 'border', 'border-transparent', 'px-4', 'cursor-text');

				// Phase 2: when shrink done, fade in border
				setTimeout(function() {
					searchWrapper.classList.add('search-border-fade');
					searchWrapper.classList.remove('border-transparent');
					searchWrapper.classList.add('border-outline-variant');
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
	</script>
</header>
