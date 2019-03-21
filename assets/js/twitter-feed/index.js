var TwitterFeedHandler = function($scope, $) {
    var loadingFeed = $scope.find(".eael-loading-feed");
    var $twitterFeed = $scope.find(".eael-twitter-feed-layout-wrapper").eq(0),
        $name =
            $twitterFeed.data("twitter-feed-ac-name") !== undefined
                ? $twitterFeed.data("twitter-feed-ac-name")
                : "",
        $limit =
            $twitterFeed.data("twitter-feed-post-limit") !== undefined
                ? $twitterFeed.data("twitter-feed-post-limit")
                : "",
        $hash_tag =
            $twitterFeed.data("twitter-feed-hashtag-name") !== undefined
                ? $twitterFeed.data("twitter-feed-hashtag-name")
                : "",
        $key =
            $twitterFeed.data("twitter-feed-consumer-key") !== undefined
                ? $twitterFeed.data("twitter-feed-consumer-key")
                : "",
        $app_secret =
            $twitterFeed.data("twitter-feed-consumer-secret") !== undefined
                ? $twitterFeed.data("twitter-feed-consumer-secret")
                : "",
        $length =
            $twitterFeed.data("twitter-feed-content-length") !== undefined
                ? $twitterFeed.data("twitter-feed-content-length")
                : 400,
        $media =
            $twitterFeed.data("twitter-feed-media") !== undefined
                ? $twitterFeed.data("twitter-feed-media")
                : false,
        $feed_type =
            $twitterFeed.data("twitter-feed-type") !== undefined
                ? $twitterFeed.data("twitter-feed-type")
                : false,
        $carouselId =
            $twitterFeed.data("twitter-feed-id") !== undefined
                ? $twitterFeed.data("twitter-feed-id")
                : " ";

    var $id_name = $name.toString();
    var $hash_tag_name = $hash_tag.toString();
    var $key_name = $key.toString();
    var $app_secret = $app_secret.toString();

    function eael_twitter_feeds() {
        $(
            "#eael-twitter-feed-" +
                $carouselId +
                ".eael-twitter-feed-layout-container"
        ).socialfeed({
            // TWITTER
            twitter: {
                accounts: [$id_name, $hash_tag_name],
                limit: $limit,
                consumer_key: $key_name,
                consumer_secret: $app_secret
            },

            // GENERAL SETTINGS
            length: $length,
            show_media: $media,
            template_html:
                '<div class="eael-social-feed-element {{? !it.moderation_passed}}hidden{{?}}" dt-create="{{=it.dt_create}}" social-feed-id = "{{=it.id}}">\
                <div class="eael-content">\
                    <a class="pull-left auth-img" href="{{=it.author_link}}" target="_blank">\
                        <img class="media-object" src="{{=it.author_picture}}">\
                    </a>\
                    <div class="media-body">\
                        <p>\
                            <i class="fa fa-{{=it.social_network}} social-feed-icon"></i>\
                            <span class="author-title">{{=it.author_name}}</span>\
                            <span class="muted pull-right social-feed-date"> {{=it.time_ago}}</span>\
                        </p>\
                        <div class="text-wrapper">\
                            <p class="social-feed-text">{{=it.text}} </p>\
                            <p><a href="{{=it.link}}" target="_blank" class="read-more-link">Read More <i class="fa fa-angle-double-right"></i></a></p>\
                        </div>\
                    </div>\
                </div>\
                {{=it.attachment}}\
            </div>'
        });
    }

    //Twitter Feed masonry View

    function eael_twitter_feed_masonry() {
        $(".eael-twitter-feed-layout-container.masonry-view").masonry({
            itemSelector: ".eael-social-feed-element",
            percentPosition: true,
            columnWidth: ".eael-social-feed-element"
        });
    }

    $.ajax({
        url: eael_twitter_feeds(),
        beforeSend: function() {
            loadingFeed.addClass("show-loading");
        },
        success: function() {
            $(".eael-twitter-feed-layout-container").bind(
                "DOMSubtreeModified",
                function() {
                    if ($feed_type == "masonry") {
                        setTimeout(function() {
                            eael_twitter_feed_masonry();
                        }, 150);
                    }
                }
            );
            loadingFeed.removeClass("show-loading");
        },
        error: function() {
            console.log("error loading");
        }
    });
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-twitter-feed.default",
        TwitterFeedHandler
    );
});
