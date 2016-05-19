<?php

/**
 * Grid Shortcodes
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_row_sc( $atts, $content = null, $tag = '' ) {
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

function ts_bootstrap4_column_sc( $atts, $content = null, $tag = '' ) {
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

		if (key_exists('print', $atts) && !key_exists('pr', $atts)) $atts['pr'] = $atts['print'];
		if (key_exists('size', $atts) && !key_exists('pr', $atts)) $atts['pr'] = $atts['size'];
		if (key_exists('pull', $atts) && !key_exists('pr-pull', $atts)) $atts['pr-pull'] = $atts['pull'];
		if (key_exists('push', $atts) && !key_exists('pr-push', $atts)) $atts['pr-push'] = $atts['push'];
		if (key_exists('offset', $atts) && !key_exists('pr-offset', $atts)) $atts['pr-offset'] = $atts['offset'];


		// legacy - from WP-Bootstrap2, backward compatability
		if (!key_exists('md', $atts)) {
			if (key_exists('span', $atts)) {
				$atts['md'] = $atts['span'];
				if (!key_exists('pr', $atts)) $atts['pr'] = $atts['span'];
			} else
				foreach($atts as $name => $value )
					if (is_numeric($name))
						if (strncasecmp($value, 'span', 4) === 0) {
							$atts['md'] = substr($value, 4);
							if (!key_exists('pr', $atts)) $atts['pr'] = substr($value, 4);
						}
		}
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
			'pr' => false,
			'pr-pull' => false,
			'pr-push' => false,
			'pr-offset' => false,
		), $atts, $tag);

	$class = '';
	foreach ($attribs as $key => $value) {
		if ($value !== false) {
			if (('pr' == $key) && (intval($value) == 0)) {
				$class .= 'hidden-print ';
			} else {
				$class .= 'col-' . $key . '-' . intval($value) . ' ';
			}
		}
	}

	$output = '<div' . bs4_get_shortcode_class($atts, rtrim($class)) . '>';
	$output .= do_shortcode( $content );
	$output .= '</div>';

	$bs4_singletons['columns'][] = $output;
}

function __ts_bootstrap4_col_part($value, $atts, $content) {
	global $bs4_singletons;

	if (!isset($bs4_singletons) || !key_exists('in_row', $bs4_singletons) || ($bs4_singletons['in_row'] !== true))
		return $content;

	if (!key_exists('columns', $bs4_singletons) || !is_array($bs4_singletons['columns']))
		$bs4_singletons['columns'] = array();

	$output = '<div' . bs4_get_shortcode_class($atts, 'col-md-'.$value.' col-pr-'.$value) . '>';
	$output .= do_shortcode( $content );
	$output .= '</div>';

	$bs4_singletons['columns'][] = $output;
}

function ts_bootstrap4_one_half_sc( $atts, $content = null, $tag = '' ) {
	__ts_bootstrap4_col_part(6, $atts, $content);
}

function ts_bootstrap4_one_third_sc( $atts, $content = null, $tag = '' ) {
	__ts_bootstrap4_col_part(4, $atts, $content);
}

function ts_bootstrap4_two_thirds_sc( $atts, $content = null, $tag = '' ) {
	__ts_bootstrap4_col_part(8, $atts, $content);
}

function ts_bootstrap4_one_fourth_sc( $atts, $content = null, $tag = '' ) {
	__ts_bootstrap4_col_part(3, $atts, $content);
}

function ts_bootstrap4_three_fourths_sc( $atts, $content = null, $tag = '' ) {
	__ts_bootstrap4_col_part(9, $atts, $content);
}

bs4_add_shortcode( 'row', 'ts_bootstrap4_row_sc' );
bs4_add_shortcode( 'column', 'ts_bootstrap4_column_sc' );
bs4_add_shortcode( 'col', 'ts_bootstrap4_column_sc' );
bs4_add_shortcode( 'one_half', 'ts_bootstrap4_one_half_sc' );
bs4_add_shortcode( 'half', 'ts_bootstrap4_one_half_sc' );  // lazy
bs4_add_shortcode( 'one_third', 'ts_bootstrap4_one_third_sc' );
bs4_add_shortcode( 'third', 'ts_bootstrap4_one_third_sc' );  // lazy
bs4_add_shortcode( 'two_thirds', 'ts_bootstrap4_two_thirds_sc' );
bs4_add_shortcode( 'one_fourth', 'ts_bootstrap4_one_fourth_sc' );
bs4_add_shortcode( 'fourth', 'ts_bootstrap4_one_fourth_sc' );  // lazy
bs4_add_shortcode( 'two_fourths', 'ts_bootstrap4_one_half_sc' );  // unreduced
bs4_add_shortcode( 'three_fourths', 'ts_bootstrap4_three_fourths_sc' );

