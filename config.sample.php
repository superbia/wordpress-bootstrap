<?php
/**
 * Example environment config file
 *
 * Copy to config.environment.php where environment is one of
 * dev, staging or production.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 *
 * @package _s
 */

/**
 * WordPress Dev Environment DB credentials
 */
define( 'DB_NAME', '' );
define( 'DB_USER', '' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */

