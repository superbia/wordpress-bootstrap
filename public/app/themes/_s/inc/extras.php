<?php
/**
 * Media customisations.
 *
 * @package _s
 */

namespace _s\Theme;

add_filter( 'the_content',        __NAMESPACE__ . '\\remove_gallery_shortcode_from_the_content', 0 );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\print_jquery_in_footer' );

/**
 * Remove the gallery shortcode from the content.
 *
 * @param string $content Content of the current post.
 */
function remove_gallery_shortcode_from_the_content( $content ) {
	add_shortcode( 'gallery', '__return_false' );
	return $content;
}

/**
 * Move jQuery to the website footer.
 */
function print_jquery_in_footer() {
	wp_scripts()->add_data( 'jquery', 'group', 1 );
	wp_scripts()->add_data( 'jquery-core', 'group', 1 );
	wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
}
