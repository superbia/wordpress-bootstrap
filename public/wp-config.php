<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Run WordPress in the wp sub-directory
 * * Server/Environment
 * * MySQL settings
 * * Database table prefix
 * * Disable the theme editor
 * * Set number of saved revisions
 * * Debugging per environment
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

/** Running wordpress install from the wp directory */
define( 'WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp' );
define( 'WP_HOME',    'http://' . $_SERVER['SERVER_NAME'] );

/** Move wp-content back into web root */
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/app' );
define( 'WP_CONTENT_URL', WP_HOME . '/app' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'WP_DB_PREFIX';

/** Disable the theme and plugin editors */
define( 'DISALLOW_FILE_EDIT', true );

/** Set the number of post revisions */
define( 'WP_POST_REVISIONS', 3 );

/**
 * Define type of server
 *
 * Depending on the type other stuff can be configured
 * Note: Define them all, don't skip one if other is already defined
 */
define( 'DB_CREDENTIALS_PATH', dirname( __DIR__ ) ); // cache it for multiple use
define( 'WP_DEV_SERVER', file_exists( DB_CREDENTIALS_PATH . '/config.dev.php' ) );
define( 'WP_STAGING_SERVER', file_exists( DB_CREDENTIALS_PATH . '/config.staging.php' ) );
define( 'WP_PRODUCTION_SERVER', file_exists( DB_CREDENTIALS_PATH . '/config.production.php' ) );

/**
 * Load DB credentials
 */
if ( WP_DEV_SERVER ) {
	require DB_CREDENTIALS_PATH . '/config.dev.php';
} elseif ( WP_STAGING_SERVER ) {
	require DB_CREDENTIALS_PATH . '/config.staging.php';
} else {
	require DB_CREDENTIALS_PATH . '/config.production.php';
}

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
if (  WP_DEV_SERVER ) {
	define( 'WP_DEBUG', true );
	define( 'WP_DEBUG_LOG', true ); // Stored in app/debug.log
	define( 'WP_DEBUG_DISPLAY', true );
	define( 'SCRIPT_DEBUG', true );
	define( 'SAVEQUERIES', true );
} else if ( WP_STAGING_SERVER ) {
	define( 'WP_DEBUG', true );
	define( 'WP_DEBUG_LOG', true ); // Stored in app/debug.log
	define( 'WP_DEBUG_DISPLAY', false );
} else {
	define( 'WP_DEBUG', false );
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
