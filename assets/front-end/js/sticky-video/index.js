    
    var videoIsActive = 0;
       
    var eaelStickVideoHeight = 0;
    var eaelStickVideoHeight2 = 0;
    var eaelVideoElement = '';
    var height = 0;
    var position = '';
    var swidth = '';
    var sheight = '';
    var playerAbc = '';

    var StickyVideo = function(scope, $) {
        
        $('.eaelsv-sticky-player-close').hide();
        var videoElement = scope.find('.eael-sticky-video-player');
        var videoElementWithoutOverlay = scope.find('.eael-sticky-video-player2');

        alert(videoElementWithoutOverlay.length);
        // When there is no image overlay
        for (j = 0; j < videoElementWithoutOverlay.length; j++) {
            var sticky2     = videoElementWithoutOverlay[j].dataset.sticky;
            var autoplay    = videoElementWithoutOverlay[j].dataset.autoplay;
            var pos         = videoElementWithoutOverlay[j].dataset.position;
            var stickyHeight    = videoElementWithoutOverlay[j].dataset.sheight;
            var stickyWidth     = videoElementWithoutOverlay[j].dataset.swidth;
            
            //eaelVideoElement = videoElementWithoutOverlay[j].querySelector('video');
            //eaelIframeElement = videoElementWithoutOverlay[j].querySelector('iframe');
            
            var eaelsvPlayerClass = $(videoElementWithoutOverlay[j]).find( 'div' ).attr('class');
            eaelVideoElement = document.querySelector('.'+eaelsvPlayerClass);
            //alert(eaelsvPlayerClass);
            playerAbc = new Plyr(eaelVideoElement);
            //var playerAbc = Array.from(document.querySelectorAll('.'+eaelsvPlayerClass)).map(p => new Plyr(p));
           
            // If element is Sticky video
            if(sticky2=='yes'){
                // If autoplay is enable
                if('yes'== autoplay){
                    
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
                playerAbc.on('play', function(event) {
                    //alert('Hello there');
                    height = GetDomElementHeight(this);
                    $('.eael-sticky-video-wrapper').removeAttr('id');
                    $('.eael-sticky-video-wrapper').removeClass('out');
                    $(this).parent().parent().attr('id', 'videobox');
                    
                    videoIsActive = 1;
                    position = pos;
                    swidth = stickyWidth;
                    sheight = stickyHeight;
                });
        
                playerAbc.on('pause', function(event) {
                    if(videoIsActive == 1){
                        videoIsActive = 0;
                    }
                });
                /*
                eaelVideoElement.addEventListener("playing", function() {
                    height = GetDomElementHeight(this);
                    $('.eael-sticky-video-wrapper').removeAttr('id');
                    $('.eael-sticky-video-wrapper').removeClass('out');
                    $(this).parent().parent().parent().attr('id', 'videobox');
                    
                    videoIsActive = 1;
                    position = pos;
                    swidth = stickyWidth;
                    sheight = stickyHeight;
                    
                });
                // When html5 video pause event is cliked
                // Stop the sticky process
                eaelVideoElement.addEventListener("pause", function() {
                    if(videoIsActive == 1){
                        videoIsActive = 0;
                    }
                });
                

                */
                
            }
        }

        // When overlay option is enable
        for (i = 0; i < videoElement.length; i++) {

            videoElement[i].onclick = function() {
                
                var ifrm = $(this).data('player');
                var sticky  = $(this).data('sticky');
                var pos2    = $(this).data('position');
                var stickyHeight2   = $(this).data('sheight');
                var stickyWidth2    = $(this).data('swidth');
                
                $(this).css('background-image', 'none');
                $(this).html(ifrm);
                
                if(sticky == 'yes') {
                    
                    $('.eael-sticky-video-wrapper').removeAttr('id');
                    $('.eael-sticky-video-wrapper').removeClass('out');
                    $(this).parent().attr('id', 'videobox');
                    height = GetDomElementHeight(this);
                    
                    videoIsActive = 1;
                    position = pos2;
                    swidth = stickyWidth2;
                    sheight = stickyHeight2;
                }
            }
            
        }

        $('.eaelsv-sticky-player-close').on('click', function(){
           $(this).parent().removeClass('out').addClass('in');
            $('.eael-sticky-video-wrapper').removeAttr('style');
            videoIsActive = 0;
        });

        
        
    };

    jQuery(window).on("elementor/frontend/init", function() {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/eael-sticky-video.default",
            StickyVideo
        );
        //var playerAbc = Array.from(document.querySelectorAll('.eaelsv-player')).map(p => new Plyr(p));
        /*player.on('play', function(event) {
            alert('Played');
        });*/
    });
    
    jQuery(window).scroll(function() {
        var FloatVideo =  jQuery('.eael-sticky-video-wrapper');
        //alert(videoIsActive)
        if(jQuery(window).scrollTop() > height ) {
            if(videoIsActive == 1){
                jQuery('.eaelsv-sticky-player-close').css('display', 'block');
                jQuery('#videobox').removeClass('in').addClass('out');
                PositionStickyPlayer(position, sheight, swidth);
            }
        }
        else {
            jQuery('.eaelsv-sticky-player-close').hide();
            jQuery('#videobox').removeClass('out').addClass('in');
            jQuery('.eael-sticky-video-wrapper').removeAttr('style');
        }
    });

    function GetDomElementHeight(element){
        var hght = (jQuery(element).parent().offset().top + jQuery(element).parent().height());
        return hght;
    }

    function PositionStickyPlayer(p, h, w){
        if(p == 'top-left'){
            jQuery('.eael-sticky-video-wrapper.out').css('top', '40px');
            jQuery('.eael-sticky-video-wrapper.out').css('left', '40px');
        }
        if(p == 'top-right'){
            jQuery('.eael-sticky-video-wrapper.out').css('top', '40px');
            jQuery('.eael-sticky-video-wrapper.out').css('right', '40px');
        }
        if(p == 'bottom-right'){
            jQuery('.eael-sticky-video-wrapper.out').css('bottom', '40px');
            jQuery('.eael-sticky-video-wrapper.out').css('right', '40px');
        }
        if(p == 'bottom-left'){
            jQuery('.eael-sticky-video-wrapper.out').css('bottom', '40px');
            jQuery('.eael-sticky-video-wrapper.out').css('left', '40px');
        }
        jQuery('.eael-sticky-video-wrapper.out').css('width', w+'px');
        jQuery('.eael-sticky-video-wrapper.out').css('height', h+'px');
    }
    