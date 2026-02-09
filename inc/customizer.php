<?php
/**
 * Theme Customizer — JagaWarta Options.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/palette-generator.php';

require_once get_template_directory() . '/inc/class-jagawarta-preset-color-control.php';

add_action( 'customize_register', 'jagawarta_customize_register' );
add_action( 'customize_save_after', 'jagawarta_generate_palette_on_save' );
add_action( 'wp_head', 'jagawarta_output_palette_css', 100 ); // Priority 100 to override main CSS

function jagawarta_customize_register( WP_Customize_Manager $wp_customize ): void {
	// Register custom control type
	$wp_customize->register_control_type( 'JagaWarta_Preset_Color_Control' );

	$wp_customize->add_panel( 'jagawarta_options', array(
		'title'    => __( 'JagaWarta Options', 'jagawarta' ),
		'priority' => 30,
	) );

	$wp_customize->add_section( 'jagawarta_front_page', array(
		'title'    => __( 'Front page', 'jagawarta' ),
		'panel'    => 'jagawarta_options',
		'priority' => 10,
	) );

	$wp_customize->add_setting( 'jagawarta_hero_count', array(
		'default'           => 5,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'jagawarta_hero_count', array(
		'label'   => __( 'Hero posts count', 'jagawarta' ),
		'section' => 'jagawarta_front_page',
		'type'    => 'number',
		'input_attrs' => array( 'min' => 1, 'max' => 10, 'step' => 1 ),
	) );

	$wp_customize->add_setting( 'jagawarta_front_hero_slider', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'jagawarta_front_hero_slider', array(
		'label'   => __( 'Hero as slider', 'jagawarta' ),
		'section' => 'jagawarta_front_page',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'jagawarta_ticker_on_front', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'jagawarta_ticker_on_front', array(
		'label'   => __( 'Show breaking ticker on front page', 'jagawarta' ),
		'section' => 'jagawarta_front_page',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'jagawarta_ticker_count', array(
		'default'           => 5,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'jagawarta_ticker_count', array(
		'label'   => __( 'Ticker posts count', 'jagawarta' ),
		'section' => 'jagawarta_front_page',
		'type'    => 'number',
		'input_attrs' => array( 'min' => 1, 'max' => 15, 'step' => 1 ),
	) );

	$wp_customize->add_setting( 'jagawarta_section_count', array(
		'default'           => 3,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'jagawarta_section_count', array(
		'label'   => __( 'Section grids (categories)', 'jagawarta' ),
		'section' => 'jagawarta_front_page',
		'type'    => 'number',
		'input_attrs' => array( 'min' => 1, 'max' => 6, 'step' => 1 ),
	) );

	$wp_customize->add_setting( 'jagawarta_latest_count', array(
		'default'           => 10,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'jagawarta_latest_count', array(
		'label'   => __( 'Latest posts count', 'jagawarta' ),
		'section' => 'jagawarta_front_page',
		'type'    => 'number',
		'input_attrs' => array( 'min' => 1, 'max' => 30, 'step' => 1 ),
	) );

	// Colors Section (Seed-based MD3 Palette)
	$wp_customize->add_section( 'jagawarta_colors', array(
		'title'       => __( 'Colors (MD3 Palette)', 'jagawarta' ),
		'description' => __( 'Select a primary brand color. All other system colors will be automatically generated from this seed.<br><strong>After publishing:</strong> Press Ctrl+Shift+R (Windows) or Cmd+Shift+R (Mac) to see changes.', 'jagawarta' ),
		'panel'       => 'jagawarta_options',
		'priority'    => 20,
	) );

	// Primary seed (required)
	$wp_customize->add_setting( 'jagawarta_seed_primary', array(
		'default'           => '#1e3a5f',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new JagaWarta_Preset_Color_Control( $wp_customize, 'jagawarta_seed_primary', array(
		'label'       => __( 'Primary Brand Color', 'jagawarta' ),
		'description' => __( 'Choose from curated presets or set a custom color.', 'jagawarta' ),
		'section'     => 'jagawarta_colors',
		'presets'     => array(
			'#1e3a5f', // JagaWarta Blue
			'#1b6ef3', // Google Blue
			'#4285f4', // Pixel Blue
			'#3f51b5', // Material Indigo
			'#673ab7', // Deep Purple
			'#006d40', // Android Green
			'#1b5e20', // Forest Green
			'#009688', // Teal
			'#b3261e', // YouTube Red
			'#b71c1c', // Brick Red
			'#e91e63', // Hot Pink
			'#ff9800', // Orange
			'#ff5722', // Deep Orange
			'#795548', // Brown
			'#607d8b', // Blue Grey
		),
	) ) );

	// Optional seeds (Neutral, Secondary, Tertiary, Error) have been removed from UI
	// to simplify the experience, but we keep their settings in the DB generation logic below
	// in case we need to restore them later or for advanced users.
}

/**
 * Generate and store palette when Customizer is saved.
 */
