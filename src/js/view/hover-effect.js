let HoverEffectHandler = function ($scope, $) {
    let $eaelRotateEffect    = $scope.data('eael_rotate_effect'),
    $eaelScaleEffect         = $scope.data('eael_scale_effect'),
    $eaelSkewEffect          = $scope.data('eael_skew_effect'),
    $Opacity                 = $scope.data('eael_opacity'),
    $eaelBlurEffect          = $scope.data('eael_blur_effect'),
    $eaelContrastEffect      = $scope.data('eael_contrast_effect'),
    $eaelGrayscaleEffect     = $scope.data('eael_grayscale_effect'),
    $eaelInvertEffect        = $scope.data('eael_invert_effect'),
    $eaelSaturateEffect      = $scope.data('eael_saturate_effect'),
    $eaelSepiaEffect         = $scope.data('eael_sepia_effect'),
    $scopeId                 = $scope.data('id'),
    $eaelBurHoverEffect      = $scope.data('eael_blur_hover_effect'),
    $eaelContrastHoverEffect = $scope.data('eael_contrast_hover_effect'),
    $eaelGrayscalHoverEffect = $scope.data('eael_grayscal_hover_effect'),
    $eaelInvertHoverEffect   = $scope.data('eael_invert_hover_effect'),
    $eaelSaturateHoverEffect = $scope.data('eael_saturate_hover_effect'),
    $eaelSepiaHoverEffect    = $scope.data('eael_sepia_hover_effect'),
    $eaelRotateHoverEffect   = $scope.data('eael_rotate_hover_effect'),
    $eaelScaleHoverEffect    = $scope.data('eael_scale_hover_effect'),
    $eaelSkewHoverEffect     = $scope.data('eael_skew_hover_effect'),
    $opacityHover            = $scope.data('eael_opacity_hover'),
    $eaelDuration            = $scope.data('eael_duration'),
    $eaelDelay               = $scope.data('eael_delay'),
    $eaelEasing              = $scope.data('eael_easing'),
    $eaelHoverDuration       = $scope.data('eael_hover_duration'),
    $eaelHoverDelay          = $scope.data('eael_hover_delay'),
    $eaelHoverEasing         = $scope.data('eael_hover_easing'),
    $eaelOffsetTop           = $scope.data('eael_offset_top'),
    $eaelOffsetLeft          = $scope.data('eael_offset_left'),
    $eaelOffsetHoverTop      = $scope.data('eael_offset_hover_top'),
    $eaelOffsetHoverLeft     = $scope.data('eael_offset_hover_left'),
    $eaelTilt                = $scope.data('eaeltilt'),
    $eaelContainer           = $('.elementor-widget-container', $scope);
    $enabledElementList      = [];

    /**
     * For editor page
     */
    if( window.isEditMode ) {
        if( window.isRunFirstTime === undefined && window.isEditMode || 1 ) {
            window.isRunFirstTime = true;
            var eaelEditModeSettings = [];
    
            function getHoverEffectSettingsVal( $el ) {
                $.each($el, function (i, el) {
                    // console.log(el.attributes.settings.attributes);
                    let $getSettings = el.attributes.settings.attributes;
                    if (el.attributes.elType === 'widget') {
                        if ($getSettings['eael_hover_effect_switch'] === 'yes') {
                            $enabledElementList.push(el.attributes.id);

                            if ($getSettings['eael_hover_effect_enable_live_changes'] === 'yes') {
                                eaelEditModeSettings[el.attributes.id] = el.attributes.settings.attributes;
                            }
                        }
                    }
    
                    if ( el.attributes.elType === 'container' ) {
                        getHoverEffectSettingsVal( el.attributes.elements.models );
                    }

                    if ( el.attributes.elType === 'section' ) {
                        getHoverEffectSettingsVal( el.attributes.elements.models );
                    }

                    if ( el.attributes.elType === 'column' ) {
                        getHoverEffectSettingsVal( el.attributes.elements.models );
                    }
                });
            }
    
            getHoverEffectSettingsVal( window.elementor.elements.models );
        }
    
        for ( let key in eaelEditModeSettings ) {
            if( $scopeId === key ) {

                //Tilt
                if( eaelEditModeSettings?.[key]?.['eael_hover_effect_hover_tilt'] === 'yes' ) {
                    $eaelTilt = 'eael_tilt';
                }

                //Opacity
                if( eaelEditModeSettings?.[key]?.['eael_hover_effect_opacity_popover'] === 'yes' ) {
                    $Opacity = {'opacity': eaelEditModeSettings?.[key]?.['eael_hover_effect_opacity']?.['size']};
                }
                if( eaelEditModeSettings?.[key]?.['eael_hover_effect_opacity_popover_hover'] === 'yes' ) {
                    $opacityHover = {'opacity': eaelEditModeSettings?.[key]?.['eael_hover_effect_opacity_hover']?.['size']};
                }
                //Filter
                if( 'yes' === eaelEditModeSettings?.[key]?.['eael_hover_effect_filter_popover'] ) {
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_blur_is_on'] === 'yes' ) {
                        $eaelBlurEffect = {'blur': eaelEditModeSettings?.[key]?.['eael_hover_effect_blur']?.['size']};
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_contrast_is_on'] === 'yes' ) {
                        $eaelContrastEffect = {'contrast': eaelEditModeSettings?.[key]?.['eael_hover_effect_contrast']?.['size']};
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_grayscale_is_on'] === 'yes' ) {
                        $eaelGrayscaleEffect = {'grayscale': eaelEditModeSettings?.[key]?.['eael_hover_effect_grayscal']?.['size']};
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_invert_is_on'] === 'yes' ) {
                        $eaelInvertEffect = {'invert': eaelEditModeSettings?.[key]?.['eael_hover_effect_invert']?.['size']};
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_saturate_is_on'] === 'yes' ) {
                        $eaelSaturateEffect = {'saturate': eaelEditModeSettings?.[key]?.['eael_hover_effect_saturate']?.['size']};
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_sepia_is_on'] === 'yes' ) {
                        $eaelSepiaEffect = {'sepia': eaelEditModeSettings?.[key]?.['eael_hover_effect_sepia']?.['size']};
                    }
                }

                //Filter Hover
                if( 'yes' === eaelEditModeSettings?.[key]?.['eael_hover_effect_filter_hover_popover'] ) {
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_blur_hover_is_on'] === 'yes' ) {
                        $eaelBurHoverEffect = {'blur': eaelEditModeSettings?.[key]?.['eael_hover_effect_blur_hover']?.['size']};
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_contrast_hover_is_on'] === 'yes' ) {
                        $eaelContrastHoverEffect = {'contrast': eaelEditModeSettings?.[key]?.['eael_hover_effect_contrast_hover']?.['size']};
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_grayscale_hover_is_on'] === 'yes' ) {
                        $eaelGrayscalHoverEffect = {'grayscale': eaelEditModeSettings?.[key]?.['eael_hover_effect_grayscal_hover']?.['size']};
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_invert_hover_is_on'] === 'yes' ) {
                        $eaelInvertHoverEffect = {'invert': eaelEditModeSettings?.[key]?.['eael_hover_effect_invert_hover']?.['size']};
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_saturate_hover_is_on'] === 'yes' ) {
                        $eaelSaturateHoverEffect = {'saturate': eaelEditModeSettings?.[key]?.['eael_hover_effect_saturate_hover']?.['size']};
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_sepia_is_on'] === 'yes' ) {
                        $eaelSepiaHoverEffect = {'sepia': eaelEditModeSettings?.[key]?.['eael_hover_effect_sepia_hover']?.['size']};
                    }
                }

                //Offset
                if( 'yes' === eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_popover'] ) {
                    $eaelOffsetTop = {
                        'size': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_top']?.['size'],
                        'unit': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_top']?.['unit']
                    };
                    $eaelOffsetLeft = {
                        'size': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_left']?.['size'],
                        'unit': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_left']?.['unit']
                    };
                }

                //Offset Hover
                if( 'yes' === eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_hover_popover'] ) {
                    $eaelOffsetHoverTop = {
                        'size': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_hover_top']?.['size'],
                        'unit': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_hover_top']?.['unit']
                    };
                    $eaelOffsetHoverLeft = {
                        'size': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_hover_left']?.['size'],
                        'unit': eaelEditModeSettings?.[key]?.['eael_hover_effect_offset_hover_left']?.['unit']
                    };
                }

                //Tranform
                if( 'yes' === eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_popover'] ) {
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_rotate_is_on'] === 'yes' ) {
                        $eaelRotateEffect = {
                            'rotate_x': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_rotatex']?.['size'],
                            'rotate_y': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_rotatey']?.['size'],
                            'rotate_z': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_rotatez']?.['size']
                        };
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_scale_is_on'] === 'yes' ) {
                        $eaelScaleEffect = {
                            'scale_x': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_scalex']?.['size'],
                            'scale_y': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_scaley']?.['size']
                        };
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_skew_is_on'] === 'yes' ) {
                        $eaelSkewEffect = {
                            'skew_x': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_skewx']?.['size'],
                            'skew_y': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_skewy']?.['size']
                        };
                    }
                }

                //Tranform Hover
                if( 'yes' === eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_popover'] ) {
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_rotate_hover_is_on'] === 'yes' ) {
                        $eaelRotateHoverEffect = {
                            'rotate_x': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_rotatex']?.['size'],
                            'rotate_y': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_rotatey']?.['size'],
                            'rotate_z': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_rotatez']?.['size']
                        };
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_scale_hover_is_on'] === 'yes' ) {
                        $eaelScaleHoverEffect = {
                            'scale_x': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_scalex']?.['size'],
                            'scale_y': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_scaley']?.['size']
                        };
                    }
                    if( eaelEditModeSettings?.[key]?.['eael_hover_effect_skew_hover_is_on'] === 'yes' ) {
                        $eaelSkewHoverEffect = {
                            'skew_x': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_skewx']?.['size'],
                            'skew_y': eaelEditModeSettings?.[key]?.['eael_hover_effect_transform_hover_skewy']?.['size']
                        };
                    }
                }
                
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
        "z-index": 1
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
        "z-index": 2
    };

    if ($enabledElementList.includes($scopeId)) {
        $(hoverSelector).hover(
            function () {
                $(this).css(hoverStyles);
            },
            function () {
                $(this).css(normalStyles);
            }
        );

        $eaelContainer.css(normalStyles);
    }

    //Tilt Effect
    if( $eaelTilt === 'eael_tilt' ) {
        $(`.elementor-element-${$scopeId}`).mousemove( function( e ) {
            var cox = ( e.pageX - $(this).offset().left - $(this).width() / 2 ) / 20;
            var coy = ( $(this).height() / 2 - ( e.pageY - $(this).offset().top ) ) / 20;
            $(this).find( '.elementor-widget-container' ).css( 'transform','perspective(500px) rotateY('+cox+'deg) rotateX('+coy+'deg)' );
        });
    
        $(`.elementor-element-${$scopeId}`).mouseleave(function( e ) {
            $(this).find('.elementor-widget-container').css( 'transform','rotateY(0) rotateX(0)' );
        });
    }

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
