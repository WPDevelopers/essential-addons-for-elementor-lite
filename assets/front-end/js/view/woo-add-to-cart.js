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

eval("eael.hooks.addAction(\"init\", \"ea\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-woo-add-to-cart.default\", function ($scope, $) {\n    var $wrapper = $scope.find('.eael-add-to-cart-wrapper[data-eael-ajax-add-to-cart=\"yes\"]');\n    if (!$wrapper.length) {\n      return;\n    }\n    var productId = $wrapper.data(\"product-id\");\n    $wrapper.on(\"submit\", \"form.cart\", function (e) {\n      e.preventDefault();\n      var $form = $(this);\n      var $button = $form.find(\".single_add_to_cart_button\");\n      if ($button.hasClass(\"loading\") || $button.hasClass(\"disabled\")) {\n        return;\n      }\n      var quantity = $form.find(\"input.qty\").val() || 1;\n      $button.addClass(\"loading\").prop(\"disabled\", true);\n      var data = {\n        \"add-to-cart\": productId,\n        product_id: productId,\n        quantity: quantity\n      };\n\n      // Include variation data when present (variable products)\n      $form.find('input[name^=\"attribute_\"], input[name=\"variation_id\"]').each(function () {\n        data[$(this).attr(\"name\")] = $(this).val();\n      });\n      var ajaxUrl = typeof wc_add_to_cart_params !== \"undefined\" ? wc_add_to_cart_params.wc_ajax_url.toString().replace(\"%%endpoint%%\", \"add_to_cart\") : \"/?wc-ajax=add_to_cart\";\n      $.post(ajaxUrl, data, function (response) {\n        $button.removeClass(\"loading\").prop(\"disabled\", false);\n        if (!response) {\n          return;\n        }\n        if (response.error && response.product_url) {\n          window.location = response.product_url;\n          return;\n        }\n\n        // Refresh WooCommerce cart fragments (updates mini-cart counts, etc.)\n        $(document.body).trigger(\"wc_fragment_refresh\");\n        $(document.body).trigger(\"added_to_cart\", [response.fragments, response.cart_hash, $button]);\n        $button.addClass(\"added\");\n        setTimeout(function () {\n          $button.removeClass(\"added\");\n        }, 2000);\n      }).fail(function () {\n        $button.removeClass(\"loading\").prop(\"disabled\", false);\n      });\n    });\n  });\n});\n\n//# sourceURL=webpack:///./src/js/view/woo-add-to-cart.js?");

/***/ })

/******/ });