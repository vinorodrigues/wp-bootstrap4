<?php
/*
 * Template Name: Sidebar on Right Page
 * Description: Page template with both sidebars on the right
 */

function bs4_this_sidebar_position($default) { return 2; }
add_filter('bs4_get_sidebar_position', 'bs4_this_sidebar_position');

include 'page.php';
