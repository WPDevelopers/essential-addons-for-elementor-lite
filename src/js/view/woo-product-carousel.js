eael.hooks.addAction("init", "ea", () => {

	const wooProductCarousel = function ($scope, $) {
		eael.hooks.doAction("quickViewAddMarkup",$scope,$);
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
			on: {
				init: function () {
					setTimeout(function (){
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
							_margin = $wooProductCarousel.data('margin-' + device);
						$margin = _margin !== undefined ? _margin : (device === 'desktop' ? $margin : 10);
						$itemsPerView = _items !== undefined && _items !== "" ? _items : (device === 'desktop' ? $items : 3);

						if( device === 'mobile' && _items === undefined ){
							$itemsPerView = 1;
						} else if ( device === 'tablet' && _items === undefined ){
							$itemsPerView = 2;
						} 
						
						el_breakpoints[bp_index] = {
							breakpoint: breakpoint.value,
							slidesPerView: $itemsPerView,
							spaceBetween: $margin
						}
						bp_index++;
					}
				});
	
				$.each(el_breakpoints, function (index, breakpoint) {
					let _index = parseInt(index);
					if (typeof el_breakpoints[_index + 1] !== 'undefined') {
						breakpoints[breakpoint.breakpoint] = {
							slidesPerView: el_breakpoints[_index + 1].slidesPerView,
							spaceBetween: el_breakpoints[_index + 1].spaceBetween
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

		eael.hooks.doAction("quickViewPopupViewInit",$scope,$);

		if (isEditMode) {
			$(".eael-product-image-wrap .woocommerce-product-gallery").css(
				"opacity",
				"1"
			);
		}

		const eael_popup = $(document).find(".eael-woocommerce-popup-view");
		if(eael_popup.length<1){
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
						$(this)[0].swiper.destroy(true, true);
						swiperLoader($(this)[0], $carouselOptions);
					}
				});
			}
		}

		eael.hooks.addAction("ea-lightbox-triggered", "ea", WooProductCarouselLoader);
		eael.hooks.addAction("ea-toggle-triggered", "ea", WooProductCarouselLoader);
	};

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

	if ( eael.elementStatusCheck('eaelWooProductSliderLoad') && window.forceFullyRun === undefined ) {
		return;
	}

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-woo-product-carousel.default",
		wooProductCarousel
	);
});
