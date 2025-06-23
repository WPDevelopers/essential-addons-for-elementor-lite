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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/code-snippet.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/code-snippet.js":
/*!*************************************!*\
  !*** ./src/js/view/code-snippet.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\n * Essential Addons Code Snippet Frontend JavaScript\n * Handles syntax highlighting and copy-to-clipboard functionality\n */\n\n(function () {\n  \"use strict\";\n\n  // Global namespace\n  window.EaelCodeSnippet = window.EaelCodeSnippet || {};\n\n  // Configuration\n  var config = {\n    highlightJsUrl: \"https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js\",\n    highlightJsCssLight: \"https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github.min.css\",\n    highlightJsCssDark: \"https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css\",\n    copySuccessTimeout: 2000,\n    retryAttempts: 3,\n    retryDelay: 1000\n  };\n\n  // State management\n  var highlightJsLoaded = false;\n  var highlightJsLoading = false;\n  var copyButtonsInitialized = false;\n\n  /**\n   * Utility function to load external scripts\n   */\n  function loadScript(src, callback) {\n    var script = document.createElement(\"script\");\n    script.src = src;\n    script.onload = callback;\n    script.onerror = function () {\n      console.warn(\"Essential Addons Code Snippet: Failed to load script:\", src);\n      if (callback) callback(false);\n    };\n    document.head.appendChild(script);\n  }\n\n  /**\n   * Utility function to load external stylesheets\n   */\n  function loadStylesheet(href) {\n    // Check if stylesheet is already loaded\n    var existingLink = document.querySelector(\"link[href=\\\"\".concat(href, \"\\\"]\"));\n    if (existingLink) return;\n    var link = document.createElement(\"link\");\n    link.rel = \"stylesheet\";\n    link.href = href;\n    link.onerror = function () {\n      console.warn(\"Essential Addons Code Snippet: Failed to load stylesheet:\", href);\n    };\n    document.head.appendChild(link);\n  }\n\n  /**\n   * Load Highlight.js library and CSS\n   */\n  function loadHighlightJs(callback) {\n    if (highlightJsLoaded) {\n      if (callback) callback(true);\n      return;\n    }\n    if (highlightJsLoading) {\n      // Wait for current loading to complete\n      var checkLoaded = setInterval(function () {\n        if (highlightJsLoaded || !highlightJsLoading) {\n          clearInterval(checkLoaded);\n          if (callback) callback(highlightJsLoaded);\n        }\n      }, 100);\n      return;\n    }\n    highlightJsLoading = true;\n\n    // Load CSS based on theme\n    var darkThemeSnippets = document.querySelectorAll(\".eael-code-snippet-wrapper.theme-dark\");\n    var lightThemeSnippets = document.querySelectorAll(\".eael-code-snippet-wrapper.theme-light\");\n    if (darkThemeSnippets.length > 0) {\n      loadStylesheet(config.highlightJsCssDark);\n    }\n    if (lightThemeSnippets.length > 0) {\n      loadStylesheet(config.highlightJsCssLight);\n    }\n\n    // Load Highlight.js\n    loadScript(config.highlightJsUrl, function (success) {\n      highlightJsLoading = false;\n      if (success && window.hljs) {\n        highlightJsLoaded = true;\n        console.log(\"Essential Addons Code Snippet: Highlight.js loaded successfully\");\n      } else {\n        console.warn(\"Essential Addons Code Snippet: Failed to load Highlight.js\");\n      }\n      if (callback) callback(highlightJsLoaded);\n    });\n  }\n\n  /**\n   * Apply syntax highlighting to code blocks\n   */\n  function applySyntaxHighlighting() {\n    if (!highlightJsLoaded || !window.hljs) {\n      return;\n    }\n    var codeBlocks = document.querySelectorAll(\".eael-code-snippet-code:not(.hljs)\");\n    codeBlocks.forEach(function (block) {\n      try {\n        window.hljs.highlightElement(block);\n      } catch (error) {\n        console.warn(\"Essential Addons Code Snippet: Error highlighting code block:\", error);\n      }\n    });\n  }\n\n  /**\n   * Copy text to clipboard using modern Clipboard API with fallback\n   */\n  function copyToClipboard(text, callback) {\n    // Modern Clipboard API\n    if (navigator.clipboard && window.isSecureContext) {\n      navigator.clipboard.writeText(text).then(function () {\n        if (callback) callback(true);\n      })[\"catch\"](function (error) {\n        console.warn(\"Essential Addons Code Snippet: Clipboard API failed:\", error);\n        fallbackCopyToClipboard(text, callback);\n      });\n    } else {\n      fallbackCopyToClipboard(text, callback);\n    }\n  }\n\n  /**\n   * Fallback copy method for older browsers\n   */\n  function fallbackCopyToClipboard(text, callback) {\n    var textArea = document.createElement(\"textarea\");\n    textArea.value = text;\n    textArea.style.position = \"fixed\";\n    textArea.style.left = \"-999999px\";\n    textArea.style.top = \"-999999px\";\n    document.body.appendChild(textArea);\n    try {\n      textArea.focus();\n      textArea.select();\n      var successful = document.execCommand(\"copy\");\n      if (callback) callback(successful);\n    } catch (error) {\n      console.warn(\"Essential Addons Code Snippet: Fallback copy failed:\", error);\n      if (callback) callback(false);\n    } finally {\n      document.body.removeChild(textArea);\n    }\n  }\n\n  /**\n   * Show copy success feedback\n   */\n  function showCopySuccess(button) {\n    var container = button.closest(\".eael-code-snippet-copy-container\");\n    var tooltip = container ? container.querySelector(\".eael-code-snippet-tooltip\") : null;\n    var svg = button.querySelector(\"svg\");\n\n    // Change icon to tick with animation\n    if (svg) {\n      var originalSVG = svg.outerHTML;\n\n      // Add transition class\n      svg.style.transition = \"all 0.3s ease\";\n      svg.style.transform = \"scale(0.8)\";\n      setTimeout(function () {\n        // Replace with tick icon\n        svg.innerHTML = '<path d=\"M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z\" fill=\"currentColor\"/>';\n        svg.setAttribute(\"viewBox\", \"0 0 24 24\");\n        svg.style.transform = \"scale(1)\";\n        //svg.style.color = '#22c55e'; // Green color for success\n      }, 150);\n\n      // Restore original icon after 1 second\n      setTimeout(function () {\n        svg.style.transform = \"scale(0.8)\";\n        setTimeout(function () {\n          svg.outerHTML = originalSVG;\n          // Re-get the svg element after replacement\n          var newSvg = button.querySelector(\"svg\");\n          if (newSvg) {\n            newSvg.style.transition = \"all 0.3s ease\";\n            newSvg.style.transform = \"scale(1)\";\n          }\n        }, 150);\n      }, 1000);\n    }\n\n    // Handle tooltip\n    if (tooltip) {\n      var originalText = tooltip.textContent;\n      tooltip.textContent = \"Copied!\";\n\n      // Reposition tooltip after text change\n      if (tooltip.classList.contains(\"show\")) {\n        positionTooltip(button, tooltip);\n      }\n      setTimeout(function () {\n        tooltip.textContent = originalText;\n        // Reposition tooltip back to original size\n        if (tooltip.classList.contains(\"show\")) {\n          positionTooltip(button, tooltip);\n        }\n      }, config.copySuccessTimeout);\n    }\n  }\n\n  /**\n   * Show copy error feedback\n   */\n  function showCopyError(button) {\n    var container = button.closest(\".eael-code-snippet-copy-container\");\n    var tooltip = container ? container.querySelector(\".eael-code-snippet-tooltip\") : null;\n    if (tooltip) {\n      var originalText = tooltip.textContent;\n      tooltip.textContent = \"Copy Failed\";\n\n      // Reposition tooltip after text change\n      if (tooltip.classList.contains(\"show\")) {\n        positionTooltip(button, tooltip);\n      }\n      setTimeout(function () {\n        tooltip.textContent = originalText;\n        // Reposition tooltip back to original size\n        if (tooltip.classList.contains(\"show\")) {\n          positionTooltip(button, tooltip);\n        }\n      }, 2000);\n    }\n  }\n\n  /**\n   * Position tooltip relative to button\n   */\n  function positionTooltip(button, tooltip) {\n    var buttonRect = button.getBoundingClientRect();\n    var tooltipRect = tooltip.getBoundingClientRect();\n\n    // Position above the button, centered\n    var left = buttonRect.left + buttonRect.width / 2 - tooltipRect.width / 2;\n    var top = buttonRect.top - tooltipRect.height - 8;\n    tooltip.style.left = Math.max(8, left) + \"px\";\n    tooltip.style.top = Math.max(8, top) + \"px\";\n  }\n\n  /**\n   * Initialize tooltip functionality\n   */\n  function initTooltip(button) {\n    var container = button.closest(\".eael-code-snippet-copy-container\");\n    var tooltip = container ? container.querySelector(\".eael-code-snippet-tooltip\") : null;\n    if (!tooltip) return;\n\n    // Show tooltip on hover\n    button.addEventListener(\"mouseenter\", function () {\n      tooltip.classList.add(\"show\");\n      positionTooltip(button, tooltip);\n    });\n\n    // Hide tooltip on leave\n    button.addEventListener(\"mouseleave\", function () {\n      tooltip.classList.remove(\"show\");\n    });\n\n    // Hide tooltip on focus out\n    button.addEventListener(\"blur\", function () {\n      tooltip.classList.remove(\"show\");\n    });\n\n    // Reposition on scroll/resize\n    window.addEventListener(\"scroll\", function () {\n      if (tooltip.classList.contains(\"show\")) {\n        positionTooltip(button, tooltip);\n      }\n    });\n    window.addEventListener(\"resize\", function () {\n      if (tooltip.classList.contains(\"show\")) {\n        positionTooltip(button, tooltip);\n      }\n    });\n  }\n\n  /**\n   * Initialize copy button for a specific snippet\n   */\n  function initCopyButton(snippet) {\n    var copyButton = snippet.querySelector(\".eael-code-snippet-copy-button\");\n    var codeElement = snippet.querySelector(\".eael-code-snippet-code code\");\n    if (!copyButton || !codeElement) {\n      return;\n    }\n\n    // Initialize tooltip functionality\n    initTooltip(copyButton);\n\n    // Remove any existing event listeners\n    copyButton.removeEventListener(\"click\", copyButton._eaelClickHandler);\n\n    // Create new click handler\n    copyButton._eaelClickHandler = function (event) {\n      event.preventDefault();\n      var codeText = codeElement.textContent || codeElement.innerText || \"\";\n      if (!codeText.trim()) {\n        console.warn(\"Essential Addons Code Snippet: No Code snippet to copy\");\n        return;\n      }\n      copyToClipboard(codeText, function (success) {\n        if (success) {\n          showCopySuccess(copyButton);\n\n          // Fire custom event\n          var customEvent = new CustomEvent(\"eael-code-copied\", {\n            detail: {\n              snippet: snippet,\n              code: codeText,\n              language: snippet.dataset.language\n            }\n          });\n          document.dispatchEvent(customEvent);\n        } else {\n          console.error(\"Essential Addons Code Snippet: Failed to copy code to clipboard\");\n          showCopyError(copyButton);\n        }\n      });\n    };\n\n    // Add event listener\n    copyButton.addEventListener(\"click\", copyButton._eaelClickHandler);\n  }\n\n  /**\n   * Initialize all code snippets on the page\n   */\n  function initializeCodeSnippets() {\n    var snippets = document.querySelectorAll(\".eael-code-snippet-wrapper\");\n    if (snippets.length === 0) {\n      return;\n    }\n\n    // Load Highlight.js if needed\n    loadHighlightJs(function (loaded) {\n      if (loaded) {\n        applySyntaxHighlighting();\n      }\n    });\n\n    // Initialize copy buttons\n    snippets.forEach(function (snippet) {\n      var hasCopyButton = snippet.dataset.copyButton === \"true\";\n      if (hasCopyButton) {\n        initCopyButton(snippet);\n      }\n    });\n    copyButtonsInitialized = true;\n  }\n\n  /**\n   * Re-initialize code snippets (useful for dynamic content)\n   */\n  function reinitialize() {\n    copyButtonsInitialized = false;\n    initializeCodeSnippets();\n  }\n\n  /**\n   * Public API\n   */\n  window.EaelCodeSnippet = {\n    init: initializeCodeSnippets,\n    reinit: reinitialize,\n    initCopyButton: initCopyButton,\n    applySyntaxHighlighting: applySyntaxHighlighting,\n    loadHighlightJs: loadHighlightJs\n  };\n\n  // Auto-initialize when DOM is ready\n  if (document.readyState === \"loading\") {\n    document.addEventListener(\"DOMContentLoaded\", initializeCodeSnippets);\n  } else {\n    initializeCodeSnippets();\n  }\n\n  // Re-initialize on dynamic content changes (for AJAX-loaded content)\n  if (window.MutationObserver) {\n    var observer = new MutationObserver(function (mutations) {\n      var shouldReinit = false;\n      mutations.forEach(function (mutation) {\n        if (mutation.type === \"childList\") {\n          mutation.addedNodes.forEach(function (node) {\n            if (node.nodeType === 1) {\n              // Element node\n              if (node.classList && node.classList.contains(\"eael-code-snippet-wrapper\")) {\n                shouldReinit = true;\n              } else if (node.querySelector && node.querySelector(\".eael-code-snippet-wrapper\")) {\n                shouldReinit = true;\n              }\n            }\n          });\n        }\n      });\n      if (shouldReinit) {\n        setTimeout(reinitialize, 100); // Small delay to ensure DOM is stable\n      }\n    });\n    observer.observe(document.body, {\n      childList: true,\n      subtree: true\n    });\n  }\n})();\n\n//# sourceURL=webpack:///./src/js/view/code-snippet.js?");

/***/ })

/******/ });