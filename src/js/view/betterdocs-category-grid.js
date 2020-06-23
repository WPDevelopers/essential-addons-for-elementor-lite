var PostGrid = function($scope, $) {
    var $gallery = $(".eael-better-docs-category-grid", $scope),
        $layout_mode = $gallery.data('layout-mode');
        
    if($layout_mode === 'masonry') {
        $gallery.isotope({
            itemSelector: ".eael-better-docs-category-grid-post",
            layoutMode: $layout_mode,
            percentPosition: true
        });

        // layout gal, while images are loading
        $gallery.imagesLoaded().progress(function() {
            $gallery.isotope("layout");
        });
    }

    $('.eael-bd-grid-sub-cat-title').on('click', function(e) {
        e.preventDefault();
        $(this)
          .children(".toggle-arrow")
          .toggle();
        $(this)
          .next(".docs-sub-cat-list")
          .slideToggle();
    });
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-betterdocs-category-grid.default",
        PostGrid
    );
});
