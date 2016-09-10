<?php

function bs4_onepage_customize_register( $wp_customize ) {

	// Navbar

	$s = $wp_customize->get_section( 'cust_navbar' );

	$wp_customize->add_panel( 'pnl_cust_navbar', array(
		'priority'    => $s->priority + 1,
		'capability'  => 'edit_theme_options',
		'title'       => $s->title,
    	) );

	$wp_customize->add_section( 'cust_navbar_1', array(
		'title'       => 'Onepage Navbar',
		'priority'    => $s->priority,
		'panel'       => 'pnl_cust_navbar',
		'description' => 'The Onepage Navbar only appears on the front page,' .
			' and only when the scroll position is at the top.' .
			' Once scrolled the default Navbar activates.',
		) );

	$s->panel = 'pnl_cust_navbar';
	$s->title = 'General Navbar';

	// $wp_customize->add_setting( 'navbar_scroll_1', array( 'defult' => false ) );
	$wp_customize->add_setting( 'navbar_color_1', array( 'default' => 0 ) );
	$wp_customize->add_setting( 'navbar_color_custom_1', array( 'default' => '#000000' ) );
	$wp_customize->add_setting( 'navbar_shading_1', array( 'default' => 0 ) );

	// $wp_customize->add_control( 'navbar_scroll_1', array(
	// 	'type'    => 'checkbox',
	// 	'section' => 'cust_navbar_1',
	// 	'label'   => 'Use a different Navbar style on the Onepage Front Page',
	// 	) );

	$wp_customize->add_control( 'navbar_color_1', array(
		'type'    => 'select',
		'section' => 'cust_navbar_1',
		'label'   => 'Onepage Color Scheme',
		'choices' => array(
			0 => 'Primary',
			1 => 'Inverse',
			2 => 'Faded',
			3 => 'Default',
			4 => 'Transparent',
			5 => 'Custom',
			) ) );

	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'navbar_color_custom_1', array(
			'settings'    => 'navbar_color_custom_1',
			'section'     => 'cust_navbar_1',
			'label'       => 'Onepage Custom Color',
			'description' => 'Only valid when Color Scheme set to Custom',
			) ) );

	$wp_customize->add_control( 'navbar_shading_1', array(
		'type'    => 'select',
		'section' => 'cust_navbar_1',
		'label'   => 'Onepage Shading Scheme',
		'choices' => array(
			0 => 'Dark',
			1 => 'Light',
			) ) );
}

add_action( 'customize_register', 'bs4_onepage_customize_register' );

/*
function bs4_onepage_customize_preview_init() {
	$min = ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_script( 'onepage-customizer', get_template_directory_uri() .
		'/addon/onepage/js/onepage-customizer' . $min . '.js', array( 'jquery', 'customize-preview' ) );
}

add_action( 'customize_preview_init', 'bs4_onepage_customize_preview_init' );
*/

// eof
