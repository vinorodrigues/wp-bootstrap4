<?php
/**
 * content.php
 */

$post_class = '';
$title_class = is_singular() ? '' : 'h2';
$is_card = is_sticky() && !is_singular() && !is_search();

?>
<article <?php post_class($post_class); ?>>
<?php
if ( $is_card ) {
	?><div class="card card-block sticky"><?php
	$title_class = 'h3 card-title';
} elseif ( is_search() ) {
	$title_class = 'h4';
}

?><header class="meta"><?php
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
	the_content('More...');  // display Post
}

$link = get_edit_post_link();
if (is_singular() && $link) { ?>
<footer class="meta">
	<p class="meta-data"><?php
        	echo '<a href="' . $link . '" class="btn btn-warning-outline btn-sm small"' .
        		'title="Edit post">' . get_bs4_i('edit', '', ' ') . 'Edit</a>';
        	/* $link = get_delete_post_link();
        	if ($link)
        		echo ' <a href="' . $link . '" class="btn btn-danger-outline btn-sm small"' .
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
