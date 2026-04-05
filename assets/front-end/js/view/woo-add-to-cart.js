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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/woo-add-to-cart.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/woo-add-to-cart.js":
/*!****************************************!*\
  !*** ./src/js/view/woo-add-to-cart.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("eael.hooks.addAction(\"init\", \"ea\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-woo-add-to-cart.default\", function ($scope, $) {\n    var $wrapper = $scope.find('.eael-add-to-cart-wrapper[data-eael-ajax-add-to-cart=\"yes\"]');\n    if (!$wrapper.length) {\n      return;\n    }\n    var productId = $wrapper.data(\"product-id\");\n    var productType = $wrapper.data(\"product-type\");\n    var nonce = $wrapper.data(\"nonce\");\n    var ajaxUrl = localize.ajaxurl;\n    var $notices = $wrapper.find(\".woocommerce-notices-wrapper\");\n    $wrapper.off(\"click.eael-atc\").on(\"click.eael-atc\", \".single_add_to_cart_button\", function (e) {\n      var $button = $(this);\n\n      // Let WooCommerce handle disabled state (e.g. no variation selected).\n      if ($button.hasClass(\"disabled\") || $button.hasClass(\"loading\")) {\n        return;\n      }\n      var $form = $button.closest(\"form\");\n      if (!$form.length) {\n        return;\n      }\n      e.preventDefault();\n\n      // Variable product: require a resolved variation_id before going async.\n      if (productType === \"variable\") {\n        var variationId = parseInt($form.find('input[name=\"variation_id\"]').val() || 0, 10);\n        if (variationId <= 0) {\n          $form.find(\".variations select\").first().trigger(\"change\");\n          return;\n        }\n      }\n      $button.addClass(\"loading\").prop(\"disabled\", true);\n      $notices.empty();\n      var data = {\n        action: \"eael_ajax_add_to_cart\",\n        nonce: nonce,\n        product_id: productId,\n        product_type: productType\n      };\n      if (productType === \"variable\") {\n        data.variation_id = parseInt($form.find('input[name=\"variation_id\"]').val() || 0, 10);\n        data.quantity = parseInt($form.find(\"input.qty\").val() || 1, 10);\n        // Collect variation attribute selects.\n        $form.find('[name^=\"attribute_\"]').each(function () {\n          data[$(this).attr(\"name\")] = $(this).val();\n        });\n      } else if (productType === \"grouped\") {\n        // Grouped: WooCommerce renders qty inputs as quantity[child_id].\n        $form.find('input[name^=\"quantity[\"]').each(function () {\n          var name = $(this).attr(\"name\"); // e.g. quantity[371]\n          data[name] = parseInt($(this).val() || 0, 10);\n        });\n      } else {\n        data.quantity = parseInt($form.find(\"input.qty\").val() || 1, 10);\n      }\n      $.post(ajaxUrl, data, function (response) {\n        $button.removeClass(\"loading\").prop(\"disabled\", false);\n        if (!response.success) {\n          if (response.data && response.data.notices) {\n            $notices.html(response.data.notices);\n          }\n          return;\n        }\n\n        // Refresh mini-cart fragments.\n        $(document.body).trigger(\"wc_fragment_refresh\");\n        $(document.body).trigger(\"added_to_cart\", [response.data.fragments, response.data.cart_hash, $button]);\n\n        // Show the success notice returned by WooCommerce.\n        if (response.data.notices) {\n          $notices.html(response.data.notices);\n        }\n        $button.addClass(\"added\");\n        setTimeout(function () {\n          $button.removeClass(\"added\");\n        }, 2000);\n      }).fail(function () {\n        $button.removeClass(\"loading\").prop(\"disabled\", false);\n      });\n    });\n  });\n});\n\n//# sourceURL=webpack:///./src/js/view/woo-add-to-cart.js?");

/***/ })

/******/ });