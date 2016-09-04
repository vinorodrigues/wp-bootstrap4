<?php
/**
 * Thanks to Tom McFarlin
 * @see: http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-5-tabbed-navigation-for-your-settings-page--wp-24971
 * Thanks to Narayan Prusty
 * @see: http://www.sitepoint.com/create-a-wordpress-theme-settings-page-with-the-settings-api/
 */


if (!defined('BS4_THOPT_SLUG')) define( 'BS4_THOPT_SLUG' , 'tswp_bootstrap4_options' );
if (!defined('BS4_THOPT_SEC1')) define( 'BS4_THOPT_SEC1' , BS4_THOPT_SLUG.'_1' );
if (!defined('BS4_THOPT_SEC2')) define( 'BS4_THOPT_SEC2' , BS4_THOPT_SLUG.'_2' );


include_once 'lib-ts/opt-common.php';


/**
 *
 */
function bs4_options_page() {
	global $title;

	$active_tab = isset($_GET['tab']) ? intval($_GET['tab']) : 1;

	?><div class="wrap"><h1><?php
	echo $title;
	?></h1><?php


	?><h2 class="nav-tab-wrapper"><?php

	if ($active_tab != 1) echo '<a href="?page='.BS4_THOPT_SLUG.'&tab=1" class="nav-tab">';
	else echo '<span class="nav-tab nav-tab-active">';
	?>Dependencies<?php
	if ($active_tab != 1) echo '</a>'; else echo '</span>';

	if ($active_tab != 2) echo '<a href="?page='.BS4_THOPT_SLUG.'&tab=2" class="nav-tab">';
	else echo '<span class="nav-tab nav-tab-active">';
	?>Styling &amp; Script<?php
	if ($active_tab != 2) echo '</a>'; else echo '</span>';

	?></h2><?php


	settings_errors();

	?><form method="post" action="options.php"><?php

	switch ($active_tab) {
		case 1:
			settings_fields(BS4_THOPT_SEC1);
			do_settings_sections(BS4_THOPT_SEC1);
			break;
		case 2:
			settings_fields(BS4_THOPT_SEC2);
			do_settings_sections(BS4_THOPT_SEC2);
			break;
		default:
			echo '<h2>Oops</h2>';
			echo '<p>Unknown tab.</p>';
			break;
	}

	submit_button();

	?></form></div><?php
}

/**
 *
 */
function bs4_admin_menu() {
	if ( function_exists('add_tecsmith_page') )
		add_tecsmith_page(
			'WP-Bootstrap4 Theme',
			'Theme Options',
			'manage_options',
			BS4_THOPT_SLUG,
			'bs4_options_page',
			'dashicons-admin-appearance',
			1 );
	add_theme_page(
		'WP-Bootstrap4 Theme',
		'Theme Options',
		'manage_options',
		BS4_THOPT_SLUG,
		'bs4_options_page',
		'dashicons-admin-appearance' );
}

add_action("admin_menu", "bs4_admin_menu");


/**
 *
 */
function bs4_get_option($option = null) {
	global $bs4_singletons;
	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['options']) ||
		empty($bs4_singletons['options'])) {

		$array1 = (array) get_option(BS4_THOPT_SEC1);
		$array2 = (array) get_option(BS4_THOPT_SEC2);
		$saved = array_merge($array1, $array2);
		$defaults = array(
			'block_plugins' => false,
			'bootstrap_css' => '',
			'equalheights' => false,
			'bootstrap_js' => '',
			'tether_js' => '',
			'jquery_js' => '',
			'icon_set' => 'fa',

			'inline_css' => '',
			'inline_js' => '',
			'footer_text' => '',
			);
		$bs4_singletons['options'] = wp_parse_args( $saved, $defaults );
	}

	if (is_null($option))
		return $bs4_singletons['options'];

	if (isset($bs4_singletons['options'][$option]))
		return $bs4_singletons['options'][$option];

	return null;
}

/**
 *
 */
function bs4_dependancies_callback() {
	?><p class="description">This section changes the look, feel and behavior of the theme's framework.
	<br /><i class="dashicons dashicons-warning"></i> <b>Use with caution!</b></p><?php
}

/**
 *
 */
function bs4_styling_callback() {
	?><p class="description">This section allows you to add custom CSS and additional JavaScript.
	<br /><i class="dashicons dashicons-warning"></i> <b>Warning:</b> Invalid code may cause the site to fail.</p><?php
}

/**
 *
 */
function bs4_opts_bool_callback($args) {
	?><input type="checkbox" id="<?= $args[1] ?>" name="<?= $args[0].'['.$args[1].']' ?>" value="1" <?= checked(1, bs4_get_option($args[1]), false) ?> />
	<label for="<?= $args[1] ?>"><?= $args[2] ?></label><?php
	if (isset($args[3])) { ?><br /><span class="description"><?= $args[3] ?></span><?php }
}

/**
 *
 */
