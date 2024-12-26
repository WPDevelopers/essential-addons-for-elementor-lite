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

eval("function _typeof(o) { \"@babel/helpers - typeof\"; return _typeof = \"function\" == typeof Symbol && \"symbol\" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && \"function\" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? \"symbol\" : typeof o; }, _typeof(o); }\nfunction ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }\nfunction _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }\nfunction _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }\nfunction _toPropertyKey(t) { var i = _toPrimitive(t, \"string\"); return \"symbol\" == _typeof(i) ? i : i + \"\"; }\nfunction _toPrimitive(t, r) { if (\"object\" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || \"default\"); if (\"object\" != _typeof(i)) return i; throw new TypeError(\"@@toPrimitive must return a primitive value.\"); } return (\"string\" === r ? String : Number)(t); }\nvar WooProdectImage = function WooProdectImage($scope, $) {\n  var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {\n    if (\"undefined\" === typeof Swiper || \"function\" === typeof Swiper) {\n      var asyncSwiper = elementorFrontend.utils.swiper;\n      return new asyncSwiper(swiperElement, swiperConfig).then(function (newSwiperInstance) {\n        return newSwiperInstance;\n      });\n    } else {\n      return swiperPromise(swiperElement, swiperConfig);\n    }\n  };\n  var swiperPromise = function swiperPromise(swiperElement, swiperConfig) {\n    return new Promise(function (resolve, reject) {\n      var swiperInstance = new Swiper(swiperElement, swiperConfig);\n      resolve(swiperInstance);\n    });\n  };\n\n  // Get unique ID for the slider\n  var sliderId = $scope.data(\"id\"); // Ensure your Elementor widget has a unique data attribute like data-id\n  var sliderThumbSelector = \"#slider-container-\".concat(sliderId, \" .product_image_slider__thumbs .swiper-container\");\n  var sliderImageSelector = \"#slider-container-\".concat(sliderId, \" .product_image_slider__container .swiper-container\");\n\n  // Thumb slider options\n  var $sliderThumbsOptions = $scope.find(\".product_image_slider__thumbs\");\n  var $sliderThumbs = $sliderThumbsOptions.data(\"pi_thumb\");\n\n  // Image slider options\n  var $sliderImagesOptions = $scope.find(\".product_image_slider__container\");\n  var $sliderImagesData = $sliderImagesOptions.data(\"pi_image\");\n\n  // Set slider height dynamically\n  $(window).on(\"load\", function () {\n    var $getImageHeight = $scope.find(\".image_slider__image\").height();\n    $scope.find(\".eael-pi-thumb-left .product_image_slider .product_image_slider__thumbs, .eael-pi-thumb-right .product_image_slider .product_image_slider__thumbs\").css(\"height\", $getImageHeight);\n  });\n\n  // Load the thumbs Swiper first\n  var sliderThumbsObj = swiperLoader($(sliderThumbSelector), $sliderThumbs);\n  sliderThumbsObj.then(function (swiperInstance) {\n    var $sliderImages = _objectSpread(_objectSpread({}, $sliderImagesData), $sliderThumbs.thumbnail === \"yes\" && {\n      thumbs: {\n        swiper: swiperInstance\n      }\n    });\n\n    // Initialize the main slider after setting the thumbs swiper\n    swiperLoader($(sliderImageSelector), $sliderImages).then(function (mainSwiperInstance) {})[\"catch\"](function (error) {\n      console.log(\"Error initializing main Swiper:\", error);\n    });\n  })[\"catch\"](function (error) {\n    console.log(\"Error initializing Swiper thumbs:\", error);\n  });\n\n  // Magnific Popup for the specific slider\n  $scope.find(\".product_image_slider__trigger a\").on(\"click\", function (e) {\n    e.preventDefault();\n    var items = [];\n    $scope.find(\".swiper-slide .image_slider__image img\").each(function (index) {\n      items.push({\n        src: $(this).attr(\"src\")\n      });\n    });\n    $.magnificPopup.open({\n      items: items,\n      mainClass: \"eael-pi\",\n      gallery: {\n        enabled: true\n      },\n      type: \"image\"\n    });\n  });\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-woo-product-images.default\", WooProdectImage);\n});\n\n//# sourceURL=webpack:///./src/js/view/woo-product-image.js?");

/***/ })

/******/ });