<?php
/**
 * Theme Customizer — JagaWarta Options.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'customize_register', 'jagawarta_customize_register' );
add_action( 'wp_head', 'jagawarta_customizer_accent_css', 5 );

function jagawarta_customize_register( WP_Customize_Manager $wp_customize ): void {
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

	$wp_customize->add_section( 'jagawarta_colors', array(
		'title'    => __( 'Colors', 'jagawarta' ),
		'panel'    => 'jagawarta_options',
		'priority' => 20,
	) );

	$wp_customize->add_setting( 'jagawarta_primary_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'jagawarta_primary_color', array(
		'label'       => __( 'Primary color override', 'jagawarta' ),
		'description' => __( 'Optional. Leave empty to use theme default.', 'jagawarta' ),
		'section'     => 'jagawarta_colors',
	) ) );
}

function jagawarta_customizer_accent_css(): void {
	$primary = get_theme_mod( 'jagawarta_primary_color', '' );
	if ( empty( $primary ) ) {
		return;
	}
	echo '<style id="jagawarta-tokens-override">';
	echo ':root { --md-sys-color-primary: ' . esc_attr( $primary ) . '; }';
	echo '</style>' . "\n";
}
