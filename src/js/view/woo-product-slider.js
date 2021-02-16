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

	};
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/woo-product-slider.default",
		wooProductSlider
	);
});
