<?php
/**
 * customizer.php
 *
 * @see: http://themefoundation.com/wordpress-theme-customizer/
 */

if ( !class_exists( 'WP_Customize_Control' ) )
	require_once ABSPATH . WPINC . '/class-wp-customize-control.php';

/**
 * Adds textarea support to the theme customizer
 */
class My_Customize_Radio_Control extends WP_Customize_Control {

	public $type = 'radio';

	public $choices = NULL;

	public $prefix = '';

	public $suffix = '.png';

	public function enqueue() {
		wp_enqueue_style( 'bootstrap4_customizer', get_template_directory_uri() . "/css/customizer.css" );
	}

	public function render_content() {
        	if (!is_null($this->choices)) :
        		?>
        		<div class="bs4-cust-radio-ctrl">
        			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php
				foreach ( $this->choices as $value => $label ) : ?>
					<input class="image-select-input"
                				type="radio"
                				value="<?php echo esc_attr( $value ); ?>"
                				id="<?php echo $this->id . $value; ?>"
                				name="<?php echo $this->id; ?>"
                				<?php $this->link(); checked( $this->value(), $value ); ?> />
                			<label class="image-select-label"
						for="<?php echo $this->id . $value; ?>"><img
                				src="<?php echo get_template_directory_uri() . '/img/' . $this->prefix . esc_html($value) . $this->suffix; ?>"
                				title="<?php echo $label; ?>"></label>
					<?php
				endforeach;
			?></div><?php
        	endif;
	}
}

function bs4_sanitize_navbar_brand( $input ) {
	return trim( wp_kses_post( force_balance_tags( $input ) ) );
}

