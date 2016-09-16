<?php
/*
Plugin Name: TS WP-Bootstrap4 Scroll Menu
Plugin URI: http://github.com/vinorodrigues/wp-bootstrap4
Description: Bootstrap 4 Scrolling menu changer for the WP-Bootstrap4 Theme
Version: 0.0.1
Author: Vino Rodrigues
Author URI: http://vinorodrigues.com
License: MIT License
License URI: http://opensource.org/licenses/mit-license.html
*/

// Tecsmith Options
function bs4_scroll_menu_admin_menu() {
	if (function_exists('add_tecsmith_item'))
		add_tecsmith_item( __('Bootstrap 4 Scroll Menu'), basename(__FILE__, '.php'), 2 );
}

add_action( 'admin_menu', 'bs4_scroll_menu_admin_menu' );
