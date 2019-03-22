var postTimelineHandler = function($scope, $) {
    var $_this = $scope.find(".eael-post-timeline"),
        $currentTimelineId = "#" + $_this.attr("id"),
        $total_posts = parseInt($_this.data("total_posts"), 10),
        $timeline_id = $_this.data("timeline_id"),
        $post_type = $_this.data("post_type"),
        $posts_per_page = parseInt($_this.data("posts_per_page"), 10),
        $post_order = $_this.data("post_order"),
        $post_orderby = $_this.data("post_orderby"),
        $post_offset = parseInt($_this.data("post_offset"), 10),
        $show_images = $_this.data("show_images"),
        $image_size = $_this.data("image_size"),
        $show_title = $_this.data("show_title"),
        $show_excerpt = $_this.data("show_excerpt"),
        $excerpt_length = parseInt($_this.data("excerpt_length"), 10),
        $btn_text = $_this.data("btn_text"),
        $tax_query = $_this.data("tax_query"),
        $post__in = $_this.data("post__in"),
        $exclude_posts = $_this.data("exclude_posts");

    var options = {
        totalPosts: $total_posts,
        loadMoreBtn: $("#eael-load-more-btn-" + $timeline_id),
        postContainer: $(".eael-post-appender-" + $timeline_id),
        postStyle: "timeline"
    };

    var settings = {
        postType: $post_type,
        perPage: $posts_per_page,
        postOrder: $post_order,
        orderBy: $post_orderby,
        offset: $post_offset,

        showImage: $show_images,
        imageSize: $image_size,
        showTitle: $show_title,
        showExcerpt: $show_excerpt,
        excerptLength: parseInt($excerpt_length, 10),
        btnText: $btn_text,
        tax_query: $tax_query,
        post__in: $post__in,
        exclude_posts: $exclude_posts
    };

    eaelLoadMore(options, settings);
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-post-timeline.default",
        postTimelineHandler
    );
});
