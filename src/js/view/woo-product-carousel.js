ea.hooks.addAction("init", "ea", () => {
	const wooProductCarousel = function ($scope, $) {
		var $wooProductCarousel = $scope.find(".eael-woo-product-carousel").eq(0),
			$type = $wooProductCarousel.data("type"),
			$autoplay =
				$wooProductCarousel.data("autoplay") !== undefined
					? $wooProductCarousel.data("autoplay")
					: 999999,
			$pagination =
				$wooProductCarousel.data("pagination") !== undefined
					? $wooProductCarousel.data("pagination")
					: ".swiper-pagination",
			$arrow_next =
				$wooProductCarousel.data("arrow-next") !== undefined
					? $wooProductCarousel.data("arrow-next")
					: ".swiper-button-next",
			$arrow_prev =
				$wooProductCarousel.data("arrow-prev") !== undefined
					? $wooProductCarousel.data("arrow-prev")
					: ".swiper-button-prev",
			$items =
				$wooProductCarousel.data("items") !== undefined
					? $wooProductCarousel.data("items")
					: 3,
			$items_tablet =
				$wooProductCarousel.data("items-tablet") !== undefined
					? $wooProductCarousel.data("items-tablet")
					: 3,
			$items_mobile =
				$wooProductCarousel.data("items-mobile") !== undefined
					? $wooProductCarousel.data("items-mobile")
					: 3,
			$margin =
				$wooProductCarousel.data("margin") !== undefined
					? $wooProductCarousel.data("margin")
					: 0,
			$margin_tablet =
				$wooProductCarousel.data("margin-tablet") !== undefined
					? $wooProductCarousel.data("margin-tablet")
					: 0,
			$margin_mobile =
				$wooProductCarousel.data("margin-mobile") !== undefined
					? $wooProductCarousel.data("margin-mobile")
					: 0,
			$effect =
				$wooProductCarousel.data("effect") !== undefined
					? $wooProductCarousel.data("effect")
					: "slide",
			$speed =
				$wooProductCarousel.data("speed") !== undefined
					? $wooProductCarousel.data("speed")
					: 400,
			$loop =
				$wooProductCarousel.data("loop") !== undefined
					? $wooProductCarousel.data("loop")
					: 0,
			$grab_cursor =
				$wooProductCarousel.data("grab-cursor") !== undefined
					? $wooProductCarousel.data("grab-cursor")
					: 0,
			$pause_on_hover =
				$wooProductCarousel.data("pause-on-hover") !== undefined
					? $wooProductCarousel.data("pause-on-hover")
					: "",
			$centeredSlides = $effect == "coverflow" ? true : false;

		var $carouselOptions = {
			direction: "horizontal",
			speed: $speed,
			effect: $effect,
			// centeredSlides: $centeredSlides,
			grabCursor: $grab_cursor,
			autoHeight: true,
			loop: $loop,
			slidesPerGroup: 1,
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
			},
			slidesPerView: $items,
			breakpoints: {
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
			},
		};

		// if($effect === 'slide' || $effect === 'coverflow') {
		// 	$carouselOptions.breakpoints = {
		//
		// 	};
		// }else {
		// 	$carouselOptions.items = $items;
		// }

		var eaelWooProductCarousel = new Swiper($wooProductCarousel, $carouselOptions);

		if ($autoplay === 0) {
			eaelWooProductCarousel.autoplay.stop();
		}

		if ($pause_on_hover && $autoplay !== 0) {
			$wooProductCarousel.on("mouseenter", function() {
				eaelWooProductCarousel.autoplay.stop();
			});
			$wooProductCarousel.on("mouseleave", function() {
				eaelWooProductCarousel.autoplay.start();
			});
		}

		// Quick view
		$(document).on("click", ".open-popup-link", function (e) {
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
		"frontend/element_ready/eael-woo-product-carousel.default",
		wooProductCarousel
	);
});
