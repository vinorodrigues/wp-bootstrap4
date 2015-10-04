<?php
/**
 * Implementation of Custom Backgrounds
 * @see: http://codex.wordpress.org/Custom_Backgrounds
 */

/**
 *
 */
function bs4_custom_background_setup() {
	global $wp_version;

	$args = array(
		// 'default-color' => 'FFFFFF',
		// 'default-image' => '',
		// 'wp-head-callback' => '',
		// 'admin-head-callback' => '',
		// 'admin-preview-callback' => '',
	);

	$args = apply_filters( 'bs4_custom_background_args', $args );

	if ( version_compare( $wp_version, '3.4', '>=' ) )
		add_theme_support( 'custom-background', $args );
}
add_action( 'after_setup_theme', 'bs4_custom_background_setup' );


/* eof */
