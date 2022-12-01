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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/woo-checkout.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/woo-checkout.js":
/*!*************************************!*\
  !*** ./src/js/view/woo-checkout.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var WooCheckout = function WooCheckout($scope, $) {\n  $.blockUI.defaults.overlayCSS.cursor = \"default\";\n  function render_order_review_template() {\n    var wooCheckout = $(\".ea-woo-checkout\");\n    setTimeout(function () {\n      $(\".ea-checkout-review-order-table\").addClass(\"processing\").block({\n        message: null,\n        overlayCSS: {\n          background: \"#fff\",\n          opacity: 0.6\n        }\n      });\n      $.ajax({\n        type: \"POST\",\n        url: localize.ajaxurl,\n        data: {\n          action: \"woo_checkout_update_order_review\",\n          orderReviewData: wooCheckout.data(\"checkout\")\n        },\n        success: function success(data) {\n          $(\".ea-checkout-review-order-table\").replaceWith(data.order_review);\n          setTimeout(function () {\n            $(\".ea-checkout-review-order-table\").removeClass(\"processing\").unblock();\n          }, 100000);\n        }\n      });\n    }, 2000);\n  }\n  $(document).on(\"click\", \".woocommerce-remove-coupon\", function (e) {\n    render_order_review_template();\n  });\n  $(\"form.checkout_coupon\").submit(function (event) {\n    render_order_review_template();\n  });\n  var wooCheckout = $(\".ea-woo-checkout\");\n  wooCheckout.on('change', 'select.shipping_method, input[name^=\"shipping_method\"], #ship-to-different-address input, .update_totals_on_change select, .update_totals_on_change input[type=\"radio\"], .update_totals_on_change input[type=\"checkbox\"]', function () {\n    $(document.body).trigger('update_checkout');\n    render_order_review_template();\n  }); // eslint-disable-line max-len\n\n  $(document.body).bind('update_checkout', function () {\n    render_order_review_template();\n  });\n\n  //move coupon remove message to coupon box (multi step and split layout)\n  $(document.body).on('removed_coupon_in_checkout', function () {\n    var message = $('.ea-woo-checkout .ms-tabs-content > .woocommerce-message,.ea-woo-checkout .split-tabs-content > .woocommerce-message').remove();\n    $('.ea-woo-checkout .checkout_coupon.woocommerce-form-coupon').before(message);\n  });\n  $(document).on('change', '.eael-checkout-cart-qty-input', function () {\n    var cart_item_key = $(this).attr('name').replace(/cart\\[([\\w]+)\\]\\[qty\\]/g, \"$1\");\n    var item_quantity = $(this).val();\n    var currentVal = parseFloat(item_quantity);\n    $this = $(this);\n    $.ajax({\n      type: 'POST',\n      url: localize.ajaxurl,\n      data: {\n        action: 'eael_checkout_cart_qty_update',\n        nonce: localize.nonce,\n        cart_item_key: cart_item_key,\n        quantity: currentVal\n      },\n      beforeSend: function beforeSend() {\n        $this.attr('disabled', 'disabled');\n        $this.closest('.ea-woo-checkout').css('opacity', '0.5');\n      },\n      success: function success(response) {\n        if (response.success) {\n          if (typeof response.data.cart_item_subtotal !== 'undefined') {\n            $this.closest('.cart_item').find('.eael-checkout-cart-item-total').html(response.data.cart_item_subtotal);\n          }\n          if (typeof response.data.cart_subtotal !== 'undefined') {\n            $this.closest('.ea-checkout-review-order-table').find('.eael-checkout-cart-subtotal').html(response.data.cart_subtotal);\n          }\n          if (typeof response.data.cart_total !== 'undefined') {\n            $this.closest('.ea-checkout-review-order-table').find('.eael-checkout-cart-total').html(response.data.cart_total);\n          }\n        }\n        $this.closest('.ea-woo-checkout').css('opacity', '1');\n        $('.eael-checkout-cart-qty-input').attr('disabled', false);\n      },\n      error: function error(_error) {\n        console.log(_error);\n        $('.eael-checkout-cart-qty-input').attr('disabled', false);\n        $this.closest('.ea-woo-checkout').css('opacity', '1');\n      }\n    });\n  });\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-woo-checkout.default\", WooCheckout);\n});\njQuery(document.body).on('country_to_state_changing', function (event, country, wrapper) {\n  var $ = jQuery,\n    checkout_keys = $('.ea-woo-checkout').data('checkout_ids'),\n    field_wrapper = $('.ea-woo-checkout .woocommerce-billing-fields__field-wrapper, .ea-woo-checkout .woocommerce-shipping-fields__field-wrapper');\n  field_wrapper.addClass('eael-reordering');\n  var reorder_fields = function reorder_fields(type, _wrapper) {\n    var $selector = $(\".woocommerce-\".concat(type, \"-fields__field-wrapper\"));\n    _wrapper = typeof _wrapper !== 'undefined' ? _wrapper : wrapper;\n    $.each(checkout_keys[type], function (field_key, form_class) {\n      var $fieldHtml = _wrapper.find(\"#\".concat(field_key, \"_field\"));\n      $fieldHtml.removeClass('form-row-first form-row-last form-row-wide').addClass(form_class);\n      $(\"#eael-wc-\".concat(type, \"-reordered-fields .eael-woo-\").concat(type, \"-fields\")).append($fieldHtml);\n    });\n    $selector.replaceWith($(\"#eael-wc-\".concat(type, \"-reordered-fields\")).contents());\n    $(\".eael-woo-\".concat(type, \"-fields\")).toggleClass(\"eael-woo-\".concat(type, \"-fields woocommerce-\").concat(type, \"-fields__field-wrapper\"));\n    $(\"#eael-wc-\".concat(type, \"-reordered-fields\")).html(\"<div class=\\\"eael-woo-\".concat(type, \"-fields\\\"></div>\"));\n  };\n  setTimeout(function () {\n    if (wrapper.hasClass(\"woocommerce-billing-fields\")) {\n      reorder_fields('billing');\n      reorder_fields('shipping', $('.woocommerce-shipping-fields'));\n    }\n    if (wrapper.hasClass(\"woocommerce-shipping-fields\")) {\n      reorder_fields('shipping');\n      reorder_fields('billing', $('.woocommerce-billing-fields'));\n    }\n    field_wrapper.removeClass('eael-reordering');\n  }, 500);\n});\nvar change_button_text = function change_button_text() {\n  var $ = jQuery,\n    button_texts = $('.ea-woo-checkout').data('button_texts');\n  setTimeout(function () {\n    if (button_texts.place_order !== '') {\n      $('#place_order').text(button_texts.place_order);\n    }\n  }, 500);\n};\njQuery(document.body).on('update_checkout payment_method_selected', function (event) {\n  change_button_text();\n}).on('click', '.woocommerce-checkout-payment li label', function () {\n  change_button_text();\n});\n\n//# sourceURL=webpack:///./src/js/view/woo-checkout.js?");

/***/ })

/******/ });