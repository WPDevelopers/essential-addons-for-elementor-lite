jQuery(document).ready(function() {
    jQuery('body').append('<div class="eael-scroll-progress"><div class="eael-scroll-progress-fill"></div></div>');

    jQuery(window).scroll(function() {
        var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        var scrolled = (winScroll / height) * 100;
        
        jQuery('.eael-scroll-progress-fill').css({
            width: scrolled + "%"
        })

        console.log(scrolled);
    });
});
