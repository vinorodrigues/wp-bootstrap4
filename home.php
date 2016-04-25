<?php
/**
 *  home.php
 *
 * @see: https://codex.wordpress.org/Template_Hierarchy
 */

function bs4_home_header_after() {
	if ( is_active_sidebar('sidebar-0') ) dynamic_sidebar('sidebar-0');
}

add_action('bs4_header_after', 'bs4_home_header_after');

get_header();
include 'bs4-theloop.php';
get_sidebar();
get_footer();
