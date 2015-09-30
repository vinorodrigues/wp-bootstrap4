<?php
/**
 * header.php
 *
 * @see: http://v4-alpha.getbootstrap.com/getting-started/introduction/#quick-start
 * @see: http://oli.jp/2009/html5-structure1/
 * @see: http://alistapart.com/article/previewofhtml5
 */

include_once 'inc/menu-walker.php';

function bs4_body_class_navbar_fixed_top($classes) { $classes[] = 'p-t-lg'; return $classes; }
function bs4_body_class_navbar_fixed_bottom($classes) { $classes[] = 'p-b-lg'; return $classes; }
function bs4_body_class_has_folio($classes) { $classes[] = 'folioed'; return $classes; }
function bs4_body_class_no_folio($classes) { $classes[] = 'banded'; return $classes; }

function bs4_heading($logo_placement = 0) {
	// 0 = text-right
	// 1 = text-center
	// 2 = text-right

	$logo_image = get_theme_mod('logo_image', false);
	if (!empty($logo_image)) {
        	$output = '<a href="' . home_url( '/' ) . '" rel="home">';
        	$output .= '<img src="' . $logo_image . '" class="img-responsive';
        	if ($logo_placement != 0) $output .= ' ' . (($logo_placement == 1) ? 'center-block' : 'pull-right');
        	$output .= '" alt="' . get_bloginfo('name', 'display');
        	$_d = get_bloginfo( 'description', 'display' );
		if ( $_d  ) $output .= ' - ' . $_d;
        	$output .= '"></a>';
	} else {
        	$output = '<h1 class="title';
        	if ($logo_placement != 0) $output .= ' ' . (($logo_placement == 1) ? 'text-center' : 'text-right');
        	$output .= '"><a href="' . home_url( '/' ) . '" rel="home">' . get_bloginfo('name', 'display') . '</a>';
        	if (!empty(get_bloginfo('description'))) {
        		$output .= ($logo_placement == 0) ? ' ' : '<br>';
        		$output .= '<small class="subtitle text-muted">' .  get_bloginfo('description', 'display') . '</small>';
        	}
        	$output .= '</h1>';
	}
	return $output;
}

function bs4_headernav($classes = '') {
	return wp_nav_menu( array(
        	'menu'            => 'header',
        	'menu_class'      => 'nav nav-pills',
        	'container'       => 'nav',
        	'container_class' => $classes,
        	'fallback_cb'     => false,
        	'depth'           => 2,
        	'walker'          => new Bootstrap_Walker_Menu_Nav(),
        	'theme_location'  => 'header',
        	'echo'            => false,
        	) );
}

function bs4_content_class($sidebar_position) {
	$o = 'col-xs-12';
	switch ($sidebar_position) {
        	case 1: $o .= ' col-md-8 col-md-push-4 col-lg-9 col-lg-push-3'; break;
        	case 2: $o .= ' col-md-8 col-lg-9'; break;
        	case 3: $o .= ' col-md-6 col-md-push-3 col-lg-8 col-lg-push-2'; break;
	};
	$o .= ' content ' . CONTENT_CLASS;
	return $o;
}

/* ----- */

global $container_width, $container_segments, $sidebar_position, $band_class;

$logo_image = get_theme_mod('logo_image', false);
$logo_placement = intval( get_theme_mod('logo_placement', 0) );
$container_width = intval( get_theme_mod('container_width', 0) );
$container_segments = intval( get_theme_mod('container_segments', 0) );

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
// elseif ($container_width == 2) { $container_class .= '-custom'; }  // FIXME : Fix container-custom SCSS

$band_class = 'band ' . BAND_CLASS;
if ($container_segments != 0) {
	$band_class .= ' ' . $container_class;
}
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
<?php if ($container_segments == 0) {
	echo '<div class="' . $container_class . ' folio ' . FOLIO_CLASS . '">';
} else {
	echo '<div class="header">';
} ?>

<header class="section">
<div class="<?= $band_class ?> heading"><div class="row">
<?php
	switch ($logo_placement) {
        case 1:  // center
        	?><div class="col-xs-12"><?= bs4_heading($logo_placement) ?></div><?php
        	if ($head_a & 1 != 0) {
                	?><div class="col-xs-12 <?php echo HSPACE_CLASS; ?>"><div style="display:table;margin:0 auto"><?=
                	bs4_headernav('nav-header') ?></div></div><?php
        	}
        	if ($head_a & 2 != 0) {
                	?><div class="col-xs-12 text-center <?php echo HSPACE_CLASS; ?>"><?php dynamic_sidebar('sidebar-3') ?></div><?php
        	}
        	break;
        case 2:  // right
        	if ($head_a == 0) {
                	?><div class="col-xs-12"><?= bs4_heading($logo_placement) ?></div><?php
        	} else {
                	?><div class="col-xs-12 col-md-6 col-md-push-6"><?= bs4_heading($logo_placement) ?></div><?php
                	?><div class="col-xs-12 col-md-6 col-md-pull-6"><?php
                	if ($head_a === 3) echo '<div class="row"><div class="col-md-12">';  // nested row
                	if ($head_a & 1 != 0) echo bs4_headernav();
                	if ($head_a === 3) echo '</div><div class="col-md-12 ' . HSPACE_CLASS . '">';
                	if ($head_a & 2 != 0) dynamic_sidebar('sidebar-3');
                	if ($head_a === 3) echo '</div></div>';
                	?></div><?php
		}
            	break;
        default:  // left
        	if ($head_a == 0) {
                	?><div class="col-xs-12"><?= bs4_heading() ?></div><?php
        	} else {
                	?><div class="col-xs-12 col-md-6"><?= bs4_heading() ?></div><?php
                	?><div class="col-xs-12 col-md-6"><?php
                	if ($head_a === 3) echo '<div class="row"><div class="col-md-12">';  // nested row
                	if ($head_a & 1 != 0) echo bs4_headernav('clearfix pull-right');
                	if ($head_a === 3) echo '</div><div class="col-md-12 ' . HSPACE_CLASS . '">';
                	if ($head_a & 2 != 0) { echo '<div class="pull-right">'; dynamic_sidebar('sidebar-3'); echo '</div>'; }
                	if ($head_a === 3) echo '</div></div>';
                	?></div><?php
		}
        	break;
	}
?>
</div></div>
<?php get_template_part( 'navbar' ); ?>
</header>

<?php if ($container_segments != 0) { echo '</div><div class="main">'; } ?>

<main>
<div class="<?= $band_class ?>"><div class="row"><div id="content" class="<?= bs4_content_class($sidebar_position) ?>">
