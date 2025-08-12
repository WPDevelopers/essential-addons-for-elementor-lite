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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/load-more.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/load-more.js":
/*!**********************************!*\
  !*** ./src/js/view/load-more.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function ($) {\n  \"use strict\";\n\n  eael.getToken();\n  $(document).on(\"click\", \".eael-load-more-button\", function (e) {\n    e.preventDefault();\n    e.stopPropagation();\n    e.stopImmediatePropagation();\n    var $this = $(this),\n      $LoaderSpan = $(\".eael_load_more_text\", $this),\n      $text = $LoaderSpan.html(),\n      $widget_id = $this.data(\"widget\"),\n      $page_id = $this.data(\"page-id\"),\n      $nonce = localize.nonce,\n      $scope = $(\".elementor-element-\" + $widget_id),\n      $class = $this.data(\"class\"),\n      $args = $this.data(\"args\"),\n      $layout = $this.data(\"layout\"),\n      $template_info = $this.data(\"template\"),\n      $page = parseInt($this.data(\"page\")) + 1,\n      $max_page = $this.data(\"max-page\") != undefined ? parseInt($this.data(\"max-page\")) : false,\n      $exclude_ids = [],\n      $active_term_id = 0,\n      $active_taxonomy = '';\n    $this.attr('disabled', true);\n    if (typeof $widget_id == \"undefined\" || typeof $args == \"undefined\") {\n      return;\n    }\n    var obj = {};\n    var $data = {\n      action: \"load_more\",\n      \"class\": $class,\n      args: $args,\n      page: $page,\n      page_id: $page_id,\n      widget_id: $widget_id,\n      nonce: $nonce,\n      template_info: $template_info\n    };\n    if ($data[\"class\"] == \"Essential_Addons_Elementor\\\\Elements\\\\Woo_Product_Gallery\") {\n      var $taxonomy = {\n        taxonomy: $('.eael-cat-tab li a.active', $scope).data('taxonomy'),\n        field: 'term_id',\n        terms: $('.eael-cat-tab li a.active', $scope).data('id'),\n        terms_tag: $('.eael-cat-tab li a.active', $scope).data('tagid')\n      };\n      var eael_cat_tab = localStorage.getItem('eael-cat-tab');\n      if (eael_cat_tab == 'true') {\n        localStorage.removeItem('eael-cat-tab');\n        var $gallery_page = 1 + 1;\n      } else {\n        var active_tab = $('.eael-cat-tab li a.active', $scope);\n        var paging = parseInt(active_tab.data(\"page\"));\n        if (isNaN(paging)) {\n          if (active_tab.length > 0) {\n            paging = 1;\n            active_tab.data(\"page\", 1);\n          } else {\n            var load_more_btn = $('.eael-load-more-button', $scope);\n            var paging = parseInt(load_more_btn.data(\"page\"));\n            if (isNaN(paging)) {\n              paging = 1;\n            }\n          }\n        }\n        var $gallery_page = paging + 1;\n      }\n      $data.taxonomy = $taxonomy;\n      $data.page = isNaN($gallery_page) ? $page : $gallery_page;\n    }\n    if ($data[\"class\"] === \"Essential_Addons_Elementor\\\\Pro\\\\Elements\\\\Dynamic_Filterable_Gallery\") {\n      $('.dynamic-gallery-item-inner', $scope).each(function () {\n        $exclude_ids.push($(this).data('itemid'));\n      });\n      $active_term_id = $(\".elementor-element-\" + $widget_id + ' .dynamic-gallery-category.active').data('termid');\n      $active_taxonomy = $(\".elementor-element-\" + $widget_id + ' .dynamic-gallery-category.active').data('taxonomy');\n      $data.page = 1; //page flag is not needed since we are using exclude ids\n      $data.exclude_ids = JSON.stringify($exclude_ids);\n      $data.active_term_id = typeof $active_term_id === 'undefined' ? 0 : $active_term_id;\n      $data.active_taxonomy = typeof $active_taxonomy === 'undefined' ? '' : $active_taxonomy;\n    }\n    String($args).split(\"&\").forEach(function (item, index) {\n      var arr = String(item).split(\"=\");\n      obj[arr[0]] = arr[1];\n    });\n    if (obj.orderby == \"rand\") {\n      var $printed = $(\".eael-grid-post\");\n      if ($printed.length) {\n        var $ids = [];\n        $printed.each(function (index, item) {\n          var $id = $(item).data(\"id\");\n          $ids.push($id);\n        });\n        $data.post__not_in = $ids;\n      }\n    }\n    $this.addClass(\"button--loading\");\n    $LoaderSpan.html(localize.i18n.loading);\n    var filterable_gallery_load_more_btn = function filterable_gallery_load_more_btn($this) {\n      var active_tab = $this.closest('.eael-filter-gallery-wrapper').find('.dynamic-gallery-category.active'),\n        active_filter = active_tab.data('filter'),\n        rest_filter = active_tab.siblings().not('.no-more-posts');\n      $this.addClass('hide');\n      active_tab.addClass('no-more-posts');\n      if (rest_filter.length === 1 && rest_filter.data('filter') === '*') {\n        rest_filter.addClass('no-more-posts');\n      }\n      if (active_filter === '*') {\n        active_tab.siblings().addClass('no-more-posts');\n      }\n    };\n    $.ajax({\n      url: localize.ajaxurl,\n      type: \"post\",\n      data: $data,\n      success: function success(response) {\n        var $content = $(response);\n        $this.removeAttr('disabled');\n        if ($content.hasClass(\"no-posts-found\") || $content.length === 0) {\n          if ($data[\"class\"] == \"Essential_Addons_Elementor\\\\Elements\\\\Woo_Product_Gallery\") {\n            $this.removeClass('button--loading').addClass('hide-load-more');\n            $LoaderSpan.html($text);\n            if ($this.parent().hasClass('eael-infinity-scroll')) {\n              $this.parent().remove();\n            }\n          } else if ($data[\"class\"] == \"Essential_Addons_Elementor\\\\Pro\\\\Elements\\\\Dynamic_Filterable_Gallery\") {\n            $this.removeClass('button--loading');\n            $LoaderSpan.html($text);\n            filterable_gallery_load_more_btn($this);\n          } else {\n            $this.remove();\n          }\n        } else {\n          if ($data[\"class\"] == \"Essential_Addons_Elementor\\\\Elements\\\\Product_Grid\") {\n            $content = $content.filter(\"li\");\n            $(\".eael-product-grid .products\", $scope).append($content);\n            if ($layout == \"masonry\") {\n              var dynamicID = \"eael-product-\" + Date.now();\n              var $isotope = $(\".eael-product-grid .products\", $scope).isotope();\n              $isotope.isotope(\"appended\", $content).isotope(\"layout\");\n              $isotope.imagesLoaded().progress(function () {\n                $isotope.isotope(\"layout\");\n              });\n              $content.find(\".woocommerce-product-gallery\").addClass(dynamicID);\n              $content.find(\".woocommerce-product-gallery\").addClass(\"eael-new-product\");\n              $(\".woocommerce-product-gallery.\" + dynamicID, $scope).each(function () {\n                $(this).wc_product_gallery();\n              });\n            } else {\n              var _dynamicID = \"eael-product-\" + Date.now();\n              $content.find('.woocommerce-product-gallery').addClass(_dynamicID);\n              $content.find('.woocommerce-product-gallery').addClass('eael-new-product');\n              $(\".woocommerce-product-gallery.\" + _dynamicID, $scope).each(function () {\n                $(this).wc_product_gallery();\n              });\n            }\n            if ($page >= $max_page) {\n              $this.remove();\n            }\n          } else {\n            $(\".eael-post-appender\", $scope).append($content);\n            if ($layout == \"masonry\") {\n              var $isotope = $(\".eael-post-appender\", $scope).isotope();\n              $isotope.isotope(\"appended\", $content).isotope(\"layout\");\n              $isotope.imagesLoaded().progress(function () {\n                $isotope.isotope(\"layout\");\n              });\n            }\n          }\n          $this.removeClass(\"button--loading\");\n          $LoaderSpan.html($text);\n          if ($data[\"class\"] == \"Essential_Addons_Elementor\\\\Elements\\\\Woo_Product_Gallery\" && $('.eael-cat-tab li a.active', $scope).length) {\n            $('.eael-cat-tab li a.active', $scope).data(\"page\", $gallery_page);\n          } else {\n            $this.data(\"page\", $page);\n          }\n          if ($data[\"class\"] == \"Essential_Addons_Elementor\\\\Pro\\\\Elements\\\\Dynamic_Filterable_Gallery\") {\n            var found_posts = $($content[0]);\n            if (found_posts.hasClass('found_posts') && found_posts.text() - obj.posts_per_page < 1) {\n              filterable_gallery_load_more_btn($this);\n            }\n          }\n        }\n        if ($max_page && $data.page >= $max_page) {\n          $this.remove();\n        }\n      },\n      error: function error(response) {\n        console.log(response);\n      }\n    });\n  });\n  $(window).on('scroll', function () {\n    var scrollElements = $('.eael-infinity-scroll');\n    if (scrollElements.length < 1) return false;\n    $.each(scrollElements, function (index, element) {\n      var scrollElement = $(element);\n      var offset = scrollElement.data('offset');\n      var elementTop = scrollElement.offset().top;\n      var elementBottom = elementTop + scrollElement.outerHeight() - offset;\n      var viewportTop = $(window).scrollTop();\n      var viewportHalf = viewportTop + $(window).height() - offset;\n      var inView = elementBottom > viewportTop && elementTop < viewportHalf;\n      if (inView) {\n        $(\".eael-load-more-button\", scrollElement).trigger('click');\n      }\n    });\n  });\n})(jQuery);\n\n//# sourceURL=webpack:///./src/js/view/load-more.js?");

/***/ })

/******/ });