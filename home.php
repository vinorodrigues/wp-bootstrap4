<?php
/**
 * home.php
 *
 * Displays list of blog entries
 *
 * @see: https://codex.wordpress.org/Template_Hierarchy
 */

function bs4_home_post_meta_class($classes) {
	$classes[] = 'home-page';
	return $classes;
}


get_header();

$page_for_posts = (!is_front_page() && (get_option('show_on_front') == 'page')) ?
	get_option('page_for_posts') :
	false;

if (false !== $page_for_posts) {
	$GLOBALS['post'] = $page_for_posts;
	setup_postdata(get_page($page_for_posts));

	add_filter('post_meta_class', 'bs4_home_post_meta_class');
	get_template_part( 'content', get_post_format() );
	remove_filter('post_meta_class', 'bs4_home_post_meta_class');

	rewind_posts();
}

get_template_part( 'loop' );
get_sidebar();
get_footer();
