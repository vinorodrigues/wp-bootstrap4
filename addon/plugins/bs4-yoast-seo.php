<?php
/*
Plugin Name: TS WP-Bootstrap4 + Yoast SEO Integration
Plugin URI: http://github.com/vinorodrigues/wp-bootstrap4
Description: Bootstrap 4 + Yoast SEO Integration
Version: 0.0.0
Author: Vino Rodrigues
Author URI: http://vinorodrigues.com
License: MIT License
License URI: http://opensource.org/licenses/mit-license.html
*/


include_once 'inc/plugin-lib.php';


// Tecsmith Options
function bs4_yoast_seo_admin_menu() {
	if (function_exists('add_tecsmith_item'))
		add_tecsmith_item( __('Bootstrap 4 + Yoast SEO Integration'), basename(__FILE__, '.php'), 4 );
}

add_action( 'admin_menu', 'bs4_yoast_seo_admin_menu' );


if ( function_exists('yoast_breadcrumb') ) :

	/**
	 * Get the creadcrumbs for later use
	 */
	function bs4_wpseo_breadcrumb_single_link($output, $link) {
		global $bs4_breadcrumbs;

		if (!isset($bs4_breadcrumbs)) $bs4_breadcrumbs = array();
		$bs4_breadcrumbs[] = $link;

		return $output;
	}


	/**
	 * Rewrite breadcrumb outpu to conform to Google Structured Data - RDFA
	 * @see: https://developers.google.com/structured-data/breadcrumbs#examples
	 */
	function bs4_wpseo_breadcrumb_output($output) {
		global $bs4_breadcrumbs;

		if (isset($bs4_breadcrumbs) && is_array($bs4_breadcrumbs)) {

			$output = '<ol class="breadcrumb" vocab="http://schema.org/" typeof="BreadcrumbList">';

			$cnt = count($bs4_breadcrumbs);
			$i = 0;
			foreach ($bs4_breadcrumbs as $link) {
				$i++;

				if ( ( isset( $link['url'] ) && ( is_string( $link['url'] ) && $link['url'] !== '' ) ) && ($cnt != $i) )
					$url = $link['url'];
				else
					$url = false;

				if ( isset( $link['text'] ) && ( is_string( $link['text'] ) && $link['text'] !== '' ) ) {
					$output .= '<li class="breadcrumb-item" property="itemListElement" typeof="ListItem"';
					if ($i == $cnt) $output .= ' class="active"';
					$output .= '>';

					if ($url) $output .= '<a property="item" typeof="WebPage" href="'.$url.'">';
					else $output .= '<span property="item">';

      					$output .= '<span property="name">'.$link['text'].'</span>';

      					if ($url) $output .= '</a>';
					else $output .= '</span>';

    					$output .= '<meta property="position" content="'.$i.'">';
  					$output .= '</li>';
				}
			}

			$output .= '</ol>';
		}

		return $output;
	}


	/**
	 *  WP-Bootstrap4 Breadcrumb, requires Yoast WPSEO plugin
	 */
	function bs4_breadcrumb() {
		add_filter('wpseo_breadcrumb_single_link', 'bs4_wpseo_breadcrumb_single_link', 1, 2);
		add_filter('wpseo_breadcrumb_output', 'bs4_wpseo_breadcrumb_output', 1);
		return yoast_breadcrumb();
	}

endif;

