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

			var productId = $wrapper.data("product-id");

			$wrapper.on("submit", "form.cart", function (e) {
				e.preventDefault();

				var $form = $(this);
				var $button = $form.find(".single_add_to_cart_button");

				if ($button.hasClass("loading") || $button.hasClass("disabled")) {
					return;
				}

				var quantity = $form.find("input.qty").val() || 1;

				$button.addClass("loading").prop("disabled", true);

				var data = {
					"add-to-cart": productId,
					product_id: productId,
					quantity: quantity,
				};

				// Include variation data when present (variable products)
				$form
					.find('input[name^="attribute_"], input[name="variation_id"]')
					.each(function () {
						data[$(this).attr("name")] = $(this).val();
					});

				var ajaxUrl =
					typeof wc_add_to_cart_params !== "undefined"
						? wc_add_to_cart_params.wc_ajax_url
								.toString()
								.replace("%%endpoint%%", "add_to_cart")
						: "/?wc-ajax=add_to_cart";

				$.post(ajaxUrl, data, function (response) {
					$button.removeClass("loading").prop("disabled", false);

					if (!response) {
						return;
					}

					if (response.error && response.product_url) {
						window.location = response.product_url;
						return;
					}

					// Refresh WooCommerce cart fragments (updates mini-cart counts, etc.)
					$(document.body).trigger("wc_fragment_refresh");
					$(document.body).trigger("added_to_cart", [
						response.fragments,
						response.cart_hash,
						$button,
					]);

					$button.addClass("added");
					setTimeout(function () {
						$button.removeClass("added");
					}, 2000);
				}).fail(function () {
					$button.removeClass("loading").prop("disabled", false);
				});
			});
		}
	);
});
