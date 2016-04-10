<?php
/**
 * FONT AWESOME icon_set
 * @see: http://fontawesome.io/icons/
 */

if (!defined( 'FA_VERSION' )) define( 'FA_VERSION', '4.6.0' );  // local version

function bs4_enqueue_style_i_fa($min = '') {
	$url = trim( get_theme_mod('fontawesome_css', false) );
	if (empty($url)) {
		$url = get_stylesheet_directory_uri() . '/css/font-awesome' . $min . '.css';
		$ver = FA_VERSION;
	} else {
		$ver = NULL;
	}
	wp_enqueue_style( 'font-awesome', $url, array(), $ver );
}

function bs4_customize_register_i_fa($wp_customize, $section) {
	$wp_customize->add_setting( 'fontawesome_css', '' );

	$wp_customize->add_control( 'fontawesome_css', array(
        	'type'        => 'url',
        	'section'     => $section,
        	'description' => 'URL to <code>font-awesome.css</code> file. Leave blank for local copy (version ' . FA_VERSION . ').',
        	'label'       => 'Font Awesome Stylesheet',
        	'input_attrs' => array(
        		'placeholder' => 'Use local copy',
        		) ) );
}

function get_bs4_icon_fa($name, $before = '', $after = '', $attribs = false) {
	$d = array(
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
        	'hellip'   => 'ellipsis-h',
        	'info'     => 'info-circle',
        	'lg'       => 'lg',
        	'link'     => 'link',
        	'login'    => 'lock',
        	'logout'   => 'unlock',
        	'post'     => 'arrow-circle-right',
        	'reply'    => 'reply',
        	'search'   => 'search',
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
