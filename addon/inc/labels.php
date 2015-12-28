<?php
/*
 * Label Shortcode
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_label_sc( $atts, $content = null, $tag = '' ) {
	$attribs = bs4_shortcode_atts(
		array(
			'type' => 'default',
			'pill' => false,
			// 'id' => false,
		), $atts, $tag);
	$attribs = bs4_filter_booleans($attribs, array('pill'));

	$class = 'label';
	if ($attribs['pill']) $$class .= ' label-pill';

	if (in_array($attribs['type'], array('default', 'primary', 'success', 'info', 'warning', 'danger'))) {
		$class .= ' label-' . $attribs['type'];
	}

	$output = '<span' . bs4_get_shortcode_class($atts, $class) . '>';
	$output .= do_shortcode($content);
	$output .= '</span>';

	return $output;
}

add_shortcode( 'label', 'ts_bootstrap4_label_sc' );
