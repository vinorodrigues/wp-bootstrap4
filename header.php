<?php
/**
 * header.php
 *
 * @see: http://v4-alpha.getbootstrap.com/getting-started/introduction/#quick-start
 * @see: http://oli.jp/2009/html5-structure1/
 * @see: http://alistapart.com/article/previewofhtml5
 */

include_once 'inc/menu-walker.php';
include_once 'inc/lib-ts/raw-styles.php';

function bs4_body_class_navbar_fixed_top($classes) { $classes[] = 'nav-fixed-top'; return $classes; }
function bs4_body_class_navbar_fixed_bottom($classes) { $classes[] = 'nav-fixed-bottom'; return $classes; }
function bs4_body_class_has_folio($classes) { $classes[] = 'folioed'; return $classes; }
function bs4_body_class_no_folio($classes) { $classes[] = 'banded'; return $classes; }

function bs4_body_class_singular($classes) {
	if (is_page() || is_attachment() || is_single()) {
		if (array_search('singular', $classes) === false)
			$classes[] = 'singular';
	} else {
		$classes[] = 'multiple';
	}
	return $classes;
}

function bs4_heading($logo_placement = 0) {
	// 0 = right
	// 1 = center
	// 2 = right

	$id = false;
	if (USE_WP45_LOGO) {  // NEW IN WP4.5
		$has_logo = has_custom_logo();
		if ($has_logo) $id = get_theme_mod( 'custom_logo' );
	} else
		$id = get_theme_mod('custom_logo', '');

	$custom_logo = false;
	if ($id) {
		$custom_logo = wp_get_attachment_image_src( $id, 'full', false );
		if ($custom_logo !== false) $custom_logo = $custom_logo[0];
	}

	$has_logo = !empty($custom_logo);
	if ($has_logo) {
		$w = get_theme_mod( 'logo_width', false );
		$h = get_theme_mod( 'logo_height', false );

		$custom_logo = '<img src="' . $custom_logo . '" class="site-logo img-fluid';
      			if ($logo_placement != 0) $custom_logo .= ' ' . (($logo_placement == 1) ? 'm-x-auto' : 'pull-xs-right');
		$custom_logo .= '" alt="' . get_bloginfo('name', 'display');
		$_d = get_bloginfo( 'description', 'display' );
		if ( $_d  ) $custom_logo .= ' - ' . $_d;
		if ($w || $h) {
			$custom_logo .= '" style="';
			if ($w) $custom_logo .= 'width:'.$w.';';
			if ($h) $custom_logo .= 'height:'.$h.';';
			$custom_logo .= '"';
		}
		$custom_logo .= '">';
	}

	if ($has_logo) {
		$output = '<a href="' . home_url( '/' ) . '" rel="home">' . $custom_logo . '</a>';
	} else {
		$output = '<h1 class="title';
		if ($logo_placement == 2) $output .= ' text-xs-right';
		$output .= '"><a href="' . home_url( '/' ) . '" rel="home">' . get_bloginfo('name', 'display') . '</a>';
		if (!empty(get_bloginfo('description'))) {
			$output .= ($logo_placement == 0) ? ' ' : '<br>';
			$output .= '<small class="text-muted subtitle">' .  get_bloginfo('description', 'display') . '</small>';
		}
		$output .= '</h1>';
	}
	return $output;
}

function bs4_headernav($container_class = '', $menu_class = '') {
	return wp_nav_menu( array(
		'menu'	          => 'header',
		'menu_class'      => 'nav nav-pills' . (!empty($menu_class) ? ' ' . $menu_class : ''),
		'container'       => 'nav',
		'container_class' => $container_class,
		'fallback_cb'     => false,
		'depth'	          => 2,
		'walker'          => new Bootstrap_Walker_Menu_Nav(),
		'theme_location'  => 'header',
		'echo'            => false,
		) );
}

function bs4_content_class($sidebar_position) {
	$o = 'col-xs-12';
	switch ($sidebar_position) {
		case 1: $o .= ' col-md-8 push-md-4 col-lg-9 push-lg-3'; break;
		case 2: $o .= ' col-md-8 col-lg-9'; break;
		case 3: $o .= ' col-md-6 push-md-3 col-lg-8 push-lg-2'; break;
	};
	if ( bs4_get_option('equalheights') ) $o .= ' eh';
	$o .= ' col-pr-12 content';
	return $o;
}

/* ----- */

global $container_width, $container_segments, $sidebar_position, $band_class;

$logo_placement = intval( get_theme_mod('logo_placement', 0) );
$container_width = intval( get_theme_mod('container_width', 0) );
$container_segments = intval( get_theme_mod('container_segments', 0) );

if (get_theme_mod( 'navbar_color', 0 ) == 4)
	ts_enqueue_style(
		'navbar',
		'.bg-custom { background-color: ' . get_theme_mod( 'navbar_color_custom', '#000000' ) . '; }' );

/*
 * 0 => Right, 1 => Center, 2 => Left  (content position)
 *  becomes ->
 * 0 => None, 1 => Left, 2 => Right, 3 => Center (sidebar position)
 */
