var PostGrid = function ($scope, $) {
    
    var container = $('.eael-post-grid-container').eq(0),
        $options   = container.data('post_grid_options'),
        $settings = container.data('post_grid_settings');
    
    
    var options = {
        totalPosts   : parseInt($options.totalPosts),
        loadMoreBtn  : $( $options.loadMoreBtn ),
        postContainer: $( $options.postContainer ),
        postStyle    : 'grid'
    }

    var exclude_posts = JSON.parse($settings.exclude_posts),
        tax_query     = JSON.parse($settings.tax_query),
        post__in      = JSON.parse($settings.post__in);

    var settings = {
        postType       : $settings.postType,
        perPage        : parseInt($settings.perPage),
        postOrder      : $settings.postOrder,
        orderBy        : $settings.orderBy,
        showImage      : parseInt($settings.showImage),
        imageSize      : $settings.imageSize,
        showTitle      : parseInt($settings.showTitle),
        showExcerpt    : parseInt($settings.showExcerpt),
        showMeta       : parseInt($settings.showMeta),
        offset         : $settings.offset,
        metaPosition   : $settings.metaPosition,
        excerptLength  : $settings.excerptLength,
        btnText        : $settings.btnText,
        tax_query      : tax_query,
        exclude_posts  : exclude_posts,
        post__in       : post__in,
        grid_style     : $settings.grid_style,
        hover_animation: $settings.hover_animation,
        hover_icon: $settings.hover_icon,
        show_read_more_button: $settings.eael_show_read_more_button,
        read_more_button_text: $settings.read_more_button_text
    }

    eaelLoadMore( options, settings );


    var $gallery = $('.eael-post-grid:not(.eael-post-carousel)').isotope({
        itemSelector: '.eael-grid-post',
        percentPosition: true,
        columnWidth: '.eael-post-grid-column'
    });

    // layout gal, while images are loading
    $gallery.imagesLoaded().progress(function() {
        $gallery.isotope("layout");
    });
    
}

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-post-grid.default",
        PostGrid
    );
});