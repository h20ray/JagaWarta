<?php
/**
 * JagaWarta theme — bootstrap only.
 * All logic lives in inc/.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'JAGAWARTA_VERSION', '1.0.0' );
define( 'JAGAWARTA_DIR', get_template_directory() );
define( 'JAGAWARTA_URI', get_template_directory_uri() );

require_once JAGAWARTA_DIR . '/inc/helpers.php';
require_once JAGAWARTA_DIR . '/inc/query/posts.php';
require_once JAGAWARTA_DIR . '/inc/query/news.php';
require_once JAGAWARTA_DIR . '/inc/assets.php';
require_once JAGAWARTA_DIR . '/inc/customizer.php';

add_action( 'after_setup_theme', 'jagawarta_setup' );
function jagawarta_setup() {
	load_theme_textdomain( 'jagawarta', JAGAWARTA_DIR . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	register_nav_menus( array( 'primary' => __( 'Primary', 'jagawarta' ) ) );
}

require_once JAGAWARTA_DIR . '/inc/blocks/register.php';
