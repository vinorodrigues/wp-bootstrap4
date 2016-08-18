<?php
/**
 *  front-page.php
 *
 * Front Page only - may display singular page or default blog list
 *
 * @see: https://codex.wordpress.org/Template_Hierarchy
 */

function bs4_front_page_post_meta_class($classes) {
	$classes[] = 'hidden-xs-up';
	return $classes;
}

function bs4_front_page_header_after() {
	if ( is_active_sidebar('sidebar-0') ) dynamic_sidebar('sidebar-0');
}

// Post header (meta) class as hidden when is_page()
if (is_page() && get_theme_mod('hide_front_page_title', true))
	add_filter('post_meta_class', 'bs4_front_page_post_meta_class');

// Add sidebar-0 to front page
add_action('bs4_header_after', 'bs4_front_page_header_after');

get_header();
$content = apply_filters( 'bs4_front_page_content', '' );
if ($content != '') {
	echo $content;
} else {
	get_template_part( 'loop' );
	get_sidebar();
}
get_footer();
_d(__FILE__);
