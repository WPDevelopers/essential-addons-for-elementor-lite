var ProductGrid = function ($scope, $) {

	// pagination



};

jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction("frontend/element_ready/eicon-woocommerce.default", ProductGrid);
});
