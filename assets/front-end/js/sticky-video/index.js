;(function (window, $) {
    var videoIsActive = 0;
    var eaelStickVideoHeight = '';
    var eaelVideoElement = '';
    var StickyVideo = function(scope, $) {
        var videoElement = scope.find('.eael-sticky-video-player');
        var videoElement2 = scope.find('.eael-sticky-video-player2');
        for (j = 0; j < videoElement2.length; j++) {
            var sticky2 = videoElement2[j].dataset.sticky;
            var stpos = videoElement2[j].dataset.stpos;
            if(sticky2=='yes'){
                eaelStickVideoHeight2 = ($(videoElement2[j]).parent().offset().top + $(videoElement2[j]).parent().height());
                //alert(videoElement2[j])
                $(videoElement2[j]).parent().attr('id', 'videobox');
                //$(videoElement2[i]).parent().empty().append(iframe1);
                if(videoIsActive == 0){
                    videoIsActive = 1;
                }
            }
        }
        for (i = 0; i < videoElement.length; i++) {
            var overlayImage = videoElement[i].dataset.image;
            var source = videoElement[i].dataset.source;
            var start = videoElement[i].dataset.start;
            var end = videoElement[i].dataset.end;
            var id = videoElement[i].dataset.id;
            var autoplay = videoElement[i].dataset.autoplay;
            var mute = videoElement[i].dataset.mute;
            var loop = videoElement[i].dataset.loop;
            var overlay = videoElement[i].dataset.overlay;
            
            var iframe1  =  document.createElement( 'iframe' );
            var	embed1 = "";

            if( source == 'youtube' ){
                if('yes'== autoplay){ ap = 1;
                } else{ ap = 0; }
                if('yes'== mute){ mt = 1;
                } else{ mt = 0; }
                if('yes'== loop){ lp = '1';
                } else{ lp = ''; }
                embed1 = 'https://www.youtube.com/embed/ID?autoplay='+ap+'&rel=0&controls=1&showinfo=0&mute='+mt+'&loop=1&wmode=opaque&start='+start+'&end='+end;
                iframe1.setAttribute( 'src', embed1.replace( 'ID', id ) );
                iframe1.setAttribute( 'frameborder', '0' );
                iframe1.setAttribute( 'allowfullscreen', '1' );
            }
            if( source == 'vimeo' ){
                if('yes'== autoplay){ ap = 1;
                } else{ ap = 0; }
                if('yes'== mute){ mt = 1;
                } else{ mt = 0; }
                if('yes'== loop){ lp = '1';
                } else{ lp = ''; }
                embade1 = 'https://player.vimeo.com/video/'+id+'?autoplay='+ap+'&color=009900&title=1&byline=1&portrait=1&muted='+mt+'&loop='+lp+'';
                iframe1.setAttribute( 'src', embade1 );
                iframe1.setAttribute( 'webkitallowfullscreen', true );
                iframe1.setAttribute( 'mozallowfullscreen', true );
                iframe1.setAttribute( 'allowfullscreen', true );
            }
            if( source == 'self_hosted' ){
                if('yes'== autoplay){ ap = 'autoplay';
                } else{ ap = ''; }
                if('yes'== mute){ mt = 'muted';
                } else{ mt = ''; }
                if('yes'== loop){ lp = 'loop';
                } else{ lp = ''; }
                iframe1 = '<video controls '+ap+' '+mt+' '+lp+'>'+
                            '<source src="'+id+'" type="video/mp4">'+
                            '</video>';
            }
            if('yes'==overlay){
                if(overlayImage!=''){
                    videoElement[i].style.backgroundImage = 'url('+overlayImage+')';
                } else{
                    videoElement[i].style.backgroundImage = 'url(//i.ytimg.com/vi/' + id + '/maxresdefault.jpg)';
                }
                videoElement[i].onclick = function() {
                    $('.eael-sticky-video-wrapper').removeAttr('id');
                    $(this).parent().attr('id', 'videobox');
                    eaelStickVideoHeight = ($(this).parent().offset().top + $(this).parent().height());
                    $(this).parent().empty().append(iframe1);
                    //alert(videoIsActive);
                    if(videoIsActive == 0){
                        videoIsActive = 1;
                    }
                    //videoElement[i].parentElement.classList.add("HossniMubarak");
                }
            }
            if(overlay!='yes'){
                eaelVideoElement = videoElement[i].querySelector('video');
                //alert(eaelVideoElement+'Hello');
                if('yes'== autoplay){
                    //videoElement[i].innerHTML = iframe1;
                    eaelStickVideoHeight = ($(videoElement[i]).parent().offset().top + $(videoElement[i]).parent().height());
        
                    $(videoElement[i]).parent().attr('id', 'videobox');
                    $(videoElement[i]).parent().empty().append(iframe1);
                    if(videoIsActive == 0){
                        videoIsActive = 1;
                    }
                }
                if(autoplay == ''){
                    //eaelVideoElement = videoElement[i].querySelector('video');
                    //var media = document.getElementById('player');
                    //alert(eaelVideoElement+'='+media)
                    eaelVideoElement.addEventListener("playing", function() {
                        eaelStickVideoHeight = ($(this).parent().offset().top + $(this).parent().height());
                        //alert(eaelStickVideoHeight);
                        $('.eael-sticky-video-wrapper').removeAttr('id');
                        $(this).parent().parent().attr('id', 'videobox');
                        if(videoIsActive == 0){
                            videoIsActive = 1;
                        }
                    });
                    
                }
                eaelVideoElement.addEventListener("pause", function() {
                    if(videoIsActive == 1){
                        videoIsActive = 0;
                    }
                });
            }
            /*
            eaelIframeElement = videoElement[i].querySelector('video');
            alert(eaelIframeElement);
            eaelIframeElement.addEventListener("click", function() {
                alert('Hello');
            });
            */
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
        //alert(eaelStickVideoHeight);
        if($(window).scrollTop() > eaelStickVideoHeight ) {
            if(videoIsActive == 1){
                $('#videobox').removeClass('in').addClass('out');
				//$('#videobox').css({ 'bottom' : '10', 'width' : '300px' });
            }
        }
        else {
            $('#videobox').removeClass('out').addClass('in');
            $('.eael-sticky-video-wrapper').removeAttr('style');
        }
    });
    
})(window, jQuery);