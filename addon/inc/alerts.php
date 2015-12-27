<?php
/**
 * Alert Shortcode
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_alert_sc( $atts, $content = null, $tag = '' ) {
	$atts = bs4_shortcode_atts(
		array(
			'type' => 'default',
			'dismiss' => false,
			// 'id' => false,
		), $atts, $tag);
	$atts = bs4_filter_booleans($atts, array('pill'));

	$output = '<div class="alert';

	if (in_array($atts['type'], array('success', 'info', 'warning', 'danger'))) {
		$output .= ' alert-' . $atts['type'];
	}
	if ($atts['dismiss']) $output .= ' alert-dismissable fade in';

	$output .= '" role="alert">';
	if ($atts['dismiss'])
		$output .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
			'<span aria-hidden="true">&times;</span>' .
			'</button>';
	$output .= do_shortcode($content);
	$output .= '</div>';

	return $output;
}

add_shortcode( 'alert', 'ts_bootstrap4_alert_sc' );
