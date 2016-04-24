<?php
/**
 * Typography Shortcodes
 */

include_once 'plugin-lib.php';

function __pos_first_letter($haystack) {
	$ret = false;
	if (!empty($haystack)) {
		$l = strlen($haystack);
		$t = false;
		for ($i=0; $i < $l; $i++) {
			if (!$t && ($haystack[$i] == '<') ) $t = true;
			elseif ($t && ($haystack[$i] == '>')) $t = false;
			elseif (!$t && !ctype_space($haystack[$i])) {
				$ret = $i;
				break;
			}
		}
	}
	return $ret;
}

function ts_bootstrap4_lead_sc( $atts, $content = null, $tag = '' ) {
	$output = '<span' . bs4_get_shortcode_class($atts, 'lead') . '>';
	if (!empty($content)) {
		$s = do_shortcode($content);
		$i = __pos_first_letter( $s );
		if ($i !== false)
			$output .= substr_replace( $s, '<span class="fc">' .
				substr($s, $i, 1) . '</span>', $i, 1);
	}
	$output .= '</span>';

	return $output;
}

add_shortcode( 'lead', 'ts_bootstrap4_lead_sc' );

function ts_bootstrap4_blockquote_sc( $atts, $content = null, $tag = '' ) {
	$attribs = bs4_shortcode_atts( array(
		'source' => '',
		'url' => '',
		'cite' => '',
		'reverse' => false,
		), $atts, $tag);
	$attribs = bs4_filter_booleans($attribs, array('reverse'));

	$class = 'blockquote';
	if ($attribs['reverse']) $class .= ' blockquote-reverse';

	$output = '<blockquote' . bs4_get_shortcode_class($atts, $class) . '>';
	$output .= do_shortcode($content);

	if (!empty($attribs['source']) || !empty($attribs['cite'])) {
		$output .= '<footer class="blockquote-footer">';
		if (!empty($attribs['url']))
			$output .= '<a href="' . $attribs['url'] . '">';
		$output .= $attribs['source'];
		if (!empty($attribs['url']))
			$output .= '</a>';
		if (!empty($attribs['cite'])) {
			if (!empty($attribs['cite'])) $output .= ' ';
			$output .= '<cite>' . $attribs['cite'] . '</cite>';
		}
		$output .= '</footer>';
	}

	$output .= '</blockquote>';

	return $output;
}

add_shortcode( 'blockquote', 'ts_bootstrap4_blockquote_sc' );
