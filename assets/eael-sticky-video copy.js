;(function (window, $) {
    var StickyVideo = function(scope, $) {
        var videoElement = scope.find('.eael-sticky-video-player');
        
        //alert(stickyElement);
        //$('.eaelsv-sticky-player').html('');
        for (i = 0; i < videoElement.length; i++) {
            //alert(i);
            var overlayImage = videoElement[i].dataset.image;
            var source = videoElement[i].dataset.source;
            var start = videoElement[i].dataset.start;
            var end = videoElement[i].dataset.end;
            var id = videoElement[i].dataset.id;
            var autoplay = videoElement[i].dataset.autoplay;
            var mute = videoElement[i].dataset.mute;
            var loop = videoElement[i].dataset.loop;
            if(overlayImage!=''){
                videoElement[i].style.backgroundImage = 'url('+overlayImage+')';
            } else{
                videoElement[i].style.backgroundImage = 'url(//i.ytimg.com/vi/' + id + '/maxresdefault.jpg)';
            }
            var iframe1  =  document.createElement( 'iframe' );
            var	embed1 = "";
            if( source == 'youtube' ){
                if('yes'== autoplay){
                    ap = 1;
                } else{
                    ap = 0;
                }
                if('yes'== mute){
                    mt = 1;
                } else{
                    mt = 0;
                }
                if('yes'== loop){
                    lp = '1';
                } else{
                    lp = '';
                }
                embed1 = 'https://www.youtube.com/embed/ID?autoplay='+ap+'&rel=0&controls=1&showinfo=0&mute='+mt+'&loop=1&wmode=opaque&start='+start+'&end='+end;
                iframe1.setAttribute( 'src', embed1.replace( 'ID', id ) );
                iframe1.setAttribute( 'frameborder', '0' );
                iframe1.setAttribute( 'allowfullscreen', '1' );
            }
            if( source == 'vimeo' ){
                if('yes'== autoplay){
                    ap = 1;
                } else{
                    ap = 0;
                }
                if('yes'== mute){
                    mt = 1;
                } else{
                    mt = 0;
                }
                if('yes'== loop){
                    lp = '1';
                } else{
                    lp = '';
                }
                embade1 = 'https://player.vimeo.com/video/'+id+'?autoplay='+ap+'&color=009900&title=1&byline=1&portrait=1&muted='+mt+'&loop='+lp+'';
                iframe1.setAttribute( 'src', embade1 );
                iframe1.setAttribute( 'webkitallowfullscreen', true );
                iframe1.setAttribute( 'mozallowfullscreen', true );
                iframe1.setAttribute( 'allowfullscreen', true );
                iframe1.setAttribute( 'width', '100%' );
                iframe1.setAttribute( 'height', '315' );
            }
            if( source == 'self_hosted' ){
                if('yes'== autoplay){
                    ap = 'autoplay';
                } else{
                    ap = '';
                }
                if('yes'== mute){
                    mt = 'muted';
                } else{
                    mt = '';
                }
                if('yes'== loop){
                    lp = 'loop';
                } else{
                    lp = '';
                }
                iframe1 = '<video id="player" preload controls '+ap+' '+mt+' '+lp+' poster="images/poster.jpg">'+
                            '<source src="'+id+'" type="video/mp4">'+
                            '</video>';
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