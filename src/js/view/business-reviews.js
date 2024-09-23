var BusinessReviewsHandler = function ($scope, $) {
    let $businessReviewsWrapper = $(".eael-business-reviews-wrapper", $scope);
	let source = $businessReviewsWrapper.attr('data-source'),
		layout = $businessReviewsWrapper.attr('data-layout');
	
	if(	source === 'google-reviews' ){
		// Slider or Grid
		if( layout === 'slider' ){
			let businessReviewsSlider = $scope.find('.eael-google-reviews-content').eq(0),
				pagination		= businessReviewsSlider.attr('data-pagination'),
				arrowNext		= businessReviewsSlider.attr('data-arrow-next'),
				arrowPrev		= businessReviewsSlider.attr('data-arrow-prev'),
				effect 			= businessReviewsSlider.attr('data-effect'),
				items 			= businessReviewsSlider.attr('data-items'),
				itemsTablet 	= businessReviewsSlider.attr('data-items_tablet'),
				itemsMobile 	= businessReviewsSlider.attr('data-items_mobile'),
				itemGap 		= businessReviewsSlider.attr('data-item_gap'),
				loop 			= businessReviewsSlider.attr('data-loop'),
				speed 			= businessReviewsSlider.attr('data-speed'),
				autoplay 		= businessReviewsSlider.attr('data-autoplay'),
				autoplayDelay 	= businessReviewsSlider.attr('data-autoplay_delay'),
				pauseOnHover	= businessReviewsSlider.attr('data-pause_on_hover'),
				grabCursor 		= businessReviewsSlider.attr('data-grab_cursor');

			let businessReviewsSliderOptions = {
				direction: 'horizontal',
				effect: effect,
				slidesPerView: items,
				loop: parseInt(loop),
				speed: parseInt(speed),
				grabCursor: parseInt(grabCursor),
				pagination: {
					el: pagination,
					clickable: true,
				},
				navigation: {
					nextEl: arrowNext,
					prevEl: arrowPrev,
				},
				autoplay: {
					delay: parseInt(autoplay) ? parseInt(autoplayDelay) : 999999,
					disableOnInteraction: false
				},
				autoHeight: true,
				spaceBetween: parseInt(itemGap),
			}

			if ( effect === 'slide' || effect === 'coverflow' ) {
				businessReviewsSliderOptions.breakpoints = {
					1024: {
						slidesPerView: items,
						spaceBetween: parseInt(itemGap),
					},
					768: {
						slidesPerView: itemsTablet,
						spaceBetween: parseInt(itemGap),
					},
					320: {
						slidesPerView: itemsMobile,
						spaceBetween: parseInt(itemGap),
					},
				}
			} else {
				businessReviewsSliderOptions.items = 1
			}

			let businessReviewsSliderObj = swiperLoader(
				businessReviewsSlider,
				businessReviewsSliderOptions
			)

			businessReviewsSliderObj.then( ( businessReviewsSliderObj ) => {
				if (autoplay === 0) {
					businessReviewsSliderObj.autoplay.stop()
				}

				if ( parseInt( pauseOnHover ) && autoplay !== 0 ) {
					businessReviewsSlider.on('mouseenter', function () {
						businessReviewsSliderObj.autoplay.stop()
					})
					businessReviewsSlider.on('mouseleave', function () {
						businessReviewsSliderObj.autoplay.start()
					})
				}
				businessReviewsSliderObj.update()
			} );
		}
	}
};

const swiperLoader = (swiperElement, swiperConfig) => {
	if ( 'undefined' === typeof Swiper || 'function' === typeof Swiper ) {
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

eael.hooks.addAction("init", "ea", () => {
	if (eael.elementStatusCheck('eaelBusinessReviews')) {
		return false;
	}

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-business-reviews.default",
		BusinessReviewsHandler
	);
});