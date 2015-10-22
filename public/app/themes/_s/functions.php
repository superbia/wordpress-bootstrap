<?php
/**
 * _s functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package _s
 */

if ( ! function_exists( '_s_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function _s_setup() {
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style( 'editor-style.css' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', '_s' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );
}
endif; // _s_setup
add_action( 'after_setup_theme', '_s_setup' );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function _s_content_width() {
	$GLOBALS['content_width'] = apply_filters( '_s_content_width', 1170 );
}
add_action( 'after_setup_theme', '_s_content_width', 0 );


/**
 * Enqueue scripts and styles.
 */
function _s_scripts_style() {
	// Load our main stylesheet.
	wp_enqueue_style( '_s-style', get_stylesheet_uri() );

	// Load Modernizr and respond.js in the head
	wp_enqueue_script( 'respond', get_template_directory_uri() . '/js/respond.min.js', array(), '1.1.0', false );
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr-custom.js', array(), '3.1.0', false );

	// Loads main theme JavaScript in the footer
	wp_enqueue_script( '_s-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery-core' ), '2015-08-30', true );
}
add_action( 'wp_enqueue_scripts', '_s_scripts_style' );


/**
 * Set default options for the theme on activation.
 */
function _s_theme_activation() {
	// Set image defaults
	update_option( 'image_default_align', 'left' );
	update_option( 'image_default_link_type', 'none' );
	update_option( 'image_default_size', 'medium' );
}
add_action( 'after_switch_theme', '_s_theme_activation' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Media customisations.
 */
require get_template_directory() . '/inc/media.php';

/**
 * TinyMCE customisations.
 */
require get_template_directory() . '/inc/tinymce.php';