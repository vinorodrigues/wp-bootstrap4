<?php
/**
 * Custom template tags for this theme.
 *
 * These are mostly filters that modify the default behavior of the theme and
 * are required for the theme to function correctly.
 */

/**
 * expert_more filter
 */
function bs4_excerpt_more( $more ) {
	// label label-pill label-info
	return ' <a title="More..." class="btn btn-primary-outline btn-sm small more-link" href="' .
        	get_permalink() . '">' . get_bs4_i('hellip') . '</a>';
}
add_filter( 'excerpt_more', 'bs4_excerpt_more' );

/**
 * the_content_more_link filter
 */
function bs4_the_content_more_link( $link, $text) {
	$hellip = get_bs4_i('hellip', ' ');
	$text = str_replace( array('...', '&hellip;'), array($hellip, $hellip), $text );
	return '<a class="btn btn-primary-outline btn-sm small more-link" href="' .
        	get_permalink() . '">' . $text . '</a>';
}
add_filter( 'the_content_more_link', 'bs4_the_content_more_link', 10, 2 );

/**
 * the_content filter
 * uses the DOM to modify tags, like <img>, to the BS4 responsive class
 *
 * @see: http://stackoverflow.com/questions/20473004/how-to-add-automatic-class-in-image-for-wordpress-post#20499803
 */
function bs4_the_content($content) {
	if (empty($content)) return '';

	$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
	$document = new DOMDocument();
	libxml_use_internal_errors(true);
	$document->loadHTML( utf8_decode($content) );

	// Fix images
	$imgs = $document->getElementsByTagName('img');
	foreach ($imgs as $img) {
        	$cls = $img->getAttribute('class');
        	$cls = ltrim( str_replace(
        		array('  ', 'alignnone', 'alignleft', 'alignright', 'aligncenter'),
        		array(' ', '', 'pull-left', 'pull-right', 'center-block'),
        		$cls . ' img-fluid' ) );
        	$img->setAttribute('class', $cls);
	}

	// Fix tables
	$tbls = $document->getElementsByTagName('table');
	foreach ($tbls as $tbl) {
        	$cls = $tbl->getAttribute('class');
        	$cls = ltrim( $cls . ' table' );
        	$tbl->setAttribute('class', $cls);

        	$thds = $tbl->getElementsByTagName('thead');

        	foreach ($thds as $thd) {
        		$cls = $thd->getAttribute('class');
        		$cls = ltrim( $cls . ' thead-default' );
        		$thd->setAttribute('class', $cls);
        	}
	}

	$html = preg_replace(
		'/^<!DOCTYPE.+?>/',
		'',
		str_replace(
			array('<html>', '</html>', '<body>', '</body>'),
			'',
			$document->saveHTML() ) );
	return $html;
}
add_filter('the_content', 'bs4_the_content'); /* */

/**
 * @see: http://www.sitepoint.com/wordpress-change-img-tag-html/
 */
/* function bs4_get_image_tag_class($class, $id, $align, $size) {
	return $class;
}
add_filter('get_image_tag_class', 'bs4_get_image_tag_class', 0, 4);  /* */

/**
 * img_caption_shortcode filter
 *
 * @see: http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
function bs4_img_caption_shortcode( $output, $attr, $content ) {
	/* We're not worried abut captions in feeds, so just return the output here. */
	if (is_feed()) return $output;

	/* Set up the default arguments. */
	$defaults = array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => ''
	);

	/* Merge the defaults with user input. */
	$attr = shortcode_atts( $defaults, $attr );

	/* If the width is less than 1 or there is no caption, return the content wrapped between the [caption]< tags. */
	if ( 1 > $attr['width'] || empty( $attr['caption'] ) )
		return $content;

	/* Set up the attributes for the caption <div>. */
	$attributes = ( !empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
	$attributes .= ' class="figure wp-caption ' . esc_attr( $attr['align'] ) . '"';
	$attributes = str_replace(
        	array(' alignnone', 'alignleft', 'alignright', 'aligncenter'),
        	array('', 'pull-left', 'pull-right', 'center-block'),
        	$attributes );
	if ($attr['align'] == 'aligncenter') {
        	$attributes .= ' style="width: ' . esc_attr( $attr['width'] ) . 'px;max-width:100%"';
	}

	/* Open the caption <div>. */
	$output = '<figure' . $attributes .'>';

	/* Allow shortcodes for the content the caption was created for. */
	$output .= do_shortcode( $content );

	/* Append the caption text. */
	$output .= '<figcaption class="figure-caption text-center">' . $attr['caption'] . '</figcaption>';

	/* Close the caption </div>. */
	$output .= '</figure>';

	/* Return the formatted, clean caption. */
	return $output;
}
add_filter( 'img_caption_shortcode', 'bs4_img_caption_shortcode', 10, 3 );

/**
 * Tag cloud widget
 */
function bs4_widget_tag_cloud_args($args) {
	$args['smallest'] = 75;
	$args['largest'] = 150;
	$args['unit'] = '%';
	return $args;
}
add_filter('widget_tag_cloud_args', 'bs4_widget_tag_cloud_args');

/**
 * Pagination, pages
 */
function bs4_wp_link_pages_item($item, $i, $is_current, $is_disabled) {
	// global $page;  // , $numpages, $multipage, $more;
	if ($is_current)
        	$item = str_replace('<li', '<li class="active"', $item);
        if ($is_disabled)
        	$item = str_replace('<li', '<li class="disabled"', $item);
	return $item;
}
add_filter('wp_link_pages_item', 'bs4_wp_link_pages_item', 10, 4);

/**
 * Pagination, comments
 */
function bs4_paginate_links_item($item, $is_current, $is_disabled) {
	if ($is_current)
        	$item = str_replace('<li', '<li class="active"', $item);
        if ($is_disabled)
        	$item = str_replace('<li', '<li class="disabled"', $item);
	return $item;
}
add_filter('paginate_links_item', 'bs4_paginate_links_item', 10, 3);

/**
 * Comment
 */
function bs4_cancel_comment_reply_link($formatted_link, $link, $text) {
	if (!isset($_GET['replytocom'])) return '';

	return ' <a rel="nofollow" id="cancel-comment-reply-link" href="' .
        	$link . '" class="btn btn-danger-outline">' . $text . '</a>';
}
add_filter('cancel_comment_reply_link', 'bs4_cancel_comment_reply_link', 10, 3);

/**
 * Comment
 */
function bs4_comment_reply_link($formatted_link /* , $args, $comment, $post */ ) {
	if (strpos($formatted_link, 'comment-reply-login') !== false) return '';

	return ' ' . str_replace(
        	'comment-reply-link',
        	'btn btn-success-outline btn-sm small',
        	$formatted_link );
}
add_filter('comment_reply_link', 'bs4_comment_reply_link' /* , 10, 4 */ );

/**
 * Default gravatar
 */
function bs4_avatar_defaults($avatars) {
	$gravatar = get_template_directory_uri() . '/img/user.png';
	$avatars[$gravatar] = 'Theme Default';
	return $avatars;
}
add_filter( 'avatar_defaults', 'bs4_avatar_defaults' );

if ( defined('WP_DEBUG') && WP_DEBUG ) :
	function get_gravatar_off( $avatar, $id_or_email, $size = '96' ) {
		$img = get_template_directory_uri() .'/img/user.png';
		return "<img src='{$img}' class='avatar avatar-{$size} " .
			AVATAR_CLASS . "' height='{$size}' width='{$size}'>";
	}
	add_filter('get_avatar', 'get_gravatar_off', 1, 3);
endif;
