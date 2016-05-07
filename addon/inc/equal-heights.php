<?php

function bs4_equal_heights_sc( $atts, $content = null, $tag = '' ) {
	if (!function_exists('bs4_equal_heights')) return '';
	if ( !bs4_get_option('equalheights') ) return '';

	$attribs = bs4_shortcode_atts(
		array(
			'for' => false,
			'id' => false,
			'class' => false,
		), $atts, $tag);

	if ($attribs['for'] !== false) {
		$what = $attribs['for'];
	} elseif ($attribs['id'] !== false) {
		$what = '#' . $attribs['id'];
	} elseif ($attribs['class'] !== false) {
		$what = explode(' ', trim($attribs['class']));
		$what = '.' . $what[0];  // only the first class
	} else
		return '';

	bs4_equal_heights($what);
	return '';
}
add_shortcode('equalheights', 'bs4_equal_heights_sc');
