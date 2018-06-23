<?php
/**
 * WP Menu customisations.
 *
 * @package _s
 */

namespace _s\Theme\Menus;

add_filter( 'nav_menu_css_class', __NAMESPACE__ . '\\normalize_wp_classes', 10, 3 );
add_filter( 'nav_menu_submenu_css_class', __NAMESPACE__ . '\\nav_menu_submenu_css_class', 10, 3 );
add_filter( 'nav_menu_item_id', '__return_empty_string' );

/**
 * Modify WP Menu classes.
 *
 * - Replace 'menu-item-has-children' with '{$args->menu_class}__parent'
 * - Replace 'current-*' classes wth 'active'
 * - Remove other generated classes
 * - Leave any custom classes added in the admin
 *
 * @param array    $classes Nav menu item classes.
 * @param object   $item    Nav menu item data object.
 * @param stdClass $args    An object of wp_nav_menu() arguments.
 * @return array            Modified classes.
 */
function normalize_wp_classes( array $classes, $item = null, $args ) {
	$queried_object  = get_queried_object();
	$reduced_classes = array();

	// Standard wp current classes to be replaced with 'active'.
	$replacements = array(
		'current-menu-item',
		'current-menu-parent',
		'current-menu-ancestor',
		'current_page_item',
		'current_page_parent',
		'current_page_ancestor',
	);

	// Replace with active class, removing everything else.
	if ( array_intersect( $replacements, $classes ) ) {
		$reduced_classes[] = 'active';
	}

	if ( $queried_object ) {
		$post_type = ( isset( $queried_object->post_type ) ) ? $queried_object->post_type : '';

		if ( 'post' !== $post_type && 'page' !== $post_type ) {
			// Add active class to CPT archives.
			if ( 'post_type_archive' === $item->type && $item->object === $post_type ) {
				$reduced_classes[] = 'active';
			}

			// Remove active class on standard posts and archives when viewing a CPT.
			$post_page = get_option( 'page_for_posts' );
			if ( $post_page === $item->object_id ) {
				$reduced_classes = array_diff( $reduced_classes, array( 'active' ) );
			}
		}
	}

	// Return, merging custom classes added via the admin.
	return array_unique( array_merge( $reduced_classes, (array) get_post_meta( $item->ID, '_menu_item_classes', true ) ) );
}

/**
 * Modify submenu classes.
 *
 * - Prefix with the menu class.
 *
 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
 * @param int      $depth   Depth of menu item. Used for padding.
 * @return array            Modified classes.
 */
function nav_menu_submenu_css_class( $classes, $args, $depth ) {
	if ( isset( $args->menu_class ) ) {
		$classes = [
			$args->menu_class . '-subMenu',
		];
	}

	return $classes;
}
