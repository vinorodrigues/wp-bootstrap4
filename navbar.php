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

$navbar_color = get_theme_mod( 'navbar_color', 0 );
$navbar_shading = boolval( get_theme_mod( 'navbar_shading', 0 ) );
$navbar_container = boolval( get_theme_mod( 'navbar_container', 0 ) );
$navbar_placement = get_theme_mod( 'navbar_placement', 0 );

if ($logo_placement == 3) {
	$navbar_logo = bs4_get_logo_img('brand-logo');
	if (!$navbar_logo) $navbar_brand = get_bloginfo('name', 'display');
	else $navbar_brand = $navbar_logo;
} else $navbar_brand = '';
$navbar_brand .= get_theme_mod( 'navbar_brand', '' );

// $navbar_icon = get_theme_mod( 'navbar_icon', false );
$navbar_search = boolval( get_theme_mod( 'navbar_search', 0 ) );

$navbar_class = array('navbar');
if ($navbar_shading) $navbar_class[] = 'navbar-light';
else $navbar_class[] = 'navbar-dark';

switch ($navbar_color) {
	case 1: $navbar_class[] = 'bg-inverse'; break;
	case 2: $navbar_class[] = 'bg-default'; break;
	case 3: $navbar_class[] = 'bg-faded'; break;
	case 4: $navbar_class[] = 'bg-transparent'; break;
	case 5: $navbar_class[] = 'bg-custom'; break;
	default: $navbar_class[] = 'bg-primary';
}
switch ($navbar_placement) {
	case 1: $navbar_class[] = 'navbar-fixed-top'; break;
	case 2: $navbar_class[] = 'navbar-fixed-bottom'; break;
	default:
		if ($navbar_container && ($container_segments != 0))
			$navbar_class[] = 'navbar-full';
		break;
}
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

?><button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#collapsible-navbar">&#9776;</button>
<div class="collapse navbar-toggleable-xs" id="collapsible-navbar"><?php

if (!empty($navbar_brand)) {
	?><a class="navbar-brand" href="<?= site_url() ?>"><?= $navbar_brand ?></a><?php
}

$menu_class = 'nav navbar-nav';
if ($navbar_brand && ($logo_placement == 3) && (!$navbar_search))
	$menu_class .= ' pull-xs-right';
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
	?><form method="get" id="search-form-0" action="<?= site_url() ?>" class="form-inline navbar-form search-from pull-xs-right">
		<input type="search" name="s" class="search-input form-control"
			placeholder="Search &hellip;" value="<?php esc_attr( get_search_query() ); ?>"
			autosave="search-0" autocorrect="on">
		<button type="submit" class="search-button btn btn-outline-success"><?php bs4_i('search'); ?></button>
	</form><?php
}

?></div><?php

if ($navbar_container) {
	?></div></nav><?php
} else {
	?></nav></div><?php
}

endif;
?>
