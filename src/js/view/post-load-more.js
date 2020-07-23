var PostGridLoadMore = function ($scope, $) {

    $(document).on('click', function(e) {
        e.preventDefault();
		e.stopPropagation();
        e.stopImmediatePropagation();

        console.log('clicked');
        
    });

}
jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction("frontend/element_ready/eael-post-grid.default", PostGridLoadMore);
});
