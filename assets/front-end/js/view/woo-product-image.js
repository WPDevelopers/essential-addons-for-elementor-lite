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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/woo-product-image.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/woo-product-image.js":
/*!******************************************!*\
  !*** ./src/js/view/woo-product-image.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var WooProdectImage = function WooProdectImage($scope, $) {\n  var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {\n    if (\"undefined\" === typeof Swiper || \"function\" === typeof Swiper) {\n      var asyncSwiper = elementorFrontend.utils.swiper;\n      return new asyncSwiper(swiperElement, swiperConfig).then(function (newSwiperInstance) {\n        return newSwiperInstance;\n      });\n    } else {\n      return swiperPromise(swiperElement, swiperConfig);\n    }\n  };\n  var swiperPromise = function swiperPromise(swiperElement, swiperConfig) {\n    return new Promise(function (resolve, reject) {\n      var swiperInstance = new Swiper(swiperElement, swiperConfig);\n      resolve(swiperInstance);\n    });\n  };\n  var sliderThumbs = {\n    // direction: \"vertical\",\n    slidesPerView: 3,\n    spaceBetween: 24,\n    // navigation: {\n    //    nextEl: \".image_slider__next\",\n    //    prevEl: \".image_slider__prev\",\n    // },\n    freeMode: true\n  };\n\n  // Load the thumbs Swiper first\n  var sliderThumbsObj = swiperLoader($(\".product_image_slider__thumbs .swiper-container\"), sliderThumbs);\n  sliderThumbsObj.then(function (swiperInstance) {\n    var sliderImages = {\n      // direction: \"vertical\",\n      // slidesPerView: 1,\n      spaceBetween: 32,\n      // mousewheel: true,\n      navigation: {\n        nextEl: \".product_image_slider__next\",\n        prevEl: \".product_image_slider__prev\"\n      },\n      // grabCursor: true,\n      // loop: true,\n      // autoplay: {\n      //    delay: 500,\n      //    disableOnInteraction: false,\n      // },\n      thumbs: {\n        swiper: swiperInstance\n      }\n    };\n\n    // Initialize the main slider after setting the thumbs swiper\n    swiperLoader($(\".product_image_slider__container .swiper-container\"), sliderImages).then(function (mainSwiperInstance) {\n      // console.log(\n      //    \"Main swiper instance initialized:\",\n      //    mainSwiperInstance\n      // );\n    })[\"catch\"](function (error) {\n      console.log(\"Error initializing main Swiper:\", error);\n    });\n  })[\"catch\"](function (error) {\n    console.log(\"Error initializing Swiper thumbs:\", error);\n  });\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-woo-product-images.default\", WooProdectImage);\n});\n\n//# sourceURL=webpack:///./src/js/view/woo-product-image.js?");

/***/ })

/******/ });