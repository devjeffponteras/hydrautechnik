(function ($) {
	"use strict";

	// Shuffle js filter and masonry
	var containerEl = document.querySelector(".shuffle-wrapper");
	if (containerEl) {
		var Shuffle = window.Shuffle;
		var myShuffle = new Shuffle(document.querySelector(".shuffle-wrapper"), {
			itemSelector: ".shuffle-item",
			buffer: 1,
		});

		jQuery('input[name="shuffle-filter"]').on("change", function (evt) {
			var input = evt.currentTarget;
			if (input.checked) {
				myShuffle.filter(input.value);
			}
		});
	}

	// animate on scroll
	AOS.init();
    
	// onepage nav
	$("#nav").onePageNav({
		scrollChange: function ($currentListItem) {
			$(".nav-holder").animate(
				{ scrollLeft: $currentListItem[0].offsetLeft },
				500
			);
		},
	});
})(jQuery);

// side navigation responsive
function openNav() {
	document.getElementById("mySidenav").style.left = "0";
	$(".dark-curtain").fadeIn();
}
/* Set the width of the side navigation to 0 */
function closeNav() {
	document.getElementById("mySidenav").style.left = "-300px";
	$(".dark-curtain").fadeOut();
}
function myFunction(x) {
	if (x.matches) {
		// If media query matches
		$(".tablet-view").addClass("sidenav").attr("id", "mySidenav");
	} else {
		$(".tablet-view").removeClass("sidenav").removeAttr("id");
	}
}

var x = window.matchMedia("(max-width: 991px)");
myFunction(x);
x.addListener(myFunction);


$("input[name='quantity']").TouchSpin();

jQuery(document).ready( function(){

	if( !jQuery('body').hasClass('device-touch') ) {

		jQuery(".hover-wrap").hover3d({
			selector: ".hover-card",
			shine: false,
			perspective: 1000,
		});

	}

});