var BusinessReviewsHandler = function ($scope, $) {
    let $businessReviewsWrapper = $(".eael-business-reviews-wrapper", $scope);
	let source = $businessReviewsWrapper.attr('data-source'),
		layout = $businessReviewsWrapper.attr('data-layout');
	
	if(	source === 'google-reviews' ){
		// Slider or Grid
		if( layout === 'slider' ){
			let businessReviewsSlider = $scope.find('.eael-google-reviews-content').eq(0),
				pagination	= businessReviewsSlider.attr('data-pagination'),
				arrowNext	= businessReviewsSlider.attr('data-arrow-next'),
				arrowPrev	= businessReviewsSlider.attr('data-arrow-prev'),
				effect 		= businessReviewsSlider.attr('data-effect'),
				items 		= businessReviewsSlider.attr('data-items'),
				loop 		= businessReviewsSlider.attr('data-loop'),
				speed 		= businessReviewsSlider.attr('data-speed');
				console.log(speed);
			let businessReviewsSliderOptions = {
				direction: 'horizontal',
				effect: effect,
				slidesPerView: items,
				loop: loop,
				speed: parseInt(speed),
				pagination: {
					el: pagination,
					clickable: true,
				},
				navigation: {
					nextEl: arrowNext,
					prevEl: arrowPrev,
				},
				autoplay: {
					delay: 3000,
					disableOnInteraction: false
				},
				autoHeight: true,
				spaceBetween: 30,
			}

			let businessReviewsSliderObj = swiperLoader(
				businessReviewsSlider,
				businessReviewsSliderOptions
			)
		}
	}
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