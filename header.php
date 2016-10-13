<?php
/**
 * header.php
 *
 * @see: http://v4-alpha.getbootstrap.com/getting-started/introduction/#quick-start
 * @see: http://oli.jp/2009/html5-structure1/
 * @see: http://alistapart.com/article/previewofhtml5
 */

global $logo_placement, $container_width, $container_segments, $sidebar_position, $container_class, $band_class;

include_once 'inc/menu-walker.php';
include_once 'inc/lib-ts/raw-styles.php';

function bs4_body_class_navbar_default($classes) {
	$classes[] = 'nav-default'; return $classes; }
function bs4_body_class_navbar_fixed_top($classes) {
	$classes[] = 'nav-fixed-top'; return $classes; }
function bs4_body_class_navbar_fixed_bottom($classes) {
	$classes[] = 'nav-fixed-bottom'; return $classes; }
function bs4_body_class_has_folio($classes) {
	$classes[] = 'folioed'; return $classes; }
function bs4_body_class_no_folio($classes) {
	$classes[] = 'banded'; return $classes; }

function bs4_body_class_singular($classes) {
	if (is_page() || is_attachment() || is_single()) {
		if (array_search('singular', $classes) === false)
			$classes[] = 'singular';
	} else {
		$classes[] = 'multiple';
	}
	if ( defined('WP_DEBUG') && WP_DEBUG ) $classes[] = 'wp-debug';
	return $classes;
}

function bs4_heading() {
	$output = apply_filters('bs4_heading', false);

	if (false === $output) {
		$custom_logo = bs4_get_logo_img('site-logo img-fluid h-i');

		if (false !== $custom_logo) {
			$output = '<a href="' . bs4_home_url() . '" rel="home">' .
				$custom_logo . '</a>';
		} else {
			$output = '<h1 class="title h-i">';
			$output .= '<a href="' . bs4_home_url() . '" rel="home">' .
				get_bloginfo('name', 'display') . '</a>';
			if (!empty(get_bloginfo('description')))
				$output .= '<br><small class="text-muted subtitle">' .
					get_bloginfo('description', 'display') .
					'</small>';
			$output .= '</h1>';
		}
	}

	return $output;
}

function bs4_headernav($container_class = '', $menu_class = '') {
	return wp_nav_menu( array(
		'menu'	          => 'header',
		'menu_class'      => 'nav nav-pills' .
			(!empty($menu_class) ? ' ' . $menu_class : ''),
		'container'       => 'nav',
		'container_class' => 'header-nav h-i' .
			(!empty($container_class) ? ' ' . $container_class : ''),
		'fallback_cb'     => false,
		'depth'	          => 2,
		'walker'          => new Bootstrap_Walker_Menu_Nav(),
		'theme_location'  => 'header',
		'echo'            => false,
		) );
}

function bs4_content_class($sidebar_position) {
	$classes = array();
	$classes[] = 'col-xs-12';
	switch ($sidebar_position) {
		case 1:
			$classes[] = 'col-md-8';
			$classes[] = 'push-md-4';
			$classes[] = 'col-lg-9';
			$classes[] = 'push-lg-3';
			break;
		case 2:
			$classes[] = 'col-md-8';
			$classes[] = 'col-lg-9';
			break;
		case 3:
			$classes[] = 'col-md-6';
			$classes[] = 'push-md-3';
			$classes[] = 'col-lg-8';
			$classes[] = 'push-lg-2';
			break;
	};
	if ( bs4_get_option('equalheights') ) $classes[] = 'eh';
	$classes[] = 'col-pr-12';
	if (($sidebar_position == 1) || ($sidebar_position == 3)) $classes[]= 'push-pr-0';
	$classes[] = 'content';
	return implode(' ', apply_filters('bs4_content_class', $classes));
}

function bs4_body_attribs($class = '') {
	echo apply_filters( 'bs4_body_attribs',
		'class="' . join( ' ', get_body_class( $class ) ) . '"' );
}

/* ----- */

$logo_placement = intval( get_theme_mod('logo_placement', 0) );
$container_width = intval( get_theme_mod('container_width', 0) );
$container_segments = intval( get_theme_mod('container_segments', 0) );

if (get_theme_mod( 'navbar_color', 0 ) == 5)
	ts_enqueue_style(
		'navbar',
		'.bg-custom { background-color: ' .
			get_theme_mod( 'navbar_color_custom', '#000000' ) . '; }' );

/*
 * 0 => Right, 1 => Center, 2 => Left  (content position)
 *  becomes ->
 * 0 => None, 1 => Left, 2 => Right, 3 => Center (sidebar position)
 */

