ea.hooks.addAction("init", "ea", () => {
    function get_configurations($contentTicker) {
        let $items =
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
                    : "";

        return {
            pauseOnHover: $pause_on_hover,
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
    }

    function autoPlayManager(element, contentTicker, options){
        if (options.autoplay.delay === 0) {
            contentTicker.autoplay.stop();
        }
        if (options.pauseOnHover && options.autoplay.delay !== 0) {
            element.on("mouseenter", function() {
                contentTicker.autoplay.stop();
            });
            element.on("mouseleave", function() {
                contentTicker.autoplay.start();
            });
        }
    }
    var ContentTicker = function($scope, $) {
        var $contentTicker = $scope.find(".eael-content-ticker").eq(0),
            contentOptions = get_configurations($contentTicker);

        swiperLoader(
            $contentTicker,
            contentOptions
        ).then((contentTicker) => {
            autoPlayManager($contentTicker, contentTicker, contentOptions);
        });

        var ContentTickerSliderr = function (element) {
            let contentTickerElements = $(element).find('.eael-content-ticker');
            if (contentTickerElements.length) {
                contentTickerElements.each(function () {
                    let $this = $(this);
                    if ($this[0].swiper) {
                        $this[0].swiper.destroy(true, true);
                        let options = get_configurations($this);
                        swiperLoader($this[0], options);
                    }
                });
            }
        }

        ea.hooks.addAction("ea-toogle-triggered", "ea", ContentTickerSliderr);
        ea.hooks.addAction("ea-lightbox-triggered", "ea", ContentTickerSliderr);
        ea.hooks.addAction("ea-advanced-tabs-triggered", "ea", ContentTickerSliderr);
        ea.hooks.addAction("ea-advanced-accordion-triggered", "ea", ContentTickerSliderr);
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

    const swiperPromise =  (swiperElement, swiperConfig) => {
        return new Promise((resolve, reject) => {
            const swiperInstance =  new Swiper( swiperElement, swiperConfig );
            resolve( swiperInstance );
        });
    }

    elementorFrontend.hooks.addAction("frontend/element_ready/eael-content-ticker.default", m_ContentTicker);
});
