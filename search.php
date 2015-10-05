<?php
/**
 *  search.php
 */

ob_start();
?>
<div class="card"><div class="card-block">
<h1 class="h4 card-title"><?= get_bs4_i('search lg', '', ' ') ?>Search results for:
<small class="text-muted"><?= get_search_query() ?></small></h1>
</div></div>
<?
$html = ob_get_clean();
bs4_inject_feature('search', $html);

include get_template_directory() . '/index.php';
