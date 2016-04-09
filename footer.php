<?php
/**
 * footer.php
 */

global $container_segments, $band_class;

function bs4_footernav($classes = '') {
	return wp_nav_menu( array(
        	'menu'            => 'footer',
        	'menu_class'      => 'list-inline',
        	'container'       => 'nav',
        	'container_class' => $classes,
        	'fallback_cb'     => false,
        	'depth'           => 1,
        	'walker'          => new Bootstrap_Walker_Menu_Nav(),
        	'theme_location'  => 'footer',
        	'echo'            => false,
        	) );
}

function ___cr($cr_class, $cr_text) {
	?><div class="<?= $cr_class ?>"><?php
	echo $cr_text;
	?></div><?php
}

function ___mn($mn_class, $mn_c_class, $center = false) {
	?><div class="<?= $mn_class ?>"><?php
	if ($center) echo '<span style="display:table;margin:0 auto">';
	echo bs4_footernav('nav nav-footer ' . $mn_c_class);
	if ($center) echo '</span>';
	?></div><?php
}

$cr_position = get_theme_mod('copyright_position', 0);  // 0 => left, 1 => center, 2 => right
$cr_text = apply_filters( 'bootstrap4_footer_text', false );
if ($cr_text === false) {
	$cr_text = '&copy; ' . date('Y') . ' ' . get_bloginfo( 'name' );
}
$has_cr = ($cr_text === false) || !empty($cr_text);
$has_mn = has_nav_menu( 'footer' );

if ($has_cr) {
	$cr_class = 'col-xs-12 col-print-12';
	switch ($cr_position) {
        	case 1: $cr_class .= ' text-xs-center'; break;
        	case 2: $cr_class .= ' text-xs-right'; break;
        	default: $cr_class .= ' text-xs-left'; break;
	}
	if ($has_mn && ($cr_position != 1)) $cr_class .= ' col-md-6';
	$cr_class .= ' copyright';
}
if ($has_mn) {
	$mn_class = 'col-xs-12 hidden-print';
	if ($has_cr && ($cr_position != 1)) $mn_class .= ' col-md-6';
	$mn_c_class = '';
	switch ($cr_position) {
        	case 1: $mn_c_class .= ''; break;
        	case 2: $mn_c_class .= ' pull-xs-left'; break;
        	default: $mn_c_class .= ' pull-xs-right'; break;
	}
}

?>
</div></div></main>

<?php if ($container_segments != 0) { echo '</div><div class="footer">'; } ?>

<footer class="section"><div class="<?= $band_class ?> footing">
<?php
get_template_part( 'footbar' );
if ($has_mn || $has_cr) :
	?><div class="row"><?php

	switch ($cr_position) {
		case 1:
        		if ($has_mn) ___mn($mn_class, $mn_c_class, true);
        		if ($has_cr) ___cr($cr_class, $cr_text);
        		break;
		case 2:
        		if ($has_mn) ___mn($mn_class, $mn_c_class);
        		if ($has_cr) ___cr($cr_class, $cr_text);
        		break;
		default:
        		if ($has_cr) ___cr($cr_class, $cr_text);
        		if ($has_mn) ___mn($mn_class, $mn_c_class);
        		break;
	}
	?></div><?php
endif;
?>
</div></footer>

<?php echo '</div>'; // .folio or .footer ?>

<div class="invisible" hidden><?php wp_footer(); ?></div>
</body>
</html>
