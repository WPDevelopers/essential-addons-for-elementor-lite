( function( $){
    jQuery(document).ready(function() {
        $(document).on("click",'.eael-toc-link', function(e) {
            e.preventDefault();
            $(document).off("scroll");

            $("ul.eael-toc-list li").removeClass("active");
            $(".eael-first-child").removeClass( "eael-highlight" );
            $(this).closest('.eael-first-child').addClass( "eael-highlight" );
            $(this).parent().addClass( "active" );

            var target = this.hash,
                $target = $(target);
            $("html, body")
                .stop()
                .animate(
                    {scrollTop: $target.offset().top},
                    600,
                    "swing",
                    function() {
                        window.location.hash = target;
                        $(document).on("scroll", EaelTocOnScroll);
                    }
                );
        });

        $(document).on("scroll", EaelTocOnScroll);

        function EaelTocOnScroll(){

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

        window.onscroll = function() {eaelTocSticky()};

        var eaelToc = document.getElementById("eael-toc");
        var sticky = (eaelToc)?eaelToc.offsetTop:0;

        /**
         * check sticky
         */
        function eaelTocSticky() {
            if(!eaelToc){
                return ;
            }
            if ( window.pageYOffset >= sticky ) {
                eaelToc.classList.add("eael-sticky");
            } else {
                eaelToc.classList.remove("eael-sticky");
            }
        }

        /**
         * add ID in main content heading tag
         * @param selector
         * @param supportTag
         */
        function eael_toc_content( selector, supportTag ){
            var mainSelector = document.querySelector(selector),
                allSupportTag = Array.prototype.slice.call( mainSelector.querySelectorAll( supportTag ) ),
                c =0;
            allSupportTag.forEach(function( el ) {
                var id = el.innerHTML.toLowerCase().trim().replace(/[^a-zA-Z ]/g, "");
                id = id.trim().replace(/ /g,"-");
                el.id = c+"-"+id;
                el.classList.add("eael-heading-content");
                c++
            });
            eael_list_hierarchy( selector, supportTag);
        }

        /**
         * Make toc list
         * @param selector
         * @param supportTag
         */
        function eael_list_hierarchy (selector, supportTag){
            var tagList     = supportTag;
            var listId      = document.getElementById('eael-toc-list');
            var mainContent = document.querySelector(selector);
            listId.innerHTML='';
            allHeadings = mainContent.querySelectorAll(tagList),
            baseTag     = parentLevel = tagList.trim().split(',')[0].substr(1,1),
            ListNode    = listId;

            for (var i = 0, len = allHeadings.length ; i < len ; ++i) {

                var currentHeading  = allHeadings[i];
                var latestLavel     = parseInt(currentHeading.tagName.substr(1,1));
                var diff            = latestLavel - parentLevel;

                if (diff > 0) {
                    if(containerLiNode){
                        var containerLiNode = ListNode.lastChild;
                        var createUlNode = document.createElement('UL');

                        containerLiNode.appendChild(createUlNode);
                        ListNode = createUlNode;
                        parentLevel = latestLavel;
                    }
                }

                if (diff < 0) {
                    while (0 !== diff++) {
                        if(ListNode.parentNode.parentNode){
                            ListNode = ListNode.parentNode.parentNode;
                        }
                    }
                    parentLevel = latestLavel;
                }

                if(ListNode.tagName!=='UL'){
                    ListNode = listId;
                }

                var createLiNode = document.createElement('LI');
                var createALink = document.createElement('A');

                if( baseTag == parentLevel ){
                    createLiNode.className = 'eael-first-child';
                    createLiNode.setAttribute('itemscope', '');
                    createLiNode.setAttribute('itemtype', 'http://schema.org/ListItem');
                    createLiNode.setAttribute('itemprop', 'itemListElement');
                }

                var Linkid = currentHeading.textContent.toLowerCase().replace(/[^a-zA-Z ]/g, "");
                Linkid = Linkid.trim().replace(/ /g,"-");
                Linkid = '#'+i+'-'+Linkid;
                createALink.className = 'eael-toc-link';
                createALink.setAttribute('itemprop', 'item');
                createALink.setAttribute('href', Linkid);
                createALink.appendChild(document.createTextNode(currentHeading.textContent))
                createLiNode.appendChild(createALink);

                ListNode.appendChild(createLiNode);
            }
        }

        $('.eael-toc-close ,.eael-toc-button').click(function(e) {
            $('.eael-toc').toggleClass('expanded');
        });

        //editor mode
        if (isEditMode) {
            elementor.settings.page.addChangeCallback(
                "eael_ext_table_of_content",
                function (newValue) {
                    if (newValue !== "yes") {
                        $("#eael-toc").addClass('eael-toc-disable');
                    }else{
                        var $settings = elementor.settings.page.getSettings();
                        var title = $settings.settings.eael_ext_toc_title;
                        ea_toc_title_change( title );
                        eael_toc_content('.entry-content', $settings.settings.eael_ext_toc_supported_heading_tag.join(', '));
                        $("#eael-toc").removeClass('eael-toc-disable eael-toc-global');
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
        var intSupportTag = $('#eael-toc').data('eaeltoctag');
        if(intSupportTag!=''){
            eael_toc_content('.entry-content', intSupportTag );
        }
    });
})(jQuery);