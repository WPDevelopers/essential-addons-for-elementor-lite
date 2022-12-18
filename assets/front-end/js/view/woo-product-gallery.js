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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/woo-product-gallery.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/woo-product-gallery.js":
/*!********************************************!*\
  !*** ./src/js/view/woo-product-gallery.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("ea.hooks.addAction(\"init\", \"ea\", function () {\n  var wooProductGallery = function wooProductGallery($scope, $) {\n    var showSecondaryImageOnHover = $scope.find(\".products.eael-post-appender\").data(\"show-secondary-image\");\n    // category\n    ea.hooks.doAction(\"quickViewAddMarkup\", $scope, $);\n    var $post_cat_wrap = $('.eael-cat-tab', $scope);\n    $('.eael-cat-tab li:first a', $scope).addClass('active');\n    $post_cat_wrap.on('click', 'a', function (e) {\n      e.preventDefault();\n      var $this = $(this);\n      if ($this.hasClass('active')) {\n        return false;\n      }\n      // tab class\n      $('.eael-cat-tab li a', $scope).removeClass('active');\n      $this.addClass('active');\n      localStorage.setItem('eael-cat-tab', 'true');\n      // collect props\n      var $class = $post_cat_wrap.data('class'),\n        $widget_id = $post_cat_wrap.data(\"widget\"),\n        $page_id = $post_cat_wrap.data(\"page-id\"),\n        $nonce = $post_cat_wrap.data(\"nonce\"),\n        $args = $post_cat_wrap.data('args'),\n        $layout = $post_cat_wrap.data('layout'),\n        $widget_class = \".elementor-element-\" + $widget_id,\n        $page = 1,\n        $template_info = $post_cat_wrap.data('template'),\n        $taxonomy = {\n          taxonomy: $('.eael-cat-tab li a.active', $scope).data('taxonomy'),\n          field: 'term_id',\n          terms: $('.eael-cat-tab li a.active', $scope).data('id')\n        };\n\n      // ajax\n      $.ajax({\n        url: localize.ajaxurl,\n        type: 'POST',\n        data: {\n          action: 'eael_product_gallery',\n          \"class\": $class,\n          args: $args,\n          taxonomy: $taxonomy,\n          template_info: $template_info,\n          page: $page,\n          page_id: $page_id,\n          widget_id: $widget_id,\n          nonce: $nonce\n        },\n        beforeSend: function beforeSend() {\n          $($widget_class + ' .woocommerce').addClass(\"eael-product-loader\");\n        },\n        success: function success(response) {\n          var $content = $(response);\n          if ($content.hasClass('no-posts-found') || $content.length == 0) {\n            $('.elementor-element-' + $widget_id + ' .eael-product-gallery .woocommerce' + ' .eael-post-appender').empty().append(\"<h2 class=\\\"eael-product-not-found\\\">No Product Found</h2>\");\n            $('.eael-load-more-button', $scope).addClass('hide-load-more');\n          } else {\n            $('.elementor-element-' + $widget_id + ' .eael-product-gallery .woocommerce' + ' .eael-post-appender').empty().append($content);\n            var $max_page = $('<div>' + response + '</div>').find('.eael-max-page').text();\n            var load_more = $('.eael-load-more-button', $scope);\n            if ($max_page && load_more.data('page') >= $max_page) {\n              load_more.addClass('hide-load-more');\n            } else {\n              load_more.removeClass('hide-load-more');\n            }\n            load_more.data('max-page', $max_page);\n            if ($layout === 'masonry') {\n              var $products = $('.eael-product-gallery .products', $scope);\n              $products.isotope('destroy');\n\n              // init isotope\n              var $isotope_products = $products.isotope({\n                itemSelector: \"li.product\",\n                layoutMode: $layout,\n                percentPosition: true\n              });\n              $isotope_products.imagesLoaded().progress(function () {\n                $isotope_products.isotope('layout');\n              });\n            }\n          }\n        },\n        complete: function complete() {\n          $($widget_class + ' .woocommerce').removeClass(\"eael-product-loader\");\n        },\n        error: function error(response) {\n          console.log(response);\n        }\n      });\n    });\n    ea.hooks.doAction(\"quickViewPopupViewInit\", $scope, $);\n    if (isEditMode) {\n      $(\".eael-product-image-wrap .woocommerce-product-gallery\").css(\"opacity\", \"1\");\n    }\n    var dataSrc = dataSrcHover = srcset = srcsetHover = '';\n    if (showSecondaryImageOnHover) {\n      $(document).on(\"mouseover\", \".eael-product-wrap\", function () {\n        dataSrc = $(this).data(\"src\");\n        dataSrcHover = $(this).data(\"src-hover\");\n        srcset = $(this).find('img').attr('srcset');\n        if (dataSrcHover) {\n          $(this).find('img').attr('srcset-hover', srcset);\n          $(this).find('img').attr('src', dataSrcHover);\n          $(this).find('img').attr('srcset', dataSrcHover);\n        }\n      }).on(\"mouseout\", \".eael-product-wrap\", function () {\n        dataSrc = $(this).data(\"src\");\n        dataSrcHover = $(this).data(\"src-hover\");\n        srcsetHover = $(this).find('img').attr('srcset-hover');\n        if (dataSrcHover) {\n          $(this).find('img').attr('src', dataSrc);\n          $(this).find('img').attr('srcset', srcsetHover);\n          $(this).find('img').attr('srcset-hover', '');\n        }\n      });\n    }\n  };\n  if (ea.elementStatusCheck('productGalleryLoad')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-woo-product-gallery.default\", wooProductGallery);\n});\n\n//# sourceURL=webpack:///./src/js/view/woo-product-gallery.js?");

/***/ })

/******/ });