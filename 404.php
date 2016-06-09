<?php
/**
 * 404 page
 */
get_header();

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
?>

<div class="alert alert-warning alert-404">

<div class="center-sm m-b-3">
	<h1 class="display-1"><?php bs4_i('warning', '', ' ') ?>404</h1>
	<p class="text-danger text-xs-center">The page you were looking for could not be found.</p>
</div>
<?php if ($referer) {
	echo '<p class="text-info text-xs-center m-b-3">';
	bs4_i('info lg', '', ' ');
	echo 'You landed on this page from <a href="' . $referer . '" title="Go back" onclick="history.back();return false;">here</a>.';
	echo '</p>';
} ?>

<form method="get" id="search-form-404"
	action="<?= home_url( '/' ) ?>" class="form-inline search-from center-sm">
	<fieldset class="form-group">
	<input type="search" name="s" class="search-input form-control"
		placeholder="Search &hellip;" value="<?php esc_attr( get_search_query() ); ?>"
		results="10" autosave="search-0" autocorrect="on">
	</fieldset>
	<button type="submit" class="search-button btn btn-primary hidden-xs-down"><?php
		bs4_i('search', '<span class="hidden-sm-down">', ' </span>') ?>Search</button>
</form>
</div>

<?php
// get_sidebar();  // no sidebar!
get_footer();
