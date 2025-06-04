let CustomCursorHandler = function ($scope, $) {
    if( window.isEditMode ) {
        function renderCustomCursor (model) {
            let settings = model?.attributes?.settings?.attributes;
            
            if( 'yes' === settings?.eael_custom_cursor_switch ) {
                let elementId = model?.attributes?.id, element = $(`.elementor-element-${elementId}`);
                
                if( 'image' === settings?.eael_custom_cursor_type ) {
                    element.attr('style', 'cursor: url("' + settings?.eael_custom_cursor_image?.url + '") 0 0, auto;');
                } else if( 'icon' === settings?.eael_custom_cursor_type ) {
                    element.attr('style', 'cursor: url("data:image/svg+xml;base64,' + settings?.eael_custom_cursor_icon?.value + '") 0 0, auto;');
                } else if( 'svg_code' === settings?.eael_custom_cursor_type ) {
                    element.attr('style', 'cursor: url("data:image/svg+xml;base64,' + btoa(settings?.eael_custom_cursor_svg_code) + '") 0 0, auto;');
                }
            }
        }
        function getHoverEffectSettingsVal( models ) {
            $.each(models, function (i, model) {
                renderCustomCursor( model );

                if ( model.attributes.elType !== 'widget' ) {
                    getHoverEffectSettingsVal( model.attributes.elements.models );
                }
            });
        }

        getHoverEffectSettingsVal( window.elementor.elements.models );
    }
    
}

jQuery(window).on("elementor/frontend/init", function () {
    if (eael.elementStatusCheck('eaelCustomCursor')) {
        return false;
    }
    elementorFrontend.hooks.addAction( "frontend/element_ready/widget", CustomCursorHandler );
});