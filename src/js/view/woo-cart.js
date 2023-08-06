var qtyIncDecButton = function ($scope) {
        $scope = $scope.type === 'updated_wc_div' ? document : $scope;
        jQuery('.eael-woo-cart-table .product-quantity div.quantity', $scope)
            .prepend('<span class="eael-cart-qty-minus" data-action-type="minus">-</span>')
            .append('<span class="eael-cart-qty-plus" data-action-type="plus">+</span>');
    },
    WooCart = function ($scope, $) {
        qtyIncDecButton($scope);

        $($scope, document).on('click', 'div.quantity .eael-cart-qty-minus, div.quantity .eael-cart-qty-plus', function () {
            var $this = $(this),
                qtyInput = $this.siblings('input[type="number"]'),
                qty = parseInt(qtyInput.val(), 10),
                min = qtyInput.attr('min'),
                min = (min === undefined || min === '') ? 0 : parseInt(min, 10),
                minCondition = min >= 0 ? min < qty : true,
                max = qtyInput.attr('max'),
                maxCondition = (max !== undefined && max !== '') ? parseInt(max, 10) > qty : true,
                buttonType = $this.data('action-type');

            if (buttonType === 'minus') {
                if (minCondition) {
                    qtyInput.val(qty - 1);
                    qtyInput.trigger('change');
                }
            } else {
                if (maxCondition) {
                    qtyInput.val(qty + 1);
                    qtyInput.trigger('change');
                }
            }
        });


        let wrapper = jQuery('.eael-woo-cart-wrapper');

        if (wrapper.hasClass('eael-auto-update')) {
            jQuery($scope, document).on('change', '.quantity input[type="number"]', function () {
                jQuery('button[name="update_cart"]').attr('aria-disabled', 'false').removeAttr('disabled').click();
            })
        }
    };

jQuery(document).on('updated_wc_div', qtyIncDecButton);

jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-woo-cart.default", WooCart);
});