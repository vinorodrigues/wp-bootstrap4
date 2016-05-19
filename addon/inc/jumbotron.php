<?php
/**
 * Jumbotron Shortcode
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_jumbotron_sc( $atts, $content = null, $tag = '' ) {
	$attribs = bs4_shortcode_atts(
		array(
			'fluid' => false,
			// 'id' => false,
		), $atts, $tag);
	$attribs = bs4_filter_booleans($attribs, array('fluid'));

	$class = 'jumbotron';
	if ($attribs['fluid']) $class .= ' jumbotron-fluid';

	$output = '<div' . bs4_get_shortcode_class($atts, $class) . '>';
	if ($attribs['fluid']) $output .= '<div class="container-fluid">';
	if (!empty($content)) $output .= do_shortcode($content);
	if ($attribs['fluid']) $output .= '</div>';
	$output .= '</div>';

	return $output;
}

bs4_add_shortcode( 'jumbotron', 'ts_bootstrap4_jumbotron_sc' );
