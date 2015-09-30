<?php
/**
 * WP Core Hacks
 */

/**
 *  Re-do of WP link_pages() for Bootstrap
 */
function wp_link_pages_2( $args = '' ) {
	global $page, $numpages, $multipage, $more;

	$defaults = array(  // HACK: changed WP defaults to Bootstrap friendly defaults
		'before'               => '<ul>',
		'after'                => '</ul>',
        	'link_before'          => '',
		'link_after'           => '',
        	'item_before'          => '<li>',  // HACK: + item_before
		'item_after'           => '</li>',  // HACK: + item_after
		'next_or_number'       => 'number',  // HACK: Added 'both' option
		'separator'            => '',
		'nextpagelink'         => __( 'Next page' ),
		'previouspagelink'     => __( 'Previous page' ),
		'pagelink'             => '%',
        	'nolink'               => '%',  // HACK: nolink
		'echo'                 => 1
		);
	$params = wp_parse_args( $args, $defaults );
	$r = apply_filters( 'wp_link_pages_args', $params );

	$output = '';
	if ( $multipage ) {
        	$output .= $r['before'];

        	$prev = $page - 1;
        	$next = $page + 1;  // HACK: move to top, always use $next & $prev
        	$do_no = 'next' != $r['next_or_number'];
        	$do_nx = 'number' != $r['next_or_number'];

        	if ( $do_nx && $more && ($do_no || $prev) ) {
        		$link = _wp_link_page( $prev ) . $r['link_before'] . $r['previouspagelink'] . $r['link_after'] . '</a>';
        		if ( $more && $prev ) {
                		$link = apply_filters( 'wp_link_pages_link', $link, $prev );
        		} else {
                		$link = str_replace( '%', $r['previouspagelink'], $r['nolink']);  // HACK: nolink
        		}
        		$output .= apply_filters( 'wp_link_pages_item', $r['item_before'] . $link . $r['item_after'], $prev, false, $prev == 0);  // HACK: item-before, item_after
        	}

		if ( $do_no ) {
			for ( $i = 1; $i <= $numpages; $i++ ) {
				$link = $r['link_before'] . str_replace( '%', $i, $r['pagelink'] ) . $r['link_after'];
				if ( $i != $page || ! $more && 1 == $page ) {
					$link = _wp_link_page( $i ) . $link . '</a>';
				} else {
                			$link = str_replace( '%', $link, $r['nolink']);  // HACK: nolink
                		}
				$link = apply_filters( 'wp_link_pages_link', $link, $i );

				// Use the custom links separator beginning with the second link.
				if ( 1 !== $i ) $output .= $r['separator'];  // HACK: remove ' ' (space)
                			$output .= apply_filters( 'wp_link_pages_item', $r['item_before'] . $link . $r['item_after'], $i, ($i == $page), false);  // HACK: item-before, item_after
				}
			}

        	if ( $do_nx && $more && ($do_no || ($next && ($next <= $numpages))) ) {
			if ( $prev ) $output .= $r['separator'];
        		if ( $next && ($next <= $numpages) ) {
				$link = _wp_link_page( $next ) . $r['link_before'] . $r['nextpagelink'] . $r['link_after'] . '</a>';
        		} else {
                		$link = str_replace( '%', $r['nextpagelink'], $r['nolink']);  // HACK: nolink
        		}
			$link = apply_filters( 'wp_link_pages_link', $link, $next );
        		$output .= apply_filters( 'wp_link_pages_item', $r['item_before'] . $link . $r['item_after'], $next, false, $next > $numpages);  // HACK: item-before, item_after
		}

		$output .= $r['after'];
	}

	$html = apply_filters( 'wp_link_pages', $output, $args );
	if ( $r['echo'] ) { echo $html; return; }
	return $html;
}