if (is_404()) $sidebar_position = 0;
else $sidebar_position = apply_filters( 'bs4_get_sidebar_position', -1 );
if (-1 == $sidebar_position) {
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
}

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
	case 0: add_filter('body_class', 'bs4_body_class_navbar_default'); break;
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
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php
	wp_head();
	// comments_popup_script(640, 480);
	// XXX: http://www.quickonlinetips.com/archives/2014/06/add-popup-comments-in-wordpress-posts/
?>
</head>
<body <?php bs4_body_attribs(); ?>><a name="top"></a>
<?php
if ($container_segments == 0) {
	echo '<div class="' . $container_class . ' folio">';
	do_action('bs4_header_before');
} else {
	do_action('bs4_header_before');
	echo '<div class="header">';
}
?>

<header id="header" class="section">
<?php
	$do_header = (($head_a & 3) == 0) && ($logo_placement != 3) && ($logo_placement != 4);

	if ($do_header) echo '<div class="' . $band_class . ' heading"><div class="row">';

	switch ($logo_placement) {
	case 1:  // center
		?><div class="col-xs-12 head-c-1"><?= bs4_heading() ?></div><?php
	case 3:  // Navbar
	case 4:  // Disabled
		if (($head_a & 1) != 0) {
			?><div class="col-xs-12 head-c-2 hidden-print"><?php
			echo bs4_headernav() ?></div><?php
		}
		if (($head_a & 2) != 0) {
			?><div class="col-xs-12 head-c-3 hidden-print"><?php
			dynamic_sidebar('sidebar-3') ?></div><?php
		}
		break;
	case 2:  // right
		if ($head_a == 0) {
			?><div class="col-xs-12 head-r-1"><?= bs4_heading() ?></div><?php
		} else {
			?><div class="col-xs-12 col-sm-5 push-sm-7 col-pr-12 head-r-1"><?php
				echo bs4_heading() ?></div><?php
			?><div class="col-xs-12 col-sm-7 pull-sm-5 <?php
				echo $head_a !== 3 ? 'head-r-2 ' : '' ?>hidden-print"><?php
			if ($head_a === 3) echo '<div class="row"><div class="col-xs-12 head-r-2">';  // nested row
			if (($head_a & 1) != 0) echo bs4_headernav();
			if ($head_a === 3) echo '</div><div class="col-xs-12 head-r-3">';
			if (($head_a & 2) != 0) dynamic_sidebar('sidebar-3');
			if ($head_a === 3) echo '</div></div>';
			?></div><?php
		}
		break;
	default:  // left
		if ($head_a == 0) {
			?><div class="col-xs-12 head-l-1"><?= bs4_heading() ?></div><?php
		} else {
			?><div class="col-xs-12 col-sm-5 col-pr-12 head-l-1"><?php
				echo bs4_heading() ?></div><?php
			?><div class="col-xs-12 col-sm-7 <?php
				echo $head_a !== 3 ? 'head-l-2 ' : '' ?>hidden-print"><?php
			if ($head_a === 3) echo '<div class="row"><div class="col-xs-12 head-l-2">';  // nested row
			if (($head_a & 1) != 0) echo bs4_headernav();
			if ($head_a === 3) echo '</div><div class="col-xs-12 head-l-3">';
			if (($head_a & 2) != 0) dynamic_sidebar('sidebar-3');
			if ($head_a === 3) echo '</div></div>';
			?></div><?php
		}
		break;
	}

	if ($do_header) echo '</div></div>';

	get_template_part( 'navbar' );
	if (!is_404()) {
		$header_image = get_header_image();
		if ( ! empty( $header_image ) ) {
			?><div id="feature" class="hidden-print"><?php
			echo '<div class="' . $band_class . ' feature">' .
				'<div class="row"><div class="col-xs-12">';
			echo '<img src="' . $header_image .
				'" class="header-image img-fluid ' .
				FEATURED_IMAGE_CLASS . '">';
			echo '</div></div></div>';
			?></div><?php
		}
	}
?></header>

<?php
do_action('bs4_header_after');
$main_band_class = $band_class;
$main_attribs = apply_filters('bs4_main_attribs', '');
if ($main_attribs != '') $main_attribs = ' ' . $main_attribs;
if ($container_segments == 0) {
	$main_band_class .= ' ' . apply_filters('bs4_main_class', 'main');
} else {
	echo '</div><div class="' . apply_filters('bs4_main_class', 'main') . '"' .
	$main_attribs . '>';
}
?>

<main id="main" class="section">
<div
  class="<?= $main_band_class ?>"<?php if ($container_segments == 0) echo $main_attribs; ?>><div
  class="row"><div
  id="content" class="<?= bs4_content_class($sidebar_position) ?>">
<?php if (function_exists('bs4_breadcrumb') && !is_404()) bs4_breadcrumb(); ?>
