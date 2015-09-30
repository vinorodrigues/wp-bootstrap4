<?php
/**
 * WP Core Hacks
 */

 /**
  *  HACK of WP Core paginate_comments_links()
  */
function paginate_comments_links_2( $args = array() ) {
	global $wp_rewrite;

	if ( !is_singular() || !get_option('page_comments') )
        	return;

	$page = get_query_var('cpage');
	if ( !$page )
        	$page = 1;
	$max_page = get_comment_pages_count();
	$defaults = array(
        	'base' => add_query_arg( 'cpage', '%#%' ),
        	'format' => '',
        	'total' => $max_page,
        	'current' => $page,
        	'echo' => true,
        	'add_fragment' => '#comments'
        	);
	if ( $wp_rewrite->using_permalinks() )
        	$defaults['base'] = user_trailingslashit(trailingslashit(get_permalink()) . $wp_rewrite->comments_pagination_base . '-%#%', 'commentpaged');

	$args = wp_parse_args( $args, $defaults );
	$page_links = paginate_links_2( $args );  // call hack

	if ( $args['echo'] )
		echo $page_links;
	else
		return $page_links;
}
