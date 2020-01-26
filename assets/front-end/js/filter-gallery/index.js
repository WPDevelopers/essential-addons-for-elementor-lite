var filterableGalleryHandler = function($scope, $) {

    var filterControls = $scope.find('.fg-layout-3-filter-controls').eq(0),
        filterTrigger = $scope.find('#fg-filter-trigger'),
        form = $scope.find('.fg-layout-3-search-box'),
        input = $scope.find('#fg-search-box-input'),
        searchRegex, buttonFilter, timer;
    var delegateAbc = '';

    if(form.length) {
        form.on('submit', function(e) {
            e.preventDefault();
        });
    }

    filterTrigger.on('click', function() {
        filterControls.toggleClass('open-filters');
    }).blur(function() {
        filterControls.toggleClass('open-filters');
    });

    if (!isEditMode) {
        var $gallery = $(".eael-filter-gallery-container", $scope),
            $settings = $gallery.data("settings"),
            $gallery_items = $gallery.data("gallery-items"),
            $layout_mode =
                $settings.grid_style == "masonry" ? "masonry" : "fitRows",
            $gallery_enabled =
                $settings.gallery_enabled == "yes" ? true : false;

        // init isotope
        var layoutMode = $('.eael-filter-gallery-wrapper').data('layout-mode');
        var $isotope_gallery = $gallery.isotope({
            itemSelector: ".eael-filterable-gallery-item-wrap",
            layoutMode: $layout_mode,
            percentPosition: true,
            stagger: 30,
            transitionDuration: $settings.duration + "ms",
            filter: function() {
                var $this = $(this);
                var $result = searchRegex ? $this.text().match( searchRegex ) : true;
                if(buttonFilter == undefined) {
                    if(layoutMode != 'layout_3') {
                        buttonFilter = $scope.find('.eael-filter-gallery-control ul li').first().data('filter');
                    }else {
                        buttonFilter = $scope.find('.fg-layout-3-filter-controls li').first().data('filter');
                    }
                }
                var buttonResult = buttonFilter ? $this.is( buttonFilter ) : true;
                return $result && buttonResult;
            }
        });

        //alert($settings.widget_id);
        // Popup
        $("#eael-filter-gallery-wrapper-"+$settings.widget_id+" .eael-magnific-link").magnificPopup({
            type: "image",
            gallery: {
                enabled: $gallery_enabled
            },
            callbacks: {
                close: function() {
                    $("#elementor-lightbox").hide();
                }
            },
            fixedContentPos: false,
        });

        // filter
        $scope.on("click", ".control", function() {

            var $this = $(this);
            buttonFilter = $( this ).attr('data-filter');
            delegateAbc = $( this ).attr('data-filter') + ' a.eael-magnific-link';

            if($scope.find('#fg-filter-trigger > span')) {
                $scope.find('#fg-filter-trigger > span').text($this.text());
            }

            $this.siblings().removeClass("active");
            $this.addClass("active");

            $('#eael-filter-gallery-wrapper-'+$settings.widget_id+' '+delegateAbc).magnificPopup({
                type: 'image',
                gallery: {
                    enabled: $gallery_enabled,
                },
                callbacks: {
                    close: function() {
                        $('#elementor-lightbox').hide();
                    }
                },
                fixedContentPos: false,
            });

            $isotope_gallery.isotope();
        });



        //quick search
        input.on('input', function() {
            var $this = $(this);

            clearTimeout(timer);
            timer = setTimeout(function() {
                searchRegex = new RegExp($this.val(), 'gi');
                $isotope_gallery.isotope();
            }, 600);

        });

        // layout gal, while images are loading
        $isotope_gallery.imagesLoaded().progress(function() {
            $isotope_gallery.isotope("layout");
        });

        // layout gal, on click tabs
        $isotope_gallery.on("arrangeComplete", function() {
            $isotope_gallery.isotope("layout");
        });

        // layout gal, after window loaded
        $(window).on("load", function() {
            $isotope_gallery.isotope("layout");
        });

        

        // popup
        $($scope).magnificPopup({
            delegate: ".eael-magnific-video-link",
            type: "iframe",
            callbacks: {
                close: function() {
                    $("#elementor-lightbox").hide();
                }
            }
        });

        // Load more button
        $scope.on("click", ".eael-gallery-load-more", function(e) {
            e.preventDefault();

            var $this = $(this),
                $init_show = $(
                    ".eael-filter-gallery-container",
                    $scope
                ).children(".eael-filterable-gallery-item-wrap").length,
                $total_items = $gallery.data("total-gallery-items"),
                $images_per_page = $gallery.data("images-per-page"),
                $nomore_text = $gallery.data("nomore-item-text"),
                $items = [];

            if ($init_show == $total_items) {
                $this.html(
                    '<div class="no-more-items-text">' + $nomore_text + "</div>"
                );
                setTimeout(function() {
                    $this.fadeOut("slow");
                }, 600);
            }

            // new items html
            for (var i = $init_show; i < $init_show + $images_per_page; i++) {
                $items.push($($gallery_items[i])[0]);
            }

            // append items
            $gallery.append($items);
            $isotope_gallery.isotope("appended", $items);
            $isotope_gallery.imagesLoaded().progress(function() {
                $isotope_gallery.isotope("layout");
            });

            // reinit magnificPopup
            $(".eael-magnific-link", $scope).magnificPopup({
                type: "image",
                gallery: {
                    enabled: $gallery_enabled
                },
                callbacks: {
                    close: function() {
                        $("#elementor-lightbox").hide();
                    }
                }
            });
        });
    }
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-filterable-gallery.default",
        filterableGalleryHandler
    );
});
