<?php

/**
 *
 */
function bs4_pro_setup() {

	register_nav_menus( array(
		'front-page' => 'Front Page Menu',
		) );

}

add_action( 'after_setup_theme', 'bs4_pro_setup' );
