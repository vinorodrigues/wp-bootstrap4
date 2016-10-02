<?php

function var_dump_ret($mixed = null) {
	ob_start();
	var_dump($mixed);
	$content = ob_get_clean();
	return htmlspecialchars($content, ENT_QUOTES);
}

function var_dump_pre($mixed = null, $name = false) {
	echo '<pre>';
	if ($name !== false)
		echo '<span style="color:#F00">' . $name . '</span><span style="color:#0F0"> = </span>';
	echo '<span style="color:#00F">' . var_dump_ret($mixed) . '</span>';
	echo '</pre>';
}

function bs4_get_shortcode_class($atts, $class = '') {
	if (is_array($atts) && array_key_exists('class', $atts) && !empty($atts['class']))
		$class = empty($class) ? $atts['class'] : $class . ' ' . $atts['class'];

	$output = '';

	if (is_array($atts) && array_key_exists('id', $atts) && !empty($atts['id']))
		$output .= ' id="' . $atts['id'] . '"';

	if (!empty($class))
		$output .= ' class="' . $class . '"';

	return $output;
}

function bs4_filter_booleans($list, $bools) {
	foreach ($list as $key => $value)
		if (in_array($key, $bools))
			$list[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
	return $list;
}

function bs4_shortcode_atts( $default_pairs, $atts, $shortcode = '') {
	if (is_array($atts)) {
		foreach($atts as $name => $value )
			if (is_numeric($name)) {
				$atts[$value] = true;
				unset($atts[$name]);
				continue;
			}
	} elseif (!empty($atts)) {
		$atts = array($atts => true);  // $atts was a string, change to array
	} else {
		$atts = array();
	}

	// fix contectual types inline;  "info" or "info='1'" becomes "type='info'"
	if (array_key_exists('type', $default_pairs) && (!array_key_exists('type', $atts))) {
		$types = array('default', 'primary', 'success', 'info', 'warning', 'danger');
		foreach ($types as $type) {
			if (isset($atts[$type]) && filter_var($atts[$type], FILTER_VALIDATE_BOOLEAN) === true) {
				$atts['type'] = $type;
				break;
			}
		}
	}

	return shortcode_atts($default_pairs, $atts, $shortcode);
}

/**
 * Cleaner shortcodes
 */
function bs4_add_shortcode($tag, $func, $do_clean = true) {
	global $bs4_singletons;

	add_shortcode($tag, $func);

	if ($do_clean) {
		if (!isset($bs4_singletons))
			$bs4_singletons = array();
		if (!isset($bs4_singletons['shortcodes']))
			$bs4_singletons['shortcodes'] = array();
		if (!isset($bs4_singletons['shortcodes'][$tag]))
			$bs4_singletons['shortcodes'][] = $tag;
	}
}

/**
 * Cleaner shortcodes - fix know issue with wpautop
 * @see: https://paulund.co.uk/remove-line-breaks-in-shortcodes
 */
/*
function bs4_shortcode_unautop( $content ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons) || !isset($bs4_singletons['shortcodes']) ||
		!is_array($bs4_singletons['shortcodes']) ) {
		return $content;
	}

	$tagregexp = join( '|', array_map( 'preg_quote', array_values( $bs4_singletons['shortcodes'] ) ) );

	$pattern =
		  '/'
		. '<p>'                              // Opening paragraph
		. '\\s*+'                            // Optional leading whitespace
		. '('                                // 1: The shortcode
		.     '\\['                          // Opening bracket
		.     "($tagregexp)"                 // 2: Shortcode name
		.     '(?![\\w-])'                   // Not followed by word character or hyphen
		                                     // Unroll the loop: Inside the opening shortcode tag
		.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
		.     '(?:'
		.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
		.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
		.     ')*?'
		.     '(?:'
		.         '\\/\\]'                   // Self closing tag and closing bracket
		.     '|'
		.         '\\]'                      // Closing bracket
		.         '(?:'                      // Unroll the loop: Optionally, anything between the opening and closing shortcode tags
		.             '[^\\[]*+'             // Not an opening bracket
		.             '(?:'
		.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
		.                 '[^\\[]*+'         // Not an opening bracket
		.             ')*+'
		.             '\\[\\/\\2\\]'         // Closing shortcode tag
		.         ')?'
		.     ')'
		. ')'
		. '\\s*+'                            // optional trailing whitespace
		. '<\\/p>'                           // closing paragraph
		. '/s';

	return preg_replace( $pattern, '$1', $content );
}

remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop', 99 );
add_filter( 'the_content', 'bs4_shortcode_unautop', 100 );

remove_filter( 'the_excerpt', 'wpautop' );
add_filter( 'the_excerpt', 'wpautop', 99 );
add_filter( 'the_excerpt', 'bs4_shortcode_unautop', 100 );
*/


/* --------------- in case raw-scripts.php is not included --------------- */

if (!function_exists('ts_enqueue_script')) :

	function __bs4_output_enqueued_scripts() {
		global $__bs4_enqueued_scripts;

		if (isset($__bs4_enqueued_scripts)) {
			echo '<script type="text/javascript">' . PHP_EOL;
			echo $__bs4_enqueued_scripts;
			echo '</script>' . PHP_EOL;
		}
	}

	function ts_enqueue_script($comment, $js) {
		global $__bs4_enqueued_scripts;

		if (!isset($__bs4_enqueued_scripts)) {
			$__bs4_enqueued_scripts = '';
			add_action( 'wp_footer', '__bs4_output_enqueued_scripts', 999 );
		}

		$__bs4_enqueued_scripts .= '/* ' . $comment . ' */' . PHP_EOL;
		$__bs4_enqueued_scripts .= $js . PHP_EOL;
	}

endif;
