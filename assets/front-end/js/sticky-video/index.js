;(function (window, $) {
    var videoIsActive = 0;
    var eaelStickVideoHeight = 0;
    var eaelStickVideoHeight2 = 0;
    var eaelVideoElement = '';
   
    var StickyVideo = function(scope, $) {
        $('.eaelsv-sticky-player-close').hide();
        var videoElement = scope.find('.eael-sticky-video-player');
        var videoElementWithoutOverlay = scope.find('.eael-sticky-video-player2');
        
        for (j = 0; j < videoElementWithoutOverlay.length; j++) {
            var sticky2     = videoElementWithoutOverlay[j].dataset.sticky;
            var autoplay    = videoElementWithoutOverlay[j].dataset.autoplay;
            
            if(sticky2=='yes'){
                eaelVideoElement = videoElementWithoutOverlay[j].querySelector('video');
                if('yes'== autoplay){
                    eaelStickVideoHeight2 = ($(videoElementWithoutOverlay[j]).parent().offset().top + $(videoElementWithoutOverlay[j]).parent().height());
                    $(videoElementWithoutOverlay[j]).parent().attr('id', 'videobox');
                    if(videoIsActive == 0){
                        videoIsActive = 1;
                    }
                } else{
                    
                }
                eaelVideoElement.addEventListener("playing", function() {
                    eaelStickVideoHeight2 = ($(this).parent().offset().top + $(this).parent().height());
                    $('.eael-sticky-video-wrapper').removeAttr('id');
                    $(this).parent().parent().parent().attr('id', 'videobox');
                    if(videoIsActive == 0){
                        videoIsActive = 1;
                    }
                });
                eaelVideoElement.addEventListener("pause", function() {
                    if(videoIsActive == 1){
                        videoIsActive = 0;
                    }
                });
            }
        }

        for (i = 0; i < videoElement.length; i++) {
            var ifrm        = videoElement[i].dataset.player;
            var sticky      = videoElement[i].dataset.sticky;
            
            videoElement[i].onclick = function() {
                $('.eael-sticky-video-wrapper').removeAttr('id');
                $(this).parent().attr('id', 'videobox');
                eaelStickVideoHeight = ($(this).parent().offset().top + $(this).parent().height());
                $(this).empty().append(ifrm);
                if(sticky == 'yes') {
                    if(videoIsActive == 0){
                        videoIsActive = 1;
                    }
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
        if(eaelStickVideoHeight<100){
            height = eaelStickVideoHeight2;
        } else{
            height = eaelStickVideoHeight;
        }
        if($(window).scrollTop() > height ) {
            if(videoIsActive == 1){
                $('.eaelsv-sticky-player-close').show();
                $('#videobox').removeClass('in').addClass('out');
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
    
})(window, jQuery);