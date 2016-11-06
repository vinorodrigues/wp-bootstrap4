<?php
/*
 * Template Name: Sidebars on Both Sides Page
 * Description: Page template with sidebars on both sides
 */

function bs4_this_sidebar_position($default) { return 3; }
add_filter('bs4_get_sidebar_position', 'bs4_this_sidebar_position');

include 'page.php';
