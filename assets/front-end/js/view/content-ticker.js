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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/content-ticker.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/content-ticker.js":
/*!***************************************!*\
  !*** ./src/js/view/content-ticker.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("eael.hooks.addAction(\"init\", \"ea\", function () {\n  function get_configurations($contentTicker) {\n    var $items = $contentTicker.data(\"items\") !== undefined ? $contentTicker.data(\"items\") : 1,\n      $items_tablet = $contentTicker.data(\"items-tablet\") !== undefined ? $contentTicker.data(\"items-tablet\") : 1,\n      $items_mobile = $contentTicker.data(\"items-mobile\") !== undefined ? $contentTicker.data(\"items-mobile\") : 1,\n      $margin = $contentTicker.data(\"margin\") !== undefined ? $contentTicker.data(\"margin\") : 10,\n      $margin_tablet = $contentTicker.data(\"margin-tablet\") !== undefined ? $contentTicker.data(\"margin-tablet\") : 10,\n      $margin_mobile = $contentTicker.data(\"margin-mobile\") !== undefined ? $contentTicker.data(\"margin-mobile\") : 10,\n      $effect = $contentTicker.data(\"effect\") !== undefined ? $contentTicker.data(\"effect\") : \"slide\",\n      $speed = $contentTicker.data(\"speed\") !== undefined ? $contentTicker.data(\"speed\") : 400,\n      $autoplay = $contentTicker.data(\"autoplay\") !== undefined ? $contentTicker.data(\"autoplay\") : 5000,\n      $loop = $contentTicker.data(\"loop\") !== undefined ? $contentTicker.data(\"loop\") : false,\n      $grab_cursor = $contentTicker.data(\"grab-cursor\") !== undefined ? $contentTicker.data(\"grab-cursor\") : false,\n      $pagination = $contentTicker.data(\"pagination\") !== undefined ? $contentTicker.data(\"pagination\") : \".swiper-pagination\",\n      $arrow_next = $contentTicker.data(\"arrow-next\") !== undefined ? $contentTicker.data(\"arrow-next\") : \".swiper-button-next\",\n      $arrow_prev = $contentTicker.data(\"arrow-prev\") !== undefined ? $contentTicker.data(\"arrow-prev\") : \".swiper-button-prev\",\n      $pause_on_hover = $contentTicker.data(\"pause-on-hover\") !== undefined ? $contentTicker.data(\"pause-on-hover\") : \"\";\n    return {\n      pauseOnHover: $pause_on_hover,\n      direction: \"horizontal\",\n      loop: $loop,\n      speed: $speed,\n      effect: $effect,\n      slidesPerView: $items,\n      spaceBetween: $margin,\n      grabCursor: $grab_cursor,\n      paginationClickable: true,\n      autoHeight: true,\n      autoplay: {\n        delay: $autoplay,\n        disableOnInteraction: false\n      },\n      pagination: {\n        el: $pagination,\n        clickable: true\n      },\n      navigation: {\n        nextEl: $arrow_next,\n        prevEl: $arrow_prev\n      },\n      breakpoints: {\n        // when window width is <= 480px\n        480: {\n          slidesPerView: $items_mobile,\n          spaceBetween: $margin_mobile\n        },\n        // when window width is <= 640px\n        768: {\n          slidesPerView: $items_tablet,\n          spaceBetween: $margin_tablet\n        }\n      }\n    };\n  }\n  function autoPlayManager(element, event, options) {\n    if (options.autoplay.delay === 0) {\n      var _event$autoplay;\n      event === null || event === void 0 || (_event$autoplay = event.autoplay) === null || _event$autoplay === void 0 || _event$autoplay.stop();\n    }\n    if (options.pauseOnHover && options.autoplay.delay !== 0) {\n      element.on(\"mouseenter\", function () {\n        var _event$autoplay2;\n        event === null || event === void 0 || (_event$autoplay2 = event.autoplay) === null || _event$autoplay2 === void 0 || _event$autoplay2.pause();\n      });\n      element.on(\"mouseleave\", function () {\n        var _event$autoplay3;\n        event === null || event === void 0 || (_event$autoplay3 = event.autoplay) === null || _event$autoplay3 === void 0 || _event$autoplay3.run();\n      });\n    }\n  }\n  var ContentTicker = function ContentTicker($scope, $) {\n    var $contentTicker = $scope.find(\".eael-content-ticker\").eq(0),\n      contentOptions = get_configurations($contentTicker);\n    swiperLoader($contentTicker, contentOptions).then(function (event) {\n      autoPlayManager($contentTicker, event, contentOptions);\n    });\n    var ContentTickerSlider = function ContentTickerSlider(element) {\n      var contentTickerElements = $(element).find('.eael-content-ticker');\n      if (contentTickerElements.length) {\n        contentTickerElements.each(function () {\n          var $this = $(this);\n          if ($this[0].swiper) {\n            $this[0].swiper.destroy(true, true);\n            var options = get_configurations($this);\n            swiperLoader($this[0], options).then(function (event) {\n              autoPlayManager($this, event, options);\n            });\n          }\n        });\n      }\n    };\n    eael.hooks.addAction(\"ea-toggle-triggered\", \"ea\", ContentTickerSlider);\n    eael.hooks.addAction(\"ea-lightbox-triggered\", \"ea\", ContentTickerSlider);\n    eael.hooks.addAction(\"ea-advanced-tabs-triggered\", \"ea\", ContentTickerSlider);\n    eael.hooks.addAction(\"ea-advanced-accordion-triggered\", \"ea\", ContentTickerSlider);\n  };\n  var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {\n    if ('undefined' === typeof Swiper || 'function' === typeof Swiper) {\n      var asyncSwiper = elementorFrontend.utils.swiper;\n      return new asyncSwiper(swiperElement, swiperConfig).then(function (newSwiperInstance) {\n        return newSwiperInstance;\n      });\n    } else {\n      return swiperPromise(swiperElement, swiperConfig);\n    }\n  };\n  var swiperPromise = function swiperPromise(swiperElement, swiperConfig) {\n    return new Promise(function (resolve, reject) {\n      var swiperInstance = new Swiper(swiperElement, swiperConfig);\n      resolve(swiperInstance);\n    });\n  };\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-content-ticker.default\", ContentTicker);\n});\n\n//# sourceURL=webpack:///./src/js/view/content-ticker.js?");

/***/ })

/******/ });