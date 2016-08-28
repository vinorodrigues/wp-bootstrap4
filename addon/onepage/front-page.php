<?php

include_once get_template_directory().'/inc/lib-ts/opt-common.php';

/**
 */
function bs4_onepage_get_sidebar_position($ignore) {
	return 0;
}

add_filter( 'bs4_get_sidebar_position', 'bs4_onepage_get_sidebar_position' );

/**
 */
function bs4_onepage_wp_nav_menu($content, $class) {
	global $menu_items;

	return $class;
}

add_filter( 'bs4_wp_nav_menu', 'bs4_onepage_wp_nav_menu', 10, 2);

/**
 */
function bs4_onepage_main_class($class) {
	if ('main' == $class) {
		$sec = 0;
	} else {
		$sec = intval($class);
		$class = '';
	}
	if ('' != $class) $class .= ' ';
	return $class . 'onepage onepage-' . $sec;
}

add_filter( 'bs4_main_class', 'bs4_onepage_main_class' );

/* ---------- Business End ------------------------------ */

global $menu_items;

if (get_option('show_on_front') == 'page') {
	$page_for_posts = get_option('page_for_posts');
	$page_on_front = get_option('page_on_front');
} else
	$page_for_posts = $page_on_front = -1;

$menu_items_temp = wp_get_nav_menu_items( 'front-page', array() );
$menu_items = array();
$onepages = array();
$cnt = 0;
$onepages[$cnt] = ($page_on_front >= 0) ? $page_on_front : get_the_ID();
foreach ($menu_items_temp as $key => $value) {
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
					$cnt++;
					$onepages[$cnt] = $value->object_id;
				}
			}

			break;
		case 'taxonomy' :
			if ($value->type_label == 'Category') {
				$typ = 'category';
			}
			break;
	}
	if ($typ !== false) $menu_items[] = array(
		'type' => $typ,
		'id' => $value->object_id,
		'title' => $value->title,
		);
}

foreach ($onepages as $sq => $page_id) {
	$bg_image = get_post_meta( $page_id, 'bs4-bg-image', false );
	if (is_array($bg_image)) $bg_image = implode(',', $bg_image);

	if ($bg_image !== false) {
		$bg_repeat = get_post_meta( $page_id, 'bs4-bg-repeat', 'no-repeat' );
		$bg_position = get_post_meta( $page_id, 'bs4-bg-position', 'left' );
		$bg_attachment = get_post_meta( $page_id, 'bs4-bg-attachment', 'scroll' );

		$src = ".onepage-$sq {";
		$src .= "\n\t\tbackground-image: url('$bg_image');";
		$src .= "\n\t\tbackground-repeat: $bg_repeat;";
		$src .= "\n\t\tbackground-position: $bg_position;";
		if ($bg_attachment == 'parallax') {
			$src .= "\n\t\tbackground-attachment: fixed;";
			if ($bg_repeat == 'no-repeat') {
				$src .= "\n\t\t-webkit-background-size: cover;";
				$src .= "\n\t\t-moz-background-size: cover;";
				$src .= "\n\t\tbackground-size: cover;";
			}
		} else {
			$src .= "\n\t\tbackground-attachment: $bg_attachment;";
		}
		$src .= "\n\t }\n";

		ts_enqueue_style( 'bg-'.$sq, $src );
	}
}



get_header();
get_template_part( 'loop' );

echo '</div>';

get_footer();

var_dump_pre($menu_items, '$menu_items');
var_dump_pre($onepages, '$onepages');

/*
get_header();
// get_template_part( 'loop' );


$menu_items_temp = wp_get_nav_menu_items( 'front-page', array() );

$menu_items = array();
if (get_option('show_on_front') == 'page') {
	$page_for_posts = get_option('page_for_posts');
	$page_on_front = get_option('page_on_front');
} else {
	$page_for_posts = $page_on_front = -1;
}

foreach ($menu_items_temp as $key => $value) {
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
	if ($typ !== false) $menu_items[] = array(
		'type' => $typ,
		'id' => $value->object_id,
		'title' => $value->title);

	// echo '--------------------------------'; var_dump_pre($key);
	// echo 'post_type'; var_dump_pre($value->post_type);
	// echo 'object_id'; var_dump_pre($value->object_id);
	// echo 'type'; var_dump_pre($value->type);
	// echo 'type_label'; var_dump_pre($value->type_label);
	// echo 'title'; var_dump_pre($value->title);
}

// echo '===================================';
// var_dump_pre( $menu_items );

foreach ($menu_items as $item) {
	echo '[' . $item['title'] . '(' . $item['type'] . ')]';
}
echo '<hr>';

global $page, $more, $preview, $pages, $multipage, $post;

foreach ($menu_items as $item) {
	echo '{';
	if (($item['type'] == 'front') || ($item['type']) == 'page') {
		$GLOBALS['post'] = $item['id'];
		setup_postdata( get_post( $item['id'] ) );
		var_dump_pre( $multipage, '$multipage' );
		var_dump_pre( $pages, '$pages' );
		the_content();
	}
	echo '}';
}
rewind_posts();
echo '<br>';

/*
// would echo post 7's content up until the <!--more--> tag
$post_7 = get_post(7);
$excerpt = $post_7->post_excerpt;
echo $excerpt

// would get post 12's entire content after which you
// can manipulate it with your own trimming preferences
$post_12 = get_post(12);
$trim_me = $post_12->post_content;
my_trim_function( $trim_me );
*/
/*
get_sidebar();
get_footer();
*/
