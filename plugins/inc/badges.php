<?php
/*
 * Badge Shortcode (was 'Label')
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_badge_sc( $atts, $content = null, $tag = '' ) {
	$attribs = bs4_shortcode_atts(
		array(
			'type' => 'default',
			'pill' => false,
			// 'id' => false,
		), $atts, $tag);
	$attribs = bs4_filter_booleans($attribs, array('pill'));

	$class = 'badge';
	if ($attribs['pill']) $class .= ' badge-pill';

	if (in_array($attribs['type'], array('default', 'primary', 'success', 'info', 'warning', 'danger'))) {
		$class .= ' badge-' . $attribs['type'];
	}

	$output = '<span' . bs4_get_shortcode_class($atts, $class) . '>';
	$output .= do_shortcode($content);
	$output .= '</span>';

	return $output;
}

bs4_add_shortcode( 'badge', 'ts_bootstrap4_badge_sc' );
