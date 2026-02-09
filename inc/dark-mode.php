<?php
/**
 * Dark mode utilities: data-theme attribute injection, script enqueuing.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add data-theme attribute to <html> tag.
 * JavaScript will override this based on user preference.
 *
 * @param string $output Language attributes string.
 * @return string Modified attributes with data-theme.
 */
function jagawarta_add_data_theme_attribute( string $output ): string {
	return $output . ' data-theme="light"';
}
add_filter( 'language_attributes', 'jagawarta_add_data_theme_attribute' );

/**
 * Output blocking inline script to prevent FOUC.
 * This MUST run before any CSS to avoid flash when navigating pages.
 */
function jagawarta_output_dark_mode_init_script(): void {
	?>
	<script>
	(function(){
		try {
			var stored = localStorage.getItem('jw-dark-mode');
			if (stored === 'dark' || (!stored && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
				document.documentElement.setAttribute('data-theme', 'dark');
			}
		} catch(e) {}
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'jagawarta_output_dark_mode_init_script', 1 ); // Priority 1: run before all other scripts

/**
 * Enqueue dark mode controller script.
 * Handles toggle button interactions and state management.
 */
function jagawarta_enqueue_dark_mode_script(): void {
	wp_enqueue_script(
		'jagawarta-dark-mode',
		get_template_directory_uri() . '/assets/dist/dark-mode.js',
		array(),
		filemtime( get_template_directory() . '/assets/dist/dark-mode.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => false,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'jagawarta_enqueue_dark_mode_script' );
