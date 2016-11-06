<?php
/*
 * Template Name: Full Page (no sidebars)
 * Description: Page template without sidebars
 */

function bs4_this_sidebar_position($default) { return 0; }
add_filter('bs4_get_sidebar_position', 'bs4_this_sidebar_position');

include 'page.php';
