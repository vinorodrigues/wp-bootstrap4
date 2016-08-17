<?php
/*
 * navbar.php
 *
 * @see: http://v4-alpha.getbootstrap.com/components/navbar/
 * @see: http://www.quackit.com/bootstrap/bootstrap_4/tutorial/bootstrap_navbars.cfm
 */

if (has_nav_menu('primary') || !empty(get_theme_mod('navbar_brand', ''))) :

include_once 'inc/menu-walker.php';

global $band_class, $logo_placement, $container_class;
$navbar_band_class = $band_class;

$navbar_color = get_theme_mod( 'navbar_color', 0 );
$navbar_shading = boolval( get_theme_mod( 'navbar_shading', 0 ) );
$navbar_container = boolval( get_theme_mod( 'navbar_container', 0 ) );
$navbar_placement = get_theme_mod( 'navbar_placement', 0 );
$navbar_brand = get_theme_mod( 'navbar_brand', '' );  // TODO : $navbar_brand & ($logo_placement == 3)
// $navbar_icon = get_theme_mod( 'navbar_icon', false );
$navbar_search = boolval( get_theme_mod( 'navbar_search', 0 ) );

$navbar_class = $navbar_shading ? 'navbar-light' : 'navbar-dark';
switch ($navbar_color) {
	case 1: $navbar_class .= ' bg-inverse'; break;
	case 2: $navbar_class .= ' bg-default'; break;
	case 3: $navbar_class .= ' bg-faded'; break;
	case 4: $navbar_class .= ' bg-transparent'; break;
	case 5: $navbar_class .= ' bg-custom'; break;
	default: $navbar_class .= ' bg-primary';
}
switch ($navbar_placement) {
	case 1: $navbar_class .= ' navbar-fixed-top'; break;
	case 2: $navbar_class .= ' navbar-fixed-bottom'; break;
	// default: ;  // do nothing
}
$navbar_class = apply_filters( 'bs4_navbar_class', $navbar_class );

if (($navbar_placement > 0) && (strpos($navbar_band_class, $container_class) !== 0)) {
	$navbar_band_class .= ' ' . $container_class;
}

if ($navbar_container) {
	?><nav class="navbar <?= $navbar_class ?> main-menu hidden-print">
	<div class="<?= $navbar_band_class ?>"><?php
} else {
	?><div class="<?= $navbar_band_class ?> main-menu hidden-print">
	<nav class="navbar <?= $navbar_class ?>"><?php
}

?><button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#CollapsingNavbar">&#9776;</button>
<div class="collapse navbar-toggleable-xs" id="CollapsingNavbar"><?php

if (!empty($navbar_brand)) {
	?><a class="navbar-brand m-b-0" href="<?= site_url() ?>"><?= $navbar_brand ?></a><?php
}

$menu_content = apply_filters('bs4_wp_nav_menu', '');
if ($menu_content != '') {
	echo $menu_content;
} else {
	$navbar_array = array(
		'menu'	         => 'primary',
		'menu_class'     => 'nav navbar-nav',
		'container'      => false,
		'fallback_cb'    => false,
		'depth'	         => 2,
		'walker'	 => new Bootstrap_Walker_Menu_Nav(),
		'theme_location' => 'primary',
		);
	wp_nav_menu( $navbar_array );
}

if ($navbar_search) {
	?><form method="get" id="search-form-0" action="<?= home_url( '/' ) ?>" class="form-inline navbar-form search-from pull-xs-right">
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
