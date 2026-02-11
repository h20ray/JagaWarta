<?php
/**
 * Vertical font-size controls for single posts.
 * Appears after featured image on desktop.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<aside
	class="hidden md:block jw-font-size-controls fixed z-overlay"
	data-font-size-controls
	aria-label="<?php esc_attr_e( 'Article text size controls', 'jagawarta' ); ?>"
>
	<div class="flex flex-col gap-spacing-2 rounded-md bg-surface-low shadow-elevation-1 px-spacing-2 py-spacing-3" role="group" aria-label="<?php esc_attr_e( 'Adjust article text size', 'jagawarta' ); ?>">
		<button
			type="button"
			class="jw-font-size-btn inline-flex items-center justify-center rounded-full w-10 h-10 text-label-medium text-on-surface-variant hover:bg-surface-high hover:text-primary focus-visible:outline-2 focus-visible:outline focus-visible:outline-primary focus-visible:outline-offset-2 transition-all duration-short ease-standard"
			data-font-size-action="decrease"
			aria-label="<?php esc_attr_e( 'Decrease article text size', 'jagawarta' ); ?>"
		>
			A-
		</button>
		<button
			type="button"
			class="jw-font-size-btn inline-flex items-center justify-center rounded-full w-10 h-10 text-label-medium text-on-surface-variant hover:bg-surface-high hover:text-primary focus-visible:outline-2 focus-visible:outline focus-visible:outline-primary focus-visible:outline-offset-2 transition-all duration-short ease-standard aria-pressed:bg-primary-container aria-pressed:text-on-primary-container"
			data-font-size-level="small"
			aria-pressed="false"
			aria-label="<?php esc_attr_e( 'Small article text size', 'jagawarta' ); ?>"
		>
			A
		</button>
		<button
			type="button"
			class="jw-font-size-btn inline-flex items-center justify-center rounded-full w-10 h-10 text-label-medium text-on-surface-variant hover:bg-surface-high hover:text-primary focus-visible:outline-2 focus-visible:outline focus-visible:outline-primary focus-visible:outline-offset-2 transition-all duration-short ease-standard aria-pressed:bg-primary-container aria-pressed:text-on-primary-container"
			data-font-size-level="default"
			aria-pressed="true"
			aria-label="<?php esc_attr_e( 'Default article text size', 'jagawarta' ); ?>"
		>
			A
		</button>
		<button
			type="button"
			class="jw-font-size-btn inline-flex items-center justify-center rounded-full w-10 h-10 text-label-medium text-on-surface-variant hover:bg-surface-high hover:text-primary focus-visible:outline-2 focus-visible:outline focus-visible:outline-primary focus-visible:outline-offset-2 transition-all duration-short ease-standard aria-pressed:bg-primary-container aria-pressed:text-on-primary-container"
			data-font-size-level="large"
			aria-pressed="false"
			aria-label="<?php esc_attr_e( 'Large article text size', 'jagawarta' ); ?>"
		>
			A+
		</button>
		<button
			type="button"
			class="jw-font-size-btn inline-flex items-center justify-center rounded-full w-10 h-10 text-label-medium text-on-surface-variant hover:bg-surface-high hover:text-primary focus-visible:outline-2 focus-visible:outline focus-visible:outline-primary focus-visible:outline-offset-2 transition-all duration-short ease-standard"
			data-font-size-action="increase"
			aria-label="<?php esc_attr_e( 'Increase article text size', 'jagawarta' ); ?>"
		>
			A+
		</button>
	</div>
</aside>
