<?php
/**
 * Carousel Shortcode
 */

include_once 'plugin-lib.php';

function ts_bootstrap4_carousel_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons)) $bs4_singletons = array();

	if (isset($bs4_singletons['in_carousel']) && $bs4_singletons['in_carousel'])
		return '';  // jump out, no nested carousel's

	if (!isset($bs4_singletons['carousel_count']))
		$bs4_singletons['carousel_count'] = 0;
	$bs4_singletons['carousel_count']++;

	$bs4_singletons['in_carousel'] = true;
	do_shortcode( $content );  // get inner content
	$bs4_singletons['in_carousel'] = false;

	$count = isset($bs4_singletons['carousel_items']) ?
		count($bs4_singletons['carousel_items']) :
		0;
	if ($count == 0) return '';  // no content

	$active_item = isset($bs4_singletons['carousel_active_item']) ?
		$bs4_singletons['carousel_active_item'] :
		0;
	unset( $bs4_singletons['carousel_active_item'] );  // clean up!
	unset( $bs4_singletons['carousel_current_item'] );  // clean up!

	$attribs = bs4_shortcode_atts(array(
		'id'       => false,
		'interval' => 5000,
		'pause'    => 'hover',
		'wrap'     => true,
		'keyboard' => true,
		/* 'inverse'  => true, */
		), $atts, $tag);
	$attribs = bs4_filter_booleans($attribs, array( 'wrap', 'keyboard' /* , 'inverse' */ ));

	if (!$attribs['id']) $attribs['id'] = 'carousel-'. $bs4_singletons['carousel_count'];

	$class = 'carousel';
	/* if ($attribs['inverse']) $class .= ' carousel-dark'; */
	$class .= ' slide';

	ob_start();
?>
<div<?= bs4_get_shortcode_class($attribs, $class) ?> data-ride="carousel">
	<ol class="carousel-indicators">
	<?php
		for ($i = 0; $i < $count; $i++ ) {
			echo '<li data-target="#' . $attribs['id'] . '" data-slide-to="' . $i . '"';
			if ($i == $active_item) echo ' class="active"';
			echo '></li>';
		}
	?>
	</ol>
	<div class="carousel-inner" role="listbox">
	<?php
		$i = 0;
		foreach ($bs4_singletons['carousel_items'] as $value) {
			echo '<div class="carousel-item';
			if ($i == $active_item) echo ' active';
			echo '">';
			echo $value;

			if (isset($bs4_singletons['carousel_captions']) && isset($bs4_singletons['carousel_captions'][$i])) {
				echo '<div class="carousel-caption">';
				echo $bs4_singletons['carousel_captions'][$i];
				echo '</div>';
			}

			echo '</div>';
			$i++;
		}
	?>
	</div>
	<a class="left carousel-control" href="#<?= $attribs['id'] ?>" role="button" data-slide="prev">
		<span class="icon-prev" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#<?= $attribs['id'] ?>" role="button" data-slide="next">
		<span class="icon-next" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>
<?php
	$content = ob_get_clean();

	unset( $bs4_singletons['carousel_items'] );  // clean up!

	// add options js

	$options = array();
	if ($attribs['interval'] != '5000')
		$options[] = 'interval: ' . $attribs['interval'];
	if ($attribs['pause'] != 'hover')
		$options[] = 'pause: "' . $attribs['pause'] . '"';
	if ($attribs['wrap'] !== true)
		$options[] = 'wrap: ' . ($attribs['wrap'] ? 'true' : 'false');
	if ($attribs['keyboard'] !== true)
		$options[] = 'keyboard: ' . ($attribs['keyboard'] ? 'true' : 'false');
	if (count($options) > 0) {
		$js = '$("#' . $attribs['id'] . '").carousel({';
		$js .= implode(', ', $options);
		$js .= '});';

		ts_enqueue_script('carousel-' . $attribs['id'], $js);
	}

	return $content;
}

function ts_bootstrap4_carousel_item_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons) || !isset($bs4_singletons['in_carousel']) || !$bs4_singletons['in_carousel'])
		return "[{$tag}]" . do_shortcode( $content ) . "[/{$tag}]";

	$attribs = bs4_shortcode_atts( array('active' => false), $atts, $tag );
	$attribs = bs4_filter_booleans($attribs, array('active'));

	if (!isset($bs4_singletons['carousel_items']))
		$bs4_singletons['carousel_items'] = array();

	// get inner content
	$bs4_singletons['carousel_current_item'] = count($bs4_singletons['carousel_items']);
	if ($attribs['active']) $bs4_singletons['carousel_active_item'] =
		$bs4_singletons['carousel_current_item'];
	$bs4_singletons['carousel_items'][] = do_shortcode( $content );
	$bs4_singletons['carousel_current_item'] = -1;

	return '';  // no output
}

function ts_bootstrap4_carousel_caption_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	if (!isset($bs4_singletons) || !isset($bs4_singletons['in_carousel']) || !$bs4_singletons['in_carousel'])
		return "[{$tag}]" . do_shortcode( $content ) . "[/{$tag}]";

	if (!isset($bs4_singletons['carousel_current_item']) || ($bs4_singletons['carousel_current_item'] < 0))
		return "[{$tag}]" . do_shortcode( $content ) . "[/{$tag}]";

	$item = $bs4_singletons['carousel_current_item'];

	if (!isset($bs4_singletons['carousel_captions']))
		$bs4_singletons['carousel_captions'] = array();

	$bs4_singletons['carousel_captions'][$item] = do_shortcode( $content );

	return '';  // no output
}

add_shortcode( 'carousel', 'ts_bootstrap4_carousel_sc' );
add_shortcode( 'carousel-item', 'ts_bootstrap4_carousel_item_sc' );
add_shortcode( 'carousel-caption', 'ts_bootstrap4_carousel_caption_sc' );
