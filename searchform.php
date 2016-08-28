<?php
/**
 * default search form
 */

global $___bs4_search_form_count;
if (!isset($___bs4_search_form_count)) $___bs4_search_form_count = 0;
$___bs4_search_form_count += 1;

?>
<form method="get" id="search-form-<?= $___bs4_search_form_count ?>"
	action="<?= esc_url(home_url('/')) ?>" class="form search-from">
	<div class="form-group row">
		<label for="s" class="col-sm-2 form-control-label search-label">Search for</label>
		<div class="col-sm-10">
			<input type="search" name="s" class="search-input form-control"
				placeholder="Search &hellip;" value="<?= get_search_query() ?>"
				autosave="search-<?= $___bs4_search_form_count ?>"  autocorrect="on">
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-10 offset-sm-2">
			<button type="submit" class="btn btn-primary search-button"><?php
			bs4_i('search', '<span class="hidden-sm-down">', ' </span>'); ?>Search</button>
		</div>
	</div>
</form>
