( function( $){
    jQuery(document).ready(function() {
        var toc_links = $("ul.eael-toc-list li a");
        toc_links.on("click", function(e) {
            e.preventDefault();
            $(document).off("scroll");
            toc_links.parent().each(function() {
                $(this).removeClass("active");
            });
            //active parent node when visit child node
            $(".eael-first-child").removeClass( "eael-highlight" );
            $(this).closest('.eael-first-child').addClass( "eael-highlight" );
            $(this).parent().addClass( "active" );
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
        // var $settings = elementor.settings.page.getSettings();
        // console.log($settings.settings);
        function onScroll(){

            var scrollPos = $(document).scrollTop();
            $(" ul.eael-toc-list li a").each( function() {
                var currLink = $(this);
                var refElement = $(currLink.attr("href"));
                var position =  refElement.position();
                var closest  = currLink.closest('.eael-first-child');
                if ( position &&
                    position.top <= scrollPos &&
                    position.top + refElement.height() > scrollPos
                ) {
                    $("ul.eael-toc-list li").removeClass("active");
                    $(".eael-first-child").removeClass("eael-highlight");
                    currLink.parent().addClass("active");
                    closest.addClass( "eael-highlight" );
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

        function eael_toc_content( selector, supportTag ){
            var container = document.querySelector(selector),
                matches = Array.prototype.slice.call( container.querySelectorAll( supportTag ) ),
                c =0;

            matches.forEach(function( el ) {
                var id = el.innerHTML.toLowerCase().replace(/ /g,"-");
                el.id = c+"-"+id;
                el.classList.add("eael-heading-content");
                c++
            });
        }

        $('.eael-toc-close ,.eael-toc-button').click(function(e) {
            $('.eael-toc').toggleClass('expanded');
        });

        //editor mode
        if (isEditMode) {
            elementor.settings.page.addChangeCallback(
                "eael_ext_table_of_content",
                function (newValue) {
                    if (newValue != "yes") {
                        $("#eael-toc").addClass('eael-toc-disable');
                    }else{
                        var $settings = elementor.settings.page.getSettings();
                        var title = $settings.settings.eael_ext_toc_title;
                        console.log($settings.settings);
                        ea_toc_title_change( title );
                        eael_toc_content('.entry-content', $settings.settings.eael_ext_toc_supported_heading_tag.join(', '));
                        $("#eael-toc").removeClass('eael-toc-disable');
                    }
                });

            elementor.settings.page.addChangeCallback(
                "eael_ext_toc_position",
                function (newValue) {
                    if (newValue === "right") {
                        $("#eael-toc").addClass('eael-toc-right');
                    }else{
                        $("#eael-toc").removeClass('eael-toc-right');
                    }
                });

            elementor.settings.page.addChangeCallback(
                "eael_ext_table_of_content_list_style",
                function (newValue) {
                    var list  = $(".eael-toc-list");
                    list.removeClass('eael-toc-list-style_2 eael-toc-list-style_3');
                    if (newValue !== "style_1") {
                        list.addClass('eael-toc-list-'+newValue);
                    }
                });

            elementor.settings.page.addChangeCallback("eael_ext_toc_title", ea_toc_title_change );

            function ea_toc_title_change ( newValue ) {
                elementorFrontend.elements.$document.find('.eael-toc-title').text(newValue);
                elementorFrontend.elements.$document.find('.eael-toc-button span').text(newValue);
            }
        }
    });
})(jQuery);