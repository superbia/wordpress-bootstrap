<?php
/**
 * Media customisations.
 *
 * @package _s
 */

/**
 * Oembed: wrap in div for responsive styles
 */
function _s_wrap_oembeds( $html, $url, $attr, $post_id ) {
	return '<div class="inline-video">' . $html . '</div>';
}
add_filter( 'embed_oembed_html', '_s_wrap_oembeds', 10, 4 );


/**
 * Oembed: customise request parameters for Vimeo
 */
function _s_customise_vimeo_oembed_params( $provider, $url, $args ) {
	// Check that it is a vimeo URL
	if ( FALSE !== strpos( $url, 'vimeo.com' ) ) {
		$args['color'] = 'a0a0a0';
		$args['byline'] = 0;
		$args['portrait'] = 0;
		$args['title'] = 0;
		$args['badge'] = 0;
		// WP is adding a whacky height arg
		unset( $args['height'] );
	}
	
	// build the query url
	$parameters = urlencode( http_build_query( $args ) );
	$provider .= '&' . $parameters;
	
	return $provider;
}
add_filter( 'oembed_fetch_url', '_s_customise_vimeo_oembed_params', 10, 3);


/**
 * Oembed: modify Youtube oembed results
 */
function _s_customise_youtube_oembed_response( $html, $url, $args ) {
	return str_replace( '?feature=oembed', '?feature=oembed&showinfo=0&rel=0&autohide=1', $html );
}
add_filter( 'oembed_result', '_s_customise_youtube_oembed_response', 10, 3 );


/**
 * Oembed: Setup oembed filter for video urls in custom fields 
 */
add_filter( '_s_custom_video_oembed', array( $GLOBALS['wp_embed'], 'autoembed' ), 9);