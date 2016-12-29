<?php
/*
 * navbar.php
 *
 * @see: http://v4-alpha.getbootstrap.com/components/navbar/
 * @see: http://www.quackit.com/bootstrap/bootstrap_4/tutorial/bootstrap_navbars.cfm
 */

global $band_class, $logo_placement, $container_class, $container_segments;

if (has_nav_menu('primary') || !empty(get_theme_mod('navbar_brand', '')) || ($logo_placement == 3)) :

include_once 'inc/menu-walker.php';

$navbar_band_class = $band_class;

$navbar_container = boolval( get_theme_mod( 'navbar_container', 0 ) );
$navbar_placement = get_theme_mod( 'navbar_placement', 0 );

$navbar_brand = apply_filters('bs4_navbar_brand', false);
if (false === $navbar_brand) {
	if ($logo_placement == 3) {
		$navbar_logo = bs4_get_logo_img('brand-logo');
		if (!$navbar_logo) $navbar_brand = get_bloginfo('name', 'display');
		else $navbar_brand = $navbar_logo;
	} else $navbar_brand = '';
	$navbar_brand .= get_theme_mod( 'navbar_brand', '' );
}

// $navbar_icon = get_theme_mod( 'navbar_icon', false );
$navbar_search = boolval( get_theme_mod( 'navbar_search', 0 ) );

$navbar_class = array('navbar');
$navbar_class[] = bs4_navbar_toggler_class( get_theme_mod( 'navbar_toggler', 0 ) );
$navbar_class[] = bs4_navbar_shading_class( boolval( get_theme_mod( 'navbar_shading', 0 ) ) );
$navbar_class[] = bs4_navbar_color_class( get_theme_mod( 'navbar_color', 0 ) );

$navbar_tog_pos = get_theme_mod( 'navbar_tog_pos', 0 );
$navbar_toggler_class = 'navbar-toggler';
if ($navbar_tog_pos != 0)
	$navbar_toggler_class .= ' ' . bs4_navbar_tog_pos_class( $navbar_tog_pos );

switch ($navbar_placement) {
	case 1: $navbar_class[] = 'navbar-fixed-top'; break;
	case 2: $navbar_class[] = 'navbar-fixed-bottom'; break;
	default:
		if ($navbar_container && ($container_segments != 0))
			$navbar_class[] = 'navbar-full';
		break;
}
if ($navbar_container && ($navbar_placement == 0) && ($container_segments == 0))
	$navbar_class[] = 'navbar-wide';

$navbar_class[] = 'main-menu';
$navbar_class[] = 'hidden-print';
$navbar_class = implode( ' ', apply_filters( 'bs4_navbar_class', $navbar_class ) );

if (($navbar_placement > 0) && (strpos($navbar_band_class, $container_class) !== 0)) {
	$navbar_band_class .= ' ' . $container_class;
}

if ($navbar_container) {
	?><nav id="main-navbar" class="<?= $navbar_class ?>">
	<div class="<?= $navbar_band_class ?>"><?php
} else {
	?><div class="<?= $navbar_band_class ?>">
	<nav id="main-navbar" class="<?= $navbar_class ?>"><?php
}

?><button class="<?= $navbar_toggler_class ?>" type="button"
  data-toggle="collapse" data-target="#collapsible-navbar"><span class="navbar-toggler-icon"></span></button><?php

function __do_bs4_navbar_brand_link($navbar_brand) {
	if (!empty($navbar_brand))
		echo apply_filters( 'bs4_navbar_brand_link',
			'<a class="navbar-brand" href="' .
			bs4_home_url() . '" rel="home">' . $navbar_brand . '</a>' );
}

if ($navbar_tog_pos != 0) __do_bs4_navbar_brand_link($navbar_brand);

?><div class="collapse navbar-collapse" id="collapsible-navbar"><?php

if ($navbar_tog_pos == 0) __do_bs4_navbar_brand_link($navbar_brand);

$menu_class = 'navbar-nav mr-auto';
if ($navbar_brand && ($logo_placement == 3) && (!$navbar_search))
	$menu_class .= ' float-right';
$menu_content = apply_filters('bs4_wp_nav_menu', '', $menu_class);

if ($menu_content != '') {
	echo $menu_content;
} else {
	$navbar_array = array(
		'menu'	         => 'primary',
		'menu_class'     => $menu_class,
		'container'      => false,
		'fallback_cb'    => false,
		'depth'	         => 2,
		'walker'	 => new Bootstrap_Walker_Menu_Nav(),
		'theme_location' => 'primary',
		);
	wp_nav_menu( $navbar_array );
}

if ($navbar_search) {
	?><form method="get" id="search-form-0" action="<?php echo site_url(); ?>"
		class="form-inline navbar-form search-from float-right"><input
			type="search" name="s" class="search-input form-control"
			placeholder="Search &hellip;" value="<?php esc_attr( get_search_query() ); ?>"
			autosave="search-0" autocorrect="on"><button
			type="submit" class="search-button btn btn-outline-success"><?php
			bs4_i('search'); ?></button></form><?php
}

?></div><?php

if ($navbar_container) { ?></div></nav><?php }
else { ?></nav></div><?php }

endif;
