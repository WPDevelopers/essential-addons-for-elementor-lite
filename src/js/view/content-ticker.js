var ContentTicker = function ($scope, $) {
	var $contentTicker = $scope.find(".eael-content-ticker").eq(0),
		$effect =
			$contentTicker.data("effect") !== undefined
				? $contentTicker.data("effect")
				: "slide",
		$speed =
			$contentTicker.data("speed") !== undefined
				? $contentTicker.data("speed")
				: 400,
		$autoplay =
			$contentTicker.data("autoplay") !== undefined
				? $contentTicker.data("autoplay")
				: 5000,
		$loop =
			$contentTicker.data("loop") !== undefined
				? $contentTicker.data("loop")
				: false,
		$grab_cursor =
			$contentTicker.data("grab-cursor") !== undefined
				? $contentTicker.data("grab-cursor")
				: false,
		$pagination =
			$contentTicker.data("pagination") !== undefined
				? $contentTicker.data("pagination")
				: ".swiper-pagination",
		$arrow_next =
			$contentTicker.data("arrow-next") !== undefined
				? $contentTicker.data("arrow-next")
				: ".swiper-button-next",
		$arrow_prev =
			$contentTicker.data("arrow-prev") !== undefined
				? $contentTicker.data("arrow-prev")
				: ".swiper-button-prev",
		$pause_on_hover =
			$contentTicker.data("pause-on-hover") !== undefined
				? $contentTicker.data("pause-on-hover")
				: "",
		$contentTickerOptions = {
			direction: "horizontal",
			loop: $loop,
			speed: $speed,
			effect: $effect,
			grabCursor: $grab_cursor,
			paginationClickable: true,
			autoHeight: true,
			autoplay: {
				delay: $autoplay,
			},
			pagination: {
				el: $pagination,
				clickable: true,
			},
			navigation: {
				nextEl: $arrow_next,
				prevEl: $arrow_prev,
			},
		};

	var $contentTickerSlider = new Swiper(
		$contentTicker,
		$contentTickerOptions
	);
	if ($autoplay === 0) {
		$contentTickerSlider.autoplay.stop();
	}
	if ($pause_on_hover && $autoplay !== 0) {
		$contentTicker.on("mouseenter", function () {
			$contentTickerSlider.autoplay.stop();
		});
		$contentTicker.on("mouseleave", function () {
			$contentTickerSlider.autoplay.start();
		});
	}
};
jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-content-ticker.default",
		ContentTicker
	);
});
