<?php
/**
 * Buttons Shortcodes
 */

include_once 'plugin-lib.php';

/**
 * Parameter	Description			Values							Default
 * type		The type of the button		default, primary, success, info, warning, danger, link	default
 * size		The size of the button		xs, sm, lg						none
 * link		Url to link to	optional	valid link						none
 * action	Create OnClick event code	valid JS						none
 * class	Any extra classes to add	free text						none
 * active 	Apply the "active" style	true, false						true if present
 * disabled	Button will be disabled		true, false						true if present
 * outline	Button not filled with color	true, false						true if present
 * block	Block-level button		true, false						false
 */

function ts_bootstrap4_button_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	$atts = bs4_shortcode_atts(
		array(
			'type' => 'default',
			'size' => false,
			'link' => false,
			'action' => false,
			'class' => false,
			'active' => false,
			'disabled' => false,
			'outline' => false,
			'block' => false,
			// 'id' => false,
			// 'title' => false,
		), $atts, $tag);
	$atts = bs4_filter_booleans($atts, array('active', 'disabled', 'outline'));
	$atts['type'] = strtolower($atts['type']);
	$atts['size'] = strtolower($atts['size']);

	$class = 'btn';

	if (in_array($atts['type'], array('default', 'primary', 'success', 'info', 'warning', 'danger', 'link'))) {
		$class .= ' btn-' . $atts['type'];
		if ($atts['outline'] === true) $class .= '-outline';
	}

	if (in_array($atts['size'], array('lg', 'sm'))) {
		$class .= ' btn-' . $atts['size'];
	}

	if ($atts['block'] === true) $class .= ' btn-block';
	if ($atts['active'] === true) $class .= ' active';
	if ($atts['class'] !== false) $class .= ' ' . $atts['class'];

	$output = '';
	$element = ($atts['link'] !== false) ? 'a' : 'button';
	$output .= '<' . $element;
	if ($atts['link'] !== false) $output .= ' href="' . $atts['link'] . '"';
	if ($atts['action'] !== false) $output .= ' onclick="' . $atts['action'] . '"';
	// if ($atts['id'] !== false) $output .= ' id="' . $atts['id'] . '"';
	// if ($atts['title'] !== false) $output .= ' title="' . $atts['title'] . '"';

	$output .= ' class="' . $class . '"';
	if ($atts['link'] !== false) $output .= ' role="button"';
	if ($atts['disabled'] === true) $output .= ' disabled';

	$output .= '>' . do_shortcode($content) . '</' . $element . '>';

	if (isset($bs4_singletons) && key_exists('in_button_grp', $bs4_singletons) && ($bs4_singletons['in_button_grp'] === true)) {
		if (!key_exists('button_grp', $bs4_singletons)) $bs4_singletons['button_grp'] = '';
		$bs4_singletons['button_grp'] .= $output;
		return '';
	} else {
		return $output;
	}
}

function ts_bootstrap4_button_grp_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();
	$bs4_singletons['in_button_grp'] = true;
	do_shortcode($content);
	$bs4_singletons['in_button_grp'] = false;

	$atts = bs4_shortcode_atts(
		array(
			'size' => false,
			'vertical' => false,
			'class' => false,
			// 'id' => false,
		), $atts, $tag);
	$atts = bs4_filter_booleans($atts, array('vertical'));

	$class = 'btn-group';
	$atts['size'] = strtolower($atts['size']);
	if (in_array($atts['size'], array('lg', 'sm'))) {
		$class .= ' btn-group-' . $atts['size'];
	}
	if ($atts['vertical'] === true) $class .= ' btn-group-vertical';
	if ($atts['class'] !== false) $class .= ' ' . $atts['class'];

	if (key_exists('button_grp', $bs4_singletons)) {
		$output = '<div class="' . $class . '"';
		// if ($atts['id'] !== false) $output .= ' id="' . $atts['id'] . '"';
		$output .= ' role="group">';
		$output .= $bs4_singletons['button_grp'];
		$output .= '</div>';
		$bs4_singletons['button_grp'] = '';
	}

	return $output;
}

add_shortcode( 'button', 'ts_bootstrap4_button_sc' );
add_shortcode( 'buttons', 'ts_bootstrap4_button_grp_sc' );
add_shortcode( 'button-group', 'ts_bootstrap4_button_grp_sc' );
