var FacebookFeed = function($scope, $) {
    if (!isEditMode) {
        $facebook_gallery = $(".eael-facebook-feed", $scope).isotope({
            itemSelector: ".eael-facebook-feed-item",
            percentPosition: true,
            columnWidth: ".eael-facebook-feed-item"
        });

        $facebook_gallery.imagesLoaded().progress(function() {
            $facebook_gallery.isotope("layout");
        });
    }

    // ajax load more
    $(".eael-load-more-button", $scope).on("click", function(e) {
        e.preventDefault();

        $this = $(this);
        $settings = $this.attr("data-settings");
        $page = $this.attr("data-page");

        // update load moer button
        $this.addClass("button--loading");
        $("span", $this).html("Loading...");

        $.ajax({
            url: localize.ajaxurl,
            type: "post",
            data: {
                action: "facebook_feed_load_more",
                security: localize.nonce,
                settings: $settings,
                page: $page
            },
            success: function(response) {
                $html = $(response.html);

                // append items
                $facebook_gallery = $(".eael-facebook-feed", $scope).isotope();
                $(".eael-facebook-feed", $scope).append($html);
                $facebook_gallery.isotope("appended", $html);
                $facebook_gallery.imagesLoaded().progress(function() {
                    $facebook_gallery.isotope("layout");
                });

                // update load more button
                if (response.num_pages > $page) {
                    $this.attr("data-page", parseInt($page) + 1);
                    $this.removeClass("button--loading");
                    $("span", $this).html("Load more");
                } else {
                    $this.remove();
                }
            },
            error: function() {}
        });
    });
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-facebook-feed.default",
        FacebookFeed
    );
});
