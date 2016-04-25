<?php
/**
 * Facilitate adding raw CSS to the header of a Wordpress rendering
 *
 * @author Vino Rodrigues
 * @package ts-raw-styles
 * @version 1.2
 * @since WordPress 3.4
*/

if ( ! defined( 'TS_RAW_STYLES' ) ) :
define( 'TS_RAW_STYLES', '1.2' );

global $ts_styles;

class TS_Styles extends WP_Dependencies {

	public $media;

	function __construct($m = 'all') {
		$this->media = $m;
	}

	function do_items( $handles = false, $group = false ) {
		// echo '<!-- TS_Styles -->' . PHP_EOL;
		echo "<style type=\"text/css\" media=\"" . $this->media . "\">\n";
		parent::do_items($handles, $group);
		echo "</style>\n";
	}

	function do_item( $handle, $group = false ) {
		if ( !parent::do_item($handle) )
			return false;

		echo "\t/* $handle */\n";
		echo "\t" . $this->registered[$handle]->src . "\n";
	}

}

function ts_print_styles() {
	global $ts_styles;

	if ( ! is_array( $ts_styles ) ) return;

	foreach ($ts_styles as $m => $m_style)
		if ( is_a( $m_style, 'TS_Styles' ) )
			$m_style->do_items(false);
}

function _ts_check_ts_styles( $media = false ) {
	global $ts_styles;
	if ( ! is_array($ts_styles) ) {
		$ts_styles = array();
		add_action( 'wp_head', 'ts_print_styles', 972 );
	}

	if ( $media && ( (!isset($ts_styles[$media])) || (!is_a( $ts_styles[$media], 'TS_Styles' ) ) ) )
		$ts_styles[$media] = new TS_Styles($media);

	return $ts_styles;
}

function ts_register_style( $handle, $src, $deps = array(), $media = 'all' ) {
	$ts_styles = _ts_check_ts_styles($media);
	if ($media)
		$ts_styles[$media]->add( $handle, $src, $deps, false );
}

function ts_deregister_style( $handle, $media = false ) {
	$ts_styles = _ts_check_ts_styles();
	if (!$media) {
		foreach ($ts_styles as $m => $m_style)
			$m_style->remove( $handle );
	} else {
		$ts_styles[$media]->remove( $handle );
	}
}

function ts_enqueue_style( $handle, $src = false, $deps = array(), $media = 'all' ) {
	$ts_styles = _ts_check_ts_styles($media);
	if ( $media ) {
		if ( $src ) {
			$_handle = explode('?', $handle);
			$ts_styles[$media]->add( $_handle[0], $src, $deps, false );
		}
		$ts_styles[$media]->enqueue( $handle );
	}
}

function ts_dequeue_style( $handle, $media = false ) {
	$ts_styles = _ts_check_ts_styles();
	if (!$media) {
		foreach ($ts_styles as $m => $m_style)
			$m_style->dequeue( $handle );
	} else {
		$ts_styles[$media]->dequeue( $handle );
	}
}

endif;  // TS_RAW_STYLES
