<?php
/*
Plugin Name: TS WP-Bootstrap4 Widgets
Plugin URI: http://github.com/vinorodrigues/wp-bootstrap4
Description: Bootstrap 4 Widgets for the WP-Bootstrap4 Theme
Version: 0.0.9
Author: Vino Rodrigues
Author URI: http://vinorodrigues.com
License: MIT License
License URI: http://opensource.org/licenses/mit-license.html
*/

include_once 'inc/plugin-lib.php';
include_once 'inc/scrollspy.php';


// Tecsmith Options
function bs4_widgets_admin_menu() {
	if (function_exists('add_tecsmith_item'))
		add_tecsmith_item( __('Bootstrap 4 Widgets'), basename(__FILE__, '.php'), 4 );
}

add_action( 'admin_menu', 'bs4_widgets_admin_menu' );
