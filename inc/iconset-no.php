<?php
/**
 * UNICODE Emoji icon set
 *
 * @see:
 */

function bs4_enqueue_style_i_no($min) {}  // do nothing
function bs4_customize_register_i_no($wp_customize, $section) {} // do nothing
function get_bs4_icon_no($name, $before, $after, $attribs) {
	$o = '';
	$i = strpos($name, ' ');
	if ($i !== false)
		$name = substr($name, 0, $i);
	/**
	 * @see: http://www.fileformat.info/info/unicode/char/search.htm
	 */
	switch ($name) {
		case 'calendar': $o = '&#128197;'; break;
		case 'category': $o = '&#128193;'; break;
		case 'clock': $o = '&#128338;'; break;
		case 'comment':
		case 'commenta':
		case 'commentr':
		case 'comments':
		case 'commentx': $o = '&#128172;'; break;
		case 'email': $o = '&#128231;'; break;
		case 'hellip': $o = '&hellip;'; break;
		case 'info': $o = '&#8505;'; break;
		case 'laquo': $o = '&laquo;'; break;
		case 'link': $o = '&#128279;'; break;
		case 'login': $o = '&#128275;'; break;
		case 'logout': $o = '&#128274;'; break;
		case 'raquo': $o = '&raquo;'; break;
		case 'tag': $o = '&#128278;'; break;
		case 'user':
			if (('' == $before) && ('' == $after)) $o = '&#128118;';
			else $o = '&#128566;';  // or &#128528;
			break;
		case 'warning': $o = '&#9888;'; break;
		default: return '';  // return nothing
	}
	return $before . $o . $after;
}
