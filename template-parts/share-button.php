<?php
/**
 * Share Button Component (Google Blog Style).
 * Simple copy-link functionality with icon.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_url = get_permalink();
$post_title = get_the_title();
?>
<button type="button" class="share-btn inline-flex items-center gap-spacing-2 px-spacing-4 py-spacing-2 text-label-medium font-medium text-primary hover:bg-surface-high rounded-sm transition-colors duration-short no-underline border-0 bg-transparent cursor-pointer" onclick="navigator.clipboard.writeText('<?php echo esc_js( $post_url ); ?>').then(() => alert('Link copied!'))">
	<!-- Share icon SVG -->
	<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<circle cx="18" cy="5" r="3"></circle>
		<circle cx="6" cy="12" r="3"></circle>
		<circle cx="18" cy="19" r="3"></circle>
		<line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
		<line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
	</svg>
	<span><?php esc_html_e( 'Share', 'jagawarta' ); ?></span>
</button>
