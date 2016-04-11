<?php
/**
 * WP Core Hacks - remove "tag-" from post class names
 */

function bs4_tag_class_fix($classes) {
	$ret = array();
	foreach ($classes as $term_class) {
		if (strncasecmp('tag-', $term_class, 4) == 0) {
			$ret[] = 't-' . substr($term_class, 4);
		} else {
			if ('tag' != $term_class) $ret[] = $term_class;
		}
	}
	return $ret;
}
add_filter( 'body_class', 'bs4_tag_class_fix', 99, 1 );
add_filter( 'post_class', 'bs4_tag_class_fix', 99, 1 );

function bs4_tag_cloud_fix($tags_data) {
	$ret = array();
	foreach ($tags_data as $tags_datum) {
		if (strncasecmp('tag-', $tags_datum['class'], 4) == 0)
			$tags_datum['class'] = 't-' . substr($tags_datum['class'], 4);
		$ret[] = $tags_datum;
	}
	return $ret;
}
add_filter( 'wp_generate_tag_cloud_data', 'bs4_tag_cloud_fix', 99, 1 );
