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
            "eael_ext_reading_progress",
            function(newValue) {
                var $settings = elementor.settings.page.getSettings();

                if (newValue == "yes") {
                    if (jQuery(".eael-reading-progress-wrap").length == 0) {
                        jQuery("body").append(
                            '<div class="eael-reading-progress-wrap eael-reading-progress-wrap-local"><div class="eael-reading-progress eael-reading-progress-local eael-reading-progress-' +
                                $settings.settings
                                    .eael_ext_reading_progress_position +
                                '"><div class="eael-reading-progress-fill"></div></div><div class="eael-reading-progress eael-reading-progress-global eael-reading-progress-' +
                                $settings.settings
                                    .eael_ext_reading_progress_position +
                                '"><div class="eael-reading-progress-fill"></div></div></div>'
                        );
                    }

                    jQuery(".eael-reading-progress-wrap")
                        .addClass("eael-reading-progress-wrap-local")
                        .removeClass(
                            "eael-reading-progress-wrap-global eael-reading-progress-wrap-disabled"
                        );
                } else {
                    jQuery(".eael-reading-progress-wrap").removeClass(
                        "eael-reading-progress-wrap-local eael-reading-progress-wrap-global"
                    );

                    if (
                        $settings.settings
                            .eael_ext_reading_progress_has_global == true
                    ) {
                        jQuery(".eael-reading-progress-wrap").addClass(
                            "eael-reading-progress-wrap-global"
                        );
                    } else {
                        jQuery(".eael-reading-progress-wrap").addClass(
                            "eael-reading-progress-wrap-disabled"
                        );
                    }
                }
            }
        );

        elementor.settings.page.addChangeCallback(
            "eael_ext_reading_progress_position",
            function(newValue) {
                elementor.settings.page.setSettings(
                    "eael_ext_reading_progress_position",
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
