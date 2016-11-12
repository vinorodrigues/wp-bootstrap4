<?php
/*
 * List-Group shortcode
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_list_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (isset($bs4_singletons['in_list']) && $bs4_singletons['in_list'])
		return bs4_shortcode_error($tag, 'nested list');  // jump out, no nesting

	if (!isset($bs4_singletons['list_count']))
		$bs4_singletons['list_count'] = 0;
	$bs4_singletons['list_count']++;

	$bs4_singletons['in_list'] = true;
	$leftover = do_shortcode( $content );  // get inner content
	$bs4_singletons['in_list'] = false;

	$count = isset($bs4_singletons['list_items']) ?
		count($bs4_singletons['list_items']) :
		0;
	if ($count == 0) return bs4_shortcode_error($tag, 'no content');  // no content

	$attribs = bs4_shortcode_atts(array(
		'id'    => false,
		), $atts, $tag);
	// $attribs = bs4_filter_booleans($attribs, array('block'));

	$class = 'list-group';
	// if ($attribs['block']) $class .= ' card-block';
	$class .= ' list-group-' . $bs4_singletons['list_count'];

	$output = '<ul' . bs4_get_shortcode_class($atts, $class) . '>';
	foreach ($bs4_singletons['list_items'] as $value) {
		$output .= $value;
	}
	$output .= '</ul>';

	unset($bs4_singletons['list_items']);

	return $output;
}

function ts_bootstrap4_list_item_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['in_list']) || !$bs4_singletons['in_list'])
		return bs4_shortcode_error($tag, 'not in [list]');  // jump out, not in card

	$attribs = bs4_shortcode_atts(array(
		'disabled' => false,
		'active' => false,
		'action' => false,
		), $atts, $tag);
	$attribs = bs4_filter_booleans($attribs, array('disabled'));
	$class = 'list-group-item';
	if ($attribs['disabled']) $class .= ' disabled';
	if ($attribs['active']) $class .= ' active';
	if ($attribs['action']) $class .= ' list-group-item-action';
	$output = '<li' . bs4_get_shortcode_class($atts, $class) . '>' .
		$content . '</li>';

	if (!isset($bs4_singletons['list_items']))
		$bs4_singletons['list_items'] = array();
	$bs4_singletons['list_items'][] = $output;

	return '';
}

bs4_add_shortcode( 'list',      'ts_bootstrap4_list_sc' );
bs4_add_shortcode( 'list-item', 'ts_bootstrap4_list_item_sc' );

// eof
