/**
 * WP-Bootstrap4
 */

jQuery(document).ready(function($) {

	var navBar = $('#main-navbar');
	if (navBar) {
		var barHeight = Math.ceil( navBar.outerHeight() );
		$('body.nav-fixed-top').css({'padding-top': barHeight+'px'});
		$('body.nav-fixed-bottom').css({'padding-bottom': barHeight+'px'});
	}

	$('#back-to-top').tooltip();

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

});
