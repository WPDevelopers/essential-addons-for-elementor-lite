var PostGrid = function($scope, $) {
    var $gallery = $(".eael-post-appender", $scope).isotope({
        itemSelector: ".eael-grid-post",
        masonry: {
            columnWidth: ".eael-post-grid-column",
            percentPosition: true
        }
    });

    // layout gal, while images are loading
    $gallery.imagesLoaded().progress(function() {
        $gallery.isotope("layout");
    });
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-post-grid.default",
        PostGrid
    );
});
