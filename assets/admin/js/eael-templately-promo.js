(function ($) {
    $("document").ready(function () {
        let templateAddSection = $("#tmpl-elementor-add-section");
        if (0 < templateAddSection.length) {
            var oldTemplateButton = templateAddSection.html();
            oldTemplateButton = oldTemplateButton.replace(
                '<div class="elementor-add-section-drag-title',
                '<div class="elementor-add-section-area-button elementor-add-templately-promo-button"><i class="eaicon-easyjobs"></i></div><div class="elementor-add-section-drag-title'
            );
            templateAddSection.html(oldTemplateButton);

        }
    });
})(jQuery);
