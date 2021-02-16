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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/woo-product-slider.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/woo-product-slider.js":
/*!*******************************************!*\
  !*** ./src/js/view/woo-product-slider.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("ea.hooks.addAction(\"init\", \"ea\", function () {\n  var wooProductSlider = function wooProductSlider($scope, $) {\n    var $wooProductSlider = $scope.find(\".eael-woo-product-slider\").eq(0),\n        $autoplay = $wooProductSlider.data(\"autoplay\") !== undefined ? $wooProductSlider.data(\"autoplay\") : 999999,\n        $pagination = $wooProductSlider.data(\"pagination\") !== undefined ? $wooProductSlider.data(\"pagination\") : \".swiper-pagination\",\n        $arrow_next = $wooProductSlider.data(\"arrow-next\") !== undefined ? $wooProductSlider.data(\"arrow-next\") : \".swiper-button-next\",\n        $arrow_prev = $wooProductSlider.data(\"arrow-prev\") !== undefined ? $wooProductSlider.data(\"arrow-prev\") : \".swiper-button-prev\",\n        $items = $wooProductSlider.data(\"items\") !== undefined ? $wooProductSlider.data(\"items\") : 3,\n        $items_tablet = $wooProductSlider.data(\"items-tablet\") !== undefined ? $wooProductSlider.data(\"items-tablet\") : 3,\n        $items_mobile = $wooProductSlider.data(\"items-mobile\") !== undefined ? $wooProductSlider.data(\"items-mobile\") : 3,\n        $margin = $wooProductSlider.data(\"margin\") !== undefined ? $wooProductSlider.data(\"margin\") : 10,\n        $margin_tablet = $wooProductSlider.data(\"margin-tablet\") !== undefined ? $wooProductSlider.data(\"margin-tablet\") : 10,\n        $margin_mobile = $wooProductSlider.data(\"margin-mobile\") !== undefined ? $wooProductSlider.data(\"margin-mobile\") : 10,\n        $effect = $wooProductSlider.data(\"effect\") !== undefined ? $wooProductSlider.data(\"effect\") : \"slide\",\n        $speed = $wooProductSlider.data(\"speed\") !== undefined ? $wooProductSlider.data(\"speed\") : 400,\n        $loop = $wooProductSlider.data(\"loop\") !== undefined ? $wooProductSlider.data(\"loop\") : 0,\n        $grab_cursor = $wooProductSlider.data(\"grab-cursor\") !== undefined ? $wooProductSlider.data(\"grab-cursor\") : 0,\n        $pause_on_hover = $wooProductSlider.data(\"pause-on-hover\") !== undefined ? $wooProductSlider.data(\"pause-on-hover\") : \"\",\n        $centeredSlides = $effect == \"coverflow\" ? true : false;\n    var $sliderOptions = {\n      direction: \"horizontal\",\n      speed: $speed,\n      effect: $effect,\n      centeredSlides: $centeredSlides,\n      grabCursor: $grab_cursor,\n      autoHeight: true,\n      loop: $loop,\n      autoplay: {\n        delay: $autoplay\n      },\n      pagination: {\n        el: $pagination,\n        clickable: true\n      },\n      navigation: {\n        nextEl: $arrow_next,\n        prevEl: $arrow_prev\n      }\n    };\n\n    if ($effect === 'slide' || $effect === 'coverflow') {\n      $sliderOptions.breakpoints = {\n        1024: {\n          slidesPerView: $items,\n          spaceBetween: $margin\n        },\n        768: {\n          slidesPerView: $items_tablet,\n          spaceBetween: $margin_tablet\n        },\n        320: {\n          slidesPerView: $items_mobile,\n          spaceBetween: $margin_mobile\n        }\n      };\n    } else {\n      $sliderOptions.items = 1;\n    }\n\n    var eaelWooProductSlider = new Swiper($wooProductSlider, $sliderOptions);\n\n    if ($autoplay === 0) {\n      eaelWooProductSlider.autoplay.stop();\n    }\n\n    if ($pause_on_hover && $autoplay !== 0) {\n      $wooProductSlider.on(\"mouseenter\", function () {\n        eaelWooProductSlider.autoplay.stop();\n      });\n      $wooProductSlider.on(\"mouseleave\", function () {\n        eaelWooProductSlider.autoplay.start();\n      });\n    }\n  };\n\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/woo-product-slider.default\", wooProductSlider);\n});\n\n//# sourceURL=webpack:///./src/js/view/woo-product-slider.js?");

/***/ })

/******/ });