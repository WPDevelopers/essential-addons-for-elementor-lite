var ImageAccordion = function ($scope, $) {
    var $imageAccordion = $scope.find(".eael-img-accordion").eq(0),
        $id =
            $imageAccordion.data("img-accordion-id") !== undefined
                ? $imageAccordion.data("img-accordion-id")
                : "",
        $type =
            $imageAccordion.data("img-accordion-type") !== undefined
                ? $imageAccordion.data("img-accordion-type")
                : "";
    var $clickCount = 0;
    if ("on-click" === $type) {
        $("#eael-img-accordion-" + $id + " a").on("click", function (e) {
            if ($(this).hasClass("overlay-active") == false) {
                e.preventDefault();
            }

            if ($clickCount == 0) {
                if ($("#eael-img-accordion-" + $id + " a")
                    .hasClass('overlay-active')) {
                    $("#eael-img-accordion-" + $id + " a")
                        .removeClass("overlay-active");
                }
                $clickCount += 1;
            }

            $("#eael-img-accordion-" + $id + " a").css("flex", "1");

            $(this)
                .find(".overlay")
                .parent("a")
                .addClass("overlay-active");
            $("#eael-img-accordion-" + $id + " a")
                .find(".overlay-inner")
                .removeClass("overlay-inner-show");
            $(this)
                .find(".overlay-inner")
                .addClass("overlay-inner-show");
            $(this).css("flex", "3");
        });
        $("#eael-img-accordion-" + $id + " a").on("blur", function (e) {
            $("#eael-img-accordion-" + $id + " a").css("flex", "1");
            $("#eael-img-accordion-" + $id + " a")
                .find(".overlay-inner")
                .removeClass("overlay-inner-show");
            $(this)
                .find(".overlay")
                .parent("a")
                .removeClass("overlay-active");
        });
    } else {
        $("#eael-img-accordion-" + $id + " a").on('hover', function () {
            if ($("#eael-img-accordion-" + $id + " a")
                .hasClass('overlay-active')) {
                $("#eael-img-accordion-" + $id + " a.overlay-active").css("flex", "1");
                $("#eael-img-accordion-" + $id + " a").removeClass("overlay-active");
                $("#eael-img-accordion-" + $id + " a .overlay .overlay-inner").removeClass('overlay-inner-show');
            }
        });
    }
};
jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-image-accordion.default",
        ImageAccordion
    );
});
