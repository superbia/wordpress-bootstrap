<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */

namespace _s\Theme;

?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="theme-color" content="#009dcc">
	<link rel="manifest" href="<?php echo esc_url( get_theme_file_uri( 'manifest.json' ) ); ?>">
	<link rel="apple-touch-icon" href="<?php echo esc_url( get_theme_file_uri( 'assets/dist/images/apple-touch-icon.png' ) ); ?>">
	<?php wp_head(); ?>
</head>
<body>
	<header class="header" id="header" role="banner">
		<?php
		wp_nav_menu(
			[
				'theme_location' => 'nav-primary',
				'container'      => false,
				'menu_id'        => 'navMain',
				'menu_class'     => 'navMain',
				'fallback_cb'    => false,
				'depth'          => 2,
			]
		);
		?>
	</header>
	<main class="main" id="main">

