<?php
/**
 * Card Shortcodes
 */

include_once 'plugin-lib.php';
include_once 'buttons.php';
include_once 'lists.php';

function ts_bootstrap4_card_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (isset($bs4_singletons['in_card']) && $bs4_singletons['in_card'])
		return bs4_shortcode_error($tag, 'nested card');  // jump out, no nesting

	if (!isset($bs4_singletons['card_count']))
		$bs4_singletons['card_count'] = 0;
	$bs4_singletons['card_count']++;

	$bs4_singletons['in_card'] = true;
	$leftover = do_shortcode( $content );  // get inner content
	$bs4_singletons['in_card'] = false;

	$count = isset($bs4_singletons['card_items']) ?
		count($bs4_singletons['card_items']) :
		0;
	if ($count == 0) return bs4_shortcode_error($tag, 'no content');  // no content

	$attribs = bs4_shortcode_atts(array(
		'block' => false,
		'inverse' => false,
		'type' => false,
		'outline' => false,
		), $atts, $tag);
	$attribs = bs4_filter_booleans($attribs, array('block', 'inverse'));

	var_dump_pre($attribs, '$attribs');

	$class = 'card';
	if ($attribs['block'] != false) $class .= ' card-block';
	if ($attribs['inverse'] != false) $class .= ' card-inverse';
	if ($attribs['type'] != false) {
		$class .= ' card-';
		if ($attribs['outline'] != false) $class .= 'outline-';
		$class .= $attribs['type'];
	}
	$class .= ' card-' . $bs4_singletons['card_count'];

	$output = '<div' . bs4_get_shortcode_class($atts, $class) . '>';
	foreach ($bs4_singletons['card_items'] as $value) {
		$output .= $value;
	}
	$output .= '</div>';

	unset($bs4_singletons['card_items']);

	return $output;
}

function ts_bootstrap4_card_block_sc( $atts, $content = null, $tag = 'card-block' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['in_card']) || !$bs4_singletons['in_card'])
		return $bs4_shortcode_error($tag, 'not in [card]');  // jump out, not in card

	if (isset($bs4_singletons['in_card_block']) && $bs4_singletons['in_card_block']) {
		$output = bs4_shortcode_error($tag, 'nested card-block');  // jump out, no nesting
	} else {
		$bs4_singletons['in_card_block'] = true;
		do_shortcode( $content );  // get inner content
		$bs4_singletons['in_card_block'] = false;

		$count = isset($bs4_singletons['card_block_items']) ?
			count($bs4_singletons['card_block_items']) :
			0;
		if ($count != 0) {

			// $attribs = bs4_shortcode_atts(array(), $atts, $tag);

			$output = '<div' . bs4_get_shortcode_class($atts, $tag) . '>';
			foreach ($bs4_singletons['card_block_items'] as $value) {
				$output .= $value;
			}
			$output .= '</div>';
		} else
			$output = bs4_shortcode_error($tag, 'no content');  // no content

		unset($bs4_singletons['card_block_items']);
	}

	if (!isset($bs4_singletons['card_items']))
		$bs4_singletons['card_items'] = array();
	$bs4_singletons['card_items'][] = $output;

	return '';
}

function __ts_bootstrap4_card_title_sc( $atts, $content = null, $tag = 'card-title', $htmltag = 'h4' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['in_card']) || !$bs4_singletons['in_card'])
		return bs4_shortcode_error($tag, 'not in [card]');  // jump out, not in card

	$store = (isset($bs4_singletons['in_card_block']) && $bs4_singletons['in_card_block']) ?
		'card_block_items' : 'card_items';

	$class = $tag;
	if ($htmltag == 'h4') {
		$attribs = bs4_shortcode_atts(array(
			'header' => false,
			), $atts, $tag);
		$attribs = bs4_filter_booleans($attribs, array('header'));
		if ($attribs['header'] != false) {
			$class .= ' card-header';
			$htmltag = 'h3';
		}
	}
	$output = '<' . $htmltag . bs4_get_shortcode_class($atts, $class) . '>' .
		$content . '</' . $htmltag . '>';

	if (!isset($bs4_singletons[$store]))
		$bs4_singletons[$store] = array();
	$bs4_singletons[$store][] = $output;

	return '';
}

