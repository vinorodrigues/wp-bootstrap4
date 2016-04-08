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

	$attribs = bs4_shortcode_atts(
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
			'title' => false,
		), $atts, $tag);
	$attribs = bs4_filter_booleans($attribs, array('active', 'disabled', 'outline'));
	$attribs['type'] = strtolower($attribs['type']);
	$attribs['size'] = strtolower($attribs['size']);

	$class = 'btn';
	if (in_array($attribs['type'], array('default', 'primary', 'success', 'info', 'warning', 'danger', 'link'))) {
		$class .= ' btn-';
		if ($attribs['outline'] === true) $class .= 'outline-';
		$class .= $attribs['type'];
	}
	if (in_array($attribs['size'], array('lg', 'sm'))) {
		$class .= ' btn-' . $attribs['size'];
	}
	if ($attribs['block'] === true) $class .= ' btn-block';
	if ($attribs['active'] === true) $class .= ' active';

	$element = ($attribs['link'] !== false) ? 'a' : 'button';
	$output = '<' . $element . bs4_get_shortcode_class($atts, $class);
	if ($attribs['link'] !== false) $output .= ' href="' . $attribs['link'] . '"';
	if ($attribs['action'] !== false) $output .= ' onclick="' . $attribs['action'] . '"';
	if ($attribs['title'] !== false) $output .= ' title="' . $attribs['title'] . '"';

	if ($attribs['link'] !== false) $output .= ' role="button"';
	if ($attribs['disabled'] === true) $output .= ' disabled';

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

	$attribs = bs4_shortcode_atts(
		array(
			'size' => false,
			'vertical' => false,
			'class' => false,
			// 'id' => false,
		), $atts, $tag);
	$attribs = bs4_filter_booleans($attribs, array('vertical'));

	$class = 'btn-group';
	$attribs['size'] = strtolower($attribs['size']);
	if (in_array($attribs['size'], array('lg', 'sm'))) {
		$class .= ' btn-group-' . $attribs['size'];
	}
	if ($attribs['vertical'] === true) $class .= ' btn-group-vertical';

	if (key_exists('button_grp', $bs4_singletons)) {
		$output = '<div' . bs4_get_shortcode_class($atts, $class);
		$output .= ' role="group">';
		$output .= $bs4_singletons['button_grp'];
		$output .= '</div>';
		$bs4_singletons['button_grp'] = '';
	}

	return $output;
}

add_shortcode( 'button', 'ts_bootstrap4_button_sc' );
add_shortcode( 'buttons', 'ts_bootstrap4_button_grp_sc' );
add_shortcode( 'button_group', 'ts_bootstrap4_button_grp_sc' );

/* function ts_bootstrap4_buttons_shortcode_fix( $content ) {
	$shortcodes = array(
		'button',
		'buttons',
		'button_group',
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
} /* */

/* add_filter( 'the_content', 'ts_bootstrap4_buttons_shortcode_fix' ); /* */
