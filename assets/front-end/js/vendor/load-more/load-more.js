(function($) {
    "use strict";

    window.eaelLoadMore = function() {
        $('.eael-load-more-button').on("click", function(e) {
            e.preventDefault();

            var $this = $(this),
            $widget_id = $this.data('widget'),
            $class = $this.data('class'),
            $args = $this.data('args'),
            $settings = $this.data('settings'),
            $layout = $this.data('layout'),
            $page = $this.data('page');

            $this.addClass("button--loading");
            $('span', $this).html("Loading...");

            $.ajax({
                url: localize.ajaxurl,
                type: "post",
                data: {
                    action: "load_more",
                    widget_id: $widget_id,
                    class: $class,
                    args: $args,
                    settings: $settings,
                    layout: $layout,
                    page: $page
                },
                success: function(response) {
                    var $content = $(response);

                    if($layout == 'masonry') {
                        $('.eael-post-appender-' . $widget_id).isotope().append($content).isotope("appended", $content);
                    } else {
                        $('.eael-post-appender-' . $widget_id).append($content)
                    }
                    
                    // if (optionsValue.postStyle === "grid") {
                    //     setTimeout(function() {
                    //         optionsValue.postContainer.masonry();
                    //         optionsValue.postContainer
                    //             .append($content)
                    //             .masonry("appended", $content);
                    //         optionsValue.postContainer.masonry({
                    //             itemSelector: ".eael-grid-post",
                    //             percentPosition: true,
                    //             columnWidth: ".eael-post-grid-column"
                    //         });
                    //     }, 100);
                    // } else {
                    //     optionsValue.postContainer.append($content);
                    // }
                    // optionsValue.loadMoreBtn.removeClass("button--loading");
                    // optionsValue.loadMoreBtn
                    //     .find("span")
                    //     .html(settingsValue.btnText);

                    // offset = offset + settingsValue.perPage;

                    // if (offset >= optionsValue.totalPosts) {
                    //     optionsValue.loadMoreBtn.remove();
                    // }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });
    };
})(jQuery);
