eael.hooks.addAction("init", "ea", () => {
	const initMarquee = function ($scope, $) {
		var $wooProductCarousel = $scope.find(".eael-woo-product-carousel").eq(0);
		var $pause_on_hover =
			$wooProductCarousel.data("pause-on-hover") !== undefined
				? $wooProductCarousel.data("pause-on-hover")
				: false,
			$speed =
				$wooProductCarousel.data("speed") !== undefined
					? $wooProductCarousel.data("speed")
					: 400,
			$dragable = $wooProductCarousel.data("grab-cursor") !== undefined
				? $wooProductCarousel.data("grab-cursor")
				: false;

		if ($wooProductCarousel.hasClass('eael-marquee-carousel') && !$wooProductCarousel.hasClass('no-gsap')) {
			$(window).on('resize', function () {
				updateMarquee($wooProductCarousel, $);
			});

			$wooProductCarousel.find(".eael-marquee-wrapper").eaelmarque({
				speed: $speed / 1000,
				direction: 'left',
				pauseOnHover: $pause_on_hover,
				draggable: $dragable
			});

			updateMarquee($wooProductCarousel, $);
		}
	}
	const wooProductCarousel = function ($scope, $) {
		eael.hooks.doAction("quickViewAddMarkup", $scope, $);
		var $wooProductCarousel = $scope.find(".eael-woo-product-carousel").eq(0);

		if ($wooProductCarousel.hasClass('eael-marquee-carousel') && !$wooProductCarousel.hasClass('no-gsap')) {
			initMarquee($scope, $);
			return;
		}

		var $isMarqueeLite = $wooProductCarousel.hasClass('no-gsap');

		var $pause_on_hover =
			$wooProductCarousel.data("pause-on-hover") !== undefined
				? $wooProductCarousel.data("pause-on-hover")
				: false,
			$speed =
				$wooProductCarousel.data("speed") !== undefined
					? $wooProductCarousel.data("speed")
					: 400,
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
			$slideItems =
				$wooProductCarousel.data("slide-items") !== undefined
					? $wooProductCarousel.data("slide-items")
					: 1,
			$slideItems = '' === $slideItems ? 1 : $slideItems,
			$effect =
				$wooProductCarousel.data("effect") !== undefined
					? $wooProductCarousel.data("effect")
					: "slide",
			$loop =
				$wooProductCarousel.data("loop") !== undefined
					? $wooProductCarousel.data("loop")
					: 0,
			$grab_cursor =
				$wooProductCarousel.data("grab-cursor") !== undefined
					? $wooProductCarousel.data("grab-cursor")
					: 0,
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

		const $carouselOptions = {
			direction: "horizontal",
			speed: $speed,
			effect: $effect,
			centeredSlides: $centeredSlides,
			grabCursor: $grab_cursor,
			autoHeight: true,
			loop: $isMarqueeLite ? true : $loop,
			autoplay: {
				delay: $isMarqueeLite ? 0 : $autoplay,
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
			freeMode: false,
			allowTouchMove: !$isMarqueeLite,
			slidesPerView: $items,
			on: {
				init: function () {
					setTimeout(function () {
						window.dispatchEvent(new Event('resize'));
					}, 200);
				},
			},
		};

		if ($effect === 'slide') {
			if (typeof (localize.el_breakpoints) === 'string') {
				$carouselOptions.breakpoints = {
					1024: {
						slidesPerView: $items,
						spaceBetween: $margin,
						slidesPerGroup: $slideItems
					},
					768: {
						slidesPerView: $items_tablet,
						spaceBetween: $margin_tablet,
						slidesPerGroup: $slideItems
					},
					320: {
						slidesPerView: $items_mobile,
						spaceBetween: $margin_mobile,
						slidesPerGroup: $slideItems
					}
				};
			} else {
				let el_breakpoints = {}, breakpoints = {}, bp_index = 0,
					desktopBreakPoint = localize.el_breakpoints.widescreen.is_enabled ? localize.el_breakpoints.widescreen.value - 1 : 4800;
				el_breakpoints[bp_index] = {
					breakpoint: 0,
					slidesPerView: 0,
					spaceBetween: 0
				}
				bp_index++;
				localize.el_breakpoints.desktop = {
					is_enabled: true,
					value: desktopBreakPoint
				}

				$.each(['mobile', 'mobile_extra', 'tablet', 'tablet_extra', 'laptop', 'desktop', 'widescreen'], function (index, device) {
					let breakpoint = localize.el_breakpoints[device];
					if (breakpoint.is_enabled) {
						let _items = $wooProductCarousel.data('items-' + device),
							_slideItems = $wooProductCarousel.data('slide-items-' + device),
							_margin = $wooProductCarousel.data('margin-' + device);
						$margin = _margin !== undefined ? _margin : (device === 'desktop' ? $margin : 10);
						$itemsPerView = _items !== undefined && _items !== "" ? _items : (device === 'desktop' ? $items : 3);
						$slidePerGroup = _slideItems !== undefined && _slideItems !== "" ? _slideItems : (device === 'desktop' ? $slideItems : 1);


						if (device === 'mobile' && _items === undefined) {
							$itemsPerView = 1;
						} else if (device === 'tablet' && _items === undefined) {
							$itemsPerView = 2;
						}

						el_breakpoints[bp_index] = {
							breakpoint: breakpoint.value,
							slidesPerView: $itemsPerView,
							spaceBetween: $margin,
							slidePerGroup: $slidePerGroup
						}
						bp_index++;
					}
				});

				$.each(el_breakpoints, function (index, breakpoint) {
					let _index = parseInt(index);
					if (typeof el_breakpoints[_index + 1] !== 'undefined') {
						breakpoints[breakpoint.breakpoint] = {
							slidesPerView: el_breakpoints[_index + 1].slidesPerView,
							spaceBetween: el_breakpoints[_index + 1].spaceBetween,
							slidesPerGroup: el_breakpoints[_index + 1].slidePerGroup,
						}
					}
				});

				$carouselOptions.breakpoints = breakpoints;
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

			$carouselOptions.breakpoints = {
				1024: {
					slidesPerView: 3,
				},
				768: {
					slidesPerView: 1,
				},
				320: {
					slidesPerView: 1,
				}
			}
		}

		if ($autoplay === 0) {
			$carouselOptions.autoplay = false
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

		eael.hooks.doAction("quickViewPopupViewInit", $scope, $);

		if (isEditMode) {
			$(".eael-product-image-wrap .woocommerce-product-gallery").css(
				"opacity",
				"1"
			);
		}

		const getBuyNowAjaxUrl = () => {
			if (typeof wc_add_to_cart_params !== "undefined" && wc_add_to_cart_params.wc_ajax_url) {
				return wc_add_to_cart_params.wc_ajax_url.replace("%%endpoint%%", "add_to_cart");
			}

			if (typeof woocommerce_params !== "undefined" && woocommerce_params.wc_ajax_url) {
				return woocommerce_params.wc_ajax_url.replace("%%endpoint%%", "add_to_cart");
			}

			return null;
		};

		$scope.on("click", ".eael-buy-now-button", function (event) {
			event.preventDefault();
			event.stopPropagation();
			event.stopImmediatePropagation();

			const $button = $(this);
			const productId = parseInt($button.data("product-id"), 10);
			const quantity = parseInt($button.data("quantity"), 10) || 1;
			const checkoutUrl = $button.data("checkout-url") || $button.attr("href");

			if (typeof elementorFrontend !== "undefined" && elementorFrontend.isEditMode()) {
				return;
			}

			if (!productId) {
				return;
			}

			if ($button.hasClass("loading")) {
				return;
			}

			const ajaxUrl = getBuyNowAjaxUrl();
			if (!ajaxUrl) {
				window.location = $button.attr("href");
				return;
			}

			$button.addClass("loading");

			$.post(ajaxUrl, { product_id: productId, quantity: quantity })
				.done(function (response) {
					if (response && response.error && response.product_url) {
						window.location = response.product_url;
						return;
					}

					if (response && response.fragments) {
						$(document.body).trigger("added_to_cart", [
							response.fragments,
							response.cart_hash,
							$button
						]);
					}

					if (checkoutUrl) {
						window.location = checkoutUrl;
					}
				})
				.fail(function () {
					window.location = $button.attr("href");
				})
				.always(function () {
					$button.removeClass("loading");
				});
		});

		// Buy Now button inside Quick View popup — triggers Add to Cart then redirects to checkout
		$(document).on("click", ".eael-popup-details-render .eael-popup-buy-now-button", function (event) {
			event.preventDefault();
			event.stopPropagation();
			event.stopImmediatePropagation();

			const $button = $(this);
			const $form = $button.closest("form.cart");
			const $addToCartBtn = $form.find(".single_add_to_cart_button");
			const checkoutUrl = $button.data("checkout-url");

			// Mirror Add to Cart disabled state
			if ($addToCartBtn.hasClass("disabled") || $addToCartBtn.hasClass("wc-variation-selection-needed")) {
				// Trigger the Add to Cart click so WooCommerce shows its validation notice
				$addToCartBtn.trigger("click");
				return;
			}

			if ($button.hasClass("loading")) {
				return;
			}

			$button.addClass("loading");

			// Build product data the same way as singlePageAddToCartButton
			var product_id = $addToCartBtn.val(),
				variation_id = $form.find('input[name="variation_id"]').val() || "",
				quantity = $form.find('input[name="quantity"]').val() || 1,
				items = $form.hasClass("grouped_form") ? $form.serializeArray() : [],
				product_data = [];

			if ($form.hasClass("variations_form")) {
				product_id = $form.find('input[name="product_id"]').val();
			}

			if (items.length > 0) {
				items.forEach(function (item) {
					var p_id = parseInt(item.name.replace(/[^\d.]/g, ""), 10);
					if (item.name.indexOf("quantity[") >= 0 && item.value != "" && p_id > 0) {
						product_data.push({
							product_id: p_id,
							quantity: item.value,
							variation_id: 0,
						});
					}
				});
			} else {
				product_data.push({
					product_id: product_id,
					quantity: quantity,
					variation_id: variation_id,
				});
			}

			$.ajax({
				url: localize.ajaxurl,
				type: "post",
				data: {
					action: "eael_product_add_to_cart",
					product_data: product_data,
					eael_add_to_cart_nonce: localize.nonce,
					cart_item_data: $form.serializeArray(),
				},
				success: function (response) {
					if (response.success) {
						$(document.body).trigger("wc_fragment_refresh");
						window.location.href = checkoutUrl;
					}
				},
				error: function () {
					$button.removeClass("loading");
				},
			});
		});

		// Sync Buy Now button disabled state with Add to Cart for variable products
		$(document).on("show_variation reset_data", ".eael-popup-details-render .variations_form", function () {
			const $form = $(this);
			const $addToCartBtn = $form.find(".single_add_to_cart_button");
			const $buyNowBtn = $form.find(".eael-popup-buy-now-button");
			if ($addToCartBtn.hasClass("disabled") || $addToCartBtn.hasClass("wc-variation-selection-needed")) {
				$buyNowBtn.addClass("disabled");
			} else {
				$buyNowBtn.removeClass("disabled");
			}
		});

		const eael_popup = $(document).find(".eael-woocommerce-popup-view");
		if (eael_popup.length < 1) {
			eael_add_popup();
		}


		function eael_add_popup() {
			let markup = `<div style="display: none" class="eael-woocommerce-popup-view eael-product-popup eael-product-zoom-in woocommerce">
                    <div class="eael-product-modal-bg"></div>
                    <div class="eael-popup-details-render eael-woo-slider-popup"><div class="eael-preloader"></div></div>
                </div>`;
			$("body").append(markup);
		}

		var WooProductCarouselLoader = function ($src) {
			let productCarousels = $($src).find('.eael-woo-product-carousel');
			if (productCarousels.length) {
				productCarousels.each(function () {
					if ($(this)[0].swiper) {
						let savedParams = $(this)[0].swiper.params;
						$(this)[0].swiper.destroy(true, true);
						swiperLoader($(this)[0], savedParams);
					}
				});
			}
		}

		eael.hooks.addAction("ea-lightbox-triggered", "ea", WooProductCarouselLoader);
		eael.hooks.addAction("ea-toggle-triggered", "ea", WooProductCarouselLoader);
		eael.hooks.addAction("ea-advanced-tabs-triggered", "ea", WooProductCarouselLoader);
		eael.hooks.addAction("ea-advanced-accordion-triggered", "ea", WooProductCarouselLoader);
	};

	const updateMarquee = function ($wooProductCarousel, $) {
		let currentDevice = $('body').attr('data-elementor-device-mode');
		currentDevice = 'desktop' === currentDevice ? '' : '-' + currentDevice;

		let itemsGap = $wooProductCarousel.data('margin' + currentDevice);
		// itemsGap = itemszGap !== undefined ? itemsGap : 10;
		let itemsPerView = $wooProductCarousel.data('items' + currentDevice);

		if ((currentDevice === 'mobile' || currentDevice === 'mobile_extra') && itemsPerView === undefined) {
			itemsPerView = 1;
		} else if ((currentDevice === 'tablet' || currentDevice === 'tablet_extra') && itemsPerView === undefined) {
			itemsPerView = 2;
		} else if (currentDevice === '' && itemsPerView === undefined) {
			itemsPerView = 3;
		}

		$wooProductCarousel.find(".eael-marquee-wrapper:not(.no-gsap) .product").css({
			'width': (100 / itemsPerView) + '%',
		});

		$wooProductCarousel.find(".eael-marquee-wrapper:not(.no-gsap)").css({
			'gap': itemsGap + 'px',
		});
	}
	const swiperLoader = (swiperElement, swiperConfig) => {
		if ('undefined' === typeof Swiper || 'function' === typeof Swiper) {
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

	if (eael.elementStatusCheck('eaelWooProductSliderLoad') && window.forceFullyRun === undefined) {
		return;
	}

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-woo-product-carousel.default",
		wooProductCarousel
	);
});
