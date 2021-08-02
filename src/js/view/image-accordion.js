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
        $("#eael-img-accordion-" + $id + " .eael-image-accordion-hover").on("click", function (e) {
            if ($(this).hasClass("overlay-active") == false) {
                e.preventDefault();
            }
            $("#eael-img-accordion-" + $id + " .eael-image-accordion-hover",$scope).removeClass("overlay-active");
            if ($clickCount == 0) {
                if ($("#eael-img-accordion-" + $id + " .eael-image-accordion-hover")
                    .hasClass('overlay-active')) {
                    $("#eael-img-accordion-" + $id + " .eael-image-accordion-hover")
                        .removeClass("overlay-active");
                }
                $clickCount += 1;
            }

            $("#eael-img-accordion-" + $id + " .eael-image-accordion-hover").css("flex", "1");

            $(this)
                .find(".overlay")
                .parent(".eael-image-accordion-hover")
                .addClass("overlay-active");
            $("#eael-img-accordion-" + $id + " .eael-image-accordion-hover")
                .find(".overlay-inner")
                .removeClass("overlay-inner-show");
            $(this)
                .find(".overlay-inner")
                .addClass("overlay-inner-show");
            $(this).css("flex", "3");
        });

    } else {
        $("#eael-img-accordion-" + $id + " .eael-image-accordion-hover").on('hover', function () {
            if ($("#eael-img-accordion-" + $id + " .eael-image-accordion-hover")
                .hasClass('overlay-active')) {
                $("#eael-img-accordion-" + $id + " .eael-image-accordion-hover.overlay-active").css("flex", "1");
                $("#eael-img-accordion-" + $id + " .eael-image-accordion-hover").removeClass("overlay-active");
                $("#eael-img-accordion-" + $id + " .eael-image-accordion-hover .overlay .overlay-inner").removeClass('overlay-inner-show');
            }
        });
    }
};
ea.hooks.addAction("init", "ea", () => {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-image-accordion.default",
        ImageAccordion
    );
});
