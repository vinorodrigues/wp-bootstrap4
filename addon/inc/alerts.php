<?php
/**
 * Alert Shortcode
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_alert_sc( $atts, $content = null, $tag = '' ) {
	$attribs = bs4_shortcode_atts(
		array(
			'type' => 'default',
			'dismiss' => false,
		), $atts, $tag);
	$attribs = bs4_filter_booleans($attribs, array('pill'));

	$class = 'alert';
	if (in_array($attribs['type'], array('success', 'info', 'warning', 'danger'))) {
		$class .= ' alert-' . $attribs['type'];
	}
	if ($attribs['dismiss']) $class .= ' alert-dismissable fade in';

	$output = '<div' . bs4_get_shortcode_class($atts, $class) . ' role="alert">';
	if ($attribs['dismiss'])
		$output .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
			'<span aria-hidden="true">&times;</span>' .
			'</button>';
	$output .= do_shortcode($content);
	$output .= '</div>';

	return $output;
}

bs4_add_shortcode( 'alert', 'ts_bootstrap4_alert_sc' );
