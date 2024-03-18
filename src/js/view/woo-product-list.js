ea.hooks.addAction("init", "ea", () => {
	const wooProductList = function ($scope, $) {
		ea.hooks.doAction("quickViewAddMarkup", $scope, $);
		
		ea.hooks.doAction("quickViewPopupViewInit", $scope, $);
	};

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-woo-product-list.default",
		wooProductList
	);
});
