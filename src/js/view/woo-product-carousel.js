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
					: 10,
			$margin_tablet =
				$wooProductCarousel.data("margin-tablet") !== undefined
					? $wooProductCarousel.data("margin-tablet")
					: 10,
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
			$centeredSlides = $effect == "coverflow" ? true : false,
			$depth =
				$wooProductCarousel.data("depth") !== undefined
					? $wooProductCarousel.data("depth")
					: 100,
			$rotate =
				$wooProductCarousel.data("rotate") !== undefined
					? $wooProductCarousel.data("rotate")
					: 50,
			$stretch =
				$wooProductCarousel.data("stretch") !== undefined
					? $wooProductCarousel.data("stretch")
					: 10;

		// console.log($items);

		const $carouselOptions = {
			direction: "horizontal",
			speed: $speed,
			effect: $effect,
			centeredSlides: $centeredSlides,
			grabCursor: $grab_cursor,
			autoHeight: true,
			loop: $loop,
			slidesPerGroup: 1,
			autoplay: {
				delay: $autoplay,
				disableOnInteraction: false
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
		};

		if ($effect === 'slide') {

			$carouselOptions.breakpoints = {
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
			}
		}

		if ($effect === 'coverflow') {
			// $carouselOptions.slidesPerView = 'auto';
			$carouselOptions.coverflowEffect = {
				rotate: $rotate,
				stretch: $stretch,
				depth: $depth,
				modifier: 1,
				slideShadows: false,
			}
		}

		swiperLoader($wooProductCarousel, $carouselOptions).then((eaelWooProductCarousel) => {
			if ($autoplay === 0) {
				eaelWooProductCarousel.autoplay.stop();
			}

			if ($pause_on_hover && $autoplay !== 0) {
				$wooProductCarousel.on("mouseenter", function () {
					eaelWooProductCarousel.autoplay.stop();
				});
				$wooProductCarousel.on("mouseleave", function () {
					eaelWooProductCarousel.autoplay.start();
				});
			}

			//gallery pagination
			const $paginationGallerySelector = $scope
			.find('.eael-woo-product-carousel-container .eael-woo-product-carousel-gallary-pagination')
			.eq(0)
			if ($paginationGallerySelector.length > 0) {
				swiperLoader($paginationGallerySelector, {
					spaceBetween: 20,
					centeredSlides: $centeredSlides,
					touchRatio: 0.2,
					slideToClickedSlide: true,
					loop: $loop,
					slidesPerGroup: 1,
					// loopedSlides: $items,
					slidesPerView: 3,
				}).then(($paginationGallerySlider) => {
					eaelWooProductCarousel.controller.control = $paginationGallerySlider
					$paginationGallerySlider.controller.control = eaelWooProductCarousel
				});
			}
		});

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
							   product_gallery.css("opacity",1);
							   popup_view_render.addClass("elementor-" + quickview_setting.page_id)
							   popup_view_render.children().addClass("elementor-element elementor-element-" + quickview_setting.widget_id)

							   if (popup_details.height() > 400) {
								   popup_details.css("height", "75vh");
							   } else {
								   popup_details.css("height", "auto");
							   }
							   product_gallery.wc_product_gallery();
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

	const swiperLoader = (swiperElement, swiperConfig) => {
		if ('undefined' === typeof Swiper) {
			const asyncSwiper = elementorFrontend.utils.swiper;
			return new asyncSwiper(swiperElement, swiperConfig).then((newSwiperInstance) => {
				return newSwiperInstance;
			});
		} else {
			return swiperPromise(swiperElement, swiperConfig);
		}
	}

	const swiperPromise = (swiperElement, swiperConfig) => {
		return new Promise((resolve, reject) => {
			const swiperInstance = new Swiper(swiperElement, swiperConfig);
			resolve(swiperInstance);
		});
	}

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-woo-product-carousel.default",
		wooProductCarousel
	);
});
