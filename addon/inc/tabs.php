<?php
/**
 * Tabs Shortcode
 */

include_once 'plugin-lib.php';

// TODO : Pills

function ts_bootstrap4_tabs_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (isset($bs4_singletons['in_tabs']) && $bs4_singletons['in_tabs'])
		return '';  // jump out, no nested tabs

	if (!isset($bs4_singletons['tabs_count']))
		$bs4_singletons['tabs_count'] = 0;
	$bs4_singletons['tabs_count']++;

	$bs4_singletons['in_tabs'] = true;
	do_shortcode( $content );  // get inner content
	$bs4_singletons['in_tabs'] = false;

	$count = isset($bs4_singletons['tabs_items']) ?
		count($bs4_singletons['tabs_items']) :
		0;
	if ($count == 0) return '';  // no content

	$active_item = isset($bs4_singletons['tabs_active_item']) ?
		$bs4_singletons['tabs_active_item'] :
		0;
	unset( $bs4_singletons['tabs_active_item'] );  // clean up!

	$attribs = bs4_shortcode_atts( array(
		'id' => false,
		'fade' => false,
		), $atts, $tag);

	$id = $attribs['id'] ? $attribs['id'] : 'tab-'.$bs4_singletons['tabs_count'];

	$output = '<ul class="nav nav-tabs" role="tablist" id="'.$id.'">';
	$i = 0;
	foreach ($bs4_singletons['tabs_titles'] as $title) {
		$iid = $bs4_singletons['tabs_ids'][$i];
		if (!$iid) {
			$iid = $id.'-'.($i+1);
			$bs4_singletons['tabs_ids'][$i] = $iid;
		}
		if (!$title) {
			$title = $iid;
			$bs4_singletons['tabs_titles'][$i] = $title;
		}

		$output .= '<li class="nav-item">';
		$output .= '<a class="nav-link';
		if ($active_item == $i) $output .= ' active';
		$output .= '" data-toggle="tab" href="#'.$iid.'" role="tab">';
		$output .= $title;
		$output .= '</a></li>';

		$i++;
	}
	$output .= '</ul>';

	$output .= '<div class="tab-content">';
	$i = 0;
	foreach ($bs4_singletons['tabs_items'] as $content) {
		$iid = $bs4_singletons['tabs_ids'][$i];

		$output .= '<div class="tab-pane';
		if ($attribs['fade']) {
			$output .= ' fade';
			if ($active_item == $i) $output .= ' in';
		}
		if ($active_item == $i) $output .= ' active';
		$output .= '" id="'.$iid.'" role="tabpanel"';
		$output .= ' data-title="' . $bs4_singletons['tabs_titles'][$i] . '">';
		$output .= $content;
		$output .= '</div>';

		$i++;
	}
	$output .= '</div>';

	return $output;
}

function ts_bootstrap4_tab_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons) || !isset($bs4_singletons['in_tabs']) || !$bs4_singletons['in_tabs'])
		return "[{$tag}]" . do_shortcode( $content ) . "[/{$tag}]";

	$attribs = bs4_shortcode_atts( array(
		'id' => false,
		'active' => false,
		'title' => '',
		), $atts, $tag );
	$attribs = bs4_filter_booleans($attribs, array('active'));

	if (!isset($bs4_singletons['tabs_titles']))
		$bs4_singletons['tabs_titles'] = array();
	if (!isset($bs4_singletons['tabs_items']))
		$bs4_singletons['tabs_items'] = array();
	if (!isset($bs4_singletons['tabs_ids']))
		$bs4_singletons['tabs_ids'] = array();

	// get inner content
	if ($attribs['active']) $bs4_singletons['tabs_active_item'] =
		count($bs4_singletons['tabs_items']);
	$bs4_singletons['tabs_titles'][] = $attribs['title'];
	$bs4_singletons['tabs_items'][] = do_shortcode( $content );
	$bs4_singletons['tabs_ids'][] = $attribs['id'] ? $attribs['id'] : null;

	return '';  // no output
}

bs4_add_shortcode( 'tabs', 'ts_bootstrap4_tabs_sc' );
bs4_add_shortcode( 'tab', 'ts_bootstrap4_tab_sc' );
