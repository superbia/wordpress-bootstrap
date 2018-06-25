<?php
/**
 * _s functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package _s
 */

namespace _s\Theme;

require_once __DIR__ . '/inc/extras.php';
require_once __DIR__ . '/inc/media.php';
require_once __DIR__ . '/inc/menus.php';
require_once __DIR__ . '/inc/template-tags.php';
require_once __DIR__ . '/inc/editor.php';

add_action( 'after_setup_theme',  __NAMESPACE__ . '\\setup' );
add_action( 'after_setup_theme',  __NAMESPACE__ . '\\content_width', 0 );
add_action( 'after_switch_theme', __NAMESPACE__ . '\\theme_activation' );
add_action( 'admin_init',         __NAMESPACE__ . '\\setup_admin' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );

/**
 * Setup the theme
 */
function setup() {
	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Enable support for flexible post formats.
	add_theme_support( 'post-formats', [ 'aside', 'image', 'video', 'quote', 'link' ] );

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );

	// Disable WordPress features.
	add_theme_support( 'terminator-disable-emoji' );
	add_theme_support( 'terminator-disable-wp-oembed' );
	add_theme_support( 'terminator-disable-rest-user-endpoint' );
	add_theme_support( 'terminator-clean-up-head' );

	// Use the CDN version of jQuery.
	add_theme_support( 'blackbird-enable-cdn-jquery' );

	// Register navigation menus.
	register_nav_menu( 'nav-primary', 'Main navigation' );
	register_nav_menu( 'nav-secondary', 'Secondary navigation' );
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function content_width() {
	$GLOBALS['content_width'] = 0;
}

/**
 * Set default options for the theme on activation.
 */
function theme_activation() {
	// Set image defaults.
	update_option( 'image_default_align', 'left' );
	update_option( 'image_default_link_type', 'none' );
	update_option( 'image_default_size', 'medium' );
}

/**
 * Set up the admin.
 */
function setup_admin() {
	add_editor_style( 'assets/dist/styles/editor.css' );
}

/**
 * Enqueue theme scripts and styles.
 */
function enqueue_scripts() {
	wp_enqueue_style( '_s-styles', get_stylesheet_directory_uri() . '/assets/dist/styles/theme.css', [], '0.1.0' );
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/dist/scripts/modernizr.js', [], '0.1.0', true );
	wp_enqueue_script( '_s-script', get_template_directory_uri() . '/assets/src/scripts/functions.js', [ 'jquery' ], '0.1.0', true );
}
