<?php
/**
 * WP-Bootstrap4 functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 */

if ( !defined('THEMEPATH') )
	define('THEMEPATH', dirname(__FILE__).'/');

if ( !defined('DOTMIN') )
	define('DOTMIN', (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) ? '' : '.min');

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

// addon plugins
if (!bs4_get_option('block_plugins')) {
	define('BOOTSTRAP4_LOADS_PLUGINS', true);

	if ($handle = opendir(THEMEPATH.'plugins')) {
		while (false !== ($entry = readdir($handle))) {
        		if (!is_dir(THEMEPATH.'plugins/'.$entry)) {
        			include_once 'plugins/'.$entry;
		        }
		}
		closedir($handle);
	}
}

/**
 * return logo Image
 */
function bs4_get_logo_img($class = '') {
	$id = false;
	if (USE_WP45_LOGO) {  // NEW IN WP4.5
		$has_logo = has_custom_logo();
		if ($has_logo) $id = get_theme_mod( 'custom_logo' );
	} else
		$id = get_theme_mod( 'custom_logo', '' );

	$custom_logo = false;
	if ($id) {
		$custom_logo = wp_get_attachment_image_src( $id, 'full', false );
		if ($custom_logo !== false) $custom_logo = $custom_logo[0];
	}

	$custom_logo = apply_filters( 'bs4_get_logo_img_url', $custom_logo );

	if ( !empty($custom_logo) ) {
		$ws = get_theme_mod( 'logo_width', false );
		$wa = ctype_digit($ws) ? $ws : false;  if ($wa) $ws = false;
		$hs = get_theme_mod( 'logo_height', false );
		$ha = ctype_digit($hs) ? $hs : false;  if ($ha) $hs = false;

		$custom_logo = '<img src="' . $custom_logo . '"';
		if ($class != '') $custom_logo .= ' class="' . $class . '"';
		$custom_logo .= ' alt="' . get_bloginfo('name', 'display');
		$_d = get_bloginfo( 'description', 'display' );
		if ( $_d  ) $custom_logo .= ' - ' . $_d;
		$custom_logo .= '"';
		if ($wa) $custom_logo .= ' width="'.$wa.'"';
		if ($ha) $custom_logo .= ' height="'.$ha.'"';
		if ($ws || $hs) {
			$custom_logo .= '" style="';
			if ($ws) $custom_logo .= 'width:'.$ws.';';
			if ($hs) $custom_logo .= 'height:'.$hs.';';
			$custom_logo .= '"';
		}
		$custom_logo .= '>';
	}

	return $custom_logo = apply_filters( 'bs4_get_logo_img_link', $custom_logo );
}

/**
 * Return home location
 */
function bs4_home_url($path = '/') {
	return apply_filters( 'bs4_home_url', esc_url(home_url($path)), $path );
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

	// This theme uses wp_nav_menu()
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
	// don't support if child theme overides with 'bs4_get_logo_img_url' or
	// 'bs4_heading' filters
	if ( USE_WP45_LOGO && !apply_filters('bs4_get_logo_img_url', false) &&
		(false === apply_filters('bs4_heading', false)) )  // NEW IN WP4.5
		add_theme_support( 'custom-logo', array(
			'flex-width' => true,
			'flex-height' => true,
		) );  /* */
}
endif; // wp_bootstrap_setup

add_action( 'after_setup_theme', 'bs4_setup' );

/**
 * Get URI of style file, if exists in child, else parent
 */
function get_theme_file_uri($filename) {
	if (!is_child_theme()) {
		return get_template_directory_uri() . $filename;
	} else {
		$f = get_stylesheet_directory() . $filename;
		if (file_exists($f)) return get_stylesheet_directory_uri() . $filename;
		else return get_template_directory_uri() . $filename;
	}
}

/**
 * Enqueue scripts and styles.
 */
