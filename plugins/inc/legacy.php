<?php
/*
 * Shortcode's deprecated
 */

// Badges - where called Label's
bs4_add_shortcode( 'label', 'ts_bootstrap4_badge_sc' );

// EqualHeights deprecated because BS4 now Flexbox only
function bs4_equal_heights_sc( $atts, $content = null, $tag = '' ) { return $content; }
bs4_add_shortcode('equalheights', 'bs4_equal_heights_sc');
