var TwitterFeedHandler = function($scope, $) {
    $gutter = $(".eael-twitter-feed-masonry", $scope).data('gutter');

    // init isotope
    $twitter_feed_gallery = $(".eael-twitter-feed-masonry", $scope).isotope({
        itemSelector: ".eael-twitter-feed-item",
        percentPosition: true,
        masonry: {
            columnWidth: ".eael-twitter-feed-item",
            gutter: $gutter
        }
    });

    // layout gal, while images are loading
    $twitter_feed_gallery.imagesLoaded().progress(function() {
        $twitter_feed_gallery.isotope("layout");
    });
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-twitter-feed.default",
        TwitterFeedHandler
    );
});
