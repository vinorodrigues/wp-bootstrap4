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
	$cnt = count($sidebars);

	$has_widebar = is_active_sidebar( 'sidebar-8' );

	if ($has_widebar) {
		switch ( $cnt ) {
			case 1: $class = 'col-12 col-md-6'; break;
			case 2:	$class = 'col-12 col-md-3'; break;
			case 3: $class = 'col-12 col-md-4 col-lg-2'; break;
			case 4: $class = 'col-12 col-md-3 col-lg-2'; break;
		}
		switch ( $cnt ) {
			case 1: $classw = 'col-12 col-md-6'; break;
			case 2: $classw = 'col-12 col-md-6'; break;
			case 3: $classw = 'col-12 col-md-12 col-lg-6'; break;
			case 4: $classw = 'col-12 col-md-12 col-lg-4'; break;
		}
	} else {
		switch ( $cnt ) {
			case 1: $class = 'col-12'; break;
			case 2:	$class = 'col-12 col-md-6'; break;
			case 3: $class = 'col-12 col-md-4'; break;
			case 4: $class = 'col-12 col-md-3'; break;
		}
	}

	$classf = ' sidebar hidden-print';

	?><div class="row footbar"><?php
	for ($i = 0; $i <= ($cnt - 1); $i++) {
		?><div class="<?= $class.$classf ?>"><?php
		dynamic_sidebar( $sidebars[$i] );
		?></div><?php
	}
	if ($has_widebar) {
		?><div class="<?= $classw.$classf ?>"><?php
		dynamic_sidebar( 'sidebar-8' );
		?></div><?php
	}
	?></div><?php
endif;
?>
