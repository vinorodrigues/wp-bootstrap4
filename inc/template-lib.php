<?php
/**
 * Template library for this theme.
 *
 * These are mostly format and helper functions.
 */

include_once 'lib-ts/raw-scripts.php';

function inject_class_in_tag($tag, $class, $haystack) {

	// (1) find the tag
	$i = strpos($haystack, '<'.$tag);
	if ($i !== false) {
		// (2) find end of Tag
		$j = strpos($haystack, '>', $i+1);
		// (3) extract only the tag
		if ($j !== false) $the_tag = substr($haystack, $i, $j-$i+1);
		else $the_tag = substr($haystack, $i);

		// (4) find if it has a class
		$k = strpos($the_tag, 'class=');
		if ($k !== false) {
			// (5a) next should be the quote
			$n = substr($the_tag, $k+6, 1);
			$p = strpos($the_tag, $n, $k+7);
			// (6a) then insert the class name
			$haystack = substr_replace($haystack, ' '.$class, $i+$p, 0);
		} else {
			// (5b) find the end of the tag
			$p = strpos($the_tag, '>');
			// (6b) then insert class attribute and name
			$haystack = substr_replace($haystack, ' class="'.$class.'"', $i+$p, 0);
		}
	}
	// (7) done.
	return $haystack;
}

function bs4_icon_set() {
	global $bs4_singletons;
	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['icon_set'])) {
		$bs4_singletons['icon_set'] = bs4_get_option('icon_set');
		$_fn = get_template_directory() . '/inc/iconset-' . $bs4_singletons['icon_set'] . '.php';
		if (file_exists($_fn)) include_once $_fn;
	}

	return $bs4_singletons['icon_set'];
}

function get_bs4_i($name, $before = '', $after = '', $attribs = false) {
	$fn = 'get_bs4_icon_'.bs4_icon_set();
	if (function_exists($fn))
		return call_user_func($fn, $name, $before, $after, $attribs);
	else return '';
}

function bs4_i($name, $before = '', $after = '', $attribs = false) {
	echo get_bs4_i($name, $before, $after, $attribs);
}

function get_bs4_user_i($id_or_email = null, $before = '', $after = '', $attribs = false) {
	if (!empty($id_or_email) && get_option('show_avatars')) {
		$avatar = get_avatar($id_or_email, 32, '', '');
		if (!empty($avatar)) {
			$avatar = str_replace(
				array('height="32" width="32"', 'height=\'32\' width=\'32\''),
				'style="width:auto;height:1em"',
				$avatar);
			$avatar = str_replace(
				array('class="', 'class=\''),
				array('class="'.AVATAR_CLASS.' ', 'class=\''.AVATAR_CLASS.' '),
				$avatar);
			return $before . $avatar . $after;
		}
	}
	return get_bs4_i('user', $before, $after, $attribs);
}

/**
 */
function bs4_navbar_color_class($navbar_color, $custom_color_class = false) {
	switch ($navbar_color) {
		case 1: $ret = 'inverse'; break;
		case 2: $ret = 'faded'; break;
		case 3: $ret = 'default'; break;
		case 4: $ret = 'transparent'; break;
		case 5:
			$ret = 'custom';
			if ($custom_color_class)
				$ret .= '-' . $custom_color_class;
			break;
		default:
			$ret = 'primary';
	}
	return 'bg-' . $ret;
}

/**
 */
function bs4_navbar_shading_class($navbar_shading) {
	if ($navbar_shading) $ret = 'light';
	else $ret = 'inverse';
	return 'navbar-' . $ret;
}

function bs4_navbar_toggler_class($navbar_toggler) {
	switch ($navbar_toggler) {
		case 1: $ret = '-sm'; break;
		case 2: $ret = '-md'; break;
		case 3: $ret = '-lg'; break;
		case 4: $ret = '-xl'; break;
		default: $ret = '';
	}
	return 'navbar-toggleable' . $ret;
}

function bs4_navbar_tog_pos_class($navbar_tog_pos) {
	if ($navbar_tog_pos == 0) return '';
	else return 'navbar-toggler-right';
}

/**
 *  Rehash of get_the_category_list()
 */
function get_bs4_category_list( $post_id = false ) {
	if ( ! is_object_in_taxonomy( get_post_type( $post_id ), 'category' ) )
		return false;

	$categories = get_the_category( $post_id );
	if ( empty( $categories ) ) return false;

	$cats = array();
	foreach ( $categories as $category ) {
		$cats[] = $category->term_id;
	}
	return $cats;
}

