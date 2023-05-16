jQuery(document).ready(function () {
	// scroll func
	jQuery(window).scroll(function () {
		var winScroll =
			document.body.scrollTop || document.documentElement.scrollTop;
		var height =
			document.documentElement.scrollHeight -
			document.documentElement.clientHeight;

		if( height === 0 ) {
			height = document.documentElement.scrollHeight;
		}
		var scrolled = (winScroll / height) * 100;

		jQuery(".eael-reading-progress-fill").css({
			width: scrolled + "%",
		});
	});
});
