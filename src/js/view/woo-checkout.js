var WooCheckout = function ($scope, $) {
	$.blockUI.defaults.overlayCSS.cursor = "default";
	function render_order_review_template() {
		var wooCheckout = $(".ea-woo-checkout");

		setTimeout(function () {
			$(".ea-checkout-review-order-table")
				.addClass("processing")
				.block({
					message: null,
					overlayCSS: {
						background: "#fff",
						opacity: 0.6,
					},
				});

			$.ajax({
				type: "POST",
				url: localize.ajaxurl,
				data: {
					action: "woo_checkout_update_order_review",
					orderReviewData: wooCheckout.data("checkout"),
				},
				success: function (data) {
					$(".ea-checkout-review-order-table").replaceWith(data.order_review);
					setTimeout(function () {
						$(".ea-checkout-review-order-table").removeClass("processing").unblock();
					}, 100000);
				},
			});
		}, 2000);
	}

	$(document).on("click", ".woocommerce-remove-coupon", function (e) {
		render_order_review_template();
	});

	$("form.checkout_coupon").submit(function (event) {
		render_order_review_template();
	});
	var wooCheckout = $(".ea-woo-checkout");
	wooCheckout.on( 'change', 'select.shipping_method, input[name^="shipping_method"], #ship-to-different-address input, .update_totals_on_change select, .update_totals_on_change input[type="radio"], .update_totals_on_change input[type="checkbox"]', function (){
		$( document.body ).trigger( 'update_checkout' );
		render_order_review_template();
	} ); // eslint-disable-line max-len
};

jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction("frontend/element_ready/eael-woo-checkout.default", WooCheckout);
});
