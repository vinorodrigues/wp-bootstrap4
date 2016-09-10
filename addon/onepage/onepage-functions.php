<?php

// include_once 'inc/taxonomy-bg.php';
include_once 'inc/metabox-bg.php';
include_once 'inc/onepage-customizer.php';


/* ---------- functions ---------- */

function in_front_page() {
	return (is_front_page() && (get_option('show_on_front') == 'page'));
}

/**
 */
function bs4_onepage_get_navbar_struct() {
	global $bs4_singletons;
	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['onepage'])) {
		$bs4_singletons['onepage'] = array();

		$bs4_singletons['onepage']['cn'] =
			bs4_navbar_color_class( get_theme_mod( 'navbar_color', 0 ) );
		$bs4_singletons['onepage']['sn'] =
			bs4_navbar_shading_class( boolval( get_theme_mod( 'navbar_shading', 0 ) ) );
		$bs4_singletons['onepage']['c1'] =
			bs4_navbar_color_class( get_theme_mod( 'navbar_color_1', 0 ), 'onepage' );
		$bs4_singletons['onepage']['s1'] =
			bs4_navbar_shading_class( boolval( get_theme_mod( 'navbar_shading_1', 0 ) ) );
	}
	return $bs4_singletons['onepage'];
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

	$min = ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) ? '' : '.min';

	// CSS
	wp_enqueue_style( 'wp-bootstrap4-onepage',
		get_template_directory_uri() . '/addon/onepage/css/wp-bootstrap4-onepage' . $min . '.css',
		array( 'bootstrap', 'wp-boostrap4' ) );

	// JS
	wp_register_script( 'easing',
		get_template_directory_uri() . '/addon/onepage/vendor/easing/js/easing' . $min . '.js',
		array( 'jquery' ),
		'1.3',
		true );

	wp_register_script( 'wp-bootstrap4-onepage',
		get_template_directory_uri() . '/addon/onepage/js/wp-bootstrap4-onepage' . $min . '.js',
		array( 'jquery', 'bootstrap', 'easing' ),
		false,
		true );

	$struct = bs4_onepage_get_navbar_struct();
	$xn = array('scrolled');
	$x1 = array('initial');
	if ($struct['cn'] != $struct['c1']) {
		$xn[] = $struct['cn'];
		$x1[] = $struct['c1'];
	}
	if ($struct['sn'] != $struct['s1']) {
		$xn[] = $struct['sn'];
		$x1[] = $struct['s1'];
	}

	wp_localize_script( 'wp-bootstrap4-onepage',
		'onepage',
		array(
			'navbar' => array(
				'placement' => get_theme_mod('navbar_placement', 0),
				'normal' => implode(' ', $xn),
				'scrolltop' => implode(' ', $x1),
				),
			'offset' => 50 ) );

	wp_enqueue_script( 'wp-bootstrap4-onepage' );
}

add_action( 'wp_enqueue_scripts', 'bs4_onepage_scripts' );

/**
 */
function bs4_onepage_admin_enqueue_scripts() {
	$min = ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) ? '' : '.min';

	// CSS

	wp_enqueue_style( 'wp-bootstrap4-onepage-admin', get_template_directory_uri() .
		'/addon/onepage/css/wp-bootstrap4-onepage-admin' . $min . '.css' );

	// JS

	wp_enqueue_media();

	// Registers and enqueues the required javascript.

	wp_register_script( 'metabox-bg', get_template_directory_uri() .
		'/addon/onepage/js/metabox-bg' . $min . '.js', array( 'jquery' ) );

	wp_localize_script( 'metabox-bg', 'meta_image',
		array(
			'title' => 'Select Image',
			'button' => 'Select',
		) );
	wp_enqueue_script( 'metabox-bg' );
}

add_action( 'admin_enqueue_scripts', 'bs4_onepage_admin_enqueue_scripts' );

/* ---------- Filters ---------- */

/**
 */
function bs4_onepage_frontpage_template( $default ) {
	if (in_front_page()) return dirname(__FILE__).'/onepage-front-page.php';
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
 */
function bs4_onepage_body_class( $classes ) {
	if (in_front_page()) $classes[] = 'onepager';
	return $classes;
}

add_filter('body_class', 'bs4_onepage_body_class' );

/**
 */
function bs4_onepage_navbar_class( $classes ) {
	if (in_front_page()) {
		$struct = bs4_onepage_get_navbar_struct();
		if ($struct['cn'] != $struct['c1']) {
			unset( $classes[ array_search( $struct['cn'], $classes ) ] );
			$classes[] = $struct['c1'];
		}
		if ($struct['sn'] != $struct['s1']) {
			unset( $classes[ array_search( $struct['sn'], $classes ) ] );
			$classes[] = $struct['s1'];
		}
		$classes[] = 'initial';
	}
 	return $classes;
}

add_filter( 'bs4_navbar_class', 'bs4_onepage_navbar_class' );

// eof
