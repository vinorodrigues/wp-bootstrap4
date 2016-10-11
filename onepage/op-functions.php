<?php

// include_once 'inc/taxonomy-bg.php';
include_once 'inc/op-metabox-bg.php';


/* ---------- functions ---------- */

function in_front_page() {
	return (has_nav_menu('front-page') && is_front_page() && (get_option('show_on_front') == 'page'));
}

/* ---------- actions ---------- */

/**
 */
function bs4_onepage_after_setup_theme() {
	register_nav_menus( array(
		'front-page' => 'Front Page Menu',
		) );
}

add_action( 'after_setup_theme', 'bs4_onepage_after_setup_theme' );

/**
 */
function bs4_onepage_scripts() {
	if (!in_front_page()) return;

	// OP-WP-Bootstrap4 CSS
	wp_enqueue_style( 'op-wp-bootstrap4',
		get_template_directory_uri() . '/onepage/css/op-wp-bootstrap4' . DOTMIN . '.css',
		array( 'bootstrap', 'wp-boostrap4' ) );

	// OP-WP-Bootstrap4 JS
	wp_register_script( 'op-wp-bootstrap4',
		get_template_directory_uri() . '/onepage/js/op-wp-bootstrap4' . DOTMIN . '.js',
		array( 'jquery', 'bootstrap', 'easing' ),
		false,
		true );

	wp_localize_script( 'op-wp-bootstrap4',
		'onepage',
		array(
			'placement' => get_theme_mod('navbar_placement', 0),
			'offset' => apply_filters('bs4_scroll_offset_px', 50) ) );

	wp_enqueue_script( 'op-wp-bootstrap4' );
}

add_action( 'wp_enqueue_scripts', 'bs4_onepage_scripts' );

/**
 */
function bs4_onepage_admin_enqueue_scripts() {
	// CSS

	wp_enqueue_style( 'op-wp-bootstrap4-admin', get_template_directory_uri() .
		'/onepage/css/op-wp-bootstrap4-admin' . DOTMIN . '.css' );

	// JS

	wp_enqueue_media();

	// Registers and enqueues the required javascript.

	wp_register_script( 'op-metabox-bg', get_template_directory_uri() .
		'/onepage/js/op-metabox-bg' . DOTMIN . '.js', array( 'jquery' ) );

	wp_localize_script( 'op-metabox-bg', 'meta_image',
		array(
			'title' => 'Select Image',
			'button' => 'Select',
		) );
	wp_enqueue_script( 'op-metabox-bg' );
}

add_action( 'admin_enqueue_scripts', 'bs4_onepage_admin_enqueue_scripts' );

/* ---------- Filters ---------- */


/**
 */
// function bs4_onepage_post_class($classes) {
// 	$classes[] = 'onepageInner';
// 	return $classes;
// }

// add_filter( 'post_class', 'bs4_onepage_post_class' );

/**
 */
function bs4_onepage_content_class($classes) {
	// $classes[] = 'onepageOuter';
	$classes[] = 'onepage-inner';
	return $classes;
}

add_filter( 'bs4_content_class', 'bs4_onepage_content_class' );

/**
 */
function bs4_onepage_frontpage_template( $default ) {
	if (in_front_page()) return dirname(__FILE__).'/op-front-page.php';
	else return $default;
}

add_filter( 'frontpage_template', 'bs4_onepage_frontpage_template' );

/**
 */
function bs4_onepage_home_url( $url, $path ) {
	if (in_front_page()) return (('' == $path) || ('/' == $path)) ? '#top' : $url;
	else return $url;
}

add_filter( 'bs4_home_url', 'bs4_onepage_home_url', 10, 2 );

/**
 */
function bs4_onepage_get_sidebar_position( $default ) {
	return in_front_page() ? 0 : $default;
}

add_filter( 'bs4_get_sidebar_position', 'bs4_onepage_get_sidebar_position' );

/**
 */
function bs4_onepage_main_class( $class ) {
	if (!in_front_page()) return $class;

	if ('main' == $class) {
		$sec = 0;
	} else {
		$sec = intval($class);
		$class = '';
	}
	if ('' != $class) $class .= ' ';
	return $class . 'onepage onepage-' . $sec;
}

add_filter( 'bs4_main_class', 'bs4_onepage_main_class' );

/**
 *
 */
function bs4_onepage_main_attribs( $attribs ) {
	if ($attribs != '') $attribs .= ' ';
	$attribs = 'style="min-height: 768px"';
	return $attribs;
}

add_filter( 'bs4_main_attribs', 'bs4_onepage_main_attribs' );

/**
 */
function bs4_onepage_body_class( $classes ) {
	if (in_front_page()) $classes[] = 'onepager';
	return $classes;
}

add_filter('body_class', 'bs4_onepage_body_class' );

// eof
