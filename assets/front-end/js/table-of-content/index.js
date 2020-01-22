( function( $){
    jQuery(document).ready(function() {

        /**
         * add ID in main content heading tag
         * @param selector
         * @param supportTag
         */
        function eael_toc_content( selector, supportTag ){
            if(selector === null || supportTag === undefined){
                //$('#eael-toc-list').html("<p class='eael-toc-not-found'>Whoops! No headings found</p>");
                return null;
            }
            var mainSelector = document.querySelector(selector),
                allSupportTag = Array.prototype.slice.call( mainSelector.querySelectorAll( supportTag ) ),
                c =0;
            allSupportTag.forEach(function( el ) {
                el.id = c+"-"+ eael_build_id( el.innerHTML );
                el.classList.add("eael-heading-content");
                c++
            });
            eael_list_hierarchy( selector, supportTag);
            var  firstChild = $('ul.eael-toc-list > li');
            firstChild.each(function(){
                this.classList.add('eael-first-child');
            });
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


            allHeadings = mainContent.querySelectorAll(tagList),
                baseTag     = parentLevel = tagList.trim().split(',')[0].substr(1,1),
                ListNode    = listId;
            if(allHeadings.length===0){
                return null;
            }
            listId.innerHTML='';
            for (var i = 0, len = allHeadings.length ; i < len ; ++i) {

                var currentHeading  = allHeadings[i];
                var latestLavel     = parseInt(currentHeading.tagName.substr(1,1));
                var diff            = latestLavel - parentLevel;

                if (diff > 0) {
                    var containerLiNode = ListNode.lastChild;
                    if(containerLiNode){
                        var createUlNode = document.createElement('UL');

                        containerLiNode.appendChild(createUlNode);
                        ListNode = createUlNode;
                        parentLevel = latestLavel;
                    }
                }

                var sequenceParent = false;

                if (diff < 0) {
                    while (0 !== diff++) {
                        if(ListNode.parentNode.parentNode){
                            ListNode = ListNode.parentNode.parentNode;
                        }
                    }
                    parentLevel = latestLavel;
                    sequenceParent = true;
                }

                if(ListNode.tagName!=='UL'){
                    ListNode = listId;
                }

                var liNode = document.createElement('LI');
                var anchorTag = document.createElement('A');
                var spanTag = document.createElement('SPAN');

                if( baseTag === parentLevel || sequenceParent){
                    liNode.setAttribute('itemscope', '');
                    liNode.setAttribute('itemtype', 'http://schema.org/ListItem');
                    liNode.setAttribute('itemprop', 'itemListElement');
                }

                Linkid = '#'+i+'-'+ eael_build_id( currentHeading.textContent );
                anchorTag.className = 'eael-toc-link';
                anchorTag.setAttribute('itemprop', 'item');
                anchorTag.setAttribute('href', Linkid);
                spanTag.appendChild(document.createTextNode(currentHeading.textContent));
                anchorTag.appendChild(spanTag);
                liNode.appendChild(anchorTag);
                ListNode.appendChild(liNode);
            }
        }

        var intSupportTag = $('#eael-toc').data('eaeltoctag');
        if(intSupportTag!==''){
            eael_toc_content(eael_toc_check_content(), intSupportTag );
        }

        $(document).on("click",'ul.eael-toc-list li a', function(e) {
            e.preventDefault();
            $(document).off("scroll");
            var parentLi = $(this).parent();
            if( parentLi.is('.eael-highlight.active') ){
                parentLi.removeClass('eael-highlight active');
                return false;
            }
            $("ul.eael-toc-list li").removeClass("active");
            $(".eael-first-child").removeClass( "eael-highlight" );
            $(this).closest('.eael-first-child').addClass( "eael-highlight" );
            $(this).parent().addClass( "active" );

            var target = this.hash,
                $target = $(target);
                window.location.hash = target;
        });

        window.onscroll = function() {eaelTocSticky()};

        var eaelToc = document.getElementById("eael-toc");
        var sticky = (eaelToc)?eaelToc.offsetTop:0;

        /**
         * check sticky
         */
        function eaelTocSticky() {
            var eaelToc = document.getElementById("eael-toc");
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
         *
         * @param content
         * @returns {string}
         */
        function eael_build_id( content ){
            return 'eael-unique-link';
        }

        /**
         *
         * @returns {null|selector}
         */
        function eael_toc_check_content(){
            var contentSelectro = null;
            if($('.elementor-inner')[0]){
                contentSelectro =  '.elementor-inner';
            }else if($('#site-content')[0]){
                contentSelectro = '#site-content';
            }
            return contentSelectro;
        }

        $(document).on('click','.eael-toc-close ,.eael-toc-button',function(){
            $('.eael-toc').toggleClass('expanded');
        });

        function eael_build_toc( $settings ){
            var pageSetting = $settings.settings,
                title = pageSetting.eael_ext_toc_title,
                toc_style_class     = 'eael-toc-list eael-toc-list-'+pageSetting.eael_ext_table_of_content_list_style,
                support_tag         =  pageSetting.eael_ext_toc_supported_heading_tag.join(', '),
                icon                =  pageSetting.eael_ext_table_of_content_header_icon.value,
                el_class            = (pageSetting.eael_ext_toc_position ==='right')?' eael-toc-right':' ';
                toc_style_class    += (pageSetting.eael_ext_toc_collapse_sub_heading ==='yes')?' eael-toc-collapse':' ';
                toc_style_class    += (pageSetting.eael_ext_toc_list_icon ==='number')?' eael-toc-number':' ';


            return '<div id="eael-toc" class="eael-toc '+el_class+'">' +
                '<div class="eael-toc-header"><span class="eael-toc-close">×</span><h2 class="eael-toc-title">'+ title + '</h2></div>' +
                '<div class="eael-toc-body"><ul id="eael-toc-list" class="'+toc_style_class+'"></ul></div>' +
                '<button class="eael-toc-button"><i class="'+icon+'"></i><span>'+ title +'</span></button>' +
                '</div>';

        }

        //editor mode
        if (isEditMode) {

            elementor.settings.page.addChangeCallback(
                "eael_ext_table_of_content",
                function (newValue) {
                    var eaelToc = $("#eael-toc");
                    eaelToc.remove();
                    if (newValue === "yes") {
                        var $settings = elementor.settings.page.getSettings();
                        $('body').append(eael_build_toc($settings));
                        eael_toc_content(eael_toc_check_content(), $settings.settings.eael_ext_toc_supported_heading_tag.join(', '));
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

            elementor.settings.page.addChangeCallback(
                "eael_ext_toc_collapse_sub_heading",
                eael_toc_list_collapse
                );

            function eael_toc_list_collapse(newValue){
                var list  = $(".eael-toc-list");
                if (newValue === "yes") {
                    list.addClass('eael-toc-collapse');
                }else{
                    list.removeClass('eael-toc-collapse');
                }
            }

            elementor.settings.page.addChangeCallback(
                "eael_ext_table_of_content_header_icon",
                function (newValue) {
                    var iconElement = $('.eael-toc-button i');
                    iconElement.removeClass().addClass(newValue.value);
                });

            elementor.settings.page.addChangeCallback(
                "eael_ext_toc_list_icon",
                function (newValue) {
                    var list  = $(".eael-toc-list");
                    if (newValue === "number") {
                        list.addClass('eael-toc-number');
                    }else{
                        list.removeClass('eael-toc-number');
                    }
                });

            elementor.settings.page.addChangeCallback(
                "eael_ext_toc_word_wrap",
                function (newValue) {
                    var list  = $(".eael-toc-list");
                    if (newValue === "yes") {
                        list.addClass('eael-toc-word-wrap');
                    }else{
                        list.removeClass('eael-toc-word-wrap');
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