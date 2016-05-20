<?php
/**
 * footbar.php
 */

global $band_class;

if (has_bs4_footer_bar()) :

	$sidebars = array();
	for ($i = 1; $i <= 4; $i++) {
		if ( is_active_sidebar( 'sidebar-' . ($i+3) ) ) {
			$sidebars[] = 'sidebar-' . ($i+3);
		}
	}

	switch ( count( $sidebars ) ) {
		case 1: $class = 'col-xs-12'; break;
		case 2: $class = 'col-xs-12 col-md-6'; break;
		case 3: $class = 'col-xs-12 col-lg-4'; break;
		case 4: $class = 'col-xs-12 col-md-6 col-lg-3'; break;
	}
	$class .= ' sidebar hidden-print';
	// if ( bs4_get_option('equalheights') ) $class .= ' e-f';

	?><div class="row footbar"><?php
	for ($i = 0; $i <= (count($sidebars) - 1); $i++) {
		?><div class="<?= $class ?>"><?php
		dynamic_sidebar( $sidebars[$i] );
		?></div><?php
	}
	?></div><?php

	// if ( bs4_get_option('equalheights') )
	// 	bs4_equal_heights('.e-f', 101);
endif;
?>
