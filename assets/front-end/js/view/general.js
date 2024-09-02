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

/***/ "./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js":
/*!**************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js ***!
  \**************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _arrayLikeToArray; });\nfunction _arrayLikeToArray(r, a) {\n  (null == a || a > r.length) && (a = r.length);\n  for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e];\n  return n;\n}\n\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js":
/*!***************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js ***!
  \***************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _arrayWithoutHoles; });\n/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray.js */ \"./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js\");\n\nfunction _arrayWithoutHoles(r) {\n  if (Array.isArray(r)) return Object(_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(r);\n}\n\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/iterableToArray.js":
/*!*************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/iterableToArray.js ***!
  \*************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _iterableToArray; });\nfunction _iterableToArray(r) {\n  if (\"undefined\" != typeof Symbol && null != r[Symbol.iterator] || null != r[\"@@iterator\"]) return Array.from(r);\n}\n\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/iterableToArray.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js":
/*!***************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js ***!
  \***************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _nonIterableSpread; });\nfunction _nonIterableSpread() {\n  throw new TypeError(\"Invalid attempt to spread non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\");\n}\n\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/toConsumableArray.js":
/*!***************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/toConsumableArray.js ***!
  \***************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _toConsumableArray; });\n/* harmony import */ var _arrayWithoutHoles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithoutHoles.js */ \"./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js\");\n/* harmony import */ var _iterableToArray_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArray.js */ \"./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/iterableToArray.js\");\n/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./unsupportedIterableToArray.js */ \"./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js\");\n/* harmony import */ var _nonIterableSpread_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nonIterableSpread.js */ \"./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js\");\n\n\n\n\nfunction _toConsumableArray(r) {\n  return Object(_arrayWithoutHoles_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(r) || Object(_iterableToArray_js__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(r) || Object(_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(r) || Object(_nonIterableSpread_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])();\n}\n\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/toConsumableArray.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js":
/*!************************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js ***!
  \************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _unsupportedIterableToArray; });\n/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray.js */ \"./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js\");\n\nfunction _unsupportedIterableToArray(r, a) {\n  if (r) {\n    if (\"string\" == typeof r) return Object(_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(r, a);\n    var t = {}.toString.call(r).slice(8, -1);\n    return \"Object\" === t && r.constructor && (t = r.constructor.name), \"Map\" === t || \"Set\" === t ? Array.from(r) : \"Arguments\" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? Object(_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(r, a) : void 0;\n  }\n}\n\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createAddHook.js":
/*!***************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createAddHook.js ***!
  \***************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validateNamespace.js */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/validateNamespace.js\");\n/* harmony import */ var _validateHookName_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./validateHookName.js */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/validateHookName.js\");\n/* harmony import */ var ___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/index.js\");\n/**\n * Internal dependencies\n */\n\n\n\n/**\n * Returns a function which, when invoked, will add a hook.\n *\n * @param  {Object}   hooks Stored hooks, keyed by hook name.\n *\n * @return {Function}       Function that adds a new hook.\n */\n\nfunction createAddHook(hooks) {\n  /**\n   * Adds the hook to the appropriate hooks container.\n   *\n   * @param {string}   hookName  Name of hook to add\n   * @param {string}   namespace The unique namespace identifying the callback in the form `vendor/plugin/function`.\n   * @param {Function} callback  Function to call when the hook is run\n   * @param {?number}  priority  Priority of this hook (default=10)\n   */\n  return function addHook(hookName, namespace, callback) {\n    var priority = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 10;\n\n    if (!Object(_validateHookName_js__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(hookName)) {\n      return;\n    }\n\n    if (!Object(_validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(namespace)) {\n      return;\n    }\n\n    if ('function' !== typeof callback) {\n      // eslint-disable-next-line no-console\n      console.error('The hook callback must be a function.');\n      return;\n    } // Validate numeric priority\n\n\n    if ('number' !== typeof priority) {\n      // eslint-disable-next-line no-console\n      console.error('If specified, the hook priority must be a number.');\n      return;\n    }\n\n    var handler = {\n      callback: callback,\n      priority: priority,\n      namespace: namespace\n    };\n\n    if (hooks[hookName]) {\n      // Find the correct insert index of the new hook.\n      var handlers = hooks[hookName].handlers;\n      var i;\n\n      for (i = handlers.length; i > 0; i--) {\n        if (priority >= handlers[i - 1].priority) {\n          break;\n        }\n      }\n\n      if (i === handlers.length) {\n        // If append, operate via direct assignment.\n        handlers[i] = handler;\n      } else {\n        // Otherwise, insert before index via splice.\n        handlers.splice(i, 0, handler);\n      } // We may also be currently executing this hook.  If the callback\n      // we're adding would come after the current callback, there's no\n      // problem; otherwise we need to increase the execution index of\n      // any other runs by 1 to account for the added element.\n\n\n      (hooks.__current || []).forEach(function (hookInfo) {\n        if (hookInfo.name === hookName && hookInfo.currentIndex >= i) {\n          hookInfo.currentIndex++;\n        }\n      });\n    } else {\n      // This is the first hook of its type.\n      hooks[hookName] = {\n        handlers: [handler],\n        runs: 0\n      };\n    }\n\n    if (hookName !== 'hookAdded') {\n      Object(___WEBPACK_IMPORTED_MODULE_2__[\"doAction\"])('hookAdded', hookName, namespace, callback, priority);\n    }\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createAddHook);\n//# sourceMappingURL=createAddHook.js.map\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createAddHook.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createCurrentHook.js":
