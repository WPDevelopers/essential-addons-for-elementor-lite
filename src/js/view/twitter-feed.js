var TwitterFeedHandler = function($scope, $) {
    if (!isEditMode) {
        $gutter = $(".eael-twitter-feed-masonry", $scope).data("gutter");
        $settings = {
            itemSelector: ".eael-twitter-feed-item",
            percentPosition: true,
            masonry: {
                columnWidth: ".eael-twitter-feed-item",
                gutter: $gutter
            }
        };

        // init isotope
        $twitter_feed_gallery = $(".eael-twitter-feed-masonry", $scope).isotope(
            $settings
        );

        // layout gal, while images are loading
        $twitter_feed_gallery.imagesLoaded().progress(function() {
            $twitter_feed_gallery.isotope("layout");
        });
    } else{
        elementor.hooks.addAction("panel/open_editor/widget/eael-twitter-feed", ( panel, model, view ) => {
            panel.content.el.onclick = (event) => {

                if (event.target.dataset.event == "ea:cache:clear") {
                    let button = event.target;
                    button.innerHTML = "Clearing...";

                    jQuery.ajax({
                        url: localize.ajaxurl,
                        type: "post",
                        data: {
                            action: "eael_clear_widget_cache_data",
                            security: localize.nonce,
                            ac_name: model.attributes.settings.attributes.eael_twitter_feed_ac_name,
                            hastag: model.attributes.settings.attributes.eael_twitter_feed_hashtag_name,
                            c_key: model.attributes.settings.attributes.eael_twitter_feed_consumer_key,
                            c_secret: model.attributes.settings.attributes.eael_twitter_feed_consumer_secret,
                        },
                        success(response) {
                            if (response.success) {
                                button.innerHTML = "Clear";

                            } else {
                                button.innerHTML = "Failed";
                            }
                        },
                        error() {
                            button.innerHTML = "Failed";
                        },
                    });
                }
            }
        });
    }
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-twitter-feed.default",
        TwitterFeedHandler
    );
});
