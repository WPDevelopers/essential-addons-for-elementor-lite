;(function (window, $) {
    
    var videoIsActive = 0;
    var eaelStickVideoHeight = 0;
    var eaelStickVideoHeight2 = 0;
    var eaelVideoElement = '';
    var height = 0;
   
    var StickyVideo = function(scope, $) {
        $('.eaelsv-sticky-player-close').hide();
        var videoElement = scope.find('.eael-sticky-video-player');
        var videoElementWithoutOverlay = scope.find('.eael-sticky-video-player2');
        
        // When there is no image overlay
        for (j = 0; j < videoElementWithoutOverlay.length; j++) {
            var sticky2     = videoElementWithoutOverlay[j].dataset.sticky;
            var autoplay    = videoElementWithoutOverlay[j].dataset.autoplay;
            
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
                    }
                }
                // When html5 video play event is cliked
                // Do the sticky process
                eaelVideoElement.addEventListener("playing", function() {
                    height = GetDomElementHeight(this);
                    $('.eael-sticky-video-wrapper').removeAttr('id');
                    $(this).parent().parent().parent().attr('id', 'videobox');
                    if(videoIsActive == 0){
                        videoIsActive = 1;
                    }
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
            
            videoElement[i].onclick = function() {
                var ifrm        = videoElement[i].dataset.player;
                var sticky      = videoElement[i].dataset.sticky;
                
                
                $(this).css('background-image', 'none');
                $(this).html(ifrm);
                $(this).removeAttr('data-player');
                //$(this).removeClass('eael-sticky-video-player');
                //$(this).addClass('eael-sticky-video-player2');
                var imported = document.createElement('script');
                imported.src = 'https://dd7tel2830j4w.cloudfront.net/f1543187702754x874859008239582200/ckin.min.js';
                document.body.appendChild(imported);
                if(sticky == 'yes') {
                    //alert(sticky);
                    $('.eael-sticky-video-wrapper').removeAttr('id');
                    $(this).parent().attr('id', 'videobox');
                    height = GetDomElementHeight(this);
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
        if($(window).scrollTop() > height ) {
            if(videoIsActive == 1){
                $('.eaelsv-sticky-player-close').css('display', 'block');
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

    function GetDomElementHeight(element){
        var hght = ($(element).parent().offset().top + $(element).parent().height());
        return hght;
    }
    
})(window, jQuery);