<?php
/**
 * Custom navigation walker for mega menu support.
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}

class JagaWarta_Nav_Walker extends Walker_Nav_Menu
{

	function start_lvl(&$output, $depth = 0, $args = null)
	{
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<div class=\"jw-mega-menu-container absolute left-0 right-0 top-full bg-surface z-40 overflow-hidden\" x-show=\"openMenu === " . esc_attr($this->current_item_id) . "\" x-transition:enter=\"jw-mega-menu-enter\" x-transition:enter-start=\"opacity-0 -translate-y-2\" x-transition:enter-end=\"opacity-100 translate-y-0\" x-transition:leave=\"jw-mega-menu-leave\" x-transition:leave-start=\"opacity-100 translate-y-0\" x-transition:leave-end=\"opacity-0 -translate-y-2\" @click.away=\"if (openMenu === " . esc_attr($this->current_item_id) . ") openMenu = null\" x-cloak>\n";
		$output .= "$indent\t<div class=\"max-w-page-max mx-auto px-6 pb-12 pt-20 relative\">\n";
		$output .= "$indent\t\t<button type=\"button\" @click.stop=\"openMenu = null\" class=\"jw-mega-menu-close absolute top-6 right-6 p-2 text-on-surface-variant hover:text-on-surface hover:bg-surface-high rounded-full transition-colors flex items-center justify-center shadow-elevation-1\" aria-label=\"Close\" title=\"" . esc_attr__('Close Menu', 'jagawarta') . "\">\n";
		$output .= "$indent\t\t\t<svg xmlns=\"http://www.w3.org/2000/svg\" height=\"24\" viewBox=\"0 -960 960 960\" width=\"24\" fill=\"currentColor\"><path d=\"m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z\"/></svg>\n";
		$output .= "$indent\t\t</button>\n";
		$output .= "$indent\t\t<ul class=\"sub-menu jw-mega-menu grid grid-cols-1 md:grid-cols-4 gap-8 list-none m-0 p-0\" role=\"menu\">\n";
	}

	function end_lvl(&$output, $depth = 0, $args = null)
	{
		$indent = str_repeat("\t", $depth);
		$output .= "$indent\t\t</ul>\n";
		$output .= "$indent\t</div>\n";
		$output .= "$indent</div>\n";
	}

	private $current_item_id = 0;

	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$classes = empty($item->classes) ? array() : (array)$item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array('menu-item-has-children', $classes);
		$is_current = in_array('current-menu-item', $classes);
		$this->current_item_id = $item->ID;

		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
		$id = $id ? ' id="' . esc_attr($id) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . ' :class="{ \'is-open\': openMenu === ' . $item->ID . ' }">';

		$attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
		$attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
		$attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

		$active_class = $is_current ? ' font-medium text-primary bg-surface-high' : '';
		$link_classes = 'jw-nav-link px-3 py-2 text-label-large font-normal text-on-surface hover:text-primary hover:bg-surface-high rounded-full transition-all duration-short ease-standard inline-block' . $active_class;

		$append_attrs = '';
		if ($has_children && $depth === 0) {
			$append_attrs = ' @click.prevent="openMenu = (openMenu === ' . $item->ID . ' ? null : ' . $item->ID . ')" :aria-expanded="openMenu === ' . $item->ID . '" aria-haspopup="true"';
		}

		$item_output = isset($args->before) ? $args->before : '';
		$item_output .= '<a class="' . esc_attr($link_classes) . '"' . $attributes . $append_attrs . '>';
		$item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');

		if ($has_children && $depth === 0) {
			$item_output .= '<svg class="jw-nav-chevron ml-1 h-4 w-4 inline-block transition-transform duration-short ease-standard" :class="{ \'rotate-180\': openMenu === ' . $item->ID . ' }" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor"><path d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"/></svg>';
		}

		$item_output .= '</a>';
		$item_output .= isset($args->after) ? $args->after : '';

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	function end_el(&$output, $item, $depth = 0, $args = null)
	{
		$output .= "</li>\n";
	}
}

class JagaWarta_Mobile_Nav_Walker extends Walker_Nav_Menu
{
	private $current_parent_id = 0;

	function start_lvl(&$output, $depth = 0, $args = null)
	{
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu jw-mobile-submenu\" role=\"menu\" id=\"submenu-" . esc_attr($this->current_parent_id) . "\" hidden>\n";
	}

	function end_lvl(&$output, $depth = 0, $args = null)
	{
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$classes = empty($item->classes) ? array() : (array)$item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array('menu-item-has-children', $classes);
		$is_current = in_array('current-menu-item', $classes);

		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
		$id = $id ? ' id="' . esc_attr($id) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
		$attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
		$attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

		$active_class = $is_current ? ' font-medium text-primary bg-surface-high' : '';
		$link_classes = 'jw-nav-link font-normal rounded-full' . $active_class;

		$item_output = isset($args->before) ? $args->before : '';

		if ($has_children) {
			$this->current_parent_id = $item->ID;
			$item_output .= '<button type="button" class="jw-mobile-submenu-toggle ' . esc_attr($link_classes) . '" aria-expanded="false" aria-controls="submenu-' . esc_attr($item->ID) . '">';
			$item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
			$item_output .= '<svg class="jw-mobile-chevron ml-auto h-5 w-5 transition-transform duration-short ease-standard" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor"><path d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"/></svg>';
			$item_output .= '</button>';
		}
		else {
			$item_output .= '<a class="' . esc_attr($link_classes) . '"' . $attributes . '>';
			$item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
			$item_output .= '</a>';
		}

		$item_output .= isset($args->after) ? $args->after : '';

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	function end_el(&$output, $item, $depth = 0, $args = null)
	{
		$output .= "</li>\n";
	}
}
