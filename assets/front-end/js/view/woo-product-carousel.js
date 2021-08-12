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

eval("ea.hooks.addAction(\"init\", \"ea\", function () {\n  var wooProductCarousel = function wooProductCarousel($scope, $) {\n    ea.hooks.doAction(\"quickViewAddMarkup\", $scope, $);\n    var $wooProductCarousel = $scope.find(\".eael-woo-product-carousel\").eq(0),\n        $type = $wooProductCarousel.data(\"type\"),\n        $autoplay = $wooProductCarousel.data(\"autoplay\") !== undefined ? $wooProductCarousel.data(\"autoplay\") : 999999,\n        $pagination = $wooProductCarousel.data(\"pagination\") !== undefined ? $wooProductCarousel.data(\"pagination\") : \".swiper-pagination\",\n        $arrow_next = $wooProductCarousel.data(\"arrow-next\") !== undefined ? $wooProductCarousel.data(\"arrow-next\") : \".swiper-button-next\",\n        $arrow_prev = $wooProductCarousel.data(\"arrow-prev\") !== undefined ? $wooProductCarousel.data(\"arrow-prev\") : \".swiper-button-prev\",\n        $items = $wooProductCarousel.data(\"items\") !== undefined ? $wooProductCarousel.data(\"items\") : 3,\n        $items_tablet = $wooProductCarousel.data(\"items-tablet\") !== undefined ? $wooProductCarousel.data(\"items-tablet\") : 3,\n        $items_mobile = $wooProductCarousel.data(\"items-mobile\") !== undefined ? $wooProductCarousel.data(\"items-mobile\") : 3,\n        $margin = $wooProductCarousel.data(\"margin\") !== undefined ? $wooProductCarousel.data(\"margin\") : 10,\n        $margin_tablet = $wooProductCarousel.data(\"margin-tablet\") !== undefined ? $wooProductCarousel.data(\"margin-tablet\") : 10,\n        $margin_mobile = $wooProductCarousel.data(\"margin-mobile\") !== undefined ? $wooProductCarousel.data(\"margin-mobile\") : 0,\n        $effect = $wooProductCarousel.data(\"effect\") !== undefined ? $wooProductCarousel.data(\"effect\") : \"slide\",\n        $speed = $wooProductCarousel.data(\"speed\") !== undefined ? $wooProductCarousel.data(\"speed\") : 400,\n        $loop = $wooProductCarousel.data(\"loop\") !== undefined ? $wooProductCarousel.data(\"loop\") : 0,\n        $grab_cursor = $wooProductCarousel.data(\"grab-cursor\") !== undefined ? $wooProductCarousel.data(\"grab-cursor\") : 0,\n        $pause_on_hover = $wooProductCarousel.data(\"pause-on-hover\") !== undefined ? $wooProductCarousel.data(\"pause-on-hover\") : \"\",\n        $centeredSlides = $effect == \"coverflow\" ? true : false,\n        $depth = $wooProductCarousel.data(\"depth\") !== undefined ? $wooProductCarousel.data(\"depth\") : 100,\n        $rotate = $wooProductCarousel.data(\"rotate\") !== undefined ? $wooProductCarousel.data(\"rotate\") : 50,\n        $stretch = $wooProductCarousel.data(\"stretch\") !== undefined ? $wooProductCarousel.data(\"stretch\") : 10;\n    var $carouselOptions = {\n      direction: \"horizontal\",\n      speed: $speed,\n      effect: $effect,\n      centeredSlides: $centeredSlides,\n      grabCursor: $grab_cursor,\n      autoHeight: true,\n      loop: $loop,\n      slidesPerGroup: 1,\n      autoplay: {\n        delay: $autoplay,\n        disableOnInteraction: false\n      },\n      pagination: {\n        el: $pagination,\n        clickable: true\n      },\n      navigation: {\n        nextEl: $arrow_next,\n        prevEl: $arrow_prev\n      },\n      slidesPerView: $items\n    };\n\n    if ($effect === 'slide') {\n      $carouselOptions.breakpoints = {\n        1024: {\n          slidesPerView: $items,\n          spaceBetween: $margin\n        },\n        768: {\n          slidesPerView: $items_tablet,\n          spaceBetween: $margin_tablet\n        },\n        320: {\n          slidesPerView: $items_mobile,\n          spaceBetween: $margin_mobile\n        }\n      };\n    }\n\n    if ($effect === 'coverflow') {\n      // $carouselOptions.slidesPerView = 'auto';\n      $carouselOptions.coverflowEffect = {\n        rotate: $rotate,\n        stretch: $stretch,\n        depth: $depth,\n        modifier: 1,\n        slideShadows: false\n      };\n    }\n\n    swiperLoader($wooProductCarousel, $carouselOptions).then(function (eaelWooProductCarousel) {\n      if ($autoplay === 0) {\n        eaelWooProductCarousel.autoplay.stop();\n      }\n\n      if ($pause_on_hover && $autoplay !== 0) {\n        $wooProductCarousel.on(\"mouseenter\", function () {\n          eaelWooProductCarousel.autoplay.stop();\n        });\n        $wooProductCarousel.on(\"mouseleave\", function () {\n          eaelWooProductCarousel.autoplay.start();\n        });\n      } //gallery pagination\n\n\n      var $paginationGallerySelector = $scope.find('.eael-woo-product-carousel-container .eael-woo-product-carousel-gallary-pagination').eq(0);\n\n      if ($paginationGallerySelector.length > 0) {\n        swiperLoader($paginationGallerySelector, {\n          spaceBetween: 20,\n          centeredSlides: $centeredSlides,\n          touchRatio: 0.2,\n          slideToClickedSlide: true,\n          loop: $loop,\n          slidesPerGroup: 1,\n          // loopedSlides: $items,\n          slidesPerView: 3\n        }).then(function ($paginationGallerySlider) {\n          eaelWooProductCarousel.controller.control = $paginationGallerySlider;\n          $paginationGallerySlider.controller.control = eaelWooProductCarousel;\n        });\n      }\n    }); // Quick view\n    // $scope.on(\"click\", \".open-popup-link\", function (e) {\n    // \te.preventDefault();\n    // \te.stopPropagation();\n    // \tconst $this = $(this);\n    // \tconst quickview_setting = $this.data('quickview-setting');\n    // \tconst popup_view = $(\".eael-woocommerce-popup-view\");\n    // \tpopup_view.find(\".eael-popup-details-render\").html('<div class=\"eael-preloader\"></div>')\n    // \tpopup_view\n    // \t.addClass(\"eael-product-popup-ready\")\n    // \t.removeClass(\"eael-product-modal-removing\");\n    // \tpopup_view.show();\n    // \t$.ajax({\n    // \t\t\t   url: localize.ajaxurl,\n    // \t\t\t   type: \"post\",\n    // \t\t\t   data: {\n    // \t\t\t\t   action: \"eael_product_quickview_popup\",\n    // \t\t\t\t   ...quickview_setting,\n    // \t\t\t\t   security: localize.nonce\n    // \t\t\t   },\n    // \t\t\t   success: function (response) {\n    // \t\t\t\t   if (response.success) {\n    // \t\t\t\t\t   const product_preview = $(response.data);\n    // \t\t\t\t\t   const popup_details = product_preview.children(\".eael-product-popup-details\");\n    //\n    // \t\t\t\t\t   popup_details.find(\".variations_form\").wc_variation_form()\n    // \t\t\t\t\t   const popup_view_render = popup_view.find(\".eael-popup-details-render\");\n    //\n    // \t\t\t\t\t   popup_view.find(\".eael-popup-details-render\").html(popup_details);\n    // \t\t\t\t\t   const product_gallery = popup_view.find(\".woocommerce-product-gallery\");\n    // \t\t\t\t\t   product_gallery.css(\"opacity\",1);\n    // \t\t\t\t\t   popup_view_render.addClass(\"elementor-\" + quickview_setting.page_id)\n    // \t\t\t\t\t   popup_view_render.children().addClass(\"elementor-element elementor-element-\" + quickview_setting.widget_id)\n    //\n    // \t\t\t\t\t   if (popup_details.height() > 400) {\n    // \t\t\t\t\t\t   popup_details.css(\"height\", \"75vh\");\n    // \t\t\t\t\t   } else {\n    // \t\t\t\t\t\t   popup_details.css(\"height\", \"auto\");\n    // \t\t\t\t\t   }\n    // \t\t\t\t\t   setTimeout(function(){ product_gallery.wc_product_gallery(); }, 1000);\n    // \t\t\t\t   }\n    // \t\t\t   },\n    // \t\t   });\n    // });\n    // $(document).on(\n    // \t\"keypress\",\n    // \t\".eael-product-details-wrap input[type=number]\",\n    // \t(e) => {\n    // \t\tlet keyValue = e.keyCode || e.which;\n    // \t\tlet regex = /^[0-9]+$/;\n    // \t\tlet isValid = regex.test(String.fromCharCode(keyValue));\n    // \t\tif (!isValid) {\n    // \t\t\treturn false;\n    // \t\t}\n    // \t\treturn isValid;\n    // \t}\n    // );\n    // handle add to cart for quick view\n    // $(document).on(\n    // \t\"click\",\n    // \t\".eael-woo-slider-popup .single_add_to_cart_button\",\n    // \tfunction (e) {\n    // \t\te.preventDefault();\n    // \t\te.stopImmediatePropagation();\n    // \t\tvar $this = $(this),\n    // \t\t\tproduct_id = $(this).val(),\n    // \t\t\tvariation_id =\n    // \t\t\t\t$this\n    // \t\t\t\t.closest(\"form.cart\")\n    // \t\t\t\t.find('input[name=\"variation_id\"]')\n    // \t\t\t\t.val() || \"\",\n    // \t\t\tquantity = $this\n    // \t\t\t.closest(\"form.cart\")\n    // \t\t\t.find('input[name=\"quantity\"]')\n    // \t\t\t.val(),\n    // \t\t\titems = $this.closest(\"form.cart.grouped_form\"),\n    // \t\t\tform = $this.closest(\"form.cart\"),\n    // \t\t\tproduct_data = [];\n    // \t\titems = items.serializeArray();\n    //\n    // \t\tif (form.hasClass(\"variations_form\")) {\n    // \t\t\tproduct_id = form.find('input[name=\"product_id\"]').val();\n    // \t\t}\n    // \t\tif (items.length > 0) {\n    // \t\t\titems.forEach((item, index) => {\n    // \t\t\t\tvar p_id = parseInt(item.name.replace(/[^\\d.]/g, \"\"), 10);\n    // \t\t\t\tif (\n    // \t\t\t\t\titem.name.indexOf(\"quantity[\") >= 0 &&\n    // \t\t\t\t\titem.value != \"\" &&\n    // \t\t\t\t\tp_id > 0\n    // \t\t\t\t) {\n    // \t\t\t\t\tproduct_data[product_data.length] = {\n    // \t\t\t\t\t\tproduct_id: p_id,\n    // \t\t\t\t\t\tquantity: item.value,\n    // \t\t\t\t\t\tvariation_id: 0,\n    // \t\t\t\t\t};\n    // \t\t\t\t}\n    // \t\t\t});\n    // \t\t} else {\n    // \t\t\tproduct_data[0] = {\n    // \t\t\t\tproduct_id: product_id,\n    // \t\t\t\tquantity: quantity,\n    // \t\t\t\tvariation_id: variation_id,\n    // \t\t\t};\n    // \t\t}\n    // \t\t$this.removeClass(\"eael-addtocart-added\");\n    // \t\t$this.addClass(\"eael-addtocart-loading\");\n    // \t\t$.ajax({\n    // \t\t\t\t   url: localize.ajaxurl,\n    // \t\t\t\t   type: \"post\",\n    // \t\t\t\t   data: {\n    // \t\t\t\t\t   action: \"eael_product_add_to_cart\",\n    // \t\t\t\t\t   product_data: product_data,\n    // \t\t\t\t\t   eael_add_to_cart_nonce: localize.nonce,\n    // \t\t\t\t\t   cart_item_data: form.serializeArray(),\n    // \t\t\t\t   },\n    // \t\t\t\t   success: function (response) {\n    // \t\t\t\t\t   if (response.success) {\n    // \t\t\t\t\t\t   $(document.body).trigger(\"wc_fragment_refresh\");\n    // \t\t\t\t\t\t   $this.removeClass(\"eael-addtocart-loading\");\n    // \t\t\t\t\t\t   $this.addClass(\"eael-addtocart-added\");\n    // \t\t\t\t\t   }\n    // \t\t\t\t   },\n    // \t\t\t   });\n    // \t}\n    // );\n\n    ea.hooks.doAction(\"quickViewPopupViewInit\", $scope, $);\n\n    if (isEditMode) {\n      $(\".eael-product-image-wrap .woocommerce-product-gallery\").css(\"opacity\", \"1\");\n    }\n  };\n\n  var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {\n    if ('undefined' === typeof Swiper) {\n      var asyncSwiper = elementorFrontend.utils.swiper;\n      return new asyncSwiper(swiperElement, swiperConfig).then(function (newSwiperInstance) {\n        return newSwiperInstance;\n      });\n    } else {\n      return swiperPromise(swiperElement, swiperConfig);\n    }\n  };\n\n  var swiperPromise = function swiperPromise(swiperElement, swiperConfig) {\n    return new Promise(function (resolve, reject) {\n      var swiperInstance = new Swiper(swiperElement, swiperConfig);\n      resolve(swiperInstance);\n    });\n  };\n\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-woo-product-carousel.default\", wooProductCarousel);\n});\n\n//# sourceURL=webpack:///./src/js/view/woo-product-carousel.js?");

/***/ })

/******/ });