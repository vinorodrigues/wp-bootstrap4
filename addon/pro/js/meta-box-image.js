/**
 * Attaches the image uploader to the input field
 */

jQuery(document).ready(function($) {
	// Instantiates the variable that holds the media library frame.
	var meta_image_frame;

	function meta_image_update_preview() {
		img_src = $('#meta-box-image').val();
		if (!img_src.trim()) {
			$('#meta-box-image-none').removeClass('hideit');
			$('#meta-box-image-prvw').addClass('hideit');
			$('#meta-box-image-prvw').attr('src', '');
		} else {
			$('#meta-box-image-none').addClass('hideit');
			$('#meta-box-image-prvw').removeClass('hideit');
			$('#meta-box-image-prvw').attr('src', img_src);
		}
	}

	// Runs when the image button is clicked.
	$('#meta-box-image-btn').click(function(e) {

		// Prevents the default action from occuring.
		e.preventDefault();

		// If the frame already exists, re-open it.
		if ( meta_image_frame ) {
			meta_image_frame.open();
			return;
		}

		// Sets up the media library frame
		meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
			title: meta_image.title,
			button: { text:  meta_image.button },
			multiple: false,
			library: { type: 'image' }
		});

		// Runs when an image is selected.
		meta_image_frame.on('select', function() {

			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

			// Sends the attachment URL to our custom image input field.
			$('#meta-box-image').val(media_attachment.url);
			meta_image_update_preview();
		});

		// Opens the media library frame.
		meta_image_frame.open();
	});

	$('#meta-box-image-clr').click(function(e) {
		$('#meta-box-image').val('');
		e.preventDefault();
		meta_image_update_preview();
	});

	$('#meta-box-image').change(function() {
		meta_image_update_preview();
	});
});
