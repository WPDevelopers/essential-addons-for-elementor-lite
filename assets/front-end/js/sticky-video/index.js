;(function (window, $) {
    
    var videoIsActive = 0;
    var eaelStickVideoHeight = 0;
    var eaelStickVideoHeight2 = 0;
    var eaelVideoElement = '';
    var height = 0;
    var position = '';
    var swidth = '';
    var sheight = '';

    var StickyVideo = function(scope, $) {
        $('.eaelsv-sticky-player-close').hide();
        var videoElement = scope.find('.eael-sticky-video-player');
        var videoElementWithoutOverlay = scope.find('.eael-sticky-video-player2');

        // When there is no image overlay
        for (j = 0; j < videoElementWithoutOverlay.length; j++) {
            var sticky2     = videoElementWithoutOverlay[j].dataset.sticky;
            var autoplay    = videoElementWithoutOverlay[j].dataset.autoplay;
            var pos         = videoElementWithoutOverlay[j].dataset.position;
            var stickyHeight    = videoElementWithoutOverlay[j].dataset.sheight;
            var stickyWidth     = videoElementWithoutOverlay[j].dataset.swidth;
            
            // If element is Sticky video
            if(sticky2=='yes'){
                eaelVideoElement = videoElementWithoutOverlay[j].querySelector('video');
                // If autoplay is enable
                if('yes'== autoplay){
                    //eaelStickVideoHeight2 = ($(videoElementWithoutOverlay[j]).parent().offset().top + $(videoElementWithoutOverlay[j]).parent().height());
                    height = GetDomElementHeight(videoElementWithoutOverlay[j]);
                    $(videoElementWithoutOverlay[j]).parent().attr('id', 'videobox');
                    if(videoIsActive == 0){
                        videoIsActive = 1;
                        position = pos;
                        swidth = stickyWidth;
                        sheight = stickyHeight;
                    }
                }
                // When html5 video play event is cliked
                // Do the sticky process
                eaelVideoElement.addEventListener("playing", function() {
                    height = GetDomElementHeight(this);
                    $('.eael-sticky-video-wrapper').removeAttr('id');
                    $('.eael-sticky-video-wrapper').removeClass('out');
                    $(this).parent().parent().parent().attr('id', 'videobox');
                    //if(videoIsActive == 0){
                        videoIsActive = 1;
                        position = pos;
                        swidth = stickyWidth;
                        sheight = stickyHeight;
                    //}
                });
                // When html5 video pause event is cliked
                // Stop the sticky process
                eaelVideoElement.addEventListener("pause", function() {
                    if(videoIsActive == 1){
                        videoIsActive = 0;
                    }
                });
            }
        }

        // When overlay option is enable
        for (i = 0; i < videoElement.length; i++) {
            
            //var ifrm    = videoElement[i].dataset.player;
                //alert(ifrm);

            videoElement[i].onclick = function() {
                
                var ifrm = $(this).data('player');
                var sticky  = $(this).data('sticky');
                var pos2    = $(this).data('position');
                var stickyHeight2   = $(this).data('sheight');
                var stickyWidth2    = $(this).data('swidth');
                
                $(this).css('background-image', 'none');
                $(this).html(ifrm);
                //$(this).removeAttr('data-player');
                //$(this).removeClass('eael-sticky-video-player');
                //$(this).addClass('eael-sticky-video-player2');
                /*var imported = document.createElement('script');
                imported.src = 'https://dd7tel2830j4w.cloudfront.net/f1543187702754x874859008239582200/ckin.min.js';
                document.body.appendChild(imported);*/
                
                if(sticky == 'yes') {
                    //alert(sticky);
                    $('.eael-sticky-video-wrapper').removeAttr('id');
                    $('.eael-sticky-video-wrapper').removeClass('out');
                    $(this).parent().attr('id', 'videobox');
                    height = GetDomElementHeight(this);
                    //if(videoIsActive == 0){
                        videoIsActive = 1;
                        position = pos2;
                        swidth = stickyWidth2;
                        sheight = stickyHeight2;
                    //}
                }
            }
            
        }
    };

    $(window).on("elementor/frontend/init", function() {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/eael-sticky-video.default",
            StickyVideo
        );
    });
    
    $(window).scroll(function() {
        var FloatVideo =  $('.eael-sticky-video-wrapper');
        if($(window).scrollTop() > height ) {
            if(videoIsActive == 1){
                $('.eaelsv-sticky-player-close').css('display', 'block');
                $('#videobox').removeClass('in').addClass('out');
                PositionStickyPlayer(position, sheight, swidth);
            }
        }
        else {
            $('.eaelsv-sticky-player-close').hide();
            $('#videobox').removeClass('out').addClass('in');
            $('.eael-sticky-video-wrapper').removeAttr('style');
        }
    });

    $('.eaelsv-sticky-player-close').on('click', function(){
        $(this).parent().removeClass('out').addClass('in');
        $('.eael-sticky-video-wrapper').removeAttr('style');
        videoIsActive = 0;
    });

    function GetDomElementHeight(element){
        var hght = ($(element).parent().offset().top + $(element).parent().height());
        return hght;
    }

    function PositionStickyPlayer(p, h, w){
        //alert(h+'='+w);
        if(p == 'top-left'){
            $('.eael-sticky-video-wrapper.out').css('top', '40px');
            $('.eael-sticky-video-wrapper.out').css('left', '40px');
        }
        if(p == 'top-right'){
            $('.eael-sticky-video-wrapper.out').css('top', '40px');
            $('.eael-sticky-video-wrapper.out').css('right', '40px');
        }
        if(p == 'bottom-right'){
            $('.eael-sticky-video-wrapper.out').css('bottom', '40px');
            $('.eael-sticky-video-wrapper.out').css('right', '40px');
        }
        if(p == 'bottom-left'){
            $('.eael-sticky-video-wrapper.out').css('bottom', '40px');
            $('.eael-sticky-video-wrapper.out').css('left', '40px');
        }
        $('.eael-sticky-video-wrapper.out').css('width', w+'px');
        $('.eael-sticky-video-wrapper.out').css('height', h+'px');
    }
    
})(window, jQuery);