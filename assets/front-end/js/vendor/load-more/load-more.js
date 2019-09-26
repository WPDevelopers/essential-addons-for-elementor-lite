(function ($) {
    "use strict";

    $(document).on("click", ".eael-load-more-button", function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var $this = $(this),
            $text = $("span", $this).html(),
            $widget_id = $this.data("widget"),
            $scope = $(".elementor-element-" + $widget_id),
            $class = $this.data("class"),
            $args = $this.data("args"),
            $settings = $this.data("settings"),
            $layout = $this.data("layout"),
            $page = parseInt($this.data("page")) + 1;

        if (typeof $widget_id == 'undefined' || typeof $args == 'undefined') {
            return;
        }

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
            success: function (response) {
                var $content = $(response);

                if (
                    $content.hasClass("no-posts-found") ||
                    $content.length == 0
                ) {
                    $this.remove();
                } else {
                    $(".eael-post-appender", $scope).append($content);

                    if ($layout == "masonry") {
                        var $isotope = $(".eael-post-appender", $scope).isotope();
                        $isotope.isotope("appended", $content).isotope("layout");

                        $isotope.imagesLoaded().progress(function () {
                            $isotope.isotope("layout");
                        });
                    }

                    $this.removeClass("button--loading");
                    $("span", $this).html($text);

                    $this.data("page", $page);
                }
            },
            error: function (response) {
                console.log(response);
            }
        });
    });
})(jQuery);
