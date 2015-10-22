<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package _s
 */

/**
 * Remove the gallery shortcode from the content
 */
function _s_remove_gallery_shortcode_from_the_content( $content ) {
	add_shortcode( 'gallery', '__return_false' );
	return $content;
}
add_filter( 'the_content', '_s_remove_gallery_shortcode_from_the_content', 0 );


/**
 * Move jQuery to the website footer.
 */
function _s_print_jquery_in_footer( &$scripts ) {
	// Return if the website is being requested via the admin or theme customizer
	global $wp_customize;
	if ( is_admin() || isset( $wp_customize ) ) {
	  return;
	}

	$scripts->add_data( 'jquery', 'group', 1 );
	$scripts->add_data( 'jquery-core', 'group', 1 );
	$scripts->add_data( 'jquery-migrate', 'group', 1 );
}
add_action( 'wp_default_scripts', '_s_print_jquery_in_footer' );
