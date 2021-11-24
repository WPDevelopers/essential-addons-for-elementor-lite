let beeHiveSwiper = function ($scope, $) {
    let bhSwiper;

    if ('undefined' === typeof Swiper) {
        bhSwiper = elementorFrontend.utils.swiper;
    } else {
        bhSwiper = Swiper;
    }

    // Init swiper slider
    new bhSwiper('.swiper-slider-container', {
        effect: 'fade',
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
}

jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/beehive-image-slider.default",
        beeHiveSwiper
    );
});