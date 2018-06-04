<?php
/**
Plugin Name:  Google analytics
Description:  Add google analytics tracking code in production.
Version:      1.0.0
Author:       Superbia
Author URI:   https://superbia.com.au/
License:      MIT License

@package SuperbiaBootstrap
*/

namespace Superbia\Analytics;

/**
 * Tracking code
 *
 * Add tracking code to wp_head when:
 * - GA_TRACKING_ID is defined
 * - WP_PRODUCTION_SERVER is true
 * - and the current user cannot publish pages... users below the editor role.
 *
 * @return void
 */
function google_analytics_tracking_code() {
	if ( ! defined( 'GA_TRACKING_ID' ) ) {
		return;
	}

	if ( WP_PRODUCTION_SERVER && false === current_user_can( 'publish_pages' ) ) : ?>
		<script>(function(G,o,O,g,l){G.GoogleAnalyticsObject=O;G[O]||(G[O]=function(){(G[O].q=G[O].q||[]).push(arguments)});G[O].l=+new Date;g=o.createElement('script'),l=o.scripts[0];g.src='//www.google-analytics.com/analytics.js';l.parentNode.insertBefore(g,l)}(this,document,'ga'));ga('create','<?php echo esc_attr( GA_TRACKING_ID ); ?>');ga('send','pageview')</script>
	<?php
	endif;
}

add_action( 'wp_head', __NAMESPACE__ . '\\google_analytics_tracking_code' );
