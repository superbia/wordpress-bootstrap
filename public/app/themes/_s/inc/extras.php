<?php
/**
 * Custom functions that act independently of the theme templates
 *
 *
 * @package _s
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init on frontend.
 */
add_action( 'template_redirect', array( '_S_Extras', 'init' ) );

class _S_Extras {
	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_filter( 'the_content', array( __CLASS__, 'remove_gallery_shortcode_from_the_content' ), 0 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'print_jquery_in_footer' ) );
	}

	/**
	 * Remove the gallery shortcode from the content
	 */
	public static function remove_gallery_shortcode_from_the_content( $content ) {
		add_shortcode( 'gallery', '__return_false' );
		return $content;
	}

	/**
	 * Move jQuery to the website footer.
	 */
	public static function print_jquery_in_footer() {
		wp_scripts()->add_data( 'jquery', 'group', 1 );
		wp_scripts()->add_data( 'jquery-core', 'group', 1 );
		wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
	}
}
