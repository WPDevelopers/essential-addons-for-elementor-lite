var PostGrid = function ($scope, $) {
    var $gallery = $('.eael-post-grid', $scope).isotope({
        itemSelector: '.eael-grid-post',
        percentPosition: true,
        columnWidth: '.eael-post-grid-column'
    });

    // layout gal, while images are loading
    $gallery.imagesLoaded().progress(function() {
        $gallery.isotope("layout");
    });
}

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-post-grid.default",
        PostGrid
    );
});