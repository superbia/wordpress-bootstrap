<?php
/**
 * Tinymce customisations.
 *
 * @package _s
 */

Class _s_Tinymce_Mods {

	function __construct() {
		add_action( 'admin_init', array( $this, 'init' ) );
	}

	function init() {
		add_filter( 'tiny_mce_before_init', array( $this, 'force_paste_as_plain_text' ) );
		add_filter( 'teeny_mce_before_init', array( $this, 'force_paste_as_plain_text' ) );
		add_filter( 'tiny_mce_before_init', array( $this, 'setup_style_formats' ) );
		add_filter( 'teeny_mce_plugins', array( $this, 'load_paste_in_teeny' ) );
		add_filter( 'mce_buttons', array( $this, 'customise_primary_toolbar' ) );
		add_filter( 'mce_buttons_2', array( $this, 'customise_advanced_toolbar' ) );
	}

	function force_paste_as_plain_text( $init_array ) {
		global $tinymce_version;

		if ( $tinymce_version[0] < 4 ) {
			$init_array[ 'paste_text_sticky' ] = true;
			$init_array[ 'paste_text_sticky_default' ] = true;
		} else {
			$init_array[ 'paste_as_text' ] = true;
		}

		return $init_array;
	}

	function load_paste_in_teeny( $plugins ) {
		return array_merge( $plugins, (array) 'paste' );
	}

	function setup_style_formats( $init_array ) {
		// Define the style_formats array
		$style_formats = array(  
			array(
				'title'    => 'Heading',
				'block'    => 'h3',
				'wrapper'  => false,
			),
			array(
				'title'    => 'Intro text',
				'block'    => 'p',
				'classes'  => 'intro-text',
				'wrapper'  => false,
			),
		);

		// Insert the array, JSON ENCODED, into 'style_formats'
		$init_array['style_formats'] = json_encode( $style_formats );

		return $init_array; 
	}

	function customise_primary_toolbar( $buttons ) {
		// Add the styles select
		array_unshift( $buttons, 'styleselect' );
		return $buttons;
	}

	function customise_advanced_toolbar( $buttons ) {
		// Remove the paste as plain text toggle button if it exists
		if ( ( $key = array_search( 'pastetext', $buttons ) ) !== false ) {
			unset( $buttons[ $key ] );
		}

		return $buttons;
	}
}

new _s_Tinymce_Mods();