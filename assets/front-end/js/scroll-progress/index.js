jQuery(document).ready(function() {
    // scroll func
    jQuery(window).scroll(function() {
        var winScroll =
            document.body.scrollTop || document.documentElement.scrollTop;
        var height =
            document.documentElement.scrollHeight -
            document.documentElement.clientHeight;
        var scrolled = (winScroll / height) * 100;

        jQuery(".eael-scroll-progress-fill").css({
            width: scrolled + "%"
        });
    });

    // live prev
    if (isEditMode) {
        elementor.settings.page.addChangeCallback(
            "eael_ext_scroll_progress",
            function(newValue) {
                var $settings = elementor.settings.page.getSettings();

                if (newValue == "yes") {
                    if (jQuery(".eael-scroll-progress").length == 0) {
                        jQuery("body").append(
                            '<div class="eael-scroll-progress eael-scroll-progress-' +
                                $settings.settings
                                    .eael_ext_scroll_progress_position +
                                '"><div class="eael-scroll-progress-fill"></div></div>'
                        );
                    }

                    jQuery(".eael-scroll-progress").css({
                        display: "initial"
                    });
                } else {
                    jQuery(".eael-scroll-progress").css({
                        display: "none"
                    });
                }
            }
        );

        elementor.settings.page.addChangeCallback(
            "eael_ext_scroll_progress_position",
            function(newValue) {
                elementor.settings.page.setSettings(
                    "eael_ext_scroll_progress_position",
                    newValue
                );
                jQuery(".eael-scroll-progress")
                    .removeClass(
                        "eael-scroll-progress-top eael-scroll-progress-bottom"
                    )
                    .addClass("eael-scroll-progress-" + newValue);
            }
        );
    }
});
