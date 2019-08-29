(function($) {
    "use strict";

    window.eaelLoadMore = function() {
        $(".eael-load-more-button").on("click", function(e) {
            e.stopPropagation();
            e.stopImmediatePropagation();
            e.preventDefault();

            var $this = $(this),
                $text = $("span", $this).html(),
                $widget_id = $this.data("widget"),
                $class = $this.data("class"),
                $args = $this.data("args"),
                $settings = $this.data("settings"),
                $layout = $this.data("layout"),
                $page = parseInt($this.data("page")) + 1;

            $this.addClass("button--loading");
            $("span", $this).html("Loading...");

            $.ajax({
                url: localize.ajaxurl,
                type: "post",
                data: {
                    action: "load_more",
                    class: $class,
                    args: $args,
                    settings: $settings,
                    page: $page
                },
                success: function(response) {
                    var $content = $(response);

                    if (
                        $content.hasClass("no-posts-found") ||
                        $content.length == 0
                    ) {
                        $this.remove();
                    } else {
                        if ($layout == "masonry") {
                            $(".eael-post-appender-" + $widget_id)
                                .append($content)
                                .isotope("appended", $content);
                        } else {
                            $(".eael-post-appender-" + $widget_id).append(
                                $content
                            );
                        }

                        $this.removeClass("button--loading");
                        $("span", $this).html($text);

                        $this.data("page", $page);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });
    };
})(jQuery);
