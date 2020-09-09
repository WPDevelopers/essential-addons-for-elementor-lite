ea.hooks.addAction("init", "ea", () => {
    const wooProductCompare = function ($scope, $) {
        console.log('product compare script loaded successfully')
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/woo-product-compare.default", wooProductCompare);
});
