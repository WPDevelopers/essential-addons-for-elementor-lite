(function ($) {
    $("document").ready(function () {
        let templateAddSection = $("#tmpl-elementor-add-section");
        if (0 < templateAddSection.length) {
            var oldTemplateButton = templateAddSection.html();
            oldTemplateButton = oldTemplateButton.replace(
                '<div class="elementor-add-section-drag-title',
                '<div class="elementor-add-section-area-button elementor-add-templately-promo-button"></div><div class="elementor-add-section-drag-title'
            );
            templateAddSection.html(oldTemplateButton);
        }

        elementor.on("preview:loaded", function () {
            $(elementor.$previewContents[0].body).on("click", ".elementor-add-templately-promo-button", function (event) {
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
                            cloneMarkup = cloneMarkup.clone(true).show()
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

        $(document).on('change', '.eael-temp-promo-confirmation', function (e) {
            var $this = $(this)
            if ($this.val() == 'dnd') {
                $(".wpdeveloper-plugin-installer").hide();
                $(".eael-prmo-status-submit").show();
            } else {
                $(".wpdeveloper-plugin-installer").show();
                $(".eael-prmo-status-submit").hide();
            }
        });

        $(document).on('click','.eael-prmo-status-submit',function (e){
            e.preventDefault();
            var $this = $(this);
            $this.prop("disabled",true);
            $(".eael-temp-promo-confirmation").prop("disabled", true);

            $.ajax({
                url: ajaxurl,
                type: "POST",
                data: {
                    action: "templately_promo_status",
                    security: localize.nonce,
                },
                success: function (response) {
                    if (response.success) {
                        $(elementor.$previewContents[0].body).find(".elementor-add-templately-promo-button").remove();
                        window.tmPromo.destroy();
                    }
                },
                error: function (err) {
                    $this.prop("disabled",false);
                    console.log(err)
                },
            });
        })

        $(document).on('click','.eael-promo-temp__times',function (e){
            e.preventDefault();
            window.tmPromo.destroy();
        })

        // install/activate plugin
        $(document).on("click", ".wpdeveloper-plugin-installer", function (ev) {
            ev.preventDefault();

            var button = $(this);
            var action = $(this).data("action");
            var slug = $(this).data("slug");
            var basename = $(this).data("basename");

            if ($.active && typeof action != "undefined") {
                button.text("Waiting...").attr("disabled", true);

                setInterval(function () {
                    if (!$.active) {
                        button.attr("disabled", false).trigger("click");
                    }
                }, 1000);
            }

            if (action == "install" && !$.active) {
                button.text("Installing...").attr("disabled", true);

                $.ajax({
                    url: localize.ajaxurl,
                    type: "POST",
                    data: {
                        action: "wpdeveloper_install_plugin",
                        security: localize.nonce,
                        slug: slug,
                    },
                    success: function (response) {
                        if (response.success) {
                            button.text("Activated");
                            button.data("action", null);
                            elementor.saver.update.apply().then(function () {
                                location.reload();
                            });
                        } else {
                            button.text("Install");
                        }

                        button.attr("disabled", false);
                    },
                    error: function (err) {
                        console.log(err.responseJSON);
                    },
                });
            } else if (action == "activate" && !$.active) {
                button.text("Activating...").attr("disabled", true);

                $.ajax({
                    url: localize.ajaxurl,
                    type: "POST",
                    data: {
                        action: "wpdeveloper_activate_plugin",
                        security: localize.nonce,
                        basename: basename,
                    },
                    success: function (response) {
                        if (response.success) {
                            button.text("Activated");
                            button.data("action", null);
                            elementor.saver.update.apply().then(function () {
                                location.reload();
                            });
                        } else {
                            button.text("Activate");
                        }

                        button.attr("disabled", false);
                    },
                    error: function (err) {
                        console.log(err.responseJSON);
                    },
                });
            }
        });
    });
})(jQuery);
