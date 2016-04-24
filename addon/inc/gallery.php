<?php
/**
 * Gallary Shortcode
 */

// include_once 'plugin-lib.php';

/**
 * Gallery Filter
 */
function ts_bootstrap4_post_gallery( $output, $attr, $instance ) {

	if (is_feed()) return '';

	$post = get_post();

	if (!empty($attr['ids'])) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	$atts = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => 'figure',
		'icontag'    => '',
		'captiontag' => 'figcaption',
		'columns'    => 3,  // don't change this or 3 will get ignored
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => ''
		), $attr, 'gallery' );

	$id = intval($atts['id']);

	if (!empty( $atts['include'])) {
		$_attachments = get_posts( array(
			'include'        => $atts['include'],
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => $atts['order'],
			'orderby'        => $atts['orderby']
			) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif (!empty($atts['exclude'])) {
		$attachments = get_children( array(
			'post_parent'    => $id,
			'exclude'        => $atts['exclude'],
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => $atts['order'],
			'orderby'        => $atts['orderby'],
			) );
	} else {
		$attachments = get_children( array(
			'post_parent'    => $id,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => $atts['order'],
			'orderby'        => $atts['orderby'],
			) );
	}

	if ( empty( $attachments ) ) return '';

	$itemtag = tag_escape( $atts['itemtag'] );
	$captiontag = tag_escape( $atts['captiontag'] );
	$icontag = tag_escape( $atts['icontag'] );
	$outertag = 'ul';
	$innertag = 'li';

	$valid_tags = wp_kses_allowed_html('post');
	if (!isset($valid_tags[$itemtag])) $itemtag = 'figure';
	if (!isset($valid_tags[$captiontag])) $captiontag = 'figcaption';
	if (!isset($valid_tags[$icontag])) $icontag = '';

	$columns = intval( $atts['columns'] );
	if ($columns > 9) $columns = 9;

	$selector = 'gallery-' . $instance;

	$size_class = sanitize_html_class( $atts['size'] );
	$output .= '<' . $outertag . ' id="' . $selector .
		'" class="row gallery gallery-' . $id . ' gal-cols-' .
		$columns . '">';

	$sizes = array(
		          //  1  2  3  4  5  6  7  8  9
		'xs' => array(1, 1, 1, 1, 1, 2, 2, 2, 2),
		'sm' => array(1, 1, 1, 2, 2, 2, 3, 3, 3),
		'md' => array(1, 1, 2, 3, 4, 4, 6, 6, 6),
		// 'lg' => array(1, 2, 3, 4, 4, 6, 6, 6, 6),
		);

	$count = count($attachments);
	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$i++;

		$output .= '<' . $innertag . ' class="';
		foreach ($sizes as $key => $value) {
			$output .= 'col-' . $key . '-' .
				(12 / $value[$columns-1]) . ' ';
		}
		$output .= 'col-gal-1-' . $columns . '">';

		$attr = array();
		if (!empty(trim($attachment->post_excerpt)))
			$attr['aria-describedby'] = $selector . '-' . $id;

		if (!empty($atts['link']) && ('file' === $atts['link'])) {
			$image_output = wp_get_attachment_link(
				$id,
				$atts['size'],
				false,
				false,
				false,
				$attr);
		} elseif (!empty($atts['link']) && ('none' === $atts['link'])) {
			$image_output = wp_get_attachment_image(
				$id,
				$atts['size'],
				false,
				$attr);
		} else {
			$image_output = wp_get_attachment_link(
				$id,
				$atts['size'],
				true,
				false,
				false,
				$attr);
		}
		$image_meta = wp_get_attachment_metadata($id);

		$output .= '<' . $itemtag . ' class="gal-item gal-' .
			$size_class . '">';

		if (!empty($icontag)) {
			$orientation = '';
			if (isset($image_meta['height'], $image_meta['width'])) {
				$orientation = ($image_meta['height'] > $image_meta['width']) ? 'portrait' : 'landscape';
			}
			$output .= '<' . $icontag . ' class="gal-icon ' . $orientation . '">';
		}
		$output .= $image_output;
		if (!empty($icontag)) {
			$output .= '</' . $icontag . '>';
		}
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= '<' . $captiontag .
				' class="wp-caption-text gal-caption" id="' .
				$selector . '-' . $id . '">';
			$output .= wptexturize($attachment->post_excerpt);
			$output .= '</' . $captiontag . '>';
		}

		$output .= '</' . $itemtag . '>';
		$output .= '</' . $innertag . '>';

		if ($i < $count) {
			if (($sizes['xs'][$columns-1] > 1) && ($i % $sizes['xs'][$columns-1] == 0))
				$output .= '<' . $innertag . ' class="clearfix hidden-sm-up"></' . $innertag . '>';  // xs
			if ($i % $sizes['sm'][$columns-1] == 0)
				$output .= '<' . $innertag . ' class="clearfix hidden-md-up hidden-xs-down "></' . $innertag . '>';  // sm
			if ($i % $sizes['md'][$columns-1] == 0)
				$output .= '<' . $innertag . ' class="clearfix hidden-lg-up hidden-sm-down"></' . $innertag . '>';  // md
			if ($i % $columns == 0)
				$output .= '<' . $innertag . ' class="clearfix hidden-md-down"></' . $innertag . '>';  // lg + xl
		}

	}

	$output .= '</' . $outertag . '>';

	return $output;
}

add_filter( 'post_gallery', 'ts_bootstrap4_post_gallery', 10, 3 );
