;(function($){

    var videoIsActive = 0;
    var eaelVideoElement = '';
    var height = 0;
    var position = '';
    var swidth = '';
    var sheight = '';
    var playerAbc = '';
    var eaelsvPlayerClass = '';
    var sticky = '';
    var autoplay = '';
    var overlay = '';
    var eaelsvCntr = 0;

    $(window).on("elementor/frontend/init",function(){
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-sticky-video.default',function($scope, $){
            
            $scope.find(".eael-sticky-video-player2").each(function(){
                var element = $(this); //[0];
                
                if(element){
                    sticky      = element.data('sticky');
                    autoplay    = element.data('autoplay');
                    position    = element.data('position');
                    sheight     = element.data('sheight');
                    swidth      = element.data('swidth');
                    overlay     = element.data('overlay');
                    eaelsvCntr = eaelsvCntr+1;

                    element.find( 'div, video' ).attr('id', 'eaelsv-player-'+eaelsvCntr);
                    eaelsvPlayerClass = element.find( 'div, video' ).attr('id');
                    playerAbc = new Plyr('#'+eaelsvPlayerClass);

                    // If element is Sticky video
                    if(sticky == 'yes'){
                        // If autoplay is enable
                        if(( 'yes'== autoplay ) && ( overlay == 'no' )){
                            
                            height = GetDomElementHeight(this);
                            element.parent().attr('id', 'videobox');
                            if(videoIsActive == 0){
                                videoIsActive = 1;
                            }
                        }
                        
                        // When play event is cliked
                        // Do the sticky process
                        PlayerPlay(playerAbc, this);
                    }

                    // Overlay Operation Started
                    if ( overlay === 'yes' ){
                        var ovrlyElmnt = element.prev();
                        element.find( 'div, video' ).attr('id', 'eaelsv-player-'+eaelsvCntr);
                        
                        $(ovrlyElmnt).on('click', function(){
                            $(this).css('display', 'none');
                            
                            //runStickyPlayer(a1);
                            if(($(this).next().data('autoplay')) === 'yes'){
                                var a1 = $(this).next().find( 'div, video' ).attr('id');
                                //alert(a1);
                                runStickyPlayer(a1);
                                height = GetDomElementHeight(this);
                                $(this).parent().attr('id', 'videobox');
                                videoIsActive = 1;
                            }
                        });
                    }
                }
            });

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

        }) ;
    });

    $(window).scroll(function() {
        var FloatVideo =  $('.eael-sticky-video-wrapper');
        //alert(videoIsActive)
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

    function GetDomElementHeight(element){
        var hght = ($(element).parent().offset().top + $(element).parent().height());
        return hght;
    }

    function PositionStickyPlayer(p, h, w){
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

    function PlayerPlay(a, b){
        a.on('play', function(event) {
            height = GetDomElementHeight(this);
            $('.eael-sticky-video-wrapper').removeAttr('id');
            $('.eael-sticky-video-wrapper').removeClass('out');
            $(this).parent().parent().attr('id', 'videobox');
            
            videoIsActive   = 1;
            position        = $(b).data('position');
            sheight         = $(b).data('sheight');
            swidth          = $(b).data('swidth');
            //alert( sheight+'='+swidth );
        });
    }

    function runStickyPlayer(cls){
        //ovrplyer.stop();
        if( cls!=='' ){
            var ovrplyer = new Plyr('#'+cls);
            //alert(cls);
            ovrplyer.restart();
        }
    }

    $(document).ready(function(){
        //do something
    });
})(jQuery);