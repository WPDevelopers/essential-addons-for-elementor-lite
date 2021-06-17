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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/general.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _arrayLikeToArray; });\nfunction _arrayLikeToArray(arr, len) {\n  if (len == null || len > arr.length) len = arr.length;\n\n  for (var i = 0, arr2 = new Array(len); i < len; i++) {\n    arr2[i] = arr[i];\n  }\n\n  return arr2;\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _arrayWithoutHoles; });\n/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray.js */ \"./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js\");\n\nfunction _arrayWithoutHoles(arr) {\n  if (Array.isArray(arr)) return Object(_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(arr);\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/classCallCheck.js ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _classCallCheck; });\nfunction _classCallCheck(instance, Constructor) {\n  if (!(instance instanceof Constructor)) {\n    throw new TypeError(\"Cannot call a class as a function\");\n  }\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/classCallCheck.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArray.js":
/*!********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArray.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _iterableToArray; });\nfunction _iterableToArray(iter) {\n  if (typeof Symbol !== \"undefined\" && iter[Symbol.iterator] != null || iter[\"@@iterator\"] != null) return Array.from(iter);\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/iterableToArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _nonIterableSpread; });\nfunction _nonIterableSpread() {\n  throw new TypeError(\"Invalid attempt to spread non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\");\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _toConsumableArray; });\n/* harmony import */ var _arrayWithoutHoles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithoutHoles.js */ \"./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js\");\n/* harmony import */ var _iterableToArray_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArray.js */ \"./node_modules/@babel/runtime/helpers/esm/iterableToArray.js\");\n/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./unsupportedIterableToArray.js */ \"./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js\");\n/* harmony import */ var _nonIterableSpread_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nonIterableSpread.js */ \"./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js\");\n\n\n\n\nfunction _toConsumableArray(arr) {\n  return Object(_arrayWithoutHoles_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(arr) || Object(_iterableToArray_js__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(arr) || Object(_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(arr) || Object(_nonIterableSpread_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])();\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js":
/*!*******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _unsupportedIterableToArray; });\n/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray.js */ \"./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js\");\n\nfunction _unsupportedIterableToArray(o, minLen) {\n  if (!o) return;\n  if (typeof o === \"string\") return Object(_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(o, minLen);\n  var n = Object.prototype.toString.call(o).slice(8, -1);\n  if (n === \"Object\" && o.constructor) n = o.constructor.name;\n  if (n === \"Map\" || n === \"Set\") return Array.from(o);\n  if (n === \"Arguments\" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return Object(_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(o, minLen);\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createAddHook.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createAddHook.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validateNamespace.js */ \"./node_modules/@wordpress/hooks/build-module/validateNamespace.js\");\n/* harmony import */ var _validateHookName_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./validateHookName.js */ \"./node_modules/@wordpress/hooks/build-module/validateHookName.js\");\n/**\n * Internal dependencies\n */\n\n\n/**\n * @callback AddHook\n *\n * Adds the hook to the appropriate hooks container.\n *\n * @param {string}               hookName  Name of hook to add\n * @param {string}               namespace The unique namespace identifying the callback in the form `vendor/plugin/function`.\n * @param {import('.').Callback} callback  Function to call when the hook is run\n * @param {number}               [priority=10]  Priority of this hook\n */\n\n/**\n * Returns a function which, when invoked, will add a hook.\n *\n * @param  {import('.').Hooks}    hooks Hooks instance.\n * @param  {import('.').StoreKey} storeKey\n *\n * @return {AddHook} Function that adds a new hook.\n */\n\nfunction createAddHook(hooks, storeKey) {\n  return function addHook(hookName, namespace, callback) {\n    var priority = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 10;\n    var hooksStore = hooks[storeKey];\n\n    if (!Object(_validateHookName_js__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(hookName)) {\n      return;\n    }\n\n    if (!Object(_validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(namespace)) {\n      return;\n    }\n\n    if ('function' !== typeof callback) {\n      // eslint-disable-next-line no-console\n      console.error('The hook callback must be a function.');\n      return;\n    } // Validate numeric priority\n\n\n    if ('number' !== typeof priority) {\n      // eslint-disable-next-line no-console\n      console.error('If specified, the hook priority must be a number.');\n      return;\n    }\n\n    var handler = {\n      callback: callback,\n      priority: priority,\n      namespace: namespace\n    };\n\n    if (hooksStore[hookName]) {\n      // Find the correct insert index of the new hook.\n      var handlers = hooksStore[hookName].handlers;\n      /** @type {number} */\n\n      var i;\n\n      for (i = handlers.length; i > 0; i--) {\n        if (priority >= handlers[i - 1].priority) {\n          break;\n        }\n      }\n\n      if (i === handlers.length) {\n        // If append, operate via direct assignment.\n        handlers[i] = handler;\n      } else {\n        // Otherwise, insert before index via splice.\n        handlers.splice(i, 0, handler);\n      } // We may also be currently executing this hook.  If the callback\n      // we're adding would come after the current callback, there's no\n      // problem; otherwise we need to increase the execution index of\n      // any other runs by 1 to account for the added element.\n\n\n      hooksStore.__current.forEach(function (hookInfo) {\n        if (hookInfo.name === hookName && hookInfo.currentIndex >= i) {\n          hookInfo.currentIndex++;\n        }\n      });\n    } else {\n      // This is the first hook of its type.\n      hooksStore[hookName] = {\n        handlers: [handler],\n        runs: 0\n      };\n    }\n\n    if (hookName !== 'hookAdded') {\n      hooks.doAction('hookAdded', hookName, namespace, callback, priority);\n    }\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createAddHook);\n//# sourceMappingURL=createAddHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createAddHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createCurrentHook.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createCurrentHook.js ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Returns a function which, when invoked, will return the name of the\n * currently running hook, or `null` if no hook of the given type is currently\n * running.\n *\n * @param  {import('.').Hooks}    hooks Hooks instance.\n * @param  {import('.').StoreKey} storeKey\n *\n * @return {() => string | null} Function that returns the current hook name or null.\n */\nfunction createCurrentHook(hooks, storeKey) {\n  return function currentHook() {\n    var _hooksStore$__current, _hooksStore$__current2;\n\n    var hooksStore = hooks[storeKey];\n    return (_hooksStore$__current = (_hooksStore$__current2 = hooksStore.__current[hooksStore.__current.length - 1]) === null || _hooksStore$__current2 === void 0 ? void 0 : _hooksStore$__current2.name) !== null && _hooksStore$__current !== void 0 ? _hooksStore$__current : null;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createCurrentHook);\n//# sourceMappingURL=createCurrentHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createCurrentHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createDidHook.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createDidHook.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _validateHookName_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validateHookName.js */ \"./node_modules/@wordpress/hooks/build-module/validateHookName.js\");\n/**\n * Internal dependencies\n */\n\n/**\n * @callback DidHook\n *\n * Returns the number of times an action has been fired.\n *\n * @param  {string} hookName The hook name to check.\n *\n * @return {number | undefined} The number of times the hook has run.\n */\n\n/**\n * Returns a function which, when invoked, will return the number of times a\n * hook has been called.\n *\n * @param  {import('.').Hooks}    hooks Hooks instance.\n * @param  {import('.').StoreKey} storeKey\n *\n * @return {DidHook} Function that returns a hook's call count.\n */\n\nfunction createDidHook(hooks, storeKey) {\n  return function didHook(hookName) {\n    var hooksStore = hooks[storeKey];\n\n    if (!Object(_validateHookName_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(hookName)) {\n      return;\n    }\n\n    return hooksStore[hookName] && hooksStore[hookName].runs ? hooksStore[hookName].runs : 0;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createDidHook);\n//# sourceMappingURL=createDidHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createDidHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createDoingHook.js":
/*!***********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createDoingHook.js ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * @callback DoingHook\n * Returns whether a hook is currently being executed.\n *\n * @param  {string} [hookName] The name of the hook to check for.  If\n *                             omitted, will check for any hook being executed.\n *\n * @return {boolean} Whether the hook is being executed.\n */\n\n/**\n * Returns a function which, when invoked, will return whether a hook is\n * currently being executed.\n *\n * @param  {import('.').Hooks}    hooks Hooks instance.\n * @param  {import('.').StoreKey} storeKey\n *\n * @return {DoingHook} Function that returns whether a hook is currently\n *                     being executed.\n */\nfunction createDoingHook(hooks, storeKey) {\n  return function doingHook(hookName) {\n    var hooksStore = hooks[storeKey]; // If the hookName was not passed, check for any current hook.\n\n    if ('undefined' === typeof hookName) {\n      return 'undefined' !== typeof hooksStore.__current[0];\n    } // Return the __current hook.\n\n\n    return hooksStore.__current[0] ? hookName === hooksStore.__current[0].name : false;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createDoingHook);\n//# sourceMappingURL=createDoingHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createDoingHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createHasHook.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createHasHook.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * @callback HasHook\n *\n * Returns whether any handlers are attached for the given hookName and optional namespace.\n *\n * @param {string} hookName    The name of the hook to check for.\n * @param {string} [namespace] Optional. The unique namespace identifying the callback\n *                             in the form `vendor/plugin/function`.\n *\n * @return {boolean} Whether there are handlers that are attached to the given hook.\n */\n\n/**\n * Returns a function which, when invoked, will return whether any handlers are\n * attached to a particular hook.\n *\n * @param  {import('.').Hooks}    hooks Hooks instance.\n * @param  {import('.').StoreKey} storeKey\n *\n * @return {HasHook} Function that returns whether any handlers are\n *                   attached to a particular hook and optional namespace.\n */\nfunction createHasHook(hooks, storeKey) {\n  return function hasHook(hookName, namespace) {\n    var hooksStore = hooks[storeKey]; // Use the namespace if provided.\n\n    if ('undefined' !== typeof namespace) {\n      return hookName in hooksStore && hooksStore[hookName].handlers.some(function (hook) {\n        return hook.namespace === namespace;\n      });\n    }\n\n    return hookName in hooksStore;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createHasHook);\n//# sourceMappingURL=createHasHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createHasHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createHooks.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createHooks.js ***!
  \*******************************************************************/
/*! exports provided: _Hooks, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"_Hooks\", function() { return _Hooks; });\n/* harmony import */ var _babel_runtime_helpers_esm_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/classCallCheck */ \"./node_modules/@babel/runtime/helpers/esm/classCallCheck.js\");\n/* harmony import */ var _createAddHook__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./createAddHook */ \"./node_modules/@wordpress/hooks/build-module/createAddHook.js\");\n/* harmony import */ var _createRemoveHook__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./createRemoveHook */ \"./node_modules/@wordpress/hooks/build-module/createRemoveHook.js\");\n/* harmony import */ var _createHasHook__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./createHasHook */ \"./node_modules/@wordpress/hooks/build-module/createHasHook.js\");\n/* harmony import */ var _createRunHook__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./createRunHook */ \"./node_modules/@wordpress/hooks/build-module/createRunHook.js\");\n/* harmony import */ var _createCurrentHook__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./createCurrentHook */ \"./node_modules/@wordpress/hooks/build-module/createCurrentHook.js\");\n/* harmony import */ var _createDoingHook__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./createDoingHook */ \"./node_modules/@wordpress/hooks/build-module/createDoingHook.js\");\n/* harmony import */ var _createDidHook__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./createDidHook */ \"./node_modules/@wordpress/hooks/build-module/createDidHook.js\");\n\n\n/**\n * Internal dependencies\n */\n\n\n\n\n\n\n\n/**\n * Internal class for constructing hooks. Use `createHooks()` function\n *\n * Note, it is necessary to expose this class to make its type public.\n *\n * @private\n */\n\nvar _Hooks = function _Hooks() {\n  Object(_babel_runtime_helpers_esm_classCallCheck__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, _Hooks);\n\n  /** @type {import('.').Store} actions */\n  this.actions = Object.create(null);\n  this.actions.__current = [];\n  /** @type {import('.').Store} filters */\n\n  this.filters = Object.create(null);\n  this.filters.__current = [];\n  this.addAction = Object(_createAddHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this, 'actions');\n  this.addFilter = Object(_createAddHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this, 'filters');\n  this.removeAction = Object(_createRemoveHook__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(this, 'actions');\n  this.removeFilter = Object(_createRemoveHook__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(this, 'filters');\n  this.hasAction = Object(_createHasHook__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(this, 'actions');\n  this.hasFilter = Object(_createHasHook__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(this, 'filters');\n  this.removeAllActions = Object(_createRemoveHook__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(this, 'actions', true);\n  this.removeAllFilters = Object(_createRemoveHook__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(this, 'filters', true);\n  this.doAction = Object(_createRunHook__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(this, 'actions');\n  this.applyFilters = Object(_createRunHook__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(this, 'filters', true);\n  this.currentAction = Object(_createCurrentHook__WEBPACK_IMPORTED_MODULE_5__[\"default\"])(this, 'actions');\n  this.currentFilter = Object(_createCurrentHook__WEBPACK_IMPORTED_MODULE_5__[\"default\"])(this, 'filters');\n  this.doingAction = Object(_createDoingHook__WEBPACK_IMPORTED_MODULE_6__[\"default\"])(this, 'actions');\n  this.doingFilter = Object(_createDoingHook__WEBPACK_IMPORTED_MODULE_6__[\"default\"])(this, 'filters');\n  this.didAction = Object(_createDidHook__WEBPACK_IMPORTED_MODULE_7__[\"default\"])(this, 'actions');\n  this.didFilter = Object(_createDidHook__WEBPACK_IMPORTED_MODULE_7__[\"default\"])(this, 'filters');\n};\n/** @typedef {_Hooks} Hooks */\n\n/**\n * Returns an instance of the hooks object.\n *\n * @return {Hooks} A Hooks instance.\n */\n\nfunction createHooks() {\n  return new _Hooks();\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createHooks);\n//# sourceMappingURL=createHooks.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createHooks.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createRemoveHook.js":
/*!************************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createRemoveHook.js ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validateNamespace.js */ \"./node_modules/@wordpress/hooks/build-module/validateNamespace.js\");\n/* harmony import */ var _validateHookName_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./validateHookName.js */ \"./node_modules/@wordpress/hooks/build-module/validateHookName.js\");\n/**\n * Internal dependencies\n */\n\n\n/**\n * @callback RemoveHook\n * Removes the specified callback (or all callbacks) from the hook with a given hookName\n * and namespace.\n *\n * @param {string} hookName  The name of the hook to modify.\n * @param {string} namespace The unique namespace identifying the callback in the\n *                           form `vendor/plugin/function`.\n *\n * @return {number | undefined} The number of callbacks removed.\n */\n\n/**\n * Returns a function which, when invoked, will remove a specified hook or all\n * hooks by the given name.\n *\n * @param  {import('.').Hooks}    hooks Hooks instance.\n * @param  {import('.').StoreKey} storeKey\n * @param  {boolean}              [removeAll=false] Whether to remove all callbacks for a hookName,\n *                                                  without regard to namespace. Used to create\n *                                                  `removeAll*` functions.\n *\n * @return {RemoveHook} Function that removes hooks.\n */\n\nfunction createRemoveHook(hooks, storeKey) {\n  var removeAll = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;\n  return function removeHook(hookName, namespace) {\n    var hooksStore = hooks[storeKey];\n\n    if (!Object(_validateHookName_js__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(hookName)) {\n      return;\n    }\n\n    if (!removeAll && !Object(_validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(namespace)) {\n      return;\n    } // Bail if no hooks exist by this name\n\n\n    if (!hooksStore[hookName]) {\n      return 0;\n    }\n\n    var handlersRemoved = 0;\n\n    if (removeAll) {\n      handlersRemoved = hooksStore[hookName].handlers.length;\n      hooksStore[hookName] = {\n        runs: hooksStore[hookName].runs,\n        handlers: []\n      };\n    } else {\n      // Try to find the specified callback to remove.\n      var handlers = hooksStore[hookName].handlers;\n\n      var _loop = function _loop(i) {\n        if (handlers[i].namespace === namespace) {\n          handlers.splice(i, 1);\n          handlersRemoved++; // This callback may also be part of a hook that is\n          // currently executing.  If the callback we're removing\n          // comes after the current callback, there's no problem;\n          // otherwise we need to decrease the execution index of any\n          // other runs by 1 to account for the removed element.\n\n          hooksStore.__current.forEach(function (hookInfo) {\n            if (hookInfo.name === hookName && hookInfo.currentIndex >= i) {\n              hookInfo.currentIndex--;\n            }\n          });\n        }\n      };\n\n      for (var i = handlers.length - 1; i >= 0; i--) {\n        _loop(i);\n      }\n    }\n\n    if (hookName !== 'hookRemoved') {\n      hooks.doAction('hookRemoved', hookName, namespace);\n    }\n\n    return handlersRemoved;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createRemoveHook);\n//# sourceMappingURL=createRemoveHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createRemoveHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createRunHook.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createRunHook.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js\");\n\n\n/**\n * Returns a function which, when invoked, will execute all callbacks\n * registered to a hook of the specified type, optionally returning the final\n * value of the call chain.\n *\n * @param  {import('.').Hooks}    hooks Hooks instance.\n * @param  {import('.').StoreKey} storeKey\n * @param  {boolean}              [returnFirstArg=false] Whether each hook callback is expected to\n *                                                       return its first argument.\n *\n * @return {(hookName:string, ...args: unknown[]) => unknown} Function that runs hook callbacks.\n */\nfunction createRunHook(hooks, storeKey) {\n  var returnFirstArg = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;\n  return function runHooks(hookName) {\n    var hooksStore = hooks[storeKey];\n\n    if (!hooksStore[hookName]) {\n      hooksStore[hookName] = {\n        handlers: [],\n        runs: 0\n      };\n    }\n\n    hooksStore[hookName].runs++;\n    var handlers = hooksStore[hookName].handlers; // The following code is stripped from production builds.\n\n    if (true) {\n      // Handle any 'all' hooks registered.\n      if ('hookAdded' !== hookName && hooksStore.all) {\n        handlers.push.apply(handlers, Object(_babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(hooksStore.all.handlers));\n      }\n    }\n\n    for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {\n      args[_key - 1] = arguments[_key];\n    }\n\n    if (!handlers || !handlers.length) {\n      return returnFirstArg ? args[0] : undefined;\n    }\n\n    var hookInfo = {\n      name: hookName,\n      currentIndex: 0\n    };\n\n    hooksStore.__current.push(hookInfo);\n\n    while (hookInfo.currentIndex < handlers.length) {\n      var handler = handlers[hookInfo.currentIndex];\n      var result = handler.callback.apply(null, args);\n\n      if (returnFirstArg) {\n        args[0] = result;\n      }\n\n      hookInfo.currentIndex++;\n    }\n\n    hooksStore.__current.pop();\n\n    if (returnFirstArg) {\n      return args[0];\n    }\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createRunHook);\n//# sourceMappingURL=createRunHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createRunHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/index.js":
/*!*************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/index.js ***!
  \*************************************************************/
/*! exports provided: defaultHooks, createHooks, addAction, addFilter, removeAction, removeFilter, hasAction, hasFilter, removeAllActions, removeAllFilters, doAction, applyFilters, currentAction, currentFilter, doingAction, doingFilter, didAction, didFilter, actions, filters */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"defaultHooks\", function() { return defaultHooks; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"addAction\", function() { return addAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"addFilter\", function() { return addFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"removeAction\", function() { return removeAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"removeFilter\", function() { return removeFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"hasAction\", function() { return hasAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"hasFilter\", function() { return hasFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"removeAllActions\", function() { return removeAllActions; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"removeAllFilters\", function() { return removeAllFilters; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"doAction\", function() { return doAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"applyFilters\", function() { return applyFilters; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"currentAction\", function() { return currentAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"currentFilter\", function() { return currentFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"doingAction\", function() { return doingAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"doingFilter\", function() { return doingFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"didAction\", function() { return didAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"didFilter\", function() { return didFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"actions\", function() { return actions; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"filters\", function() { return filters; });\n/* harmony import */ var _createHooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./createHooks */ \"./node_modules/@wordpress/hooks/build-module/createHooks.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"createHooks\", function() { return _createHooks__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; });\n\n/**\n * Internal dependencies\n */\n\n/** @typedef {(...args: any[])=>any} Callback */\n\n/**\n * @typedef Handler\n * @property {Callback} callback  The callback\n * @property {string}   namespace The namespace\n * @property {number}   priority  The namespace\n */\n\n/**\n * @typedef Hook\n * @property {Handler[]} handlers Array of handlers\n * @property {number}    runs     Run counter\n */\n\n/**\n * @typedef Current\n * @property {string} name         Hook name\n * @property {number} currentIndex The index\n */\n\n/**\n * @typedef {Record<string, Hook> & {__current: Current[]}} Store\n */\n\n/**\n * @typedef {'actions' | 'filters'} StoreKey\n */\n\n/**\n * @typedef {import('./createHooks').Hooks} Hooks\n */\n\nvar defaultHooks = Object(_createHooks__WEBPACK_IMPORTED_MODULE_0__[\"default\"])();\nvar addAction = defaultHooks.addAction,\n    addFilter = defaultHooks.addFilter,\n    removeAction = defaultHooks.removeAction,\n    removeFilter = defaultHooks.removeFilter,\n    hasAction = defaultHooks.hasAction,\n    hasFilter = defaultHooks.hasFilter,\n    removeAllActions = defaultHooks.removeAllActions,\n    removeAllFilters = defaultHooks.removeAllFilters,\n    doAction = defaultHooks.doAction,\n    applyFilters = defaultHooks.applyFilters,\n    currentAction = defaultHooks.currentAction,\n    currentFilter = defaultHooks.currentFilter,\n    doingAction = defaultHooks.doingAction,\n    doingFilter = defaultHooks.doingFilter,\n    didAction = defaultHooks.didAction,\n    didFilter = defaultHooks.didFilter,\n    actions = defaultHooks.actions,\n    filters = defaultHooks.filters;\n\n//# sourceMappingURL=index.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/index.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/validateHookName.js":
/*!************************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/validateHookName.js ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Validate a hookName string.\n *\n * @param  {string} hookName The hook name to validate. Should be a non empty string containing\n *                           only numbers, letters, dashes, periods and underscores. Also,\n *                           the hook name cannot begin with `__`.\n *\n * @return {boolean}            Whether the hook name is valid.\n */\nfunction validateHookName(hookName) {\n  if ('string' !== typeof hookName || '' === hookName) {\n    // eslint-disable-next-line no-console\n    console.error('The hook name must be a non-empty string.');\n    return false;\n  }\n\n  if (/^__/.test(hookName)) {\n    // eslint-disable-next-line no-console\n    console.error('The hook name cannot begin with `__`.');\n    return false;\n  }\n\n  if (!/^[a-zA-Z][a-zA-Z0-9_.-]*$/.test(hookName)) {\n    // eslint-disable-next-line no-console\n    console.error('The hook name can only contain numbers, letters, dashes, periods and underscores.');\n    return false;\n  }\n\n  return true;\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (validateHookName);\n//# sourceMappingURL=validateHookName.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/validateHookName.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/validateNamespace.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/validateNamespace.js ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Validate a namespace string.\n *\n * @param  {string} namespace The namespace to validate - should take the form\n *                            `vendor/plugin/function`.\n *\n * @return {boolean}             Whether the namespace is valid.\n */\nfunction validateNamespace(namespace) {\n  if ('string' !== typeof namespace || '' === namespace) {\n    // eslint-disable-next-line no-console\n    console.error('The namespace must be a non-empty string.');\n    return false;\n  }\n\n  if (!/^[a-zA-Z][a-zA-Z0-9_.\\-\\/]*$/.test(namespace)) {\n    // eslint-disable-next-line no-console\n    console.error('The namespace can only contain numbers, letters, dashes, periods, underscores and slashes.');\n    return false;\n  }\n\n  return true;\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (validateNamespace);\n//# sourceMappingURL=validateNamespace.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/validateNamespace.js?");

/***/ }),

/***/ "./src/js/view/general.js":
/*!********************************!*\
  !*** ./src/js/view/general.js ***!
  \********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/hooks */ \"./node_modules/@wordpress/hooks/build-module/index.js\");\n\nwindow.isEditMode = false;\nwindow.ea = {\n  hooks: Object(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__[\"createHooks\"])(),\n  isEditMode: false\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  window.isEditMode = elementorFrontend.isEditMode();\n  window.ea.isEditMode = elementorFrontend.isEditMode(); // hooks\n\n  ea.hooks.doAction(\"init\"); // init edit mode hook\n\n  if (ea.isEditMode) {\n    ea.hooks.doAction(\"editMode.init\");\n  }\n});\n\n//# sourceURL=webpack:///./src/js/view/general.js?");

/***/ })

/******/ });