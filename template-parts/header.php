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
<header id="site-header" class="sticky top-0 z-50 bg-surface shadow-[0_2px_5px_0_rgba(0,0,0,0.16)] transition-shadow duration-short ease-standard h-16" role="banner" data-jagawarta-header>
	<div class="w-full h-full px-4 md:px-12 flex items-center justify-between pointer-events-auto relative">
		<!-- 1. MAIN HEADER CONTENT (Visible by default) -->
		<!-- Menu Transition: Slower (700ms) to feel "cool" and match search expand -->
		<div id="header-main-content" class="w-full h-full flex items-center justify-between transition-opacity duration-700 ease-standard">
			<!-- Left Group: Logo & Desktop Menu -->
			<div class="flex items-center gap-8 md:gap-12 flex-1 justify-start">
				<!-- Mobile Toggle -->
				<button type="button" data-jagawarta-nav-toggle class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-full text-on-surface hover:bg-surface-high focus:outline-none focus-visible:ring-2 focus-visible:ring-primary header-transition" aria-controls="nav-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'jagawarta' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
				</button>

				<!-- Logo -->
				<div id="header-logo" class="flex-shrink-0 relative z-50">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-title-large font-medium text-on-surface hover:text-primary focus:outline-none focus:underline" rel="home">
							<?php bloginfo( 'name' ); ?>
						</a>
					<?php endif; ?>
				</div>

				<!-- Desktop Menu (Fades out + Slides when search active) -->
				<!-- Duration 700ms for smooth fade/slide -->
				<nav id="header-nav" class="hidden md:flex transition-all duration-700 ease-[cubic-bezier(0.2,0.0,0,1.0)] transform translate-x-0 opacity-100" aria-label="<?php esc_attr_e( 'Primary', 'jagawarta' ); ?>">
					<ul id="nav-menu" data-jagawarta-nav-menu class="flex items-center gap-1 list-none m-0 p-0" role="menubar">
						<?php
						if ( has_nav_menu( 'primary' ) ) {
							wp_nav_menu( array(
								'theme_location' => 'primary',
								'container'      => false,
								'items_wrap'     => '%3$s',
								'depth'          => 1,
								'link_before'    => '<span class="nav-link px-4 py-2 text-title-small text-on-surface hover:text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard inline-block">',
								'link_after'     => '</span>',
							) );
						} else {
							$categories = get_categories( array( 'number' => 5, 'orderby' => 'count', 'order' => 'DESC' ) );
							$is_front   = is_front_page();
							if ( $categories ) {
								echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="nav-link px-4 py-2 text-title-small text-on-surface hover:text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard inline-block' . ( $is_front ? ' font-semibold text-primary bg-surface-high' : '' ) . '">' . esc_html__( 'Home', 'jagawarta' ) . '</a></li>';
								foreach ( $categories as $cat ) {
									$active = is_category( $cat->term_id );
									echo '<li><a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="nav-link px-4 py-2 text-title-small text-on-surface hover:text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard inline-block' . ( $active ? ' font-semibold text-primary bg-surface-high' : '' ) . '">' . esc_html( $cat->name ) . '</a></li>';
								}
							}
						}
						?>
					</ul>
				</nav>
			</div>

			<!-- Right: Search -->
			<!-- Container transitions from 'justify-end' (implicit) to 'absolute centered' -->
			<div id="header-search-container" class="flex items-center justify-end ml-4 transition-all duration-500 ease-[cubic-bezier(0.2,0.0,0,1.0)]">
				<div id="header-search-inner" class="relative flex items-center w-auto h-full transition-all duration-500">
					<form role="search" method="get" class="flex items-center w-full h-full" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<label for="header-search" class="sr-only"><?php esc_html_e( 'Search', 'jagawarta' ); ?></label>
						
						<!-- Search Wrapper -->
						<!-- Spacing: px-4 default. Expanded: pl-6 pr-12. -->
						<!-- Vertical Align: items-center ensures icon is centered. -->
						<div id="search-wrapper" class="relative flex items-center h-10 rounded-full border border-outline-variant bg-surface-container-low px-4 py-1.5 cursor-text transition-all duration-500 ease-[cubic-bezier(0.2,0.0,0,1.0)] hover:bg-surface-container w-auto">
							<!-- Icon -->
							<svg id="search-icon" class="h-5 w-5 text-on-surface-variant flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
							
							<!-- Fake Text -->
							<span id="search-placeholder-text" class="ml-3 text-body-medium text-on-surface-variant select-none whitespace-nowrap transition-opacity duration-300">Search</span>

							<!-- Real Input -->
							<!-- Spacing: pl-14 to clear icon + spacing. pr-12 for close button. -->
							<input type="search" id="header-search" name="s" class="absolute inset-0 w-full h-full bg-transparent pl-14 pr-12 text-body-medium text-on-surface placeholder-transparent outline-none opacity-0 pointer-events-none transition-opacity duration-300 delay-100" placeholder="<?php esc_attr_e( 'Search', 'jagawarta' ); ?>" autocomplete="off" />
							
							<!-- Close Button -->
							<button type="button" id="search-close-btn" class="hidden absolute right-3 top-1/2 -translate-y-1/2 p-2 text-on-surface-variant hover:text-on-surface rounded-full hover:bg-surface-variant outline-none focus:outline-none focus:bg-surface-variant transition-all opacity-0" onmousedown="event.preventDefault();">
								<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
							</button>

							
							<!-- Live Search Results -->
							<div id="search-results" class="hidden absolute top-full left-0 w-full bg-surface shadow-[0_4px_12px_2px_rgba(0,0,0,0.15)] rounded-xl mt-2 overflow-hidden z-50 border-none outline-none max-h-[80vh] overflow-y-auto"></div>
						</div>
					</form>
				</div>
			</div>
		</div>

	<script>
		(function() {
			const nav = document.getElementById('header-nav');
			const searchContainer = document.getElementById('header-search-container');
			const searchWrapper = document.getElementById('search-wrapper');
			const searchInput = document.getElementById('header-search');
			const closeBtn = document.getElementById('search-close-btn');
			const resultsContainer = document.getElementById('search-results');
			const ajaxUrl = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";
			let debounceTimer;

			const searchPlaceholder = document.getElementById('search-placeholder-text');

			function enableSearch() {
				// 1. Fade Out & Slide Menu (700ms)
				// Slide Left (-translate-x-8) to mimic being "pushed" or "wiped"
				nav.classList.add('opacity-0', '-translate-x-8', 'pointer-events-none');
				
				// 2. Position Container: Absolute Center
				// We move the container from 'justify-end' to absolute centered.
				searchContainer.classList.remove('justify-end', 'ml-4');
				searchContainer.classList.add('absolute', 'left-1/2', '-translate-x-1/2', 'z-40', 'w-auto', 'h-full', 'flex', 'items-center', 'justify-center', 'pointer-events-none');
				
				// 3. Expand Wrapper
				// Updated Spacing: pl-6 (more padding left).
				searchWrapper.classList.remove('w-auto', 'bg-surface-container-low', 'border', 'border-outline-variant', 'px-4', 'cursor-text');
				searchWrapper.classList.add('w-[90vw]', 'md:w-[600px]', 'h-12', 'bg-surface', 'shadow-[0_2px_8px_1px_rgba(0,0,0,0.16)]', 'pl-6', 'pr-0', 'cursor-text', 'pointer-events-auto');

				// 4. Hide Placeholder Text
				searchPlaceholder.classList.add('opacity-0');
				
				// 5. Show Input
				searchInput.classList.remove('opacity-0', 'pointer-events-none');
				searchInput.classList.add('opacity-100', 'pointer-events-auto');
				
				// 6. Show Close Button
				closeBtn.classList.remove('hidden', 'opacity-0');
				closeBtn.classList.add('flex', 'opacity-100');
				
				// Delayed focus to allow transition
				setTimeout(() => searchInput.focus(), 50);
			}

			function disableSearch() {
				// 1. Fade In & Slide Back Menu
				nav.classList.remove('opacity-0', '-translate-x-8', 'pointer-events-none');
				
				// 2. Reset Container
				searchContainer.classList.add('justify-end', 'ml-4');
				searchContainer.classList.remove('absolute', 'left-1/2', '-translate-x-1/2', 'z-40', 'w-auto', 'h-full', 'flex', 'items-center', 'justify-center', 'pointer-events-none');
				
				// 3. Collapse Wrapper
				searchWrapper.classList.add('w-auto', 'bg-surface-container-low', 'border', 'border-outline-variant', 'px-4', 'cursor-text');
				searchWrapper.classList.remove('w-[90vw]', 'md:w-[600px]', 'h-12', 'bg-surface', 'shadow-[0_2px_8px_1px_rgba(0,0,0,0.16)]', 'pl-6', 'pr-0', 'cursor-text', 'pointer-events-auto');
				
				// 4. Show Placeholder Text
				searchPlaceholder.classList.remove('opacity-0');
				
				// 5. Hide Input
				searchInput.classList.add('opacity-0', 'pointer-events-none');
				searchInput.classList.remove('opacity-100', 'pointer-events-auto');
				
				// 6. Hide Close Button
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
