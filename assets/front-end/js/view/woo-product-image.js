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

eval("function _typeof(o) { \"@babel/helpers - typeof\"; return _typeof = \"function\" == typeof Symbol && \"symbol\" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && \"function\" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? \"symbol\" : typeof o; }, _typeof(o); }\nfunction ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }\nfunction _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }\nfunction _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }\nfunction _toPropertyKey(t) { var i = _toPrimitive(t, \"string\"); return \"symbol\" == _typeof(i) ? i : i + \"\"; }\nfunction _toPrimitive(t, r) { if (\"object\" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || \"default\"); if (\"object\" != _typeof(i)) return i; throw new TypeError(\"@@toPrimitive must return a primitive value.\"); } return (\"string\" === r ? String : Number)(t); }\nvar WooProdectImage = function WooProdectImage($scope, $) {\n  //Select key elements\n  var $productGallery = $(\".eael-single-product-images\");\n  var $sliderThumbsOptions = $(\".product_image_slider__thumbs\", $scope);\n  var $sliderThumbs = $sliderThumbsOptions.data(\"pi_thumb\");\n\n  //Find initial images\n  var $productGalleryImage = $(\".swiper-wrapper .swiper-slide:first-child .image_slider__image > img\", $productGallery);\n  var $productThumbImage = $(\".swiper-wrapper .swiper-slide:first-child .product_image_slider__thumbs__image > img\", $productGallery);\n\n  //Store original image attributes\n  var originalImage = getImageAttributes($productGalleryImage);\n  var originalThumbImage = getImageAttributes($productThumbImage);\n\n  //Helper function to get image attributes\n  function getImageAttributes($image) {\n    return {\n      src: $image.attr(\"src\"),\n      srcset: $image.attr(\"srcset\"),\n      sizes: $image.attr(\"sizes\")\n    };\n  }\n\n  // Listen for show variation event\n  $(\".variations_form\").on(\"show_variation\", handleShowVariation);\n\n  //Event handler for showing variation\n  function handleShowVariation(event, variation) {\n    var _variation$image;\n    if (variation !== null && variation !== void 0 && (_variation$image = variation.image) !== null && _variation$image !== void 0 && _variation$image.src) {\n      updateProductImage(variation.image);\n      stopSliders();\n    }\n  }\n\n  //Stop sliders\n  function stopSliders() {\n    toggleSliderAutoplay(\"stop\");\n  }\n\n  //Toggle slider autoplay\n  function toggleSliderAutoplay(action) {\n    var sliders = $(\".swiper-container\", $scope);\n    sliders.each(function (index, slider) {\n      slider.swiper.autoplay[action]();\n      slider.swiper.slideTo(0);\n    });\n  }\n\n  //Update product iamges with variation images\n  function updateProductImage(variationImage) {\n    setImageAttributes($productGalleryImage, variationImage);\n    setThumbImageAttributes($productThumbImage, variationImage);\n\n    // Re-initialize zoom lens for the updated image\n    setTimeout(function () {\n      initializeZoomLens($productGalleryImage);\n    }, 100); // Small delay to ensure image attributes are updated\n  }\n\n  //Set image attributes\n  function setImageAttributes($image, imageAttributes) {\n    $image.attr(\"src\", imageAttributes.src).attr(\"srcset\", imageAttributes.srcset).attr(\"sizes\", imageAttributes.sizes).attr(\"data-src\", imageAttributes.src).attr(\"data-large_image\", imageAttributes.full_src);\n  }\n\n  //Set thumb image attributes\n  function setThumbImageAttributes($image, imageAttributes) {\n    $image.attr(\"src\", imageAttributes.gallery_thumbnail_src).attr(\"srcset\", imageAttributes.gallery_thumbnail_src).attr(\"sizes\", imageAttributes.gallery_thumbnail_src_h);\n  }\n\n  // Listen for hide variation or reset image event\n  $(\".variations_form\").on(\"hide_variation reset_image\", handleResetVariation);\n\n  //Event handler for reseting variation\n  function handleResetVariation() {\n    resetProductImages();\n    resumeSliders();\n  }\n\n  //Resume sliders\n  function resumeSliders() {\n    if ($sliderThumbs.autoplay !== undefined) {\n      toggleSliderAutoplay(\"start\");\n    }\n  }\n\n  //Reset product image to original state\n  function resetProductImages() {\n    resetProductImage($productGalleryImage, originalImage);\n    resetProductImage($productThumbImage, originalThumbImage);\n  }\n\n  //Reset a single image with fade effect\n  function resetProductImage($image, originalAttributes) {\n    $image.fadeOut(100, function () {\n      $image.attr(\"src\", originalAttributes.src).attr(\"srcset\", originalAttributes.srcset).attr(\"sizes\", originalAttributes.sizes).removeAttr(\"data-src\").removeAttr(\"data-large_image\");\n      $image.fadeIn(100, function () {\n        // Re-initialize zoom lens after image is reset and visible\n        if ($image.hasClass('image_slider__image')) {\n          setTimeout(function () {\n            initializeZoomLens($image);\n          }, 50);\n        }\n      });\n    });\n  }\n  var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {\n    if (\"undefined\" === typeof Swiper || \"function\" === typeof Swiper) {\n      var asyncSwiper = elementorFrontend.utils.swiper;\n      return new asyncSwiper(swiperElement, swiperConfig).then(function (newSwiperInstance) {\n        return newSwiperInstance;\n      });\n    } else {\n      return swiperPromise(swiperElement, swiperConfig);\n    }\n  };\n  var swiperPromise = function swiperPromise(swiperElement, swiperConfig) {\n    return new Promise(function (resolve, reject) {\n      var swiperInstance = new Swiper(swiperElement, swiperConfig);\n      resolve(swiperInstance);\n    });\n  };\n\n  // Get unique ID for the slider\n  var sliderId = $scope.data(\"id\"); // Ensure your Elementor widget has a unique data attribute like data-id\n  var sliderThumbSelector = \"#slider-container-\".concat(sliderId, \" .product_image_slider__thumbs .swiper-container\");\n  var sliderImageSelector = \"#slider-container-\".concat(sliderId, \" .product_image_slider__container .swiper-container\");\n\n  // Thumb slider options\n  // let $sliderThumbsOptions = $(\".product_image_slider__thumbs\", $scope);\n  // let $sliderThumbs = $sliderThumbsOptions.data(\"pi_thumb\");\n  var $height_for_mobile = $sliderThumbsOptions.data(\"for_mobile\");\n\n  // console.log(\"Item\", $sliderThumbs);\n\n  // Image slider options\n  var $sliderImagesOptions = $(\".product_image_slider__container\", $scope);\n  var $sliderImagesData = $sliderImagesOptions.data(\"pi_image\");\n\n  // Set slider height dynamically\n  $(window).on(\"load\", function () {\n    // Check if the screen width is less than or equal to 767px\n    if (window.matchMedia(\"(max-width: 767px)\").matches) {\n      // For small screens\n      var getImageHeight = $(\".image_slider__image\", $scope).height();\n      var newThumbHeight = $sliderThumbs.slidesPerView * $height_for_mobile;\n      var compareHeight = Math.min(newThumbHeight, getImageHeight);\n      $(\".eael-pi-thumb-left .product_image_slider .product_image_slider__thumbs, .eael-pi-thumb-right .product_image_slider .product_image_slider__thumbs\", $scope).css(\"height\", compareHeight);\n      $scope.find(\".eael-pi-thumb-bottom .product_image_slider .product_image_slider__thumbs\").css(\"width\", compareHeight);\n    } else {\n      // For larger screens\n      var _getImageHeight = $(\".image_slider__image\", $scope).height();\n      var _newThumbHeight = $sliderThumbs.slidesPerView * 100;\n      var _compareHeight = Math.min(_newThumbHeight, _getImageHeight);\n      $(\".eael-pi-thumb-left .product_image_slider .product_image_slider__thumbs, .eael-pi-thumb-right .product_image_slider .product_image_slider__thumbs\", $scope).css(\"height\", _compareHeight);\n    }\n  });\n\n  // Load the thumbs Swiper first\n  var sliderThumbsObj = swiperLoader($(sliderThumbSelector), $sliderThumbs);\n  sliderThumbsObj.then(function (swiperInstance) {\n    var $sliderImages = _objectSpread(_objectSpread({}, $sliderImagesData), $sliderThumbs.thumbnail === \"yes\" && {\n      thumbs: {\n        swiper: swiperInstance\n      }\n    });\n\n    // Initialize the main slider after setting the thumbs swiper\n    swiperLoader($(sliderImageSelector), $sliderImages).then(function (mainSwiperInstance) {\n      // Initialize zoom lens for the active slide when swiper changes\n      if (mainSwiperInstance) {\n        mainSwiperInstance.on('slideChange', function () {\n          setTimeout(function () {\n            var $activeSlide = $(mainSwiperInstance.slides[mainSwiperInstance.activeIndex]);\n            var $activeImg = $activeSlide.find('.image_slider__image img');\n            if ($activeImg.length) {\n              initializeZoomLens($activeImg);\n            }\n          }, 100);\n        });\n      }\n    })[\"catch\"](function (error) {\n      console.log(\"Error initializing main Swiper:\", error);\n    });\n  })[\"catch\"](function (error) {\n    console.log(\"Error initializing Swiper thumbs:\", error);\n  });\n\n  // Magnific Popup for the specific slider\n  $(\".product_image_slider__trigger a\", $scope).on(\"click\", function (e) {\n    e.preventDefault();\n    var items = [];\n    $scope.find(\".swiper-slide .image_slider__image img\").each(function (index) {\n      items.push({\n        src: $(this).attr(\"src\")\n      });\n    });\n    $.magnificPopup.open({\n      items: items,\n      mainClass: \"eael-pi\",\n      gallery: {\n        enabled: true\n      },\n      type: \"image\"\n    });\n  });\n  function zoomLenseEffect() {\n    var zoomOptions = {\n      lensWidth: (zoomEffect === null || zoomEffect === void 0 ? void 0 : zoomEffect.lensSize) || 100,\n      lensHeight: (zoomEffect === null || zoomEffect === void 0 ? void 0 : zoomEffect.lensSize) || 100,\n      borderRadius: (zoomEffect === null || zoomEffect === void 0 ? void 0 : zoomEffect.lensBorderRadius) || '8px',\n      lensBorder: zoomEffect === null || zoomEffect === void 0 ? void 0 : zoomEffect.lensBorder,\n      autoResize: true\n    };\n\n    // Initialize zoom lens for image(s)\n    function initializeZoomLens($images) {\n      if (!$images) {\n        $images = $(\".image_slider__image img\", $scope);\n      }\n      $images.each(function () {\n        var $img = $(this);\n\n        // Clean up existing zoom lens\n        $img.off('.zoom');\n        $('.eael-lens-zoom, .eael-result-zoom').remove();\n\n        // Initialize when image is ready\n        if (this.complete && this.naturalHeight !== 0) {\n          $img.eaelZoomLense(zoomOptions);\n        } else {\n          $img.on('load.zoom', function () {\n            $(this).eaelZoomLense(zoomOptions);\n          });\n        }\n      });\n    }\n\n    // Initialize zoom lens with multiple fallbacks\n    function setupZoomLens() {\n      // Try immediate initialization\n      initializeZoomLens();\n\n      // Fallback for window load\n      $(window).on('load', function () {\n        initializeZoomLens();\n      });\n\n      // Use imagesLoaded if available\n      if (typeof $.fn.imagesLoaded === 'function') {\n        $(\".image_slider__image img\", $scope).imagesLoaded(function () {\n          initializeZoomLens();\n        });\n      }\n    }\n\n    // Setup zoom lens\n    setupZoomLens();\n  }\n  function magnifyEffect() {\n    $(\".image_slider__image img\", $scope).eaelMagnify({\n      lensSize: (zoomEffect === null || zoomEffect === void 0 ? void 0 : zoomEffect.lensSize) || 200,\n      lensBorder: zoomEffect === null || zoomEffect === void 0 ? void 0 : zoomEffect.lensBorder\n    });\n  }\n  function zoomInsideEffect() {}\n  var zoomEffect = $sliderImagesData.zoomEffect;\n  if (window.isEditMode) {\n    $('.eael-magnify-lens').remove();\n  }\n  if ((zoomEffect === null || zoomEffect === void 0 ? void 0 : zoomEffect.enabled) === 'yes') {\n    // Image Zoom Lens Configuration\n    if ('lense' === (zoomEffect === null || zoomEffect === void 0 ? void 0 : zoomEffect.type)) {\n      zoomLenseEffect();\n    } else if ('magnify' === (zoomEffect === null || zoomEffect === void 0 ? void 0 : zoomEffect.type)) {\n      magnifyEffect();\n    } else if ('inside' === (zoomEffect === null || zoomEffect === void 0 ? void 0 : zoomEffect.type)) {\n      zoomInsideEffect();\n    }\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-woo-product-images.default\", WooProdectImage);\n});\n\n//# sourceURL=webpack:///./src/js/view/woo-product-image.js?");

/***/ })

/******/ });