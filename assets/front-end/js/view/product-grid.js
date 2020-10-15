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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/product-grid.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/product-grid.js":
/*!*************************************!*\
  !*** ./src/js/view/product-grid.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("ea.hooks.addAction(\"init\", \"ea\", function () {\n  var productGrid = function productGrid($scope, $) {\n    var $wrap = $scope.find('#eael-product-grid'); // cache wrapper\n\n    var widgetId = $wrap.data('widget-id');\n    var pageId = $wrap.data('page-id');\n    var nonce = $wrap.data('nonce');\n    var overlay = document.createElement(\"div\");\n    overlay.classList.add('wcpc-overlay');\n    overlay.setAttribute('id', 'wcpc-overlay');\n    var body = document.getElementsByTagName('body')[0];\n    body.appendChild(overlay);\n    var overlayNode = document.getElementById('wcpc-overlay');\n    var $doc = $(document);\n    var modalTemplate = \"\\n        <div class=\\\"eael-wcpc-modal\\\">\\n            <i title=\\\"Close\\\" class=\\\"close-modal far fa-times-circle\\\"></i>\\n            <div class=\\\"modal__content\\\" id=\\\"eael_modal_content\\\">\\n            </div>\\n        </div>\\n        \";\n    $(body).append(modalTemplate);\n    var $modalContentWraper = $('#eael_modal_content');\n    var modal = document.getElementsByClassName('eael-wcpc-modal')[0];\n    var ajaxData = [{\n      name: \"action\",\n      value: 'eael_product_grid'\n    }, {\n      name: \"widget_id\",\n      value: widgetId\n    }, {\n      name: \"page_id\",\n      value: pageId\n    }, {\n      name: \"nonce\",\n      value: nonce\n    }];\n\n    var sendData = function sendData(ajaxData, successCb, errorCb) {\n      $.ajax({\n        url: localize.ajaxurl,\n        type: 'POST',\n        dataType: 'json',\n        data: ajaxData,\n        success: successCb,\n        error: errorCb\n      });\n    };\n\n    $doc.on('click', '.eael-wc-compare', function (e) {\n      e.preventDefault();\n      ajaxData.push({\n        name: 'product_id',\n        value: e.target.dataset.productId\n      });\n      sendData(ajaxData, handleSuccess, handleError); //@TODO; show a loader while fetching the table\n    });\n    $doc.on('click', '.close-modal', function (e) {\n      modal.style.visibility = 'hidden';\n      modal.style.opacity = '0';\n      overlayNode.style.visibility = 'hidden';\n      overlayNode.style.opacity = '0';\n    });\n    $doc.on('click', '.eael-wc-remove', function (e) {\n      e.preventDefault();\n      $(this).prop('disabled', true); // prevent additional ajax request\n\n      var rmData = Array.from(ajaxData);\n      rmData.push({\n        name: 'product_id',\n        value: e.target.dataset.productId\n      });\n      rmData.push({\n        name: 'remove_product',\n        value: 1\n      });\n      sendData(rmData, handleSuccess, handleError); //@TODO; show a loader while updating the table\n    });\n\n    function handleSuccess(data) {\n      var success = data && data.success;\n\n      if (success) {\n        $modalContentWraper.html(data.data.compare_table);\n        modal.style.visibility = 'visible';\n        modal.style.opacity = '1';\n        overlayNode.style.visibility = 'visible';\n        overlayNode.style.opacity = '1';\n      }\n    }\n\n    function handleError(xhr, err) {\n      var errorHtml = \"<p class=\\\"eael-form-msg invalid\\\">\".concat(err.toString(), \" </p>\");\n    }\n  };\n\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eicon-woocommerce.default\", productGrid);\n});\n\n//# sourceURL=webpack:///./src/js/view/product-grid.js?");

/***/ })

/******/ });