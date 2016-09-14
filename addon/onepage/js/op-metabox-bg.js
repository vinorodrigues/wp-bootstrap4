/**
 * Attaches the image uploader to the input field
 */

jQuery(document).ready(function($) {
	// Instantiates the variable that holds the media library frame.
	var meta_image_frame;

	function meta_image_update_preview() {
		var img_src = $('#metabox-bg-val').val();
		if (!img_src.trim()) {
			$('#metabox-bg-img').hide();
			$('#metabox-bg-img').attr('src', '');
			$('#metabox-bg-ops').hide();
			$('#metabox-bg-wrn').show();
		} else {
			$('#metabox-bg-wrn').hide();
			$('#metabox-bg-img').attr('src', img_src);
			$('#metabox-bg-img').show();
			$('#metabox-bg-ops').show();
		}
	}

	// Runs when the image button is clicked.
	$('#metabox-bg-btn').click(function(e) {

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
			$('#metabox-bg-val').val(media_attachment.url);
			meta_image_update_preview();
		});

		// Opens the media library frame.
		meta_image_frame.open();
	});

	$('#metabox-bg-clr').click(function(e) {
		e.preventDefault();
		$('#metabox-bg-val').val('');
		meta_image_update_preview();
	});

	$('#metabox-bg-val').change(function() {
		alert('1');
		meta_image_update_preview();
	});
});
