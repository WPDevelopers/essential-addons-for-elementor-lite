var WooCheckout = function ($scope, $) {
	$.blockUI.defaults.overlayCSS.cursor = "default";
	
	//We added this class in body
	document.body.classList.add('eael-woo-checkout');

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

	$( document.body ).bind( 'update_checkout', ()=> {
		render_order_review_template();
	} );

	//move coupon remove message to coupon box (multi step and split layout)
	$(document.body).on('removed_coupon_in_checkout',function(){
		let message = $('.ea-woo-checkout .ms-tabs-content > .woocommerce-message,.ea-woo-checkout .split-tabs-content > .woocommerce-message').remove();
		$('.ea-woo-checkout .checkout_coupon.woocommerce-form-coupon').before(message);
	});

	$( document ).on( 'change', '.eael-checkout-cart-qty-input', function() {
        let cart_item_key = $( this ).attr( 'name' ).replace(/cart\[([\w]+)\]\[qty\]/g, "$1");
        let item_quantity = $( this ).val();
        let currentVal = parseFloat(item_quantity);
		$this = $(this);
		$( document.body ).trigger( 'update_checkout' );
        $.ajax({
			type: 'POST',
			url: localize.ajaxurl,
			data: {
				action: 'eael_checkout_cart_qty_update',
				nonce: localize.nonce,
				cart_item_key: cart_item_key,
				quantity: currentVal
			},
		});
    });

};

jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction("frontend/element_ready/eael-woo-checkout.default", WooCheckout);
});

if ( jQuery('.ea-woo-checkout').hasClass('checkout-reorder-enabled') ) {
	jQuery(document.body).on('country_to_state_changing', function (event, country, wrapper) {
		let $ = jQuery, checkout_keys = $('.ea-woo-checkout').data('checkout_ids'),
			field_wrapper = $('.ea-woo-checkout .woocommerce-billing-fields__field-wrapper, .ea-woo-checkout .woocommerce-shipping-fields__field-wrapper');
		field_wrapper.addClass('eael-reordering');
		let reorder_fields = function (type, _wrapper) {
			let $selector = $(`.woocommerce-${type}-fields__field-wrapper`);
			_wrapper = typeof _wrapper !== 'undefined' ? _wrapper : wrapper;
			$.each(checkout_keys[type], function (field_key, form_class) {
				let $fieldHtml = _wrapper.find(`#${field_key}_field`);
				if ($fieldHtml.length === 0) {
					$fieldHtml = _wrapper.find(`input[name='${field_key}']`).closest('p');
				}
				$fieldHtml.removeClass('form-row-first form-row-last form-row-wide').addClass(form_class);
				$(`#eael-wc-${type}-reordered-fields .eael-woo-${type}-fields`).append($fieldHtml);
			});
			$selector.replaceWith($(`#eael-wc-${type}-reordered-fields`).contents());
			$(`.eael-woo-${type}-fields`).toggleClass(`eael-woo-${type}-fields woocommerce-${type}-fields__field-wrapper`);
			$(`#eael-wc-${type}-reordered-fields`).html(`<div class="eael-woo-${type}-fields"></div>`);
		};
		setTimeout(function () {
			if (wrapper.hasClass(`woocommerce-billing-fields`)) {
				reorder_fields('billing');
				reorder_fields('shipping', $('.woocommerce-shipping-fields'));
			}
			if (wrapper.hasClass(`woocommerce-shipping-fields`)) {
				reorder_fields('shipping');
				reorder_fields('billing', $('.woocommerce-billing-fields'));
			}
			field_wrapper.removeClass('eael-reordering');
		}, 500);
	});
}
let change_button_text = function (){
	let $ = jQuery, button_texts = $('.ea-woo-checkout').data('button_texts');
	setTimeout(function() {
		if (button_texts.place_order !== ''){
			$('#place_order').text(button_texts.place_order);
		}
	}, 500);
}
jQuery(document.body).on('update_checkout payment_method_selected updated_checkout', function(event) {
	change_button_text();
}).on('click', '.woocommerce-checkout-payment li label', function (){
	change_button_text();
});
