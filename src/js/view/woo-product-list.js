eael.hooks.addAction("init", "ea", () => {
	const wooProductList = function ($scope, $) {
		eael.hooks.doAction("quickViewAddMarkup", $scope, $);
		
		eael.hooks.doAction("quickViewPopupViewInit", $scope, $);
	};

	if ( eael.elementStatusCheck('eaelProductListLoad') && window.forceFullyRun === undefined ) {
        return;
    }

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-woo-product-list.default",
		wooProductList
	);
});
