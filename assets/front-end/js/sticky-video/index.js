    
    var videoIsActive = 0;
       
    //var eaelStickVideoHeight = 0;
    //var eaelStickVideoHeight2 = 0;
    var eaelVideoElement = '';
    var eaelVdoElmntOvrly = '';
    var eaelVdoElmntOvrly2 = '';
    var height = 0;
    var position = '';
    var swidth = '';
    var sheight = '';
    var playerAbc = '';
    var playerAbcd = '';
    var eaelsvPlayerClass = '';
    var sticky = '';
    var autoplay = '';

    var StickyVideo = function(scope, $) {
        
        $('.eaelsv-sticky-player-close').hide();
        //var videoElement = scope.find('.eael-sticky-video-player');
        var videoElementWithoutOverlay = scope.find('.eael-sticky-video-player2');

        //alert(videoElementWithoutOverlay.length);
        // When there is no image overlay
        for (j = 0; j < videoElementWithoutOverlay.length; j++) {
            sticky     = videoElementWithoutOverlay[j].dataset.sticky;
            autoplay    = videoElementWithoutOverlay[j].dataset.autoplay;
            position    = videoElementWithoutOverlay[j].dataset.position;
            sheight     = videoElementWithoutOverlay[j].dataset.sheight;
            swidth      = videoElementWithoutOverlay[j].dataset.swidth;
            var overlay      = videoElementWithoutOverlay[j].dataset.overlay;
            
            eaelsvPlayerClass = $(videoElementWithoutOverlay[j]).find( 'div, video' ).attr('class');
            eaelVideoElement = document.querySelector('.'+eaelsvPlayerClass);
            playerAbc = new Plyr(eaelVideoElement);

            if(overlay == 'yes'){
                var ovrlyElmnt = $(videoElementWithoutOverlay[j]).prev();
                $(ovrlyElmnt).on('click', function(){
                    $(this).css('display', 'none');
                    //alert('abc');
                    //if('yes'== autoplay){
                        //playerAbc.play();
                    //}
                });
            }
            
            // If element is Sticky video
            if(sticky == 'yes'){
                // If autoplay is enable
                if('yes'== autoplay){
                    
                    height = GetDomElementHeight(videoElementWithoutOverlay[j]);
                    $(videoElementWithoutOverlay[j]).parent().attr('id', 'videobox');
                    if(videoIsActive == 0){
                        videoIsActive = 1;
                    }
                }
                
                // When html5 video play event is cliked
                // Do the sticky process
                PlayerPlay(playerAbc, videoElementWithoutOverlay[j]);
            }
        }

        playerAbc.on('pause', function(event) {
            if(videoIsActive == 1){
                videoIsActive = 0;
            }
        });

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
            jQuery('.eael-sticky-video-wrapper.out').css('bottom', '0px');
            jQuery('.eael-sticky-video-wrapper.out').css('right', '40px');
        }
        if(p == 'bottom-left'){
            jQuery('.eael-sticky-video-wrapper.out').css('bottom', '0px');
            jQuery('.eael-sticky-video-wrapper.out').css('left', '40px');
        }
        jQuery('.eael-sticky-video-wrapper.out').css('width', w+'px');
        jQuery('.eael-sticky-video-wrapper.out').css('height', h+'px');
    }

    function PlayerPlay(a, b){
        a.on('play', function(event) {
            height = GetDomElementHeight(this);
            jQuery('.eael-sticky-video-wrapper').removeAttr('id');
            jQuery('.eael-sticky-video-wrapper').removeClass('out');
            jQuery(this).parent().parent().attr('id', 'videobox');
            
            videoIsActive   = 1;
            position        = jQuery(b).data('position');
            sheight         = jQuery(b).data('sheight');
            swidth          = jQuery(b).data('swidth');
            //alert( sheight+'='+swidth );
        });
    }
    