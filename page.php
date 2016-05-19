<?php
/**
 *  page.php
 *
 * Displays singular pages
 *
 * @see: https://codex.wordpress.org/Template_Hierarchy
 */

get_header();
get_template_part( 'loop' );
get_sidebar();
get_footer();
