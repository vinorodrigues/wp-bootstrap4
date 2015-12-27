<?php
/*
 * Label Shortcode
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_label_sc( $atts, $content = null, $tag = '' ) {
	$atts = bs4_shortcode_atts(
		array(
			'type' => 'default',
			'pill' => false,
			// 'id' => false,
		), $atts, $tag);
	$atts = bs4_filter_booleans($atts, array('pill'));

	$output = '<span class="label';
	if ($atts['pill']) $output .= ' label-pill';

	if (in_array($atts['type'], array('default', 'primary', 'success', 'info', 'warning', 'danger'))) {
		$output .= ' label-' . $atts['type'];
	}

	$output .= '">';
	$output .= do_shortcode($content);
	$output .= '</span>';

	return $output;
}

add_shortcode( 'label', 'ts_bootstrap4_label_sc' );
