eael.hooks.addAction("init", "ea", () => {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-woo-add-to-cart.default",
		function ($scope, $) {
			var $wrapper = $scope.find(
				'.eael-add-to-cart-wrapper[data-eael-ajax-add-to-cart="yes"]'
			);

			if (!$wrapper.length) {
				return;
			}

			var productId   = $wrapper.data("product-id");
			var productType = $wrapper.data("product-type");
			var nonce       = $wrapper.data("nonce");
			var ajaxUrl     = localize.ajaxurl;
			var $notices    = $wrapper.find(".woocommerce-notices-wrapper");

			$wrapper.off("click.eael-atc").on(
				"click.eael-atc",
				".single_add_to_cart_button",
				function (e) {
					var $button = $(this);

					// Let WooCommerce handle disabled state (e.g. no variation selected).
					if ($button.hasClass("disabled") || $button.hasClass("loading")) {
						return;
					}

					var $form = $button.closest("form");
					if (!$form.length) {
						return;
					}

					e.preventDefault();

					// Variable product: require a resolved variation_id before going async.
					if (productType === "variable") {
						var variationId = parseInt(
							$form.find('input[name="variation_id"]').val() || 0,
							10
						);
						if (variationId <= 0) {
							$form.find(".variations select").first().trigger("change");
							return;
						}
					}

					$button.addClass("loading").prop("disabled", true);
					$notices.empty();

					var data = {
						action:       "eael_ajax_add_to_cart",
						nonce:        nonce,
						product_id:   productId,
						product_type: productType,
					};

					if (productType === "variable") {
						data.variation_id = parseInt(
							$form.find('input[name="variation_id"]').val() || 0,
							10
						);
						data.quantity = parseInt($form.find("input.qty").val() || 1, 10);
						// Collect variation attribute selects.
						$form.find('[name^="attribute_"]').each(function () {
							data[$(this).attr("name")] = $(this).val();
						});
					} else if (productType === "grouped") {
						// Grouped: WooCommerce renders qty inputs as quantity[child_id].
						$form.find('input[name^="quantity["]').each(function () {
							var name = $(this).attr("name"); // e.g. quantity[371]
							data[name] = parseInt($(this).val() || 0, 10);
						});
					} else {
						data.quantity = parseInt($form.find("input.qty").val() || 1, 10);
					}

					$.post(ajaxUrl, data, function (response) {
						$button.removeClass("loading").prop("disabled", false);

						if (!response.success) {
							if (response.data && response.data.notices) {
								$notices.html(response.data.notices);
							}
							return;
						}

						// Refresh mini-cart fragments.
						$(document.body).trigger("wc_fragment_refresh");
						$(document.body).trigger("added_to_cart", [
							response.data.fragments,
							response.data.cart_hash,
							$button,
						]);

						// Show the success notice returned by WooCommerce.
						if (response.data.notices) {
							$notices.html(response.data.notices);
						}

						$button.addClass("added");
						setTimeout(function () {
							$button.removeClass("added");
						}, 2000);
					}).fail(function () {
						$button.removeClass("loading").prop("disabled", false);
					});
				}
			);
		}
	);
});
