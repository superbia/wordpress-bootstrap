<?php
/**
 * Editor customisations.
 *
 * @package _s
 */

namespace _s\Theme;

add_action( 'admin_init', __NAMESPACE__ . '\\register_hooks' );

/**
 * Register hooks with admin_init.
 */
function register_hooks() {
	add_filter( 'tiny_mce_before_init',  __NAMESPACE__ . '\\setup_style_formats' );
	add_filter( 'mce_buttons',           __NAMESPACE__ . '\\customise_primary_toolbar' );
	add_filter( 'mce_buttons_2',         __NAMESPACE__ . '\\customise_advanced_toolbar' );
	add_filter( 'tiny_mce_before_init',  __NAMESPACE__ . '\\force_paste_as_plain_text' );
	add_filter( 'teeny_mce_before_init', __NAMESPACE__ . '\\force_paste_as_plain_text' );
	add_filter( 'teeny_mce_plugins',     __NAMESPACE__ . '\\load_paste_in_teeny' );
}

/**
 * Setup custom editor styles.
 *
 * @param  array $mce_init TinyMCE config.
 * @return array $mce_init Modified config with custom styles.
 */
function setup_style_formats( $mce_init ) {
	$style_formats = array(
		array(
			'title'   => 'Heading',
			'block'   => 'h2',
			'wrapper' => false,
		),
		array(
			'title'   => 'Sub-heading',
			'block'   => 'h3',
			'wrapper' => false,
		),
		array(
			'title'   => 'Quote credit',
			'inline'  => 'cite',
			'classes' => 'quote-credit',
			'wrapper' => false,
		),
	);

	$mce_init['style_formats'] = wp_json_encode( $style_formats );

	return $mce_init;
}

/**
 * Setup custom editor styles.
 *
 * @param  array $buttons Primary toolbar buttons.
 * @return array $buttons Modified buttons array.
 */
function customise_primary_toolbar( $buttons ) {
	// Remove the formats select.
	$formatselect = array_search( 'formatselect', $buttons, true );

	if ( false !== $formatselect ) {
		unset( $buttons[ $formatselect ] );
	}

	// Add the styles select to the beginning of the toolbar.
	array_unshift( $buttons, 'styleselect' );

	return $buttons;
}

/**
 * Setup custom editor styles.
 *
 * @param  array $buttons Second-row list of buttons.
 * @return array $buttons Modified buttons array.
 */
function customise_advanced_toolbar( $buttons ) {
	// Remove the paste as plain text toggle button if it exists.
	$key = array_search( 'pastetext', $buttons, true );

	if ( false !== $key ) {
		unset( $buttons[ $key ] );
	}

	return $buttons;
}

/**
 * Force paste as plain text.
 *
 * @param  array $init_array An array with TinyMCE config.
 * @return array $init_array Modified TinyMCE init array.
 */
function force_paste_as_plain_text( $init_array ) {
	global $tinymce_version;

	if ( $tinymce_version[0] < 4 ) {
		$init_array['paste_text_sticky']         = true;
		$init_array['paste_text_sticky_default'] = true;
	} else {
		$init_array['paste_as_text'] = true;
	}

	return $init_array;
}

/**
 * Load paste in teeny.
 *
 * @param  array $plugins An array of teenyMCE plugins.
 */
function load_paste_in_teeny( $plugins ) {
	return array_merge( $plugins, (array) 'paste' );
}
