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

    function hoverAction(event, element) {
        if (element.hasClass("overlay-active") === false) {
            event.preventDefault();
        }
        let imageAccordion = $(".eael-image-accordion-hover", $scope);

        imageAccordion.removeClass("overlay-active");
        imageAccordion.css("flex", "1");
        element.find(".overlay").parent(".eael-image-accordion-hover").addClass("overlay-active");
        imageAccordion.find(".overlay-inner").removeClass("overlay-inner-show");
        element.find(".overlay-inner").addClass("overlay-inner-show");
        element.css("flex", "3");
    }

    function hoverOutAction(event, element) {
        if (element.hasClass("overlay-active") === false) {
            event.preventDefault();
        }
        let imageAccordion = $(".eael-image-accordion-hover", $scope);

        imageAccordion.removeClass("overlay-active");
        imageAccordion.css("flex", "1");
        imageAccordion.find(".overlay-inner").removeClass("overlay-inner-show");
    }

    if ("on-click" === $type) {
        $(".eael-image-accordion-hover", $scope).on("click", function (e) {
            hoverAction(e, $(this));
        });

    } else {
        $(".eael-image-accordion-hover", $scope).hover(function (e) {
            hoverAction(e, $(this));
        });
        
        $(".eael-image-accordion-hover", $scope).mouseleave(function (e) {
            console.log('leave');
            hoverOutAction(e, $(this));
        });
    }
};
ea.hooks.addAction("init", "ea", () => {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-image-accordion.default",
        ImageAccordion
    );
});
