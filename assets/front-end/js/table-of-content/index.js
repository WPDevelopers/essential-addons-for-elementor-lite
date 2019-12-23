( function( $){
    jQuery(document).ready(function() {
        var toc_links = $("ul.eael-toc-list li a");
        toc_links.on("click", function(e) {
            e.preventDefault();
            $(document).off("scroll");
            toc_links.parent().each(function() {
                $(this).removeClass("active");
            });
            $(this).parent().addClass("active");
            var target = this.hash,
                $target = $(target);
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

            var scrollPos = $(document).scrollTop();
            $(" ul.eael-toc-list li a").each( function() {
                var currLink = $(this);
                var refElement = $(currLink.attr("href"));
                var position =  refElement.position();

                if ( position &&
                    position.top <= scrollPos &&
                    position.top + refElement.height() > scrollPos
                ) {
                    $("ul.eael-toc-list li").removeClass("active");
                    currLink.parent().addClass("active");
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

        $('.eael-toc-close ,.eael-toc-button').click(function(e) {
            $('.eael-toc').toggleClass('expanded');
            var checkClass = $( ".eael-toc" ).hasClass( "expanded" );
            var close =  $('.eael-toc-close');
            // if(checkClass){
            //     close.text('Table of content');
            // }else{
            //     close.text('X');
            // }
        });

    });
})(jQuery);