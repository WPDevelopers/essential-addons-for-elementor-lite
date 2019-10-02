;(function (window, $) {
    var videoIsActive = 0;
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
                alert(id);
                iframe1 = '';
            }
            
            videoElement[i].onclick = function() {
                $(this).parent().attr('id', 'videobox');
                $(this).parent().empty().append(iframe1);
                videoIsActive = 1;
                //videoElement[i].parentElement.classList.add("HossniMubarak");
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
        if($(window).scrollTop() > 299 ) {
            if(videoIsActive == 1){
                $('#videobox').removeClass('in').addClass('out');
				$('#videobox').css('bottom', '10');   
            }
        }
        else{
            $('#videobox').removeClass('out').addClass('in');
        }
    });
    
})(window, jQuery);