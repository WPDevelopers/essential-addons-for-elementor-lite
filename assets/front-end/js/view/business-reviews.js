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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/business-reviews.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/business-reviews.js":
/*!*****************************************!*\
  !*** ./src/js/view/business-reviews.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var BusinessReviewsHandler = function BusinessReviewsHandler($scope, $) {\n  var $businessReviewsWrapper = $(\".eael-business-reviews-wrapper\", $scope);\n  var source = $businessReviewsWrapper.attr('data-source'),\n    layout = $businessReviewsWrapper.attr('data-layout');\n  if (source === 'google-reviews') {\n    // Slider or Grid\n    if (layout === 'slider') {\n      var businessReviewsSlider = $scope.find('.eael-google-reviews-content').eq(0),\n        pagination = businessReviewsSlider.attr('data-pagination'),\n        arrowNext = businessReviewsSlider.attr('data-arrow-next'),\n        arrowPrev = businessReviewsSlider.attr('data-arrow-prev'),\n        effect = businessReviewsSlider.attr('data-effect'),\n        items = businessReviewsSlider.attr('data-items'),\n        itemsTablet = businessReviewsSlider.attr('data-items_tablet'),\n        itemsMobile = businessReviewsSlider.attr('data-items_mobile'),\n        itemGap = businessReviewsSlider.attr('data-item_gap'),\n        loop = businessReviewsSlider.attr('data-loop'),\n        speed = businessReviewsSlider.attr('data-speed'),\n        autoplay = businessReviewsSlider.attr('data-autoplay'),\n        autoplayDelay = businessReviewsSlider.attr('data-autoplay_delay'),\n        pauseOnHover = businessReviewsSlider.attr('data-pause_on_hover'),\n        grabCursor = businessReviewsSlider.attr('data-grab_cursor');\n      var businessReviewsSliderOptions = {\n        direction: 'horizontal',\n        effect: effect,\n        slidesPerView: items,\n        loop: parseInt(loop),\n        speed: parseInt(speed),\n        grabCursor: parseInt(grabCursor),\n        pagination: {\n          el: pagination,\n          clickable: true\n        },\n        navigation: {\n          nextEl: arrowNext,\n          prevEl: arrowPrev\n        },\n        autoplay: {\n          delay: parseInt(autoplay) ? parseInt(autoplayDelay) : 999999,\n          disableOnInteraction: false\n        },\n        autoHeight: true,\n        spaceBetween: parseInt(itemGap)\n      };\n      if (effect === 'slide' || effect === 'coverflow') {\n        businessReviewsSliderOptions.breakpoints = {\n          1024: {\n            slidesPerView: items,\n            spaceBetween: parseInt(itemGap)\n          },\n          768: {\n            slidesPerView: itemsTablet,\n            spaceBetween: parseInt(itemGap)\n          },\n          320: {\n            slidesPerView: itemsMobile,\n            spaceBetween: parseInt(itemGap)\n          }\n        };\n      } else {\n        businessReviewsSliderOptions.items = 1;\n      }\n      var businessReviewsSliderObj = swiperLoader(businessReviewsSlider, businessReviewsSliderOptions);\n      businessReviewsSliderObj.then(function (businessReviewsSliderObj) {\n        if (autoplay === 0) {\n          businessReviewsSliderObj.autoplay.stop();\n        }\n        if (parseInt(pauseOnHover) && autoplay !== 0) {\n          businessReviewsSlider.on('mouseenter', function () {\n            businessReviewsSliderObj.autoplay.stop();\n          });\n          businessReviewsSlider.on('mouseleave', function () {\n            businessReviewsSliderObj.autoplay.start();\n          });\n        }\n        businessReviewsSliderObj.update();\n      });\n    }\n  }\n};\nvar swiperLoader = function swiperLoader(swiperElement, swiperConfig) {\n  if ('undefined' === typeof Swiper || 'function' === typeof Swiper) {\n    var asyncSwiper = elementorFrontend.utils.swiper;\n    return new asyncSwiper(swiperElement, swiperConfig).then(function (newSwiperInstance) {\n      return newSwiperInstance;\n    });\n  } else {\n    return swiperPromise(swiperElement, swiperConfig);\n  }\n};\nvar swiperPromise = function swiperPromise(swiperElement, swiperConfig) {\n  return new Promise(function (resolve, reject) {\n    var swiperInstance = new Swiper(swiperElement, swiperConfig);\n    resolve(swiperInstance);\n  });\n};\neael.hooks.addAction(\"init\", \"ea\", function () {\n  if (eael.elementStatusCheck('eaelBusinessReviews')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-business-reviews.default\", BusinessReviewsHandler);\n});\n\n//# sourceURL=webpack:///./src/js/view/business-reviews.js?");

/***/ })

/******/ });