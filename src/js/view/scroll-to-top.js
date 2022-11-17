(function ($) {
    "use strict";
    $(function () {
        let offset = 100;
        let speed = 300;
        let duration = 300;

        if ($(this).scrollTop() > offset) {
            $('.eael-ext-scroll-to-top-wrap').removeClass('scroll-to-top-hide');
        }

        if( typeof elementorFrontend !== 'undefined' && elementorFrontend ){
            elementorFrontend.elements.$body[0].addEventListener('scroll', function(){
                eaelScrollToTop(this);
            });
        }

        $(window).scroll(function () {
            eaelScrollToTop(this);
        });

        function eaelScrollToTop($currentObj){
            if ($($currentObj).scrollTop() < offset) {
                $('.eael-ext-scroll-to-top-wrap').fadeOut(duration);
            } else {
                $('.eael-ext-scroll-to-top-wrap').fadeIn(duration);
            }
        }

        $('.eael-ext-scroll-to-top-wrap').on('click', function () {
            $('html, body').animate({ scrollTop: 0 }, speed);
            return false;
        });
    });
})(jQuery);