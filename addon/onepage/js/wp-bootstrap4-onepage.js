/**
 * JS for Onepage functionality
 * requires jQuery Easing - http://gsgd.co.uk/sandbox/jquery/easing/
 */

jQuery(document).ready(function($) {
	"use strict";

	var menu_offset = 50;

	$('a.page-scroll').bind('click', function(event) {
		var $anchor = $(this);
		$('html, body').stop().animate({
			scrollTop: ($($anchor.attr('href')).offset().top - menu_offset)
		}, 800, 'easeInOutExpo');
		event.preventDefault();
		return false;
	});

	if (onepage.navbar_placement == 1) {
		menu_offset = parseInt($('html').css('marginTop')) +
			parseInt($('#main-navbar').css('height')) +
			parseInt($('.onepage-0').css('marginTop')) +
			parseInt($('.onepage-0').css('paddingTop'));

		$('.onepager').scrollspy({
			target: '#main-navbar',
			offset: menu_offset + 1
		})
	}

	$(window).scroll(function() {
		var cur_pos = $(window).scrollTop();
		if (cur_pos > menu_offset) {
			$("#main-navbar").removeClass("initial").addClass("scrolled");
		} else {
			$("#main-navbar").removeClass("scrolled").addClass("initial");
		}
	});

});
