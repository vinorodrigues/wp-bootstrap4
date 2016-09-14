<?php

/**
 * Add theme support
 */
function bs4_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

add_action( 'after_setup_theme', 'bs4_woocommerce_support' );

// oef
