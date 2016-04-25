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
	return ' <a title="More..." class="btn btn-outline-secondary btn-sm more-link" href="' .
		get_permalink() . '">' . get_bs4_i('hellip') . '</a>';
}
add_filter( 'excerpt_more', 'bs4_excerpt_more' );

/**
 * the_content_more_link filter
 */
function bs4_the_content_more_link( $link, $text) {
	$hellip = get_bs4_i('hellip', ' ');
	$text = str_replace( array('...', '&hellip;'), array($hellip, $hellip), $text );
	return '<a class="btn btn-outline-secondary btn-sm more-link" href="' .
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
		/* $cls = ltrim( str_replace(
			array('  ', 'alignnone', 'alignleft', 'alignright', 'aligncenter'),
			array(' ', '', 'pull-xs-left', 'pull-xs-right', 'm-x-auto'),
			$cls ) ); */
		$cls .=  ' img-fluid';
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
		array('', 'pull-xs-left', 'pull-xs-right', 'm-x-auto'),
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
	if (strpos($item, '<li') !== false) {
		$item = inject_class_in_tag('li', 'page-item', $item);
		if ($is_current) $item = inject_class_in_tag('li', 'active', $item);
		if ($is_disabled) $item = inject_class_in_tag('li', 'disabled', $item);
		$item = inject_class_in_tag('a', 'page-link', $item);
	}
	return $item;
}
add_filter('wp_link_pages_item', 'bs4_wp_link_pages_item', 10, 4);

/**
 * Pagination, comments
 */
function bs4_paginate_links_item($item, $is_current, $is_disabled) {
	if (strpos($item, '<li') !== false) {
		$item = inject_class_in_tag('li', 'page-item', $item);
		if ($is_current) $item = inject_class_in_tag('li', 'active', $item);
		if ($is_disabled) $item = inject_class_in_tag('li', 'disabled', $item);
		$item = inject_class_in_tag('a', 'page-link', $item);
	}
	return $item;
}
add_filter('paginate_links_item', 'bs4_paginate_links_item', 10, 3);

/**
 * Comment
 */
function bs4_cancel_comment_reply_link($formatted_link, $link, $text) {
	if (!isset($_GET['replytocom'])) return '';

	return ' <a rel="nofollow" id="cancel-comment-reply-link" href="' .
		$link . '" class="btn btn-outline-danger">' . $text . '</a>';
}
add_filter('cancel_comment_reply_link', 'bs4_cancel_comment_reply_link', 10, 3);

/**
 * Comment
 */
function bs4_comment_reply_link($formatted_link /* , $args, $comment, $post */ ) {
	if (strpos($formatted_link, 'comment-reply-login') !== false) return '';

	return ' ' . str_replace(
		'comment-reply-link',
		'btn btn-outline-success btn-sm small',
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

/**
 * BS4 page password form
 */
function bs4_password_form() {
	global $post;
	$label = 'pwbox-'.( empty( $post->ID ) ? 'X' . rand() : $post->ID );
	$o = '<p class="text-info">' . get_bs4_i('info lg', '', ' ') . 'Enter password to view this protected post.</p>';
	$o .= '<form class="form-inline" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">';
	$o .= '<div class="form-group">';
	$o .= '<input type="text" class="form-control" id="' . $label . '" name="post_password" maxlength="20" placeholder="Password" required>';
	$o .= '</div>';
	$o .= '<div class="form-group"><button type="submit" class="btn btn-primary">' . get_bs4_i('login', '', ' ') . 'Submit</button></div>';
	$o .= '</form>';

	return $o;
}
add_filter( 'the_password_form', 'bs4_password_form' );


function bs4_wp_list_categories($links) {
	$links = str_replace(array(' (', ')'), array(' <span class="tag tag-default">', '</span>'), $links);
	return $links;
}
add_filter('wp_list_categories', 'bs4_wp_list_categories');