function bs4_scripts() {
	$t_ver = wp_get_theme()->version;

	// CSS

	$url = trim( bs4_get_option('bootstrap_css') );
	if (empty($url)) {
		$url = get_theme_file_uri( '/vendor/bootstrap/css/bootstrap' . DOTMIN . '.css' );
		$ver = BOOTSTRAP_VERSION;
	} else {
		$ver = NULL;
	}
	wp_enqueue_style( 'bootstrap', $url, array(), $ver );

	wp_enqueue_style(
		'bootstrap-fix',
		get_theme_file_uri( '/css/bootstrap-fix' . DOTMIN . '.css' ),
		array('bootstrap'),
		$t_ver );

	wp_enqueue_style(
		'bootstrap-pr',
		get_theme_file_uri( '/vendor/print-ready/css/bootstrap-pr' . DOTMIN . '.css' ),
		array('bootstrap'),
		BOOTSTRAP_PR_VERSION );

	@call_user_func('bs4_enqueue_style_i_'.bs4_icon_set(), DOTMIN);

	wp_enqueue_style(
		'wp-boostrap4',
		get_theme_file_uri( '/css/wp-bootstrap4' . DOTMIN . '.css' ),
		array( 'bootstrap' ),
		$t_ver );

	if (is_child_theme()) {
		wp_enqueue_style(
			'style-parent',
			get_template_directory_uri().'/style.css',
			array(),
			$t_ver );
		wp_enqueue_style(
			'style-child',
			get_stylesheet_uri(),
			array('style-parent'),
			$t_ver );
	} else {
		wp_enqueue_style(
			'style',
			get_stylesheet_uri(),
			array(),
			$t_ver );
	}

	// JS

	// remove WP jquery that relies on v1
	wp_deregister_script('jquery');

	// jQuery
	$url = trim( bs4_get_option('jquery_js') );
	if (empty($url)) {
		$url = get_theme_file_uri( '/vendor/jquery/js/jquery-' . JQUERY_VERSION . DOTMIN . '.js' );
		$ver = JQUERY_VERSION;
	} else {
		$ver = NULL;
	}
	wp_enqueue_script( 'jquery', $url, array(), $ver, true );

	// Tether
	$url = get_theme_file_uri( '/vendor/tether/js/tether' . DOTMIN . '.js' );
	$ver = TETHER_VERSION;
	wp_enqueue_script( 'tether', $url, array( 'jquery' ), $ver, true );

	// Easing
	$url = get_theme_file_uri( '/vendor/easing/js/easing' . DOTMIN . '.js' );
	$ver = EASING_VERSION;
	wp_enqueue_script( 'easing', $url, array( 'jquery' ), $ver, true );

	// Bootstrap
	$url = trim( bs4_get_option('bootstrap_js') );
	if (empty($url)) {
		$url = get_theme_file_uri( '/vendor/bootstrap/js/bootstrap' . DOTMIN . '.js' );
		$ver = BOOTSTRAP_VERSION;
	} else {
		$ver = NULL;
	}
	wp_enqueue_script( 'bootstrap', $url, array( 'jquery' ), $ver, true );

	// WP-Bootstrap4
	wp_enqueue_script(
		'wp-bootstrap4',
		get_theme_file_uri( '/js/wp-bootstrap4' . DOTMIN . '.js' ),
		array( 'jquery' ),
		false,
		true );

	// Equal Heights
	if ( bs4_get_option('equalheights') )
		wp_register_script(
			'match-height',
			get_theme_file_uri( '/vendor/match-height/js/matchHeight' . DOTMIN . '.js' ),
			array( 'jquery' ),
			MATCH_HEIGHT_VERSION,
			true );

	// Comment Reply
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
	register_sidebar( array(
		'name'          => 'Footer Bar Wide',
		'id'            => 'sidebar-8',
		'description'   => '',
		'before_widget' => '<aside class="widget widget-footer widget-wide %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<' . $tag . '>',
		'after_title'   => '</' . $tag . '>',
		) );

	if (!USE_ONEPAGE) {
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
}

add_action( 'widgets_init', 'bs4_widgets_init' );

/**
 * Place back to top button
 */
function bs4_back_to_top_button() {
?>
<a
	id="back-to-top"
	href="#top"
	class="btn btn-info back-to-top"
	role="button"
	title="Return to the top of page"
	data-toggle="tooltip"
	data-placement="left"><?php bs4_i('top'); ?></a>
<?php
}

add_action( 'wp_footer', 'bs4_back_to_top_button', 990 );


/**
 * OnePage addon ====================
 */
if ( USE_ONEPAGE )
	include_once 'onepage/op-functions.php';


/**
 * WooCommerce addon ====================
 */
if ( USE_WOOCOMMERCE )
	include_once 'woocommerce/wc-functions.php';


/* oef */
