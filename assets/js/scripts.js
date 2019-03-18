(function($) {
    window.isEditMode = false;

    $(window).on("elementor/frontend/init", function() {
        window.isEditMode = elementorFrontend.isEditMode();
    });
})(jQuery);
