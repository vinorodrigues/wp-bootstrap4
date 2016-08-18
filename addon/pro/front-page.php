<?php

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

get_sidebar();
get_footer();
_d(__FILE__);
