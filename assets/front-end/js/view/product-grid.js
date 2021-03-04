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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/product-grid.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/product-grid.js":
/*!*************************************!*\
  !*** ./src/js/view/product-grid.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("ea.hooks.addAction(\"init\", \"ea\", function () {\n  var productGrid = function productGrid($scope, $) {\n    var $wrap = $scope.find(\"#eael-product-grid\"); // cache wrapper\n\n    var widgetId = $wrap.data(\"widget-id\");\n    var pageId = $wrap.data(\"page-id\");\n    var nonce = $wrap.data(\"nonce\");\n    var overlay = document.createElement(\"div\");\n    overlay.classList.add(\"wcpc-overlay\");\n    overlay.setAttribute(\"id\", \"wcpc-overlay\");\n    var body = document.getElementsByTagName(\"body\")[0];\n    body.appendChild(overlay);\n    var overlayNode = document.getElementById(\"wcpc-overlay\");\n    var $doc = $(document);\n    var loader = false;\n    var compareBtn = false;\n    var hasCompareIcon = false;\n    var compareBtnSpan = false;\n    var requestType = false; // compare | remove\n\n    var iconBeforeCompare = '<i class=\"fas fa-exchange-alt\"></i>';\n    var iconAfterCompare = '<i class=\"fas fa-check-circle\"></i>';\n    var modalTemplate = \"\\n        <div class=\\\"eael-wcpc-modal\\\">\\n            <i title=\\\"Close\\\" class=\\\"close-modal far fa-times-circle\\\"></i>\\n            <div class=\\\"modal__content\\\" id=\\\"eael_modal_content\\\">\\n            </div>\\n        </div>\\n        \";\n    $(body).append(modalTemplate);\n    var $modalContentWraper = $(\"#eael_modal_content\");\n    var modal = document.getElementsByClassName(\"eael-wcpc-modal\")[0];\n    var ajaxData = [{\n      name: \"action\",\n      value: \"eael_product_grid\"\n    }, {\n      name: \"widget_id\",\n      value: widgetId\n    }, {\n      name: \"page_id\",\n      value: pageId\n    }, {\n      name: \"nonce\",\n      value: nonce\n    }];\n\n    var sendData = function sendData(ajaxData, successCb, errorCb, beforeCb, completeCb) {\n      $.ajax({\n        url: localize.ajaxurl,\n        type: \"POST\",\n        dataType: \"json\",\n        data: ajaxData,\n        beforeSend: beforeCb,\n        success: successCb,\n        error: errorCb,\n        complete: completeCb\n      });\n    };\n\n    $doc.on(\"click\", \".eael-wc-compare\", function (e) {\n      e.preventDefault();\n      e.stopImmediatePropagation();\n      requestType = \"compare\";\n      compareBtn = $(this);\n      compareBtnSpan = compareBtn.find(\".eael-wc-compare-text\");\n\n      if (!compareBtnSpan.length) {\n        hasCompareIcon = compareBtn.hasClass(\"eael-wc-compare-icon\");\n      }\n\n      if (!hasCompareIcon) {\n        loader = compareBtn.find(\".eael-wc-compare-loader\");\n        loader.show();\n      }\n\n      var product_id = compareBtn.data(\"product-id\");\n      var oldProductIds = localStorage.getItem('productIds');\n\n      if (oldProductIds) {\n        oldProductIds = JSON.parse(oldProductIds);\n        oldProductIds.push(product_id);\n      } else {\n        oldProductIds = [product_id];\n      }\n\n      ajaxData.push({\n        name: \"product_id\",\n        value: compareBtn.data(\"product-id\")\n      });\n      ajaxData.push({\n        name: \"product_ids\",\n        value: JSON.stringify(oldProductIds)\n      });\n      sendData(ajaxData, handleSuccess, handleError);\n    });\n    $doc.on(\"click\", \".close-modal\", function (e) {\n      modal.style.visibility = \"hidden\";\n      modal.style.opacity = \"0\";\n      overlayNode.style.visibility = \"hidden\";\n      overlayNode.style.opacity = \"0\";\n    });\n    $doc.on(\"click\", \".eael-wc-remove\", function (e) {\n      e.preventDefault();\n      e.stopImmediatePropagation();\n      var $rBtn = $(this);\n      var productId = $rBtn.data(\"product-id\");\n      $rBtn.addClass(\"disable\");\n      $rBtn.prop(\"disabled\", true); // prevent additional ajax request\n\n      var oldProductIds = localStorage.getItem('productIds');\n\n      if (oldProductIds) {\n        oldProductIds = JSON.parse(oldProductIds);\n        oldProductIds.push(productId);\n      } else {\n        oldProductIds = [productId];\n      }\n\n      var rmData = Array.from(ajaxData);\n      rmData.push({\n        name: \"product_id\",\n        value: productId\n      });\n      rmData.push({\n        name: \"remove_product\",\n        value: 1\n      });\n      rmData.push({\n        name: \"product_ids\",\n        value: JSON.stringify(oldProductIds)\n      });\n      requestType = \"remove\";\n      var compareBtn = $('button[data-product-id=\"' + productId + '\"]');\n      compareBtnSpan = compareBtn.find(\".eael-wc-compare-text\");\n\n      if (!compareBtnSpan.length) {\n        hasCompareIcon = compareBtn.hasClass(\"eael-wc-compare-icon\");\n      }\n\n      sendData(rmData, handleSuccess, handleError);\n    });\n\n    function handleSuccess(data) {\n      var success = data && data.success;\n\n      if (success) {\n        $modalContentWraper.html(data.data.compare_table);\n        modal.style.visibility = \"visible\";\n        modal.style.opacity = \"1\";\n        overlayNode.style.visibility = \"visible\";\n        overlayNode.style.opacity = \"1\";\n        localStorage.setItem('productIds', JSON.stringify(data.data.product_ids));\n      }\n\n      if (loader) {\n        loader.hide();\n      }\n\n      if (\"compare\" === requestType) {\n        if (compareBtnSpan && compareBtnSpan.length) {\n          compareBtnSpan.text(localize.i18n.added);\n        } else if (hasCompareIcon) {\n          compareBtn.html(iconAfterCompare);\n        }\n      }\n\n      if (\"remove\" === requestType) {\n        if (compareBtnSpan && compareBtnSpan.length) {\n          compareBtnSpan.text(localize.i18n.compare);\n        } else if (hasCompareIcon) {\n          compareBtn.html(iconBeforeCompare);\n        }\n      }\n    }\n\n    function handleError(xhr, err) {\n      console.log(err.toString());\n    } // pagination\n\n\n    $(\".eael-woo-pagination\", $scope).on(\"click\", \"a\", function (e) {\n      e.preventDefault();\n      var $this = $(this),\n          nth = $this.data(\"pnumber\"),\n          lmt = $this.data(\"plimit\"),\n          ajax_url = localize.ajaxurl,\n          args = $this.data(\"args\"),\n          settings = $this.data(\"settings\"),\n          widgetid = $this.data(\"widgetid\"),\n          widgetclass = \".elementor-element-\" + widgetid,\n          template_info = $this.data(\"template\");\n      $.ajax({\n        url: ajax_url,\n        type: \"post\",\n        data: {\n          action: \"woo_product_pagination_product\",\n          number: nth,\n          limit: lmt,\n          args: args,\n          templateInfo: template_info,\n          settings: settings\n        },\n        beforeSend: function beforeSend() {\n          $(widgetclass).addClass(\"eael-product-loader\");\n        },\n        success: function success(response) {\n          $(widgetclass + \" .eael-product-grid .products\").html(response);\n          $(widgetclass + \" .woocommerce-product-gallery\").each(function () {\n            $(this).wc_product_gallery();\n          });\n        },\n        complete: function complete() {\n          $(widgetclass).removeClass(\"eael-product-loader\");\n        }\n      });\n      $.ajax({\n        url: ajax_url,\n        type: \"post\",\n        data: {\n          action: \"woo_product_pagination\",\n          number: nth,\n          limit: lmt,\n          args: args,\n          settings: settings\n        },\n        // beforeSend\t: function(){\n        // \t$(widgetclass+\" .eael-product-grid .products\").html(\"<li style='text-align:center;'>Loading please \" +\n        // \t\t\"wait...!</li>\");\n        // },\n        success: function success(response) {\n          $(widgetclass + \" .eael-product-grid .eael-woo-pagination\").html(response);\n        }\n      });\n    });\n    $(document).on(\"click\", \".open-popup-link\", function (e) {\n      e.preventDefault();\n      e.stopPropagation();\n      var $this = $(this);\n      var id = $this.attr(\"href\");\n      var popup = $(id);\n      var popup_details = popup.children(\".eael-product-popup-details\");\n\n      if (popup_details.height() > 400) {\n        popup_details.css(\"height\", \"75vh\");\n      } else {\n        popup_details.css(\"height\", \"auto\");\n      }\n\n      $(id + \" .variations_form\").wc_variation_form();\n      popup.addClass(\"eael-product-popup-ready\").removeClass(\"eael-product-modal-removing\");\n    });\n    $(document).on(\"keypress\", \".eael-product-details-wrap input[type=number]\", function (e) {\n      var keyValue = e.keyCode || e.which;\n      var regex = /^[0-9]+$/;\n      var isValid = regex.test(String.fromCharCode(keyValue));\n\n      if (!isValid) {\n        return false;\n      }\n\n      return isValid;\n    }); // handle add to cart for quick view\n\n    $scope.on(\"click\", \".eael-product-popup-details .single_add_to_cart_button\", function (e) {\n      e.preventDefault();\n      var $this = $(this),\n          product_id = $(this).val(),\n          variation_id = $this.closest(\"form.cart\").find('input[name=\"variation_id\"]').val() || \"\",\n          quantity = $this.closest(\"form.cart\").find('input[name=\"quantity\"]').val(),\n          items = $this.closest(\"form.cart.grouped_form\"),\n          form = $this.closest(\"form.cart\"),\n          product_data = [];\n      items = items.serializeArray();\n\n      if (form.hasClass(\"variations_form\")) {\n        product_id = form.find('input[name=\"product_id\"]').val();\n      }\n\n      if (items.length > 0) {\n        items.forEach(function (item, index) {\n          var p_id = parseInt(item.name.replace(/[^\\d.]/g, \"\"), 10);\n\n          if (item.name.indexOf(\"quantity[\") >= 0 && item.value != \"\" && p_id > 0) {\n            product_data[product_data.length] = {\n              product_id: p_id,\n              quantity: item.value,\n              variation_id: 0\n            };\n          }\n        });\n      } else {\n        product_data[0] = {\n          product_id: product_id,\n          quantity: quantity,\n          variation_id: variation_id\n        };\n      }\n\n      $this.removeClass(\"eael-addtocart-added\");\n      $this.addClass(\"eael-addtocart-loading\");\n      $.ajax({\n        url: localize.ajaxurl,\n        type: \"post\",\n        data: {\n          action: \"eael_product_add_to_cart\",\n          product_data: product_data,\n          eael_add_to_cart_nonce: localize.nonce\n        },\n        success: function success(response) {\n          if (response.success) {\n            $(document.body).trigger(\"wc_fragment_refresh\");\n            $this.removeClass(\"eael-addtocart-loading\");\n            $this.addClass(\"eael-addtocart-added\");\n          }\n        }\n      });\n    });\n    $(document).on(\"click\", \".eael-product-popup-close\", function (event) {\n      event.stopPropagation();\n      $(\".eael-product-popup\").addClass(\"eael-product-modal-removing\").removeClass(\"eael-product-popup-ready\");\n    });\n    $(document).on(\"click\", function (event) {\n      if (event.target.closest(\".eael-product-popup-details\")) return;\n      $(\".eael-product-popup.eael-product-zoom-in.eael-product-popup-ready\").addClass(\"eael-product-modal-removing\").removeClass(\"eael-product-popup-ready\");\n    });\n\n    if (isEditMode) {\n      $(\".eael-product-image-wrap .woocommerce-product-gallery\").css(\"opacity\", \"1\");\n    }\n  };\n\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eicon-woocommerce.default\", productGrid);\n});\n\n//# sourceURL=webpack:///./src/js/view/product-grid.js?");

/***/ })

/******/ });