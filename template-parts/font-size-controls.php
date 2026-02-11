<?php
/**
 * Reading controls side rail for single posts.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<aside
	class="hidden md:block jw-font-size-controls"
	data-font-size-controls
	aria-label="<?php esc_attr_e( 'Article reading controls', 'jagawarta' ); ?>"
>
	<div class="jw-font-size-rail" role="toolbar" aria-label="<?php esc_attr_e( 'Reading controls', 'jagawarta' ); ?>">
		<button
			type="button"
			class="jw-font-size-btn jw-font-size-btn-scroll"
			data-scroll-action="up"
			aria-label="<?php esc_attr_e( 'Scroll to start of content', 'jagawarta' ); ?>"
		>
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
				<path d="M6 14L12 8L18 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
			</svg>
		</button>

		<div class="jw-font-size-divider" aria-hidden="true"></div>

		<div class="jw-font-size-group" role="group" aria-label="<?php esc_attr_e( 'Text size', 'jagawarta' ); ?>">
			<button
				type="button"
				class="jw-font-size-btn jw-font-size-btn-size"
				data-font-size-level="small"
				aria-pressed="false"
				aria-label="<?php esc_attr_e( 'Small article text size', 'jagawarta' ); ?>"
			>
				A-
			</button>
			<button
				type="button"
				class="jw-font-size-btn jw-font-size-btn-size"
				data-font-size-level="default"
				aria-pressed="true"
				aria-label="<?php esc_attr_e( 'Default article text size', 'jagawarta' ); ?>"
			>
				A
			</button>
			<button
				type="button"
				class="jw-font-size-btn jw-font-size-btn-size"
				data-font-size-level="large"
				aria-pressed="false"
				aria-label="<?php esc_attr_e( 'Large article text size', 'jagawarta' ); ?>"
			>
				A+
			</button>
		</div>

		<div class="jw-font-size-divider" aria-hidden="true"></div>

		<button
			type="button"
			class="jw-font-size-btn jw-font-size-btn-scroll"
			data-scroll-action="down"
			aria-label="<?php esc_attr_e( 'Scroll to end of content', 'jagawarta' ); ?>"
		>
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
				<path d="M6 10L12 16L18 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
			</svg>
		</button>
	</div>
</aside>
