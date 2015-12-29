<?php
/**
 * Usage of equalheights.js
 */

include_once 'raw-scripts.php';

function bs4_equal_heights( $what = false, $interval = 100 ) {
	static $_ts_equalheights_count = 0;  // was global

	if ($what) {
		$_ts_equalheights_count++;
		$f = 'equalHeights_' . $_ts_equalheights_count;
		$t = 'resizeTimer_' . $_ts_equalheights_count;
		$h = str_replace( array('.', '#'), array('dot-', 'hash-'), $what );
		if ( ! wp_script_is( 'equalheights', 'registered' ) )
			_doing_it_wrong(__FUNCTION__,
				'You need to <code>wp_register_script( "equalheights", "equalheights.js")</code> first',
				'0.2');

		if ($_ts_equalheights_count <= 1)
			wp_enqueue_script( 'equalheights' );
		ts_enqueue_script( 'equalheights-' . $h,
			'(function($) {' . "\n" .
			'  function ' . $f . '() { $("' . $what . '").equalHeights(); }' . "\n" .
			'  var ' . $t . ' = null;' . "\n" .
			'  function ' . $f . '_timed() {'  ."\n" .
			'    if (' . $t . ') clearTimeout(' . $t . ');' . "\n" .
			'    ' . $t . ' = setTimeout(' . $f . ', ' . intval($interval) . ');' . "\n" .
			'  }' . "\n" .
			'  $(document).ready(' . $f . '_timed);' . "\n" .
			'  $(window).bind("resize", ' . $f . '_timed);' . "\n" .
			'})(jQuery);', 'equalheights' );
	}
}
