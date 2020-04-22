var WooCheckout = function($scope, $) {

    $.blockUI.defaults.overlayCSS.cursor = 'default';
    function render_order_review_template(){
        var wooCheckout = $('.ea-woo-checkout');

        setTimeout(
            function () {
                $('.ea-checkout-review-order-table').addClass( 'processing' ).block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });

                $.ajax({
                    type:		'POST',
                    url:		localize.ajaxurl,
                    data:		{
                        action: 'woo_checkout_update_order_review',
                        orderReviewData : wooCheckout.data('checkout')
                    },
                    success:	function( data ) {
                        $( ".ea-checkout-review-order-table" ).replaceWith( data.order_review);
                        setTimeout(function () {
                            $( '.ea-checkout-review-order-table' ).removeClass('processing').unblock();
                        }, 100000)
                    }
                });
            },2000
        );
    }

    $(document).on('click', '.woocommerce-remove-coupon', function(e) {
        render_order_review_template();
    });

    $( 'form.checkout_coupon' ).submit(function (event) {
        render_order_review_template();
    });
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-woo-checkout.default",
        WooCheckout
    );
});
