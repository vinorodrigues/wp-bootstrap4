<?php
/**
 * 404 page
 */
get_header();

$catagorized = (get_category_count() > 1);
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
?>

<div class="alert alert-warning alert-404">

<center class="m-b-3">
	<h1 class="display-1"><?php bs4_i('warning', '', ' ') ?>404</h1>
	<p class="text-center text-danger">The page you were looking for could not be found.</p>
</center>
<?php if ($referer) {
	echo '<p class="text-center text-info m-b-3">';
	bs4_i('info lg', '', ' ');
	echo 'You landed on this page from <a href="' . $referer . '" title="Go back" onclick="history.back();return false;">here</a>.';
	echo '</p>';
} ?>

<div class='row'><div class="col-xs-12 m-x-auto"><center>
<form method="get" id="search-form-404 ceter-block"
	action="<?= home_url( '/' ) ?>" class="form-inline search-from">
	<fieldset class="form-group">
	<input type="search" name="s" class="search-input form-control"
		placeholder="Search &hellip;" value="<?php esc_attr( get_search_query() ); ?>"
		results="10" autosave="search-0" autocorrect="on">
	</fieldset>
	<button type="submit" class="search-button btn btn-primary hidden-xs-down"><?php
		bs4_i('search', '<span class="hidden-sm-down">', ' </span>') ?>Search</button>
</form>
</center></div></div>
</div>

<?php

if (!has_bs4_footer_bar()) :

$wtag = 'h4';
$args = array(
	'before_widget' => '<aside class="widget">',
	'after_widget' => "</aside>",
	'before_title' => '<' . $wtag . '>',
	'after_title' => '</' . $wtag . '>',
);

?>
<div class="row">
	<div class="<?= ($catagorized ? 'col-sm-12 col-lg-4' : 'col-sm-6 col-lg-4 offset-lg-2') ?>">
		<?php the_widget( 'WP_Widget_Recent_Posts', array('title' => 'Recent Posts', 'number' => 5), $args ); ?>
	</div>
	<?php /*
		$args['after_title'] .= '<span class="text-center">';
		$args['after_widget'] = '</span>' . $args['after_widget'];
	*/ ?>
	<?php if ($catagorized) { ?>
		<div class="col-sm-6 col-lg-4">
		<?php the_widget( 'WP_Widget_Tag_Cloud', array('title' => 'Categories', 'taxonomy' => 'category'), $args ); ?>
		</div>
	<?php } ?>
	<div class="col-sm-6 col-lg-4">
		<?php the_widget( 'WP_Widget_Tag_Cloud', array('title' => 'Tags'), $args ); ?>
	</div>
</div>

<?

endif;  // has_bs4_footer_bar();

// get_sidebar();  // no sidebar!
get_footer();
