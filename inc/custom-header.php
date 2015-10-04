<?php
/**
 * Sample implementation of the Custom Header feature
 * @see: http://codex.wordpress.org/Custom_Headers
 *
 * You can add an optional custom header image to header.php like so ...
 *
 *	<?php $header_image = get_header_image();
 *	if ( ! empty( $header_image ) ) { ?>
 *		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
 *			<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
 *		</a>
 *	<?php } // if ( ! empty( $header_image ) ) ?>
 */

include_once get_stylesheet_directory() . "/bs4-config.php";

/**
 * Setup the WordPress core custom header feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for previous versions.
 */
function bs4_custom_header_setup() {
	global $wp_version;

	$args = array(
		// 'default-image'          => '',
		'width'                  => FEATURED_IMAGE_X,
		'height'                 => FEATURED_IMAGE_Y,
		'flex-height'            => true,
		// 'flex-width'             => false,
		//'uploads'                => true,
		'random-default'         => true,
		'header-text'            => false,
		// 'default-text-color'     => '',
		// 'wp-head-callback'       => 'bs4_header_style',
		// 'admin-head-callback'    => 'bs4_admin_header_style',
		// 'admin-preview-callback' => 'bs4_admin_header_image',
	);

	$args = apply_filters( 'bs4_custom_header_args', $args );

	if ( version_compare( $wp_version, '3.4', '>=' ) )
		add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'bs4_custom_header_setup' );

/**
 * Styles the header image and text displayed on the blog
 */
function bs4_header_style() {
	// do notyhing
	/* ?>
	<style type="text/css">
	</style>
	<?php */
}

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 */
function bs4_admin_header_style() {
	// do notyhing
	/* ?>
	<style type="text/css">
	</style>
	<?php */
}

/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 */
function bs4_admin_header_image() {
	$header_image = get_header_image();
	if ( ! empty( $header_image ) ) : ?>
		<div id="headimg">
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		</div>
	<?php
	endif;
}
