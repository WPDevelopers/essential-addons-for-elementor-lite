ea.hooks.addAction("init", "ea", () => {
	const wooProductGallery = function ($scope, $) {
		// category
		const $post_cat_wrap = $('.eael-cat-tab', $scope)
		$post_cat_wrap.on('click', 'a', function (e) {
			e.preventDefault();
			let $this = $(this);
			// tab class
			$('.eael-cat-tab li a', $scope).removeClass('active');
			$this.addClass('active');

			// collect props
			const $class = $post_cat_wrap.data('class'),
				$widget_id = $post_cat_wrap.data("widget"),
				$page_id = $post_cat_wrap.data("page-id"),
				$nonce = $post_cat_wrap.data("nonce"),
				$args = $post_cat_wrap.data('args'),
				$layout = $post_cat_wrap.data('layout'),

				$widget_class = ".elementor-element-" + $widget_id,

				$page = 1,
				$template_info = $post_cat_wrap.data('template'),
				$taxonomy = {
					taxonomy: 'product_cat',
					field: 'slug',
					terms: $('.eael-cat-tab li a.active', $scope).data('terms'),
				};

			// ajax
			$.ajax({
				url: localize.ajaxurl,
				type: 'POST',
				data: {
					action: 'eael_product_gallery',
					class: $class,
					args: $args,
					taxonomy: $taxonomy,
					template_info: $template_info,
					page: $page,
					page_id: $page_id,
					widget_id: $widget_id,
					nonce: $nonce
				},
				beforeSend: function () {
					$($widget_class + ' .woocommerce').addClass("eael-product-loader");
				},
				success: function (response) {
					var $content = $(response);
					// console.log($content);

					if ($content.hasClass('no-posts-found') || $content.length == 0) {
						// do nothing
					} else {
						$('.elementor-element-' + $widget_id + ' .eael-product-gallery .woocommerce' +
							' .eael-post-appender')
							.empty()
							.append($content);

						if ($layout == "masonry") {

							var $products = $('.eael-product-gallery .products', $scope);

							// init isotope
							var $isotope_products = $products.isotope({
								itemSelector: "li.product",
								layoutMode: $layout,
								percentPosition: true
							});

							$isotope_products.imagesLoaded().progress( function() {
								$isotope_products.isotope('layout');
							})

							$(window).on('resize', function() {
								$isotope_products.isotope('layout');
							});

						}
					}
				},
				complete: function () {
					$($widget_class + ' .woocommerce').removeClass("eael-product-loader");
				},
				error: function (response) {
					console.log(response);
				}
			});
		});


		//

		// Quick view
		$scope.on("click", ".eael-product-gallery-open-popup", function (e) {
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
						product_gallery.css("opacity",1);
						popup_view_render.addClass("elementor-" + quickview_setting.page_id)
						popup_view_render.children().addClass("elementor-element elementor-element-" + quickview_setting.widget_id)

						if (popup_details.height() > 400) {
							popup_details.css("height", "75vh");
						} else {
							popup_details.css("height", "auto");
						}
						setTimeout(function(){ product_gallery.wc_product_gallery(); }, 1000);
					}
				},
			});
		});

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

		// handle add to cart for quick view
		$(document).on(
			"click",
			".eael-woo-slider-popup .single_add_to_cart_button",
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
						}
					},
				});
			}
		);

		$(document).on("click", ".eael-product-popup-close", function (event) {
			event.stopPropagation();
			$(".eael-product-popup")
				.addClass("eael-product-modal-removing")
				.removeClass("eael-product-popup-ready");
		});
		$(document).on("click", function (event) {
			if (event.target.closest(".eael-product-popup-details")) return;
			$(".eael-product-popup.eael-product-zoom-in.eael-product-popup-ready")
				.addClass("eael-product-modal-removing")
				.removeClass("eael-product-popup-ready");
			//
		});

		if (isEditMode) {
			$(".eael-product-image-wrap .woocommerce-product-gallery").css(
				"opacity",
				"1"
			);
		}

	};


	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-woo-product-gallery.default",
		wooProductGallery
	);
});
