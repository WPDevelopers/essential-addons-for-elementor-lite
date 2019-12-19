jQuery(document).ready(function() {
    var toc_links = jQuery(".eael-toc .eael-toc-body .eael-toc-list a");
    toc_links.on("click", function(e) {
        e.preventDefault();
        jQuery(document).off("scroll");
        toc_links.each(function() {
            jQuery(this).removeClass("active");
        });
        jQuery(this).addClass("active");
        var target = this.hash,
            $target = jQuery(target);
        jQuery("html, body")
            .stop()
            .animate(
                {
                    scrollTop: $target.offset().top
                },
                600,
                "swing",
                function() {
                    window.location.hash = target;
                    jQuery(document).on("scroll", onScroll);
                }
            );
    });

    jQuery(document).on("scroll", onScroll);

    function onScroll(){
        var scrollPos = jQuery(document).scrollTop();

        jQuery(
            ".eael-toc .eael-toc-body .eael-toc-list a"
        ).each(function() {
            var currLink = jQuery(this);
            var refElement = jQuery(currLink.attr("href"));
            if (
                refElement.position().top <= scrollPos &&
                refElement.position().top + refElement.height() > scrollPos
            ) {
                jQuery(".eael-toc .eael-toc-list a").removeClass("active");
                currLink.addClass("active");
            }
        });
    }

    window.onscroll = function() {eaelSticky()};

    var eaelToc = document.getElementById("eael-toc");
    var sticky = eaelToc.offsetTop;
    function eaelSticky() {
        if ( window.pageYOffset >= sticky ) {
            eaelToc.classList.add("eael-sticky");
        } else {
            eaelToc.classList.remove("eael-sticky");
        }
    }

    jQuery('.eael-toc-close').click(function(e) {
        jQuery('.eael-toc').toggleClass('expanded');
        var checkclass = jQuery( ".eael-toc" ).hasClass( "expanded" );
        var close =  jQuery('.eael-toc-close');
        if(checkclass){
            close.text('Table of content');
        }else{
            close.text('X');
        }
    });

});