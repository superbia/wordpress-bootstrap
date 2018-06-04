<?php
/**
Plugin Name:  Performance resets
Description:  Remove or alter core functionality that has performance impacts.
Version:      1.0.0
Author:       Superbia
Author URI:   https://superbia.com.au/
License:      MIT License

@package SuperbiaBootstrap
 */

namespace Superbia\PerformanceResets;

add_action( 'add_meta_boxes', __NAMESPACE__ . '\\remove_custom_fields_metabox' );

/**
 * Remove the custom fields metabox.
 *
 * The custom fields metabox uses a slow query.
 *
 * @see https://core.trac.wordpress.org/ticket/33885
 */
function remove_custom_fields_metabox() {
	remove_meta_box( 'postcustom', null, 'normal' );
};
