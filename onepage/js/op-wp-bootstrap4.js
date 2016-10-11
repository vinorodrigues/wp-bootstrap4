/**
 * JS for Onepage functionality
 * requires jQuery Easing - http://gsgd.co.uk/sandbox/jquery/easing/
 */

jQuery(document).ready(function($) {
	"use strict";

	var panel0Offset = Math.ceil( $('.onepage-0').offset().top );

	function panelHeight() {
		var windowHeight = $(window).height();
		if (onepage.placement > 0) {
			var navBar = $('#main-navbar');
			if (navBar) windowHeight = windowHeight - navBar.outerHeight();
		}

		$('.onepage').css({'min-height': windowHeight+'px'});
		$('.onepage-0').css({'min-height': (windowHeight-panel0Offset)+'px'});

		$('.onepage').each(function() {
			var outerHeight = $(this).height(),
			    innerPanel = $(this).find('.onepage-inner');

			if ( innerPanel ) {
				innerHeight = innerPanel.height();
				if (innerHeight < outerHeight) {
					innerPanel.css({'margin-top': ((outerHeight-innerHeight)/2)+'px'});
				} else {
					innerPanel.css({'margin-top': '0'});
				}
			}
		});
	}

	panelHeight();

	$(window).resize(function() {
		panelHeight();
	});

	$('a.page-scroll').bind('click', function(event) {
		var anchor = $(this),
		    aTop = $(anchor.attr('href')).offset().top + 1;

		if (onepage.placement == 1) {
			var navBar = $('#main-navbar');
			if (navBar) aTop = aTop - navBar.outerHeight();
		}

		$('html, body').stop().animate({scrollTop: aTop}, 800, 'easeInOutExpo');
		event.preventDefault();
		return false;
	});

	if (onepage.placement > 0) {
		var nBO = 0;
		if (onepage.placement == 1) {
			var navBar = $('#main-navbar');
			if (navBar) nBO = navBar.outerHeight();
		}

		$('.onepager').scrollspy({
			target: '#main-navbar',
			offset: nBO
		})
	}

});
