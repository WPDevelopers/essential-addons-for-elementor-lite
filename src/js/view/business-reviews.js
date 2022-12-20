var BusinessReviewsHandler = function ($scope, $) {
    let $eael_business_reviews = $(".eael-business-reviews-wrapper", $scope);

    // Slider js
	var $businessReviewsSlider = $scope.find('.eael-business-reviews-main').eq(0),
		$pagination = '.swiper-pagination',
		$arrow_next = '.swiper-button-next',
		$arrow_prev = '.swiper-button-prev',
		$effect = 'slide',
		$speed = 400,
		$autoplay = 999999,
		$loop = 0,
		$grab_cursor = 0,
		$centeredSlides = true;

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
	}

	$businessReviewsSliderOptions.items = 1

	console.log($businessReviewsSlider);
	console.log($businessReviewsSliderOptions);
	var $businessReviewsSliderObj = swiperLoader(
		$businessReviewsSlider,
		$businessReviewsSliderOptions
	)
	$businessReviewsSliderObj.then( ( $businessReviewsSliderObj ) => {
		$businessReviewsSliderObj.update()
	} );

};

const swiperLoader = (swiperElement, swiperConfig) => {
	if ( 'undefined' === typeof Swiper ) {
		const asyncSwiper = elementorFrontend.utils.swiper;
		return new asyncSwiper( swiperElement, swiperConfig ).then( ( newSwiperInstance ) => {
			console.log( 'New Swiper instance is ready: ', newSwiperInstance );
			
			return  newSwiperInstance;
		} );
	} else {
		console.log( 'Swiper global variable is ready, create a new instance: ', Swiper );

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