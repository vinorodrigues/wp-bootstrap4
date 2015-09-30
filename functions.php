<?php
/**
 * WP-Bootstrap4 functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 */

include_once 'bs4-config.php';
include_once 'inc/template-tags.php';
include_once 'inc/template-lib.php';
include_once 'inc/fix-post-template.php';
include_once 'inc/fix-comment-template.php';
include_once 'inc/fix-general-template.php';
include_once 'inc/fix-link-template.php';

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
	add_theme_support( 'post-thumbnails' );

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
		// 'search-form',
		// 'comment-form',
		// 'comment-list',
		// 'gallery',
		// 'caption',
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

	include get_template_directory() . '/inc/customizer.php';

}
endif; // wp_bootstrap_setup
add_action( 'after_setup_theme', 'bs4_setup' );

/**
 * Enqueue scripts and styles.
 */
function bs4_scripts() {
	$min = ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) ? '' : '.min';

	// CSS

	$url = trim( get_theme_mod('bootstrap_css', false) );
	if (empty($url)) {
		$url = get_stylesheet_directory_uri() . '/css/bootstrap' . $min . '.css';
		$ver = BS_VERSION;
	} else {
		$ver = NULL;
	}
	wp_enqueue_style( 'bootstrap', $url, array(), $ver );

	call_user_func('bs4_enqueue_style_i_'.bs4_icon_set(), $min);

	// XXX: Load parent style if child, with customise option to do so

	wp_enqueue_style( 'style', get_stylesheet_uri() );

	// JS

	wp_deregister_script('jquery');  // remove WP jquery that relies on v1

	$url = trim( get_theme_mod('jquery_js', false) );
	if (empty($url)) {
		$url = get_stylesheet_directory_uri() . '/js/jquery' . $min . '.js';
		$ver = JQ_VERSION;
	} else {
		$ver = NULL;
	}
	wp_enqueue_script( 'jquery', $url, array(), $ver, true );

	$url = trim( get_theme_mod('bootstrap_js', false) );
	if (empty($url)) {
		$url = get_stylesheet_directory_uri() . '/js/bootstrap' . $min . '.js';
		$ver = BS_VERSION;
	} else {
		$ver = NULL;
	}
	wp_enqueue_script( 'bootstrap', $url, array( 'jquery' ), $ver, true );

	/* wp_register_script(
        'equalheights',
        get_template_directory_uri() . '/js/equalheights' . $min . '.js',
        array( 'jquery-core' ),
        false,
        true
        );  /* */

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'bs4_scripts' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bs4_widgets_init() {
	$tag = 'h4';

	register_sidebar( array(
		'name'          => 'Primary Sidebar',
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside class="%2$s ' . WIDGET_CLASS . '">',
		'after_widget'  => '</aside>',
		'before_title'  => '<' . $tag . '>',
		'after_title'   => '</' . $tag . '>',
        	) );

	register_sidebar( array(
        	'name'          => 'Secondary Sidebar',
        	'id'            => 'sidebar-2',
		'description'   => '',
		'before_widget' => '<aside class="%2$s ' . WIDGET_CLASS . '">',
		'after_widget'  => '</aside>',
		'before_title'  => '<' . $tag . '>',
		'after_title'   => '</' . $tag . '>',
        	) );

	register_sidebar( array(
        	'name'          => 'Header Ancillary',
        	'id'            => 'sidebar-3',
		'description'   => 'Placed besides the Site Title or Site Logo',
		'before_widget' => '<aside class="%2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<' . $tag . ' class="invisible" hidden>',
		'after_title'   => '</' . $tag . '>',
        	) );

	for ($i = 1; $i <= 4; $i++) {
        	register_sidebar( array(
        		'name'          => 'Footer Bar ' . $i,
        		'id'            => 'sidebar-' . ($i+3),
        		'description'   => '',
        		'before_widget' => '<aside class="%2$s ' . WIDGET_CLASS . '">',
        		'after_widget'  => '</aside>',
        		'before_title'  => '<' . $tag . '>',
        		'after_title'   => '</' . $tag . '>',
			) );
    	}
}
add_action( 'widgets_init', 'bs4_widgets_init' );
