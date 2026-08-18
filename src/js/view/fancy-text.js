var FancyText = function ($scope, $) {
    // Only these animation names are ever emitted by the widget. Anything else
    // is attacker-supplied (e.g. via a ShortCode widget) and must not reach
    // Morphext, which uses it as a CSS class token (CVE-2026-15145).
    var ALLOWED_TRANSITIONS = [
        "typing", "fadeIn", "fadeInUp", "fadeInDown", "fadeInLeft",
        "fadeInRight", "zoomIn", "bounceIn", "swing"
    ];

    var $fancyText = $scope.find(".eael-fancy-text-container").eq(0);
    var rawTransition = $fancyText.data("fancy-text-transition-type");
    var transitionType = ALLOWED_TRANSITIONS.indexOf(rawTransition) !== -1
        ? rawTransition
        : "fadeIn";

    var config = {
        id: $fancyText.data("fancy-text-id"),
        text: DOMPurify.sanitize($fancyText.data("fancy-text") || "").split("|"),
        transitionType: transitionType,
        speed: $fancyText.data("fancy-text-speed"),
        delay: $fancyText.data("fancy-text-delay"),
        showCursor: $fancyText.data("fancy-text-cursor") === "yes",
        loop: $fancyText.data("fancy-text-loop") === "yes",
        action: $fancyText.data("fancy-text-action")
    };

    function initTyped() {
        var typedConfig = {
            strings: config.text,
            typeSpeed: config.speed,
            backSpeed: 0,
            startDelay: 300,
            backDelay: config.delay,
            showCursor: config.showCursor,
            loop: config.loop
        };

        return new Typed("#eael-fancy-text-" + config.id, typedConfig);
    }

    function initMorphext() {
        $("#eael-fancy-text-" + config.id).Morphext({
            animation: config.transitionType,
            separator: ", ",
            // Hand the sanitized strings over directly. Left to itself Morphext
            // reads the rendered <noscript> fallback, which strips the author's
            // inline markup and splits any phrase containing the separator.
            phrases: config.text,
            speed: config.delay,
            complete: function () {
                if (!config.loop && ($(this)[0].index + 1) === $(this)[0].phrases.length) {
                    $(this)[0].stop();
                }
            }
        });
    }

    if (config.transitionType === "typing") {
        if ( 'page_load' === config.action ) {
            initTyped();
        } else {
            $(window).on('scroll', function() {
                if ($fancyText.isInViewport(1) && !$fancyText.hasClass('eael-animated')) {
                    initTyped();
                    $fancyText.addClass('eael-animated');
                }
            });
        }
    } else {
        if ( 'page_load' === config.action ) {
            initMorphext();
        } else {
            $(window).on('scroll', function() {
                if ($fancyText.isInViewport(1) && !$fancyText.hasClass('eael-animated')) {
                    initMorphext();
                    $fancyText.addClass('eael-animated');
                }
            });
        }
    }

    // Show fancy text after initialization
    function showFancyText() {
        $(".eael-fancy-text-strings", $scope).css("display", "inline-block");
    }

    $(document).ready(function () {
        setTimeout(showFancyText, 500);
    });

    if (isEditMode) {
        setTimeout(showFancyText, 800);
    }
};

jQuery(window).on("elementor/frontend/init", function () {
    if (eael.elementStatusCheck('eaelFancyTextLoad')) {
        return false;
    }
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-fancy-text.default", FancyText);
});
