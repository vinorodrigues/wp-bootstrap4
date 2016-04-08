<?php
/*
 * sidebar.php
 */

global $sidebar_position;

function bs4_sidebar_class($sidebar_position, $first = true) {
	$o = 'col-xs-12';
	if ($first) {
		switch ($sidebar_position) {
        		case 1: $o .= ' col-md-4 pull-md-8 col-lg-3 pull-lg-9 sb-l'; break;
        		case 2: $o .= ' col-md-4 col-lg-3 sb-r'; break;
        		case 3: $o .= ' col-sm-6 col-md-3 pull-md-6 col-lg-2 pull-lg-8 sb-l'; break;
        	};
	} else {
        	// $sidebar_position == 3
        	$o .= ' col-sm-6 col-md-3 col-lg-2 sb-r';
	}
	$o .= ' sidebar no-print';
	if ( !get_theme_mod('bootstrap_flexbox', false) ) $o .= ' in-main';
	return $o;
}

?></div><?php
if ($sidebar_position != 0) :
	?><div class="<?= bs4_sidebar_class($sidebar_position) ?>"><?php
        dynamic_sidebar('sidebar-1');
        if (($sidebar_position != 3) && is_active_sidebar('sidebar-2'))
        	dynamic_sidebar('sidebar-2');
	?></div><?php
endif;
if ($sidebar_position == 3) :
	?><div class="<?= bs4_sidebar_class($sidebar_position, false) ?>"><?php
	dynamic_sidebar('sidebar-2');
	?></div><?php
endif;

if ( !get_theme_mod('bootstrap_flexbox', false) )
	bs4_equal_heights('.in-main', 99);
