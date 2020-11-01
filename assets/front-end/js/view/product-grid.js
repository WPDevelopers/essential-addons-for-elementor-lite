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

eval("var ProductGrid = function ProductGrid($scope, $) {\n  // pagination\n  $('.eael-woo-pagination', $scope).on('click', 'a', function (e) {\n    e.preventDefault();\n    var $this = $(this),\n        nth = $this.data('pnumber'),\n        lmt = $this.data('plimit'),\n        ajax_url = localize.ajaxurl,\n        args = $this.data('args'),\n        settings = $this.data('settings'),\n        widgetid = $this.data('widgetid'),\n        widgetclass = \".elementor-element-\" + widgetid,\n        template_info = $this.data('template');\n    $.ajax({\n      url: ajax_url,\n      type: 'post',\n      data: {\n        action: 'woo_product_pagination_product',\n        number: nth,\n        limit: lmt,\n        args: args,\n        templateInfo: template_info,\n        settings: settings\n      },\n      beforeSend: function beforeSend() {\n        $(widgetclass).addClass('eael-product-loader');\n      },\n      success: function success(response) {\n        // console.log(response);\n        $(widgetclass + \" .eael-product-grid .products\").html(response);\n        $(widgetclass + \" .woocommerce-product-gallery\").each(function () {\n          $(this).wc_product_gallery();\n        });\n      },\n      complete: function complete() {\n        $(widgetclass).removeClass('eael-product-loader');\n      }\n    });\n    $.ajax({\n      url: ajax_url,\n      type: 'post',\n      data: {\n        action: 'woo_product_pagination',\n        number: nth,\n        limit: lmt,\n        args: args,\n        settings: settings\n      },\n      // beforeSend\t: function(){\n      // \t$(widgetclass+\" .eael-product-grid .products\").html(\"<li style='text-align:center;'>Loading please \" +\n      // \t\t\"wait...!</li>\");\n      // },\n      success: function success(response) {\n        $(widgetclass + \" .eael-product-grid .eael-woo-pagination\").html(response);\n      }\n    });\n  });\n  $(document).on('click', '.open-popup-link', function (e) {\n    e.preventDefault();\n    e.stopPropagation();\n    var $this = $(this);\n    var id = $this.attr('href');\n    var popup = $(id);\n    var popup_details = popup.children(\".eael-product-popup-details\");\n\n    if (popup_details.height() > 400) {\n      popup_details.css(\"height\", '75vh');\n    } else {\n      popup_details.css(\"height\", 'auto');\n    } // if($layout_mode === 'masonry') {\n    // \tif($(id+\" .woocommerce-product-gallery\").hasClass('eael-new-product')){\n    // \t\t// $(id+\" .woocommerce-product-gallery.eael-new-product\").wc_product_gallery({\n    // \t\t// \tphotoswipe_enabled:true,\n    // \t\t// });\n    // \t}\n    // }\n\n\n    $(id + \" .variations_form\").wc_variation_form();\n    popup.addClass(\"eael-product-popup-ready\").removeClass(\"eael-product-modal-removing\");\n  });\n  $(document).on('keypress', '.eael-product-details-wrap input[type=number]', function (e) {\n    var keyValue = e.keyCode || e.which;\n    var regex = /^[0-9]+$/;\n    var isValid = regex.test(String.fromCharCode(keyValue));\n\n    if (!isValid) {\n      return false;\n    }\n\n    return isValid;\n  }); // handle add to cart for quick view\n\n  $scope.on('click', '.eael-product-popup-details .single_add_to_cart_button', function (e) {\n    e.preventDefault();\n    var $this = $(this),\n        product_id = $(this).val(),\n        variation_id = $this.closest('form.cart').find('input[name=\"variation_id\"]').val() || '',\n        quantity = $this.closest('form.cart').find('input[name=\"quantity\"]').val(),\n        items = $this.closest('form.cart.grouped_form'),\n        form = $this.closest('form.cart'),\n        product_data = [];\n    items = items.serializeArray();\n\n    if (form.hasClass('variations_form')) {\n      product_id = form.find('input[name=\"product_id\"]').val();\n    }\n\n    if (items.length > 0) {\n      items.forEach(function (item, index) {\n        var p_id = parseInt(item.name.replace(/[^\\d.]/g, ''), 10);\n\n        if (item.name.indexOf('quantity[') >= 0 && item.value != '' && p_id > 0) {\n          product_data[product_data.length] = {\n            'product_id': p_id,\n            'quantity': item.value,\n            'variation_id': 0\n          };\n        }\n      });\n    } else {\n      product_data[0] = {\n        'product_id': product_id,\n        'quantity': quantity,\n        'variation_id': variation_id\n      };\n    }\n\n    $this.removeClass('eael-addtocart-added');\n    $this.addClass('eael-addtocart-loading');\n    $.ajax({\n      url: localize.ajaxurl,\n      type: 'post',\n      data: {\n        action: 'eael_product_add_to_cart',\n        product_data: product_data,\n        eael_add_to_cart_nonce: localize.nonce\n      },\n      success: function success(response) {\n        if (response.success) {\n          $(document.body).trigger('wc_fragment_refresh');\n          $this.removeClass('eael-addtocart-loading');\n          $this.addClass('eael-addtocart-added');\n        }\n      }\n    });\n  });\n};\n\njQuery(window).on(\"elementor/frontend/init\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eicon-woocommerce.default\", ProductGrid);\n});\n\n//# sourceURL=webpack:///./src/js/view/product-grid.js?");

/***/ })

/******/ });