;(function (window, $) {
    var StickyVideo = function(scope, $) {
        alert('Hossni Mubarak123');
        var videoElement = scope.find('.eael-sticky-video-player');
        
        //alert(stickyElement);
        //$('.eaelsv-sticky-player').html('');
        for (i = 0; i < videoElement.length; i++) {
            var overlayImage = videoElement[i].dataset.image;
            var source = videoElement[i].dataset.source;
            var start = videoElement[i].dataset.start;
            var end = videoElement[i].dataset.end;
            var id = videoElement[i].dataset.id;
            if(overlayImage!=''){
                videoElement[i].style.backgroundImage = 'url('+overlayImage+')';
            } else{
                videoElement[i].style.backgroundImage = 'url(//i.ytimg.com/vi/' + id + '/maxresdefault.jpg)';
            }
            var iframe1  =  document.createElement( 'iframe' );
            var	embed1 = "";
            if( source == 'youtube' ){
                //alert(source);
                embed1 = 'https://www.youtube.com/embed/ID?autoplay=1&rel=0&controls=1&showinfo=0&mute=0&wmode=opaque&start='+start+'&end='+end;
                iframe1.setAttribute( 'src', embed1.replace( 'ID', id ) );
                iframe1.setAttribute( 'frameborder', '0' );
                iframe1.setAttribute( 'allowfullscreen', '1' );
            }
            if( source == 'vimeo' ){
                //alert(source);
                embade1 = 'https://player.vimeo.com/video/'+id+'?autoplay=1&color=009900&title=1&byline=1&portrait=1';
                iframe1.setAttribute( 'src', embade1 );
                iframe1.setAttribute( 'webkitallowfullscreen', true );
                iframe1.setAttribute( 'mozallowfullscreen', true );
                iframe1.setAttribute( 'allowfullscreen', true );
                iframe1.setAttribute( 'width', '100%' );
                iframe1.setAttribute( 'height', '315' );
            }
            videoElement[i].onclick = function() {
                $(this).parent().empty().append(iframe1);
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
        var FloatVideo =  $('.eaelsv-sticky-player');
        //alert(FloatVideo.length);
        if($(window).scrollTop() >= 300 ) {
            for (i = 0; i < FloatVideo.length; i++) {
                var sticky = FloatVideo[i].dataset.sticky;
                var src = FloatVideo[i].dataset.source;
                var id = FloatVideo[i].dataset.id;
                if(sticky){
                    if( src == 'youtube' ){
                        url = '<iframe width="100%" height="100%"'+
                                'src="https://www.youtube.com/embed/'+id+'?autoplay=1">'+
                                '</iframe>';
                    }
                    else if( src == 'vimeo' ){
                        //alert('Hello');
                        url = '<iframe width="100%" height="100%"'+
                                'src="https://player.vimeo.com/video/'+id+'?autoplay=1&color=009900&title=1&byline=1&portrait=1">'+
                                '</iframe>';
                    }
                    else{
                        url = '';
                    }
                    FloatVideo[i].classList.add("eaelsv-display-player");
                    //FloatVideo[i].style.BackgroundColor = '#FF0000!important';
                    FloatVideo[i].innerHTML = url;
                }
            }
           /*
            if(sticky=='yes'){
                alert(videoElement[i]);
                //videoElement[i].style.display = '';
                videoElement[i].classList.add("mystyle");
                
            }
            */
        }
        if(jQuery(window).scrollTop() < 300) {
            for (i = 0; i < FloatVideo.length; i++) {
                var sticky = FloatVideo[i].dataset.sticky;
                if(sticky){
                    FloatVideo[i].classList.remove("eaelsv-display-player");
                }
            }
        }
    });
    
})(window, jQuery);