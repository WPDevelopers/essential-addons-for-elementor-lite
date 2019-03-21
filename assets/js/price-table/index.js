var PricingTooltip = function($scope, $) {
    if ($.fn.tooltipster) {
        var $tooltip = $scope.find(".tooltip"),
            i;

        for (i = 0; i < $tooltip.length; i++) {
            var $currentTooltip = $("#" + $($tooltip[i]).attr("id")),
                $tooltipSide =
                    $currentTooltip.data("side") !== undefined
                        ? $currentTooltip.data("side")
                        : false,
                $tooltipTrigger =
                    $currentTooltip.data("trigger") !== undefined
                        ? $currentTooltip.data("trigger")
                        : "hover",
                $animation =
                    $currentTooltip.data("animation") !== undefined
                        ? $currentTooltip.data("animation")
                        : "fade",
                $anim_duration =
                    $currentTooltip.data("animation_duration") !== undefined
                        ? $currentTooltip.data("animation_duration")
                        : 300,
                $theme =
                    $currentTooltip.data("theme") !== undefined
                        ? $currentTooltip.data("theme")
                        : "default",
                $arrow = "yes" == $currentTooltip.data("arrow") ? true : false;

            $currentTooltip.tooltipster({
                animation: $animation,
                trigger: $tooltipTrigger,
                side: $tooltipSide,
                delay: $anim_duration,
                arrow: $arrow,
                theme: "tooltipster-" + $theme
            });
        }
    }
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-pricing-table.default",
        PricingTooltip
    );
});
