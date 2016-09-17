<?php
/*
Plugin Name: TS WP-Bootstrap4 Layout Shortcodes
Plugin URI: http://github.com/vinorodrigues/wp-bootstrap4
Description: Bootstrap 4 Layout shortcodes for the WP-Bootstrap4 Theme
Version: 0.0.9
Author: Vino Rodrigues
Author URI: http://vinorodrigues.com
License: MIT License
License URI: http://opensource.org/licenses/mit-license.html
*/

include_once 'inc/plugin-lib.php';
include_once 'inc/grid.php';
include_once 'inc/equal-heights.php';


// Tecsmith Options
function bs4_layout_admin_menu() {
	if (function_exists('add_tecsmith_item'))
		add_tecsmith_item( __('Bootstrap 4 Layout shortcodes'), basename(__FILE__, '.php'), 3 );
}

add_action( 'admin_menu', 'bs4_layout_admin_menu' );
