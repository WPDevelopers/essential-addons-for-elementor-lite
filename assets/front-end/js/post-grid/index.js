var PostGrid = function($scope, $) {
    var $gallery = $(".eael-post-appender", $scope),
        $layout_mode = $gallery.data('layout-mode');
        
    if($layout_mode === 'masonry') {
        $gallery.isotope({
            itemSelector: ".eael-grid-post",
            layoutMode: $layout_mode,
            percentPosition: true
        });

        // layout gal, while images are loading
        $gallery.imagesLoaded().progress(function() {
            $gallery.isotope("layout");
        });
    }
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-post-grid.default",
        PostGrid
    );
});
