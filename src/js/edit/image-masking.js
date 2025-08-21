let ImageMaskingHandler = function ($scope, $) {
    function get_clip_path( shape ){
        let shapes = {
            'circle': 'circle(50% at 50% 50%)',
            'ellipse': 'ellipse(50% 35% at 50% 50%)',
            'inset': 'inset(10% 10% 10% 10%)',
            'triangle': 'polygon(50% 0%, 0% 100%, 100% 100%)',
            'trapezoid': 'polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%)',
            'parallelogram': 'polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%)',
            'rhombus': 'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)',
            'pentagon': 'polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%)',
            'hexagon': 'polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%)',
            'heptagon': 'polygon(50% 0%, 90% 20%, 100% 60%, 75% 100%, 25% 100%, 0% 60%, 10% 20%)',
            'octagon': 'polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%)',
            'nonagon': 'polygon(50% 0%, 85% 15%, 100% 50%, 85% 85%, 50% 100%, 15% 85%, 0% 50%, 15% 15%)',
            'decagon': 'polygon(50% 0%, 80% 10%, 100% 40%, 95% 80%, 65% 100%, 35% 100%, 5% 80%, 0% 40%, 20% 10%)',
            'star': 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)',
            'cross': 'polygon(30% 0%, 70% 0%, 70% 30%, 100% 30%, 100% 70%, 70% 70%, 70% 100%, 30% 100%, 30% 70%, 0% 70%, 0% 30%, 30% 30%)',
            'arrow': 'polygon(0% 40%, 60% 40%, 60% 20%, 100% 50%, 60% 80%, 60% 60%, 0% 60%)',
            'left_arrow': 'polygon(100% 40%, 40% 40%, 40% 20%, 0% 50%, 40% 80%, 40% 60%, 100% 60%)',
            'chevron': 'polygon(25% 0%, 100% 50%, 25% 100%, 0% 75%, 50% 50%, 0% 25%)',
            'message': 'polygon(0% 0%, 100% 0%, 100% 75%, 75% 75%, 50% 100%, 50% 75%, 0% 75%)',
            'close': 'polygon(20% 0%, 50% 30%, 80% 0%, 100% 20%, 70% 50%, 100% 80%, 80% 100%, 50% 70%, 20% 100%, 0% 80%, 30% 50%, 0% 20%)',
            'frame': 'polygon(0% 0%, 0% 100%, 25% 100%, 25% 25%, 75% 25%, 75% 75%, 25% 75%, 25% 100%, 100% 100%, 100% 0%)',
            'rabbet': 'polygon(20% 0%, 80% 0%, 80% 20%, 100% 20%, 100% 80%, 80% 80%, 80% 100%, 20% 100%, 20% 80%, 0% 80%, 0% 20%, 20% 20%)',
            'starburst': 'polygon(50% 0%, 60% 20%, 80% 10%, 70% 30%, 90% 50%, 70% 70%, 80% 90%, 60% 80%, 50% 100%, 40% 80%, 20% 90%, 30% 70%, 10% 50%, 30% 30%, 20% 10%, 40% 20%)',
            'blob': 'polygon(50% 0%, 80% 10%, 100% 40%, 90% 70%, 60% 100%, 30% 90%, 10% 60%, 0% 30%, 20% 10%)'
        };
        return shapes[shape] || '';
    }
    function renderImageMasking (model) {
        let settings = model?.attributes?.settings?.attributes;
        let elementId = model?.attributes?.id, element = $(`.elementor-element-${elementId}`);
        let styleId = 'eael-image-masking-' + elementId;

        // Remove existing style if present
        $('#' + styleId).remove();

        if( 'yes' === settings?.eael_enable_image_masking ) {
            let style = '';
            if( 'clip' === settings?.eael_image_masking_type ){
                let clipPath = get_clip_path( settings?.eael_image_masking_clip_path );
                if( 'custom' === settings?.eael_image_masking_clip_path ){
                    clipPath = settings?.eael_image_masking_custom_clip_path;
                    clipPath = clipPath.replace( 'clip-path: ', '' );
                }
                if( clipPath ) {
                    style += '.elementor-element-' + elementId + ' img {clip-path: ' + clipPath + ';}';
                }
            }

            if( style ){
                element.append('<style id="' + styleId + '">' + style + '</style>');
            }
        }
    }
    function getImageMaskingSettingsVal( models ) {
        $.each(models, function (_, model) {
            renderImageMasking( model );

            if ( model.attributes.elType !== 'widget' ) {
                getImageMaskingSettingsVal( model.attributes.elements.models );
            }
        });
    }

    getImageMaskingSettingsVal( window.elementor.elements.models );
}

jQuery(window).on("elementor/frontend/init", function () {
    if (eael.elementStatusCheck('eaelImageMaskingEditor')) {
        return false;
    }
    elementorFrontend.hooks.addAction( "frontend/element_ready/widget", ImageMaskingHandler );
});