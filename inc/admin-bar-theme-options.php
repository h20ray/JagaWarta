<?php
/**
 * Admin bar shortcuts for theme options.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_bar_menu', 'jagawarta_register_admin_bar_theme_options', 90 );

/**
 * Register quick links under the WP admin bar.
 */
function jagawarta_register_admin_bar_theme_options( WP_Admin_Bar $admin_bar ): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$admin_bar->add_node(
		array(
			'id'    => 'jagawarta-theme-options',
			'title' => esc_html__( 'Theme Options', 'jagawarta' ),
			'href'  => admin_url( 'customize.php?autofocus[panel]=jagawarta_options' ),
			'meta'  => array(
				'title' => esc_attr__( 'Open JagaWarta options', 'jagawarta' ),
			),
		)
	);

	$items = array(
		'front-page-live' => array(
			'title' => __( 'Front Page Live Preview', 'jagawarta' ),
			'href'  => admin_url( 'customize.php?autofocus[section]=jagawarta_front_page&url=' . rawurlencode( home_url( '/' ) ) ),
		),
		'front-page' => array(
			'title' => __( 'Front Page Settings', 'jagawarta' ),
			'href'  => admin_url( 'customize.php?autofocus[section]=jagawarta_front_page' ),
		),
		'colors' => array(
			'title' => __( 'Brand Colors', 'jagawarta' ),
			'href'  => admin_url( 'customize.php?autofocus[section]=jagawarta_colors' ),
		),
		'site-identity' => array(
			'title' => __( 'Logo & Site Identity', 'jagawarta' ),
			'href'  => admin_url( 'customize.php?autofocus[section]=title_tagline' ),
		),
		'menus' => array(
			'title' => __( 'Navigation Menus', 'jagawarta' ),
			'href'  => admin_url( 'nav-menus.php' ),
		),
		'category-colors' => array(
			'title' => __( 'Category Chip Colors', 'jagawarta' ),
			'href'  => admin_url( 'edit-tags.php?taxonomy=category' ),
		),
		'homepage' => array(
			'title' => __( 'Homepage & Posts Page', 'jagawarta' ),
			'href'  => admin_url( 'options-reading.php' ),
		),
	);

	foreach ( $items as $id => $item ) {
		$admin_bar->add_node(
			array(
				'id'     => 'jagawarta-theme-options-' . $id,
				'parent' => 'jagawarta-theme-options',
				'title'  => esc_html( $item['title'] ),
				'href'   => $item['href'],
			)
		);
	}
}
