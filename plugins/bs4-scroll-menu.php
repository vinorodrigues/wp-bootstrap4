<?php
/*
Plugin Name: TS WP-Bootstrap4 Scroll Menu
Plugin URI: http://github.com/vinorodrigues/wp-bootstrap4
Description: Bootstrap 4 Scrolling menu changer for the WP-Bootstrap4 Theme
Version: 0.0.1
Author: Vino Rodrigues
Author URI: http://vinorodrigues.com
License: MIT License
License URI: http://opensource.org/licenses/mit-license.html
*/

if (!defined('SCROLL_MENU_URL')) {
	if (defined('BOOTSTRAP4_LOADS_PLUGINS') && BOOTSTRAP4_LOADS_PLUGINS)
		define( 'SCROLL_MENU_URL', trailingslashit(get_template_directory_uri()).'plugins');
	else
		define( 'SCROLL_MENU_URL', esc_url(plugin_dir_url( __FILE__ )));
}

/**
 * Tecsmith Options, does noting, just a place holder
 */
function bs4_scroll_menu_admin_menu() {
	if (function_exists('add_tecsmith_item'))
		add_tecsmith_item( __('Bootstrap 4 Scroll Menu'), basename(__FILE__, '.php'), 2 );
}

add_action( 'admin_menu', 'bs4_scroll_menu_admin_menu' );

/**
 * retrieves array of nav-bar settings
 */
function bs4_scroll_menu_get_navbar_struct() {
	global $bs4_singletons;
	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['scroll-menu'])) {
		$bs4_singletons['scroll-menu'] = array();

		$bs4_singletons['scroll-menu']['cs'] =
			bs4_navbar_color_class( get_theme_mod( 'navbar_color', 0 ) );
		$bs4_singletons['scroll-menu']['ss'] =
			bs4_navbar_shading_class( boolval( get_theme_mod( 'navbar_shading', 0 ) ) );
		$bs4_singletons['scroll-menu']['cu'] =
			bs4_navbar_color_class( get_theme_mod( 'i_navbar_color', 0 ), 'i' );
		$bs4_singletons['scroll-menu']['su'] =
			bs4_navbar_shading_class( boolval( get_theme_mod( 'i_navbar_shading', 0 ) ) );
	}
	return $bs4_singletons['scroll-menu'];
}

function bs4_scroll_menu_should_do() {
	$do = get_theme_mod('i_navbar_when', false);
	if (!$do || ($do == 0)) return false;
	if ($do == 1) return is_front_page();
	if ($do == 2) return true;
	return false;
}

/**
 * Load JS etc.
 */
function bs4_scroll_menu_scripts() {
	if (!bs4_scroll_menu_should_do()) return;

	if (get_theme_mod( 'i_navbar_color', 0 ) == 5) {
		ts_enqueue_style(
			'navbar-scroll-menu',
			'.bg-custom-i { background-color: ' . get_theme_mod(
				'i_navbar_color_custom', '#000000' ) . '; }' );
	}


	wp_register_script( 'bs4-scroll-menu',
		SCROLL_MENU_URL . '/js/bs4-scroll-menu' . DOTMIN . '.js',
		array( 'jquery' ),
		false,
		true );

	$struct = bs4_scroll_menu_get_navbar_struct();
	$xs = array('');
	$xu = array('');
	if ($struct['cs'] != $struct['cu']) {
		$xs[] = $struct['cs'];
		$xu[] = $struct['cu'];
	}
	if ($struct['ss'] != $struct['su']) {
		$xs[] = $struct['ss'];
		$xu[] = $struct['su'];
	}

	wp_localize_script( 'bs4-scroll-menu',
		'scroll_menu',
		array(
			'scrolled' => implode(' ', apply_filters('bs4_scroll_scrolled_classes', $xs)),
			'unscrolled' => implode(' ', apply_filters('bs4_scroll_unscrolled_classes', $xu)),
			'offset' => apply_filters('bs4_scroll_offset_px', 50) ) );

	wp_enqueue_script( 'bs4-scroll-menu' );
}

