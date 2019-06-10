var PostGrid = function ($scope, $) {
    $('.eael-post-grid:not(.eael-post-carousel)').isotope({
        itemSelector: '.eael-grid-post',
        percentPosition: true,
        columnWidth: '.eael-post-grid-column'
    });
}

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-post-grid.default",
        PostGrid
    );
});