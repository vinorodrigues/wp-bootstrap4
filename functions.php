<?php
/**
 * WP-Bootstrap4 functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 */

// config
include_once 'config.php';
include_once 'inc/options.php';
include_once 'inc/customizer.php';
// general API
include_once 'inc/lib-ts/raw-scripts.php';
include_once 'inc/lib-ts/raw-styles.php';
include_once 'inc/lib-ts/opt-common.php';
// template includes
include_once 'inc/template-tags.php';
include_once 'inc/template-lib.php';
include_once 'inc/custom-header.php';
include_once 'inc/custom-background.php';
include_once 'inc/equal-heights.php';
// Wordpress fix-ups
include_once 'inc/fix-post-template.php';
include_once 'inc/fix-comment-template.php';
include_once 'inc/fix-general-template.php';
include_once 'inc/fix-link-template.php';
include_once 'inc/fix-tag-class.php';
// plugins
if (!bs4_get_option('block_plugins')) {
	include_once 'addon/inc/plugin-lib.php';
	include_once 'addon/bs4-layout.php';
	include_once 'addon/bs4-components.php';
	include_once 'addon/bs4-widgets.php';
	include_once 'addon/bs4-yoast-seo.php';
}


if ( ! function_exists( 'bs4_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bs4_setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );
	set_post_thumbnail_size( POST_THUMBNAIL_X, POST_THUMBNAIL_Y, true );
	add_image_size( 'featured-image', FEATURED_IMAGE_X, FEATURED_IMAGE_Y, true);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => 'Primary Menu',
		'header'  => 'Header Menu',
		'footer'  => 'Footer Menu',
		) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		// 'gallery',
		'caption',
		) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		// 'aside',
		// 'image',
		// 'video',
		// 'quote',
		// 'link',
		) );

	/* WP4.5 Cutom Logo */
	if (USE_WP45_LOGO)  // NEW IN WP4.5
		add_theme_support( 'custom-logo', array(
			'flex-width' => true,
			'flex-height' => true,
		) );  /* */
}
endif; // wp_bootstrap_setup

add_action( 'after_setup_theme', 'bs4_setup' );

/**
 * Enqueue scripts and styles.
 */
function bs4_scripts() {
	$min = ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) ? '' : '.min';

	// CSS

	$url = trim( bs4_get_option('bootstrap_css') );
	if (empty($url)) {
		$url = get_stylesheet_directory_uri() . '/css/bootstrap' . $min . '.css';
		$ver = BOOTSTRAP_VERSION;
	} else {
		$ver = NULL;
	}
	wp_enqueue_style( 'bootstrap', $url, array(), $ver );

	wp_enqueue_style(
		'bootstrap-fix',
		get_stylesheet_directory_uri() . '/css/bootstrap-fix.css',
		array('bootstrap'),
		false );

	wp_enqueue_style(
		'bootstrap-pr',
		get_stylesheet_directory_uri() . '/css/bootstrap-pr' . $min . '.css',
		array('bootstrap'),
		false );

	@call_user_func('bs4_enqueue_style_i_'.bs4_icon_set(), $min);

	wp_enqueue_style(
		'wp-boostrap4',
		get_stylesheet_directory_uri() . '/css/wp-bootstrap4.css',
		array( 'bootstrap' ),
		false );

	wp_enqueue_style(  // This theme or its child's style.css
		'style',
		get_stylesheet_uri() );

	// JS

	wp_deregister_script('jquery');  // remove WP jquery that relies on v1

	$url = trim( bs4_get_option('jquery_js') );
	if (empty($url)) {
		$url = get_stylesheet_directory_uri() . '/js/jquery-' . JQUERY_VERSION . $min . '.js';
		$ver = JQUERY_VERSION;
	} else {
		$ver = NULL;
	}
	wp_enqueue_script( 'jquery', $url, array(), $ver, true );

	$url = trim( bs4_get_option('tether_js') );
	if (empty($url)) {
		$url = get_stylesheet_directory_uri() . '/js/tether' . $min . '.js';
		$ver = TETHER_VERSION;
	} else {
		$ver = NULL;
	}
	wp_enqueue_script( 'tether', $url, array( 'jquery' ), $ver, true );

	$url = trim( bs4_get_option('bootstrap_js') );
	if (empty($url)) {
		$url = get_stylesheet_directory_uri() . '/js/bootstrap' . $min . '.js';
		$ver = BOOTSTRAP_VERSION;
	} else {
		$ver = NULL;
	}
	wp_enqueue_script( 'bootstrap', $url, array( 'jquery' ), $ver, true );

	if ( bs4_get_option('equalheights') )
		wp_register_script(
			'equalheights',
			get_template_directory_uri() . '/js/grids' . $min . '.js',
			array( 'jquery' ),
			false,
			true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Raw JS

	/* ts_enqueue_script(
		'wp-bootstrap4-tooltips',

		'(function($) {' . PHP_EOL .
		'  $(document).ready(function(){' . PHP_EOL .
		// '    $(\'a[href]\').tooltip();' . PHP_EOL .
		// '    $(\'abbr\').tooltip();' . PHP_EOL .
		// '    $(\'acronym\').tooltip();' . PHP_EOL .
		'    $(\'[data-toggle="tooltip"]\').tooltip();' . PHP_EOL .
		'  });' . PHP_EOL .
		'})(jQuery);' );  /* */
}

add_action( 'wp_enqueue_scripts', 'bs4_scripts' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bs4_widgets_init() {
	global $container_segments;

	$tag = 'h4';

	register_sidebar( array(
		'name'          => 'Primary Sidebar',
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside class="widget widget-sidebar %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<' . $tag . '>',
		'after_title'   => '</' . $tag . '>',
		) );

	register_sidebar( array(
		'name'          => 'Secondary Sidebar',
		'id'            => 'sidebar-2',
		'description'   => '',
		'before_widget' => '<aside class="widget widget-sidebar %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<' . $tag . '>',
		'after_title'   => '</' . $tag . '>',
		) );

	register_sidebar( array(
		'name'          => 'Header Ancillary',
		'id'            => 'sidebar-3',
		'description'   => 'Placed besides the Site Title or Site Logo',
		'before_widget' => '<aside class="widget widget-header h-i %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<' . $tag . ' class="hidden-xs-up" hidden>',
		'after_title'   => '</' . $tag . '>',
		) );

	for ($i = 1; $i <= 4; $i++) {
		register_sidebar( array(
			'name'          => 'Footer Bar ' . $i,
			'id'            => 'sidebar-' . ($i+3),
			'description'   => '',
			'before_widget' => '<aside class="widget widget-footer %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<' . $tag . '>',
			'after_title'   => '</' . $tag . '>',
			) );
	}

	$cls = 'widget-home';
	if (get_theme_mod('container_segments', 0) != 0) $cls .= ' band';
	register_sidebar( array(
		'name'          => 'Home Page Only',
		'id'            => 'sidebar-0',
		'description'   => '',
		'before_widget' => '<article class="widget '.$cls.' %2$s">',
		'after_widget'  => '</article>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
		) );
}

add_action( 'widgets_init', 'bs4_widgets_init' );


/* oef */
