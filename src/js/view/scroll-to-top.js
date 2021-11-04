(function ($) {
    "use strict";
    $(function () {
        let offset = 100;
        let speed = 300;
        let duration = 300;

        $(window).scroll(function () {
            if ($(this).scrollTop() < offset) {
                $('.eael-ext-scroll-to-top-button').fadeOut(duration);
            } else {
                $('.eael-ext-scroll-to-top-button').fadeIn(duration);
            }
        });

        $('.eael-ext-scroll-to-top-button').on('click', function () {
            $('html, body').animate({ scrollTop: 0 }, speed);
            return false;
        });
    });
})(jQuery);