function ts_bootstrap4_card_title_sc( $atts, $content = null, $tag = '') {
	return __ts_bootstrap4_card_title_sc($atts, $content, $tag, 'h4');
}

function ts_bootstrap4_card_subtitle_sc( $atts, $content = null, $tag = '') {
	return __ts_bootstrap4_card_title_sc($atts, $content, $tag, 'h6');
}

function ts_bootstrap4_card_text_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['in_card']) || !$bs4_singletons['in_card'])
		return bs4_shortcode_error($tag, 'not in [card]');  // jump out, not in card

	$store = (isset($bs4_singletons['in_card_block']) && $bs4_singletons['in_card_block']) ?
		'card_block_items' : 'card_items';

	// $attribs = bs4_shortcode_atts(array(), $atts, $tag);
	$output = '<p' . bs4_get_shortcode_class($atts, 'card-text') . '>' .
		do_shortcode( $content ) . '</p>';

	if (!isset($bs4_singletons[$store]))
		$bs4_singletons[$store] = array();
	$bs4_singletons[$store][] = $output;

	return '';
}

function __ts_bootstrap4_card_img_sc( $atts, $content = null, $tag = '', $img_class = 'card-img' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['in_card']) || !$bs4_singletons['in_card'])
		return bs4_shortcode_error($tag, 'not in [card]');  // jump out, not in card

	$store = (isset($bs4_singletons['in_card_block']) && $bs4_singletons['in_card_block']) ?
		'card_block_items' : 'card_items';

	$attribs = bs4_shortcode_atts(array(
		'src' => false,
		'alt' => false,
		'title' => false,
		'width' => false,
		'height' => false,
		), $atts, $tag);
	$output = '<img' . bs4_get_shortcode_class($atts, $img_class);
	if ($attribs['src'] !== false) $output .= ' src="' . $attribs['src'] . '"';
	if ($attribs['alt'] !== false) $output .= ' alt="' . $attribs['alt'] . '"';
	if ($attribs['title'] !== false) $output .= ' title="' . $attribs['title'] . '"';
	if ($attribs['width'] !== false) $output .= ' width="' . $attribs['width'] . '"';
	if ($attribs['height'] !== false) $output .= ' height="' . $attribs['height'] . '"';
	$output .= '>';

	if (!isset($bs4_singletons[$store]))
		$bs4_singletons[$store] = array();
	$bs4_singletons[$store][] = $output;

	return '';
}

function ts_bootstrap4_card_image_sc( $atts, $content = null, $tag = '' ) {
	if (empty($content))
		return __ts_bootstrap4_card_img_sc( $atts, $content, $tag, '' );

	$output = __ts_bootstrap4_card_img_sc( $atts, $content, $tag, 'card-img' );
	$output .= ts_bootstrap4_card_block_sc( $atts, $content, 'card-img-overlay' );

	return $output;
}

function ts_bootstrap4_card_top_sc( $atts, $content = null, $tag = '' ) {
	return __ts_bootstrap4_card_img_sc( $atts, $content, $tag, 'card-img-top' );
}

function ts_bootstrap4_card_bottom_sc( $atts, $content = null, $tag = '' ) {
	return __ts_bootstrap4_card_img_sc( $atts, $content, $tag, 'card-img-bottom' );
}

function ts_bootstrap4_card_button_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['in_card']) || !$bs4_singletons['in_card'])
		return bs4_shortcode_error($tag, 'not in [card]');  // jump out, not in card

	$store = (isset($bs4_singletons['in_card_block']) && $bs4_singletons['in_card_block']) ?
		'card_block_items' : 'card_items';

	$output = ts_bootstrap4_button_sc( $atts, $content, $tag );  // call the button shortcode

	if (!isset($bs4_singletons[$store]))
		$bs4_singletons[$store] = array();
	$bs4_singletons[$store][] = $output;

	return '';
}

