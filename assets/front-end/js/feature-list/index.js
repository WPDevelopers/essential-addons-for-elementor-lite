;(function ($) {
    jQuery(document).ready(function () {
        if (isEditMode) {
            elementor.settings.editorPreferences.addChangeCallback(
                'eael_feature_list_icon_position',
                function (newValue) {
                    // var iconElement = $('.eael-toc-button i')
                    // iconElement.removeClass().addClass(newValue.value)
                    console.log(newValue)
                    console.log('changed')
                }
            )
        }
    })
})(jQuery)

// jQuery(window).on('elementor/frontend/init', function () {
//     elementorFrontend.hooks.addAction(
//         'frontend/element_ready/eael-feature-list.default',
//         FeatureListHandler
//     )
// })
