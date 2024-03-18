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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/woo-product-carousel.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/woo-product-carousel.js":
/*!*********************************************!*\
  !*** ./src/js/view/woo-product-carousel.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("ea.hooks.addAction(\"init\", \"ea\", function () {\n  var wooProductCarousel = function wooProductCarousel($scope, $) {\n    ea.hooks.doAction(\"quickViewAddMarkup\", $scope, $);\n    var $wooProductCarousel = $scope.find(\".eael-woo-product-carousel\").eq(0),\n      $type = $wooProductCarousel.data(\"type\"),\n      $autoplay = $wooProductCarousel.data(\"autoplay\") !== undefined ? $wooProductCarousel.data(\"autoplay\") : 999999,\n      $pagination = $wooProductCarousel.data(\"pagination\") !== undefined ? $wooProductCarousel.data(\"pagination\") : \".swiper-pagination\",\n      $arrow_next = $wooProductCarousel.data(\"arrow-next\") !== undefined ? $wooProductCarousel.data(\"arrow-next\") : \".swiper-button-next\",\n      $arrow_prev = $wooProductCarousel.data(\"arrow-prev\") !== undefined ? $wooProductCarousel.data(\"arrow-prev\") : \".swiper-button-prev\",\n      $items = $wooProductCarousel.data(\"items\") !== undefined ? $wooProductCarousel.data(\"items\") : 3,\n      $items_tablet = $wooProductCarousel.data(\"items-tablet\") !== undefined ? $wooProductCarousel.data(\"items-tablet\") : 3,\n      $items_mobile = $wooProductCarousel.data(\"items-mobile\") !== undefined ? $wooProductCarousel.data(\"items-mobile\") : 3,\n      $margin = $wooProductCarousel.data(\"margin\") !== undefined ? $wooProductCarousel.data(\"margin\") : 10,\n      $margin_tablet = $wooProductCarousel.data(\"margin-tablet\") !== undefined ? $wooProductCarousel.data(\"margin-tablet\") : 10,\n      $margin_mobile = $wooProductCarousel.data(\"margin-mobile\") !== undefined ? $wooProductCarousel.data(\"margin-mobile\") : 0,\n      $effect = $wooProductCarousel.data(\"effect\") !== undefined ? $wooProductCarousel.data(\"effect\") : \"slide\",\n      $speed = $wooProductCarousel.data(\"speed\") !== undefined ? $wooProductCarousel.data(\"speed\") : 400,\n      $loop = $wooProductCarousel.data(\"loop\") !== undefined ? $wooProductCarousel.data(\"loop\") : 0,\n      $grab_cursor = $wooProductCarousel.data(\"grab-cursor\") !== undefined ? $wooProductCarousel.data(\"grab-cursor\") : 0,\n      $pause_on_hover = $wooProductCarousel.data(\"pause-on-hover\") !== undefined ? $wooProductCarousel.data(\"pause-on-hover\") : \"\",\n      $centeredSlides = $effect == \"coverflow\" ? true : false,\n      $depth = $wooProductCarousel.data(\"depth\") !== undefined ? $wooProductCarousel.data(\"depth\") : 100,\n      $rotate = $wooProductCarousel.data(\"rotate\") !== undefined ? $wooProductCarousel.data(\"rotate\") : 50,\n      $stretch = $wooProductCarousel.data(\"stretch\") !== undefined ? $wooProductCarousel.data(\"stretch\") : 10;\n    var $carouselOptions = {\n      direction: \"horizontal\",\n      speed: $speed,\n      effect: $effect,\n      centeredSlides: $centeredSlides,\n      grabCursor: $grab_cursor,\n      autoHeight: true,\n      loop: $loop,\n      slidesPerGroup: 1,\n      autoplay: {\n        delay: $autoplay,\n        disableOnInteraction: false\n      },\n      pagination: {\n        el: $pagination,\n        clickable: true\n      },\n      navigation: {\n        nextEl: $arrow_next,\n        prevEl: $arrow_prev\n      },\n      slidesPerView: $items,\n      on: {\n        init: function init() {\n          setTimeout(function () {\n            window.dispatchEvent(new Event('resize'));\n          }, 200);\n        }\n      }\n    };\n    if ($effect === 'slide') {\n      $carouselOptions.breakpoints = {\n        1024: {\n          slidesPerView: $items,\n          spaceBetween: $margin\n        },\n        768: {\n          slidesPerView: $items_tablet,\n          spaceBetween: $margin_tablet\n        },\n        320: {\n          slidesPerView: $items_mobile,\n          spaceBetween: $margin_mobile\n        }\n      };\n    }\n    if ($effect === 'coverflow') {\n      // $carouselOptions.slidesPerView = 'auto';\n      $carouselOptions.coverflowEffect = {\n        rotate: $rotate,\n        stretch: $stretch,\n        depth: $depth,\n        modifier: 1,\n        slideShadows: false\n      };\n      $carouselOptions.breakpoints = {\n        1024: {\n          slidesPerView: 3\n        },\n        768: {\n          slidesPerView: 1\n        },\n        320: {\n          slidesPerView: 1\n        }\n      };\n    }\n    if ($autoplay === 0) {\n      $carouselOptions.autoplay = false;\n    }\n    swiperLoader($wooProductCarousel, $carouselOptions).then(function (eaelWooProductCarousel) {\n      if ($autoplay === 0) {\n        eaelWooProductCarousel.autoplay.stop();\n      }\n      if ($pause_on_hover && $autoplay !== 0) {\n        $wooProductCarousel.on(\"mouseenter\", function () {\n          eaelWooProductCarousel.autoplay.stop();\n        });\n        $wooProductCarousel.on(\"mouseleave\", function () {\n          eaelWooProductCarousel.autoplay.start();\n        });\n      }\n\n      //gallery pagination\n      var $paginationGallerySelector = $scope.find('.eael-woo-product-carousel-container .eael-woo-product-carousel-gallary-pagination').eq(0);\n      if ($paginationGallerySelector.length > 0) {\n        swiperLoader($paginationGallerySelector, {\n          spaceBetween: 20,\n          centeredSlides: $centeredSlides,\n          touchRatio: 0.2,\n          slideToClickedSlide: true,\n          loop: $loop,\n          slidesPerGroup: 1,\n          // loopedSlides: $items,\n          slidesPerView: 3\n        }).then(function ($paginationGallerySlider) {\n          eaelWooProductCarousel.controller.control = $paginationGallerySlider;\n          $paginationGallerySlider.controller.control = eaelWooProductCarousel;\n        });\n      }\n    });\n    ea.hooks.doAction(\"quickViewPopupViewInit\", $scope, $);\n    if (isEditMode) {\n      $(\".eael-product-image-wrap .woocommerce-product-gallery\").css(\"opacity\", \"1\");\n    }\n    var eael_popup = $(document).find(\".eael-woocommerce-popup-view\");\n    if (eael_popup.length < 1) {\n      eael_add_popup();\n    }\n    function eael_add_popup() {\n      var markup = \"<div style=\\\"display: none\\\" class=\\\"eael-woocommerce-popup-view eael-product-popup eael-product-zoom-in woocommerce\\\">\\n                    <div class=\\\"eael-product-modal-bg\\\"></div>\\n                    <div class=\\\"eael-popup-details-render eael-woo-slider-popup\\\"><div class=\\\"eael-preloader\\\"></div></div>\\n                </div>\";\n      $(\"body\").append(markup);\n    }\n    var WooProductCarouselLoader = function WooProductCarouselLoader($src) {\n      var productCarousels = $($src).find('.eael-woo-product-carousel');\n      if (productCarousels.length) {\n        productCarousels.each(function () {\n          if ($(this)[0].swiper) {\n            $(this)[0].swiper.destroy(true, true);\n            swiperLoader($(this)[0], $carouselOptions);\n          }\n        });\n      }\n    };\n    ea.hooks.addAction(\"ea-lightbox-triggered\", \"ea\", WooProductCarouselLoader);\n    ea.hooks.addAction(\"ea-toggle-triggered\", \"ea\", WooProductCarouselLoader);\n  };\n  var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {\n    if ('undefined' === typeof Swiper || 'function' === typeof Swiper) {\n      var asyncSwiper = elementorFrontend.utils.swiper;\n      return new asyncSwiper(swiperElement, swiperConfig).then(function (newSwiperInstance) {\n        return newSwiperInstance;\n      });\n    } else {\n      return swiperPromise(swiperElement, swiperConfig);\n    }\n  };\n  var swiperPromise = function swiperPromise(swiperElement, swiperConfig) {\n    return new Promise(function (resolve, reject) {\n      var swiperInstance = new Swiper(swiperElement, swiperConfig);\n      resolve(swiperInstance);\n    });\n  };\n  if (ea.elementStatusCheck('eaelWooProductSliderLoad')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-woo-product-carousel.default\", wooProductCarousel);\n});\n\n//# sourceURL=webpack:///./src/js/view/woo-product-carousel.js?");

/***/ })

/******/ });