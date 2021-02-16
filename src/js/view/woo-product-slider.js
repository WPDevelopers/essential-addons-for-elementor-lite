ea.hooks.addAction("init", "ea", () => {
	const wooProductSlider = function ($scope, $) {
		var $wooProductSlider = $scope.find(".eael-woo-product-slider").eq(0),
			$autoplay =
				$wooProductSlider.data("autoplay") !== undefined
					? $wooProductSlider.data("autoplay")
					: 999999,
			$pagination =
				$wooProductSlider.data("pagination") !== undefined
					? $wooProductSlider.data("pagination")
					: ".swiper-pagination",
			$arrow_next =
				$wooProductSlider.data("arrow-next") !== undefined
					? $wooProductSlider.data("arrow-next")
					: ".swiper-button-next",
			$arrow_prev =
				$wooProductSlider.data("arrow-prev") !== undefined
					? $wooProductSlider.data("arrow-prev")
					: ".swiper-button-prev",
			$items =
				$wooProductSlider.data("items") !== undefined
					? $wooProductSlider.data("items")
					: 3,
			$items_tablet =
				$wooProductSlider.data("items-tablet") !== undefined
					? $wooProductSlider.data("items-tablet")
					: 3,
			$items_mobile =
				$wooProductSlider.data("items-mobile") !== undefined
					? $wooProductSlider.data("items-mobile")
					: 3,
			$margin =
				$wooProductSlider.data("margin") !== undefined
					? $wooProductSlider.data("margin")
					: 10,
			$margin_tablet =
				$wooProductSlider.data("margin-tablet") !== undefined
					? $wooProductSlider.data("margin-tablet")
					: 10,
			$margin_mobile =
				$wooProductSlider.data("margin-mobile") !== undefined
					? $wooProductSlider.data("margin-mobile")
					: 10,
			$effect =
				$wooProductSlider.data("effect") !== undefined
					? $wooProductSlider.data("effect")
					: "slide",
			$speed =
				$wooProductSlider.data("speed") !== undefined
					? $wooProductSlider.data("speed")
					: 400,
			$loop =
				$wooProductSlider.data("loop") !== undefined
					? $wooProductSlider.data("loop")
					: 0,
			$grab_cursor =
				$wooProductSlider.data("grab-cursor") !== undefined
					? $wooProductSlider.data("grab-cursor")
					: 0,
			$pause_on_hover =
				$wooProductSlider.data("pause-on-hover") !== undefined
					? $wooProductSlider.data("pause-on-hover")
					: "",
			$centeredSlides = $effect == "coverflow" ? true : false;

		var $sliderOptions = {
			direction: "horizontal",
			speed: $speed,
			effect: $effect,
			centeredSlides: $centeredSlides,
			grabCursor: $grab_cursor,
			autoHeight: true,
			loop: $loop,
			autoplay: {
				delay: $autoplay
			},
			pagination: {
				el: $pagination,
				clickable: true
			},
			navigation: {
				nextEl: $arrow_next,
				prevEl: $arrow_prev
			}
		};

		if($effect === 'slide' || $effect === 'coverflow') {
			$sliderOptions.breakpoints = {
				1024: {
					slidesPerView: $items,
					spaceBetween: $margin
				},
				768: {
					slidesPerView: $items_tablet,
					spaceBetween: $margin_tablet
				},
				320: {
					slidesPerView: $items_mobile,
					spaceBetween: $margin_mobile
				}
			};
		}else {
			$sliderOptions.items = 1;
		}



		var eaelWooProductSlider = new Swiper($wooProductSlider, $sliderOptions);

		if ($autoplay === 0) {
			eaelWooProductSlider.autoplay.stop();
		}

		if ($pause_on_hover && $autoplay !== 0) {
			$wooProductSlider.on("mouseenter", function() {
				eaelWooProductSlider.autoplay.stop();
			});
			$wooProductSlider.on("mouseleave", function() {
				eaelWooProductSlider.autoplay.start();
			});
		}

		// Quick view
		$(document).on("click", ".open-popup-link", function (e) {

			$(this).toggleClass('transform');

			e.preventDefault();
			e.stopPropagation();
			const $this = $(this);
			const id = $this.attr("href");
			const popup = $(id);
			const popup_details = popup.children(".eael-product-popup-details");
			if (popup_details.height() > 400) {
				popup_details.css("height", "75vh");
			} else {
				popup_details.css("height", "auto");
			}
			$(id + " .variations_form").wc_variation_form();
			popup
				.addClass("eael-product-popup-ready")
				.removeClass("eael-product-modal-removing");
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
		$scope.on(
			"click",
			".eael-product-popup-details .single_add_to_cart_button",
			function (e) {
				e.preventDefault();
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
		});

	};
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-woo-product-slider.default",
		wooProductSlider
	);
});
