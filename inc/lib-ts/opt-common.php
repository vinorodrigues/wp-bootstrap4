<?php

if (!defined('TS_SETTINGS_MENU')) :
	define( 'TS_SETTINGS_MENU', '1.0' );
	define( 'TS_SETTINGS_MENU_SLUG', 'tecsmith' );


$TECSMITH_SVG = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="128" height="128">' .
	'<path fill="#c30000" d="M 63.875 56.5054 L 112.7966 32.4702 L 63.875 10.1934 L 14.9534 32.4702 Z" />' .
	'<path fill="#00c300" d="M 59.8318 63.595 L 10.0478 38.5742 L 15.2894 89.3438 L 60.2238 117.3886 Z" />' .
	'<path fill="#0000c3" d="M 67.9182 63.595 L 117.7022 38.5742 L 112.4606 89.3438 L 67.5262 117.3886 Z" />' .
	'</svg>';


/**
 * Top level Tecsmith settings page
 */
function ts_top_admin_options_page() {
	global $title, $ts_settings, $TECSMITH_SVG;

	if ( ! current_user_can( 'manage_options' ) )
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

	echo '<div class="wrap">';
	screen_icon();
	echo '<h2 style="vertical-align:middle;">';
	echo '<img src="data:image/svg+xml;base64,'.base64_encode($TECSMITH_SVG).'" style="width:32px;height:32px;vertical-align:middle;">';
	echo ' ';
	echo $title;
	echo '</h2>';

	if (!isset($ts_settings) || !isset( $ts_settings['tecsmith_menu_items'] ))
		add_settings_error(
    			TS_SETTINGS_MENU_SLUG,
	    		esc_attr(TS_SETTINGS_MENU_SLUG),
    			'There are no active items with settings',
    			'error' );

	settings_errors();

	if (isset($ts_settings) && isset( $ts_settings['tecsmith_menu_items'] )) {
		@ksort($ts_settings['tecsmith_menu_items']);

		echo '<table>';
		echo '<thead style="background-color:rgba(0,0,0,0.2)">';
		echo '<tr><th style="text-align:left;width:40%;padding:4px 8px">Settings</th>';
		echo '<th style="text-align:left;width:60%;padding:4px 8px">Description</th></tr>';
		echo '</thead><tbody>';
		foreach ($ts_settings['tecsmith_menu_items'] as $id => $data) {
			echo '<tr><td>';
			if ($data[1] !== false) {
				echo '<a href="';
				echo get_admin_url( null, 'admin.php?page=' . $data[1] );
				echo '" class="button ">' . $data[2] . '</a>';
			} else {
				echo '<center><span class="dashicons dashicons-yes"></span></center>';
			}
			echo '</td><td>';
			echo $data[3];
			echo '</td><tr>';
		}
		echo '</tbody></table>';
	}

	?>
	<hr>
	<h3><i class="dashicons dashicons-smiley"></i> Thank you for using Tecsmith software!</h3>
	<p>
	<i class="dashicons dashicons-admin-home"></i> Visit our home page at <a href="http://tecsmith.com.au" target="_blank">tecsmith.com.au</a>.<br>
	<i class="dashicons dashicons-media-code"></i> Our open source is hosted on <a href="http://github.com/tecsmith" target="_blank">Github</a>.<br>
	<i class="dashicons dashicons-email"></i> &lt;<a href="mailto:hello@tecsmith.com.au">hello@tecsmith.com.au</a>&gt;<br>
	<i class="dashicons dashicons-twitter"></i> <a href="http://twitter.com/tcsmth" target="_blank">@tcsmth</a><br>
	</p>
	<?php

	echo '</div>';
}


/**
 * Add Tecsmith menu item
 */
function add_tecsmith_menu() {
	global $ts_settings, $TECSMITH_SVG;
	if (!isset( $ts_settings )) $ts_settings = array();

	if (isset( $ts_settings['tecsmith_menu'] ))
		return $ts_settings['tecsmith_menu'];

	$slug = add_submenu_page(
		TS_SETTINGS_MENU_SLUG,
		'Active Tecsmith Plugins',
		'Active Plugins',
		'manage_options',
		TS_SETTINGS_MENU_SLUG,
		'ts_top_admin_options_page' );

	$slug = add_menu_page(
		'Tecsmith Options',
		'Tecsmith',
		'manage_options',
		TS_SETTINGS_MENU_SLUG,
		'ts_top_admin_options_page',
		'data:image/svg+xml;base64,'.base64_encode($TECSMITH_SVG) );  /* */

	$ts_settings['tecsmith_menu'] = $slug;
	return $slug;
}


function __add_tecsmith_item($position, $slug, $menu_slug, $menu_title, $page_title) {
	global $ts_settings;
	if (!isset( $ts_settings )) $ts_settings = array();

	if (!isset( $ts_settings['tecsmith_menu_items'] )) $ts_settings['tecsmith_menu_items'] = array();

	if (is_null($position)) $position = 100;

	while ( array_key_exists($position, $ts_settings['tecsmith_menu_items']) ) $position++;

	$ts_settings['tecsmith_menu_items'][$position] = array($slug, $menu_slug, $menu_title, $page_title);

	return $position;
}


/**
 * Add Tecsmith menu item
 */
function add_tecsmith_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null ) {
	add_tecsmith_menu();
	$slug = add_submenu_page(
		TS_SETTINGS_MENU_SLUG,
		$page_title,
		$menu_title,
		$capability,
		$menu_slug,
		$function,
		$icon_url,
		$position );
	__add_tecsmith_item($position, $slug, $menu_slug, $menu_title, $page_title);
	return $slug;
}


/**
 * Add tecsmith item with no menu
 */
function add_tecsmith_item( $page_title, $menu_slug, $position = null ) {
	add_tecsmith_menu();
	$slug = plugin_basename( $menu_slug );
	__add_tecsmith_item($position, $slug, false, false, $page_title);
	return $slug;
}


endif;  // TS_SETTINGS_MENU
/* eof */