function get_bs4_tag_list( $post_id = false ) {
	if (!$post_id) $post_id = get_the_ID();
	$terms = get_the_terms( $post_id, 'post_tag');
	if ( empty( $terms ) ) return false;

	$tags = array();
	if ($terms)
		foreach ($terms as $term) {
			$tags[] = $term->term_id;
		}
	return $tags;
}

function __bs4_get_cat_cnt_limit($minmax = 'max') {
	global $bs4_singletons;
	if (!isset($bs4_singletons)) $bs4_singletons = array();

	$singleton = 'category-' . $minmax;
	if (!isset($bs4_singletons[$singleton])) {
		$bs4_singletons[$singleton] = 0;
		$args = array( 'parent' => 0, 'hide_empty' => 1, 'orderby' => 'count', 'number' => 1 );
		if ('min' != $minmax) $args['order'] = 'DESC';
		$cats = get_categories( $args );
		if (is_array($cats)) {
			$cats = array_values($cats)[0];
		 	if (is_object($cats))
				$bs4_singletons[$singleton] = intval( $cats->count );
		}
	}
	return $bs4_singletons[$singleton];
}

function __bs4_get_cat_cnt_min() {
	return __bs4_get_cat_cnt_limit('min');
}

function __bs4_get_cat_cnt_max() {
	return __bs4_get_cat_cnt_limit('max');
}

function __bs4_get_tag_cnt_limit($minmax = 'max') {
	global $bs4_singletons;
	if (!isset($bs4_singletons)) $bs4_singletons = array();

	$singleton = 'tag-' . $minmax;
	if (!isset($bs4_singletons[$singleton])) {
		$args = array( 'parent' => 0, 'hide_empty' => 1, 'orderby' => 'count', 'number' => 1 );
		if ('min' != $minmax) $args['order'] = 'DESC';
		$tags = get_tags( $args );
		if (is_array($tags) && is_object($tags[0]))
			$bs4_singletons[$singleton] = intval( $tags[0]->count );
		else
			$bs4_singletons[$singleton] = 0;
	}
	return $bs4_singletons[$singleton];
}

function __bs4_get_tag_cnt_min() {
	return __bs4_get_tag_cnt_limit('min');
}

function __bs4_get_tag_cnt_max() {
	return __bs4_get_tag_cnt_limit('max');
}

/**
 * Get Post meta data
 */
function get_bs4_post_meta() {
	if ( 'post' != get_post_type() ) return '';
	$o = '';

	$o .= '<time datetime="' . get_the_time('c') . '" class="meta-time meta-item">';
	$o .= get_bs4_i('calendar', '', ' ', 'title="Posted on"');
	$o .= get_the_time(get_option('date_format'));
	$o .= '</time>';

	$o .= ' <span class="meta-author meta-item">';
	$o .= '<a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">';
	$o .= get_bs4_user_i(get_the_author_meta('ID'), '', ' ', 'title="Posted by"');
	$o .= get_the_author() . '</a>';
	$o .= '</span>';

	if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) :
		$o .= '<span class="meta-comments meta-item hidden-print">';
		ob_start();
		comments_popup_link(
			get_bs4_i('commenta') . '<span class="hidden-sm-down"> Leave a comment</span>',
			get_bs4_i('comment') . ' 1<span class="hidden-sm-down"> comment</span>',
			get_bs4_i('comments') . ' %<span class="hidden-sm-down"> comments</span>',
			'',
			get_bs4_i('commentx') . '<span class="hidden-sm-down"> No comments</span>'  // never used
			);
		$o .= ob_get_clean();
		$o .= '</span>';
	endif;

	$categories = get_bs4_category_list();
	if (!empty($categories)) {
		$o .= ' <span class="meta-categories meta-item">';
		$o .= get_bs4_i('category', '', ' ', 'title="Categories"');
		$i = count($categories);
		$max = __bs4_get_cat_cnt_max() - __bs4_get_cat_cnt_min() + 1;
		foreach ($categories as $id) {
			$i--;

			$cat = get_category($id);
			$per = intval(($cat->count / $max) * 100);
			if ($per > 75) $cls = 'danger top-25';
			elseif ($per > 50) $cls = 'warning top-50';
			elseif ($per > 25) $cls = 'success top-75';
			else $cls = 'info top-100';

			$o .= '<a href="' . get_category_link($id) . '"';
			if (!empty($cat->description)) $o .= ' title=" ' . $cat->description . '"';
			$o .= ' class="badge badge-' . $cls . '">' . $cat->name . '</a>';
			if ($i != 0) $o .= '<span class="sep"> </span>';
		}
		$o .= '</span>';
	}

	$tags = get_bs4_tag_list();
	if (!empty($tags)) {
		$o .= ' <span class="meta-tags meta-item">';
		$o .= get_bs4_i('tag', '', ' ', 'title="Tags"');
		$i = count($tags);
		$max = __bs4_get_tag_cnt_max() - __bs4_get_tag_cnt_min() + 1;
		foreach ($tags as $id) {
			$i--;

			$tag = get_tag($id);

			$per = intval(($tag->count / $max) * 100);
			if ($per > 75) $cls = 'danger top-25';
			elseif ($per > 50) $cls = 'warning top-50';
			elseif ($per > 25) $cls = 'success top-75';
			else $cls = 'info top-100';

			$o .= '<a href="' . get_tag_link($id) . '"';
			if (!empty($tag->description)) $o .= ' title=" ' . $tag->description . '"';
			$o .= ' class="badge badge-pill badge-' . $cls . ' tag-link-' . $id . '">' . $tag->name . '</a>';
			if ($i != 0) $o .= '<span class="sep"> </span>';
		}
		$o .= '</span>';
	}

	return $o;
}

