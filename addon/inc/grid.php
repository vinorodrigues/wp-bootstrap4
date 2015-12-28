<?php

/**
 * Grid Shortcodes
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_row( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	$bs4_singletons['in_row'] = true;
	do_shortcode( $content );
	$bs4_singletons['in_row'] = false;

	$output = '';
	if (isset($bs4_singletons['columns']) && is_array($bs4_singletons['columns']) && (!empty($bs4_singletons['columns'])))
		foreach ($bs4_singletons['columns'] as $col)
			$output .= $col;

	return '<div' . bs4_get_shortcode_class($atts, 'row') . '>' . $output . '</div>';
}

function ts_bootstrap4_column( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons) || !key_exists('in_row', $bs4_singletons) || ($bs4_singletons['in_row'] !== true))
		return $content;

	if (!key_exists('columns', $bs4_singletons) || !is_array($bs4_singletons['columns']))
		$bs4_singletons['columns'] = array();

	if (is_array($atts)) {
		if (key_exists('size', $atts) && !key_exists('md', $atts)) $atts['md'] = $atts['size'];
		if (key_exists('pull', $atts) && !key_exists('md-pull', $atts)) $atts['md-pull'] = $atts['pull'];
		if (key_exists('push', $atts) && !key_exists('md-push', $atts)) $atts['md-push'] = $atts['push'];
		if (key_exists('offset', $atts) && !key_exists('md-offset', $atts)) $atts['md-offset'] = $atts['offset'];
	}

	$attribs = bs4_shortcode_atts(
		array(
			'xs' => false,
			'sm' => false,
			'md' => false,
			'lg' => false,
			'xl' => false,
			'xs-pull' => false,
			'sm-pull' => false,
			'md-pull' => false,
			'lg-pull' => false,
			'xl-pull' => false,
			'xs-push' => false,
			'sm-push' => false,
			'md-push' => false,
			'lg-push' => false,
			'xl-push' => false,
			'xs-offset' => false,
			'sm-offset' => false,
			'md-offset' => false,
			'lg-offset' => false,
			'xl-offset' => false,
		), $atts, $tag);

	$class = '';
	foreach ($attribs as $key => $value) {
		if ($value !== false) $class .= 'col-' . $key . '-' . intval($value) . ' ';
	}

	$output = '<div' . bs4_get_shortcode_class($atts, rtrim($class)) . '>';
	$output .= $content;
	$output .= '</div>';

	$bs4_singletons['columns'][] = $output;
}

function __ts_bootstrap4_col_part($value, $atts, $content) {
	global $bs4_singletons;

	if (!isset($bs4_singletons) || !key_exists('in_row', $bs4_singletons) || ($bs4_singletons['in_row'] !== true))
		return $content;

	if (!key_exists('columns', $bs4_singletons) || !is_array($bs4_singletons['columns']))
		$bs4_singletons['columns'] = array();

	$output = '<div' . bs4_get_shortcode_class($atts, 'col-md-'.$value) . '>';
	$output .= do_shortcode( $content );
	$output .= '</div>';

	$bs4_singletons['columns'][] = $output;
}

function ts_bootstrap4_one_half( $atts, $content = null, $tag = '' ) {
	__ts_bootstrap4_col_part(6, $atts, $content);
}

function ts_bootstrap4_one_third( $atts, $content = null, $tag = '' ) {
	__ts_bootstrap4_col_part(4, $atts, $content);
}

function ts_bootstrap4_two_thirds( $atts, $content = null, $tag = '' ) {
	__ts_bootstrap4_col_part(8, $atts, $content);
}

function ts_bootstrap4_one_fourth( $atts, $content = null, $tag = '' ) {
	__ts_bootstrap4_col_part(3, $atts, $content);
}

function ts_bootstrap4_three_fourths( $atts, $content = null, $tag = '' ) {
	__ts_bootstrap4_col_part(9, $atts, $content);
}

add_shortcode( 'row', 'ts_bootstrap4_row' );
add_shortcode( 'column', 'ts_bootstrap4_column' );
add_shortcode( 'col', 'ts_bootstrap4_column' );
add_shortcode( 'one_half', 'ts_bootstrap4_one_half' );
add_shortcode( 'half', 'ts_bootstrap4_one_half' );  // lazy
add_shortcode( 'one_third', 'ts_bootstrap4_one_third' );
add_shortcode( 'third', 'ts_bootstrap4_one_third' );  // lazy
add_shortcode( 'two_thirds', 'ts_bootstrap4_two_thirds' );
add_shortcode( 'one_fourth', 'ts_bootstrap4_one_fourth' );
add_shortcode( 'fourth', 'ts_bootstrap4_one_fourth' );  // lazy
add_shortcode( 'two_fourths', 'ts_bootstrap4_one_half' );  // unreduced
add_shortcode( 'three_fourths', 'ts_bootstrap4_three_fourths' );

function ts_bootstrap4_grid_shortcode_fix( $content ) {
	$shortcodes = array(
		'row',
		);

	foreach ( $shortcodes as $shortcode ) {
        	$array = array (
        		'<p>[' . $shortcode    => '[' .$shortcode,
			'<br>[' . $shortcode   => '[' .$shortcode,
			'<br />[' . $shortcode => '[' .$shortcode,
        		// '<p>[/' . $shortcode   => '[/' .$shortcode,
        		$shortcode . ']</p>'   => $shortcode . ']',
			$shortcode . ']<br>'   => $shortcode . ']',
			$shortcode . ']<br />' => $shortcode . ']',
        		);

        	$content = strtr( $content, $array );
	}

	return $content;
}

add_filter( 'the_content', 'ts_bootstrap4_grid_shortcode_fix' );
