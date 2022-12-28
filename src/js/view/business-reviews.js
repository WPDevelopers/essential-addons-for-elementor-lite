var BusinessReviewsHandler = function ($scope, $) {
    let $eael_business_reviews = $(".eael-business-reviews-wrapper", $scope);

	var $businessReviewsSlider = $scope.find('.eael-google-reviews-content').eq(0),
	$pagination = '.swiper-pagination',
	$arrow_next = '.swiper-button-next',
	$arrow_prev = '.swiper-button-prev',
	$items = 3,
	$items_tablet = 3,
	$items_mobile = 3,
	$margin = 10,
	$margin_tablet = 10,
	$margin_mobile = 10,
	$effect = 'slide',
	$speed = 400,
	$autoplay = 3000,
	$loop = 0,
	$grab_cursor = 0,
	$centeredSlides = false,
	$pause_on_hover = '';

	var $businessReviewsSliderOptions = {
		direction: 'horizontal',
		speed: $speed,
		effect: $effect,
		centeredSlides: $centeredSlides,
		grabCursor: $grab_cursor,
		autoHeight: true,
		loop: $loop,
		autoplay: {
			delay: $autoplay,
			disableOnInteraction: false
		},
		pagination: {
			el: $pagination,
			clickable: true,
		},
		navigation: {
			nextEl: $arrow_next,
			prevEl: $arrow_prev,
		},
		slidesPerView: 3,
		spaceBetween: 30
	}

	$businessReviewsSliderOptions.items = 4

	var $businessReviewsSliderObj = swiperLoader(
		$businessReviewsSlider,
		$businessReviewsSliderOptions
	)
	$businessReviewsSliderObj.then( ( $businessReviewsSliderObj ) => {
		$businessReviewsSliderObj.update()
		
		//gallery pagination
		var $paginationGallerySelector = $scope
			.find('.eael-business-reviews .eael-business-reviews-gallary-pagination')
			.eq(0)
		if ($paginationGallerySelector.length > 0) {
			swiperLoader($paginationGallerySelector, {
				spaceBetween: 20,
				centeredSlides: true,
				touchRatio: 0.2,
				slideToClickedSlide: true,
				loop: true,
				slidesPerGroup: 1,
				loopedSlides: $items,
				slidesPerView: 3,
			}).then(( $paginationGallerySlider) => {
				$businessReviewsSliderObj.controller.control = $paginationGallerySlider
				$paginationGallerySlider.controller.control = $businessReviewsSliderObj
			})
		}
	} );
};

const swiperLoader = (swiperElement, swiperConfig) => {
	if ( 'undefined' === typeof Swiper ) {
		const asyncSwiper = elementorFrontend.utils.swiper;
		return new asyncSwiper( swiperElement, swiperConfig ).then( ( newSwiperInstance ) => {
			return  newSwiperInstance;
		} );
	} else {
		return swiperPromise( swiperElement, swiperConfig );
	}
}

const swiperPromise =  (swiperElement, swiperConfig) => {
	return new Promise((resolve, reject) => {
		const swiperInstance =  new Swiper( swiperElement, swiperConfig );
		resolve( swiperInstance );
	});
}

jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-business-reviews.default",
        BusinessReviewsHandler
    );
});