add_action( 'wp_enqueue_scripts', 'bs4_scroll_menu_scripts' );

/**
 * body_class filter
 */
function bs4_scroll_menu_body_class($classes) {
	if (bs4_scroll_menu_should_do()) $classes[] = 'unscrolled';
	return $classes;
}

add_filter( 'body_class', 'bs4_scroll_menu_body_class' );

/**
 * bs4_navbar_class filter
 */
function bs4_scroll_menu_navbar_class( $classes ) {
	if (bs4_scroll_menu_should_do()) {
		$struct = bs4_scroll_menu_get_navbar_struct();

		if ($struct['cs'] != $struct['cu']) {
			unset( $classes[ array_search( $struct['cs'], $classes ) ] );
			$classes[] = $struct['cu'];
		}
		if ($struct['ss'] != $struct['su']) {
			unset( $classes[ array_search( $struct['ss'], $classes ) ] );
			$classes[] = $struct['su'];
		}
		// $classes[] = 'unscrolled';
	}
 	return $classes;
}

add_filter( 'bs4_navbar_class', 'bs4_scroll_menu_navbar_class' );

/**
 * customize_register filter - builds navbar customizer
 */
function bs4_scroll_menu_customize_register( $wp_customize ) {

	// Navbar

	$s = $wp_customize->get_section( 'cust_navbar' );

	$wp_customize->add_panel( 'pnl_cust_navbar', array(
		'priority'    => $s->priority + 1,
		'capability'  => 'edit_theme_options',
		'title'       => $s->title,
    	) );

	$wp_customize->add_section( 'cust_i_navbar', array(
		'title'       => 'Initial Navbar',
		'priority'    => $s->priority,
		'panel'       => 'pnl_cust_navbar',
		'description' => 'The initial navbar only appears when the scroll position is at the top.' .
			' Once scrolled the default Navbar activates.',
		) );

	$s->panel = 'pnl_cust_navbar';
	$s->title = 'General Navbar';

	$wp_customize->add_setting( 'i_navbar_when', array( 'defult' => false ) );
	$wp_customize->add_setting( 'i_navbar_color', array( 'default' => 0 ) );
	$wp_customize->add_setting( 'i_navbar_color_custom', array( 'default' => '#000000' ) );
	$wp_customize->add_setting( 'i_navbar_shading', array( 'default' => 0 ) );

	$wp_customize->add_control( 'i_navbar_when', array(
		'type'    => 'select',
		'section' => 'cust_i_navbar',
	 	'label'   => 'When to activate nav-bar scroll',
		'choices' => array(
			0 => 'Never (Disabled)',
			1 => 'Only on front page',
			2 => 'All pages',
	 		) ) );

	$wp_customize->add_control( 'i_navbar_color', array(
		'type'    => 'select',
		'section' => 'cust_i_navbar',
		'label'   => 'Initial Color Scheme',
		'choices' => array(
			0 => 'Primary',
			1 => 'Inverse',
			2 => 'Faded',
			3 => 'Default',
			4 => 'Transparent',
			5 => 'Custom',
			) ) );

	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'i_navbar_color_custom', array(
			'settings'    => 'i_navbar_color_custom',
			'section'     => 'cust_i_navbar',
			'label'       => 'Initial Custom Color',
			'description' => 'Only valid when Color Scheme set to Custom',
			) ) );

	$wp_customize->add_control( 'i_navbar_shading', array(
		'type'    => 'select',
		'section' => 'cust_i_navbar',
		'label'   => 'Initial Shading Scheme',
		'choices' => array(
			0 => 'Dark',
			1 => 'Light',
			) ) );
}

add_action( 'customize_register', 'bs4_scroll_menu_customize_register' );

// oef