$_1 = is_active_sidebar('sidebar-1');
$_2 = is_active_sidebar('sidebar-2');
$_b = $_1 && $_2;  // both
$_i = $_1 || $_2;  // either
switch ( intval(get_theme_mod('content_position', 0)) ) {
	case 1:  // content center
		if ($_b) {
			$sidebar_position = 3;
		} elseif (!$_i) {
			$sidebar_position = 0;
		} elseif ($_1) {
			$sidebar_position = 1;
		} else {
			$sidebar_position = 2;
		}
		break;
	case 2:  // content left
		$sidebar_position = $_i ? 2 : 0;
		break;
	default:  // 0, content right
		$sidebar_position = $_i ? 1 : 0;
		break;
}

if (is_404()) $sidebar_position = 0;

$container_class = 'container';
if ($container_width == 1) { $container_class .= '-fluid'; }
// elseif ($container_width == 2) { ??? }

$band_class = 'band';
if ($container_segments != 0) {
	$band_class .= ' ' . $container_class;
}

add_filter('body_class', 'bs4_body_class_singular');

if ($container_segments == 0) {
	add_filter('body_class', 'bs4_body_class_has_folio');
} else {
	add_filter('body_class', 'bs4_body_class_no_folio');
}

switch (get_theme_mod( 'navbar_placement', 0 )) {
	case 1: add_filter('body_class', 'bs4_body_class_navbar_fixed_top'); break;
	case 2: add_filter('body_class', 'bs4_body_class_navbar_fixed_bottom'); break;
}

$head_a = 0;
if (has_nav_menu('header')) $head_a += 1;
if (is_active_sidebar('sidebar-3')) $head_a += 2;

$color = get_theme_mod('title_color', false);
if ($color)
	ts_enqueue_style(
		'title-color',
		'.heading .title { color: ' . $color . ' !important; }' );

$color = get_theme_mod('tagline_color', false);  // XXX
if ($color)
	ts_enqueue_style(
		'tagline-color',
		'.heading .subtitle { color: ' . $color . ' !important; }' );

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php
	wp_head();
	// comments_popup_script(640, 480);
	// XXX: http://www.quickonlinetips.com/archives/2014/06/add-popup-comments-in-wordpress-posts/
?>
</head>
<body <?php body_class(); ?>><a name="top"></a>
<?php
if ($container_segments == 0) {
	echo '<div class="' . $container_class . ' folio">';
} else {
	echo '<div class="header">';
}
do_action('bs4_header_before');
?>

<header class="section">
<div class="<?= $band_class ?> heading"><div class="row">
<?php
	switch ($logo_placement) {
	case 1:  // center
		?><div class="col-xs-12"><center><?= bs4_heading($logo_placement) ?></center></div><?php
		if (($head_a & 1) != 0) {
			?><div class="col-xs-12 m-t-1 headspace hidden-print"><center><?=
			bs4_headernav('nav-header', 'nav-center') ?></center></div><?php
		}
		if (($head_a & 2) != 0) {
			?><div class="col-xs-12 m-t-1 text-xs-center headspace hidden-print"><center><?php dynamic_sidebar('sidebar-3') ?></center></div><?php
		}
		break;
	case 2:  // right
		if ($head_a == 0) {
			?><div class="col-xs-12"><?= bs4_heading($logo_placement) ?></div><?php
		} else {
			?><div class="col-xs-12 col-md-5 push-md-7 col-pr-12"><?= bs4_heading($logo_placement) ?></div><?php
			?><div class="col-xs-12 col-md-7 pull-md-5 hidden-print"><?php
			if ($head_a === 3) echo '<div class="row"><div class="col-md-12">';  // nested row
			if (($head_a & 1) != 0) echo bs4_headernav();
			if ($head_a === 3) echo '</div><div class="col-md-12 headspace">';
			if (($head_a & 2) != 0) dynamic_sidebar('sidebar-3');
			if ($head_a === 3) echo '</div></div>';
			?></div><?php
		}
	    	break;
	default:  // left
		if ($head_a == 0) {
			?><div class="col-xs-12"><?= bs4_heading() ?></div><?php
		} else {
			?><div class="col-xs-12 col-md-5 col-pr-12"><?= bs4_heading() ?></div><?php
			?><div class="col-xs-12 col-md-7 hidden-print"><?php
			if ($head_a === 3) echo '<div class="row"><div class="col-md-12">';  // nested row
			if (($head_a & 1) != 0) echo bs4_headernav('clearfix pull-xs-right');
			if ($head_a === 3) echo '</div><div class="col-md-12 headspace">';
			if (($head_a & 2) != 0) { echo '<div class="pull-xs-right">'; dynamic_sidebar('sidebar-3'); echo '</div>'; }
			if ($head_a === 3) echo '</div></div>';
			?></div><?php
		}
		break;
	}
?>
</div></div><?php
get_template_part( 'navbar' );
if (!is_404()) {
	$header_image = get_header_image();
	if ( ! empty( $header_image ) ) {
		?><div id="feature" class="hidden-print"><?php
		echo '<div class="' . $band_class . ' feature"><div class="row"><div class="col-xs-12">';
		echo '<img src="' . $header_image . '" class="header-image img-fluid ' . FEATURED_IMAGE_CLASS . '">';
		echo '</div></div></div>';
		?></div><?php
	}
}
?></header>

<?php
do_action('bs4_header_after');
if ($container_segments != 0) { echo '</div><div class="main">'; }
?>

<main class="section">
<div class="<?= $band_class ?><?= ($container_segments == 0 ? ' main' : '')?>"><div class="row"><div id="content" class="<?= bs4_content_class($sidebar_position) ?>">
