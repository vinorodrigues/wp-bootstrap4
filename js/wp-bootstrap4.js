/**
 *
 */

jQuery(document).ready(function($) {
	$(window).scroll(function () {
		if ($(this).scrollTop() > 50) {
			$('#back-to-top').fadeIn();
		} else {
			$('#back-to-top').fadeOut();
		}
	});

	// scroll body to 0px on click
	$('#back-to-top').click(function () {
		$('#back-to-top').tooltip('hide');
		$('html,body').animate({
			scrollTop: 0
		}, 800, 'easeInOutExpo');
		return false;
	});

	$('#back-to-top').tooltip();
});
