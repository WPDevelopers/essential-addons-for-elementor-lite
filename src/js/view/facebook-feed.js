jQuery(window).on("elementor/frontend/init", function () {
    let FacebookFeed = function ($scope, $) {
        if (!isEditMode) {
            var $facebook_gallery = $(".eael-facebook-feed", $scope).isotope({
                itemSelector: ".eael-facebook-feed-item",
                percentPosition: true,
                columnWidth: ".eael-facebook-feed-item"
            });

            $facebook_gallery.imagesLoaded().progress(function () {
                $facebook_gallery.isotope("layout");
            });
        }

        // ajax load more
        $(".eael-load-more-button", $scope).on("click", function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            let $this = $(this),
                $LoaderSpan = $(".eael_fb_load_more_text", $this),
                $text = $LoaderSpan.html(),
                $widget_id = $this.data("widget-id"),
                $post_id = $this.data("post-id"),
                $page = $this.data("page");
            // update load more button
            $this.addClass("button--loading");
            $LoaderSpan.html(localize.i18n.loading);

            $.ajax({
                url: localize.ajaxurl,
                type: "post",
                data: {
                    action: "facebook_feed_load_more",
                    security: localize.nonce,
                    page: $page,
                    post_id: $post_id,
                    widget_id: $widget_id,
                },
                success: function (response) {
                    let $html = $(response.html);

                    // append items
                    let $facebook_gallery = $(".eael-facebook-feed", $scope).isotope();
                    $(".eael-facebook-feed", $scope).append($html);
                    $facebook_gallery.isotope("appended", $html);
                    $facebook_gallery.imagesLoaded().progress(function () {
                        $facebook_gallery.isotope("layout");
                    });

                    // update load more button
                    if (response.num_pages > $page) {
                        $page++;
                        $this.data("page", $page);
                        $this.removeClass("button--loading");
                        $LoaderSpan.html($text);
                    } else {
                        $this.remove();
                    }
                },
                error: function () {
                }
            });
        });

        var FacebookGallery = function ($src) {
            $facebook_gallery.imagesLoaded().progress(function () {
                $facebook_gallery.isotope("layout");
            });
        }

        ea.hooks.addAction("ea-lightbox-triggered", "ea", FacebookGallery);
        ea.hooks.addAction("ea-advanced-tabs-triggered", "ea", FacebookGallery);
        ea.hooks.addAction("ea-advanced-accordion-triggered", "ea", FacebookGallery);
        ea.hooks.addAction("ea-toogle-triggered", "ea", FacebookGallery);
    };
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-facebook-feed.default",
        FacebookFeed
    );
});
