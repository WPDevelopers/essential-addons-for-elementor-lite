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

        elementor.on("preview:loaded", function () {
            $(elementor.$previewContents[0].body).on("click",".elementor-add-templately-promo-button", function (event){
                 window.tmPromo = elementorCommon.dialogsManager.createWidget(
                    "lightbox",
                    {
                        id: "eael-templately-promo-popup",
                        headerMessage: !1,
                        message: "",
                        hide: {
                            auto: !1,
                            onClick: !1,
                            onOutsideClick: false,
                            onOutsideContextMenu: !1,
                            onBackgroundClick: !0,
                        },
                        position: {
                            my: "center",
                            at: "center",
                        },
                        onShow: function () {
                            var contentTemp = $(".dialog-content-tempromo")
                            var cloneMarkup = $("#eael-promo-temp-wrap")
                            cloneMarkup = cloneMarkup.clone( true )
                            contentTemp.html(cloneMarkup);
                        },
                        onHide: function () {
                            window.tmPromo.destroy();
                        }
                    }
                );
                window.tmPromo.getElements("header").remove();
                window.tmPromo.getElements("message").append(
                    window.tmPromo.addElement("content-tempromo")
                );
                window.tmPromo.show();
            });
        });
    });
})(jQuery);
