var TwitterFeedHandler = function($scope, $) {
    let $eael_twitter_feed = $(".eael-twitter-feed", $scope);
    let $posts_per_page = $eael_twitter_feed.data("posts-per-page");
    let $total_posts = $eael_twitter_feed.data("total-posts");
    let $nomore_item_text = $eael_twitter_feed.data("nomore-item-text");
    let $next_page = $eael_twitter_feed.data("next-page");

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
                            page_permalink: localize.page_permalink,
                            widget_id: model.attributes.id,
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

    $scope.on("click", ".eael-twitter-feed-load-more", function (e) {
        e.preventDefault();
        $('.eael-twitter-feed-item.page-' + $next_page, $scope).removeClass('eael-d-none').addClass('eael-d-block');
        $eael_twitter_feed.attr("data-next-page", $next_page + 1);

        $(".eael-twitter-feed-masonry", $scope).isotope("layout");
        
        if( $('.eael-twitter-feed-item.page-' + $next_page, $scope).hasClass('eael-last-twitter-feed-item') ) {
            $(".eael-twitter-feed-load-more", $scope).html( $nomore_item_text ).fadeOut('1500');
        }

        $next_page++;
    });

};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-twitter-feed.default",
        TwitterFeedHandler
    );
});
