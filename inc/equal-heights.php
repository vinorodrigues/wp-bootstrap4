<?php
/**
 * Usage of grids.js
 */

include_once 'lib-ts/raw-scripts.php';

function bs4_equal_heights( $what = false ) {
	static $_ts_equalheights_count = 0;  // was global

	if ($what) {
		$_ts_equalheights_count++;
		$h = str_replace( array('.', '#'), array('dot-', 'hash-'), $what );
		if ( ! wp_script_is( 'match-height', 'registered' ) )
			_doing_it_wrong(__FUNCTION__,
				'You need to <code>wp_register_script( "match-height", "matchHeight.js")</code> first',
				'0.2' );

		if ($_ts_equalheights_count <= 1)
			wp_enqueue_script( 'match-height' );
		ts_enqueue_script( 'match-height-' . $_ts_equalheights_count . '-' . $h,
			'jQuery(document).ready(function($) {' . PHP_EOL .
			'  $("' . $what . '").matchHeight(); ' . PHP_EOL .
			'});', 'match-height' );
	}
}
