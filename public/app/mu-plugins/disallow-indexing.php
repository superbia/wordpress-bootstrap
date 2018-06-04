<?php
/**
Plugin Name:  Disallow Indexing
Description:  Disallow indexing on non-production environments.
Version:      1.0.0
Author:       Superbia
Author URI:   https://superbia.com.au/
License:      MIT License

@package SuperbiaBootstrap
 */

if ( false === WP_PRODUCTION_SERVER ) {
	add_action( 'pre_option_blog_public', '__return_zero' );
}