function jagawarta_generate_palette_on_save(): void {
	// Only Primary is exposed in UI now. Others are left empty to trigger auto-generation.
	$seeds = array(
		'primary'   => get_theme_mod( 'jagawarta_seed_primary', '#1e3a5f' ),
		'neutral'   => '', // Auto
		'secondary' => '', // Auto
		'tertiary'  => '', // Auto
		'error'     => '', // Auto
	);

	$palette = jagawarta_generate_full_palette( $seeds );

	set_theme_mod( 'jagawarta_generated_palette', array(
		'seeds'   => $seeds,
		'palette' => $palette,
	) );
}

/**
 * Output generated palette CSS in wp_head.
 */
function jagawarta_output_palette_css(): void {
	$data = get_theme_mod( 'jagawarta_generated_palette', null );
	
	if ( empty( $data ) || empty( $data['palette'] ) ) {
		return;
	}

	$palette = $data['palette'];
	$light = $palette['light'] ?? array();
	$dark = $palette['dark'] ?? array();

	echo '<style id="jagawarta-palette-override">' . "\n";

	// Light mode palette overrides
	if ( ! empty( $light ) ) {
		echo ':root {' . "\n";
		
		// Output reference palette variables
		foreach ( $light as $scheme => $tones ) {
			foreach ( $tones as $tone => $hex ) {
				echo '  --md-ref-palette-' . esc_attr( $scheme ) . '-' . esc_attr( $tone ) . ': ' . esc_attr( $hex ) . ';' . "\n";
			}
		}
		
		// CRITICAL: Output system color mappings to reference palette
		// Without these, UI components won't update when reference colors change
		echo "\n  /* System Color Mappings (Light Mode) */" . "\n";
		echo '  --md-sys-color-primary: var(--md-ref-palette-primary-40);' . "\n";
		echo '  --md-sys-color-on-primary: var(--md-ref-palette-primary-100);' . "\n";
		echo '  --md-sys-color-primary-container: var(--md-ref-palette-primary-90);' . "\n";
		echo '  --md-sys-color-on-primary-container: var(--md-ref-palette-primary-10);' . "\n";
		echo '  --md-sys-color-secondary: var(--md-ref-palette-secondary-40);' . "\n";
		echo '  --md-sys-color-on-secondary: var(--md-ref-palette-secondary-100);' . "\n";
		echo '  --md-sys-color-secondary-container: var(--md-ref-palette-secondary-90);' . "\n";
		echo '  --md-sys-color-on-secondary-container: var(--md-ref-palette-secondary-10);' . "\n";
		echo '  --md-sys-color-tertiary: var(--md-ref-palette-tertiary-40);' . "\n";
		echo '  --md-sys-color-on-tertiary: var(--md-ref-palette-tertiary-100);' . "\n";
		echo '  --md-sys-color-tertiary-container: var(--md-ref-palette-tertiary-90);' . "\n";
		echo '  --md-sys-color-on-tertiary-container: var(--md-ref-palette-tertiary-10);' . "\n";
		echo '  --md-sys-color-error: var(--md-ref-palette-error-40);' . "\n";
		echo '  --md-sys-color-on-error: var(--md-ref-palette-error-100);' . "\n";
		echo '  --md-sys-color-error-container: var(--md-ref-palette-error-90);' . "\n";
		echo '  --md-sys-color-on-error-container: var(--md-ref-palette-error-10);' . "\n";
		echo '  --md-sys-color-surface: var(--md-ref-palette-neutral-99);' . "\n";
		echo '  --md-sys-color-on-surface: var(--md-ref-palette-neutral-10);' . "\n";
		echo '  --md-sys-color-on-surface-variant: var(--md-ref-palette-neutral-30);' . "\n";
		echo '  --md-sys-color-surface-variant: var(--md-ref-palette-neutral-90);' . "\n";
		echo '  --md-sys-color-outline: var(--md-ref-palette-neutral-50);' . "\n";
		echo '  --md-sys-color-outline-variant: var(--md-ref-palette-neutral-80);' . "\n";
		
		echo '}' . "\n";
	}

	// Dark mode palette overrides
	if ( ! empty( $dark ) ) {
		echo '[data-theme="dark"] {' . "\n";
		
		// Output reference palette variables (same for dark)
		foreach ( $dark as $scheme => $tones ) {
			foreach ( $tones as $tone => $hex ) {
				echo '  --md-ref-palette-' . esc_attr( $scheme ) . '-' . esc_attr( $tone ) . ': ' . esc_attr( $hex ) . ';' . "\n";
			}
		}
		
		// System color mappings for dark mode (inverted tones)
		echo "\n  /* System Color Mappings (Dark Mode) */" . "\n";
		echo '  --md-sys-color-primary: var(--md-ref-palette-primary-80);' . "\n";
		echo '  --md-sys-color-on-primary: var(--md-ref-palette-primary-20);' . "\n";
		echo '  --md-sys-color-primary-container: var(--md-ref-palette-primary-30);' . "\n";
		echo '  --md-sys-color-on-primary-container: var(--md-ref-palette-primary-90);' . "\n";
		echo '  --md-sys-color-secondary: var(--md-ref-palette-secondary-80);' . "\n";
		echo '  --md-sys-color-on-secondary: var(--md-ref-palette-secondary-20);' . "\n";
		echo '  --md-sys-color-secondary-container: var(--md-ref-palette-secondary-30);' . "\n";
		echo '  --md-sys-color-on-secondary-container: var(--md-ref-palette-secondary-90);' . "\n";
		echo '  --md-sys-color-tertiary: var(--md-ref-palette-tertiary-80);' . "\n";
		echo '  --md-sys-color-on-tertiary: var(--md-ref-palette-tertiary-20);' . "\n";
		echo '  --md-sys-color-tertiary-container: var(--md-ref-palette-tertiary-30);' . "\n";
		echo '  --md-sys-color-on-tertiary-container: var(--md-ref-palette-tertiary-90);' . "\n";
		echo '  --md-sys-color-error: var(--md-ref-palette-error-80);' . "\n";
		echo '  --md-sys-color-on-error: var(--md-ref-palette-error-20);' . "\n";
		echo '  --md-sys-color-error-container: var(--md-ref-palette-error-30);' . "\n";
		echo '  --md-sys-color-on-error-container: var(--md-ref-palette-error-90);' . "\n";
		echo '  --md-sys-color-surface: var(--md-ref-palette-neutral-10);' . "\n";
		echo '  --md-sys-color-on-surface: var(--md-ref-palette-neutral-90);' . "\n";
		echo '  --md-sys-color-on-surface-variant: var(--md-ref-palette-neutral-80);' . "\n";
		echo '  --md-sys-color-surface-variant: var(--md-ref-palette-neutral-30);' . "\n";
		echo '  --md-sys-color-outline: var(--md-ref-palette-neutral-60);' . "\n";
		echo '  --md-sys-color-outline-variant: var(--md-ref-palette-neutral-30);' . "\n";
		
		echo '}' . "\n";
	}

	echo '</style>' . "\n";
}

