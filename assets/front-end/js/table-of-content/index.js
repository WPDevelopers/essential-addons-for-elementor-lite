( function( $){
    jQuery(document).ready(function() {
        var toc_links = $(".eael-toc .eael-toc-body .eael-toc-list a");
        toc_links.on("click", function(e) {
            e.preventDefault();
            $(document).off("scroll");
            toc_links.each(function() {
                $(this).removeClass("active");
            });
            $(this).addClass("active");
            var target = this.hash,
                $target = jQuery(target);
            $("html, body")
                .stop()
                .animate(
                    {
                        scrollTop: $target.offset().top
                    },
                    600,
                    "swing",
                    function() {
                        window.location.hash = target;
                        $(document).on("scroll", onScroll);
                    }
                );
        });

        $(document).on("scroll", onScroll);

        function onScroll(){

            var scrollPos = jQuery(document).scrollTop();
            $(".eael-toc .eael-toc-body .eael-toc-list a").each( function() {
                var currLink = $(this);
                var refElement = $(currLink.attr("href"));
                var position =  refElement.position();

                if ( position &&
                    position.top <= scrollPos &&
                    position.top + refElement.height() > scrollPos
                ) {
                    $(".eael-toc .eael-toc-list a").removeClass("active");
                    currLink.addClass("active");
                }
            });
        }

        window.onscroll = function() {eaelSticky()};

        var eaelToc = document.getElementById("eael-toc");
        var sticky = (eaelToc)?eaelToc.offsetTop:0;

        function eaelSticky() {
            if(!eaelToc){
                return ;
            }
            if ( window.pageYOffset >= sticky ) {
                eaelToc.classList.add("eael-sticky");
            } else {
                eaelToc.classList.remove("eael-sticky");
            }
        }

        $('.eael-toc-close').click(function(e) {
            $('.eael-toc').toggleClass('expanded');
            var checkClass = $( ".eael-toc" ).hasClass( "expanded" );
            var close =  $('.eael-toc-close');
            if(checkClass){
                close.text('Table of content');
            }else{
                close.text('X');
            }
        });

    });
})(jQuery);