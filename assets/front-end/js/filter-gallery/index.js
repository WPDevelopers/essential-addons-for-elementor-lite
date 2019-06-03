var filterableGalleryHandler = function($scope, $) {
    if (!isEditMode) {
        var $gallery = $(".eael-filter-gallery-container", $scope),
            $settings = $gallery.data("settings"),
            $gallery_items = $gallery.data("gallery-items"),
            $layout_mode =
                $settings.grid_style == "masonry" ? "masonry" : "fitRows",
            $gallery_enabled =
                $settings.gallery_enabled == "yes" ? true : false;

        // init isotope
        var $isotope_gallery = $gallery.isotope({
            itemSelector: ".eael-filterable-gallery-item-wrap",
            layoutMode: $layout_mode,
            percentPosition: true,
            stagger: 30,
            transitionDuration: $settings.duration + "ms",
            filter: $(
                ".eael-filter-gallery-control .control.active",
                $scope
            ).data("filter")
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

        // filter
        $scope.on("click", ".control", function() {
            var $this = $(this),
                $filterValue = $this.data("filter");

            $this.siblings().removeClass("active");
            $this.addClass("active");
            $isotope_gallery.isotope({
                filter: $filterValue
            });
        });

        // popup
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
