var NFTGalleryHandler = function ($scope, $) {
    let $eael_nft_gallery = $(".eael-nft-gallery-wrapper", $scope);
    let $posts_per_page = $eael_nft_gallery.data("posts-per-page");
    let $total_posts = $eael_nft_gallery.data("total-posts");
    let $nomore_item_text = $eael_nft_gallery.data("nomore-item-text");
    let $next_page = $eael_nft_gallery.data("next-page");

    $scope.on("click", ".eael-nft-gallery-load-more", function (e) {
        e.preventDefault();
        $('.eael-nft-item.page-' + $next_page, $scope).removeClass('eael-d-none').addClass('eael-d-block');
        $eael_nft_gallery.attr("data-next-page", $next_page + 1);

        if ($('.eael-nft-item.page-' + $next_page, $scope).hasClass('eael-last-nft-gallery-item')) {
            $(".eael-nft-gallery-load-more", $scope).html($nomore_item_text).fadeOut('1500');
        }

        $next_page++;
    });

    // Slider js
	var $testimonialSlider = $scope.find('.eael-testimonial-slider-main').eq(0),
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
		$autoplay = 999999,
		$loop = 0,
		$grab_cursor = 0,
		$centeredSlides = true;

	var $testimonialSliderOptions = {
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

	$testimonialSliderOptions.items = 1

	var $testimonialSliderObj = swiperLoader(
		$testimonialSlider,
		$testimonialSliderOptions
	)
	$testimonialSliderObj.then( ( $testimonialSliderObj ) => {
		$testimonialSliderObj.update()
	} );

};

jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-nft-gallery.default",
        NFTGalleryHandler
    );
});