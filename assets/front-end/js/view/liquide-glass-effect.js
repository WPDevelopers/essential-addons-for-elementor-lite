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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/liquide-glass-effect.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/liquide-glass-effect.js":
/*!*********************************************!*\
  !*** ./src/js/view/liquide-glass-effect.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }\nfunction _nonIterableRest() { throw new TypeError(\"Invalid attempt to destructure non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\"); }\nfunction _unsupportedIterableToArray(r, a) { if (r) { if (\"string\" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return \"Object\" === t && r.constructor && (t = r.constructor.name), \"Map\" === t || \"Set\" === t ? Array.from(r) : \"Arguments\" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }\nfunction _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }\nfunction _iterableToArrayLimit(r, l) { var t = null == r ? null : \"undefined\" != typeof Symbol && r[Symbol.iterator] || r[\"@@iterator\"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t[\"return\"] && (u = t[\"return\"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }\nfunction _arrayWithHoles(r) { if (Array.isArray(r)) return r; }\nvar LiquidGlassEffectHandler = function LiquidGlassEffectHandler($scope, $) {\n  var $scopeId = $scope.data(\"id\"),\n    $glassEffects = $scope.data(\"eael_glass_effects\");\n\n  // Check if glass effects data exists\n  if (!$glassEffects) {\n    return;\n  }\n\n  // Apply SVG filter attributes\n  var glassEffects = {\n    \"eael-glass-distortion1\": {\n      freq: $glassEffects.freq,\n      scale: $glassEffects.scale\n    },\n    \"eael-glass-distortion2\": {\n      freq: $glassEffects.freq,\n      scale: $glassEffects.scale\n    },\n    \"eael-glass-distortion3\": {\n      freq: $glassEffects.freq,\n      scale: $glassEffects.scale\n    }\n  };\n\n  // Function to update SVG filter attributes\n  function updateFilterAttributes(filterId, _ref) {\n    var freq = _ref.freq,\n      scale = _ref.scale;\n    var filterElement = document.querySelector(\"#\".concat(filterId));\n    if (!filterElement) {\n      return;\n    }\n    var turbulenceElement = filterElement.querySelector(\"feTurbulence\");\n    if (turbulenceElement) {\n      turbulenceElement.setAttribute(\"baseFrequency\", \"\".concat(freq, \" \").concat(freq));\n    }\n    var displacementElement = filterElement.querySelector(\"feDisplacementMap\");\n    if (displacementElement) {\n      displacementElement.setAttribute(\"scale\", scale);\n    }\n  }\n\n  // Apply to all filters\n  Object.entries(glassEffects).forEach(function (_ref2) {\n    var _ref3 = _slicedToArray(_ref2, 2),\n      id = _ref3[0],\n      config = _ref3[1];\n    updateFilterAttributes(id, config);\n  });\n\n  // Display values in editor mode only\n  if (elementorFrontend.isEditMode()) {\n    // Store the scope for later updates\n    $scope.attr(\"data-eael-glass-widget\", \"true\");\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/widget\", LiquidGlassEffectHandler);\n\n  // Add real-time updates for editor mode\n  if (elementorFrontend.isEditMode()) {\n    // Function to get current control values from elementor model\n    var getCurrentGlassEffectValues = function getCurrentGlassEffectValues(elementView) {\n      var settings = elementView.model.get(\"settings\");\n      var freq = settings.get(\"eael_liquid_glass_effect_noise_freq\");\n      var strength = settings.get(\"eael_liquid_glass_effect_noise_strength\");\n      return {\n        freq: freq && freq.size ? freq.size : 0.008,\n        scale: strength && strength.size ? strength.size : 77\n      };\n    }; // Function to update SVG filters and display\n    var updateGlassEffectDisplay = function updateGlassEffectDisplay(elementView) {\n      var $element = elementView.$el;\n      var glassEffectsData = getCurrentGlassEffectValues(elementView);\n\n      // Update SVG filter attributes\n      var glassEffects = {\n        \"eael-glass-distortion1\": {\n          freq: glassEffectsData.freq,\n          scale: glassEffectsData.scale\n        },\n        \"eael-glass-distortion2\": {\n          freq: glassEffectsData.freq,\n          scale: glassEffectsData.scale\n        },\n        \"eael-glass-distortion3\": {\n          freq: glassEffectsData.freq,\n          scale: glassEffectsData.scale\n        }\n      };\n\n      // Function to update SVG filter attributes\n      function updateFilterAttributes(filterId, _ref4) {\n        var freq = _ref4.freq,\n          scale = _ref4.scale;\n        var filterElement = document.querySelector(\"#\".concat(filterId));\n        if (!filterElement) {\n          return;\n        }\n        var turbulenceElement = filterElement.querySelector(\"feTurbulence\");\n        if (turbulenceElement) {\n          turbulenceElement.setAttribute(\"baseFrequency\", \"\".concat(freq, \" \").concat(freq));\n        }\n        var displacementElement = filterElement.querySelector(\"feDisplacementMap\");\n        if (displacementElement) {\n          displacementElement.setAttribute(\"scale\", scale);\n        }\n      }\n\n      // Apply to all filters\n      Object.entries(glassEffects).forEach(function (_ref5) {\n        var _ref6 = _slicedToArray(_ref5, 2),\n          id = _ref6[0],\n          config = _ref6[1];\n        updateFilterAttributes(id, config);\n      });\n    }; // Listen for control changes in the editor\n    elementor.channels.editor.on(\"change\", function (controlView, elementView) {\n      if (!elementView) return;\n      var controlName = controlView.model.get(\"name\");\n\n      // Check if the changed control affects liquid glass effect\n      if (controlName === \"eael_liquid_glass_effect_noise_freq\" || controlName === \"eael_liquid_glass_effect_noise_strength\") {\n        // Update immediately without delay\n        updateGlassEffectDisplay(elementView);\n      }\n    });\n\n    // Alternative approach: Listen for any setting change and check if it's our widget\n    elementor.channels.editor.on(\"change\", function (controlView) {\n      var controlName = controlView.model.get(\"name\");\n      if (controlName === \"eael_liquid_glass_effect_noise_freq\" || controlName === \"eael_liquid_glass_effect_noise_strength\") {\n        // Find the current editing element\n        var currentElement = elementor.getCurrentElement();\n        if (currentElement && currentElement.view) {\n          updateGlassEffectDisplay(currentElement.view);\n        }\n      }\n    });\n\n    // Listen for panel changes to update display when switching between elements\n    elementor.channels.editor.on(\"panel:change\", function () {\n      setTimeout(function () {\n        var currentElement = elementor.getCurrentElement();\n        if (currentElement && currentElement.view) {\n          var settings = currentElement.view.model.get(\"settings\");\n          if (settings.get(\"eael_liquid_glass_effect_switch\") === \"yes\") {\n            updateGlassEffectDisplay(currentElement.view);\n          }\n        }\n      }, 100);\n    });\n  }\n});\n\n//# sourceURL=webpack:///./src/js/view/liquide-glass-effect.js?");

/***/ })

/******/ });