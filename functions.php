<?php
/**
 * JagaWarta theme — bootstrap only.
 * All logic lives in inc/.
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}

$theme = wp_get_theme();
$theme_version = (string)$theme->get('Version');
define('JAGAWARTA_VERSION', $theme_version !== '' ? $theme_version : '1.0.1');
define('JAGAWARTA_DIR', get_template_directory());
define('JAGAWARTA_URI', get_template_directory_uri());

require_once JAGAWARTA_DIR . '/inc/helpers.php';
require_once JAGAWARTA_DIR . '/inc/helpers-share.php';
require_once JAGAWARTA_DIR . '/inc/category-color.php';
require_once JAGAWARTA_DIR . '/inc/query/posts.php';
require_once JAGAWARTA_DIR . '/inc/assets.php';
require_once JAGAWARTA_DIR . '/inc/customizer.php';
require_once JAGAWARTA_DIR . '/inc/dark-mode.php';
require_once JAGAWARTA_DIR . '/inc/ajax-search.php';
require_once JAGAWARTA_DIR . '/inc/ajax-load-more.php';

add_action('after_setup_theme', 'jagawarta_setup');
function jagawarta_setup()
{
	load_theme_textdomain('jagawarta', JAGAWARTA_DIR . '/languages');
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
	add_theme_support('responsive-embeds');
	add_theme_support('custom-logo', array(
		'height' => 60,
		'width' => 240,
		'flex-height' => true,
		'flex-width' => true,
	));
	register_nav_menus(array(
		'primary' => __('Primary', 'jagawarta'),
		'footer-categories' => __('Footer Categories', 'jagawarta'),
		'footer-links' => __('Footer Links', 'jagawarta'),
	));
}

add_action('after_switch_theme', 'jagawarta_setup_reading_settings');
/**
 * Auto-provision a Blog page and configure Reading Settings on theme activation.
 *
 * Only runs when no posts page is set. Admins can freely change settings after.
 * This ensures "View All" always has a valid destination.
 */
function jagawarta_setup_reading_settings(): void
{
	// Skip if a posts page is already configured.
	if ((int)get_option('page_for_posts')) {
		return;
	}

	// Find or create a Berita page.
	$blog_page = get_page_by_path('berita');
	if (!$blog_page) {
		$blog_id = wp_insert_post(array(
			'post_title' => __('Berita', 'jagawarta'),
			'post_name' => 'berita',
			'post_status' => 'publish',
			'post_type' => 'page',
		));
	}
	else {
		$blog_id = $blog_page->ID;
	}

	if (!$blog_id || is_wp_error($blog_id)) {
		return;
	}

	// Find or create a Home page.
	$front_page_id = (int)get_option('page_on_front');
	if (!$front_page_id) {
		$home_page = get_page_by_path('home');
		if (!$home_page) {
			$front_page_id = wp_insert_post(array(
				'post_title' => __('Home', 'jagawarta'),
				'post_name' => 'home',
				'post_status' => 'publish',
				'post_type' => 'page',
			));
		}
		else {
			$front_page_id = $home_page->ID;
		}
	}

	if (!$front_page_id || is_wp_error($front_page_id)) {
		return;
	}

	// Set Reading Settings: static front page.
	update_option('show_on_front', 'page');
	update_option('page_on_front', $front_page_id);
	update_option('page_for_posts', $blog_id);
}

/**
 * Adjust main query for the posts listing page.
 */
add_action('pre_get_posts', 'jagawarta_adjust_main_query');
function jagawarta_adjust_main_query($query)
{
	if (!is_admin() && $query->is_main_query() && ($query->is_home() || $query->is_archive())) {
		$query->set('posts_per_page', 20);
	}
}

require_once JAGAWARTA_DIR . '/inc/blocks/register.php';
