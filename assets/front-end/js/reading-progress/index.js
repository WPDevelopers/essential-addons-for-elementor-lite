jQuery(document).ready(function() {
    // scroll func
    jQuery(window).scroll(function() {
        var winScroll =
            document.body.scrollTop || document.documentElement.scrollTop;
        var height =
            document.documentElement.scrollHeight -
            document.documentElement.clientHeight;
        var scrolled = (winScroll / height) * 100;

        jQuery(".eael-reading-progress-fill").css({
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
                    if (jQuery(".eael-reading-progress").length == 0) {
                        jQuery("body").append(
                            '<div class="eael-reading-progress eael-reading-progress-' +
                                $settings.settings
                                    .eael_ext_scroll_progress_position +
                                '"><div class="eael-reading-progress-fill"></div></div>'
                        );
                    }

                    jQuery(".eael-reading-progress").css({
                        display: "initial"
                    });
                } else {
                    jQuery(".eael-reading-progress").css({
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
                jQuery(".eael-reading-progress")
                    .removeClass(
                        "eael-reading-progress-top eael-reading-progress-bottom"
                    )
                    .addClass("eael-reading-progress-" + newValue);
            }
        );
    }
});
