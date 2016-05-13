<?php
/**
 * FONT AWESOME icon_set
 * @see: http://fontawesome.io/icons/
 */


if (!defined( 'FONTAWESOME_VERSION' ))
	define( 'FONTAWESOME_VERSION', '4.6.3' );


function bs4_enqueue_style_i_fa($min = '') {
	$url = trim( bs4_get_option('fontawesome_css') );
	if (empty($url)) {
		$url = get_stylesheet_directory_uri() . '/css/font-awesome' . $min . '.css';
		$ver = FONTAWESOME_VERSION;
	} else {
		$ver = NULL;
	}
	wp_enqueue_style( 'font-awesome', $url, array(), $ver );
}

function bs4_options_register_i_fa($section) {
	add_settings_field(
		'fontawesome_css',
		'FontAwesome Stylesheet',
		'bs4_opts_url_callback',
		$section,
		'bs4_dependancies',
		array(
			$section,
			'fontawesome_css',
			'URL to <code>fontawesome.css</code> file. Leave blank for local copy (version ' . FONTAWESOME_VERSION . ').',
			)
		);
}

function bs4_sanitize_i_options_fa($output) {

	if (is_null($output)) return null;

	if (isset($output['fontawesome_css'])) {
		$url = esc_url_raw( strip_tags( stripslashes( $output['fontawesome_css'] ) ) );
		if (empty($url) || (!filter_var($url, FILTER_VALIDATE_URL) === false))
			$output['fontawesome_css'] = $url;
		else
			add_settings_error('fontawesome_css', 'url', '<label for="fontawesome_css">Invalid URL</label>');
	}
	return $output;
}

function get_bs4_icon_fa($name, $before = '', $after = '', $attribs = false) {
	$d = array(
		// '2x'       => '2x',
		// '3x'       => '3x',
		// '4x'       => '4x',
		// '5x'       => '5x',
		'calendar' => 'calendar',
		'cancel'   => 'close',
		'category' => 'bookmark',
		'clock'    => 'clock-o',
		'comment'  => 'comment',
		'commenta' => 'commenting',
		'commentr' => 'commenting-o',
		'comments' => 'comments',
		'commentx' => 'commenting-o',
		'edit'     => 'edit',
		'email'    => 'envelope',
		'fw'       => 'fw',  // fixed width
		'hellip'   => 'ellipsis-h',
		'info'     => 'info-circle',
		'laquo'    => 'chevron-left',
		'lg'       => 'lg',  // large
		'link'     => 'link',
		'login'    => 'lock',
		'logout'   => 'unlock',
		'post'     => 'arrow-circle-right',
		'raquo'    => 'chevron-right',
		'reply'    => 'reply',
		'search'   => 'search',
		// 'spin'     => 'spin',
		'tag'      => 'tag',
		'trash'    => 'trash',
		'user'     => 'user',
		'wait'     => 'hourglass-half',
		'warning'  => 'exclamation-triangle',
		);

	$ns = explode(' ', $name);
	$o = '<i class="fa';
	foreach ($ns as $nm) {
		$v = isset($d[$nm]) ? 'fa-'.$d[$nm] : '';
		if ($v) $o .= ' ' . $v;
	}
	$o .= '"';
	if ($attribs) $o .= ' ' . $attribs;
	$o .= '></i>';
	return $before . $o . $after;
}
