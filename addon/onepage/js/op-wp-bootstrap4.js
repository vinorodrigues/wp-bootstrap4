/**
 * JS for Onepage functionality
 * requires jQuery Easing - http://gsgd.co.uk/sandbox/jquery/easing/
 */

jQuery(document).ready(function($) {
	"use strict";

	$('a.page-scroll').bind('click', function(event) {
		var $anchor = $(this);
		$('html, body').stop().animate({
			scrollTop: ($($anchor.attr('href')).offset().top - onepage.offset)
		}, 800, 'easeInOutExpo');
		event.preventDefault();
		return false;
	});

	if (onepage.navbar.placement == 1) {
		if (onepage.offset == 50)
			onepage.offset = parseInt($('html').css('marginTop')) +
				parseInt($('#main-navbar').css('height')) +
				parseInt($('.onepage-0').css('marginTop')) +
				parseInt($('.onepage-0').css('paddingTop'));

		$('.onepager').scrollspy({
			target: '#main-navbar',
			offset: onepage.offset + 1
		})
	}

	$(window).scroll(function() {
		var cur_pos = $(window).scrollTop();
		if (cur_pos > onepage.offset) {
			$("#main-navbar").removeClass(onepage.navbar.scrolltop).addClass(onepage.navbar.normal);
		} else {
			$("#main-navbar").removeClass(onepage.navbar.normal).addClass(onepage.navbar.scrolltop);
		}
	});

});
