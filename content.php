<?php
/**
 * content.php
 */

global $band_class;

$post_class = '';
$title_class = is_singular() ? '' : 'h2';
$is_card = is_sticky() && !is_singular() && !is_search();
$has_thumbnail = has_post_thumbnail( get_the_ID() );

?>
<article <?php post_class($post_class); ?>>
<?php
$title_class = 'title';
if ( $is_card ) {
	?><div class="card card-block sticky"><?php
	$title_class .= ' h3 card-title';
} elseif ( is_search() ) {
	$title_class .= ' h4';
}

?><header class="meta<?php if (is_home()) echo ' home';
	if (is_front_page()) echo ' front-page';
	if (is_singular()) echo ' singular'; ?>"><?php

if ($has_thumbnail && is_singular()) {
	$thumbnail = get_the_post_thumbnail( get_the_ID(),
		'featured-image',
		array('class' => 'img-featured ' . FEATURED_IMAGE_CLASS) );
	$fn = 'featured_image_' . get_the_ID();
	bs4_inject_feature($fn, $thumbnail);
}

$_t = trim( get_the_title() );
if (empty($_t)) $_t = false;

if (is_singular()) {
	if ($_t) {
		?><h1 class="<?= $title_class ?>"><?= $_t ?></h1><?php
	}
} else {
	?><h1 class="<?= $title_class ?>"><a href="<?php the_permalink(); ?>"><?php
	if (!$_t) {
		bs4_i('link');
	} else {
		echo $_t;
	}
	?></a></h1><?php
}

if (is_singular() && !is_page()) { ?><p class="meta-data text-muted"><?php bs4_post_meta(); ?></p><?php }

?></header><?php

if ( $is_card ) {
	?><span class="card-text"><?php
}

if ( is_search() ) {
	the_excerpt();
} else {
	if ($has_thumbnail && !is_singular()) {
		$thumbnail = get_the_post_thumbnail( get_the_ID(),
			'post-thumbnail',
			array('class' => 'media-object ' . POST_THUMBNAIL_CLASS) );

		?><div class="media"><a class="media-left" href="<?php the_permalink(); ?>"><?php
		echo $thumbnail;
		?></a><div class="media-body"><?php
	}

	the_content('More...');  // display Post

	if ($has_thumbnail && !is_singular()) {
		?></div></div><?php
	}
}

$link = get_edit_post_link();
if (is_singular() && $link) { ?>
<footer class="meta">
	<p class="meta-data"><?php
		echo '<a href="' . $link . '" class="btn btn-outline-warning btn-sm small hidden-print"' .
			'title="Edit post">' . get_bs4_i('edit', '', ' ') . 'Edit</a>';
		/* $link = get_delete_post_link();
		if ($link)
			echo ' <a href="' . $link . '" class="btn btn-outline-danger btn-sm small"' .
				'title="Delete post">' .
				get_bs4_i('trash', '', ' ') . 'Delete</a>'; */
	?></p>
</footer>
<?php }

if ( $is_card ) {
	?></span></div><?php
}
?>
</article>
