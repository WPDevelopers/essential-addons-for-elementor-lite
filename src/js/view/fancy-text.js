var FancyText = function ($scope, $) {
    var $fancyText = $scope.find(".eael-fancy-text-container").eq(0),
        $id = $fancyText.data("fancy-text-id") !== undefined ? $fancyText.data("fancy-text-id") : "",
        $fancy_text = $fancyText.data("fancy-text") !== undefined ? $fancyText.data("fancy-text") : "",
        $transition_type = $fancyText.data("fancy-text-transition-type") !== undefined ? $fancyText.data("fancy-text-transition-type") : "",
        $fancy_text_speed = $fancyText.data("fancy-text-speed") !== undefined ? $fancyText.data("fancy-text-speed") : "",
        $fancy_text_delay = $fancyText.data("fancy-text-delay") !== undefined ? $fancyText.data("fancy-text-delay") : "",
        $fancy_text_cursor = $fancyText.data("fancy-text-cursor") === "yes",
        $fancy_text_loop = $fancyText.data("fancy-text-loop") !== undefined ? ($fancyText.data("fancy-text-loop") === "yes") : false;

    $fancy_text = DOMPurify.sanitize($fancy_text).split("|");

    if ($transition_type === "typing") {
        new Typed("#eael-fancy-text-" + $id, {
            strings: $fancy_text,
            typeSpeed: $fancy_text_speed,
            backSpeed: 0,
            startDelay: 300,
            backDelay: $fancy_text_delay,
            showCursor: $fancy_text_cursor,
            loop: $fancy_text_loop,
        });
    }

    if ($transition_type !== "typing") {
        $("#eael-fancy-text-" + $id).Morphext({
            animation: $transition_type,
            separator: ", ",
            speed: $fancy_text_delay,
            complete: function () {
                if (!$fancy_text_loop && ($(this)[0].index + 1) === $(this)[0].phrases.length) {
                    $(this)[0].stop();
                }
            }
        });
    }

    $(document).ready(function () {
        setTimeout(function () {
            $(".eael-fancy-text-strings", $scope).css("display", "inline-block");
        }, 500);
    });


    if (isEditMode) {
        setTimeout(function () {
            $(".eael-fancy-text-strings", $scope).css("display", "inline-block");
        }, 800);
    }
};
jQuery(window).on("elementor/frontend/init", function () {

    if (eael.elementStatusCheck('eaelFancyTextLoad')) {
        return false;
    }

    elementorFrontend.hooks.addAction("frontend/element_ready/eael-fancy-text.default", FancyText);
});
