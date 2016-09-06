<?php

// include_once 'inc/taxonomy-bg.php';
include_once 'inc/metabox-bg.php';

/* ---------- functions ---------- */

function in_front_page() {
	return (is_front_page() && (get_option('show_on_front') == 'page'));
}

/* ---------- actions ---------- */

/**
 */
function bs4_onepage_after_setup_theme() {
	register_nav_menus( array(
		'front-page' => 'Front Page Menu',
		) );
}

/**
 */
function bs4_onepage_scripts() {
	$min = ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) ? '' : '.min';

	// CSS
	wp_enqueue_style( 'wp-bootstrap4-onepage',
		get_template_directory_uri() . '/addon/onepage/css/wp-bootstrap4-onepage.css',
		array( 'bootstrap', 'wp-boostrap4' ) );

	// JS
	wp_register_script( 'easing',
		get_template_directory_uri() . '/addon/onepage/js/easing' . $min . '.js',
		array( 'jquery' ),
		'1.3',
		true );

	wp_register_script( 'wp-bootstrap4-onepage',
		get_template_directory_uri() . '/addon/onepage/js/wp-bootstrap4-onepage.js',
		array( 'jquery', 'bootstrap', 'easing' ),
		false,
		true );

	wp_localize_script( 'wp-bootstrap4-onepage',
		'onepage',
		array(
			'navbar_placement' => get_theme_mod('navbar_placement', 0),
		) );

	wp_enqueue_script( 'wp-bootstrap4-onepage' );
}

/**
 */
function bs4_onepage_admin_enqueue_scripts() {
	// CSS

	wp_enqueue_style( 'wp-bootstrap4-onepage-admin', get_template_directory_uri() .
		'/addon/onepage/css/wp-bootstrap4-onepage-admin.css' );

	// JS

	wp_enqueue_media();

	// Registers and enqueues the required javascript.
	wp_register_script( 'metabox-bg', get_template_directory_uri() .
		'/addon/onepage/js/metabox-bg.js', array( 'jquery' ) );

	wp_localize_script( 'metabox-bg', 'meta_image',
		array(
			'title' => 'Select Image',
			'button' => 'Select',
		) );
	wp_enqueue_script( 'metabox-bg' );
}

/* ---------- Filters ---------- */

/**
 */
function bs4_onepage_frontpage_template( $template ) {
	return dirname(__FILE__).'/front-page.php';
}

/**
 */
function bs4_onepage_home_url($url, $path) {
	return (('' == $path) || ('/' == $path)) ? '#top' : $url;
}

/**
 */
function bs4_onepage_get_sidebar_position($default) {
	return 0;
}

/**
 */
function bs4_onepage_main_class($class) {
	if ('main' == $class) {
		$sec = 0;
	} else {
		$sec = intval($class);
		$class = '';
	}
	if ('' != $class) $class .= ' ';
	return $class . 'onepage onepage-' . $sec;
}

/**
 */
function bs4_onepage_body_class($classes) {
	$classes[] = 'onepager';
	return $classes;
}

/**
 */
function bs4_onepage_navbar_class($classes) {
	$classes[] = 'initial';
 	return $classes;
}

/* ---------- All the filters ---------- */

add_action( 'after_setup_theme', 'bs4_onepage_after_setup_theme' );
add_action( 'admin_enqueue_scripts', 'bs4_onepage_admin_enqueue_scripts' );

if (in_front_page()) {
	add_action( 'wp_enqueue_scripts', 'bs4_onepage_scripts' );
	add_filter( 'frontpage_template', 'bs4_onepage_frontpage_template' );

	add_filter( 'bs4_home_url', 'bs4_onepage_home_url', 10, 2 );
	add_filter( 'bs4_get_sidebar_position', 'bs4_onepage_get_sidebar_position' );
	add_filter( 'bs4_main_class', 'bs4_onepage_main_class' );
	add_filter('body_class', 'bs4_onepage_body_class' );
	add_filter( 'bs4_navbar_class', 'bs4_onepage_navbar_class' );
}

// eof
