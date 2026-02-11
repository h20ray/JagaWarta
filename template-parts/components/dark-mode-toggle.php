<?php
/**
 * Dark mode toggle button component.
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}
?>
<button
	id="dark-mode-toggle"
	class="dark-mode-toggle h-12 w-12 flex items-center justify-center rounded-full text-on-surface hover:bg-surface-high focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 transition-colors"
	aria-label="<?php esc_attr_e('Switch to dark mode', 'jagawarta'); ?>"
	type="button"
>
	<svg class="icon-sun" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
		<path d="M12 17C14.7614 17 17 14.7614 17 12C17 9.23858 14.7614 7 12 7C9.23858 7 7 9.23858 7 12C7 14.7614 9.23858 17 12 17Z" fill="currentColor"/>
		<path d="M12 1V3M12 21V23M4.22 4.22L5.64 5.64M18.36 18.36L19.78 19.78M1 12H3M21 12H23M4.22 19.78L5.64 18.36M18.36 5.64L19.78 4.22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
	</svg>
	<svg class="icon-moon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="display: none;">
		<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" fill="currentColor"/>
	</svg>
	<span class="screen-reader-text"><?php esc_html_e('Toggle dark mode', 'jagawarta'); ?></span>
</button>
