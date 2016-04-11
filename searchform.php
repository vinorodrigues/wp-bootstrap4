<?php
/**
 * default search form
 */

global $___bs4_search_form_count;
if (!isset($___bs4_search_form_count)) $___bs4_search_form_count = 0;
$___bs4_search_form_count += 1;

?>
<form method="get" id="search-form-<?= $___bs4_search_form_count ?>"
	action="<?= home_url( '/' ) ?>" class="form search-from">
	<fieldset class="form-group">
        	<label for="s" class="search-label">Search for:</label>
        	<div class="input-group">
        		<?php /* <label for="s" class="input-group-addon"><?php bs4_i('search') ?></label> */ ?>
        		<input type="search" name="s" class="search-input form-control"
                		results="10" placeholder="Search &hellip;"
                		value="<?= get_search_query() ?>" />
        	</div>
	</fieldset>
	<button type="submit" class="search-button btn btn-primary"><?php
        	bs4_i('search', '<span class="hidden-sm-down">', ' </span>'); ?>Search</button>
</form>
