let HoverEffectHandler = function ($scope, $) {
    let $eaelRotateEffect = $scope.data('eael_rotate_effect'),
    $eaelScaleEffect      = $scope.data('eael_scale_effect'),
    $eaelSkewEffect       = $scope.data('eael_skew_effect'),
    $Opacity              = $scope.data('eael_opacity'),
    $eaelBlurEffect       = $scope.data('eael_blur_effect'),
    $eaelContrastEffect   = $scope.data('eael_contrast_effect'),
    $eaelGrayscaleEffect  = $scope.data('eael_grayscale_effect'),
    $eaelInvertEffect     = $scope.data('eael_invert_effect'),
    $eaelSaturateEffect   = $scope.data('eael_saturate_effect'),
    $eaelSepiaEffect      = $scope.data('eael_sepia_effect'),
    $scopeId = $scope.data('id'),
    $eaelBurHoverEffect = $scope.data('eael_blur_hover_effect'),
    $eaelContrastHoverEffect = $scope.data('eael_contrast_hover_effect'),
    $eaelGrayscalHoverEffect = $scope.data('eael_grayscal_hover_effect'),
    $eaelInvertHoverEffect = $scope.data('eael_invert_hover_effect'),
    $eaelSaturateHoverEffect = $scope.data('eael_saturate_hover_effect'),
    $eaelSepiaHoverEffect = $scope.data('eael_sepia_hover_effect'),
    $eaelRotateHoverEffect = $scope.data('eael_rotate_hover_effect'),
    $eaelScaleHoverEffect = $scope.data('eael_scale_hover_effect'),
    $eaelSkewHoverEffect = $scope.data('eael_skew_hover_effect'),
    $opacityHover              = $scope.data('eael_opacity_hover'),
    $eaelDuration              = $scope.data('eael_duration'),
    $eaelDelay              = $scope.data('eael_delay'),
    $eaelEasing              = $scope.data('eael_easing'),
    $eaelHoverDuration              = $scope.data('eael_hover_duration'),
    $eaelHoverDelay              = $scope.data('eael_hover_delay'),
    $eaelHoverEasing              = $scope.data('eael_hover_easing'),
    $eaelOffsetTop              = $scope.data('eael_offset_top'),
    $eaelOffsetLeft              = $scope.data('eael_offset_left'),
    $eaelOffsetHoverTop              = $scope.data('eael_offset_hover_top'),
    $eaelOffsetHoverLeft              = $scope.data('eael_offset_hover_left'),
    $eaelContainer        = $('.elementor-widget-container', $scope);

    /**
     * For editor page
     */
    if( window.isEditMode ) {
        if( window.isRunFirstTime === undefined && window.isEditMode || 1 ) {
            window.isRunFirstTime = true;
            var eaelEditModeSettings = [];
    
            function getSettingsVal( $el ) {
                $.each($el, function (i, el) {
                    let $getSettings = el.attributes.settings.attributes;
                    if ( el.attributes.elType === 'widget' ) {
                        if ( $getSettings['eael_hover_effect_switch'] === 'yes' && $getSettings['eael_hover_effect_enable_live_changes'] == 'yes' ) {
                            eaelEditModeSettings[el.attributes.id] = el.attributes.settings.attributes;
                        }
                    }
    
                    if ( el.attributes.elType === 'container' ) {
                        getSettingsVal( el.attributes.elements.models );
                    }
                });
            }
    
            getSettingsVal(window.elementor.elements.models);
        }
    
        for ( let key in eaelEditModeSettings ) {
            if( $scopeId === key ) {
                $Opacity = {'opacity': eaelEditModeSettings?.[key]?.['eael_hover_effect_opacity']?.['size']};
                $opacityHover = {'opacity': eaelEditModeSettings?.[key]?.['eael_hover_effect_opacity_hover']?.['size']};
                //FIlter
                $eaelBlurEffect = {'blur': eaelEditModeSettings?.[key]?.['eael_hover_effect_blur']?.['size']};
                $eaelContrastEffect = {'contrast': eaelEditModeSettings?.[key]?.['eael_hover_effect_contrast']?.['size']};
                $eaelGrayscaleEffect = {'grayscale': eaelEditModeSettings?.[key]?.['eael_hover_effect_grayscal']?.['size']};
                $eaelInvertEffect = {'invert': eaelEditModeSettings?.[key]?.['eael_hover_effect_invert']?.['size']};
                $eaelSaturateEffect = {'saturate': eaelEditModeSettings?.[key]?.['eael_hover_effect_saturate']?.['size']};
                $eaelSepiaEffect = {'sepia': eaelEditModeSettings?.[key]?.['eael_hover_effect_sepia']?.['size']};

                //Filter Hover
                $eaelBurHoverEffect = {'blur': eaelEditModeSettings?.[key]?.['eael_hover_effect_blur_hover']?.['size']};
                $eaelContrastHoverEffect = {'contrast': eaelEditModeSettings?.[key]?.['eael_hover_effect_contrast_hover']?.['size']};
                $eaelGrayscalHoverEffect = {'grayscale': eaelEditModeSettings?.[key]?.['eael_hover_effect_grayscal_hover']?.['size']};
                $eaelInvertHoverEffect = {'invert': eaelEditModeSettings?.[key]?.['eael_hover_effect_invert_hover']?.['size']};
                $eaelSaturateHoverEffect = {'saturate': eaelEditModeSettings?.[key]?.['eael_hover_effect_saturate_hover']?.['size']};
                $eaelSepiaHoverEffect = {'sepia': eaelEditModeSettings?.[key]?.['eael_hover_effect_sepia_hover']?.['size']};

                //Offset
                $eaelOffsetTop = {
                    'size': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_top']?.['size'],
                    'unit': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_top']?.['unit']
                };
                $eaelOffsetLeft = {
                    'size': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_left']?.['size'],
                    'unit': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_left']?.['unit']
                };

                //Offset Hover
                $eaelOffsetHoverTop = {
                    'size': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_hover_top']?.['size'],
                    'unit': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_hover_top']?.['unit']
                };
                $eaelOffsetHoverLeft = {
                    'size': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_hover_left']?.['size'],
                    'unit': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_hover_left']?.['unit']
                };

                //Tranform
                $eaelRotateEffect = {
                    'rotate_x': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_rotatex']?.['size'],
                    'rotate_y': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_rotatey']?.['size'],
                    'rotate_z': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_rotatez']?.['size']
                };
                $eaelScaleEffect = {
                    'scale_x': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_scalex']?.['size'],
                    'scale_y': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_scaley']?.['size']
                };
                $eaelSkewEffect = {
                    'skew_x': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_skewx']?.['size'],
                    'skew_y': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_skewy']?.['size']
                };

                //Tranform Hover
                $eaelRotateHoverEffect = {
                    'rotate_x': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_rotatex']?.['size'],
                    'rotate_y': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_rotatey']?.['size'],
                    'rotate_z': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_rotatez']?.['size']
                };
                $eaelScaleHoverEffect = {
                    'scale_x': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_scalex']?.['size'],
                    'scale_y': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_scaley']?.['size']
                };
                $eaelSkewHoverEffect = {
                    'skew_x': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_skewx']?.['size'],
                    'skew_y': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_skewy']?.['size']
                };
                //Transition
                $eaelDuration = {'transitionDuration': eaelEditModeSettings?.[key]?.['eael_hover_effect_general_settings_duration']?.['size']};
                $eaelDelay = {'transitionDelay': eaelEditModeSettings?.[key]?.['eael_hover_effect_general_settings_delay']?.['size']};
                $eaelEasing = {'transitionEasing': eaelEditModeSettings?.[key]?.['eael_hover_effect_general_settings_easing']};

                //Transition Hover
                $eaelHoverDuration = {'transitionDuration': eaelEditModeSettings?.[key]?.['eael_hover_effect_general_settings_duration']?.['size']};
                $eaelHoverDelay = {'transitionDelay': eaelEditModeSettings?.[key]?.['eael_hover_effect_general_settings_delay']?.['size']};
                $eaelHoverEasing = {'transitionEasing': eaelEditModeSettings?.[key]?.['eael_hover_effect_general_settings_easing']};
            }
        }
    }

    let hoverSelector = `body [data-id="${$scopeId}"] > .elementor-widget-container`;

    //Opacity
    let $opacityVal = $Opacity ? $Opacity?.opacity : '1';

    //Offset
    let $offsetX = $eaelOffsetTop?.size ? `translateX(${$eaelOffsetTop.size}${$eaelOffsetTop.unit})` : 'translateX(0)';
    let $offsetY = $eaelOffsetLeft?.size ? `translateY(${$eaelOffsetLeft.size}${$eaelOffsetLeft.unit})` : 'translateY(0)';

    //Offset Hover
    let $offsetHoverX = $eaelOffsetHoverTop?.size ? `translateX(${$eaelOffsetHoverTop.size}${$eaelOffsetHoverTop.unit})` : 'translateX(0)';
    let $offsetHoverY = $eaelOffsetHoverLeft?.size ? `translateY(${$eaelOffsetHoverLeft.size}${$eaelOffsetHoverLeft.unit})` : 'translateY(0)';
    
    //Transitions
    let $eaelDurationVal = $eaelDuration ? $eaelDuration?.transitionDuration : '0';
    let $eaelDelayVal = $eaelDelay ? $eaelDelay?.transitionDelay : '0';
    let $eaelEasingVal = $eaelEasing ? $eaelEasing?.transitionEasing : '0';

    //Transitions Hover
    let $eaelDurationHoverVal = $eaelHoverDuration ? $eaelHoverDuration?.transitionDuration : '0';
    let $eaelDelayHoverVal = $eaelHoverDelay ? $eaelHoverDelay?.transitionDelay : '0';
    let $eaelEasingHoverVal = $eaelHoverEasing ? $eaelHoverEasing?.transitionEasing : '0';

    //Filter
    let $blur      = $eaelBlurEffect?.blur ? `blur(${$eaelBlurEffect.blur}px)`                        : 'blur(0px)';
    let $contrast  = $eaelContrastEffect?.contrast ? `contrast(${$eaelContrastEffect.contrast}%)`     : 'contrast(100%)';
    let $grayscale = $eaelGrayscaleEffect?.grayscale ? `grayscale(${$eaelGrayscaleEffect.grayscale}%)`: 'grayscale(0%)';
    let $invert    = $eaelInvertEffect?.invert ? `invert(${$eaelInvertEffect.invert}%)`               : 'invert(0%)';
    let $saturate  = $eaelSaturateEffect?.saturate ? `saturate(${$eaelSaturateEffect.saturate}%)`     : 'saturate(100%)';
    let $sepia     = $eaelSepiaEffect?.sepia ? `sepia(${$eaelSepiaEffect.sepia}%)` : 'sepia(0%)';

    //Rotate
    let $rotateX = $eaelRotateEffect?.rotate_x ? `rotateX(${$eaelRotateEffect.rotate_x}deg)` : 'rotateX(0)';
    let $rotateY = $eaelRotateEffect?.rotate_y ? `rotateY(${$eaelRotateEffect.rotate_y}deg)` : 'rotateY(0)';
    let $rotateZ = $eaelRotateEffect?.rotate_z ? `rotateZ(${$eaelRotateEffect.rotate_z}deg)` : 'rotateZ(0)';

    //Scale
    let $scaleX = $eaelScaleEffect?.scale_x ? `scaleX(${$eaelScaleEffect.scale_x})` : 'scaleX(1)';
    let $scaleY = $eaelScaleEffect?.scale_y ? `scaleY(${$eaelScaleEffect.scale_y})` : 'scaleY(1)';
    
    //Skew
    let $skewX = $eaelSkewEffect?.skew_x ? `skewX(${$eaelSkewEffect.skew_x}deg)` : 'skewX(0deg)';
    let $skewY = $eaelSkewEffect?.skew_y ? `skewY(${$eaelSkewEffect.skew_y}deg)` : 'skewY(0deg)';

    //Hover
    let $opacityHoverVal = $opacityHover ? $opacityHover?.opacity : '1';
    //Rotate
    let $rotateXHover = $eaelRotateHoverEffect?.rotate_x ? `rotateX(${$eaelRotateHoverEffect.rotate_x}deg)` : 'rotateX(0)';
    let $rotateYHover = $eaelRotateHoverEffect?.rotate_y ? `rotateY(${$eaelRotateHoverEffect.rotate_y}deg)` : 'rotateY(0)';
    let $rotateZHover = $eaelRotateHoverEffect?.rotate_z ? `rotateZ(${$eaelRotateHoverEffect.rotate_z}deg)` : 'rotateZ(0)';

    //Scale
    let $scaleXHover = $eaelScaleHoverEffect?.scale_x ? `scaleX(${$eaelScaleHoverEffect.scale_x})` : 'scaleX(1)';
    let $scaleYHover = $eaelScaleHoverEffect?.scale_y ? `scaleY(${$eaelScaleHoverEffect.scale_y})` : 'scaleY(1)';
    
    //Skew
    let $skewXHover = $eaelSkewHoverEffect?.skew_x ? `skewX(${$eaelSkewHoverEffect.skew_x}deg)` : 'skewX(0)';
    let $skewYHover = $eaelSkewHoverEffect?.skew_y ? `skewY(${$eaelSkewHoverEffect.skew_y}deg)` : 'skewY(0)';
    
    //Filter Hover
    let $blurHover      = $eaelBurHoverEffect?.blur ? `blur(${$eaelBurHoverEffect.blur}px)` : 'blur(0px)';
    let $contrastHover      = $eaelContrastHoverEffect?.contrast ? `contrast(${$eaelContrastHoverEffect.contrast}%)` : 'contrast(100%)';
    let $grayscaleHover      = $eaelGrayscalHoverEffect?.grayscale ? `grayscale(${$eaelGrayscalHoverEffect.grayscale}%)` : 'grayscale(0%)';
    let $invertHover      = $eaelInvertHoverEffect?.invert ? `invert(${$eaelInvertHoverEffect.invert}%)` : 'invert(0%)';
    let $saturateHover      = $eaelSaturateHoverEffect?.saturate ? `saturate(${$eaelSaturateHoverEffect.saturate}%)` : 'saturate(100%)';
    let $sepiaHover      = $eaelSepiaHoverEffect?.sepia ? `sepia(${$eaelSepiaHoverEffect.sepia}%)` : 'sepia(0%)';
    
    //Normal
    let normalStyles = {
        "transform": `${$rotateX} ${$rotateY} ${$rotateZ} ${$scaleX} ${$scaleY} ${$skewX} ${$skewY} ${$offsetX} ${$offsetY}`,
        "opacity": $opacityVal,
        "filter": `${$blur} ${$contrast} ${$grayscale} ${$invert} ${$saturate} ${$sepia}`,
        "transition-property": 'all',
        "transition-duration": `${$eaelDurationVal}ms`,
        "transition-delay": `${$eaelDelayVal}ms`,
        "transition-timing-function": $eaelEasingVal,
    }

    //Hover
    let hoverStyles = {
        'opacity': $opacityHoverVal,
        'filter': `${$blurHover} ${$contrastHover} ${$grayscaleHover} ${$invertHover} ${$saturateHover} ${$sepiaHover}`,
        "transform": `${$rotateXHover} ${$rotateYHover} ${$rotateZHover} ${$scaleXHover} ${$scaleYHover} ${$skewXHover} ${$skewYHover} ${$offsetHoverX} ${$offsetHoverY}`,
        "transition-property": 'all',
        "transition-duration": `${$eaelDurationHoverVal}ms`,
        "transition-delay": `${$eaelDelayHoverVal}ms`,
        "transition-timing-function": $eaelEasingHoverVal,
    };

    $(hoverSelector).hover(
        function() {
            $(this).css(hoverStyles);
        },
        function() {
        $(this).css(normalStyles);
        }
    );  

    //
    $eaelContainer.css(normalStyles);
}

jQuery(window).on("elementor/frontend/init", function () {
    if (ea.elementStatusCheck('eaelHoverEffect')) {
        return false;
    }
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/widget", 
        HoverEffectHandler
    );
});
