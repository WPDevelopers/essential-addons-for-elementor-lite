let ImageMaskingHandler = function ($scope, $) {

    function get_clip_path(shape) {
        let shapes = {
            'bavel': 'polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%)',
            'rabbet': 'polygon(0% 15%, 15% 15%, 15% 0%, 85% 0%, 85% 15%, 100% 15%, 100% 85%, 85% 85%, 85% 100%, 15% 100%, 15% 85%, 0% 85%)',
            'chevron-left': 'polygon(100% 0%, 75% 50%, 100% 100%, 25% 100%, 0% 50%, 25% 0%)',
            'chevron-right': 'polygon(75% 0%, 100% 50%, 75% 100%, 0% 100%, 25% 50%, 0% 0%)',
            'star': 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)',
        };
        return shapes[shape] || '';
    }

    function renderImageMasking(model) {
        let settings = model?.attributes?.settings?.attributes;
        let elementId = model?.attributes?.id, element = $(`.elementor-element-${elementId}`);
        let styleId = 'eael-image-masking-' + elementId;
        $scope = element;

        // Remove existing style if present
        $('#' + styleId).remove();

        if ('yes' === settings?.eael_enable_image_masking) {
            let style = '';
            if ('clip' === settings?.eael_image_masking_type) {
                let clipPath = '';
                if ('yes' === settings?.eael_image_masking_enable_custom_clip_path) {
                    clipPath = settings?.eael_image_masking_custom_clip_path;
                    clipPath = clipPath.replace('clip-path: ', '');
                } else {
                    clipPath = get_clip_path(settings?.eael_image_masking_clip_path)
                }
                if (clipPath) {
                    style += '.elementor-element-' + elementId + ' img {clip-path: ' + clipPath + ';}';
                }

                if ('yes' === settings?.eael_image_masking_hover_effect) {
                    let hoverClipPath = '';
                    if ('yes' === settings?.eael_image_masking_enable_custom_clip_path_hover) {
                        hoverClipPath = settings?.eael_image_masking_custom_clip_path_hover;
                        hoverClipPath = hoverClipPath.replace('clip-path: ', '');
                    } else {
                        hoverClipPath = get_clip_path(settings?.eael_image_masking_clip_path_hover)
                    }
                    if (hoverClipPath) {
                        let hoverSelector = settings?.eael_image_masking_hover_selector;
                        if (hoverSelector) {
                            hoverSelector = ' ' + hoverSelector.trim();
                        }
                        style += '.elementor-element-' + elementId + hoverSelector + ':hover img {clip-path: ' + hoverClipPath + ';}';
                    }
                }
            } else if ('image' === settings?.eael_image_masking_type) {
                let image = settings?.eael_image_masking_svg;
                let mask_url = '';
                if ('upload' !== image) {
                    mask_url = EAELImageMaskingConfig?.svg_dir_url + image + '.svg';
                } else if ('upload' === image) {
                    let image = settings?.eael_image_masking_image;
                    mask_url = image?.url;
                }
                if (mask_url) {
                    style += '.elementor-element-' + elementId + ' img {mask-image: url(' + mask_url + '); -webkit-mask-image: url(' + mask_url + ');}';
                }

                if ('yes' === settings?.eael_image_masking_hover_effect) {
                    let hover_mask_url = '';
                    let hoverImage = settings?.eael_image_masking_svg_hover;
                    if ('upload' !== hoverImage) {
                        hover_mask_url = EAELImageMaskingConfig?.svg_dir_url + hoverImage + '.svg';
                    } else if ('upload' === hoverImage) {
                        hover_mask_url = settings?.eael_image_masking_image_hover?.url;
                    }

                    if (hover_mask_url) {
                        let hover_selector = settings?.eael_image_masking_hover_selector;
                        if (hover_selector) {
                            hover_selector = ' ' + hover_selector.trim();
                        }
                        style += '.elementor-element-' + elementId + hover_selector + ':hover img {mask-image: url(' + hover_mask_url + '); -webkit-mask-image: url(' + hover_mask_url + ');}';
                    }
                }
            }

            if (style) {
                element.append('<style id="' + styleId + '">' + style + '</style>');
            }
        }
    }

    function getImageMaskingSettingsVal(models) {
        $.each(models, function (_, model) {
            // Only process if image masking is enabled for this element
            let settings = model?.attributes?.settings?.attributes;
            if (settings && 'yes' === settings?.eael_enable_image_masking) {
                renderImageMasking(model);
            }

            if (model.attributes.elType !== 'widget') {
                getImageMaskingSettingsVal(model.attributes.elements.models);
            }
        });
    }

    if (window.elementor?.elements?.models) {
        getImageMaskingSettingsVal(window.elementor?.elements?.models);
    }
}

// TODO: Remove this function after several versions once existing sites have re-saved and eael_svg_path is no longer present in saved data.
function cleanLegacySvgPath(elements) {
    if (!elements) return;
    elements.forEach(function (element) {
        var custom = element?.settings?.eael_svg_paths_custom;
        if (Array.isArray(custom)) {
            custom.forEach(function (item) {
                delete item.eael_svg_path;
            });
        }
        cleanLegacySvgPath(element.elements);
    });
}

jQuery(window).on("elementor/frontend/init", function () {
    if (eael.elementStatusCheck('eaelImageMaskingEditor')) {
        return false;
    }
    elementorFrontend.hooks.addAction("frontend/element_ready/widget", ImageMaskingHandler);

    elementor.hooks.addFilter('elementor/documents/save/data', function (data) {
        cleanLegacySvgPath(data?.elements);
        return data;
    });
});