<?php
/**
 * Environment Declaration
 * 
 * @package    Superbia WordPress Master Config
 * @version    1.0.0
 * @author     Superbia  <hello@superbia.com.au>
 */

if ( ! defined( 'WP_ENV' ) && ! defined( 'WP_CLI' ) ) {
	switch ( strtolower( $_SERVER['HTTP_HOST'] ) ) {
		case 'domain.com' :
			define( 'WP_ENV', 'production' );
			define( 'WP_DEBUG', false );
		break;
		
		case 'wp-bootstrap-stage.dev' :
			define( 'WP_ENV', 'staging' );
			define( 'WP_DEBUG', false );
		break;

		default :
			define( 'WP_ENV', 'local' );
			define( 'WP_DEBUG', true );
		break;
	}
}