/**
 * Echo Post meta data
 */
function bs4_post_meta() {
	echo get_bs4_post_meta();
}

/**
 * Count how many categories
 */
function get_category_count() {
	global $bs4_singletons;
	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (!isset($bs4_singletons['category_count']))  // only need to do this once
		$bs4_singletons['category_count'] = count( get_categories( array('parent' => 0, 'hide_empty' => 0) ) );
	return $bs4_singletons['category_count'];
}

/**
 * Echo page pagination
 */
function bs4_content_pager() {
	global $wp_query;

	$_n = false;
	$_p = false;

	if ( is_single() ) {

		// navigation links for single posts
		$in_same_cat = get_category_count() > 1;
		$_n = get_next_post_link( '%link ', 'Next'.get_bs4_i('raquo', ' '), $in_same_cat );
		$_p = get_previous_post_link( '%link ', get_bs4_i('laquo', '', ' ').'Previous', $in_same_cat );

	} elseif ( ($wp_query->max_num_pages > 1) && ( is_home() || is_archive() || is_search() ) ) {

		// navigation links for home, blog,  archive, and search pages
		$_n = get_next_posts_link( get_bs4_i('laquo', '', ' ').'Older' );
		$_p = get_previous_posts_link( 'Newer'.get_bs4_i('raquo', ' ') );

	}

	if ($_n || $_p) {
		$_n = inject_class_in_tag('a', 'btn btn-outline-info', $_n);
		$_p = inject_class_in_tag('a', 'btn btn-outline-info', $_p);

		?><nav class="centered hidden-print"><div class="pager"><?php

		if ( is_single() ) {
			if ($_p) echo $_p;
			if ($_p && $_n) echo ' ';
			if ($_n) echo $_n;
		} else {
			if ($_n) echo $_n;
			if ($_p && $_n) echo ' ';
			if ($_p) echo $_p;
		}
		?></div></nav><?php
	}
}

function bs4_link_pages() {
	wp_link_pages_2( array(
		'before'           => '<nav class="centered"><ul class="pagination">',
		'after'            => '</ul></center></nav>',
		'nextpagelink'     => get_bs4_i('raquo'),
		'previouspagelink' => get_bs4_i('laquo'),
		'nolink'           => '<a>%</a>',
		'next_or_number'   => 'both',
		) );
}

/* function validate_gravatar($email) {
	// Craft a potential url and test its headers
	$hash = md5(strtolower(trim($email)));
	$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
	$headers = @get_headers($uri);
	if (!preg_match("|200|", $headers[0])) return false;
	else return true;
} /* */

function bs4_inject_feature($fn, $html) {
	global $band_class;

	$fi = 'fn_' . str_replace('-', '_', $fn);

	$html = '<div class="' . $band_class . ' feature"><div class="row"><div class="col-12">' .
		$html .
		'</div></div></div>';

	$js =
		'(function() {' . PHP_EOL .
		'  function ' . $fi . '(){' . PHP_EOL .
		'    $("#feature").html("' .
		str_replace(array('"', "\n", "\f"), array('\"', ' ', ''), $html) .
		'");' . PHP_EOL .
		'  }' . PHP_EOL .
		'  $(document).ready(' . $fi . ');' . PHP_EOL .
		'})(jQuery);';
	ts_enqueue_script(str_replace('_', '-', $fn), $js);

}

function has_bs4_footer_bar() {
	$ret = false;
	for ($i = 1; $i <= 5; $i++) {
		if ( is_active_sidebar( 'sidebar-' . ($i+3) ) ) {
			$ret = true;
			break;
		}
	}
	return $ret;
}
