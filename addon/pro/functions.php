<?php

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
