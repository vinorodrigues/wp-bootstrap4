<?php
/**
 *  front-page.php
 *
 * Front Page only - may display singular page or default blog list
 *
 * @see: https://codex.wordpress.org/Template_Hierarchy
 */

function bs4_front_page_post_meta_class($classes) {
	$classes[] = 'front-page';
	return $classes;
}

function bs4_front_page_leader_after() {
	if ( is_active_sidebar('sidebar-0') ) dynamic_sidebar('sidebar-0');
}

// Post header (meta) class as hidden when is_page()
if (is_page() && get_theme_mod('hide_front_page_title', true))
	add_filter('post_meta_class', 'bs4_front_page_post_meta_class');

// Add sidebar-0 to front page
add_action('tha_leader_after', 'bs4_front_page_leader_after');

get_header();
get_template_part( 'loop' );
get_sidebar();
get_footer();