function bs4_opts_url_callback($args) {
	$error = get_settings_errors();
	$has_err = false;
	foreach ($error as $err) {
		if ($err['setting'] == $args[1]) { $has_err = true; break; }
	}

	if ($has_err) echo '<span class="form-invalid">';
	?><input type="url" style="width:100%" id="<?= $args[1] ?>" name="<?= $args[0].'['.$args[1].']' ?>" value="<?= bs4_get_option($args[1]); ?>" />
	<br /><span class="description"><?= $args[2] ?></span><?php
	if ($has_err) echo '</span>';
}

/**
 *
 */
function bs4_opts_text_callback($args) {
	?><input type="text" style="width:100%" id="<?= $args[1] ?>" name="<?= $args[0].'['.$args[1].']' ?>" value="<?= bs4_get_option($args[1]); ?>" />
	<br /><span class="description"><?= $args[2] ?></span><?php
}

/**
 *
 */
function bs4_opts_blob_callback($args) {
	wp_editor(bs4_get_option($args[1]), $args[1], array(
		'media_buttons' => false,
		'textarea_name' => $args[0].'['.$args[1].']',
		'textarea_rows' => 6,
		'tinymce' => false,
		'quicktags' => false,
		));
}

/**
 *
 */
function bs4_opts_icon_set_callback($args) {
	$val = bs4_get_option('icon_set');
	?>
	<select id="icon_set" name="<?= BS4_THOPT_SEC1 ?>[icon_set]">
		<option value="fa" <?= selected($val, 'fa', false) ?>>Font Awesome</option>
		<?php /* <option value="oi" <?= selected($val, 'oi', false) ?>>Open Iconic</option>
		<option value="oc" <?= selected($val, 'oc', false) ?>>Octicons</option>
		<option value="gi" <?= selected($val, 'gi', false) ?>>Glyphicons</option> */ ?>
		<option value="no" <?= selected($val, 'no', false) ?>>None</option>
	</select>
	<?php

}

/**
 *
 */
function __bs4_san_bool($item, $input, &$output) {
	if (isset($input[$item]))
		$output[$item] = ($input[$item] == '1') ? true : false;
}

/**
 *
 */
function __bs4_san_url($item, $input, &$output) {
	if (isset($input[$item])) {
		$url = esc_url_raw( strip_tags( stripslashes( $input[$item] ) ) );
		if (empty($url) || (!filter_var($url, FILTER_VALIDATE_URL) === false))
			$output[$item] = $url;
		else
			add_settings_error($item, 'url', '<label for="'.$item.'">Invalid URL</label>');
	}
}

/**
 *
 */
function __bs4_san_text($item, $input, &$output) {
	if (isset($input[$item]))
		$output[$item] = trim($input[$item]);
}


/**
 *
 */
function bs4_sanitize_options( $input ) {
	global $bs4_singletons;

	$icon_set = bs4_get_option('icon_set');

	// clear to reload next read
	if (isset($bs4_singletons) &&
		isset($bs4_singletons['options']))
		unset($bs4_singletons['options']);

	if (is_null($input)) return null;

	$output = array();
	foreach ($input as $key => $value) $output[$key] = $value;

	__bs4_san_bool('block_plugins', $input, $output);
	__bs4_san_bool('equalheights', $input, $output);
	__bs4_san_bool('wide_footer', $input, $output);
	__bs4_san_url('bootstrap_css', $input, $output);
	__bs4_san_url('bootstrap_js', $input, $output);
	__bs4_san_url('tether_js', $input, $output);
	__bs4_san_url('jquery_js', $input, $output);
	__bs4_san_text('footer_text', $input, $output);

	if (function_exists('bs4_sanitize_i_options_'.$icon_set))
		$output = call_user_func('bs4_sanitize_i_options_'.$icon_set, $output);

	return $output;
}

/**
 *
 */
