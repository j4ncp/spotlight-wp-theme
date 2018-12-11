jQuery(document).ready(function() {
	jQuery('#slideshow-container').slick({
		arrows: false,
		vertical: true,
		dots: true,
		appendDots: jQuery('#dots-list'),
		autoplay: true,
		autoplaySpeed: 3000
	});
});
