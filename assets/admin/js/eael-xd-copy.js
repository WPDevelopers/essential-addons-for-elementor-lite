"use strict";window.XdUtils=window.XdUtils||function(){function a(a,b){var c,d=b||{};for(c in a)a.hasOwnProperty(c)&&(d[c]=a[c]);return d}return{extend:a}}(),window.xdLocalStorage=window.xdLocalStorage||function(){function a(a){k[a.id]&&(k[a.id](a),delete k[a.id])}function b(b){var c;try{c=JSON.parse(b.data)}catch(a){}c&&c.namespace===h&&("iframe-ready"===c.id?(m=!0,i.initCallback()):a(c))}function c(a,b,c,d){j++,k[j]=d;var e={namespace:h,id:j,action:a,key:b,value:c};g.contentWindow.postMessage(JSON.stringify(e),"*")}function d(a){i=XdUtils.extend(a,i);var c=document.createElement("div");window.addEventListener?window.addEventListener("message",b,!1):window.attachEvent("onmessage",b),c.innerHTML='<iframe id="'+i.iframeId+'" src='+i.iframeUrl+' style="display: none;"></iframe>',document.body.appendChild(c),g=document.getElementById(i.iframeId)}function e(){return l?!!m||(console.log("You must wait for iframe ready message before using the api."),!1):(console.log("You must call xdLocalStorage.init() before using it."),!1)}function f(){return"complete"===document.readyState}var g,h="eael-xd-copy-message",i={iframeId:"cross-domain-iframe",iframeUrl:void 0,initCallback:function(){}},j=-1,k={},l=!1,m=!0;return{init:function(a){if(!a.iframeUrl)throw"You must specify iframeUrl";if(l)return void console.log("xdLocalStorage was already initialized!");l=!0,f()?d(a):document.addEventListener?document.addEventListener("readystatechange",function(){f()&&d(a)}):document.attachEvent("readystatechange",function(){f()&&d(a)})},setItem:function(a,b,d){e()&&c("set",a,b,d)},getItem:function(a,b){e()&&c("get",a,null,b)},removeItem:function(a,b){e()&&c("remove",a,null,b)},key:function(a,b){e()&&c("key",a,null,b)},getSize:function(a){e()&&c("size",null,null,a)},getLength:function(a){e()&&c("length",null,null,a)},clear:function(a){e()&&c("clear",null,null,a)},wasInit:function(){return l}}}();

(function () {
    function a(b) {
        return b.forEach(function (b) {
            b.id = elementorCommon.helpers.getUniqueId(), 0 < b.elements.length && a(b.elements)
        }), b
    }

    function eaPasteHandler(b, c) {
        var d = c,
            e = c.model.get("elType"),
            f = b.elementcode.elType,
            g = b.elementcode,
            h = JSON.stringify(g);

        var i = /\.(jpg|png|jpeg|gif|svg|webp|psd|bmp)/gi.test(h),
            j = {
                elType: f,
                settings: g.settings
            },
            k = null,
            l = {
                index: 0
            };
        switch (f) {
            case "section":
                j.elements = a(g.elements), k = elementor.getPreviewContainer();
                break;
            case "column":
                j.elements = a(g.elements);
                "section" === e ? k = d.getContainer() :
                    "column" === e ? (k = d.getContainer().parent, l.index = d.getOption("_index") + 1) :
                        "widget" === e ? (k = d.getContainer().parent.parent, l.index = d.getContainer().parent.view.getOption("_index") + 1) : void 0;
                break;
            case "widget":
                j.widgetType = b.elementtype, k = d.getContainer();
                "section" === e ? k = d.children.findByIndex(0).getContainer() :
                    "column" === e ? k = d.getContainer() :
                        "widget" === e ? (k = d.getContainer().parent, l.index = d.getOption("_index") + 1) : void 0;
        }
        var m = $e.run("document/elements/create", {
            model: j,
            container: k,
            options: l
        });
        i && jQuery.ajax({
            url: eael_xd_copy.ajax_url,
            method: "POST",
            data: {
                nonce: eael_xd_copy.nonce,
                action: "eael_xd_copy_fetch_content",
                xd_copy_data: h
            }
        }).done(function (a) {
            if (a.success) {
                var b = a.data[0];
                j.elType = b.elType, j.settings = b.settings, "widget" === j.elType ? j.widgetType = b.widgetType : j.elements = b.elements, $e.run("document/elements/delete", {
                    container: m
                }), $e.run("document/elements/create", {
                    model: j,
                    container: k,
                    options: l
                })
            }
        })
    }

    xdLocalStorage.init({
        iframeUrl: "https://w3techies.net/xdcp/",
        initCallback: function () {
            console.log("eael xd copy iframe ready");
        }
    });

    var XdCopyType = ["section", "column", "widget"];

    XdCopyType.forEach(function (XdType, index) {
        elementor.hooks.addFilter("elements/" + XdType + "/contextMenuGroups", function (groups, element) {
            groups.splice(1, 0, {
                name: "eael_" + XdType,
                actions: [
                    {
                        name: "ea_copy",
                        title: eael_xd_copy.xd_copy,
                        icon: 'eicon-copy',
                        shortcut: '<i class="eaicon-badge"></i>',
                        callback: function () {
                            var copiedElement = {};
                            copiedElement.elementtype = XdType === "widget" ? element.model.get("widgetType") : null;
                            copiedElement.elementcode = element.model.toJSON();

                            xdLocalStorage.setItem("eael-xd-copy-data", JSON.stringify(copiedElement), function (data) {
                                elementor.notifications.showToast({
                                    message: elementor.translate(XdType.toUpperCase() + " Copied! 📰")
                                });
                            });
                        }
                    },
                    {
                        name: "ea_paste",
                        title: eael_xd_copy.xd_paste,
                        icon: 'eicon-import-kit',
                        shortcut: '<i class="eaicon-badge"></i>',
                        callback: function () {
                            xdLocalStorage.getItem("eael-xd-copy-data", function (newElement) {
                                eaPasteHandler(JSON.parse(newElement.value), element);

                                elementor.notifications.showToast({
                                    message: elementor.translate("EA Copy Data Pasted! ✔️")
                                });
                            });
                        }
                    },
                ]
            });

            return groups;
        });
    });

})(jQuery);