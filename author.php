<?php
/**
 *  author.php
 */

$author = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));

ob_start();
?>
<div class="card"><div class="card-block">
<h1 class="h4 card-title"><?= get_bs4_user_i($author->ID, '', ' ') ?>Author:
<small class="text-muted"><?= $author->display_name ?></small></h1>
<?php
if (!empty($author->description)) echo '<p class="card-text">' . $author->description . '</p>';
?>
</div></div>
<?
$html = ob_get_clean();
bs4_inject_feature('author', $html);

include get_template_directory() . '/index.php';
