<?php
/*
 * Tag Shortcode (was 'Label')
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_tag_sc( $atts, $content = null, $tag = '' ) {
	$attribs = bs4_shortcode_atts(
		array(
			'type' => 'default',
			'pill' => false,
			// 'id' => false,
		), $atts, $tag);
	$attribs = bs4_filter_booleans($attribs, array('pill'));

	$class = 'tag';
	if ($attribs['pill']) $class .= ' tag-pill';

	if (in_array($attribs['type'], array('default', 'primary', 'success', 'info', 'warning', 'danger'))) {
		$class .= ' tag-' . $attribs['type'];
	}

	$output = '<span' . bs4_get_shortcode_class($atts, $class) . '>';
	$output .= do_shortcode($content);
	$output .= '</span>';

	return $output;
}

bs4_add_shortcode( 'tag', 'ts_bootstrap4_tag_sc' );