function ts_bootstrap4_card_link_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['in_card']) || !$bs4_singletons['in_card'])
		return bs4_shortcode_error($tag, 'not in [card]');  // jump out, not in card

	$store = (isset($bs4_singletons['in_card_block']) && $bs4_singletons['in_card_block']) ?
		'card_block_items' : 'card_items';

	$attribs = bs4_shortcode_atts(array(
		'link' => false,
		'url' => false,
		'href' => false,
		), $atts, $tag);

	if ($attribs['link'] === false && $attribs['href'] !== false)
		$attribs['link'] = $attribs['href'];
	if ($attribs['link'] === false && $attribs['url'] !== false)
		$attribs['link'] = $attribs['url'];

	$output = '<a' . bs4_get_shortcode_class($atts, 'card-link');
	if ($attribs['link'] != false) $output .= ' href="' . $attribs['link'] . '"';
	$output .= '>' . $content . '</a>';

	if (!isset($bs4_singletons[$store]))
		$bs4_singletons[$store] = array();
	$bs4_singletons[$store][] = $output;

	return '';
}

function ts_bootstrap4_card_list_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['in_card']) || !$bs4_singletons['in_card'])
		return bs4_shortcode_error($tag, 'not in [card]');  // jump out, not in card

	if (isset($bs4_singletons['in_card_block']) && $bs4_singletons['in_card_block']) {
		$output = bs4_shortcode_error($tag, "can't be in [card-block]");
	} else {
		$class = 'list-group-flush';
		$attribs = bs4_shortcode_atts(array(), $atts, $tag);
		if (array_key_exists('class', $attribs)) {
			$atts['class'] .= ' ' . $class;
		} else {
			$atts['class'] = $class;
		}
		$output = ts_bootstrap4_list_sc( $atts, $content, $tag );
	}

	if (!isset($bs4_singletons['card_items']))
		$bs4_singletons['card_items'] = array();
	$bs4_singletons['card_items'][] = $output;

	return '';
}

function ts_bootstrap4_card_list_item_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['in_card']) || !$bs4_singletons['in_card'])
		return bs4_shortcode_error($tag, 'not in [card]');  // jump out, not in card

	return ts_bootstrap4_list_item_sc( $atts, $content, $tag );
}

bs4_add_shortcode( 'card',           'ts_bootstrap4_card_sc' );

bs4_add_shortcode( 'card-block',     'ts_bootstrap4_card_block_sc' );
bs4_add_shortcode( 'card-header',    'ts_bootstrap4_card_block_sc' );
bs4_add_shortcode( 'card-footer',    'ts_bootstrap4_card_block_sc' );

bs4_add_shortcode( 'card-title',     'ts_bootstrap4_card_title_sc' );
bs4_add_shortcode( 'card-subtitle',  'ts_bootstrap4_card_subtitle_sc' );
bs4_add_shortcode( 'card-text',      'ts_bootstrap4_card_text_sc' );

bs4_add_shortcode( 'card-image',     'ts_bootstrap4_card_image_sc' );
bs4_add_shortcode( 'card-img',       'ts_bootstrap4_card_image_sc' );  // alias
bs4_add_shortcode( 'card-top',       'ts_bootstrap4_card_top_sc' );
bs4_add_shortcode( 'card-bottom',    'ts_bootstrap4_card_bottom_sc' );
bs4_add_shortcode( 'card-btm',       'ts_bootstrap4_card_bottom_sc' );  // alias

bs4_add_shortcode( 'card-button',    'ts_bootstrap4_card_button_sc' );
bs4_add_shortcode( 'card-btn',       'ts_bootstrap4_card_button_sc' );  // alias
bs4_add_shortcode( 'card-link',      'ts_bootstrap4_card_link_sc' );

bs4_add_shortcode( 'card-list',      'ts_bootstrap4_card_list_sc' );
bs4_add_shortcode( 'card-list-item', 'ts_bootstrap4_card_list_item_sc' );
bs4_add_shortcode( 'card-item',      'ts_bootstrap4_card_list_item_sc' );  // alias

// eof
