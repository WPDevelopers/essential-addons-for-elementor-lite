/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/quick-view.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/quick-view.js":
/*!***********************************!*\
  !*** ./src/js/view/quick-view.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _typeof(o) { \"@babel/helpers - typeof\"; return _typeof = \"function\" == typeof Symbol && \"symbol\" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && \"function\" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? \"symbol\" : typeof o; }, _typeof(o); }\nfunction ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }\nfunction _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }\nfunction _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }\nfunction _toPropertyKey(t) { var i = _toPrimitive(t, \"string\"); return \"symbol\" == _typeof(i) ? i : String(i); }\nfunction _toPrimitive(t, r) { if (\"object\" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || \"default\"); if (\"object\" != _typeof(i)) return i; throw new TypeError(\"@@toPrimitive must return a primitive value.\"); } return (\"string\" === r ? String : Number)(t); }\nvar QuickView = {\n  quickViewAddMarkup: function quickViewAddMarkup($scope, jq) {\n    var popupMarkup = \"<div style=\\\"display: none\\\" class=\\\"eael-woocommerce-popup-view eael-product-popup eael-product-zoom-in woocommerce\\\">\\n                    \\t\\t\\t<div class=\\\"eael-product-modal-bg\\\"></div>\\n                    \\t\\t\\t<div class=\\\"eael-popup-details-render eael-woo-slider-popup\\\"><div class=\\\"eael-preloader\\\"></div></div>\\n               \\t\\t\\t\\t </div>\";\n    if (!jq('body > .eael-woocommerce-popup-view').length) {\n      jq('body').prepend(popupMarkup);\n    }\n  },\n  openPopup: function openPopup($scope, $) {\n    // Quick view\n    $scope.on(\"click\", \".open-popup-link\", function (e) {\n      e.preventDefault();\n      e.stopPropagation();\n      var $this = $(this);\n      var quickview_setting = $this.data('quickview-setting');\n      var popup_view = $(\".eael-woocommerce-popup-view\");\n      popup_view.find(\".eael-popup-details-render\").html('<div class=\"eael-preloader\"></div>');\n      popup_view.addClass(\"eael-product-popup-ready\").removeClass(\"eael-product-modal-removing\");\n      popup_view.show();\n      $.ajax({\n        url: localize.ajaxurl,\n        type: \"post\",\n        data: _objectSpread(_objectSpread({\n          action: \"eael_product_quickview_popup\"\n        }, quickview_setting), {}, {\n          security: localize.nonce\n        }),\n        success: function success(response) {\n          if (response.success) {\n            var product_preview = $(response.data);\n            var popup_details = product_preview.children(\".eael-product-popup-details\");\n            popup_details.find(\".variations_form\").wc_variation_form();\n            var popup_view_render = popup_view.find(\".eael-popup-details-render\");\n            popup_view.find(\".eael-popup-details-render\").html(popup_details);\n            var product_gallery = popup_view.find(\".woocommerce-product-gallery\");\n            popup_view_render.addClass(\"elementor-\" + quickview_setting.page_id);\n            popup_view_render.children().addClass(\"elementor-element elementor-element-\" + quickview_setting.widget_id);\n            if (popup_details.height() > 400) {\n              popup_details.css(\"height\", \"75vh\");\n            } else {\n              popup_details.css(\"height\", \"auto\");\n            }\n            setTimeout(function () {\n              var setHeight = product_gallery.find('.woocommerce-product-gallery__image').height();\n              $('body').prepend('<style class=\"eael-quick-view-dynamic-css\">.woocommerce-product-gallery .flex-viewport { height: ' + setHeight + 'px; }</style>');\n              product_gallery.wc_product_gallery();\n              product_gallery.closest('.eael-product-image-wrap').css('background', 'none');\n            }, 500);\n            setTimeout(function () {\n              $('.eael-quick-view-dynamic-css').remove();\n            }, 1500);\n          }\n        }\n      });\n    });\n  },\n  closePopup: function closePopup($scope, jq) {\n    jq(document).on(\"click\", \".eael-product-popup-close\", function (event) {\n      event.stopPropagation();\n      QuickView.remove_product_popup(jq);\n    });\n    jq(document).on(\"click\", function (event) {\n      if (event.target.closest(\".eael-product-popup-details\")) return;\n      QuickView.remove_product_popup(jq);\n    });\n  },\n  singlePageAddToCartButton: function singlePageAddToCartButton($scope, $) {\n    $(document).on(\"click\", \".eael-woo-slider-popup .single_add_to_cart_button:not(.wc-variation-selection-needed)\", function (e) {\n      e.preventDefault();\n      e.stopImmediatePropagation();\n      var $this = $(this),\n        product_id = $(this).val(),\n        variation_id = $this.closest(\"form.cart\").find('input[name=\"variation_id\"]').val() || \"\",\n        quantity = $this.closest(\"form.cart\").find('input[name=\"quantity\"]').val(),\n        items = $this.closest(\"form.cart.grouped_form\"),\n        form = $this.closest(\"form.cart\"),\n        product_data = [];\n      items = items.serializeArray();\n      if (form.hasClass(\"variations_form\")) {\n        product_id = form.find('input[name=\"product_id\"]').val();\n      }\n      if (items.length > 0) {\n        items.forEach(function (item, index) {\n          var p_id = parseInt(item.name.replace(/[^\\d.]/g, \"\"), 10);\n          if (item.name.indexOf(\"quantity[\") >= 0 && item.value != \"\" && p_id > 0) {\n            product_data[product_data.length] = {\n              product_id: p_id,\n              quantity: item.value,\n              variation_id: 0\n            };\n          }\n        });\n      } else {\n        product_data[0] = {\n          product_id: product_id,\n          quantity: quantity,\n          variation_id: variation_id\n        };\n      }\n      $this.removeClass(\"eael-addtocart-added\");\n      $this.addClass(\"eael-addtocart-loading\");\n      $.ajax({\n        url: localize.ajaxurl,\n        type: \"post\",\n        data: {\n          action: \"eael_product_add_to_cart\",\n          product_data: product_data,\n          eael_add_to_cart_nonce: localize.nonce,\n          cart_item_data: form.serializeArray()\n        },\n        success: function success(response) {\n          if (response.success) {\n            $(document.body).trigger(\"wc_fragment_refresh\");\n            $this.removeClass(\"eael-addtocart-loading\");\n            $this.addClass(\"eael-addtocart-added\");\n            if (localize.cart_redirectition == 'yes') {\n              window.location.href = localize.cart_page_url;\n            }\n          }\n        }\n      });\n    });\n  },\n  preventStringInNumberField: function preventStringInNumberField($scope, $) {\n    $(document).on(\"keypress\", \".eael-product-details-wrap input[type=number]\", function (e) {\n      var keyValue = e.keyCode || e.which;\n      var regex = /^[0-9]+$/;\n      var isValid = regex.test(String.fromCharCode(keyValue));\n      if (!isValid) {\n        return false;\n      }\n      return isValid;\n    });\n  },\n  remove_product_popup: function remove_product_popup(jq) {\n    var selector = jq(\".eael-product-popup.eael-product-zoom-in.eael-product-popup-ready\");\n    selector.addClass(\"eael-product-modal-removing\").removeClass(\"eael-product-popup-ready\");\n    selector.find('.eael-popup-details-render').html('');\n  }\n};\nif (!ea.elementStatusCheck('eaelQuickView')) {\n  ea.hooks.addAction('quickViewAddMarkup', 'ea', QuickView.quickViewAddMarkup, 10);\n  ea.hooks.addAction('quickViewPopupViewInit', 'ea', QuickView.openPopup, 10);\n  ea.hooks.addAction('quickViewPopupViewInit', 'ea', QuickView.closePopup, 10);\n  ea.hooks.addAction('quickViewPopupViewInit', 'ea', QuickView.singlePageAddToCartButton, 10);\n  ea.hooks.addAction('quickViewPopupViewInit', 'ea', QuickView.preventStringInNumberField, 10);\n}\n\n//# sourceURL=webpack:///./src/js/view/quick-view.js?");

/***/ })

/******/ });