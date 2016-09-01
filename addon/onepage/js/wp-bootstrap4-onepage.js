/**
 * @see http://callmenick.com/post/advanced-parallax-scrolling-effect
 */

(function(){

	var parallax = document.querySelectorAll(".onepage"),
		speed = 0.5;

	window.onscroll = function() {
		[].slice.call(parallax).forEach(function(el,i) {

			var windowYOffset = window.pageYOffset,
				elBackgrounPos = "0 " + (-(windowYOffset * speed)) + "px";

			el.style.backgroundPosition = elBackgrounPos;
		});

	};

})();
