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
                var a = elementorCommon.dialogsManager.createWidget(
                    "lightbox",
                    {
                        id: "templately-app-modal",
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
                            console.log("onShow")
                        },
                        onHide: function () {
                            console.log("onHide")
                        },
                        onInit:function (){
                            console.log("onInit")
                        },
                        onReady:function (){
                            console.log("onReady")
                        }
                    }
                );
                a.show();
            });
        });
    });
})(jQuery);
