<?php
/**
 * Custom navigation walker for mega menu support.
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}

class JagaWarta_Nav_Walker extends Walker_Nav_Menu {

	function start_lvl(&$output, $depth = 0, $args = null) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu jw-mega-menu\" role=\"menu\">\n";
	}

	function end_lvl(&$output, $depth = 0, $args = null) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array('menu-item-has-children', $classes);
		$is_current = in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes);

		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
		$id = $id ? ' id="' . esc_attr($id) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
		$attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
		$attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

		$active_class = $is_current ? ' font-medium text-primary bg-surface-high border-b-2 border-primary' : '';
		$link_classes = 'jw-nav-link px-4 py-2 text-title-small font-normal text-on-surface hover:text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard inline-block' . $active_class;

		$item_output = isset($args->before) ? $args->before : '';
		$item_output .= '<a class="' . esc_attr($link_classes) . '"' . $attributes . '>';
		$item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');

		if ($has_children && $depth === 0) {
			$item_output .= '<svg class="jw-nav-chevron ml-1 h-4 w-4 inline-block transition-transform duration-short ease-standard" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor"><path d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"/></svg>';
		}

		$item_output .= '</a>';
		$item_output .= isset($args->after) ? $args->after : '';

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	function end_el(&$output, $item, $depth = 0, $args = null) {
		$output .= "</li>\n";
	}
}

class JagaWarta_Mobile_Nav_Walker extends Walker_Nav_Menu {
	private $current_parent_id = 0;

	function start_lvl(&$output, $depth = 0, $args = null) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu jw-mobile-submenu\" role=\"menu\" id=\"submenu-" . esc_attr($this->current_parent_id) . "\" hidden>\n";
	}

	function end_lvl(&$output, $depth = 0, $args = null) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array('menu-item-has-children', $classes);
		$is_current = in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes);

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
		$link_classes = 'jw-nav-link font-normal' . $active_class;

		$item_output = isset($args->before) ? $args->before : '';
		
		if ($has_children) {
			$this->current_parent_id = $item->ID;
			$item_output .= '<button type="button" class="jw-mobile-submenu-toggle ' . esc_attr($link_classes) . '" aria-expanded="false" aria-controls="submenu-' . esc_attr($item->ID) . '">';
			$item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
			$item_output .= '<svg class="jw-mobile-chevron ml-auto h-5 w-5 transition-transform duration-short ease-standard" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor"><path d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"/></svg>';
			$item_output .= '</button>';
		} else {
			$item_output .= '<a class="' . esc_attr($link_classes) . '"' . $attributes . '>';
			$item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
			$item_output .= '</a>';
		}

		$item_output .= isset($args->after) ? $args->after : '';

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	function end_el(&$output, $item, $depth = 0, $args = null) {
		$output .= "</li>\n";
	}
}
