<?php
/**
 * ScrollSpy Widget and Shortcodes
 */

include_once 'plugin-lib.php';

/**
 *
 */
class TS_BS4_ScrollSpy_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'bs4_scrollspy_widget',
			'Bootstrap 4 ScrollSpy Widget',
			array(
				'classname' => 'bs4_scrollspy_widget',
				)
			);
	}

	public function widget( $args, $instance ) {
		global $bs4_singletons;

		if (!isset($bs4_singletons) && !isset($bs4_singletons['scrollspy']) && !is_array($bs4_singletons['scrollspy']))
			return;

		if (!isset($bs4_singletons['nav_widget_count'])) $bs4_singletons['nav_widget_count'] = 0;
		$bs4_singletons['nav_widget_count']++;

		$wid = 'nav-widget-' . $bs4_singletons['nav_widget_count'];

		echo $args['before_widget'];
		if (!empty( $instance['title'] )) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		echo '<ul id="' . $wid . '" class="nav nav-widget">';
		foreach ($bs4_singletons['scrollspy'] as $id => $title) {
			echo '<li class="nav-item">';
			echo '<a class="nav-link" href="#'.$id.'">';
			echo $title;
			echo '</a>';
			echo '</li>';
		}
		echo '<ul>';

		echo $args['after_widget'];

		ts_enqueue_script($wid,
			'(function($) { $(document).ready(function(){' .
			' $("body").scrollspy({ target: "#'.$wid.'" })' .
			' }); })(jQuery);' );
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		return $instance;
	}
}

/**
 *
 */
function bs4_register_scrollspy_widget() {
	register_widget( 'TS_BS4_ScrollSpy_Widget' );
}

add_action( 'widgets_init', 'bs4_register_scrollspy_widget' );


/**
 *
 */
function ts_bootstrap4_heading_sc( $atts, $content = null, $tag = '' ) {
	global $bs4_singletons;

	$attribs = bs4_shortcode_atts( array(
		'id' => false,
		'title' => '',
		), $atts, $tag );

	$title = $attribs['title'] ? $attribs['title'] : $content;
	$id = $attribs['id'] ? $attribs['id'] : sanitize_title($title);

	if (!isset($bs4_singletons)) $bs4_singletons = array();
	if (!isset($bs4_singletons['scrollspy'])) $bs4_singletons['scrollspy'] = array();
	$bs4_singletons['scrollspy'][$id] = $title;

	return '<' . $tag . ' id="' . $id . '">' . $content . '</' . $tag . '>';
}

bs4_add_shortcode( 'h1', 'ts_bootstrap4_heading_sc' );
bs4_add_shortcode( 'h2', 'ts_bootstrap4_heading_sc' );
bs4_add_shortcode( 'h3', 'ts_bootstrap4_heading_sc' );
bs4_add_shortcode( 'h4', 'ts_bootstrap4_heading_sc' );
bs4_add_shortcode( 'h5', 'ts_bootstrap4_heading_sc' );
bs4_add_shortcode( 'h6', 'ts_bootstrap4_heading_sc' );
