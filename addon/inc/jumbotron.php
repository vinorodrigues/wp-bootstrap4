<?php
/**
 * Jumbotron Shortcode
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_jumbotron_sc( $atts, $content = null, $tag = '' ) {
	$atts = bs4_shortcode_atts(
		array(
			'fluid' => false,
			// 'id' => false,
		), $atts, $tag);
	$atts = bs4_filter_booleans($atts, array('fluid'));

	$output = '<div class="jumbotron';
	if ($atts['fluid']) $output .= ' jumbotron-fluid';
	$output .= '">';
	if ($atts['fluid']) $output .= '<div class="container-fluid">';
	$output .= do_shortcode($content);
	if ($atts['fluid']) $output .= '</div>';
	$output .= '</div>';

	return $output;
}

add_shortcode( 'jumbotron', 'ts_bootstrap4_jumbotron_sc' );