/*!*******************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createCurrentHook.js ***!
  \*******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Returns a function which, when invoked, will return the name of the\n * currently running hook, or `null` if no hook of the given type is currently\n * running.\n *\n * @param  {Object}   hooks          Stored hooks, keyed by hook name.\n *\n * @return {Function}                Function that returns the current hook.\n */\nfunction createCurrentHook(hooks) {\n  /**\n   * Returns the name of the currently running hook, or `null` if no hook of\n   * the given type is currently running.\n   *\n   * @return {?string}             The name of the currently running hook, or\n   *                               `null` if no hook is currently running.\n   */\n  return function currentHook() {\n    if (!hooks.__current || !hooks.__current.length) {\n      return null;\n    }\n\n    return hooks.__current[hooks.__current.length - 1].name;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createCurrentHook);\n//# sourceMappingURL=createCurrentHook.js.map\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createCurrentHook.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createDidHook.js":
/*!***************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createDidHook.js ***!
  \***************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _validateHookName_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validateHookName.js */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/validateHookName.js\");\n/**\n * Internal dependencies\n */\n\n/**\n * Returns a function which, when invoked, will return the number of times a\n * hook has been called.\n *\n * @param  {Object}   hooks Stored hooks, keyed by hook name.\n *\n * @return {Function}       Function that returns a hook's call count.\n */\n\nfunction createDidHook(hooks) {\n  /**\n   * Returns the number of times an action has been fired.\n   *\n   * @param  {string} hookName The hook name to check.\n   *\n   * @return {number}          The number of times the hook has run.\n   */\n  return function didHook(hookName) {\n    if (!Object(_validateHookName_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(hookName)) {\n      return;\n    }\n\n    return hooks[hookName] && hooks[hookName].runs ? hooks[hookName].runs : 0;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createDidHook);\n//# sourceMappingURL=createDidHook.js.map\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createDidHook.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createDoingHook.js":
/*!*****************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createDoingHook.js ***!
  \*****************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Returns a function which, when invoked, will return whether a hook is\n * currently being executed.\n *\n * @param  {Object}   hooks Stored hooks, keyed by hook name.\n *\n * @return {Function}       Function that returns whether a hook is currently\n *                          being executed.\n */\nfunction createDoingHook(hooks) {\n  /**\n   * Returns whether a hook is currently being executed.\n   *\n   * @param  {?string} hookName The name of the hook to check for.  If\n   *                            omitted, will check for any hook being executed.\n   *\n   * @return {boolean}             Whether the hook is being executed.\n   */\n  return function doingHook(hookName) {\n    // If the hookName was not passed, check for any current hook.\n    if ('undefined' === typeof hookName) {\n      return 'undefined' !== typeof hooks.__current[0];\n    } // Return the __current hook.\n\n\n    return hooks.__current[0] ? hookName === hooks.__current[0].name : false;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createDoingHook);\n//# sourceMappingURL=createDoingHook.js.map\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createDoingHook.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createHasHook.js":
/*!***************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createHasHook.js ***!
  \***************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Returns a function which, when invoked, will return whether any handlers are\n * attached to a particular hook.\n *\n * @param  {Object}   hooks Stored hooks, keyed by hook name.\n *\n * @return {Function}       Function that returns whether any handlers are\n *                          attached to a particular hook and optional namespace.\n */\nfunction createHasHook(hooks) {\n  /**\n   * Returns whether any handlers are attached for the given hookName and optional namespace.\n   *\n   * @param {string}  hookName  The name of the hook to check for.\n   * @param {?string} namespace Optional. The unique namespace identifying the callback\n   *                                      in the form `vendor/plugin/function`.\n   *\n   * @return {boolean} Whether there are handlers that are attached to the given hook.\n   */\n  return function hasHook(hookName, namespace) {\n    // Use the namespace if provided.\n    if ('undefined' !== typeof namespace) {\n      return hookName in hooks && hooks[hookName].handlers.some(function (hook) {\n        return hook.namespace === namespace;\n      });\n    }\n\n    return hookName in hooks;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createHasHook);\n//# sourceMappingURL=createHasHook.js.map\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createHasHook.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createHooks.js":
/*!*************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createHooks.js ***!
  \*************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _createAddHook__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./createAddHook */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createAddHook.js\");\n/* harmony import */ var _createRemoveHook__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./createRemoveHook */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createRemoveHook.js\");\n/* harmony import */ var _createHasHook__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./createHasHook */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createHasHook.js\");\n/* harmony import */ var _createRunHook__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./createRunHook */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createRunHook.js\");\n/* harmony import */ var _createCurrentHook__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./createCurrentHook */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createCurrentHook.js\");\n/* harmony import */ var _createDoingHook__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./createDoingHook */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createDoingHook.js\");\n/* harmony import */ var _createDidHook__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./createDidHook */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createDidHook.js\");\n/**\n * Internal dependencies\n */\n\n\n\n\n\n\n\n/**\n * Returns an instance of the hooks object.\n *\n * @return {Object} Object that contains all hooks.\n */\n\nfunction createHooks() {\n  var actions = Object.create(null);\n  var filters = Object.create(null);\n  actions.__current = [];\n  filters.__current = [];\n  return {\n    addAction: Object(_createAddHook__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(actions),\n    addFilter: Object(_createAddHook__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(filters),\n    removeAction: Object(_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(actions),\n    removeFilter: Object(_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(filters),\n    hasAction: Object(_createHasHook__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(actions),\n    hasFilter: Object(_createHasHook__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(filters),\n    removeAllActions: Object(_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(actions, true),\n    removeAllFilters: Object(_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(filters, true),\n    doAction: Object(_createRunHook__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(actions),\n    applyFilters: Object(_createRunHook__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(filters, true),\n    currentAction: Object(_createCurrentHook__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(actions),\n    currentFilter: Object(_createCurrentHook__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(filters),\n    doingAction: Object(_createDoingHook__WEBPACK_IMPORTED_MODULE_5__[\"default\"])(actions),\n    doingFilter: Object(_createDoingHook__WEBPACK_IMPORTED_MODULE_5__[\"default\"])(filters),\n    didAction: Object(_createDidHook__WEBPACK_IMPORTED_MODULE_6__[\"default\"])(actions),\n    didFilter: Object(_createDidHook__WEBPACK_IMPORTED_MODULE_6__[\"default\"])(filters),\n    actions: actions,\n    filters: filters\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createHooks);\n//# sourceMappingURL=createHooks.js.map\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createHooks.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createRemoveHook.js":
/*!******************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createRemoveHook.js ***!
  \******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validateNamespace.js */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/validateNamespace.js\");\n/* harmony import */ var _validateHookName_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./validateHookName.js */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/validateHookName.js\");\n/* harmony import */ var ___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/index.js\");\n/**\n * Internal dependencies\n */\n\n\n\n/**\n * Returns a function which, when invoked, will remove a specified hook or all\n * hooks by the given name.\n *\n * @param  {Object}   hooks      Stored hooks, keyed by hook name.\n * @param  {boolean}     removeAll  Whether to remove all callbacks for a hookName, without regard to namespace. Used to create `removeAll*` functions.\n *\n * @return {Function}            Function that removes hooks.\n */\n\nfunction createRemoveHook(hooks, removeAll) {\n  /**\n   * Removes the specified callback (or all callbacks) from the hook with a\n   * given hookName and namespace.\n   *\n   * @param {string}    hookName  The name of the hook to modify.\n   * @param {string}    namespace The unique namespace identifying the callback in the form `vendor/plugin/function`.\n   *\n   * @return {number}             The number of callbacks removed.\n   */\n  return function removeHook(hookName, namespace) {\n    if (!Object(_validateHookName_js__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(hookName)) {\n      return;\n    }\n\n    if (!removeAll && !Object(_validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(namespace)) {\n      return;\n    } // Bail if no hooks exist by this name\n\n\n    if (!hooks[hookName]) {\n      return 0;\n    }\n\n    var handlersRemoved = 0;\n\n    if (removeAll) {\n      handlersRemoved = hooks[hookName].handlers.length;\n      hooks[hookName] = {\n        runs: hooks[hookName].runs,\n        handlers: []\n      };\n    } else {\n      // Try to find the specified callback to remove.\n      var handlers = hooks[hookName].handlers;\n\n      var _loop = function _loop(i) {\n        if (handlers[i].namespace === namespace) {\n          handlers.splice(i, 1);\n          handlersRemoved++; // This callback may also be part of a hook that is\n          // currently executing.  If the callback we're removing\n          // comes after the current callback, there's no problem;\n          // otherwise we need to decrease the execution index of any\n          // other runs by 1 to account for the removed element.\n\n          (hooks.__current || []).forEach(function (hookInfo) {\n            if (hookInfo.name === hookName && hookInfo.currentIndex >= i) {\n              hookInfo.currentIndex--;\n            }\n          });\n        }\n      };\n\n      for (var i = handlers.length - 1; i >= 0; i--) {\n        _loop(i);\n      }\n    }\n\n    if (hookName !== 'hookRemoved') {\n      Object(___WEBPACK_IMPORTED_MODULE_2__[\"doAction\"])('hookRemoved', hookName, namespace);\n    }\n\n    return handlersRemoved;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createRemoveHook);\n//# sourceMappingURL=createRemoveHook.js.map\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createRemoveHook.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createRunHook.js":
/*!***************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createRunHook.js ***!
  \***************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/toConsumableArray */ \"./node_modules/.pnpm/@babel+runtime@7.24.6/node_modules/@babel/runtime/helpers/esm/toConsumableArray.js\");\n\n\n/**\n * Returns a function which, when invoked, will execute all callbacks\n * registered to a hook of the specified type, optionally returning the final\n * value of the call chain.\n *\n * @param  {Object}   hooks          Stored hooks, keyed by hook name.\n * @param  {?boolean}    returnFirstArg Whether each hook callback is expected to\n *                                   return its first argument.\n *\n * @return {Function}                Function that runs hook callbacks.\n */\nfunction createRunHook(hooks, returnFirstArg) {\n  /**\n   * Runs all callbacks for the specified hook.\n   *\n   * @param  {string} hookName The name of the hook to run.\n   * @param  {...*}   args     Arguments to pass to the hook callbacks.\n   *\n   * @return {*}               Return value of runner, if applicable.\n   */\n  return function runHooks(hookName) {\n    if (!hooks[hookName]) {\n      hooks[hookName] = {\n        handlers: [],\n        runs: 0\n      };\n    }\n\n    hooks[hookName].runs++;\n    var handlers = hooks[hookName].handlers; // The following code is stripped from production builds.\n\n    if (true) {\n      // Handle any 'all' hooks registered.\n      if ('hookAdded' !== hookName && hooks.all) {\n        handlers.push.apply(handlers, Object(_babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(hooks.all.handlers));\n      }\n    }\n\n    for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {\n      args[_key - 1] = arguments[_key];\n    }\n\n    if (!handlers || !handlers.length) {\n      return returnFirstArg ? args[0] : undefined;\n    }\n\n    var hookInfo = {\n      name: hookName,\n      currentIndex: 0\n    };\n\n    hooks.__current.push(hookInfo);\n\n    while (hookInfo.currentIndex < handlers.length) {\n      var handler = handlers[hookInfo.currentIndex];\n      var result = handler.callback.apply(null, args);\n\n      if (returnFirstArg) {\n        args[0] = result;\n      }\n\n      hookInfo.currentIndex++;\n    }\n\n    hooks.__current.pop();\n\n    if (returnFirstArg) {\n      return args[0];\n    }\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createRunHook);\n//# sourceMappingURL=createRunHook.js.map\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createRunHook.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/index.js":
/*!*******************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/index.js ***!
  \*******************************************************************************************************/
/*! exports provided: createHooks, addAction, addFilter, removeAction, removeFilter, hasAction, hasFilter, removeAllActions, removeAllFilters, doAction, applyFilters, currentAction, currentFilter, doingAction, doingFilter, didAction, didFilter, actions, filters */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"addAction\", function() { return addAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"addFilter\", function() { return addFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"removeAction\", function() { return removeAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"removeFilter\", function() { return removeFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"hasAction\", function() { return hasAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"hasFilter\", function() { return hasFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"removeAllActions\", function() { return removeAllActions; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"removeAllFilters\", function() { return removeAllFilters; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"doAction\", function() { return doAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"applyFilters\", function() { return applyFilters; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"currentAction\", function() { return currentAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"currentFilter\", function() { return currentFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"doingAction\", function() { return doingAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"doingFilter\", function() { return doingFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"didAction\", function() { return didAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"didFilter\", function() { return didFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"actions\", function() { return actions; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"filters\", function() { return filters; });\n/* harmony import */ var _createHooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./createHooks */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/createHooks.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"createHooks\", function() { return _createHooks__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; });\n\n/**\n * Internal dependencies\n */\n\n\nvar _createHooks = Object(_createHooks__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(),\n    addAction = _createHooks.addAction,\n    addFilter = _createHooks.addFilter,\n    removeAction = _createHooks.removeAction,\n    removeFilter = _createHooks.removeFilter,\n    hasAction = _createHooks.hasAction,\n    hasFilter = _createHooks.hasFilter,\n    removeAllActions = _createHooks.removeAllActions,\n    removeAllFilters = _createHooks.removeAllFilters,\n    doAction = _createHooks.doAction,\n    applyFilters = _createHooks.applyFilters,\n    currentAction = _createHooks.currentAction,\n    currentFilter = _createHooks.currentFilter,\n    doingAction = _createHooks.doingAction,\n    doingFilter = _createHooks.doingFilter,\n    didAction = _createHooks.didAction,\n    didFilter = _createHooks.didFilter,\n    actions = _createHooks.actions,\n    filters = _createHooks.filters;\n\n\n//# sourceMappingURL=index.js.map\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/index.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/validateHookName.js":
/*!******************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/validateHookName.js ***!
  \******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Validate a hookName string.\n *\n * @param  {string} hookName The hook name to validate. Should be a non empty string containing\n *                           only numbers, letters, dashes, periods and underscores. Also,\n *                           the hook name cannot begin with `__`.\n *\n * @return {boolean}            Whether the hook name is valid.\n */\nfunction validateHookName(hookName) {\n  if ('string' !== typeof hookName || '' === hookName) {\n    // eslint-disable-next-line no-console\n    console.error('The hook name must be a non-empty string.');\n    return false;\n  }\n\n  if (/^__/.test(hookName)) {\n    // eslint-disable-next-line no-console\n    console.error('The hook name cannot begin with `__`.');\n    return false;\n  }\n\n  if (!/^[a-zA-Z][a-zA-Z0-9_.-]*$/.test(hookName)) {\n    // eslint-disable-next-line no-console\n    console.error('The hook name can only contain numbers, letters, dashes, periods and underscores.');\n    return false;\n  }\n\n  return true;\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (validateHookName);\n//# sourceMappingURL=validateHookName.js.map\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/validateHookName.js?");

/***/ }),

/***/ "./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/validateNamespace.js":
/*!*******************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/validateNamespace.js ***!
  \*******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Validate a namespace string.\n *\n * @param  {string} namespace The namespace to validate - should take the form\n *                            `vendor/plugin/function`.\n *\n * @return {boolean}             Whether the namespace is valid.\n */\nfunction validateNamespace(namespace) {\n  if ('string' !== typeof namespace || '' === namespace) {\n    // eslint-disable-next-line no-console\n    console.error('The namespace must be a non-empty string.');\n    return false;\n  }\n\n  if (!/^[a-zA-Z][a-zA-Z0-9_.\\-\\/]*$/.test(namespace)) {\n    // eslint-disable-next-line no-console\n    console.error('The namespace can only contain numbers, letters, dashes, periods, underscores and slashes.');\n    return false;\n  }\n\n  return true;\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (validateNamespace);\n//# sourceMappingURL=validateNamespace.js.map\n\n//# sourceURL=webpack:///./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/validateNamespace.js?");

/***/ }),

/***/ "./src/js/view/general.js":
/*!********************************!*\
  !*** ./src/js/view/general.js ***!
  \********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/hooks */ \"./node_modules/.pnpm/@wordpress+hooks@2.6.0/node_modules/@wordpress/hooks/build-module/index.js\");\nfunction _typeof(o) { \"@babel/helpers - typeof\"; return _typeof = \"function\" == typeof Symbol && \"symbol\" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && \"function\" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? \"symbol\" : typeof o; }, _typeof(o); }\nfunction ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }\nfunction _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }\nfunction _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }\nfunction _toPropertyKey(t) { var i = _toPrimitive(t, \"string\"); return \"symbol\" == _typeof(i) ? i : i + \"\"; }\nfunction _toPrimitive(t, r) { if (\"object\" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || \"default\"); if (\"object\" != _typeof(i)) return i; throw new TypeError(\"@@toPrimitive must return a primitive value.\"); } return (\"string\" === r ? String : Number)(t); }\n\nwindow.isEditMode = false;\nwindow.eael = window.ea = {\n  hooks: Object(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__[\"createHooks\"])(),\n  isEditMode: false,\n  elementStatusCheck: function elementStatusCheck(name) {\n    if (window.eaElementList && name in window.eaElementList) {\n      return true;\n    } else {\n      window.eaElementList = _objectSpread(_objectSpread({}, window.eaElementList), {}, _defineProperty({}, name, true));\n    }\n    return false;\n  }\n};\neael.hooks.addAction(\"widgets.reinit\", \"ea\", function ($content) {\n  var filterGallery = jQuery(\".eael-filter-gallery-container\", $content);\n  var postGridGallery = jQuery(\".eael-post-grid:not(.eael-post-carousel)\", $content);\n  var twitterfeedGallery = jQuery(\".eael-twitter-feed-masonry\", $content);\n  var instaGallery = jQuery(\".eael-instafeed\", $content);\n  var paGallery = jQuery(\".premium-gallery-container\", $content);\n  var eventCalendar = jQuery(\".eael-event-calendar-cls\", $content);\n  var testimonialSlider = jQuery(\".eael-testimonial-slider\", $content);\n  var teamMemberCarousel = jQuery(\".eael-tm-carousel\", $content);\n  var postCarousel = jQuery(\".eael-post-carousel:not(.eael-post-grid)\", $content);\n  var logoCarousel = jQuery(\".eael-logo-carousel\", $content);\n  var twitterCarousel = jQuery(\".eael-twitter-feed-carousel\", $content);\n  if (filterGallery.length) {\n    filterGallery.isotope(\"layout\");\n  }\n  if (postGridGallery.length) {\n    postGridGallery.isotope(\"layout\");\n  }\n  if (twitterfeedGallery.length) {\n    twitterfeedGallery.isotope(\"layout\");\n  }\n  if (instaGallery.length) {\n    instaGallery.isotope(\"layout\");\n  }\n  if (paGallery.length) {\n    paGallery.isotope(\"layout\");\n  }\n  if (eventCalendar.length) {\n    eael.hooks.doAction(\"eventCalendar.reinit\");\n  }\n  if (testimonialSlider.length) {\n    eael.hooks.doAction(\"testimonialSlider.reinit\");\n  }\n  if (teamMemberCarousel.length) {\n    eael.hooks.doAction(\"teamMemberCarousel.reinit\");\n  }\n  if (postCarousel.length) {\n    eael.hooks.doAction(\"postCarousel.reinit\");\n  }\n  if (logoCarousel.length) {\n    eael.hooks.doAction(\"logoCarousel.reinit\");\n  }\n  if (twitterCarousel.length) {\n    eael.hooks.doAction(\"twitterCarousel.reinit\");\n  }\n});\nvar ea_swiper_slider_init_inside_template = function ea_swiper_slider_init_inside_template(content) {\n  window.dispatchEvent(new Event('resize'));\n  content = _typeof(content) === 'object' ? content : jQuery(content);\n  content.find('.swiper-wrapper').each(function () {\n    var transform = jQuery(this).css('transform');\n    jQuery(this).css('transform', transform);\n  });\n};\neael.hooks.addAction(\"ea-advanced-tabs-triggered\", \"ea\", ea_swiper_slider_init_inside_template);\neael.hooks.addAction(\"ea-advanced-accordion-triggered\", \"ea\", ea_swiper_slider_init_inside_template);\njQuery(window).on(\"elementor/frontend/init\", function () {\n  window.isEditMode = elementorFrontend.isEditMode();\n  window.eael.isEditMode = elementorFrontend.isEditMode();\n\n  // hooks\n  eael.hooks.doAction(\"init\");\n\n  // init edit mode hook\n  if (eael.isEditMode) {\n    eael.hooks.doAction(\"editMode.init\");\n  }\n});\n(function ($) {\n  eael.getToken = function () {\n    if (localize.nonce && !eael.noncegenerated) {\n      $.ajax({\n        url: localize.ajaxurl,\n        type: \"post\",\n        data: {\n          action: \"eael_get_token\"\n        },\n        success: function success(response) {\n          if (response.success) {\n            localize.nonce = response.data.nonce;\n            eael.noncegenerated = true;\n          }\n        }\n      });\n    }\n  };\n  eael.sanitizeURL = function (url) {\n    if (url.startsWith('/') || url.startsWith('#')) {\n      return url;\n    }\n    try {\n      var urlObject = new URL(url);\n\n      // Check if the protocol is valid (allowing only 'http' and 'https')\n      if (!['http:', 'https:', 'ftp:', 'ftps:', 'mailto:', 'news:', 'irc:', 'irc6:', 'ircs:', 'gopher:', 'nntp:', 'feed:', 'telnet:', 'mms:', 'rtsp:', 'sms:', 'svn:', 'tel:', 'fax:', 'xmpp:', 'webcal:', 'urn:'].includes(urlObject.protocol)) {\n        throw new Error('Invalid protocol');\n      }\n\n      // If all checks pass, return the sanitized URL\n      return urlObject.toString();\n    } catch (error) {\n      console.error('Error sanitizing URL:', error.message);\n      return '#';\n    }\n  };\n\n  //Add hashchange code form advanced-accordion\n  var isTriggerOnHashchange = true;\n  window.addEventListener('hashchange', function () {\n    if (!isTriggerOnHashchange) {\n      return;\n    }\n    var hashTag = window.location.hash.substr(1);\n    hashTag = hashTag === 'safari' ? 'eael-safari' : hashTag;\n    if (hashTag !== 'undefined' && hashTag) {\n      jQuery('#' + hashTag).trigger('click');\n    }\n  });\n  $('a').on('click', function (e) {\n    var hashURL = $(this).attr('href'),\n      isStartWithHash;\n    hashURL = hashURL === undefined ? '' : hashURL;\n    isStartWithHash = hashURL.startsWith('#');\n    if (!isStartWithHash) {\n      hashURL = hashURL.replace(localize.page_permalink, '');\n      isStartWithHash = hashURL.startsWith('#');\n    }\n    if (isStartWithHash) {\n      isTriggerOnHashchange = false;\n      setTimeout(function () {\n        isTriggerOnHashchange = true;\n      }, 100);\n    }\n\n    // we will try and catch the error but not show anything just do it if possible\n    try {\n      if (hashURL.startsWith('#!')) {\n        var replace_with_hash = hashURL.replace('#!', '#');\n        $(replace_with_hash).trigger('click');\n      } else {\n        if (isStartWithHash && ($(hashURL).hasClass('eael-tab-item-trigger') || $(hashURL).hasClass('eael-accordion-header'))) {\n          $(hashURL).trigger('click');\n          if (typeof hashURL !== 'undefined' && hashURL) {\n            var tabs = $(hashURL).closest('.eael-advance-tabs');\n            if (tabs.length > 0) {\n              var idOffset = tab.data('custom-id-offset');\n              idOffset = idOffset ? parseFloat(idOffset) : 0;\n              $('html, body').animate({\n                scrollTop: $(hashURL).offset().top - idOffset\n              }, 300);\n            }\n          }\n        }\n      }\n    } catch (err) {\n      // nothing to do\n    }\n  });\n  $(document).on('click', '.e-n-tab-title', function () {\n    window.dispatchEvent(new Event('resize'));\n  });\n})(jQuery);\n(function ($) {\n  $(document).on('click', '.theme-savoy .eael-product-popup .nm-qty-minus, .theme-savoy .eael-product-popup .nm-qty-plus', function (e) {\n    // Get elements and values\n    var $this = $(this),\n      $qty = $this.closest('.quantity').find('.qty'),\n      currentVal = parseFloat($qty.val()),\n      max = parseFloat($qty.attr('max')),\n      min = parseFloat($qty.attr('min')),\n      step = $qty.attr('step');\n\n    // Format values\n    if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;\n    if (max === '' || max === 'NaN') max = '';\n    if (min === '' || min === 'NaN') min = 0;\n    if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;\n\n    // Change the value\n    if ($this.hasClass('nm-qty-plus')) {\n      if (max && (max == currentVal || currentVal > max)) {\n        $qty.val(max);\n      } else {\n        $qty.val(currentVal + parseFloat(step));\n      }\n    } else {\n      if (min && (min == currentVal || currentVal < min)) {\n        $qty.val(min);\n      } else if (currentVal > 0) {\n        $qty.val(currentVal - parseFloat(step));\n      }\n    }\n  });\n})(jQuery);\n(function ($) {\n  $.fn.isInViewport = function () {\n    if ($(this).length < 1) return false;\n    var elementTop = $(this).offset().top;\n    var elementBottom = elementTop + $(this).outerHeight() / 2;\n    var viewportTop = $(window).scrollTop();\n    var viewportHalf = viewportTop + $(window).height() / 2;\n    return elementBottom > viewportTop && elementTop < viewportHalf;\n  };\n  $(document).ready(function () {\n    var resetPasswordParams = new URLSearchParams(location.search);\n    if (resetPasswordParams.has('popup-selector') && (resetPasswordParams.has('eael-lostpassword') || resetPasswordParams.has('eael-resetpassword'))) {\n      var popupSelector = resetPasswordParams.get('popup-selector');\n      if (popupSelector.length) {\n        popupSelector = popupSelector.replace(/_/g, \" \");\n        setTimeout(function () {\n          jQuery(popupSelector).trigger('click');\n        }, 300);\n      }\n    }\n  });\n})(jQuery);\n\n//# sourceURL=webpack:///./src/js/view/general.js?");

/***/ })

/******/ });