function bs4_customize_register( $wp_customize ) {
	// Site Identity

	$wp_customize->add_setting( 'title_color', false );
	$wp_customize->add_setting( 'tagline_color', false );
	$wp_customize->add_setting( 'logo_image' );
	$wp_customize->add_setting( 'logo_placement', array( 'default' => 0 ) );

	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize,
		'title_color', array(
			'label'        => 'Site Title Color',
			'section'    => 'title_tagline',
			'settings'   => 'title_color',
			) ) );

	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize,
		'tagline_color', array(
			'label'        => 'Tagline Color',
			'section'    => 'title_tagline',
			'settings'   => 'tagline_color',
			) ) );

	$wp_customize->add_control( new WP_Customize_Image_Control(
        	$wp_customize,
        	'logo_image',
        	array(
        		'label'       => 'Site Logo',
        		'section'     => 'title_tagline',
        		'description' => '<b>Note:</b> Setting a Site Logo image will hide the Site Title and Tagline',
        		'settings'    => 'logo_image',
        		) ) );

	$wp_customize->add_control( new My_Customize_Radio_Control(
        	$wp_customize, 'logo_placement', array(
        		'settings' => 'logo_placement',
        		'section'  => 'title_tagline',
        		'label'    => 'Logo Placement',
        		'prefix'   => 'cust-logo-',
        		'choices'  => array(
                		0 => 'Left',
                		1 => 'Center',
                		2 => 'Right',
                		) ) ) );

	// Layout
	/**
	 * @see: http://v4-alpha.getbootstrap.com/layout/overview/
	 */

	$wp_customize->add_section( 'cust_layout', array(
        	'title'    => 'Layout',
        	'priority' => 35,
        	) );

	$wp_customize->add_setting( 'container_segments' , array( 'default' => 0 ) );
	$wp_customize->add_setting( 'container_width' , array( 'default' => 0 ) );
	$wp_customize->add_setting( 'content_position', array( 'default' => 0 ) );
	$wp_customize->add_setting( 'copyright_position', array( 'default' => 0 ) );

	$wp_customize->add_control( new My_Customize_Radio_Control(
        	$wp_customize, 'container_segments', array(
        		'settings' => 'container_segments',
        		'section'  => 'cust_layout',
        		'label'    => 'Container Segmentation',
        		'prefix'   => 'cust-segm-',
        		'choices'  => array(
                		0 => 'Single Folio',
                		1 => 'Multiple Bands',
                		) ) ) );

	$wp_customize->add_control( new My_Customize_Radio_Control(
        	$wp_customize, 'container_width', array(
        		'settings' => 'container_width',
        		'section'  => 'cust_layout',
        		'label'    => 'Container Width',
        		'prefix'   => 'cust-wdth-',
        		'choices'  => array(
                		0 => 'Responsive, Fixed-width',
                		1 => 'Fluid',
                		2 => 'Custom Width',
                		) ) ) );

	$wp_customize->add_control( new My_Customize_Radio_Control(
        	$wp_customize, 'content_position', array(
        		'settings' => 'content_position',
        		'section'  => 'cust_layout',
        		'label'    => 'Content Position',
        		'prefix'   => 'cust-cpos-',
        		'choices'  => array(
                		0 => 'Right',
                		1 => 'Center',
                		2 => 'Left',
                		) ) ) );

	$wp_customize->add_control( 'copyright_position', array(
        	'type'    => 'select',
        	'section' => 'cust_layout',
        	'label'   => 'Footer Text Position',
        	'choices' => array(
        		0 => 'Left',
        		1 => 'Center',
        		2 => 'Right'
        		) ) );

	// Navbar

	$wp_customize->add_section( 'cust_navbar', array(
        	'title'       => 'Navbar',
        	'description' => 'Customise the look of the Bootstrap Navbar',
        	'priority'    => 85,
        	) );

	$wp_customize->add_setting( 'navbar_color', array( 'default' => 0 ) );
	$wp_customize->add_setting( 'navbar_color_custom', array( 'default' => '#000000' ) );
	$wp_customize->add_setting( 'navbar_shading', array( 'default' => 0 ) );
	$wp_customize->add_setting( 'navbar_container', array( 'default' => 0 ) );
	$wp_customize->add_setting( 'navbar_placement', array( 'default' => 0 ) );
	$wp_customize->add_setting( 'navbar_brand', array( 'default' => '', 'sanitize_callback' => 'bs4_sanitize_navbar_brand' ) );
	// $wp_customize->add_setting( 'navbar_icon', array( 'default' => false ) );
	$wp_customize->add_setting( 'navbar_search', array( 'default' => 0 ) );

	$wp_customize->add_control( 'navbar_color', array(
        	'type'    => 'select',
        	'section' => 'cust_navbar',
        	'label'   => 'Color Scheme',
        	'choices' => array(
        		0 => 'Primary',
        		1 => 'Inverse',
        		2 => 'Faded',
        		3 => 'Default',
        		4 => 'Custom',
        		) ) );

	$wp_customize->add_control( new WP_Customize_Color_Control(
        	$wp_customize, 'navbar_color_custom', array(
        		'settings'    => 'navbar_color_custom',
        		'section'     => 'cust_navbar',
        		'label'       => 'Custom Color',
        		'description' => 'Only valid when Color Scheme set to Custom',
        		) ) );

	$wp_customize->add_control( 'navbar_shading', array(
        	'type'    => 'select',
        	'section' => 'cust_navbar',
        	'label'   => 'Shading Scheme',
        	'choices' => array(
        		0 => 'Dark',
        		1 => 'Light',
        		) ) );

	$wp_customize->add_control( 'navbar_container', array(
        	'type'    => 'select',
        	'section' => 'cust_navbar',
        	'label'   => 'Container',
        	'choices' => array(
        		0 => 'Align with content',
        		1 => 'Wide with inner items aligned',
        		) ) );

	$wp_customize->add_control( 'navbar_placement', array(
        	'type'    => 'select',
        	'section' => 'cust_navbar',
        	'label'   => 'Placement',
        	'choices' => array(
        		0 => 'Default',
        		1 => 'Fixed to Top',
        		2 => 'Fixed to Bottom',
        		) ) );

	$wp_customize->add_control( 'navbar_brand', array(
        	'type'    => 'text',
        	'section' => 'cust_navbar',
        	'label'   => 'Brand Text',
        	) );

	/* $wp_customize->add_control( new WP_Customize_Image_Control(
        	$wp_customize, 'navbar_icon', array(
        		'settings' => 'navbar_icon',
        		'section'  => 'cust_navbar',
        		'label'    => 'Brand Icon',
        		) ) );  /* */

	$wp_customize->add_control( 'navbar_search', array(
        	'type'    => 'checkbox',
        	'section' => 'cust_navbar',
        	'label'   => 'Include search box',
        	) );

	// Colors

	$wp_customize->remove_section('colors');

	// Bootstrap 4

	$wp_customize->add_section( 'cust_bootstrap', array(
        	'title'       => 'Bootstrap 4',
        	'description' => '<b>General settings for Bootstrap 4</b><br>' .
                        'This section changes the look, feel and behavior of the themes framework.' .
                        '<br><br><i class="dashicons dashicons-warning"> </i> <b>Use with caution!</b>',
        	'priority'    => 999,
        	) );

	$wp_customize->add_setting( 'load_plugins', true );
	$wp_customize->add_setting( 'bootstrap_css', '' );
	$wp_customize->add_setting( 'bootstrap_flexbox', 0 );
	$wp_customize->add_setting( 'bootstrap_js', '' );
	$wp_customize->add_setting( 'tether_js', '' );
	$wp_customize->add_setting( 'jquery_js', '' );
	$wp_customize->add_setting( 'icon_set', 'fa' );

	$wp_customize->add_control( 'load_plugins', array(
        	'type'        => 'checkbox',
        	'section'     => 'cust_bootstrap',
        	'label'       => 'Load theme plugins',
        	'description' => 'Dynamically load the TSWP-Bootsrap4 plugins.',
        	) );

	$wp_customize->add_control( 'bootstrap_css', array(
        	'type'        => 'url',
        	'section'     => 'cust_bootstrap',
        	'description' => 'URL to <code>boostrap.css</code> file. Leave blank for local copy (version ' . BS_VERSION . ').',
        	'label'       => 'Bootstrap Stylesheet',
        	'input_attrs' => array(
        		'placeholder' => 'Use local copy',
        		) ) );

	$wp_customize->add_control( 'bootstrap_flexbox', array(
        	'type'        => 'checkbox',
        	'section'     => 'cust_bootstrap',
        	'label'       => 'CSS is Flex Box variant',
        	'description' => 'Flex Box columns are always equal in height. <b>NB:</b>' .
                        ' Requires Chrome 21+, Safari 6.1+, Firefox 22+, IE 10+.',
        	) );

	$wp_customize->add_control( 'bootstrap_js', array(
        	'type'        => 'url',
        	'section'     => 'cust_bootstrap',
        	'description' => 'URL to <code>boostrap.js</code> file. Leave blank for local copy (version ' . BS_VERSION . ').',
        	'label'       => 'Bootstrap JavaScript',
        	'input_attrs' => array(
        		'placeholder' => 'Use local copy',
			) ) );

	$wp_customize->add_control( 'jquery_js', array(
        	'type'        => 'url',
        	'section'     => 'cust_bootstrap',
        	'description' => 'URL to <code>jquery.js</code> file. Leave blank for local copy (version ' . JQ_VERSION . '). Must be version 2.1 or later.',
        	'label'       => 'jQuery JavaScript',
        	'input_attrs' => array(
        		'placeholder' => 'Use local copy',
        		) ) );

	$wp_customize->add_control( 'tether_js', array(
        	'type'        => 'url',
        	'section'     => 'cust_bootstrap',
        	'description' => 'URL to <code>tether.js</code> file (only used in Tooltip shortcode). Leave blank for local copy (version ' . TE_VERSION . ').',
        	'label'       => 'Tether JavaScript',
        	'input_attrs' => array(
        		'placeholder' => 'Use local copy',
			) ) );

	$wp_customize->add_control( 'icon_set', array(
        	'type'        => 'select',
        	'section'     => 'cust_bootstrap',
        	'label'       => 'Icon set',
        	'description' => '* = Refresh this page <u>after saving</u> to see icon set specific settings. (There may be none.)',
        	'choices' => array(
        		'fa' => 'Font Awesome *',
			// 'oi' => 'Open Iconic * (default)',  // TODO: Open Iconic
        		'no' => 'None',
        		) ) );

	call_user_func('bs4_customize_register_i_'.bs4_icon_set(), $wp_customize, 'cust_bootstrap');
}
add_action( 'customize_register', 'bs4_customize_register' );
