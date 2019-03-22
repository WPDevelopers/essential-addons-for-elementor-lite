var ContentTicker = function($scope, $) {
    var $contentTicker = $scope.find(".eael-content-ticker").eq(0),
        $items =
            $contentTicker.data("items") !== undefined
                ? $contentTicker.data("items")
                : 1,
        $items_tablet =
            $contentTicker.data("items-tablet") !== undefined
                ? $contentTicker.data("items-tablet")
                : 1,
        $items_mobile =
            $contentTicker.data("items-mobile") !== undefined
                ? $contentTicker.data("items-mobile")
                : 1,
        $margin =
            $contentTicker.data("margin") !== undefined
                ? $contentTicker.data("margin")
                : 10,
        $margin_tablet =
            $contentTicker.data("margin-tablet") !== undefined
                ? $contentTicker.data("margin-tablet")
                : 10,
        $margin_mobile =
            $contentTicker.data("margin-mobile") !== undefined
                ? $contentTicker.data("margin-mobile")
                : 10,
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
            slidesPerView: $items,
            spaceBetween: $margin,
            grabCursor: $grab_cursor,
            paginationClickable: true,
            autoHeight: true,
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
            },
            breakpoints: {
                // when window width is <= 480px
                480: {
                    slidesPerView: $items_mobile,
                    spaceBetween: $margin_mobile
                },
                // when window width is <= 640px
                768: {
                    slidesPerView: $items_tablet,
                    spaceBetween: $margin_tablet
                }
            }
        };

    var $contentTickerSlider = new Swiper(
        $contentTicker,
        $contentTickerOptions
    );
    if ($autoplay === 0) {
        $contentTickerSlider.autoplay.stop();
    }
    if ($pause_on_hover && $autoplay !== 0) {
        $contentTicker.on("mouseenter", function() {
            $contentTickerSlider.autoplay.stop();
        });
        $contentTicker.on("mouseleave", function() {
            $contentTickerSlider.autoplay.start();
        });
    }
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-content-ticker.default",
        ContentTicker
    );
});