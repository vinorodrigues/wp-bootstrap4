<?php
/*
 * Tag Shortcode (was 'Label')
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_icon_fa_sc( $atts, $content = null, $tag = '' ) {
	$attribs = bs4_shortcode_atts(
		array(
			'name' => '',
		), $atts, $tag);

	$output = '';
	if (!empty($attribs['name'])) {
		$output .= '<i' . bs4_get_shortcode_class($atts, 'fa fa-'.$attribs['name']) . '>';
		if (!empty($content)) $output .= $content;
		$output .= '</i>';
	}

	return $output;
}

bs4_add_shortcode( 'icon', 'ts_bootstrap4_icon_fa_sc' );
