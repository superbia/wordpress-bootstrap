<?php
/**
 * WordPress Master Config
 * 
 * Loads config file based on the current environment
 * environment is set via hostname or via the -e flag when using wp-cli
 *
 * @package    Superbia WordPress Master Config
 * @version    1.0.0
 * @author     Superbia  <hello@superbia.com.au>
 */

/** Disable the theme and plugin editors */
define( 'DISALLOW_FILE_EDIT', true );

/** Set the number of post revisions */
define( 'WP_POST_REVISIONS', 3 );

/* MySQL - General Settings */
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );
$table_prefix  = 'wp_';

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );


/** 
 * Set the environment
 */

/** WP CLI: set environment from --env=<environment> argument */
if ( defined( 'WP_CLI' ) ) {
    foreach ( $argv as $arg ) {
        if ( preg_match( '/--env=([a-z]+)/', $arg, $m ) ) {
            define( 'WP_ENV', $m[1] );
        }
    }
}

/** Set environment from hostname */
require_once( dirname(__FILE__) . '/config.env.php' );

/** Default to the local environment */
if ( ! defined( 'WP_ENV' ) ) {
	define( 'WP_ENV', 'local' );
}

/** Load environment specific configuration */
require_once( dirname(__FILE__) . '/config.' . WP_ENV . '.php' );
