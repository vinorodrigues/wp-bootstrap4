<?php
/**
 * 404 page
 */
get_header();

$catagorized = (get_category_count() > 1);
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
?>

<div class="alert alert-404">

<h1 class="display-1 text-center"><?php bs4_i('warning', '', ' ') ?>404</h1>
<p class="text-center text-danger">The page you were looking for could not be found.</p>
<?php if ($referer) {
	echo '<p class="text-center text-info">';
	bs4_i('info lg', '', ' ');
	echo 'You landed on this page from <a href="' . $referer . '" title="Go back" onclick="history.back();return false;">here</a>.';
	echo '</p>';
} ?>

<hr class="soft">

<div class='row'><div class="m-x-auto">
<form method="get" id="search-form-404 ceter-block"
	action="<?= home_url( '/' ) ?>" class="form-inline search-from">
	<input type="search" name="s" class="search-input form-control"
		results="10" placeholder="Search &hellip;"
        	value="<?php esc_attr( get_search_query() ); ?>">
	<button type="submit" class="search-button btn btn-primary hidden-xs-down"><?php
        	bs4_i('search', '<span class="hidden-sm-down">', ' </span>') ?>Search</button>
</form>
</div></div>
</div>

<hr class="soft">

<?php
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
// get_sidebar();  // no sidebar!
get_footer();
