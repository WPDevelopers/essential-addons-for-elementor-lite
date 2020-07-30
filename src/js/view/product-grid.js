var ProductGrid = function($scope, $) {
    alert('hello');

    $('.open-popup-link').magnificPopup({
        type:'inline',
        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    });

};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-product-grid.default",
        ProductGrid
    );
});
