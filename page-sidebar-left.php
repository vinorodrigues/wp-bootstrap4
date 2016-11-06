<?php
/*
 * Template Name: Sidebar on Left Page
 * Description: Page template with both sidebars on the left
 */

function bs4_this_sidebar_position($default) { return 1; }
add_filter('bs4_get_sidebar_position', 'bs4_this_sidebar_position');

include 'page.php';
