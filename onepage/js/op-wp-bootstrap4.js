/**
 * JS for Onepage functionality
 * requires jQuery Easing - http://gsgd.co.uk/sandbox/jquery/easing/
 */

jQuery(document).ready(function($) {
	"use strict";

	function panelHeight() {
		var innerPanel = $('.onepageInner'),
			windowHeight = $(window).height(),
			firstPanelTop = Math.ceil( $('.onepage-0').position().top );
		windowHeight = windowHeight - firstPanelTop;

		$('.onepage').css({minHeight: windowHeight+'px'});

		innerPanel.each(function() {
			var innerPanelHeight = $(this).outerHeight(),
				halfHeight = (windowHeight - innerPanelHeight) / 2;
			if (innerPanelHeight < windowHeight) {
				$(this).css({marginTop: halfHeight+'px'});
			} else {
				$(this).css({marginTop: '0'});
			}
		});
	}

	panelHeight();

	$(window).resize(function() {
		panelHeight();
	});

	$('a.page-scroll').bind('click', function(event) {
		var $anchor = $(this);
		$('html, body').stop().animate({
			scrollTop: ($($anchor.attr('href')).offset().top - onepage.offset)
		}, 800, 'easeInOutExpo');
		event.preventDefault();
		return false;
	});

	if (onepage.placement == 1) {
		$('body').css({
			'margin-top': parseInt($('#main-navbar').css('height'))
			});

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

	if (onepage.placement == 2) {
		$('body').css({
			'margin-bottom': parseInt($('#main-navbar').css('height'))
			});
	}
});
