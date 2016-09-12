<?php

global $onepage_items, $onepage_top_id, $wp_query,
	$band_class, $container_segments, $container_class;

include_once get_template_directory().'/inc/lib-ts/opt-common.php';

/**
 */
function bs4_onepage_wp_nav_menu($content, $class) {
	global $onepage_items, $onepage_top_id;

	$out = '<ul class="' . $class . '">';

	$cnt = count($onepage_items);
	for ($i=1; $i < $cnt; $i++) {
		$item = $onepage_items[$i];
		$out .= '<li class="nav-item">';
		$out .= '<a class="nav-link page-scroll';
		if ($onepage_top_id == $item['id']) $out .= ' active';
		$out .= '" href="#' . $item['name'] . '">';
		$out .= $item['title'];
		$out .= '</a>';
		$out .= '</li>';
	}

	$out .= '</ul>';

	return $out;
}

add_filter('bs4_wp_nav_menu', 'bs4_onepage_wp_nav_menu', 10, 2);

/**
 */
function bs4_onepage_post_meta_class($classes) { $classes[] = 'front-page'; return $classes; }
add_filter('post_meta_class', 'bs4_onepage_post_meta_class');


/* ---------- Business End ------------------------------ */

if (get_theme_mod( 'navbar_color_1', 0 ) == 5)
	ts_enqueue_style(
		'navbar-onepage',
		'.bg-custom-onepage { background-color: ' . get_theme_mod( 'navbar_color_custom_1', '#000000' ) . '; }' );

if (get_option('show_on_front') == 'page') {
	$page_for_posts = intval(get_option('page_for_posts'));
	$page_on_front = intval(get_option('page_on_front'));
} else
	$page_for_posts = $page_on_front = -1;

$onepage_top_id = $wp_query->post->ID;

$menu_items = has_nav_menu('front-page') ?
	wp_get_nav_menu_items( 'front-page', array() ) :
	array();
$onepage_items = array();

$onepage_items[] = array(
	'type' => 'home',
	'id' => ($page_on_front >= 0) ? $page_on_front : get_the_ID(),
	);

foreach ($menu_items as $key => $value) {
	$typ = false;
	switch ($value->type) {
		case 'post_type' :
			if ($value->type_label == 'Page') {
				if ($value->object_id == $page_on_front) {
					$typ = 'front';
				} elseif ($value->object_id == $page_for_posts) {
					$typ = 'posts';
				} else {
					$typ = 'page';
				}
			}

			break;
		case 'taxonomy' :
			if ($value->type_label == 'Category') {
				$typ = 'category';
			}
			break;
	}
	if ($typ !== false) {
		$onepage_items[] = array(
			'type' => $typ,
			'id' => intval($value->object_id),
			'title' => $value->title,
			'name' => (intval($value->object_id) == $page_on_front) ?
				'main' :
				sanitize_title( $value->title, 'page-'.$value->object_id ),
			);
	}
}

foreach ($onepage_items as $n => $item) {
	$bg_image = get_post_meta( $item['id'], 'bs4-bg-image', false );
	if (is_array($bg_image)) $bg_image = implode(',', $bg_image);

	if ($bg_image) {
		$bg_repeat = get_post_meta( $item['id'], 'bs4-bg-repeat', 'no-repeat' );
		$bg_position = get_post_meta( $item['id'], 'bs4-bg-position', 'left' );
		$bg_attachment = get_post_meta( $item['id'], 'bs4-bg-attachment', 'scroll' );
		$bg_size = get_post_meta( $item['id'], 'bs4-bg-size', 'auto' );

		$src = ".onepage-$n{";
		$src .= "background-image:url('$bg_image');";
		$src .= "background-repeat:$bg_repeat;";
		$src .= "background-position:$bg_position;";
		$src .= "background-attachment:$bg_attachment;";
		if ('auto' != $bg_size) $src .= "background-size:$bg_size;";
		$src .= "}";

		ts_enqueue_style( 'bg-'.$n, $src );
 	}
}

/* ---------- Output ------------------------------ */

get_header();
get_template_part( 'loop' );

$cnt = count($onepage_items);
if ($cnt > 1) {
	for ($i=1; $i < $cnt; $i++) {
		$item = $onepage_items[$i];
		if (($i == 1) && ($item['type'] == 'front')) continue;  // skip if 1st menu item is "front"

		if  ($item['type'] == 'page') {

			echo '</div></div></div></main>';
			// echo '</div></div></div>';

			$this_band_class = $band_class;
			if ($container_segments == 0) {
				$this_band_class .= ' onepage onepage-' . $i;
			} else {
				echo '</div><div class="main onepage onepage-' . $i . '">';
			}

			echo '<main id="' . $item['name'] . '" class="section">';
			echo '<div class="' . $this_band_class . '">';
			// echo '<div id="' . $item['name'] . '" class="' . $this_band_class . '">';
			echo '<div class="row">';
			echo '<div class="' . bs4_content_class(0) . '">';

			$GLOBALS['post'] = $item['id'];
			setup_postdata( get_post( $item['id'] ) );

			$format = get_post_format() ? : 'onepage';
			get_template_part( 'content', $format );
		}
	}
	rewind_posts();
}

echo '</div>';  // replaces get_sidebar();
get_footer();

// eof
