<?php
/**
 * Implementation of Custom Backgrounds
 * @see: http://codex.wordpress.org/Custom_Backgrounds
 */

/**
 *
 */
function bs4_custom_background_setup() {
	$args = array(
		'default-color' => 'FFFFFF',
		'default-image' => '',
		// 'admin-head-callback' => '',
		// 'admin-preview-callback' => '',
	);

	add_theme_support( 'custom-background', $args );
}
add_action( 'after_setup_theme', 'bs4_custom_background_setup' );
