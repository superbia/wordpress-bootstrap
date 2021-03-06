<?php
/**
 * Media customisations.
 *
 * @package _s
 */

namespace _s\Theme;

add_filter( 'oembed_fetch_url',  __NAMESPACE__ . '\\vimeo_oembed_params', 10, 3 );
add_filter( 'oembed_result',     __NAMESPACE__ . '\\modify_youtube_oembed_result', 10, 2 );

/**
 * Setup filter for oembed urls in custom fields.
 */
add_filter( '_s_custom_field_oembed', array( $GLOBALS['wp_embed'], 'autoembed' ), 9 );

/**
 * Customise Vimeo oembed request parameters.
 * - simplify the player.
 *
 * @param string $provider  URL of the oEmbed provider.
 * @param string $url       URL of the content to be embedded.
 * @param array  $args      arguments, usually passed from a shortcode.
 * @return string Updated oembed provider/request url.
 */
function vimeo_oembed_params( $provider, $url, $args ) {

	// Only modify Vimeo provider urls.
	if ( false !== strpos( $url, 'vimeo.com' ) ) {
		$args['color']    = 'a0a0a0';
		$args['byline']   = 0;
		$args['portrait'] = 0;
		$args['title']    = 0;
		$args['badge']    = 0;
		// Unset height for responsive layouts.
		unset( $args['height'] );
	}

	// Build the provide request url.
	$parameters = rawurlencode( http_build_query( $args ) );
	$provider  .= '&' . $parameters;

	return $provider;
}

/**
 * Modify Youtube oembed result.
 * - simplify the player.
 *
 * @param string $data  The returned oEmbed HTML.
 * @param string $url   URL of the content to be embedded.
 */
function modify_youtube_oembed_result( $data, $url ) {
	if ( false !== strpos( $url, 'youtube.com' ) ) {
		$data = str_replace( '?feature=oembed', '?feature=oembed&showinfo=0&rel=0&autohide=1', $data );
	}

	return $data;
}
