<?php
/*
Plugin Name: TS WP-Bootstrap4 Component Shortcodes
Plugin URI: http://github.com/vinorodrigues/wp-bootstrap4
Description: Bootstrap 4 Component shortcodes for the WP-Bootstrap4 Theme
Version: 0.0.9
Author: Vino Rodrigues
Author URI: http://vinorodrigues.com
License: MIT License
License URI: http://opensource.org/licenses/mit-license.html
*/

include_once 'inc/plugin-lib.php';
include_once 'inc/buttons.php';
include_once 'inc/jumbotron.php';
include_once 'inc/tags.php';
include_once 'inc/alerts.php';
include_once 'inc/typography.php';
include_once 'inc/gallery.php';
include_once 'inc/carousel.php';

if ('fa' == bs4_icon_set()) @include_once 'inc/iconset-sc-fa.php';

// Deprecated stuff
include_once 'inc/legacy.php';
