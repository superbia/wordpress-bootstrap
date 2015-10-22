<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #main element and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */

?>
	<?php if ( current_user_can( 'activate_plugins' ) ) : ?>
		<div style="position:absolute;bottom:0;right:0;position:fixed;background-color:#000;color:#fff;font-family:Arial, sans-serif;font-size:9px;padding:5px;z-index:500;"><?php timer_stop(1); ?> seconds / <?php echo $wpdb->num_queries; ?> queries</div>
	<?php endif; ?>
	<?php wp_footer(); ?>
	</main>
</body>
</html>