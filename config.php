<?php
/**
 * Configuration file
 */

define( 'BOOTSTRAP_VERSION', '4.0.0-alpha.5' );  // Bootstrap Version - @see http://v4-alpha.getbootstrap.com/
define( 'JQUERY_VERSION', '3.1.1' );  // JQuery Version - @see http://jquery.com
define( 'TETHER_VERSION', '1.4.0' );  // Tether Version - @see http://tether.io
define( 'EASING_VERSION', '1.3' );  // Easing Version - @see http://gsgd.co.uk/sandbox/jquery/easing
define( 'FONTAWESOME_VERSION', '4.7.0' );  // Fontawesome version - @see http://fontawesome.io
define( 'BOOTSTRAP_PR_VERSION', '0.0.1' );

define( 'AVATAR_CLASS', 'rounded-circle' );

define( 'POST_THUMBNAIL_X', 96 );
define( 'POST_THUMBNAIL_Y', 96 );
define( 'POST_THUMBNAIL_CLASS', 'img-thumbnail' );

define( 'FEATURED_IMAGE_X', 1156 );
define( 'FEATURED_IMAGE_Y', 289 );
define( 'FEATURED_IMAGE_CLASS', 'rounded' );

define( 'USE_WP45_LOGO', function_exists('get_custom_logo') );

define( 'USE_ONEPAGE', true );
define( 'USE_WOOCOMMERCE', file_exists(trailingslashit(dirname(__FILE__)).'woocommerce/wc-functions.php') );
