<?php
/**
 * @see: http://code.tutsplus.com/tutorials/how-to-create-custom-wordpress-writemeta-boxes--wp-20336
 * @see: http://themefoundation.com/wordpress-meta-boxes-guide/
 */

/**
 * Register meta box(es).
 */
function bs4_onepage_add_meta_boxes() {
	add_meta_box( 'bs4-onepage-page-options-bg',  // id
		'Front Page Background',  // title
		'bs4_onepage_add_meta_boxes_cb_bg',  // callback
		'page',  // screen
		'side'  // context - normal', 'side' & 'advanced'.
		// 'default'  // priority
		);
}

add_action( 'add_meta_boxes', 'bs4_onepage_add_meta_boxes' );


/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function bs4_onepage_add_meta_boxes_cb_bg( $post ) {
	wp_nonce_field(plugin_basename(__FILE__), 'bs4_onepage_meta_bg_nonce');
	$post_meta = get_post_meta( $post->ID );

	$bs4_bg_image = isset($post_meta['bs4-bg-image'][0]) ? $post_meta['bs4-bg-image'][0] : '';
	$bs4_bg_repeat = isset($post_meta['bs4-bg-repeat'][0]) ? $post_meta['bs4-bg-repeat'][0] : 'repeat';
	$bs4_bg_position = isset($post_meta['bs4-bg-position'][0]) ? $post_meta['bs4-bg-position'][0] : 'left';
	$bs4_bg_attachment = isset($post_meta['bs4-bg-attachment'][0]) ? $post_meta['bs4-bg-attachment'][0] : 'scroll';
	$bs4_bg_size = isset($post_meta['bs4-bg-size'][0]) ? $post_meta['bs4-bg-size'][0] : 'auto';

?>
	<p><label for="metabox-bg-val"><strong>Background Image</strong>
	<div id="metabox-bg-wrn" class="thumbnail placeholder"<?php if ('' != $bs4_bg_image) echo ' style="display: none;"'; ?>>No image selected</div>
	<img id="metabox-bg-img" class="thumbnail thumbnail-image" draggable="false" alt="" src="<?= $bs4_bg_image ?>" <?php if ('' == $bs4_bg_image) echo ' style="display: none;"'; ?>/>
	<input id="metabox-bg-val" type="text" name="bs4-bg-image" value="<?= $bs4_bg_image ?>" />
	<button id="metabox-bg-btn" class="button button-secondary">Select Image</button>
	<button id="metabox-bg-clr" class="button">Clear</button>
	</label></p>

	<div id="metabox-bg-ops"<?php if ('' == $bs4_bg_image) echo ' style="display:none;"'; ?>>
	<p><strong>Background Repeat</strong></p>
	<label><input type="radio" name="bs4-bg-repeat" value="no-repeat" <?php checked( $bs4_bg_repeat, 'no-repeat' ); ?>> No Repeat<br></label>
	<label><input type="radio" name="bs4-bg-repeat" value="repeat" <?php checked( $bs4_bg_repeat, 'repeat' ); ?>> Tile<br></label>
	<label><input type="radio" name="bs4-bg-repeat" value="repeat-x" <?php checked( $bs4_bg_repeat, 'repeat-x' ); ?>> Tile Horizontally<br></label>
	<label><input type="radio" name="bs4-bg-repeat" value="repeat-y" <?php checked( $bs4_bg_repeat, 'repeat-y' ); ?>> Tile Vertically<br></label>

	<p><strong>Background Position</strong></p>
	<label><input type="radio" name="bs4-bg-position" value="left" <?php checked( $bs4_bg_position, 'left' ); ?>> Left<br></label>
	<label><input type="radio" name="bs4-bg-position" value="center" <?php checked( $bs4_bg_position, 'center' ); ?>> Center<br></label>
	<label><input type="radio" name="bs4-bg-position" value="right" <?php checked( $bs4_bg_position, 'right' ); ?>> Rigth<br></label>

	<p><strong>Background Attachment</strong></p>
	<label><input type="radio" name="bs4-bg-attachment" value="scroll" <?php checked( $bs4_bg_attachment, 'scroll' ); ?>> Scroll<br></label>
	<label><input type="radio" name="bs4-bg-attachment" value="fixed" <?php checked( $bs4_bg_attachment, 'fixed' ); ?>> Fixed<br></label>
	</div>

	<p><strong>Background Size</strong></p>
	<label><input type="radio" name="bs4-bg-size" value="auto" <?php checked( $bs4_bg_size, 'auto' ); ?>> Default<br></label>
	<?php /* <label><input type="radio" name="bs4-bg-size" value="100%" <?php checked( $bs4_bg_size, '100%' ); ?>> Full Width<br></label>
	<label><input type="radio" name="bs4-bg-size" value="auto 100%" <?php checked( $bs4_bg_size, 'auto 100%' ); ?>> Full Height<br></label>
	*/ ?><label><input type="radio" name="bs4-bg-size" value="contain" <?php checked( $bs4_bg_size, 'contain' ); ?>> Letterbox Scale<br></label>
	<label><input type="radio" name="bs4-bg-size" value="cover" <?php checked( $bs4_bg_size, 'cover' ); ?>> Scale &amp; Crop<br></label>
	<label><input type="radio" name="bs4-bg-size" value="100% 100%" <?php checked( $bs4_bg_size, '100% 100%' ); ?>> Stretch<br></label>
	</div>
<?php
}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function bs4_onepage_metabox_save_post( $post_id ) {
	// Checks save status and nonce
	if ( wp_is_post_autosave( $post_id ) ) return;
	if ( wp_is_post_revision( $post_id ) ) return;

	$is_valid_nonce = ( isset( $_POST['bs4_onepage_meta_bg_nonce'] ) &&
		wp_verify_nonce( $_POST['bs4_onepage_meta_bg_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false';
	if ( !$is_valid_nonce ) return;

	// Checks for input and sanitizes/saves if needed
	if ( isset( $_POST['bs4-bg-image'] ) )
		update_post_meta( $post_id, 'bs4-bg-image', $_POST['bs4-bg-image'] );
	if ( isset( $_POST[ 'bs4-bg-repeat' ] ) )
		update_post_meta( $post_id, 'bs4-bg-repeat', $_POST['bs4-bg-repeat'] );
	if ( isset( $_POST[ 'bs4-bg-position' ] ) )
		update_post_meta( $post_id, 'bs4-bg-position', $_POST['bs4-bg-position'] );
	if ( isset( $_POST[ 'bs4-bg-attachment' ] ) )
		update_post_meta( $post_id, 'bs4-bg-attachment', $_POST['bs4-bg-attachment'] );
	if ( isset( $_POST[ 'bs4-bg-size' ] ) )
		update_post_meta( $post_id, 'bs4-bg-size', $_POST['bs4-bg-size'] );
}

add_action( 'save_post', 'bs4_onepage_metabox_save_post' );


/* oef */