function bs4_admin_init() {
	register_setting( BS4_THOPT_SEC1, BS4_THOPT_SEC1, 'bs4_sanitize_options' );
	register_setting( BS4_THOPT_SEC2, BS4_THOPT_SEC2, 'bs4_sanitize_options' );

	/* ---------------------------------------------------------------- */

	add_settings_section(
		'bs4_dependancies',           // ID section
		'Theme Dependancies',         // Title
		'bs4_dependancies_callback',  // description callback
		BS4_THOPT_SEC1 );             // page

	add_settings_field(
		'block_plugins',               // ID
		'Don\'t load theme plugins',  // Title
		'bs4_opts_bool_callback',     // callback
		BS4_THOPT_SEC1,               // page
		'bs4_dependancies',           // section
		array(                        // args
			BS4_THOPT_SEC1,
			'block_plugins',
			'Inhibit from loading the WP-Bootsrap4 plugins.',
			'WP theme purists will want this on, but there will be significant loss of theme functionality.'
			)
		);

	add_settings_field(
		'bootstrap_css',
		'Bootstrap Stylesheet',
		'bs4_opts_url_callback',
		BS4_THOPT_SEC1,
		'bs4_dependancies',
		array(
			BS4_THOPT_SEC1,
			'bootstrap_css',
			'URL to <code>boostrap.css</code> file. Leave blank for local copy (version ' . BOOTSTRAP_VERSION . ').',
			)
		);

	add_settings_field(
		'equalheights',
		'Enable Equal Heights',
		'bs4_opts_bool_callback',
		BS4_THOPT_SEC1,
		'bs4_dependancies',
		array(
			BS4_THOPT_SEC1,
			'equalheights',
			'Use the <code>match-heights.js</code> JavaScript to create equal high columns.',
			'Do not use this option if you have set for the FlexBox variant of Bootstrap.')
		);

	add_settings_field(
		'bootstrap_js',
		'Bootstrap JavaScript',
		'bs4_opts_url_callback',
		BS4_THOPT_SEC1,
		'bs4_dependancies',
		array(
			BS4_THOPT_SEC1,
			'bootstrap_js',
			'URL to <code>boostrap.js</code> file.',
			)
		);

	add_settings_field(
		'tether_js',
		'Tether JavaScript',
		'bs4_opts_url_callback',
		BS4_THOPT_SEC1,
		'bs4_dependancies',
		array(
			BS4_THOPT_SEC1,
			'tether_js',
			'URL to <code>tether_js</code> file. Leave blank for local copy (version ' . TETHER_VERSION . ').',
			)
		);

	add_settings_field(
		'jquery_js',
		'JQuery JavaScript',
		'bs4_opts_url_callback',
		BS4_THOPT_SEC1,
		'bs4_dependancies',
		array(
			BS4_THOPT_SEC1,
			'jquery_js',
			'URL to <code>jquery.js</code> file. Leave blank for local copy (version ' . JQUERY_VERSION . ').',
			)
		);

	add_settings_field(
		'icon_set',
		'Icon set',
		'bs4_opts_icon_set_callback',
		BS4_THOPT_SEC1,
		'bs4_dependancies' );

	$fn = 'bs4_options_register_i_'.bs4_icon_set();
	if (function_exists($fn)) call_user_func($fn, BS4_THOPT_SEC1);

	/* ---------------------------------------------------------------- */

	add_settings_section(
		'bs4_styling',
		'Theme Styling',
		'bs4_styling_callback',
		BS4_THOPT_SEC2 );

	add_settings_field(
		'inline_css',
		'Inline CSS',
		'bs4_opts_blob_callback',
		BS4_THOPT_SEC2,
		'bs4_styling',
		array(
			BS4_THOPT_SEC2,
			'inline_css'
			)
		);

	add_settings_field(
		'inline_js',
		'Inline JavaScript',
		'bs4_opts_blob_callback',
		BS4_THOPT_SEC2,
		'bs4_styling',
		array(
			BS4_THOPT_SEC2,
			'inline_js'
			)
		);

	add_settings_field(
		'footer_text',
		'Footer Text',
		'bs4_opts_text_callback',
		BS4_THOPT_SEC2,
		'bs4_styling',
		array(
			BS4_THOPT_SEC2,
			'footer_text',
			'Text to display in the copyright section of the footer.' .
				'<br /><small>Use <kbd>%c%</kbd> for &copy; symbol,' .
				' <kbd>%y%</kbd> for current year &amp' .
				' <kbd>%n%</kbd> for site name, or' .
				' just <kbd>%x%</kbd> to disable the footer.' .
				' Leave empty to use the default.</small>'
			)
		);

	add_settings_field(
		'wide_footer',
		'Wide footer bar',
		'bs4_opts_bool_callback',
		BS4_THOPT_SEC2,
		'bs4_styling',
		array(
			BS4_THOPT_SEC2,
			'wide_footer',
			'Last footer bar is wide.'
			)
		);

}

add_action('admin_init', 'bs4_admin_init');


/**
 *
 */
function bs4_footer_text_from_options($text) {
	$content = str_replace(
		array('%c%', '%y%', '%n%', '%x%', '%d%', '%l%'),
		array('&copy;', date('Y'), get_bloginfo('name'), ' ', '·', '|'),
		bs4_get_option('footer_text') );

	if ($content != '') return trim($content);
	else return $text;
}

/**
 *
 */
function bs4_wp_head_from_options() {
	$content = bs4_get_option('inline_css');

	if (!empty($content)) {
		echo "\n<style type=\"text/css\">\n";
		echo $content;
		echo "\n</style>\n";
	}
}

/**
 *
 */
function bs4_wp_footer_from_options() {
	$content = bs4_get_option('inline_js');

	if (!empty($content)) {
		echo "\n<script language=\"JavaScript\">\n";
		echo $content;
		echo "\n</script>\n";
	}
}

/**
 *
 */
function bs4_options_loaded() {
	if (!empty(bs4_get_option('footer_text')))
		add_filter( 'bootstrap4_footer_text', 'bs4_footer_text_from_options' );

	if (!empty(bs4_get_option('inline_css')))
		add_action( 'wp_head', 'bs4_wp_head_from_options', 981 );

	if (!empty(bs4_get_option('inline_js')))
		add_action( 'wp_footer', 'bs4_wp_footer_from_options', 981 );
}

add_action( 'wp_loaded', 'bs4_options_loaded' );


// *** eof ***
