<?php
/**
 * Card Shortcodes
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_card_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	$bs4_singletons['in_card'] = true;
	do_shortcode( $content );
	$bs4_singletons['in_card'] = false;

	$output = '';
	// if (isset($bs4_singletons['columns']) && is_array($bs4_singletons['columns']) &&
	// 	(!empty($bs4_singletons['columns']))) {
	// 	foreach ($bs4_singletons['columns'] as $col)
	// 		$output .= $col;
	// 	unset($bs4_singletons['columns']);
	// }

	return '<div' . bs4_get_shortcode_class($atts, 'card') . '>' . $output . '</div>';
}

bs4_add_shortcode( 'card', 'ts_bootstrap4_card_sc' );
