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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/edit/eael-url-search.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/edit/eael-url-search.js":
/*!****************************************!*\
  !*** ./src/js/edit/eael-url-search.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\n * EAEL URL Search — Elementor editor script\n *\n * Upgrades every `.eael-url-ajax-search` <select> (rendered by the EAEL_URL\n * control's content_template) into a Select2 AJAX dropdown.\n *\n * Flow:\n *   1. `content_template()` fires `eael_url_search_init` on document.body and\n *      a MutationObserver watches the panel for newly inserted wrappers.\n *   2. `initSearch()` calls Select2 with an AJAX data source that calls the\n *      `eael_lr_search_redirect_post` action (≥3 chars threshold).\n *   3. On selection the permalink (`e.params.data.id`) is written into the\n *      sibling `[data-setting=\"url\"]` input and Elementor's `input` event is\n *      dispatched so the model is dirtied and saved on the next save.\n */\n(function ($) {\n  'use strict';\n\n  if (typeof eaelURLSearch === 'undefined') {\n    return;\n  }\n\n  // WeakSet so we never double-initialise a wrapper element.\n  var _initialized = typeof WeakSet !== 'undefined' ? new WeakSet() : null;\n  function isInitialized(el) {\n    return _initialized ? _initialized.has(el) : $(el).data('eaelUrlReady');\n  }\n  function markInitialized(el) {\n    if (_initialized) {\n      _initialized.add(el);\n    } else {\n      $(el).data('eaelUrlReady', true);\n    }\n  }\n\n  /**\n   * Boot Select2 on a single `.eael-url-search-wrap` element.\n   *\n   * @param {jQuery} $wrap  The `.eael-url-search-wrap` wrapper div.\n   */\n  function initSearch($wrap) {\n    var el = $wrap[0];\n    if (!el || isInitialized(el)) {\n      return;\n    }\n    markInitialized(el);\n\n    // ── DOM refs ──────────────────────────────────────────────────────────\n    var $select = $wrap.find('.eael-url-ajax-search');\n    // The URL text input lives in a sibling of .eael-url-search-wrap inside\n    // the same .elementor-control-field wrapper.\n    var $urlInput = $wrap.closest('.elementor-control-field').find('[data-setting=\"url\"]');\n    if (!$select.length || !$urlInput.length) {\n      return;\n    }\n\n    // ── post_types from data attribute ────────────────────────────────────\n    var postTypes;\n    try {\n      postTypes = JSON.parse($wrap.attr('data-post-types') || '[]');\n    } catch (e) {\n      postTypes = ['page', 'post', 'product'];\n    }\n    if (!postTypes.length) {\n      postTypes = ['page', 'post', 'product'];\n    }\n\n    // ── Pre-populate with the already-saved URL ───────────────────────────\n    var currentUrl = $urlInput.val();\n    if (currentUrl) {\n      $select.append(new Option(currentUrl, currentUrl, true, true));\n    }\n\n    // ── Determine best dropdownParent ─────────────────────────────────────\n    // Elementor's panel scrolls inside its own container; anchoring the\n    // dropdown there prevents overflow clipping.\n    var $panelContent = $wrap.closest('.elementor-panel-content-wrapper');\n    var dropdownParent = $panelContent.length ? $panelContent : $('body');\n\n    // ── Initialise Select2 ────────────────────────────────────────────────\n    $select.select2({\n      dropdownParent: dropdownParent,\n      minimumInputLength: parseInt(eaelURLSearch.minChars, 10) || 3,\n      placeholder: eaelURLSearch.placeholder,\n      allowClear: true,\n      language: {\n        inputTooShort: function inputTooShort() {\n          return eaelURLSearch.placeholder;\n        },\n        noResults: function noResults() {\n          return 'No results found';\n        }\n      },\n      ajax: {\n        url: eaelURLSearch.ajaxUrl,\n        type: 'POST',\n        dataType: 'json',\n        delay: 350,\n        data: function data(params) {\n          return {\n            action: 'eael_lr_search_redirect_post',\n            nonce: eaelURLSearch.nonce,\n            search: params.term,\n            post_types: postTypes\n          };\n        },\n        processResults: function processResults(data) {\n          // Handler returns a flat [ {id, text}, … ] array.\n          return {\n            results: Array.isArray(data) ? data : []\n          };\n        },\n        cache: true\n      }\n    });\n\n    // ── Sync Select2 → URL input ──────────────────────────────────────────\n    $select.on('select2:select', function (e) {\n      var url = e.params.data.id; // AJAX handler sets id = permalink\n      $urlInput.val(url).trigger('input');\n      updateHint($wrap, url);\n    });\n    $select.on('select2:clear', function () {\n      $urlInput.val('').trigger('input');\n      updateHint($wrap, '');\n    });\n\n    // ── Keep hint in sync if the admin types in the URL input directly ────\n    $urlInput.on('input.eaelurl', function () {\n      updateHint($wrap, $(this).val());\n    });\n\n    // Show the hint for whatever value is already there.\n    updateHint($wrap, currentUrl);\n  }\n\n  /**\n   * Update the informational hint below the <select>.\n   *\n   * @param {jQuery} $wrap\n   * @param {string} url\n   */\n  function updateHint($wrap, url) {\n    var $hint = $wrap.find('.eael-url-search-hint');\n    if (!$hint.length) {\n      return;\n    }\n    if (url) {\n      $hint.html('<strong>Selected:</strong> ' + '<a href=\"' + escapeHtml(url) + '\" target=\"_blank\" rel=\"noopener noreferrer\" ' + 'style=\"word-break:break-all;\">' + escapeHtml(url) + '</a>');\n    } else {\n      $hint.text(\"Type \\u2265 3 characters to search posts, pages and products \\u2014 or type a URL directly below.\");\n    }\n  }\n\n  /** Minimal HTML escape helper (avoids a jQuery round-trip). */\n  function escapeHtml(str) {\n    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\"/g, '&quot;');\n  }\n\n  // ── Trigger-based initialisation (from content_template body trigger) ─────\n  $(document.body).on('eael_url_search_init', function () {\n    // Slight delay so the template has finished rendering its DOM.\n    setTimeout(function () {\n      $('.eael-url-search-wrap').each(function () {\n        initSearch($(this));\n      });\n    }, 80);\n  });\n\n  // ── MutationObserver: catch lazy-rendered / section-toggled instances ─────\n  var _observer = new MutationObserver(function (mutations) {\n    mutations.forEach(function (mutation) {\n      mutation.addedNodes.forEach(function (node) {\n        if (node.nodeType !== 1 /* ELEMENT_NODE */) {\n          return;\n        }\n        var $node = $(node);\n        if ($node.hasClass('eael-url-search-wrap')) {\n          initSearch($node);\n        }\n        $node.find('.eael-url-search-wrap').each(function () {\n          initSearch($(this));\n        });\n      });\n    });\n  });\n\n  /**\n   * Attach the observer to the Elementor panel once it exists.\n   * The panel is a persistent DOM element so we only need to observe it once.\n   */\n  function attachObserver() {\n    var panelEl = document.getElementById('elementor-panel');\n    if (panelEl) {\n      _observer.observe(panelEl, {\n        childList: true,\n        subtree: true\n      });\n    }\n  }\n\n  // Elementor may already be ready, or we may be loading before it is.\n  if (typeof elementor !== 'undefined' && elementor.initialized) {\n    attachObserver();\n  } else {\n    $(window).on('elementor:init', attachObserver);\n    // Belt-and-suspenders: try after a short delay.\n    setTimeout(attachObserver, 1500);\n  }\n})(jQuery);\n\n//# sourceURL=webpack:///./src/js/edit/eael-url-search.js?");

/***/ })

/******/ });