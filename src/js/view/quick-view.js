const QuickView = {
	
	quickViewAddMarkup: ($scope, jq) => {
		const popupMarkup = `<div style="display: none" class="eael-woocommerce-popup-view eael-product-popup eael-product-zoom-in woocommerce">
                    			<div class="eael-product-modal-bg"></div>
                    			<div class="eael-popup-details-render eael-woo-slider-popup"><div class="eael-preloader"></div></div>
               				 </div>`;
		if (!jq('body > .eael-woocommerce-popup-view').length) {
			jq('body').prepend(popupMarkup);
		}
	},
	
	openPopup:($scope, $)=>{
		// Quick view
		$scope.on("click", ".open-popup-link", function (e) {
			e.preventDefault();
			e.stopPropagation();
			const $this = $(this);
			const quickview_setting = $this.data('quickview-setting');
			const popup_view = $(".eael-woocommerce-popup-view");
			popup_view.find(".eael-popup-details-render").html('<div class="eael-preloader"></div>')
			popup_view
			.addClass("eael-product-popup-ready")
			.removeClass("eael-product-modal-removing");
			popup_view.show();
			$.ajax({
			       url: localize.ajaxurl,
			       type: "post",
			       data: {
				       action: "eael_product_quickview_popup",
				       ...quickview_setting,
				       security: localize.nonce
			       },
			       success: function (response) {
				       if (response.success) {
					       const product_preview = $(response.data);
					       const popup_details = product_preview.children(".eael-product-popup-details");
					
					       popup_details.find(".variations_form").wc_variation_form()
					       const popup_view_render = popup_view.find(".eael-popup-details-render");
					
					       popup_view.find(".eael-popup-details-render").html(popup_details);
					       const product_gallery = popup_view.find(".woocommerce-product-gallery");

					       popup_view_render.addClass("elementor-" + quickview_setting.page_id)
					       popup_view_render.children().addClass("elementor-element elementor-element-" + quickview_setting.widget_id)
					
					       if (popup_details.height() > 400) {
						       popup_details.css("height", "75vh");
					       } else {
						       popup_details.css("height", "auto");
					       }
						   setTimeout(function () {
							   var setHeight = product_gallery.find('.woocommerce-product-gallery__image').height();
							   $('body').prepend('<style class="eael-quick-view-dynamic-css">.woocommerce-product-gallery .flex-viewport { height: ' + setHeight + 'px; }</style>');
							   product_gallery.wc_product_gallery();
							   product_gallery.closest('.eael-product-image-wrap').css('background', 'none');
						   }, 500);
						   setTimeout(function () {
							   $('.eael-quick-view-dynamic-css').remove();
						   }, 1500);
				       }
			       },
		       });
		});
	},
	closePopup: ($scope, jq) => {
		
		jq(document).on("click", ".eael-product-popup-close", function (event) {
			event.stopPropagation();
			QuickView.remove_product_popup(jq);
		});
		
		jq(document).on("click", function (event) {
			if (event.target.closest(".eael-product-popup-details")) return;
			QuickView.remove_product_popup(jq);
		});
		
	},
	singlePageAddToCartButton: ($scope, $) => {
		$(document).on(
			"click",
			".eael-woo-slider-popup .single_add_to_cart_button:not(.wc-variation-selection-needed)",
			function (e) {
				e.preventDefault();
				e.stopImmediatePropagation();
				var $this = $(this),
					product_id = $(this).val(),
					variation_id =
						$this
						.closest("form.cart")
						.find('input[name="variation_id"]')
						.val() || "",
					quantity = $this
					.closest("form.cart")
					.find('input[name="quantity"]')
					.val(),
					items = $this.closest("form.cart.grouped_form"),
					form = $this.closest("form.cart"),
					product_data = [];
				items = items.serializeArray();
				
				if (form.hasClass("variations_form")) {
					product_id = form.find('input[name="product_id"]').val();
				}
				if (items.length > 0) {
					items.forEach((item, index) => {
						var p_id = parseInt(item.name.replace(/[^\d.]/g, ""), 10);
						if (
							item.name.indexOf("quantity[") >= 0 &&
							item.value != "" &&
							p_id > 0
						) {
							product_data[product_data.length] = {
								product_id: p_id,
								quantity: item.value,
								variation_id: 0,
							};
						}
					});
				} else {
					product_data[0] = {
						product_id: product_id,
						quantity: quantity,
						variation_id: variation_id,
					};
				}
				$this.removeClass("eael-addtocart-added");
				$this.addClass("eael-addtocart-loading");
				$.ajax({
					       url: localize.ajaxurl,
					       type: "post",
					       data: {
						       action: "eael_product_add_to_cart",
						       product_data: product_data,
						       eael_add_to_cart_nonce: localize.nonce,
						       cart_item_data: form.serializeArray(),
					       },
					       success: function (response) {
						       if (response.success) {
							       $(document.body).trigger("wc_fragment_refresh");
							       $this.removeClass("eael-addtocart-loading");
							       $this.addClass("eael-addtocart-added");

							       if(localize.cart_redirectition=='yes'){
							       	 window.location.href=localize.cart_page_url;
							       }
						       }
					       },
				       });
			}
		);
	},
	preventStringInNumberField: ($scope, $) => {
		$(document).on(
			"keypress",
			".eael-product-details-wrap input[type=number]",
			(e) => {
				let keyValue = e.keyCode || e.which;
				let regex = /^[0-9]+$/;
				let isValid = regex.test(String.fromCharCode(keyValue));
				if (!isValid) {
					return false;
				}
				return isValid;
			}
		);
	},
	remove_product_popup: (jq) => {
		let selector = jq(".eael-product-popup.eael-product-zoom-in.eael-product-popup-ready")
		selector.addClass("eael-product-modal-removing").removeClass("eael-product-popup-ready");
		selector.find('.eael-popup-details-render').html('');
	},
}

if (!eael.elementStatusCheck('eaelQuickView')) {
	eael.hooks.addAction('quickViewAddMarkup', 'ea', QuickView.quickViewAddMarkup, 10);
	eael.hooks.addAction('quickViewPopupViewInit', 'ea', QuickView.openPopup, 10);
	eael.hooks.addAction('quickViewPopupViewInit', 'ea', QuickView.closePopup, 10);
	eael.hooks.addAction('quickViewPopupViewInit', 'ea', QuickView.singlePageAddToCartButton, 10);
	eael.hooks.addAction('quickViewPopupViewInit', 'ea', QuickView.preventStringInNumberField, 10);
}



