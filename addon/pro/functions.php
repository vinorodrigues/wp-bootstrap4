<?php

// include_once 'inc/taxonomy-bg.php';
include_once 'inc/metabox-bg.php';

/**
 */
function bs4_pro_setup() {

	register_nav_menus( array(
		'front-page' => 'Front Page Menu',
		) );

}

add_action( 'after_setup_theme', 'bs4_pro_setup' );

/**
 */
function bs4_filter_front_page_template( $template ) {
	return is_home() ? $template : dirname(__FILE__).'/front-page.php';
}

add_filter( 'frontpage_template', 'bs4_filter_front_page_template' );

/**
 */

function bs4_pro_admin_enqueue_scripts() {
	// CSS
	wp_enqueue_style( 'bs4-admin-styles-pro', get_template_directory_uri() . '/addon/pro/css/wp-bootstrap4-pro-admin.css' );

	// JS

	wp_enqueue_media();

	// Registers and enqueues the required javascript.
	wp_register_script( 'meta-box-image', get_template_directory_uri() . '/addon/pro/js/meta-box-image.js', array( 'jquery' ) );
	wp_localize_script( 'meta-box-image', 'meta_image',
		array(
			'title' => 'Select Image',
			'button' => 'Select',
		) );
	wp_enqueue_script( 'meta-box-image' );
}

add_action( 'admin_enqueue_scripts', 'bs4_pro_admin_enqueue_scripts' );
