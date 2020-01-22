!function(t,s,e){"use strict";var i=function(t,s){var i=this;this.el=t,this.options={},Object.keys(r).forEach(function(t){i.options[t]=r[t]}),Object.keys(s).forEach(function(t){i.options[t]=s[t]}),this.isInput="input"===this.el.tagName.toLowerCase(),this.attr=this.options.attr,this.showCursor=!this.isInput&&this.options.showCursor,this.elContent=this.attr?this.el.getAttribute(this.attr):this.el.textContent,this.contentType=this.options.contentType,this.typeSpeed=this.options.typeSpeed,this.startDelay=this.options.startDelay,this.backSpeed=this.options.backSpeed,this.backDelay=this.options.backDelay,e&&this.options.stringsElement instanceof e?this.stringsElement=this.options.stringsElement[0]:this.stringsElement=this.options.stringsElement,this.strings=this.options.strings,this.strPos=0,this.arrayPos=0,this.stopNum=0,this.loop=this.options.loop,this.loopCount=this.options.loopCount,this.curLoop=0,this.stop=!1,this.cursorChar=this.options.cursorChar,this.shuffle=this.options.shuffle,this.sequence=[],this.build()};i.prototype={constructor:i,init:function(){var t=this;t.timeout=setTimeout(function(){for(var s=0;s<t.strings.length;++s)t.sequence[s]=s;t.shuffle&&(t.sequence=t.shuffleArray(t.sequence)),t.typewrite(t.strings[t.sequence[t.arrayPos]],t.strPos)},t.startDelay)},build:function(){var t=this;if(this.showCursor===!0&&(this.cursor=s.createElement("span"),this.cursor.className="typed-cursor",this.cursor.innerHTML=this.cursorChar,this.el.parentNode&&this.el.parentNode.insertBefore(this.cursor,this.el.nextSibling)),this.stringsElement){this.strings=[],this.stringsElement.style.display="none";var e=Array.prototype.slice.apply(this.stringsElement.children);e.forEach(function(s){t.strings.push(s.innerHTML)})}this.init()},typewrite:function(t,s){if(this.stop!==!0){var e=Math.round(70*Math.random())+this.typeSpeed,i=this;i.timeout=setTimeout(function(){var e=0,r=t.substr(s);if("^"===r.charAt(0)){var o=1;/^\^\d+/.test(r)&&(r=/\d+/.exec(r)[0],o+=r.length,e=parseInt(r)),t=t.substring(0,s)+t.substring(s+o)}if("html"===i.contentType){var n=t.substr(s).charAt(0);if("<"===n||"&"===n){var a="",h="";for(h="<"===n?">":";";t.substr(s+1).charAt(0)!==h&&(a+=t.substr(s).charAt(0),s++,!(s+1>t.length)););s++,a+=h}}i.timeout=setTimeout(function(){if(s===t.length){if(i.options.onStringTyped(i.arrayPos),i.arrayPos===i.strings.length-1&&(i.options.callback(),i.curLoop++,i.loop===!1||i.curLoop===i.loopCount))return;i.timeout=setTimeout(function(){i.backspace(t,s)},i.backDelay)}else{0===s&&i.options.preStringTyped(i.arrayPos);var e=t.substr(0,s+1);i.attr?i.el.setAttribute(i.attr,e):i.isInput?i.el.value=e:"html"===i.contentType?i.el.innerHTML=e:i.el.textContent=e,s++,i.typewrite(t,s)}},e)},e)}},backspace:function(t,s){if(this.stop!==!0){var e=Math.round(70*Math.random())+this.backSpeed,i=this;i.timeout=setTimeout(function(){if("html"===i.contentType&&">"===t.substr(s).charAt(0)){for(var e="";"<"!==t.substr(s-1).charAt(0)&&(e-=t.substr(s).charAt(0),s--,!(s<0)););s--,e+="<"}var r=t.substr(0,s);i.attr?i.el.setAttribute(i.attr,r):i.isInput?i.el.value=r:"html"===i.contentType?i.el.innerHTML=r:i.el.textContent=r,s>i.stopNum?(s--,i.backspace(t,s)):s<=i.stopNum&&(i.arrayPos++,i.arrayPos===i.strings.length?(i.arrayPos=0,i.shuffle&&(i.sequence=i.shuffleArray(i.sequence)),i.init()):i.typewrite(i.strings[i.sequence[i.arrayPos]],s))},e)}},shuffleArray:function(t){var s,e,i=t.length;if(i)for(;--i;)e=Math.floor(Math.random()*(i+1)),s=t[e],t[e]=t[i],t[i]=s;return t},reset:function(){var t=this;clearInterval(t.timeout);this.el.getAttribute("id");this.el.textContent="","undefined"!=typeof this.cursor&&"undefined"!=typeof this.cursor.parentNode&&this.cursor.parentNode.removeChild(this.cursor),this.strPos=0,this.arrayPos=0,this.curLoop=0,this.options.resetCallback()}},i["new"]=function(t,e){var r=Array.prototype.slice.apply(s.querySelectorAll(t));r.forEach(function(t){var s=t._typed,r="object"==typeof e&&e;s&&s.reset(),t._typed=s=new i(t,r),"string"==typeof e&&s[e]()})},e&&(e.fn.typed=function(t){return this.each(function(){var s=e(this),r=s.data("typed"),o="object"==typeof t&&t;r&&r.reset(),s.data("typed",r=new i(this,o)),"string"==typeof t&&r[t]()})}),t.Typed=i;var r={strings:["These are the default values...","You know what you should do?","Use your own!","Have a great day!"],stringsElement:null,typeSpeed:0,startDelay:0,backSpeed:0,shuffle:!1,backDelay:500,loop:!1,loopCount:!1,showCursor:!0,cursorChar:"|",attr:null,contentType:"html",callback:function(){},preStringTyped:function(){},onStringTyped:function(){},resetCallback:function(){}}}(window,document,window.jQuery);
!function(a){"use strict";function b(b,c){this.element=a(b),this.settings=a.extend({},d,c),this._defaults=d,this._init()}var c="Morphext",d={animation:"bounceIn",separator:",",speed:2e3,complete:a.noop};b.prototype={_init:function(){var b=this;this.phrases=[],this.element.addClass("morphext"),a.each(this.element.text().split(this.settings.separator),function(c,d){b.phrases.push(a.trim(d))}),this.index=-1,this.animate(),this.start()},animate:function(){this.index=++this.index%this.phrases.length,this.element[0].innerHTML='<span class="animated '+this.settings.animation+'">'+this.phrases[this.index]+"</span>",a.isFunction(this.settings.complete)&&this.settings.complete.call(this)},start:function(){var a=this;this._interval=setInterval(function(){a.animate()},this.settings.speed)},stop:function(){this._interval=clearInterval(this._interval)}},a.fn[c]=function(d){return this.each(function(){a.data(this,"plugin_"+c)||a.data(this,"plugin_"+c,new b(this,d))})}}(jQuery);

/*!
 * Morphext - Text Rotating Plugin for jQuery
 * https://github.com/MrSaints/Morphext
 *
 * Built on jQuery Boilerplate
 * http://jqueryboilerplate.com/
 *
 * Copyright 2014 Ian Lai and other contributors
 * Released under the MIT license
 * http://ian.mit-license.org/
 */

/*eslint-env browser */
/*global jQuery:false */
/*eslint-disable no-underscore-dangle */

(function ($) {
    "use strict";

    var pluginName = "Morphext",
        defaults = {
            animation: "bounceIn",
            separator: ",",
            speed: 2000,
            complete: $.noop
        };

    function Plugin (element, options) {
        this.element = $(element);

        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._init();
    }

    Plugin.prototype = {
        _init: function () {
            var $that = this;
            this.phrases = [];

            this.element.addClass("morphext");

            $.each(this.element.text().split(this.settings.separator), function (key, value) {
                $that.phrases.push($.trim(value));
            });

            this.index = -1;
            this.animate();
            this.start();
        },
        animate: function () {
            this.index = ++this.index % this.phrases.length;
            this.element[0].innerHTML = "<span class=\"animated " + this.settings.animation + "\">" + this.phrases[this.index] + "</span>";

            if ($.isFunction(this.settings.complete)) {
                this.settings.complete.call(this);
            }
        },
        start: function () {
            var $that = this;
            this._interval = setInterval(function () {
                $that.animate();
            }, this.settings.speed);
        },
        stop: function () {
            this._interval = clearInterval(this._interval);
        }
    };

    $.fn[pluginName] = function (options) {
        return this.each(function() {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };
})(jQuery);

/*!
 * 
 *   typed.js - A JavaScript Typing Animation Library
 *   Author: Matt Boldt <me@mattboldt.com>
 *   Version: v2.0.9
 *   Url: https://github.com/mattboldt/typed.js
 *   License(s): MIT
 * 
 */
(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["Typed"] = factory();
	else
		root["Typed"] = factory();
})(this, function() {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;
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
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, '__esModule', {
	  value: true
	});
	
	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }
	
	var _initializerJs = __webpack_require__(1);
	
	var _htmlParserJs = __webpack_require__(3);
	
	/**
	 * Welcome to Typed.js!
	 * @param {string} elementId HTML element ID _OR_ HTML element
	 * @param {object} options options object
	 * @returns {object} a new Typed object
	 */
	
	var Typed = (function () {
	  function Typed(elementId, options) {
	    _classCallCheck(this, Typed);
	
	    // Initialize it up
	    _initializerJs.initializer.load(this, options, elementId);
	    // All systems go!
	    this.begin();
	  }
	
	  /**
	   * Toggle start() and stop() of the Typed instance
	   * @public
	   */
	
	  _createClass(Typed, [{
	    key: 'toggle',
	    value: function toggle() {
	      this.pause.status ? this.start() : this.stop();
	    }
	
	    /**
	     * Stop typing / backspacing and enable cursor blinking
	     * @public
	     */
	  }, {
	    key: 'stop',
	    value: function stop() {
	      if (this.typingComplete) return;
	      if (this.pause.status) return;
	      this.toggleBlinking(true);
	      this.pause.status = true;
	      this.options.onStop(this.arrayPos, this);
	    }
	
	    /**
	     * Start typing / backspacing after being stopped
	     * @public
	     */
	  }, {
	    key: 'start',
	    value: function start() {
	      if (this.typingComplete) return;
	      if (!this.pause.status) return;
	      this.pause.status = false;
	      if (this.pause.typewrite) {
	        this.typewrite(this.pause.curString, this.pause.curStrPos);
	      } else {
	        this.backspace(this.pause.curString, this.pause.curStrPos);
	      }
	      this.options.onStart(this.arrayPos, this);
	    }
	
	    /**
	     * Destroy this instance of Typed
	     * @public
	     */
	  }, {
	    key: 'destroy',
	    value: function destroy() {
	      this.reset(false);
	      this.options.onDestroy(this);
	    }
	
	    /**
	     * Reset Typed and optionally restarts
	     * @param {boolean} restart
	     * @public
	     */
	  }, {
	    key: 'reset',
	    value: function reset() {
	      var restart = arguments.length <= 0 || arguments[0] === undefined ? true : arguments[0];
	
	      clearInterval(this.timeout);
	      this.replaceText('');
	      if (this.cursor && this.cursor.parentNode) {
	        this.cursor.parentNode.removeChild(this.cursor);
	        this.cursor = null;
	      }
	      this.strPos = 0;
	      this.arrayPos = 0;
	      this.curLoop = 0;
	      if (restart) {
	        this.insertCursor();
	        this.options.onReset(this);
	        this.begin();
	      }
	    }
	
	    /**
	     * Begins the typing animation
	     * @private
	     */
	  }, {
	    key: 'begin',
	    value: function begin() {
	      var _this = this;
	
	      this.typingComplete = false;
	      this.shuffleStringsIfNeeded(this);
	      this.insertCursor();
	      if (this.bindInputFocusEvents) this.bindFocusEvents();
	      this.timeout = setTimeout(function () {
	        // Check if there is some text in the element, if yes start by backspacing the default message
	        if (!_this.currentElContent || _this.currentElContent.length === 0) {
	          _this.typewrite(_this.strings[_this.sequence[_this.arrayPos]], _this.strPos);
	        } else {
	          // Start typing
	          _this.backspace(_this.currentElContent, _this.currentElContent.length);
	        }
	      }, this.startDelay);
	    }
	
	    /**
	     * Called for each character typed
	     * @param {string} curString the current string in the strings array
	     * @param {number} curStrPos the current position in the curString
	     * @private
	     */
	  }, {
	    key: 'typewrite',
	    value: function typewrite(curString, curStrPos) {
	      var _this2 = this;
	
	      if (this.fadeOut && this.el.classList.contains(this.fadeOutClass)) {
	        this.el.classList.remove(this.fadeOutClass);
	        if (this.cursor) this.cursor.classList.remove(this.fadeOutClass);
	      }
	
	      var humanize = this.humanizer(this.typeSpeed);
	      var numChars = 1;
	
	      if (this.pause.status === true) {
	        this.setPauseStatus(curString, curStrPos, true);
	        return;
	      }
	
	      // contain typing function in a timeout humanize'd delay
	      this.timeout = setTimeout(function () {
	        // skip over any HTML chars
	        curStrPos = _htmlParserJs.htmlParser.typeHtmlChars(curString, curStrPos, _this2);
	
	        var pauseTime = 0;
	        var substr = curString.substr(curStrPos);
	        // check for an escape character before a pause value
	        // format: \^\d+ .. eg: ^1000 .. should be able to print the ^ too using ^^
	        // single ^ are removed from string
	        if (substr.charAt(0) === '^') {
	          if (/^\^\d+/.test(substr)) {
	            var skip = 1; // skip at least 1
	            substr = /\d+/.exec(substr)[0];
	            skip += substr.length;
	            pauseTime = parseInt(substr);
	            _this2.temporaryPause = true;
	            _this2.options.onTypingPaused(_this2.arrayPos, _this2);
	            // strip out the escape character and pause value so they're not printed
	            curString = curString.substring(0, curStrPos) + curString.substring(curStrPos + skip);
	            _this2.toggleBlinking(true);
	          }
	        }
	
	        // check for skip characters formatted as
	        // "this is a `string to print NOW` ..."
	        if (substr.charAt(0) === '`') {
	          while (curString.substr(curStrPos + numChars).charAt(0) !== '`') {
	            numChars++;
	            if (curStrPos + numChars > curString.length) break;
	          }
	          // strip out the escape characters and append all the string in between
	          var stringBeforeSkip = curString.substring(0, curStrPos);
	          var stringSkipped = curString.substring(stringBeforeSkip.length + 1, curStrPos + numChars);
	          var stringAfterSkip = curString.substring(curStrPos + numChars + 1);
	          curString = stringBeforeSkip + stringSkipped + stringAfterSkip;
	          numChars--;
	        }
	
	        // timeout for any pause after a character
	        _this2.timeout = setTimeout(function () {
	          // Accounts for blinking while paused
	          _this2.toggleBlinking(false);
	
	          // We're done with this sentence!
	          if (curStrPos >= curString.length) {
	            _this2.doneTyping(curString, curStrPos);
	          } else {
	            _this2.keepTyping(curString, curStrPos, numChars);
	          }
	          // end of character pause
	          if (_this2.temporaryPause) {
	            _this2.temporaryPause = false;
	            _this2.options.onTypingResumed(_this2.arrayPos, _this2);
	          }
	        }, pauseTime);
	
	        // humanized value for typing
	      }, humanize);
	    }
	
	    /**
	     * Continue to the next string & begin typing
	     * @param {string} curString the current string in the strings array
	     * @param {number} curStrPos the current position in the curString
	     * @private
	     */
	  }, {
	    key: 'keepTyping',
	    value: function keepTyping(curString, curStrPos, numChars) {
	      // call before functions if applicable
	      if (curStrPos === 0) {
	        this.toggleBlinking(false);
	        this.options.preStringTyped(this.arrayPos, this);
	      }
	      // start typing each new char into existing string
	      // curString: arg, this.el.html: original text inside element
	      curStrPos += numChars;
	      var nextString = curString.substr(0, curStrPos);
	      this.replaceText(nextString);
	      // loop the function
	      this.typewrite(curString, curStrPos);
	    }
	
	    /**
	     * We're done typing all strings
	     * @param {string} curString the current string in the strings array
	     * @param {number} curStrPos the current position in the curString
	     * @private
	     */
	  }, {
	    key: 'doneTyping',
	    value: function doneTyping(curString, curStrPos) {
	      var _this3 = this;
	
	      // fires callback function
	      this.options.onStringTyped(this.arrayPos, this);
	      this.toggleBlinking(true);
	      // is this the final string
	      if (this.arrayPos === this.strings.length - 1) {
	        // callback that occurs on the last typed string
	        this.complete();
	        // quit if we wont loop back
	        if (this.loop === false || this.curLoop === this.loopCount) {
	          return;
	        }
	      }
	      this.timeout = setTimeout(function () {
	        _this3.backspace(curString, curStrPos);
	      }, this.backDelay);
	    }
	
	    /**
	     * Backspaces 1 character at a time
	     * @param {string} curString the current string in the strings array
	     * @param {number} curStrPos the current position in the curString
	     * @private
	     */
	  }, {
	    key: 'backspace',
	    value: function backspace(curString, curStrPos) {
	      var _this4 = this;
	
	      if (this.pause.status === true) {
	        this.setPauseStatus(curString, curStrPos, true);
	        return;
	      }
	      if (this.fadeOut) return this.initFadeOut();
	
	      this.toggleBlinking(false);
	      var humanize = this.humanizer(this.backSpeed);
	
	      this.timeout = setTimeout(function () {
	        curStrPos = _htmlParserJs.htmlParser.backSpaceHtmlChars(curString, curStrPos, _this4);
	        // replace text with base text + typed characters
	        var curStringAtPosition = curString.substr(0, curStrPos);
	        _this4.replaceText(curStringAtPosition);
	
	        // if smartBack is enabled
	        if (_this4.smartBackspace) {
	          // the remaining part of the current string is equal of the same part of the new string
	          var nextString = _this4.strings[_this4.arrayPos + 1];
	          if (nextString && curStringAtPosition === nextString.substr(0, curStrPos)) {
	            _this4.stopNum = curStrPos;
	          } else {
	            _this4.stopNum = 0;
	          }
	        }
	
	        // if the number (id of character in current string) is
	        // less than the stop number, keep going
	        if (curStrPos > _this4.stopNum) {
	          // subtract characters one by one
	          curStrPos--;
	          // loop the function
	          _this4.backspace(curString, curStrPos);
	        } else if (curStrPos <= _this4.stopNum) {
	          // if the stop number has been reached, increase
	          // array position to next string
	          _this4.arrayPos++;
	          // When looping, begin at the beginning after backspace complete
	          if (_this4.arrayPos === _this4.strings.length) {
	            _this4.arrayPos = 0;
	            _this4.options.onLastStringBackspaced();
	            _this4.shuffleStringsIfNeeded();
	            _this4.begin();
	          } else {
	            _this4.typewrite(_this4.strings[_this4.sequence[_this4.arrayPos]], curStrPos);
	          }
	        }
	        // humanized value for typing
	      }, humanize);
	    }
	
	    /**
	     * Full animation is complete
	     * @private
	     */
	  }, {
	    key: 'complete',
	    value: function complete() {
	      this.options.onComplete(this);
	      if (this.loop) {
	        this.curLoop++;
	      } else {
	        this.typingComplete = true;
	      }
	    }
	
	    /**
	     * Has the typing been stopped
	     * @param {string} curString the current string in the strings array
	     * @param {number} curStrPos the current position in the curString
	     * @param {boolean} isTyping
	     * @private
	     */
	  }, {
	    key: 'setPauseStatus',
	    value: function setPauseStatus(curString, curStrPos, isTyping) {
	      this.pause.typewrite = isTyping;
	      this.pause.curString = curString;
	      this.pause.curStrPos = curStrPos;
	    }
	
	    /**
	     * Toggle the blinking cursor
	     * @param {boolean} isBlinking
	     * @private
	     */
	  }, {
	    key: 'toggleBlinking',
	    value: function toggleBlinking(isBlinking) {
	      if (!this.cursor) return;
	      // if in paused state, don't toggle blinking a 2nd time
	      if (this.pause.status) return;
	      if (this.cursorBlinking === isBlinking) return;
	      this.cursorBlinking = isBlinking;
	      if (isBlinking) {
	        this.cursor.classList.add('typed-cursor--blink');
	      } else {
	        this.cursor.classList.remove('typed-cursor--blink');
	      }
	    }
	
	    /**
	     * Speed in MS to type
	     * @param {number} speed
	     * @private
	     */
	  }, {
	    key: 'humanizer',
	    value: function humanizer(speed) {
	      return Math.round(Math.random() * speed / 2) + speed;
	    }
	
	    /**
	     * Shuffle the sequence of the strings array
	     * @private
	     */
	  }, {
	    key: 'shuffleStringsIfNeeded',
	    value: function shuffleStringsIfNeeded() {
	      if (!this.shuffle) return;
	      this.sequence = this.sequence.sort(function () {
	        return Math.random() - 0.5;
	      });
	    }
	
	    /**
	     * Adds a CSS class to fade out current string
	     * @private
	     */
	  }, {
	    key: 'initFadeOut',
	    value: function initFadeOut() {
	      var _this5 = this;
	
	      this.el.className += ' ' + this.fadeOutClass;
	      if (this.cursor) this.cursor.className += ' ' + this.fadeOutClass;
	      return setTimeout(function () {
	        _this5.arrayPos++;
	        _this5.replaceText('');
	
	        // Resets current string if end of loop reached
	        if (_this5.strings.length > _this5.arrayPos) {
	          _this5.typewrite(_this5.strings[_this5.sequence[_this5.arrayPos]], 0);
	        } else {
	          _this5.typewrite(_this5.strings[0], 0);
	          _this5.arrayPos = 0;
	        }
	      }, this.fadeOutDelay);
	    }
	
	    /**
	     * Replaces current text in the HTML element
	     * depending on element type
	     * @param {string} str
	     * @private
	     */
	  }, {
	    key: 'replaceText',
	    value: function replaceText(str) {
	      if (this.attr) {
	        this.el.setAttribute(this.attr, str);
	      } else {
	        if (this.isInput) {
	          this.el.value = str;
	        } else if (this.contentType === 'html') {
	          this.el.innerHTML = str;
	        } else {
	          this.el.textContent = str;
	        }
	      }
	    }
	
	    /**
	     * If using input elements, bind focus in order to
	     * start and stop the animation
	     * @private
	     */
	  }, {
	    key: 'bindFocusEvents',
	    value: function bindFocusEvents() {
	      var _this6 = this;
	
	      if (!this.isInput) return;
	      this.el.addEventListener('focus', function (e) {
	        _this6.stop();
	      });
	      this.el.addEventListener('blur', function (e) {
	        if (_this6.el.value && _this6.el.value.length !== 0) {
	          return;
	        }
	        _this6.start();
	      });
	    }
	
	    /**
	     * On init, insert the cursor element
	     * @private
	     */
	  }, {
	    key: 'insertCursor',
	    value: function insertCursor() {
	      if (!this.showCursor) return;
	      if (this.cursor) return;
	      this.cursor = document.createElement('span');
	      this.cursor.className = 'typed-cursor';
	      this.cursor.innerHTML = this.cursorChar;
	      this.el.parentNode && this.el.parentNode.insertBefore(this.cursor, this.el.nextSibling);
	    }
	  }]);
	
	  return Typed;
	})();
	
	exports['default'] = Typed;
	module.exports = exports['default'];

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

	'use strict';
	
	Object.defineProperty(exports, '__esModule', {
	  value: true
	});
	
	var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };
	
	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();
	
	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }
	
	var _defaultsJs = __webpack_require__(2);
	
	var _defaultsJs2 = _interopRequireDefault(_defaultsJs);
	
	/**
	 * Initialize the Typed object
	 */
	
	var Initializer = (function () {
	  function Initializer() {
	    _classCallCheck(this, Initializer);
	  }
	
	  _createClass(Initializer, [{
	    key: 'load',
	
	    /**
	     * Load up defaults & options on the Typed instance
	     * @param {Typed} self instance of Typed
	     * @param {object} options options object
	     * @param {string} elementId HTML element ID _OR_ instance of HTML element
	     * @private
	     */
	
	    value: function load(self, options, elementId) {
	      // chosen element to manipulate text
	      if (typeof elementId === 'string') {
	        self.el = document.querySelector(elementId);
	      } else {
	        self.el = elementId;
	      }
	
	      self.options = _extends({}, _defaultsJs2['default'], options);
	
	      // attribute to type into
	      self.isInput = self.el.tagName.toLowerCase() === 'input';
	      self.attr = self.options.attr;
	      self.bindInputFocusEvents = self.options.bindInputFocusEvents;
	
	      // show cursor
	      self.showCursor = self.isInput ? false : self.options.showCursor;
	
	      // custom cursor
	      self.cursorChar = self.options.cursorChar;
	
	      // Is the cursor blinking
	      self.cursorBlinking = true;
	
	      // text content of element
	      self.elContent = self.attr ? self.el.getAttribute(self.attr) : self.el.textContent;
	
	      // html or plain text
	      self.contentType = self.options.contentType;
	
	      // typing speed
	      self.typeSpeed = self.options.typeSpeed;
	
	      // add a delay before typing starts
	      self.startDelay = self.options.startDelay;
	
	      // backspacing speed
	      self.backSpeed = self.options.backSpeed;
	
	      // only backspace what doesn't match the previous string
	      self.smartBackspace = self.options.smartBackspace;
	
	      // amount of time to wait before backspacing
	      self.backDelay = self.options.backDelay;
	
	      // Fade out instead of backspace
	      self.fadeOut = self.options.fadeOut;
	      self.fadeOutClass = self.options.fadeOutClass;
	      self.fadeOutDelay = self.options.fadeOutDelay;
	
	      // variable to check whether typing is currently paused
	      self.isPaused = false;
	
	      // input strings of text
	      self.strings = self.options.strings.map(function (s) {
	        return s.trim();
	      });
	
	      // div containing strings
	      if (typeof self.options.stringsElement === 'string') {
	        self.stringsElement = document.querySelector(self.options.stringsElement);
	      } else {
	        self.stringsElement = self.options.stringsElement;
	      }
	
	      if (self.stringsElement) {
	        self.strings = [];
	        self.stringsElement.style.display = 'none';
	        var strings = Array.prototype.slice.apply(self.stringsElement.children);
	        var stringsLength = strings.length;
	
	        if (stringsLength) {
	          for (var i = 0; i < stringsLength; i += 1) {
	            var stringEl = strings[i];
	            self.strings.push(stringEl.innerHTML.trim());
	          }
	        }
	      }
	
	      // character number position of current string
	      self.strPos = 0;
	
	      // current array position
	      self.arrayPos = 0;
	
	      // index of string to stop backspacing on
	      self.stopNum = 0;
	
	      // Looping logic
	      self.loop = self.options.loop;
	      self.loopCount = self.options.loopCount;
	      self.curLoop = 0;
	
	      // shuffle the strings
	      self.shuffle = self.options.shuffle;
	      // the order of strings
	      self.sequence = [];
	
	      self.pause = {
	        status: false,
	        typewrite: true,
	        curString: '',
	        curStrPos: 0
	      };
	
	      // When the typing is complete (when not looped)
	      self.typingComplete = false;
	
	      // Set the order in which the strings are typed
	      for (var i in self.strings) {
	        self.sequence[i] = i;
	      }
	
	      // If there is some text in the element
	      self.currentElContent = this.getCurrentElContent(self);
	
	      self.autoInsertCss = self.options.autoInsertCss;
	
	      this.appendAnimationCss(self);
	    }
	  }, {
	    key: 'getCurrentElContent',
	    value: function getCurrentElContent(self) {
	      var elContent = '';
	      if (self.attr) {
	        elContent = self.el.getAttribute(self.attr);
	      } else if (self.isInput) {
	        elContent = self.el.value;
	      } else if (self.contentType === 'html') {
	        elContent = self.el.innerHTML;
	      } else {
	        elContent = self.el.textContent;
	      }
	      return elContent;
	    }
	  }, {
	    key: 'appendAnimationCss',
	    value: function appendAnimationCss(self) {
	      var cssDataName = 'data-typed-js-css';
	      if (!self.autoInsertCss) {
	        return;
	      }
	      if (!self.showCursor && !self.fadeOut) {
	        return;
	      }
	      if (document.querySelector('[' + cssDataName + ']')) {
	        return;
	      }
	
	      var css = document.createElement('style');
	      css.type = 'text/css';
	      css.setAttribute(cssDataName, true);
	
	      var innerCss = '';
	      if (self.showCursor) {
	        innerCss += '\n        .typed-cursor{\n          opacity: 1;\n        }\n        .typed-cursor.typed-cursor--blink{\n          animation: typedjsBlink 0.7s infinite;\n          -webkit-animation: typedjsBlink 0.7s infinite;\n                  animation: typedjsBlink 0.7s infinite;\n        }\n        @keyframes typedjsBlink{\n          50% { opacity: 0.0; }\n        }\n        @-webkit-keyframes typedjsBlink{\n          0% { opacity: 1; }\n          50% { opacity: 0.0; }\n          100% { opacity: 1; }\n        }\n      ';
	      }
	      if (self.fadeOut) {
	        innerCss += '\n        .typed-fade-out{\n          opacity: 0;\n          transition: opacity .25s;\n        }\n        .typed-cursor.typed-cursor--blink.typed-fade-out{\n          -webkit-animation: 0;\n          animation: 0;\n        }\n      ';
	      }
	      if (css.length === 0) {
	        return;
	      }
	      css.innerHTML = innerCss;
	      document.body.appendChild(css);
	    }
	  }]);
	
	  return Initializer;
	})();
	
	exports['default'] = Initializer;
	var initializer = new Initializer();
	exports.initializer = initializer;

/***/ }),
/* 2 */
/***/ (function(module, exports) {

	/**
	 * Defaults & options
	 * @returns {object} Typed defaults & options
	 * @public
	 */
	
	'use strict';
	
	Object.defineProperty(exports, '__esModule', {
	  value: true
	});
	var defaults = {
	  /**
	   * @property {array} strings strings to be typed
	   * @property {string} stringsElement ID of element containing string children
	   */
	  strings: ['These are the default values...', 'You know what you should do?', 'Use your own!', 'Have a great day!'],
	  stringsElement: null,
	
	  /**
	   * @property {number} typeSpeed type speed in milliseconds
	   */
	  typeSpeed: 0,
	
	  /**
	   * @property {number} startDelay time before typing starts in milliseconds
	   */
	  startDelay: 0,
	
	  /**
	   * @property {number} backSpeed backspacing speed in milliseconds
	   */
	  backSpeed: 0,
	
	  /**
	   * @property {boolean} smartBackspace only backspace what doesn't match the previous string
	   */
	  smartBackspace: true,
	
	  /**
	   * @property {boolean} shuffle shuffle the strings
	   */
	  shuffle: false,
	
	  /**
	   * @property {number} backDelay time before backspacing in milliseconds
	   */
	  backDelay: 700,
	
	  /**
	   * @property {boolean} fadeOut Fade out instead of backspace
	   * @property {string} fadeOutClass css class for fade animation
	   * @property {boolean} fadeOutDelay Fade out delay in milliseconds
	   */
	  fadeOut: false,
	  fadeOutClass: 'typed-fade-out',
	  fadeOutDelay: 500,
	
	  /**
	   * @property {boolean} loop loop strings
	   * @property {number} loopCount amount of loops
	   */
	  loop: false,
	  loopCount: Infinity,
	
	  /**
	   * @property {boolean} showCursor show cursor
	   * @property {string} cursorChar character for cursor
	   * @property {boolean} autoInsertCss insert CSS for cursor and fadeOut into HTML <head>
	   */
	  showCursor: true,
	  cursorChar: '|',
	  autoInsertCss: true,
	
	  /**
	   * @property {string} attr attribute for typing
	   * Ex: input placeholder, value, or just HTML text
	   */
	  attr: null,
	
	  /**
	   * @property {boolean} bindInputFocusEvents bind to focus and blur if el is text input
	   */
	  bindInputFocusEvents: false,
	
	  /**
	   * @property {string} contentType 'html' or 'null' for plaintext
	   */
	  contentType: 'html',
	
	  /**
	   * All typing is complete
	   * @param {Typed} self
	   */
	  onComplete: function onComplete(self) {},
	
	  /**
	   * Before each string is typed
	   * @param {number} arrayPos
	   * @param {Typed} self
	   */
	  preStringTyped: function preStringTyped(arrayPos, self) {},
	
	  /**
	   * After each string is typed
	   * @param {number} arrayPos
	   * @param {Typed} self
	   */
	  onStringTyped: function onStringTyped(arrayPos, self) {},
	
	  /**
	   * During looping, after last string is typed
	   * @param {Typed} self
	   */
	  onLastStringBackspaced: function onLastStringBackspaced(self) {},
	
	  /**
	   * Typing has been stopped
	   * @param {number} arrayPos
	   * @param {Typed} self
	   */
	  onTypingPaused: function onTypingPaused(arrayPos, self) {},
	
	  /**
	   * Typing has been started after being stopped
	   * @param {number} arrayPos
	   * @param {Typed} self
	   */
	  onTypingResumed: function onTypingResumed(arrayPos, self) {},
	
	  /**
	   * After reset
	   * @param {Typed} self
	   */
	  onReset: function onReset(self) {},
	
	  /**
	   * After stop
	   * @param {number} arrayPos
	   * @param {Typed} self
	   */
	  onStop: function onStop(arrayPos, self) {},
	
	  /**
	   * After start
	   * @param {number} arrayPos
	   * @param {Typed} self
	   */
	  onStart: function onStart(arrayPos, self) {},
	
	  /**
	   * After destroy
	   * @param {Typed} self
	   */
	  onDestroy: function onDestroy(self) {}
	};
	
	exports['default'] = defaults;
	module.exports = exports['default'];

/***/ }),
/* 3 */
/***/ (function(module, exports) {

	
	/**
	 * TODO: These methods can probably be combined somehow
	 * Parse HTML tags & HTML Characters
	 */
	
	'use strict';
	
	Object.defineProperty(exports, '__esModule', {
	  value: true
	});
	
	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();
	
	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }
	
	var HTMLParser = (function () {
	  function HTMLParser() {
	    _classCallCheck(this, HTMLParser);
	  }
	
	  _createClass(HTMLParser, [{
	    key: 'typeHtmlChars',
	
	    /**
	     * Type HTML tags & HTML Characters
	     * @param {string} curString Current string
	     * @param {number} curStrPos Position in current string
	     * @param {Typed} self instance of Typed
	     * @returns {number} a new string position
	     * @private
	     */
	
	    value: function typeHtmlChars(curString, curStrPos, self) {
	      if (self.contentType !== 'html') return curStrPos;
	      var curChar = curString.substr(curStrPos).charAt(0);
	      if (curChar === '<' || curChar === '&') {
	        var endTag = '';
	        if (curChar === '<') {
	          endTag = '>';
	        } else {
	          endTag = ';';
	        }
	        while (curString.substr(curStrPos + 1).charAt(0) !== endTag) {
	          curStrPos++;
	          if (curStrPos + 1 > curString.length) {
	            break;
	          }
	        }
	        curStrPos++;
	      }
	      return curStrPos;
	    }
	
	    /**
	     * Backspace HTML tags and HTML Characters
	     * @param {string} curString Current string
	     * @param {number} curStrPos Position in current string
	     * @param {Typed} self instance of Typed
	     * @returns {number} a new string position
	     * @private
	     */
	  }, {
	    key: 'backSpaceHtmlChars',
	    value: function backSpaceHtmlChars(curString, curStrPos, self) {
	      if (self.contentType !== 'html') return curStrPos;
	      var curChar = curString.substr(curStrPos).charAt(0);
	      if (curChar === '>' || curChar === ';') {
	        var endTag = '';
	        if (curChar === '>') {
	          endTag = '<';
	        } else {
	          endTag = '&';
	        }
	        while (curString.substr(curStrPos - 1).charAt(0) !== endTag) {
	          curStrPos--;
	          if (curStrPos < 0) {
	            break;
	          }
	        }
	        curStrPos--;
	      }
	      return curStrPos;
	    }
	  }]);
	
	  return HTMLParser;
	})();
	
	exports['default'] = HTMLParser;
	var htmlParser = new HTMLParser();
	exports.htmlParser = htmlParser;

/***/ })
/******/ ])
});
;
/*!
 * Countdown v0.1.0
 * https://github.com/fengyuanchen/countdown
 *
 * Copyright 2014 Fengyuan Chen
 * Released under the MIT license
 */

(function (factory) {
    if (typeof define === "function" && define.amd) {
        // AMD. Register as anonymous module.
        define(["jquery"], factory);
    } else {
        // Browser globals.
        factory(jQuery);
    }
})(function ($) {

    "use strict";

    var Countdown = function (element, options) {
            this.$element = $(element);
            this.defaults = $.extend({}, Countdown.defaults, this.$element.data(), $.isPlainObject(options) ? options : {});
            this.init();
        };

    Countdown.prototype = {
        constructor: Countdown,

        init: function () {
            var content = this.$element.html(),
                date = new Date(this.defaults.date || content);

            if (date.getTime()) {
                this.content = content;
                this.date = date;
                this.find();

                if (this.defaults.autoStart) {
                    this.start();
                }
            }
        },

        find: function () {
            var $element = this.$element;

            this.$days = $element.find("[data-days]");
            this.$hours = $element.find("[data-hours]");
            this.$minutes = $element.find("[data-minutes]");
            this.$seconds = $element.find("[data-seconds]");

            if ((this.$days.length + this.$hours.length + this.$minutes.length + this.$seconds.length) > 0) {
                this.found = true;
            }
        },

        reset: function () {
            if (this.found) {
                this.output("days");
                this.output("hours");
                this.output("minutes");
                this.output("seconds");
            } else {
                this.output();
            }
        },

        ready: function () {
            var date = this.date,
                decisecond = 100,
                second = 1000,
                minute = 60000,
                hour = 3600000,
                day = 86400000,
                remainder = {},
                diff;

            if (!date) {
                return false;
            }

            diff = date.getTime() - (new Date()).getTime();

            if (diff <= 0) {
                this.end();
                return false;
            }

            remainder.days = diff;
            remainder.hours = remainder.days % day;
            remainder.minutes = remainder.hours % hour;
            remainder.seconds = remainder.minutes % minute;
            remainder.milliseconds = remainder.seconds % second;

            this.days = Math.floor(remainder.days / day);
            this.hours = Math.floor(remainder.hours / hour);
            this.minutes = Math.floor(remainder.minutes / minute);
            this.seconds = Math.floor(remainder.seconds / second);
            this.deciseconds = Math.floor(remainder.milliseconds / decisecond);

            return true;
        },

        start: function () {
            if (!this.active && this.ready()) {
                this.active = true;
                this.reset();
                this.autoUpdate = this.defaults.fast ?
                    setInterval($.proxy(this.fastUpdate, this), 100) :
                    setInterval($.proxy(this.update, this), 1000);
            }
        },

        stop: function () {
            if (this.active) {
                this.active = false;
                clearInterval(this.autoUpdate);
            }
        },

        end: function () {
            if (!this.date) {
                return;
            }

            this.stop();

            this.days = 0;
            this.hours = 0;
            this.minutes = 0;
            this.seconds = 0;
            this.deciseconds = 0;
            this.reset();
            this.defaults.end();
        },

        destroy: function () {
            if (!this.date) {
                return;
            }

            this.stop();

            this.$days = null;
            this.$hours = null;
            this.$minutes = null;
            this.$seconds = null;

            this.$element.empty().html(this.content);
            this.$element.removeData("countdown");
        },

        fastUpdate: function () {
            if (--this.deciseconds >= 0) {
                this.output("deciseconds");
            } else {
                this.deciseconds = 9;
                this.update();
            }
        },

        update: function () {
            if (--this.seconds >= 0) {
                this.output("seconds");
            } else {
                this.seconds = 59;

                if (--this.minutes >= 0) {
                    this.output("minutes");
                } else {
                    this.minutes = 59;

                    if (--this.hours >= 0) {
                        this.output("hours");
                    } else {
                        this.hours = 23;

                        if (--this.days >= 0) {
                            this.output("days");
                        } else {
                            this.end();
                        }
                    }
                }
            }
        },

        output: function (type) {
            if (!this.found) {
                this.$element.empty().html(this.template());
                return;
            }

            switch (type) {
                case "deciseconds":
                    this.$seconds.text(this.getSecondsText());
                    break;

                case "seconds":
                    this.$seconds.text(this.seconds);
                    break;

                case "minutes":
                    this.$minutes.text(this.minutes);
                    break;

                case "hours":
                    this.$hours.text(this.hours);
                    break;

                case "days":
                    this.$days.text(this.days);
                    break;

                // No default
            }
        },

        template: function () {
            return this.defaults.text
                    .replace("%s", this.days)
                    .replace("%s", this.hours)
                    .replace("%s", this.minutes)
                    .replace("%s", this.getSecondsText());
        },

        getSecondsText: function () {
            return this.active && this.defaults.fast ? (this.seconds + "." + this.deciseconds) : this.seconds;
        }
    };

    // Default settings
    Countdown.defaults = {
        autoStart: true,
        date: null,
        fast: false,
        end: $.noop,
        text: "%s days, %s hours, %s minutes, %s seconds"
    };

    // Set default settings
    Countdown.setDefaults = function (options) {
        $.extend(Countdown.defaults, options);
    };

    // Register as jQuery plugin
    $.fn.countdown = function (options) {
        return this.each(function () {
            var $this = $(this),
                data = $this.data("countdown");

            if (!data) {
                $this.data("countdown", (data = new Countdown(this, options)));
            }

            if (typeof options === "string" && $.isFunction(data[options])) {
                data[options]();
            }
        });
    };

    $.fn.countdown.constructor = Countdown;
    $.fn.countdown.setDefaults = Countdown.setDefaults;

    $(function () {
        $("[countdown]").countdown();
    });

});

/**
 * author Christopher Blum
 *    - based on the idea of Remy Sharp, http://remysharp.com/2009/01/26/element-in-view-event-plugin/
 *    - forked from http://github.com/zuk/jquery.inview/
 */
(function (factory) {
    if (typeof define == 'function' && define.amd) {
        // AMD
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node, CommonJS
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {

    var inviewObjects = [], viewportSize, viewportOffset,
        d = document, w = window, documentElement = d.documentElement, timer;

    $.event.special.inview = {
        add: function (data) {
            inviewObjects.push({ data: data, $element: $(this), element: this });
            // Use setInterval in order to also make sure this captures elements within
            // "overflow:scroll" elements or elements that appeared in the dom tree due to
            // dom manipulation and reflow
            // old: $(window).scroll(checkInView);
            //
            // By the way, iOS (iPad, iPhone, ...) seems to not execute, or at least delays
            // intervals while the user scrolls. Therefore the inview event might fire a bit late there
            //
            // Don't waste cycles with an interval until we get at least one element that
            // has bound to the inview event.
            if (!timer && inviewObjects.length) {
                timer = setInterval(checkInView, 250);
            }
        },

        remove: function (data) {
            for (var i = 0; i < inviewObjects.length; i++) {
                var inviewObject = inviewObjects[i];
                if (inviewObject.element === this && inviewObject.data.guid === data.guid) {
                    inviewObjects.splice(i, 1);
                    break;
                }
            }

            // Clear interval when we no longer have any elements listening
            if (!inviewObjects.length) {
                clearInterval(timer);
                timer = null;
            }
        }
    };

    function getViewportSize() {
        var mode, domObject, size = { height: w.innerHeight, width: w.innerWidth };

        // if this is correct then return it. iPad has compat Mode, so will
        // go into check clientHeight/clientWidth (which has the wrong value).
        if (!size.height) {
            mode = d.compatMode;
            if (mode || !$.support.boxModel) { // IE, Gecko
                domObject = mode === 'CSS1Compat' ?
                    documentElement : // Standards
                    d.body; // Quirks
                size = {
                    height: domObject.clientHeight,
                    width: domObject.clientWidth
                };
            }
        }

        return size;
    }

    function getViewportOffset() {
        return {
            top: w.pageYOffset || documentElement.scrollTop || d.body.scrollTop,
            left: w.pageXOffset || documentElement.scrollLeft || d.body.scrollLeft
        };
    }

    function checkInView() {
        if (!inviewObjects.length) {
            return;
        }

        var i = 0, $elements = $.map(inviewObjects, function (inviewObject) {
            var selector = inviewObject.data.selector,
                $element = inviewObject.$element;
            return selector ? $element.find(selector) : $element;
        });

        viewportSize = viewportSize || getViewportSize();
        viewportOffset = viewportOffset || getViewportOffset();

        for (; i < inviewObjects.length; i++) {
            // Ignore elements that are not in the DOM tree
            if (!$.contains(documentElement, $elements[i][0])) {
                continue;
            }

            var $element = $($elements[i]),
                elementSize = { height: $element[0].offsetHeight, width: $element[0].offsetWidth },
                elementOffset = $element.offset(),
                inView = $element.data('inview');

            // Don't ask me why because I haven't figured out yet:
            // viewportOffset and viewportSize are sometimes suddenly null in Firefox 5.
            // Even though it sounds weird:
            // It seems that the execution of this function is interferred by the onresize/onscroll event
            // where viewportOffset and viewportSize are unset
            if (!viewportOffset || !viewportSize) {
                return;
            }

            if (elementOffset.top + elementSize.height > viewportOffset.top &&
                elementOffset.top < viewportOffset.top + viewportSize.height &&
                elementOffset.left + elementSize.width > viewportOffset.left &&
                elementOffset.left < viewportOffset.left + viewportSize.width) {
                if (!inView) {
                    $element.data('inview', true).trigger('inview', [true]);
                }
            } else if (inView) {
                $element.data('inview', false).trigger('inview', [false]);
            }
        }
    }

    $(w).on("scroll resize scrollstop", function () {
        viewportSize = viewportOffset = null;
    });

    // IE < 9 scrolls to focused elements without firing the "scroll" event
    if (!documentElement.addEventListener && documentElement.attachEvent) {
        documentElement.attachEvent("onfocusin", function () {
            viewportOffset = null;
        });
    }
}));
/*! Magnific Popup - v1.1.0 - 2016-02-20
* http://dimsemenov.com/plugins/magnific-popup/
* Copyright (c) 2016 Dmitry Semenov; */
; (function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module. 
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node/CommonJS 
        factory(require('jquery'));
    } else {
        // Browser globals 
        factory(window.jQuery || window.Zepto);
    }
}(function ($) {

    /*>>core*/
    /**
     * 
     * Magnific Popup Core JS file
     * 
     */


    /**
     * Private static constants
     */
    var CLOSE_EVENT = 'Close',
        BEFORE_CLOSE_EVENT = 'BeforeClose',
        AFTER_CLOSE_EVENT = 'AfterClose',
        BEFORE_APPEND_EVENT = 'BeforeAppend',
        MARKUP_PARSE_EVENT = 'MarkupParse',
        OPEN_EVENT = 'Open',
        CHANGE_EVENT = 'Change',
        NS = 'mfp',
        EVENT_NS = '.' + NS,
        READY_CLASS = 'mfp-ready',
        REMOVING_CLASS = 'mfp-removing',
        PREVENT_CLOSE_CLASS = 'mfp-prevent-close';


    /**
     * Private vars 
     */
    /*jshint -W079 */
    var mfp, // As we have only one instance of MagnificPopup object, we define it locally to not to use 'this'
        MagnificPopup = function () { },
        _isJQ = !!(window.jQuery),
        _prevStatus,
        _window = $(window),
        _document,
        _prevContentType,
        _wrapClasses,
        _currPopupType;


    /**
     * Private functions
     */
    var _mfpOn = function (name, f) {
        mfp.ev.on(NS + name + EVENT_NS, f);
    },
        _getEl = function (className, appendTo, html, raw) {
            var el = document.createElement('div');
            el.className = 'mfp-' + className;
            if (html) {
                el.innerHTML = html;
            }
            if (!raw) {
                el = $(el);
                if (appendTo) {
                    el.appendTo(appendTo);
                }
            } else if (appendTo) {
                appendTo.appendChild(el);
            }
            return el;
        },
        _mfpTrigger = function (e, data) {
            mfp.ev.triggerHandler(NS + e, data);

            if (mfp.st.callbacks) {
                // converts "mfpEventName" to "eventName" callback and triggers it if it's present
                e = e.charAt(0).toLowerCase() + e.slice(1);
                if (mfp.st.callbacks[e]) {
                    mfp.st.callbacks[e].apply(mfp, $.isArray(data) ? data : [data]);
                }
            }
        },
        _getCloseBtn = function (type) {
            if (type !== _currPopupType || !mfp.currTemplate.closeBtn) {
                mfp.currTemplate.closeBtn = $(mfp.st.closeMarkup.replace('%title%', mfp.st.tClose));
                _currPopupType = type;
            }
            return mfp.currTemplate.closeBtn;
        },
        // Initialize Magnific Popup only when called at least once
        _checkInstance = function () {
            if (!$.magnificPopup.instance) {
                /*jshint -W020 */
                mfp = new MagnificPopup();
                mfp.init();
                $.magnificPopup.instance = mfp;
            }
        },
        // CSS transition detection, http://stackoverflow.com/questions/7264899/detect-css-transitions-using-javascript-and-without-modernizr
        supportsTransitions = function () {
            var s = document.createElement('p').style, // 's' for style. better to create an element if body yet to exist
                v = ['ms', 'O', 'Moz', 'Webkit']; // 'v' for vendor

            if (s['transition'] !== undefined) {
                return true;
            }

            while (v.length) {
                if (v.pop() + 'Transition' in s) {
                    return true;
                }
            }

            return false;
        };



    /**
     * Public functions
     */
    MagnificPopup.prototype = {

        constructor: MagnificPopup,

        /**
         * Initializes Magnific Popup plugin. 
         * This function is triggered only once when $.fn.magnificPopup or $.magnificPopup is executed
         */
        init: function () {
            var appVersion = navigator.appVersion;
            mfp.isLowIE = mfp.isIE8 = document.all && !document.addEventListener;
            mfp.isAndroid = (/android/gi).test(appVersion);
            mfp.isIOS = (/iphone|ipad|ipod/gi).test(appVersion);
            mfp.supportsTransition = supportsTransitions();

            // We disable fixed positioned lightbox on devices that don't handle it nicely.
            // If you know a better way of detecting this - let me know.
            mfp.probablyMobile = (mfp.isAndroid || mfp.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent));
            _document = $(document);

            mfp.popupsCache = {};
        },

        /**
         * Opens popup
         * @param  data [description]
         */
        open: function (data) {

            var i;

            if (data.isObj === false) {
                // convert jQuery collection to array to avoid conflicts later
                mfp.items = data.items.toArray();

                mfp.index = 0;
                var items = data.items,
                    item;
                for (i = 0; i < items.length; i++) {
                    item = items[i];
                    if (item.parsed) {
                        item = item.el[0];
                    }
                    if (item === data.el[0]) {
                        mfp.index = i;
                        break;
                    }
                }
            } else {
                mfp.items = $.isArray(data.items) ? data.items : [data.items];
                mfp.index = data.index || 0;
            }

            // if popup is already opened - we just update the content
            if (mfp.isOpen) {
                mfp.updateItemHTML();
                return;
            }

            mfp.types = [];
            _wrapClasses = '';
            if (data.mainEl && data.mainEl.length) {
                mfp.ev = data.mainEl.eq(0);
            } else {
                mfp.ev = _document;
            }

            if (data.key) {
                if (!mfp.popupsCache[data.key]) {
                    mfp.popupsCache[data.key] = {};
                }
                mfp.currTemplate = mfp.popupsCache[data.key];
            } else {
                mfp.currTemplate = {};
            }



            mfp.st = $.extend(true, {}, $.magnificPopup.defaults, data);
            mfp.fixedContentPos = mfp.st.fixedContentPos === 'auto' ? !mfp.probablyMobile : mfp.st.fixedContentPos;

            if (mfp.st.modal) {
                mfp.st.closeOnContentClick = false;
                mfp.st.closeOnBgClick = false;
                mfp.st.showCloseBtn = false;
                mfp.st.enableEscapeKey = false;
            }


            // Building markup
            // main containers are created only once
            if (!mfp.bgOverlay) {

                // Dark overlay
                mfp.bgOverlay = _getEl('bg').on('click' + EVENT_NS, function () {
                    mfp.close();
                });

                mfp.wrap = _getEl('wrap').attr('tabindex', -1).on('click' + EVENT_NS, function (e) {
                    if (mfp._checkIfClose(e.target)) {
                        mfp.close();
                    }
                });

                mfp.container = _getEl('container', mfp.wrap);
            }

            mfp.contentContainer = _getEl('content');
            if (mfp.st.preloader) {
                mfp.preloader = _getEl('preloader', mfp.container, mfp.st.tLoading);
            }


            // Initializing modules
            var modules = $.magnificPopup.modules;
            for (i = 0; i < modules.length; i++) {
                var n = modules[i];
                n = n.charAt(0).toUpperCase() + n.slice(1);
                mfp['init' + n].call(mfp);
            }
            _mfpTrigger('BeforeOpen');


            if (mfp.st.showCloseBtn) {
                // Close button
                if (!mfp.st.closeBtnInside) {
                    mfp.wrap.append(_getCloseBtn());
                } else {
                    _mfpOn(MARKUP_PARSE_EVENT, function (e, template, values, item) {
                        values.close_replaceWith = _getCloseBtn(item.type);
                    });
                    _wrapClasses += ' mfp-close-btn-in';
                }
            }

            if (mfp.st.alignTop) {
                _wrapClasses += ' mfp-align-top';
            }



            if (mfp.fixedContentPos) {
                mfp.wrap.css({
                    overflow: mfp.st.overflowY,
                    overflowX: 'hidden',
                    overflowY: mfp.st.overflowY
                });
            } else {
                mfp.wrap.css({
                    top: _window.scrollTop(),
                    position: 'absolute'
                });
            }
            if (mfp.st.fixedBgPos === false || (mfp.st.fixedBgPos === 'auto' && !mfp.fixedContentPos)) {
                mfp.bgOverlay.css({
                    height: _document.height(),
                    position: 'absolute'
                });
            }



            if (mfp.st.enableEscapeKey) {
                // Close on ESC key
                _document.on('keyup' + EVENT_NS, function (e) {
                    if (e.keyCode === 27) {
                        mfp.close();
                    }
                });
            }

            _window.on('resize' + EVENT_NS, function () {
                mfp.updateSize();
            });


            if (!mfp.st.closeOnContentClick) {
                _wrapClasses += ' mfp-auto-cursor';
            }

            if (_wrapClasses)
                mfp.wrap.addClass(_wrapClasses);


            // this triggers recalculation of layout, so we get it once to not to trigger twice
            var windowHeight = mfp.wH = _window.height();


            var windowStyles = {};

            if (mfp.fixedContentPos) {
                if (mfp._hasScrollBar(windowHeight)) {
                    var s = mfp._getScrollbarSize();
                    if (s) {
                        windowStyles.marginRight = s;
                    }
                }
            }

            if (mfp.fixedContentPos) {
                if (!mfp.isIE7) {
                    windowStyles.overflow = 'hidden';
                } else {
                    // ie7 double-scroll bug
                    $('body, html').css('overflow', 'hidden');
                }
            }



            var classesToadd = mfp.st.mainClass;
            if (mfp.isIE7) {
                classesToadd += ' mfp-ie7';
            }
            if (classesToadd) {
                mfp._addClassToMFP(classesToadd);
            }

            // add content
            mfp.updateItemHTML();

            _mfpTrigger('BuildControls');

            // remove scrollbar, add margin e.t.c
            $('html').css(windowStyles);

            // add everything to DOM
            mfp.bgOverlay.add(mfp.wrap).prependTo(mfp.st.prependTo || $(document.body));

            // Save last focused element
            mfp._lastFocusedEl = document.activeElement;

            // Wait for next cycle to allow CSS transition
            setTimeout(function () {

                if (mfp.content) {
                    mfp._addClassToMFP(READY_CLASS);
                    mfp._setFocus();
                } else {
                    // if content is not defined (not loaded e.t.c) we add class only for BG
                    mfp.bgOverlay.addClass(READY_CLASS);
                }

                // Trap the focus in popup
                _document.on('focusin' + EVENT_NS, mfp._onFocusIn);

            }, 16);

            mfp.isOpen = true;
            mfp.updateSize(windowHeight);
            _mfpTrigger(OPEN_EVENT);

            return data;
        },

        /**
         * Closes the popup
         */
        close: function () {
            if (!mfp.isOpen) return;
            _mfpTrigger(BEFORE_CLOSE_EVENT);

            mfp.isOpen = false;
            // for CSS3 animation
            if (mfp.st.removalDelay && !mfp.isLowIE && mfp.supportsTransition) {
                mfp._addClassToMFP(REMOVING_CLASS);
                setTimeout(function () {
                    mfp._close();
                }, mfp.st.removalDelay);
            } else {
                mfp._close();
            }
        },

        /**
         * Helper for close() function
         */
        _close: function () {
            _mfpTrigger(CLOSE_EVENT);

            var classesToRemove = REMOVING_CLASS + ' ' + READY_CLASS + ' ';

            mfp.bgOverlay.detach();
            mfp.wrap.detach();
            mfp.container.empty();

            if (mfp.st.mainClass) {
                classesToRemove += mfp.st.mainClass + ' ';
            }

            mfp._removeClassFromMFP(classesToRemove);

            if (mfp.fixedContentPos) {
                var windowStyles = { marginRight: '' };
                if (mfp.isIE7) {
                    $('body, html').css('overflow', '');
                } else {
                    windowStyles.overflow = '';
                }
                $('html').css(windowStyles);
            }

            _document.off('keyup' + EVENT_NS + ' focusin' + EVENT_NS);
            mfp.ev.off(EVENT_NS);

            // clean up DOM elements that aren't removed
            mfp.wrap.attr('class', 'mfp-wrap').removeAttr('style');
            mfp.bgOverlay.attr('class', 'mfp-bg');
            mfp.container.attr('class', 'mfp-container');

            // remove close button from target element
            if (mfp.st.showCloseBtn &&
                (!mfp.st.closeBtnInside || mfp.currTemplate[mfp.currItem.type] === true)) {
                if (mfp.currTemplate.closeBtn)
                    mfp.currTemplate.closeBtn.detach();
            }


            if (mfp.st.autoFocusLast && mfp._lastFocusedEl) {
                $(mfp._lastFocusedEl).focus(); // put tab focus back
            }
            mfp.currItem = null;
            mfp.content = null;
            mfp.currTemplate = null;
            mfp.prevHeight = 0;

            _mfpTrigger(AFTER_CLOSE_EVENT);
        },

        updateSize: function (winHeight) {

            if (mfp.isIOS) {
                // fixes iOS nav bars https://github.com/dimsemenov/Magnific-Popup/issues/2
                var zoomLevel = document.documentElement.clientWidth / window.innerWidth;
                var height = window.innerHeight * zoomLevel;
                mfp.wrap.css('height', height);
                mfp.wH = height;
            } else {
                mfp.wH = winHeight || _window.height();
            }
            // Fixes #84: popup incorrectly positioned with position:relative on body
            if (!mfp.fixedContentPos) {
                mfp.wrap.css('height', mfp.wH);
            }

            _mfpTrigger('Resize');

        },

        /**
         * Set content of popup based on current index
         */
        updateItemHTML: function () {
            var item = mfp.items[mfp.index];

            // Detach and perform modifications
            mfp.contentContainer.detach();

            if (mfp.content)
                mfp.content.detach();

            if (!item.parsed) {
                item = mfp.parseEl(mfp.index);
            }

            var type = item.type;

            _mfpTrigger('BeforeChange', [mfp.currItem ? mfp.currItem.type : '', type]);
            // BeforeChange event works like so:
            // _mfpOn('BeforeChange', function(e, prevType, newType) { });

            mfp.currItem = item;

            if (!mfp.currTemplate[type]) {
                var markup = mfp.st[type] ? mfp.st[type].markup : false;

                // allows to modify markup
                _mfpTrigger('FirstMarkupParse', markup);

                if (markup) {
                    mfp.currTemplate[type] = $(markup);
                } else {
                    // if there is no markup found we just define that template is parsed
                    mfp.currTemplate[type] = true;
                }
            }

            if (_prevContentType && _prevContentType !== item.type) {
                mfp.container.removeClass('mfp-' + _prevContentType + '-holder');
            }

            var newContent = mfp['get' + type.charAt(0).toUpperCase() + type.slice(1)](item, mfp.currTemplate[type]);
            mfp.appendContent(newContent, type);

            item.preloaded = true;

            _mfpTrigger(CHANGE_EVENT, item);
            _prevContentType = item.type;

            // Append container back after its content changed
            mfp.container.prepend(mfp.contentContainer);

            _mfpTrigger('AfterChange');
        },


        /**
         * Set HTML content of popup
         */
        appendContent: function (newContent, type) {
            mfp.content = newContent;

            if (newContent) {
                if (mfp.st.showCloseBtn && mfp.st.closeBtnInside &&
                    mfp.currTemplate[type] === true) {
                    // if there is no markup, we just append close button element inside
                    if (!mfp.content.find('.mfp-close').length) {
                        mfp.content.append(_getCloseBtn());
                    }
                } else {
                    mfp.content = newContent;
                }
            } else {
                mfp.content = '';
            }

            _mfpTrigger(BEFORE_APPEND_EVENT);
            mfp.container.addClass('mfp-' + type + '-holder');

            mfp.contentContainer.append(mfp.content);
        },


        /**
         * Creates Magnific Popup data object based on given data
         * @param  {int} index Index of item to parse
         */
        parseEl: function (index) {
            var item = mfp.items[index],
                type;

            if (item.tagName) {
                item = { el: $(item) };
            } else {
                type = item.type;
                item = { data: item, src: item.src };
            }

            if (item.el) {
                var types = mfp.types;

                // check for 'mfp-TYPE' class
                for (var i = 0; i < types.length; i++) {
                    if (item.el.hasClass('mfp-' + types[i])) {
                        type = types[i];
                        break;
                    }
                }

                item.src = item.el.attr('data-mfp-src');
                if (!item.src) {
                    item.src = item.el.attr('href');
                }
            }

            item.type = type || mfp.st.type || 'inline';
            item.index = index;
            item.parsed = true;
            mfp.items[index] = item;
            _mfpTrigger('ElementParse', item);

            return mfp.items[index];
        },


        /**
         * Initializes single popup or a group of popups
         */
        addGroup: function (el, options) {
            var eHandler = function (e) {
                e.mfpEl = this;
                mfp._openClick(e, el, options);
            };

            if (!options) {
                options = {};
            }

            var eName = 'click.magnificPopup';
            options.mainEl = el;

            if (options.items) {
                options.isObj = true;
                el.off(eName).on(eName, eHandler);
            } else {
                options.isObj = false;
                if (options.delegate) {
                    el.off(eName).on(eName, options.delegate, eHandler);
                } else {
                    options.items = el;
                    el.off(eName).on(eName, eHandler);
                }
            }
        },
        _openClick: function (e, el, options) {
            var midClick = options.midClick !== undefined ? options.midClick : $.magnificPopup.defaults.midClick;


            if (!midClick && (e.which === 2 || e.ctrlKey || e.metaKey || e.altKey || e.shiftKey)) {
                return;
            }

            var disableOn = options.disableOn !== undefined ? options.disableOn : $.magnificPopup.defaults.disableOn;

            if (disableOn) {
                if ($.isFunction(disableOn)) {
                    if (!disableOn.call(mfp)) {
                        return true;
                    }
                } else { // else it's number
                    if (_window.width() < disableOn) {
                        return true;
                    }
                }
            }

            if (e.type) {
                e.preventDefault();

                // This will prevent popup from closing if element is inside and popup is already opened
                if (mfp.isOpen) {
                    e.stopPropagation();
                }
            }

            options.el = $(e.mfpEl);
            if (options.delegate) {
                options.items = el.find(options.delegate);
            }
            mfp.open(options);
        },


        /**
         * Updates text on preloader
         */
        updateStatus: function (status, text) {

            if (mfp.preloader) {
                if (_prevStatus !== status) {
                    mfp.container.removeClass('mfp-s-' + _prevStatus);
                }

                if (!text && status === 'loading') {
                    text = mfp.st.tLoading;
                }

                var data = {
                    status: status,
                    text: text
                };
                // allows to modify status
                _mfpTrigger('UpdateStatus', data);

                status = data.status;
                text = data.text;

                mfp.preloader.html(text);

                mfp.preloader.find('a').on('click', function (e) {
                    e.stopImmediatePropagation();
                });

                mfp.container.addClass('mfp-s-' + status);
                _prevStatus = status;
            }
        },


        /*
            "Private" helpers that aren't private at all
         */
        // Check to close popup or not
        // "target" is an element that was clicked
        _checkIfClose: function (target) {

            if ($(target).hasClass(PREVENT_CLOSE_CLASS)) {
                return;
            }

            var closeOnContent = mfp.st.closeOnContentClick;
            var closeOnBg = mfp.st.closeOnBgClick;

            if (closeOnContent && closeOnBg) {
                return true;
            } else {

                // We close the popup if click is on close button or on preloader. Or if there is no content.
                if (!mfp.content || $(target).hasClass('mfp-close') || (mfp.preloader && target === mfp.preloader[0])) {
                    return true;
                }

                // if click is outside the content
                if ((target !== mfp.content[0] && !$.contains(mfp.content[0], target))) {
                    if (closeOnBg) {
                        // last check, if the clicked element is in DOM, (in case it's removed onclick)
                        if ($.contains(document, target)) {
                            return true;
                        }
                    }
                } else if (closeOnContent) {
                    return true;
                }

            }
            return false;
        },
        _addClassToMFP: function (cName) {
            mfp.bgOverlay.addClass(cName);
            mfp.wrap.addClass(cName);
        },
        _removeClassFromMFP: function (cName) {
            this.bgOverlay.removeClass(cName);
            mfp.wrap.removeClass(cName);
        },
        _hasScrollBar: function (winHeight) {
            return ((mfp.isIE7 ? _document.height() : document.body.scrollHeight) > (winHeight || _window.height()));
        },
        _setFocus: function () {
            (mfp.st.focus ? mfp.content.find(mfp.st.focus).eq(0) : mfp.wrap).focus();
        },
        _onFocusIn: function (e) {
            if (e.target !== mfp.wrap[0] && !$.contains(mfp.wrap[0], e.target)) {
                mfp._setFocus();
                return false;
            }
        },
        _parseMarkup: function (template, values, item) {
            var arr;
            if (item.data) {
                values = $.extend(item.data, values);
            }
            _mfpTrigger(MARKUP_PARSE_EVENT, [template, values, item]);

            $.each(values, function (key, value) {
                if (value === undefined || value === false) {
                    return true;
                }
                arr = key.split('_');
                if (arr.length > 1) {
                    var el = template.find(EVENT_NS + '-' + arr[0]);

                    if (el.length > 0) {
                        var attr = arr[1];
                        if (attr === 'replaceWith') {
                            if (el[0] !== value[0]) {
                                el.replaceWith(value);
                            }
                        } else if (attr === 'img') {
                            if (el.is('img')) {
                                el.attr('src', value);
                            } else {
                                el.replaceWith($('<img>').attr('src', value).attr('class', el.attr('class')));
                            }
                        } else {
                            el.attr(arr[1], value);
                        }
                    }

                } else {
                    template.find(EVENT_NS + '-' + key).html(value);
                }
            });
        },

        _getScrollbarSize: function () {
            // thx David
            if (mfp.scrollbarSize === undefined) {
                var scrollDiv = document.createElement("div");
                scrollDiv.style.cssText = 'width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;';
                document.body.appendChild(scrollDiv);
                mfp.scrollbarSize = scrollDiv.offsetWidth - scrollDiv.clientWidth;
                document.body.removeChild(scrollDiv);
            }
            return mfp.scrollbarSize;
        }

    }; /* MagnificPopup core prototype end */




    /**
     * Public static functions
     */
    $.magnificPopup = {
        instance: null,
        proto: MagnificPopup.prototype,
        modules: [],

        open: function (options, index) {
            _checkInstance();

            if (!options) {
                options = {};
            } else {
                options = $.extend(true, {}, options);
            }

            options.isObj = true;
            options.index = index || 0;
            return this.instance.open(options);
        },

        close: function () {
            return $.magnificPopup.instance && $.magnificPopup.instance.close();
        },

        registerModule: function (name, module) {
            if (module.options) {
                $.magnificPopup.defaults[name] = module.options;
            }
            $.extend(this.proto, module.proto);
            this.modules.push(name);
        },

        defaults: {

            // Info about options is in docs:
            // http://dimsemenov.com/plugins/magnific-popup/documentation.html#options

            disableOn: 0,

            key: null,

            midClick: false,

            mainClass: '',

            preloader: true,

            focus: '', // CSS selector of input to focus after popup is opened

            closeOnContentClick: false,

            closeOnBgClick: true,

            closeBtnInside: true,

            showCloseBtn: true,

            enableEscapeKey: true,

            modal: false,

            alignTop: false,

            removalDelay: 0,

            prependTo: null,

            fixedContentPos: 'auto',

            fixedBgPos: 'auto',

            overflowY: 'auto',

            closeMarkup: '<button title="%title%" type="button" class="mfp-close">&#215;</button>',

            tClose: 'Close (Esc)',

            tLoading: 'Loading...',

            autoFocusLast: true

        }
    };



    $.fn.magnificPopup = function (options) {
        _checkInstance();

        var jqEl = $(this);

        // We call some API method of first param is a string
        if (typeof options === "string") {

            if (options === 'open') {
                var items,
                    itemOpts = _isJQ ? jqEl.data('magnificPopup') : jqEl[0].magnificPopup,
                    index = parseInt(arguments[1], 10) || 0;

                if (itemOpts.items) {
                    items = itemOpts.items[index];
                } else {
                    items = jqEl;
                    if (itemOpts.delegate) {
                        items = items.find(itemOpts.delegate);
                    }
                    items = items.eq(index);
                }
                mfp._openClick({ mfpEl: items }, jqEl, itemOpts);
            } else {
                if (mfp.isOpen)
                    mfp[options].apply(mfp, Array.prototype.slice.call(arguments, 1));
            }

        } else {
            // clone options obj
            options = $.extend(true, {}, options);

            /*
             * As Zepto doesn't support .data() method for objects
             * and it works only in normal browsers
             * we assign "options" object directly to the DOM element. FTW!
             */
            if (_isJQ) {
                jqEl.data('magnificPopup', options);
            } else {
                jqEl[0].magnificPopup = options;
            }

            mfp.addGroup(jqEl, options);

        }
        return jqEl;
    };

    /*>>core*/

    /*>>inline*/

    var INLINE_NS = 'inline',
        _hiddenClass,
        _inlinePlaceholder,
        _lastInlineElement,
        _putInlineElementsBack = function () {
            if (_lastInlineElement) {
                _inlinePlaceholder.after(_lastInlineElement.addClass(_hiddenClass)).detach();
                _lastInlineElement = null;
            }
        };

    $.magnificPopup.registerModule(INLINE_NS, {
        options: {
            hiddenClass: 'hide', // will be appended with `mfp-` prefix
            markup: '',
            tNotFound: 'Content not found'
        },
        proto: {

            initInline: function () {
                mfp.types.push(INLINE_NS);

                _mfpOn(CLOSE_EVENT + '.' + INLINE_NS, function () {
                    _putInlineElementsBack();
                });
            },

            getInline: function (item, template) {

                _putInlineElementsBack();

                if (item.src) {
                    var inlineSt = mfp.st.inline,
                        el = $(item.src);

                    if (el.length) {

                        // If target element has parent - we replace it with placeholder and put it back after popup is closed
                        var parent = el[0].parentNode;
                        if (parent && parent.tagName) {
                            if (!_inlinePlaceholder) {
                                _hiddenClass = inlineSt.hiddenClass;
                                _inlinePlaceholder = _getEl(_hiddenClass);
                                _hiddenClass = 'mfp-' + _hiddenClass;
                            }
                            // replace target inline element with placeholder
                            _lastInlineElement = el.after(_inlinePlaceholder).detach().removeClass(_hiddenClass);
                        }

                        mfp.updateStatus('ready');
                    } else {
                        mfp.updateStatus('error', inlineSt.tNotFound);
                        el = $('<div>');
                    }

                    item.inlineElement = el;
                    return el;
                }

                mfp.updateStatus('ready');
                mfp._parseMarkup(template, {}, item);
                return template;
            }
        }
    });

    /*>>inline*/

    /*>>ajax*/
    var AJAX_NS = 'ajax',
        _ajaxCur,
        _removeAjaxCursor = function () {
            if (_ajaxCur) {
                $(document.body).removeClass(_ajaxCur);
            }
        },
        _destroyAjaxRequest = function () {
            _removeAjaxCursor();
            if (mfp.req) {
                mfp.req.abort();
            }
        };

    $.magnificPopup.registerModule(AJAX_NS, {

        options: {
            settings: null,
            cursor: 'mfp-ajax-cur',
            tError: '<a href="%url%">The content</a> could not be loaded.'
        },

        proto: {
            initAjax: function () {
                mfp.types.push(AJAX_NS);
                _ajaxCur = mfp.st.ajax.cursor;

                _mfpOn(CLOSE_EVENT + '.' + AJAX_NS, _destroyAjaxRequest);
                _mfpOn('BeforeChange.' + AJAX_NS, _destroyAjaxRequest);
            },
            getAjax: function (item) {

                if (_ajaxCur) {
                    $(document.body).addClass(_ajaxCur);
                }

                mfp.updateStatus('loading');

                var opts = $.extend({
                    url: item.src,
                    success: function (data, textStatus, jqXHR) {
                        var temp = {
                            data: data,
                            xhr: jqXHR
                        };

                        _mfpTrigger('ParseAjax', temp);

                        mfp.appendContent($(temp.data), AJAX_NS);

                        item.finished = true;

                        _removeAjaxCursor();

                        mfp._setFocus();

                        setTimeout(function () {
                            mfp.wrap.addClass(READY_CLASS);
                        }, 16);

                        mfp.updateStatus('ready');

                        _mfpTrigger('AjaxContentAdded');
                    },
                    error: function () {
                        _removeAjaxCursor();
                        item.finished = item.loadError = true;
                        mfp.updateStatus('error', mfp.st.ajax.tError.replace('%url%', item.src));
                    }
                }, mfp.st.ajax.settings);

                mfp.req = $.ajax(opts);

                return '';
            }
        }
    });

    /*>>ajax*/

    /*>>image*/
    var _imgInterval,
        _getTitle = function (item) {
            if (item.data && item.data.title !== undefined)
                return item.data.title;

            var src = mfp.st.image.titleSrc;

            if (src) {
                if ($.isFunction(src)) {
                    return src.call(mfp, item);
                } else if (item.el) {
                    return item.el.attr(src) || '';
                }
            }
            return '';
        };

    $.magnificPopup.registerModule('image', {

        options: {
            markup: '<div class="mfp-figure">' +
                '<div class="mfp-close"></div>' +
                '<figure>' +
                '<div class="mfp-img"></div>' +
                '<figcaption>' +
                '<div class="mfp-bottom-bar">' +
                '<div class="mfp-title"></div>' +
                '<div class="mfp-counter"></div>' +
                '</div>' +
                '</figcaption>' +
                '</figure>' +
                '</div>',
            cursor: 'mfp-zoom-out-cur',
            titleSrc: 'title',
            verticalFit: true,
            tError: '<a href="%url%">The image</a> could not be loaded.'
        },

        proto: {
            initImage: function () {
                var imgSt = mfp.st.image,
                    ns = '.image';

                mfp.types.push('image');

                _mfpOn(OPEN_EVENT + ns, function () {
                    if (mfp.currItem.type === 'image' && imgSt.cursor) {
                        $(document.body).addClass(imgSt.cursor);
                    }
                });

                _mfpOn(CLOSE_EVENT + ns, function () {
                    if (imgSt.cursor) {
                        $(document.body).removeClass(imgSt.cursor);
                    }
                    _window.off('resize' + EVENT_NS);
                });

                _mfpOn('Resize' + ns, mfp.resizeImage);
                if (mfp.isLowIE) {
                    _mfpOn('AfterChange', mfp.resizeImage);
                }
            },
            resizeImage: function () {
                var item = mfp.currItem;
                if (!item || !item.img) return;

                if (mfp.st.image.verticalFit) {
                    var decr = 0;
                    // fix box-sizing in ie7/8
                    if (mfp.isLowIE) {
                        decr = parseInt(item.img.css('padding-top'), 10) + parseInt(item.img.css('padding-bottom'), 10);
                    }
                    item.img.css('max-height', mfp.wH - decr);
                }
            },
            _onImageHasSize: function (item) {
                if (item.img) {

                    item.hasSize = true;

                    if (_imgInterval) {
                        clearInterval(_imgInterval);
                    }

                    item.isCheckingImgSize = false;

                    _mfpTrigger('ImageHasSize', item);

                    if (item.imgHidden) {
                        if (mfp.content)
                            mfp.content.removeClass('mfp-loading');

                        item.imgHidden = false;
                    }

                }
            },

            /**
             * Function that loops until the image has size to display elements that rely on it asap
             */
            findImageSize: function (item) {

                var counter = 0,
                    img = item.img[0],
                    mfpSetInterval = function (delay) {

                        if (_imgInterval) {
                            clearInterval(_imgInterval);
                        }
                        // decelerating interval that checks for size of an image
                        _imgInterval = setInterval(function () {
                            if (img.naturalWidth > 0) {
                                mfp._onImageHasSize(item);
                                return;
                            }

                            if (counter > 200) {
                                clearInterval(_imgInterval);
                            }

                            counter++;
                            if (counter === 3) {
                                mfpSetInterval(10);
                            } else if (counter === 40) {
                                mfpSetInterval(50);
                            } else if (counter === 100) {
                                mfpSetInterval(500);
                            }
                        }, delay);
                    };

                mfpSetInterval(1);
            },

            getImage: function (item, template) {

                var guard = 0,

                    // image load complete handler
                    onLoadComplete = function () {
                        if (item) {
                            if (item.img[0].complete) {
                                item.img.off('.mfploader');

                                if (item === mfp.currItem) {
                                    mfp._onImageHasSize(item);

                                    mfp.updateStatus('ready');
                                }

                                item.hasSize = true;
                                item.loaded = true;

                                _mfpTrigger('ImageLoadComplete');

                            }
                            else {
                                // if image complete check fails 200 times (20 sec), we assume that there was an error.
                                guard++;
                                if (guard < 200) {
                                    setTimeout(onLoadComplete, 100);
                                } else {
                                    onLoadError();
                                }
                            }
                        }
                    },

                    // image error handler
                    onLoadError = function () {
                        if (item) {
                            item.img.off('.mfploader');
                            if (item === mfp.currItem) {
                                mfp._onImageHasSize(item);
                                mfp.updateStatus('error', imgSt.tError.replace('%url%', item.src));
                            }

                            item.hasSize = true;
                            item.loaded = true;
                            item.loadError = true;
                        }
                    },
                    imgSt = mfp.st.image;


                var el = template.find('.mfp-img');
                if (el.length) {
                    var img = document.createElement('img');
                    img.className = 'mfp-img';
                    if (item.el && item.el.find('img').length) {
                        img.alt = item.el.find('img').attr('alt');
                    }
                    item.img = $(img).on('load.mfploader', onLoadComplete).on('error.mfploader', onLoadError);
                    img.src = item.src;

                    // without clone() "error" event is not firing when IMG is replaced by new IMG
                    // TODO: find a way to avoid such cloning
                    if (el.is('img')) {
                        item.img = item.img.clone();
                    }

                    img = item.img[0];
                    if (img.naturalWidth > 0) {
                        item.hasSize = true;
                    } else if (!img.width) {
                        item.hasSize = false;
                    }
                }

                mfp._parseMarkup(template, {
                    title: _getTitle(item),
                    img_replaceWith: item.img
                }, item);

                mfp.resizeImage();

                if (item.hasSize) {
                    if (_imgInterval) clearInterval(_imgInterval);

                    if (item.loadError) {
                        template.addClass('mfp-loading');
                        mfp.updateStatus('error', imgSt.tError.replace('%url%', item.src));
                    } else {
                        template.removeClass('mfp-loading');
                        mfp.updateStatus('ready');
                    }
                    return template;
                }

                mfp.updateStatus('loading');
                item.loading = true;

                if (!item.hasSize) {
                    item.imgHidden = true;
                    template.addClass('mfp-loading');
                    mfp.findImageSize(item);
                }

                return template;
            }
        }
    });

    /*>>image*/

    /*>>zoom*/
    var hasMozTransform,
        getHasMozTransform = function () {
            if (hasMozTransform === undefined) {
                hasMozTransform = document.createElement('p').style.MozTransform !== undefined;
            }
            return hasMozTransform;
        };

    $.magnificPopup.registerModule('zoom', {

        options: {
            enabled: false,
            easing: 'ease-in-out',
            duration: 300,
            opener: function (element) {
                return element.is('img') ? element : element.find('img');
            }
        },

        proto: {

            initZoom: function () {
                var zoomSt = mfp.st.zoom,
                    ns = '.zoom',
                    image;

                if (!zoomSt.enabled || !mfp.supportsTransition) {
                    return;
                }

                var duration = zoomSt.duration,
                    getElToAnimate = function (image) {
                        var newImg = image.clone().removeAttr('style').removeAttr('class').addClass('mfp-animated-image'),
                            transition = 'all ' + (zoomSt.duration / 1000) + 's ' + zoomSt.easing,
                            cssObj = {
                                position: 'fixed',
                                zIndex: 9999,
                                left: 0,
                                top: 0,
                                '-webkit-backface-visibility': 'hidden'
                            },
                            t = 'transition';

                        cssObj['-webkit-' + t] = cssObj['-moz-' + t] = cssObj['-o-' + t] = cssObj[t] = transition;

                        newImg.css(cssObj);
                        return newImg;
                    },
                    showMainContent = function () {
                        mfp.content.css('visibility', 'visible');
                    },
                    openTimeout,
                    animatedImg;

                _mfpOn('BuildControls' + ns, function () {
                    if (mfp._allowZoom()) {

                        clearTimeout(openTimeout);
                        mfp.content.css('visibility', 'hidden');

                        // Basically, all code below does is clones existing image, puts in on top of the current one and animated it

                        image = mfp._getItemToZoom();

                        if (!image) {
                            showMainContent();
                            return;
                        }

                        animatedImg = getElToAnimate(image);

                        animatedImg.css(mfp._getOffset());

                        mfp.wrap.append(animatedImg);

                        openTimeout = setTimeout(function () {
                            animatedImg.css(mfp._getOffset(true));
                            openTimeout = setTimeout(function () {

                                showMainContent();

                                setTimeout(function () {
                                    animatedImg.remove();
                                    image = animatedImg = null;
                                    _mfpTrigger('ZoomAnimationEnded');
                                }, 16); // avoid blink when switching images

                            }, duration); // this timeout equals animation duration

                        }, 16); // by adding this timeout we avoid short glitch at the beginning of animation


                        // Lots of timeouts...
                    }
                });
                _mfpOn(BEFORE_CLOSE_EVENT + ns, function () {
                    if (mfp._allowZoom()) {

                        clearTimeout(openTimeout);

                        mfp.st.removalDelay = duration;

                        if (!image) {
                            image = mfp._getItemToZoom();
                            if (!image) {
                                return;
                            }
                            animatedImg = getElToAnimate(image);
                        }

                        animatedImg.css(mfp._getOffset(true));
                        mfp.wrap.append(animatedImg);
                        mfp.content.css('visibility', 'hidden');

                        setTimeout(function () {
                            animatedImg.css(mfp._getOffset());
                        }, 16);
                    }

                });

                _mfpOn(CLOSE_EVENT + ns, function () {
                    if (mfp._allowZoom()) {
                        showMainContent();
                        if (animatedImg) {
                            animatedImg.remove();
                        }
                        image = null;
                    }
                });
            },

            _allowZoom: function () {
                return mfp.currItem.type === 'image';
            },

            _getItemToZoom: function () {
                if (mfp.currItem.hasSize) {
                    return mfp.currItem.img;
                } else {
                    return false;
                }
            },

            // Get element postion relative to viewport
            _getOffset: function (isLarge) {
                var el;
                if (isLarge) {
                    el = mfp.currItem.img;
                } else {
                    el = mfp.st.zoom.opener(mfp.currItem.el || mfp.currItem);
                }

                var offset = el.offset();
                var paddingTop = parseInt(el.css('padding-top'), 10);
                var paddingBottom = parseInt(el.css('padding-bottom'), 10);
                offset.top -= ($(window).scrollTop() - paddingTop);


                /*
    
                Animating left + top + width/height looks glitchy in Firefox, but perfect in Chrome. And vice-versa.
    
                 */
                var obj = {
                    width: el.width(),
                    // fix Zepto height+padding issue
                    height: (_isJQ ? el.innerHeight() : el[0].offsetHeight) - paddingBottom - paddingTop
                };

                // I hate to do this, but there is no another option
                if (getHasMozTransform()) {
                    obj['-moz-transform'] = obj['transform'] = 'translate(' + offset.left + 'px,' + offset.top + 'px)';
                } else {
                    obj.left = offset.left;
                    obj.top = offset.top;
                }
                return obj;
            }

        }
    });



    /*>>zoom*/

    /*>>iframe*/

    var IFRAME_NS = 'iframe',
        _emptyPage = '//about:blank',

        _fixIframeBugs = function (isShowing) {
            if (mfp.currTemplate[IFRAME_NS]) {
                var el = mfp.currTemplate[IFRAME_NS].find('iframe');
                if (el.length) {
                    // reset src after the popup is closed to avoid "video keeps playing after popup is closed" bug
                    if (!isShowing) {
                        el[0].src = _emptyPage;
                    }

                    // IE8 black screen bug fix
                    if (mfp.isIE8) {
                        el.css('display', isShowing ? 'block' : 'none');
                    }
                }
            }
        };

    $.magnificPopup.registerModule(IFRAME_NS, {

        options: {
            markup: '<div class="mfp-iframe-scaler">' +
                '<div class="mfp-close"></div>' +
                '<iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe>' +
                '</div>',

            srcAction: 'iframe_src',

            // we don't care and support only one default type of URL by default
            patterns: {
                youtube: {
                    index: 'youtube.com',
                    id: 'v=',
                    src: '//www.youtube.com/embed/%id%?autoplay=1'
                },
                vimeo: {
                    index: 'vimeo.com/',
                    id: '/',
                    src: '//player.vimeo.com/video/%id%?autoplay=1'
                },
                gmaps: {
                    index: '//maps.google.',
                    src: '%id%&output=embed'
                }
            }
        },

        proto: {
            initIframe: function () {
                mfp.types.push(IFRAME_NS);

                _mfpOn('BeforeChange', function (e, prevType, newType) {
                    if (prevType !== newType) {
                        if (prevType === IFRAME_NS) {
                            _fixIframeBugs(); // iframe if removed
                        } else if (newType === IFRAME_NS) {
                            _fixIframeBugs(true); // iframe is showing
                        }
                    }// else {
                    // iframe source is switched, don't do anything
                    //}
                });

                _mfpOn(CLOSE_EVENT + '.' + IFRAME_NS, function () {
                    _fixIframeBugs();
                });
            },

            getIframe: function (item, template) {
                var embedSrc = item.src;
                var iframeSt = mfp.st.iframe;

                $.each(iframeSt.patterns, function () {
                    if (embedSrc.indexOf(this.index) > -1) {
                        if (this.id) {
                            if (typeof this.id === 'string') {
                                embedSrc = embedSrc.substr(embedSrc.lastIndexOf(this.id) + this.id.length, embedSrc.length);
                            } else {
                                embedSrc = this.id.call(this, embedSrc);
                            }
                        }
                        embedSrc = this.src.replace('%id%', embedSrc);
                        return false; // break;
                    }
                });

                var dataObj = {};
                if (iframeSt.srcAction) {
                    dataObj[iframeSt.srcAction] = embedSrc;
                }
                mfp._parseMarkup(template, dataObj, item);

                mfp.updateStatus('ready');

                return template;
            }
        }
    });



    /*>>iframe*/

    /*>>gallery*/
    /**
     * Get looped index depending on number of slides
     */
    var _getLoopedId = function (index) {
        var numSlides = mfp.items.length;
        if (index > numSlides - 1) {
            return index - numSlides;
        } else if (index < 0) {
            return numSlides + index;
        }
        return index;
    },
        _replaceCurrTotal = function (text, curr, total) {
            return text.replace(/%curr%/gi, curr + 1).replace(/%total%/gi, total);
        };

    $.magnificPopup.registerModule('gallery', {

        options: {
            enabled: false,
            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
            preload: [0, 2],
            navigateByImgClick: true,
            arrows: true,

            tPrev: 'Previous (Left arrow key)',
            tNext: 'Next (Right arrow key)',
            tCounter: '%curr% of %total%'
        },

        proto: {
            initGallery: function () {

                var gSt = mfp.st.gallery,
                    ns = '.mfp-gallery';

                mfp.direction = true; // true - next, false - prev

                if (!gSt || !gSt.enabled) return false;

                _wrapClasses += ' mfp-gallery';

                _mfpOn(OPEN_EVENT + ns, function () {

                    if (gSt.navigateByImgClick) {
                        mfp.wrap.on('click' + ns, '.mfp-img', function () {
                            if (mfp.items.length > 1) {
                                mfp.next();
                                return false;
                            }
                        });
                    }

                    _document.on('keydown' + ns, function (e) {
                        if (e.keyCode === 37) {
                            mfp.prev();
                        } else if (e.keyCode === 39) {
                            mfp.next();
                        }
                    });
                });

                _mfpOn('UpdateStatus' + ns, function (e, data) {
                    if (data.text) {
                        data.text = _replaceCurrTotal(data.text, mfp.currItem.index, mfp.items.length);
                    }
                });

                _mfpOn(MARKUP_PARSE_EVENT + ns, function (e, element, values, item) {
                    var l = mfp.items.length;
                    values.counter = l > 1 ? _replaceCurrTotal(gSt.tCounter, item.index, l) : '';
                });

                _mfpOn('BuildControls' + ns, function () {
                    if (mfp.items.length > 1 && gSt.arrows && !mfp.arrowLeft) {
                        var markup = gSt.arrowMarkup,
                            arrowLeft = mfp.arrowLeft = $(markup.replace(/%title%/gi, gSt.tPrev).replace(/%dir%/gi, 'left')).addClass(PREVENT_CLOSE_CLASS),
                            arrowRight = mfp.arrowRight = $(markup.replace(/%title%/gi, gSt.tNext).replace(/%dir%/gi, 'right')).addClass(PREVENT_CLOSE_CLASS);

                        arrowLeft.click(function () {
                            mfp.prev();
                        });
                        arrowRight.click(function () {
                            mfp.next();
                        });

                        mfp.container.append(arrowLeft.add(arrowRight));
                    }
                });

                _mfpOn(CHANGE_EVENT + ns, function () {
                    if (mfp._preloadTimeout) clearTimeout(mfp._preloadTimeout);

                    mfp._preloadTimeout = setTimeout(function () {
                        mfp.preloadNearbyImages();
                        mfp._preloadTimeout = null;
                    }, 16);
                });


                _mfpOn(CLOSE_EVENT + ns, function () {
                    _document.off(ns);
                    mfp.wrap.off('click' + ns);
                    mfp.arrowRight = mfp.arrowLeft = null;
                });

            },
            next: function () {
                mfp.direction = true;
                mfp.index = _getLoopedId(mfp.index + 1);
                mfp.updateItemHTML();
            },
            prev: function () {
                mfp.direction = false;
                mfp.index = _getLoopedId(mfp.index - 1);
                mfp.updateItemHTML();
            },
            goTo: function (newIndex) {
                mfp.direction = (newIndex >= mfp.index);
                mfp.index = newIndex;
                mfp.updateItemHTML();
            },
            preloadNearbyImages: function () {
                var p = mfp.st.gallery.preload,
                    preloadBefore = Math.min(p[0], mfp.items.length),
                    preloadAfter = Math.min(p[1], mfp.items.length),
                    i;

                for (i = 1; i <= (mfp.direction ? preloadAfter : preloadBefore); i++) {
                    mfp._preloadItem(mfp.index + i);
                }
                for (i = 1; i <= (mfp.direction ? preloadBefore : preloadAfter); i++) {
                    mfp._preloadItem(mfp.index - i);
                }
            },
            _preloadItem: function (index) {
                index = _getLoopedId(index);

                if (mfp.items[index].preloaded) {
                    return;
                }

                var item = mfp.items[index];
                if (!item.parsed) {
                    item = mfp.parseEl(index);
                }

                _mfpTrigger('LazyLoad', item);

                if (item.type === 'image') {
                    item.img = $('<img class="mfp-img" />').on('load.mfploader', function () {
                        item.hasSize = true;
                    }).on('error.mfploader', function () {
                        item.hasSize = true;
                        item.loadError = true;
                        _mfpTrigger('LazyLoadError', item);
                    }).attr('src', item.src);
                }


                item.preloaded = true;
            }
        }
    });

    /*>>gallery*/

    /*>>retina*/

    var RETINA_NS = 'retina';

    $.magnificPopup.registerModule(RETINA_NS, {
        options: {
            replaceSrc: function (item) {
                return item.src.replace(/\.\w+$/, function (m) { return '@2x' + m; });
            },
            ratio: 1 // Function or number.  Set to 1 to disable.
        },
        proto: {
            initRetina: function () {
                if (window.devicePixelRatio > 1) {

                    var st = mfp.st.retina,
                        ratio = st.ratio;

                    ratio = !isNaN(ratio) ? ratio : ratio();

                    if (ratio > 1) {
                        _mfpOn('ImageHasSize' + '.' + RETINA_NS, function (e, item) {
                            item.img.css({
                                'max-width': item.img[0].naturalWidth / ratio,
                                'width': '100%'
                            });
                        });
                        _mfpOn('ElementParse' + '.' + RETINA_NS, function (e, item) {
                            item.src = st.replaceSrc(item, ratio);
                        });
                    }
                }

            }
        }
    });

    /*>>retina*/
    _checkInstance();
}));
(function($) {
	$.fn.eaelProgressBar = function() {
		var $this = $(this)
		var $layout = $this.data('layout')
		var $num = $this.data('count')
		var $duration = $this.data('duration')

		$this.one('inview', function() {
			if ($layout == 'line') {
				$('.eael-progressbar-line-fill', $this).css({
					'width': $num + '%',
				})
			} else if ($layout == 'half_circle') {
				$('.eael-progressbar-circle-half', $this).css({
					'transform': 'rotate(' + ($num * 1.8) + 'deg)',
				})
			}

			$('.eael-progressbar-count', $this).prop({
				'counter': 0
			}).animate({
				counter: $num
			}, {
				duration: $duration,
				easing: 'linear',
				step: function(counter) {
					if ($layout == 'circle') {
						var rotate = (counter * 3.6)
						$('.eael-progressbar-circle-half-left', $this).css({
							'transform': "rotate(" + rotate + "deg)",
						})
						if (rotate > 180) {
							$('.eael-progressbar-circle-pie', $this).css({
								'-webkit-clip-path': 'inset(0)',
								'clip-path': 'inset(0)',
							})
							$('.eael-progressbar-circle-half-right', $this).css({
								'visibility': 'visible'
							})
						}
					}

					$(this).text(Math.ceil(counter))
				}
			})
		})
	}
}(jQuery));
typeof navigator === "object" && (function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
  typeof define === 'function' && define.amd ? define('Plyr', factory) :
  (global = global || self, global.Plyr = factory());
}(this, function () { 'use strict';

  function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
      throw new TypeError("Cannot call a class as a function");
    }
  }

  function _defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      Object.defineProperty(target, descriptor.key, descriptor);
    }
  }

  function _createClass(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    return Constructor;
  }

  function _defineProperty(obj, key, value) {
    if (key in obj) {
      Object.defineProperty(obj, key, {
        value: value,
        enumerable: true,
        configurable: true,
        writable: true
      });
    } else {
      obj[key] = value;
    }

    return obj;
  }

  function _slicedToArray(arr, i) {
    return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _nonIterableRest();
  }

  function _toConsumableArray(arr) {
    return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _nonIterableSpread();
  }

  function _arrayWithoutHoles(arr) {
    if (Array.isArray(arr)) {
      for (var i = 0, arr2 = new Array(arr.length); i < arr.length; i++) arr2[i] = arr[i];

      return arr2;
    }
  }

  function _arrayWithHoles(arr) {
    if (Array.isArray(arr)) return arr;
  }

  function _iterableToArray(iter) {
    if (Symbol.iterator in Object(iter) || Object.prototype.toString.call(iter) === "[object Arguments]") return Array.from(iter);
  }

  function _iterableToArrayLimit(arr, i) {
    var _arr = [];
    var _n = true;
    var _d = false;
    var _e = undefined;

    try {
      for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {
        _arr.push(_s.value);

        if (i && _arr.length === i) break;
      }
    } catch (err) {
      _d = true;
      _e = err;
    } finally {
      try {
        if (!_n && _i["return"] != null) _i["return"]();
      } finally {
        if (_d) throw _e;
      }
    }

    return _arr;
  }

  function _nonIterableSpread() {
    throw new TypeError("Invalid attempt to spread non-iterable instance");
  }

  function _nonIterableRest() {
    throw new TypeError("Invalid attempt to destructure non-iterable instance");
  }

  var defaults = {
    addCSS: true,
    // Add CSS to the element to improve usability (required here or in your CSS!)
    thumbWidth: 15,
    // The width of the thumb handle
    watch: true // Watch for new elements that match a string target

  };

  // Element matches a selector
  function matches(element, selector) {

    function match() {
      return Array.from(document.querySelectorAll(selector)).includes(this);
    }

    var matches = match;
    return matches.call(element, selector);
  }

  // Trigger event
  function trigger(element, type) {
    if (!element || !type) {
      return;
    } // Create and dispatch the event


    var event = new Event(type); // Dispatch the event

    element.dispatchEvent(event);
  }

  // ==========================================================================
  // Type checking utils
  // ==========================================================================
  var getConstructor = function getConstructor(input) {
    return input !== null && typeof input !== 'undefined' ? input.constructor : null;
  };

  var instanceOf = function instanceOf(input, constructor) {
    return Boolean(input && constructor && input instanceof constructor);
  };

  var isNullOrUndefined = function isNullOrUndefined(input) {
    return input === null || typeof input === 'undefined';
  };

  var isObject = function isObject(input) {
    return getConstructor(input) === Object;
  };

  var isNumber = function isNumber(input) {
    return getConstructor(input) === Number && !Number.isNaN(input);
  };

  var isString = function isString(input) {
    return getConstructor(input) === String;
  };

  var isBoolean = function isBoolean(input) {
    return getConstructor(input) === Boolean;
  };

  var isFunction = function isFunction(input) {
    return getConstructor(input) === Function;
  };

  var isArray = function isArray(input) {
    return Array.isArray(input);
  };

  var isNodeList = function isNodeList(input) {
    return instanceOf(input, NodeList);
  };

  var isElement = function isElement(input) {
    return instanceOf(input, Element);
  };

  var isEvent = function isEvent(input) {
    return instanceOf(input, Event);
  };

  var isEmpty = function isEmpty(input) {
    return isNullOrUndefined(input) || (isString(input) || isArray(input) || isNodeList(input)) && !input.length || isObject(input) && !Object.keys(input).length;
  };

  var is = {
    nullOrUndefined: isNullOrUndefined,
    object: isObject,
    number: isNumber,
    string: isString,
    boolean: isBoolean,
    function: isFunction,
    array: isArray,
    nodeList: isNodeList,
    element: isElement,
    event: isEvent,
    empty: isEmpty
  };

  // Get the number of decimal places
  function getDecimalPlaces(value) {
    var match = "".concat(value).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);

    if (!match) {
      return 0;
    }

    return Math.max(0, // Number of digits right of decimal point.
    (match[1] ? match[1].length : 0) - ( // Adjust for scientific notation.
    match[2] ? +match[2] : 0));
  } // Round to the nearest step

  function round(number, step) {
    if (step < 1) {
      var places = getDecimalPlaces(step);
      return parseFloat(number.toFixed(places));
    }

    return Math.round(number / step) * step;
  }

  var RangeTouch =
  /*#__PURE__*/
  function () {
    /**
     * Setup a new instance
     * @param {String|Element} target
     * @param {Object} options
     */
    function RangeTouch(target, options) {
      _classCallCheck(this, RangeTouch);

      if (is.element(target)) {
        // An Element is passed, use it directly
        this.element = target;
      } else if (is.string(target)) {
        // A CSS Selector is passed, fetch it from the DOM
        this.element = document.querySelector(target);
      }

      if (!is.element(this.element) || !is.empty(this.element.rangeTouch)) {
        return;
      }

      this.config = Object.assign({}, defaults, options);
      this.init();
    }

    _createClass(RangeTouch, [{
      key: "init",
      value: function init() {
        // Bail if not a touch enabled device
        if (!RangeTouch.enabled) {
          return;
        } // Add useful CSS


        if (this.config.addCSS) {
          // TODO: Restore original values on destroy
          this.element.style.userSelect = 'none';
          this.element.style.webKitUserSelect = 'none';
          this.element.style.touchAction = 'manipulation';
        }

        this.listeners(true);
        this.element.rangeTouch = this;
      }
    }, {
      key: "destroy",
      value: function destroy() {
        // Bail if not a touch enabled device
        if (!RangeTouch.enabled) {
          return;
        }

        this.listeners(false);
        this.element.rangeTouch = null;
      }
    }, {
      key: "listeners",
      value: function listeners(toggle) {
        var _this = this;

        var method = toggle ? 'addEventListener' : 'removeEventListener'; // Listen for events

        ['touchstart', 'touchmove', 'touchend'].forEach(function (type) {
          _this.element[method](type, function (event) {
            return _this.set(event);
          }, false);
        });
      }
      /**
       * Get the value based on touch position
       * @param {Event} event
       */

    }, {
      key: "get",
      value: function get(event) {
        if (!RangeTouch.enabled || !is.event(event)) {
          return null;
        }

        var input = event.target;
        var touch = event.changedTouches[0];
        var min = parseFloat(input.getAttribute('min')) || 0;
        var max = parseFloat(input.getAttribute('max')) || 100;
        var step = parseFloat(input.getAttribute('step')) || 1;
        var delta = max - min; // Calculate percentage

        var percent;
        var clientRect = input.getBoundingClientRect();
        var thumbWidth = 100 / clientRect.width * (this.config.thumbWidth / 2) / 100; // Determine left percentage

        percent = 100 / clientRect.width * (touch.clientX - clientRect.left); // Don't allow outside bounds

        if (percent < 0) {
          percent = 0;
        } else if (percent > 100) {
          percent = 100;
        } // Factor in the thumb offset


        if (percent < 50) {
          percent -= (100 - percent * 2) * thumbWidth;
        } else if (percent > 50) {
          percent += (percent - 50) * 2 * thumbWidth;
        } // Find the closest step to the mouse position


        return min + round(delta * (percent / 100), step);
      }
      /**
       * Update range value based on position
       * @param {Event} event
       */

    }, {
      key: "set",
      value: function set(event) {
        if (!RangeTouch.enabled || !is.event(event) || event.target.disabled) {
          return;
        } // Prevent text highlight on iOS


        event.preventDefault(); // Set value

        event.target.value = this.get(event); // Trigger event

        trigger(event.target, event.type === 'touchend' ? 'change' : 'input');
      }
    }], [{
      key: "setup",

      /**
       * Setup multiple instances
       * @param {String|Element|NodeList|Array} target
       * @param {Object} options
       */
      value: function setup(target) {
        var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
        var targets = null;

        if (is.empty(target) || is.string(target)) {
          targets = Array.from(document.querySelectorAll(is.string(target) ? target : 'input[type="range"]'));
        } else if (is.element(target)) {
          targets = [target];
        } else if (is.nodeList(target)) {
          targets = Array.from(target);
        } else if (is.array(target)) {
          targets = target.filter(is.element);
        }

        if (is.empty(targets)) {
          return null;
        }

        var config = Object.assign({}, defaults, options);

        if (is.string(target) && config.watch) {
          // Create an observer instance
          var observer = new MutationObserver(function (mutations) {
            Array.from(mutations).forEach(function (mutation) {
              Array.from(mutation.addedNodes).forEach(function (node) {
                if (!is.element(node) || !matches(node, target)) {
                  return;
                } // eslint-disable-next-line no-unused-vars


                var range = new RangeTouch(node, config);
              });
            });
          }); // Pass in the target node, as well as the observer options

          observer.observe(document.body, {
            childList: true,
            subtree: true
          });
        }

        return targets.map(function (t) {
          return new RangeTouch(t, options);
        });
      }
    }, {
      key: "enabled",
      get: function get() {
        return 'ontouchstart' in document.documentElement;
      }
    }]);

    return RangeTouch;
  }();

  // ==========================================================================
  // Type checking utils
  // ==========================================================================
  var getConstructor$1 = function getConstructor(input) {
    return input !== null && typeof input !== 'undefined' ? input.constructor : null;
  };

  var instanceOf$1 = function instanceOf(input, constructor) {
    return Boolean(input && constructor && input instanceof constructor);
  };

  var isNullOrUndefined$1 = function isNullOrUndefined(input) {
    return input === null || typeof input === 'undefined';
  };

  var isObject$1 = function isObject(input) {
    return getConstructor$1(input) === Object;
  };

  var isNumber$1 = function isNumber(input) {
    return getConstructor$1(input) === Number && !Number.isNaN(input);
  };

  var isString$1 = function isString(input) {
    return getConstructor$1(input) === String;
  };

  var isBoolean$1 = function isBoolean(input) {
    return getConstructor$1(input) === Boolean;
  };

  var isFunction$1 = function isFunction(input) {
    return getConstructor$1(input) === Function;
  };

  var isArray$1 = function isArray(input) {
    return Array.isArray(input);
  };

  var isWeakMap = function isWeakMap(input) {
    return instanceOf$1(input, WeakMap);
  };

  var isNodeList$1 = function isNodeList(input) {
    return instanceOf$1(input, NodeList);
  };

  var isElement$1 = function isElement(input) {
    return instanceOf$1(input, Element);
  };

  var isTextNode = function isTextNode(input) {
    return getConstructor$1(input) === Text;
  };

  var isEvent$1 = function isEvent(input) {
    return instanceOf$1(input, Event);
  };

  var isKeyboardEvent = function isKeyboardEvent(input) {
    return instanceOf$1(input, KeyboardEvent);
  };

  var isCue = function isCue(input) {
    return instanceOf$1(input, window.TextTrackCue) || instanceOf$1(input, window.VTTCue);
  };

  var isTrack = function isTrack(input) {
    return instanceOf$1(input, TextTrack) || !isNullOrUndefined$1(input) && isString$1(input.kind);
  };

  var isPromise = function isPromise(input) {
    return instanceOf$1(input, Promise);
  };

  var isEmpty$1 = function isEmpty(input) {
    return isNullOrUndefined$1(input) || (isString$1(input) || isArray$1(input) || isNodeList$1(input)) && !input.length || isObject$1(input) && !Object.keys(input).length;
  };

  var isUrl = function isUrl(input) {
    // Accept a URL object
    if (instanceOf$1(input, window.URL)) {
      return true;
    } // Must be string from here


    if (!isString$1(input)) {
      return false;
    } // Add the protocol if required


    var string = input;

    if (!input.startsWith('http://') || !input.startsWith('https://')) {
      string = "http://".concat(input);
    }

    try {
      return !isEmpty$1(new URL(string).hostname);
    } catch (e) {
      return false;
    }
  };

  var is$1 = {
    nullOrUndefined: isNullOrUndefined$1,
    object: isObject$1,
    number: isNumber$1,
    string: isString$1,
    boolean: isBoolean$1,
    function: isFunction$1,
    array: isArray$1,
    weakMap: isWeakMap,
    nodeList: isNodeList$1,
    element: isElement$1,
    textNode: isTextNode,
    event: isEvent$1,
    keyboardEvent: isKeyboardEvent,
    cue: isCue,
    track: isTrack,
    promise: isPromise,
    url: isUrl,
    empty: isEmpty$1
  };

  // ==========================================================================
  var transitionEndEvent = function () {
    var element = document.createElement('span');
    var events = {
      WebkitTransition: 'webkitTransitionEnd',
      MozTransition: 'transitionend',
      OTransition: 'oTransitionEnd otransitionend',
      transition: 'transitionend'
    };
    var type = Object.keys(events).find(function (event) {
      return element.style[event] !== undefined;
    });
    return is$1.string(type) ? events[type] : false;
  }(); // Force repaint of element

  function repaint(element, delay) {
    setTimeout(function () {
      try {
        // eslint-disable-next-line no-param-reassign
        element.hidden = true; // eslint-disable-next-line no-unused-expressions

        element.offsetHeight; // eslint-disable-next-line no-param-reassign

        element.hidden = false;
      } catch (e) {// Do nothing
      }
    }, delay);
  }

  // ==========================================================================
  // Browser sniffing
  // Unfortunately, due to mixed support, UA sniffing is required
  // ==========================================================================
  var browser = {
    isIE:
    /* @cc_on!@ */
    !!document.documentMode,
    isEdge: window.navigator.userAgent.includes('Edge'),
    isWebkit: 'WebkitAppearance' in document.documentElement.style && !/Edge/.test(navigator.userAgent),
    isIPhone: /(iPhone|iPod)/gi.test(navigator.platform),
    isIos: /(iPad|iPhone|iPod)/gi.test(navigator.platform)
  };

  // ==========================================================================
  // https://github.com/WICG/EventListenerOptions/blob/gh-pages/explainer.md
  // https://www.youtube.com/watch?v=NPM6172J22g

  var supportsPassiveListeners = function () {
    // Test via a getter in the options object to see if the passive property is accessed
    var supported = false;

    try {
      var options = Object.defineProperty({}, 'passive', {
        get: function get() {
          supported = true;
          return null;
        }
      });
      window.addEventListener('test', null, options);
      window.removeEventListener('test', null, options);
    } catch (e) {// Do nothing
    }

    return supported;
  }(); // Toggle event listener


  function toggleListener(element, event, callback) {
    var _this = this;

    var toggle = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;
    var passive = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : true;
    var capture = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : false;

    // Bail if no element, event, or callback
    if (!element || !('addEventListener' in element) || is$1.empty(event) || !is$1.function(callback)) {
      return;
    } // Allow multiple events


    var events = event.split(' '); // Build options
    // Default to just the capture boolean for browsers with no passive listener support

    var options = capture; // If passive events listeners are supported

    if (supportsPassiveListeners) {
      options = {
        // Whether the listener can be passive (i.e. default never prevented)
        passive: passive,
        // Whether the listener is a capturing listener or not
        capture: capture
      };
    } // If a single node is passed, bind the event listener


    events.forEach(function (type) {
      if (_this && _this.eventListeners && toggle) {
        // Cache event listener
        _this.eventListeners.push({
          element: element,
          type: type,
          callback: callback,
          options: options
        });
      }

      element[toggle ? 'addEventListener' : 'removeEventListener'](type, callback, options);
    });
  } // Bind event handler

  function on(element) {
    var events = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
    var callback = arguments.length > 2 ? arguments[2] : undefined;
    var passive = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : true;
    var capture = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : false;
    toggleListener.call(this, element, events, callback, true, passive, capture);
  } // Unbind event handler

  function off(element) {
    var events = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
    var callback = arguments.length > 2 ? arguments[2] : undefined;
    var passive = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : true;
    var capture = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : false;
    toggleListener.call(this, element, events, callback, false, passive, capture);
  } // Bind once-only event handler

  function once(element) {
    var _this2 = this;

    var events = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
    var callback = arguments.length > 2 ? arguments[2] : undefined;
    var passive = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : true;
    var capture = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : false;

    var onceCallback = function onceCallback() {
      off(element, events, onceCallback, passive, capture);

      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }

      callback.apply(_this2, args);
    };

    toggleListener.call(this, element, events, onceCallback, true, passive, capture);
  } // Trigger event

  function triggerEvent(element) {
    var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
    var bubbles = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
    var detail = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};

    // Bail if no element
    if (!is$1.element(element) || is$1.empty(type)) {
      return;
    } // Create and dispatch the event


    var event = new CustomEvent(type, {
      bubbles: bubbles,
      detail: Object.assign({}, detail, {
        plyr: this
      })
    }); // Dispatch the event

    element.dispatchEvent(event);
  } // Unbind all cached event listeners

  function unbindListeners() {
    if (this && this.eventListeners) {
      this.eventListeners.forEach(function (item) {
        var element = item.element,
            type = item.type,
            callback = item.callback,
            options = item.options;
        element.removeEventListener(type, callback, options);
      });
      this.eventListeners = [];
    }
  } // Run method when / if player is ready

  function ready() {
    var _this3 = this;

    return new Promise(function (resolve) {
      return _this3.ready ? setTimeout(resolve, 0) : on.call(_this3, _this3.elements.container, 'ready', resolve);
    }).then(function () {});
  }

  function cloneDeep(object) {
    return JSON.parse(JSON.stringify(object));
  } // Get a nested value in an object

  function getDeep(object, path) {
    return path.split('.').reduce(function (obj, key) {
      return obj && obj[key];
    }, object);
  } // Deep extend destination object with N more objects

  function extend() {
    var target = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

    for (var _len = arguments.length, sources = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
      sources[_key - 1] = arguments[_key];
    }

    if (!sources.length) {
      return target;
    }

    var source = sources.shift();

    if (!is$1.object(source)) {
      return target;
    }

    Object.keys(source).forEach(function (key) {
      if (is$1.object(source[key])) {
        if (!Object.keys(target).includes(key)) {
          Object.assign(target, _defineProperty({}, key, {}));
        }

        extend(target[key], source[key]);
      } else {
        Object.assign(target, _defineProperty({}, key, source[key]));
      }
    });
    return extend.apply(void 0, [target].concat(sources));
  }

  function wrap(elements, wrapper) {
    // Convert `elements` to an array, if necessary.
    var targets = elements.length ? elements : [elements]; // Loops backwards to prevent having to clone the wrapper on the
    // first element (see `child` below).

    Array.from(targets).reverse().forEach(function (element, index) {
      var child = index > 0 ? wrapper.cloneNode(true) : wrapper; // Cache the current parent and sibling.

      var parent = element.parentNode;
      var sibling = element.nextSibling; // Wrap the element (is automatically removed from its current
      // parent).

      child.appendChild(element); // If the element had a sibling, insert the wrapper before
      // the sibling to maintain the HTML structure; otherwise, just
      // append it to the parent.

      if (sibling) {
        parent.insertBefore(child, sibling);
      } else {
        parent.appendChild(child);
      }
    });
  } // Set attributes

  function setAttributes(element, attributes) {
    if (!is$1.element(element) || is$1.empty(attributes)) {
      return;
    } // Assume null and undefined attributes should be left out,
    // Setting them would otherwise convert them to "null" and "undefined"


    Object.entries(attributes).filter(function (_ref) {
      var _ref2 = _slicedToArray(_ref, 2),
          value = _ref2[1];

      return !is$1.nullOrUndefined(value);
    }).forEach(function (_ref3) {
      var _ref4 = _slicedToArray(_ref3, 2),
          key = _ref4[0],
          value = _ref4[1];

      return element.setAttribute(key, value);
    });
  } // Create a DocumentFragment

  function createElement(type, attributes, text) {
    // Create a new <element>
    var element = document.createElement(type); // Set all passed attributes

    if (is$1.object(attributes)) {
      setAttributes(element, attributes);
    } // Add text node


    if (is$1.string(text)) {
      element.innerText = text;
    } // Return built element


    return element;
  } // Inaert an element after another

  function insertAfter(element, target) {
    if (!is$1.element(element) || !is$1.element(target)) {
      return;
    }

    target.parentNode.insertBefore(element, target.nextSibling);
  } // Insert a DocumentFragment

  function insertElement(type, parent, attributes, text) {
    if (!is$1.element(parent)) {
      return;
    }

    parent.appendChild(createElement(type, attributes, text));
  } // Remove element(s)

  function removeElement(element) {
    if (is$1.nodeList(element) || is$1.array(element)) {
      Array.from(element).forEach(removeElement);
      return;
    }

    if (!is$1.element(element) || !is$1.element(element.parentNode)) {
      return;
    }

    element.parentNode.removeChild(element);
  } // Remove all child elements

  function emptyElement(element) {
    if (!is$1.element(element)) {
      return;
    }

    var length = element.childNodes.length;

    while (length > 0) {
      element.removeChild(element.lastChild);
      length -= 1;
    }
  } // Replace element

  function replaceElement(newChild, oldChild) {
    if (!is$1.element(oldChild) || !is$1.element(oldChild.parentNode) || !is$1.element(newChild)) {
      return null;
    }

    oldChild.parentNode.replaceChild(newChild, oldChild);
    return newChild;
  } // Get an attribute object from a string selector

  function getAttributesFromSelector(sel, existingAttributes) {
    // For example:
    // '.test' to { class: 'test' }
    // '#test' to { id: 'test' }
    // '[data-test="test"]' to { 'data-test': 'test' }
    if (!is$1.string(sel) || is$1.empty(sel)) {
      return {};
    }

    var attributes = {};
    var existing = extend({}, existingAttributes);
    sel.split(',').forEach(function (s) {
      // Remove whitespace
      var selector = s.trim();
      var className = selector.replace('.', '');
      var stripped = selector.replace(/[[\]]/g, ''); // Get the parts and value

      var parts = stripped.split('=');

      var _parts = _slicedToArray(parts, 1),
          key = _parts[0];

      var value = parts.length > 1 ? parts[1].replace(/["']/g, '') : ''; // Get the first character

      var start = selector.charAt(0);

      switch (start) {
        case '.':
          // Add to existing classname
          if (is$1.string(existing.class)) {
            attributes.class = "".concat(existing.class, " ").concat(className);
          } else {
            attributes.class = className;
          }

          break;

        case '#':
          // ID selector
          attributes.id = selector.replace('#', '');
          break;

        case '[':
          // Attribute selector
          attributes[key] = value;
          break;

        default:
          break;
      }
    });
    return extend(existing, attributes);
  } // Toggle hidden

  function toggleHidden(element, hidden) {
    if (!is$1.element(element)) {
      return;
    }

    var hide = hidden;

    if (!is$1.boolean(hide)) {
      hide = !element.hidden;
    } // eslint-disable-next-line no-param-reassign


    element.hidden = hide;
  } // Mirror Element.classList.toggle, with IE compatibility for "force" argument

  function toggleClass(element, className, force) {
    if (is$1.nodeList(element)) {
      return Array.from(element).map(function (e) {
        return toggleClass(e, className, force);
      });
    }

    if (is$1.element(element)) {
      var method = 'toggle';

      if (typeof force !== 'undefined') {
        method = force ? 'add' : 'remove';
      }

      element.classList[method](className);
      return element.classList.contains(className);
    }

    return false;
  } // Has class name

  function hasClass(element, className) {
    return is$1.element(element) && element.classList.contains(className);
  } // Element matches selector

  function matches$1(element, selector) {

    function match() {
      return Array.from(document.querySelectorAll(selector)).includes(this);
    }

    var method = match;
    return method.call(element, selector);
  } // Find all elements

  function getElements(selector) {
    return this.elements.container.querySelectorAll(selector);
  } // Find a single element

  function getElement(selector) {
    return this.elements.container.querySelector(selector);
  } // Trap focus inside container

  function trapFocus() {
    var element = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
    var toggle = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

    if (!is$1.element(element)) {
      return;
    }

    var focusable = getElements.call(this, 'button:not(:disabled), input:not(:disabled), [tabindex]');
    var first = focusable[0];
    var last = focusable[focusable.length - 1];

    var trap = function trap(event) {
      // Bail if not tab key or not fullscreen
      if (event.key !== 'Tab' || event.keyCode !== 9) {
        return;
      } // Get the current focused element


      var focused = document.activeElement;

      if (focused === last && !event.shiftKey) {
        // Move focus to first element that can be tabbed if Shift isn't used
        first.focus();
        event.preventDefault();
      } else if (focused === first && event.shiftKey) {
        // Move focus to last element that can be tabbed if Shift is used
        last.focus();
        event.preventDefault();
      }
    };

    toggleListener.call(this, this.elements.container, 'keydown', trap, toggle, false);
  } // Set focus and tab focus class

  function setFocus() {
    var element = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
    var tabFocus = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

    if (!is$1.element(element)) {
      return;
    } // Set regular focus


    element.focus({
      preventScroll: true
    }); // If we want to mimic keyboard focus via tab

    if (tabFocus) {
      toggleClass(element, this.config.classNames.tabFocus);
    }
  }

  var defaultCodecs = {
    'audio/ogg': 'vorbis',
    'audio/wav': '1',
    'video/webm': 'vp8, vorbis',
    'video/mp4': 'avc1.42E01E, mp4a.40.2',
    'video/ogg': 'theora'
  }; // Check for feature support

  var support = {
    // Basic support
    audio: 'canPlayType' in document.createElement('audio'),
    video: 'canPlayType' in document.createElement('video'),
    // Check for support
    // Basic functionality vs full UI
    check: function check(type, provider, playsinline) {
      var canPlayInline = browser.isIPhone && playsinline && support.playsinline;
      var api = support[type] || provider !== 'html5';
      var ui = api && support.rangeInput && (type !== 'video' || !browser.isIPhone || canPlayInline);
      return {
        api: api,
        ui: ui
      };
    },
    // Picture-in-picture support
    // Safari & Chrome only currently
    pip: function () {
      if (browser.isIPhone) {
        return false;
      } // Safari
      // https://developer.apple.com/documentation/webkitjs/adding_picture_in_picture_to_your_safari_media_controls


      if (is$1.function(createElement('video').webkitSetPresentationMode)) {
        return true;
      } // Chrome
      // https://developers.google.com/web/updates/2018/10/watch-video-using-picture-in-picture


      if (document.pictureInPictureEnabled && !createElement('video').disablePictureInPicture) {
        return true;
      }

      return false;
    }(),
    // Airplay support
    // Safari only currently
    airplay: is$1.function(window.WebKitPlaybackTargetAvailabilityEvent),
    // Inline playback support
    // https://webkit.org/blog/6784/new-video-policies-for-ios/
    playsinline: 'playsInline' in document.createElement('video'),
    // Check for mime type support against a player instance
    // Credits: http://diveintohtml5.info/everything.html
    // Related: http://www.leanbackplayer.com/test/h5mt.html
    mime: function mime(input) {
      if (is$1.empty(input)) {
        return false;
      }

      var _input$split = input.split('/'),
          _input$split2 = _slicedToArray(_input$split, 1),
          mediaType = _input$split2[0];

      var type = input; // Verify we're using HTML5 and there's no media type mismatch

      if (!this.isHTML5 || mediaType !== this.type) {
        return false;
      } // Add codec if required


      if (Object.keys(defaultCodecs).includes(type)) {
        type += "; codecs=\"".concat(defaultCodecs[input], "\"");
      }

      try {
        return Boolean(type && this.media.canPlayType(type).replace(/no/, ''));
      } catch (e) {
        return false;
      }
    },
    // Check for textTracks support
    textTracks: 'textTracks' in document.createElement('video'),
    // <input type="range"> Sliders
    rangeInput: function () {
      var range = document.createElement('input');
      range.type = 'range';
      return range.type === 'range';
    }(),
    // Touch
    // NOTE: Remember a device can be mouse + touch enabled so we check on first touch event
    touch: 'ontouchstart' in document.documentElement,
    // Detect transitions support
    transitions: transitionEndEvent !== false,
    // Reduced motion iOS & MacOS setting
    // https://webkit.org/blog/7551/responsive-design-for-motion/
    reducedMotion: 'matchMedia' in window && window.matchMedia('(prefers-reduced-motion)').matches
  };

  function validateRatio(input) {
    if (!is$1.array(input) && (!is$1.string(input) || !input.includes(':'))) {
      return false;
    }

    var ratio = is$1.array(input) ? input : input.split(':');
    return ratio.map(Number).every(is$1.number);
  }
  function reduceAspectRatio(ratio) {
    if (!is$1.array(ratio) || !ratio.every(is$1.number)) {
      return null;
    }

    var _ratio = _slicedToArray(ratio, 2),
        width = _ratio[0],
        height = _ratio[1];

    var getDivider = function getDivider(w, h) {
      return h === 0 ? w : getDivider(h, w % h);
    };

    var divider = getDivider(width, height);
    return [width / divider, height / divider];
  }
  function getAspectRatio(input) {
    var parse = function parse(ratio) {
      return validateRatio(ratio) ? ratio.split(':').map(Number) : null;
    }; // Try provided ratio


    var ratio = parse(input); // Get from config

    if (ratio === null) {
      ratio = parse(this.config.ratio);
    } // Get from embed


    if (ratio === null && !is$1.empty(this.embed) && is$1.array(this.embed.ratio)) {
      ratio = this.embed.ratio;
    } // Get from HTML5 video


    if (ratio === null && this.isHTML5) {
      var _this$media = this.media,
          videoWidth = _this$media.videoWidth,
          videoHeight = _this$media.videoHeight;
      ratio = reduceAspectRatio([videoWidth, videoHeight]);
    }

    return ratio;
  } // Set aspect ratio for responsive container

  function setAspectRatio(input) {
    if (!this.isVideo) {
      return {};
    }

    var ratio = getAspectRatio.call(this, input);

    var _ref = is$1.array(ratio) ? ratio : [0, 0],
        _ref2 = _slicedToArray(_ref, 2),
        w = _ref2[0],
        h = _ref2[1];

    var padding = 100 / w * h;
    this.elements.wrapper.style.paddingBottom = "".concat(padding, "%"); // For Vimeo we have an extra <div> to hide the standard controls and UI

    if (this.isVimeo && this.supported.ui) {
      var height = 240;
      var offset = (height - padding) / (height / 50);
      this.media.style.transform = "translateY(-".concat(offset, "%)");
    } else if (this.isHTML5) {
      this.elements.wrapper.classList.toggle(this.config.classNames.videoFixedRatio, ratio !== null);
    }

    return {
      padding: padding,
      ratio: ratio
    };
  }

  // ==========================================================================
  var html5 = {
    getSources: function getSources() {
      var _this = this;

      if (!this.isHTML5) {
        return [];
      }

      var sources = Array.from(this.media.querySelectorAll('source')); // Filter out unsupported sources (if type is specified)

      return sources.filter(function (source) {
        var type = source.getAttribute('type');

        if (is$1.empty(type)) {
          return true;
        }

        return support.mime.call(_this, type);
      });
    },
    // Get quality levels
    getQualityOptions: function getQualityOptions() {
      // Get sizes from <source> elements
      return html5.getSources.call(this).map(function (source) {
        return Number(source.getAttribute('size'));
      }).filter(Boolean);
    },
    extend: function extend() {
      if (!this.isHTML5) {
        return;
      }

      var player = this; // Set aspect ratio if fixed

      if (!is$1.empty(this.config.ratio)) {
        setAspectRatio.call(player);
      } // Quality


      Object.defineProperty(player.media, 'quality', {
        get: function get() {
          // Get sources
          var sources = html5.getSources.call(player);
          var source = sources.find(function (s) {
            return s.getAttribute('src') === player.source;
          }); // Return size, if match is found

          return source && Number(source.getAttribute('size'));
        },
        set: function set(input) {
          // Get sources
          var sources = html5.getSources.call(player); // Get first match for requested size

          var source = sources.find(function (s) {
            return Number(s.getAttribute('size')) === input;
          }); // No matching source found

          if (!source) {
            return;
          } // Get current state


          var _player$media = player.media,
              currentTime = _player$media.currentTime,
              paused = _player$media.paused,
              preload = _player$media.preload,
              readyState = _player$media.readyState; // Set new source

          player.media.src = source.getAttribute('src'); // Prevent loading if preload="none" and the current source isn't loaded (#1044)

          if (preload !== 'none' || readyState) {
            // Restore time
            player.once('loadedmetadata', function () {
              player.currentTime = currentTime; // Resume playing

              if (!paused) {
                player.play();
              }
            }); // Load new source

            player.media.load();
          } // Trigger change event


          triggerEvent.call(player, player.media, 'qualitychange', false, {
            quality: input
          });
        }
      });
    },
    // Cancel current network requests
    // See https://github.com/sampotts/plyr/issues/174
    cancelRequests: function cancelRequests() {
      if (!this.isHTML5) {
        return;
      } // Remove child sources


      removeElement(html5.getSources.call(this)); // Set blank video src attribute
      // This is to prevent a MEDIA_ERR_SRC_NOT_SUPPORTED error
      // Info: http://stackoverflow.com/questions/32231579/how-to-properly-dispose-of-an-html5-video-and-close-socket-or-connection

      this.media.setAttribute('src', this.config.blankVideo); // Load the new empty source
      // This will cancel existing requests
      // See https://github.com/sampotts/plyr/issues/174

      this.media.load(); // Debugging

      this.debug.log('Cancelled network requests');
    }
  };

  // ==========================================================================

  function dedupe(array) {
    if (!is$1.array(array)) {
      return array;
    }

    return array.filter(function (item, index) {
      return array.indexOf(item) === index;
    });
  } // Get the closest value in an array

  function closest(array, value) {
    if (!is$1.array(array) || !array.length) {
      return null;
    }

    return array.reduce(function (prev, curr) {
      return Math.abs(curr - value) < Math.abs(prev - value) ? curr : prev;
    });
  }

  // ==========================================================================

  function generateId(prefix) {
    return "".concat(prefix, "-").concat(Math.floor(Math.random() * 10000));
  } // Format string

  function format(input) {
    for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
      args[_key - 1] = arguments[_key];
    }

    if (is$1.empty(input)) {
      return input;
    }

    return input.toString().replace(/{(\d+)}/g, function (match, i) {
      return args[i].toString();
    });
  } // Get percentage

  function getPercentage(current, max) {
    if (current === 0 || max === 0 || Number.isNaN(current) || Number.isNaN(max)) {
      return 0;
    }

    return (current / max * 100).toFixed(2);
  } // Replace all occurances of a string in a string

  function replaceAll() {
    var input = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    var find = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
    var replace = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
    return input.replace(new RegExp(find.toString().replace(/([.*+?^=!:${}()|[\]/\\])/g, '\\$1'), 'g'), replace.toString());
  } // Convert to title case

  function toTitleCase() {
    var input = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    return input.toString().replace(/\w\S*/g, function (text) {
      return text.charAt(0).toUpperCase() + text.substr(1).toLowerCase();
    });
  } // Convert string to pascalCase

  function toPascalCase() {
    var input = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    var string = input.toString(); // Convert kebab case

    string = replaceAll(string, '-', ' '); // Convert snake case

    string = replaceAll(string, '_', ' '); // Convert to title case

    string = toTitleCase(string); // Convert to pascal case

    return replaceAll(string, ' ', '');
  } // Convert string to pascalCase

  function toCamelCase() {
    var input = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    var string = input.toString(); // Convert to pascal case

    string = toPascalCase(string); // Convert first character to lowercase

    return string.charAt(0).toLowerCase() + string.slice(1);
  } // Remove HTML from a string

  function stripHTML(source) {
    var fragment = document.createDocumentFragment();
    var element = document.createElement('div');
    fragment.appendChild(element);
    element.innerHTML = source;
    return fragment.firstChild.innerText;
  } // Like outerHTML, but also works for DocumentFragment

  function getHTML(element) {
    var wrapper = document.createElement('div');
    wrapper.appendChild(element);
    return wrapper.innerHTML;
  }

  var resources = {
    pip: 'PIP',
    airplay: 'AirPlay',
    html5: 'HTML5',
    vimeo: 'Vimeo',
    youtube: 'YouTube'
  };
  var i18n = {
    get: function get() {
      var key = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
      var config = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

      if (is$1.empty(key) || is$1.empty(config)) {
        return '';
      }

      var string = getDeep(config.i18n, key);

      if (is$1.empty(string)) {
        if (Object.keys(resources).includes(key)) {
          return resources[key];
        }

        return '';
      }

      var replace = {
        '{seektime}': config.seekTime,
        '{title}': config.title
      };
      Object.entries(replace).forEach(function (_ref) {
        var _ref2 = _slicedToArray(_ref, 2),
            k = _ref2[0],
            v = _ref2[1];

        string = replaceAll(string, k, v);
      });
      return string;
    }
  };

  var Storage =
  /*#__PURE__*/
  function () {
    function Storage(player) {
      _classCallCheck(this, Storage);

      this.enabled = player.config.storage.enabled;
      this.key = player.config.storage.key;
    } // Check for actual support (see if we can use it)


    _createClass(Storage, [{
      key: "get",
      value: function get(key) {
        if (!Storage.supported || !this.enabled) {
          return null;
        }

        var store = window.localStorage.getItem(this.key);

        if (is$1.empty(store)) {
          return null;
        }

        var json = JSON.parse(store);
        return is$1.string(key) && key.length ? json[key] : json;
      }
    }, {
      key: "set",
      value: function set(object) {
        // Bail if we don't have localStorage support or it's disabled
        if (!Storage.supported || !this.enabled) {
          return;
        } // Can only store objectst


        if (!is$1.object(object)) {
          return;
        } // Get current storage


        var storage = this.get(); // Default to empty object

        if (is$1.empty(storage)) {
          storage = {};
        } // Update the working copy of the values


        extend(storage, object); // Update storage

        window.localStorage.setItem(this.key, JSON.stringify(storage));
      }
    }], [{
      key: "supported",
      get: function get() {
        try {
          if (!('localStorage' in window)) {
            return false;
          }

          var test = '___test'; // Try to use it (it might be disabled, e.g. user is in private mode)
          // see: https://github.com/sampotts/plyr/issues/131

          window.localStorage.setItem(test, test);
          window.localStorage.removeItem(test);
          return true;
        } catch (e) {
          return false;
        }
      }
    }]);

    return Storage;
  }();

  // ==========================================================================
  // Fetch wrapper
  // Using XHR to avoid issues with older browsers
  // ==========================================================================
  function fetch(url) {
    var responseType = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'text';
    return new Promise(function (resolve, reject) {
      try {
        var request = new XMLHttpRequest(); // Check for CORS support

        if (!('withCredentials' in request)) {
          return;
        }

        request.addEventListener('load', function () {
          if (responseType === 'text') {
            try {
              resolve(JSON.parse(request.responseText));
            } catch (e) {
              resolve(request.responseText);
            }
          } else {
            resolve(request.response);
          }
        });
        request.addEventListener('error', function () {
          throw new Error(request.status);
        });
        request.open('GET', url, true); // Set the required response type

        request.responseType = responseType;
        request.send();
      } catch (e) {
        reject(e);
      }
    });
  }

  // ==========================================================================

  function loadSprite(url, id) {
    if (!is$1.string(url)) {
      return;
    }

    var prefix = 'cache';
    var hasId = is$1.string(id);
    var isCached = false;

    var exists = function exists() {
      return document.getElementById(id) !== null;
    };

    var update = function update(container, data) {
      // eslint-disable-next-line no-param-reassign
      container.innerHTML = data; // Check again incase of race condition

      if (hasId && exists()) {
        return;
      } // Inject the SVG to the body


      document.body.insertAdjacentElement('afterbegin', container);
    }; // Only load once if ID set


    if (!hasId || !exists()) {
      var useStorage = Storage.supported; // Create container

      var container = document.createElement('div');
      container.setAttribute('hidden', '');

      if (hasId) {
        container.setAttribute('id', id);
      } // Check in cache


      if (useStorage) {
        var cached = window.localStorage.getItem("".concat(prefix, "-").concat(id));
        isCached = cached !== null;

        if (isCached) {
          var data = JSON.parse(cached);
          update(container, data.content);
        }
      } // Get the sprite


      fetch(url).then(function (result) {
        if (is$1.empty(result)) {
          return;
        }

        if (useStorage) {
          window.localStorage.setItem("".concat(prefix, "-").concat(id), JSON.stringify({
            content: result
          }));
        }

        update(container, result);
      }).catch(function () {});
    }
  }

  // ==========================================================================

  var getHours = function getHours(value) {
    return Math.trunc(value / 60 / 60 % 60, 10);
  };
  var getMinutes = function getMinutes(value) {
    return Math.trunc(value / 60 % 60, 10);
  };
  var getSeconds = function getSeconds(value) {
    return Math.trunc(value % 60, 10);
  }; // Format time to UI friendly string

  function formatTime() {
    var time = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
    var displayHours = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
    var inverted = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;

    // Bail if the value isn't a number
    if (!is$1.number(time)) {
      return formatTime(null, displayHours, inverted);
    } // Format time component to add leading zero


    var format = function format(value) {
      return "0".concat(value).slice(-2);
    }; // Breakdown to hours, mins, secs


    var hours = getHours(time);
    var mins = getMinutes(time);
    var secs = getSeconds(time); // Do we need to display hours?

    if (displayHours || hours > 0) {
      hours = "".concat(hours, ":");
    } else {
      hours = '';
    } // Render


    return "".concat(inverted && time > 0 ? '-' : '').concat(hours).concat(format(mins), ":").concat(format(secs));
  }

  var controls = {
    // Get icon URL
    getIconUrl: function getIconUrl() {
      var url = new URL(this.config.iconUrl, window.location);
      var cors = url.host !== window.location.host || browser.isIE && !window.svg4everybody;
      return {
        url: this.config.iconUrl,
        cors: cors
      };
    },
    // Find the UI controls
    findElements: function findElements() {
      try {
        this.elements.controls = getElement.call(this, this.config.selectors.controls.wrapper); // Buttons

        this.elements.buttons = {
          play: getElements.call(this, this.config.selectors.buttons.play),
          pause: getElement.call(this, this.config.selectors.buttons.pause),
          restart: getElement.call(this, this.config.selectors.buttons.restart),
          rewind: getElement.call(this, this.config.selectors.buttons.rewind),
          fastForward: getElement.call(this, this.config.selectors.buttons.fastForward),
          mute: getElement.call(this, this.config.selectors.buttons.mute),
          pip: getElement.call(this, this.config.selectors.buttons.pip),
          airplay: getElement.call(this, this.config.selectors.buttons.airplay),
          settings: getElement.call(this, this.config.selectors.buttons.settings),
          captions: getElement.call(this, this.config.selectors.buttons.captions),
          fullscreen: getElement.call(this, this.config.selectors.buttons.fullscreen)
        }; // Progress

        this.elements.progress = getElement.call(this, this.config.selectors.progress); // Inputs

        this.elements.inputs = {
          seek: getElement.call(this, this.config.selectors.inputs.seek),
          volume: getElement.call(this, this.config.selectors.inputs.volume)
        }; // Display

        this.elements.display = {
          buffer: getElement.call(this, this.config.selectors.display.buffer),
          currentTime: getElement.call(this, this.config.selectors.display.currentTime),
          duration: getElement.call(this, this.config.selectors.display.duration)
        }; // Seek tooltip

        if (is$1.element(this.elements.progress)) {
          this.elements.display.seekTooltip = this.elements.progress.querySelector(".".concat(this.config.classNames.tooltip));
        }

        return true;
      } catch (error) {
        // Log it
        this.debug.warn('It looks like there is a problem with your custom controls HTML', error); // Restore native video controls

        this.toggleNativeControls(true);
        return false;
      }
    },
    // Create <svg> icon
    createIcon: function createIcon(type, attributes) {
      var namespace = 'http://www.w3.org/2000/svg';
      var iconUrl = controls.getIconUrl.call(this);
      var iconPath = "".concat(!iconUrl.cors ? iconUrl.url : '', "#").concat(this.config.iconPrefix); // Create <svg>

      var icon = document.createElementNS(namespace, 'svg');
      setAttributes(icon, extend(attributes, {
        role: 'presentation',
        focusable: 'false'
      })); // Create the <use> to reference sprite

      var use = document.createElementNS(namespace, 'use');
      var path = "".concat(iconPath, "-").concat(type); // Set `href` attributes
      // https://github.com/sampotts/plyr/issues/460
      // https://developer.mozilla.org/en-US/docs/Web/SVG/Attribute/xlink:href

      if ('href' in use) {
        use.setAttributeNS('http://www.w3.org/1999/xlink', 'href', path);
      } // Always set the older attribute even though it's "deprecated" (it'll be around for ages)


      use.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', path); // Add <use> to <svg>

      icon.appendChild(use);
      return icon;
    },
    // Create hidden text label
    createLabel: function createLabel(key) {
      var attr = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      var text = i18n.get(key, this.config);
      var attributes = Object.assign({}, attr, {
        class: [attr.class, this.config.classNames.hidden].filter(Boolean).join(' ')
      });
      return createElement('span', attributes, text);
    },
    // Create a badge
    createBadge: function createBadge(text) {
      if (is$1.empty(text)) {
        return null;
      }

      var badge = createElement('span', {
        class: this.config.classNames.menu.value
      });
      badge.appendChild(createElement('span', {
        class: this.config.classNames.menu.badge
      }, text));
      return badge;
    },
    // Create a <button>
    createButton: function createButton(buttonType, attr) {
      var _this = this;

      var attributes = extend({}, attr);
      var type = toCamelCase(buttonType);
      var props = {
        element: 'button',
        toggle: false,
        label: null,
        icon: null,
        labelPressed: null,
        iconPressed: null
      };
      ['element', 'icon', 'label'].forEach(function (key) {
        if (Object.keys(attributes).includes(key)) {
          props[key] = attributes[key];
          delete attributes[key];
        }
      }); // Default to 'button' type to prevent form submission

      if (props.element === 'button' && !Object.keys(attributes).includes('type')) {
        attributes.type = 'button';
      } // Set class name


      if (Object.keys(attributes).includes('class')) {
        if (!attributes.class.split(' ').some(function (c) {
          return c === _this.config.classNames.control;
        })) {
          extend(attributes, {
            class: "".concat(attributes.class, " ").concat(this.config.classNames.control)
          });
        }
      } else {
        attributes.class = this.config.classNames.control;
      } // Large play button


      switch (buttonType) {
        case 'play':
          props.toggle = true;
          props.label = 'play';
          props.labelPressed = 'pause';
          props.icon = 'play';
          props.iconPressed = 'pause';
          break;

        case 'mute':
          props.toggle = true;
          props.label = 'mute';
          props.labelPressed = 'unmute';
          props.icon = 'volume';
          props.iconPressed = 'muted';
          break;

        case 'captions':
          props.toggle = true;
          props.label = 'enableCaptions';
          props.labelPressed = 'disableCaptions';
          props.icon = 'captions-off';
          props.iconPressed = 'captions-on';
          break;

        case 'fullscreen':
          props.toggle = true;
          props.label = 'enterFullscreen';
          props.labelPressed = 'exitFullscreen';
          props.icon = 'enter-fullscreen';
          props.iconPressed = 'exit-fullscreen';
          break;

        case 'play-large':
          attributes.class += " ".concat(this.config.classNames.control, "--overlaid");
          type = 'play';
          props.label = 'play';
          props.icon = 'play';
          break;

        default:
          if (is$1.empty(props.label)) {
            props.label = type;
          }

          if (is$1.empty(props.icon)) {
            props.icon = buttonType;
          }

      }

      var button = createElement(props.element); // Setup toggle icon and labels

      if (props.toggle) {
        // Icon
        button.appendChild(controls.createIcon.call(this, props.iconPressed, {
          class: 'icon--pressed'
        }));
        button.appendChild(controls.createIcon.call(this, props.icon, {
          class: 'icon--not-pressed'
        })); // Label/Tooltip

        button.appendChild(controls.createLabel.call(this, props.labelPressed, {
          class: 'label--pressed'
        }));
        button.appendChild(controls.createLabel.call(this, props.label, {
          class: 'label--not-pressed'
        }));
      } else {
        button.appendChild(controls.createIcon.call(this, props.icon));
        button.appendChild(controls.createLabel.call(this, props.label));
      } // Merge and set attributes


      extend(attributes, getAttributesFromSelector(this.config.selectors.buttons[type], attributes));
      setAttributes(button, attributes); // We have multiple play buttons

      if (type === 'play') {
        if (!is$1.array(this.elements.buttons[type])) {
          this.elements.buttons[type] = [];
        }

        this.elements.buttons[type].push(button);
      } else {
        this.elements.buttons[type] = button;
      }

      return button;
    },
    // Create an <input type='range'>
    createRange: function createRange(type, attributes) {
      // Seek input
      var input = createElement('input', extend(getAttributesFromSelector(this.config.selectors.inputs[type]), {
        type: 'range',
        min: 0,
        max: 100,
        step: 0.01,
        value: 0,
        autocomplete: 'off',
        // A11y fixes for https://github.com/sampotts/plyr/issues/905
        role: 'slider',
        'aria-label': i18n.get(type, this.config),
        'aria-valuemin': 0,
        'aria-valuemax': 100,
        'aria-valuenow': 0
      }, attributes));
      this.elements.inputs[type] = input; // Set the fill for webkit now

      controls.updateRangeFill.call(this, input); // Improve support on touch devices

      RangeTouch.setup(input);
      return input;
    },
    // Create a <progress>
    createProgress: function createProgress(type, attributes) {
      var progress = createElement('progress', extend(getAttributesFromSelector(this.config.selectors.display[type]), {
        min: 0,
        max: 100,
        value: 0,
        role: 'progressbar',
        'aria-hidden': true
      }, attributes)); // Create the label inside

      if (type !== 'volume') {
        progress.appendChild(createElement('span', null, '0'));
        var suffixKey = {
          played: 'played',
          buffer: 'buffered'
        }[type];
        var suffix = suffixKey ? i18n.get(suffixKey, this.config) : '';
        progress.innerText = "% ".concat(suffix.toLowerCase());
      }

      this.elements.display[type] = progress;
      return progress;
    },
    // Create time display
    createTime: function createTime(type, attrs) {
      var attributes = getAttributesFromSelector(this.config.selectors.display[type], attrs);
      var container = createElement('div', extend(attributes, {
        class: "".concat(attributes.class ? attributes.class : '', " ").concat(this.config.classNames.display.time, " ").trim(),
        'aria-label': i18n.get(type, this.config)
      }), '00:00'); // Reference for updates

      this.elements.display[type] = container;
      return container;
    },
    // Bind keyboard shortcuts for a menu item
    // We have to bind to keyup otherwise Firefox triggers a click when a keydown event handler shifts focus
    // https://bugzilla.mozilla.org/show_bug.cgi?id=1220143
    bindMenuItemShortcuts: function bindMenuItemShortcuts(menuItem, type) {
      var _this2 = this;

      // Navigate through menus via arrow keys and space
      on(menuItem, 'keydown keyup', function (event) {
        // We only care about space and ⬆️ ⬇️️ ➡️
        if (![32, 38, 39, 40].includes(event.which)) {
          return;
        } // Prevent play / seek


        event.preventDefault();
        event.stopPropagation(); // We're just here to prevent the keydown bubbling

        if (event.type === 'keydown') {
          return;
        }

        var isRadioButton = matches$1(menuItem, '[role="menuitemradio"]'); // Show the respective menu

        if (!isRadioButton && [32, 39].includes(event.which)) {
          controls.showMenuPanel.call(_this2, type, true);
        } else {
          var target;

          if (event.which !== 32) {
            if (event.which === 40 || isRadioButton && event.which === 39) {
              target = menuItem.nextElementSibling;

              if (!is$1.element(target)) {
                target = menuItem.parentNode.firstElementChild;
              }
            } else {
              target = menuItem.previousElementSibling;

              if (!is$1.element(target)) {
                target = menuItem.parentNode.lastElementChild;
              }
            }

            setFocus.call(_this2, target, true);
          }
        }
      }, false); // Enter will fire a `click` event but we still need to manage focus
      // So we bind to keyup which fires after and set focus here

      on(menuItem, 'keyup', function (event) {
        if (event.which !== 13) {
          return;
        }

        controls.focusFirstMenuItem.call(_this2, null, true);
      });
    },
    // Create a settings menu item
    createMenuItem: function createMenuItem(_ref) {
      var _this3 = this;

      var value = _ref.value,
          list = _ref.list,
          type = _ref.type,
          title = _ref.title,
          _ref$badge = _ref.badge,
          badge = _ref$badge === void 0 ? null : _ref$badge,
          _ref$checked = _ref.checked,
          checked = _ref$checked === void 0 ? false : _ref$checked;
      var attributes = getAttributesFromSelector(this.config.selectors.inputs[type]);
      var menuItem = createElement('button', extend(attributes, {
        type: 'button',
        role: 'menuitemradio',
        class: "".concat(this.config.classNames.control, " ").concat(attributes.class ? attributes.class : '').trim(),
        'aria-checked': checked,
        value: value
      }));
      var flex = createElement('span'); // We have to set as HTML incase of special characters

      flex.innerHTML = title;

      if (is$1.element(badge)) {
        flex.appendChild(badge);
      }

      menuItem.appendChild(flex); // Replicate radio button behaviour

      Object.defineProperty(menuItem, 'checked', {
        enumerable: true,
        get: function get() {
          return menuItem.getAttribute('aria-checked') === 'true';
        },
        set: function set(check) {
          // Ensure exclusivity
          if (check) {
            Array.from(menuItem.parentNode.children).filter(function (node) {
              return matches$1(node, '[role="menuitemradio"]');
            }).forEach(function (node) {
              return node.setAttribute('aria-checked', 'false');
            });
          }

          menuItem.setAttribute('aria-checked', check ? 'true' : 'false');
        }
      });
      this.listeners.bind(menuItem, 'click keyup', function (event) {
        if (is$1.keyboardEvent(event) && event.which !== 32) {
          return;
        }

        event.preventDefault();
        event.stopPropagation();
        menuItem.checked = true;

        switch (type) {
          case 'language':
            _this3.currentTrack = Number(value);
            break;

          case 'quality':
            _this3.quality = value;
            break;

          case 'speed':
            _this3.speed = parseFloat(value);
            break;

          default:
            break;
        }

        controls.showMenuPanel.call(_this3, 'home', is$1.keyboardEvent(event));
      }, type, false);
      controls.bindMenuItemShortcuts.call(this, menuItem, type);
      list.appendChild(menuItem);
    },
    // Format a time for display
    formatTime: function formatTime$1() {
      var time = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
      var inverted = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

      // Bail if the value isn't a number
      if (!is$1.number(time)) {
        return time;
      } // Always display hours if duration is over an hour


      var forceHours = getHours(this.duration) > 0;
      return formatTime(time, forceHours, inverted);
    },
    // Update the displayed time
    updateTimeDisplay: function updateTimeDisplay() {
      var target = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
      var time = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
      var inverted = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;

      // Bail if there's no element to display or the value isn't a number
      if (!is$1.element(target) || !is$1.number(time)) {
        return;
      } // eslint-disable-next-line no-param-reassign


      target.innerText = controls.formatTime(time, inverted);
    },
    // Update volume UI and storage
    updateVolume: function updateVolume() {
      if (!this.supported.ui) {
        return;
      } // Update range


      if (is$1.element(this.elements.inputs.volume)) {
        controls.setRange.call(this, this.elements.inputs.volume, this.muted ? 0 : this.volume);
      } // Update mute state


      if (is$1.element(this.elements.buttons.mute)) {
        this.elements.buttons.mute.pressed = this.muted || this.volume === 0;
      }
    },
    // Update seek value and lower fill
    setRange: function setRange(target) {
      var value = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;

      if (!is$1.element(target)) {
        return;
      } // eslint-disable-next-line


      target.value = value; // Webkit range fill

      controls.updateRangeFill.call(this, target);
    },
    // Update <progress> elements
    updateProgress: function updateProgress(event) {
      var _this4 = this;

      if (!this.supported.ui || !is$1.event(event)) {
        return;
      }

      var value = 0;

      var setProgress = function setProgress(target, input) {
        var val = is$1.number(input) ? input : 0;
        var progress = is$1.element(target) ? target : _this4.elements.display.buffer; // Update value and label

        if (is$1.element(progress)) {
          progress.value = val; // Update text label inside

          var label = progress.getElementsByTagName('span')[0];

          if (is$1.element(label)) {
            label.childNodes[0].nodeValue = val;
          }
        }
      };

      if (event) {
        switch (event.type) {
          // Video playing
          case 'timeupdate':
          case 'seeking':
          case 'seeked':
            value = getPercentage(this.currentTime, this.duration); // Set seek range value only if it's a 'natural' time event

            if (event.type === 'timeupdate') {
              controls.setRange.call(this, this.elements.inputs.seek, value);
            }

            break;
          // Check buffer status

          case 'playing':
          case 'progress':
            setProgress(this.elements.display.buffer, this.buffered * 100);
            break;

          default:
            break;
        }
      }
    },
    // Webkit polyfill for lower fill range
    updateRangeFill: function updateRangeFill(target) {
      // Get range from event if event passed
      var range = is$1.event(target) ? target.target : target; // Needs to be a valid <input type='range'>

      if (!is$1.element(range) || range.getAttribute('type') !== 'range') {
        return;
      } // Set aria values for https://github.com/sampotts/plyr/issues/905


      if (matches$1(range, this.config.selectors.inputs.seek)) {
        range.setAttribute('aria-valuenow', this.currentTime);
        var currentTime = controls.formatTime(this.currentTime);
        var duration = controls.formatTime(this.duration);
        var format = i18n.get('seekLabel', this.config);
        range.setAttribute('aria-valuetext', format.replace('{currentTime}', currentTime).replace('{duration}', duration));
      } else if (matches$1(range, this.config.selectors.inputs.volume)) {
        var percent = range.value * 100;
        range.setAttribute('aria-valuenow', percent);
        range.setAttribute('aria-valuetext', "".concat(percent.toFixed(1), "%"));
      } else {
        range.setAttribute('aria-valuenow', range.value);
      } // WebKit only


      if (!browser.isWebkit) {
        return;
      } // Set CSS custom property


      range.style.setProperty('--value', "".concat(range.value / range.max * 100, "%"));
    },
    // Update hover tooltip for seeking
    updateSeekTooltip: function updateSeekTooltip(event) {
      var _this5 = this;

      // Bail if setting not true
      if (!this.config.tooltips.seek || !is$1.element(this.elements.inputs.seek) || !is$1.element(this.elements.display.seekTooltip) || this.duration === 0) {
        return;
      }

      var visible = "".concat(this.config.classNames.tooltip, "--visible");

      var toggle = function toggle(show) {
        return toggleClass(_this5.elements.display.seekTooltip, visible, show);
      }; // Hide on touch


      if (this.touch) {
        toggle(false);
        return;
      } // Determine percentage, if already visible


      var percent = 0;
      var clientRect = this.elements.progress.getBoundingClientRect();

      if (is$1.event(event)) {
        percent = 100 / clientRect.width * (event.pageX - clientRect.left);
      } else if (hasClass(this.elements.display.seekTooltip, visible)) {
        percent = parseFloat(this.elements.display.seekTooltip.style.left, 10);
      } else {
        return;
      } // Set bounds


      if (percent < 0) {
        percent = 0;
      } else if (percent > 100) {
        percent = 100;
      } // Display the time a click would seek to


      controls.updateTimeDisplay.call(this, this.elements.display.seekTooltip, this.duration / 100 * percent); // Set position

      this.elements.display.seekTooltip.style.left = "".concat(percent, "%"); // Show/hide the tooltip
      // If the event is a moues in/out and percentage is inside bounds

      if (is$1.event(event) && ['mouseenter', 'mouseleave'].includes(event.type)) {
        toggle(event.type === 'mouseenter');
      }
    },
    // Handle time change event
    timeUpdate: function timeUpdate(event) {
      // Only invert if only one time element is displayed and used for both duration and currentTime
      var invert = !is$1.element(this.elements.display.duration) && this.config.invertTime; // Duration

      controls.updateTimeDisplay.call(this, this.elements.display.currentTime, invert ? this.duration - this.currentTime : this.currentTime, invert); // Ignore updates while seeking

      if (event && event.type === 'timeupdate' && this.media.seeking) {
        return;
      } // Playing progress


      controls.updateProgress.call(this, event);
    },
    // Show the duration on metadataloaded or durationchange events
    durationUpdate: function durationUpdate() {
      // Bail if no UI or durationchange event triggered after playing/seek when invertTime is false
      if (!this.supported.ui || !this.config.invertTime && this.currentTime) {
        return;
      } // If duration is the 2**32 (shaka), Infinity (HLS), DASH-IF (Number.MAX_SAFE_INTEGER || Number.MAX_VALUE) indicating live we hide the currentTime and progressbar.
      // https://github.com/video-dev/hls.js/blob/5820d29d3c4c8a46e8b75f1e3afa3e68c1a9a2db/src/controller/buffer-controller.js#L415
      // https://github.com/google/shaka-player/blob/4d889054631f4e1cf0fbd80ddd2b71887c02e232/lib/media/streaming_engine.js#L1062
      // https://github.com/Dash-Industry-Forum/dash.js/blob/69859f51b969645b234666800d4cb596d89c602d/src/dash/models/DashManifestModel.js#L338


      if (this.duration >= Math.pow(2, 32)) {
        toggleHidden(this.elements.display.currentTime, true);
        toggleHidden(this.elements.progress, true);
        return;
      } // Update ARIA values


      if (is$1.element(this.elements.inputs.seek)) {
        this.elements.inputs.seek.setAttribute('aria-valuemax', this.duration);
      } // If there's a spot to display duration


      var hasDuration = is$1.element(this.elements.display.duration); // If there's only one time display, display duration there

      if (!hasDuration && this.config.displayDuration && this.paused) {
        controls.updateTimeDisplay.call(this, this.elements.display.currentTime, this.duration);
      } // If there's a duration element, update content


      if (hasDuration) {
        controls.updateTimeDisplay.call(this, this.elements.display.duration, this.duration);
      } // Update the tooltip (if visible)


      controls.updateSeekTooltip.call(this);
    },
    // Hide/show a tab
    toggleMenuButton: function toggleMenuButton(setting, toggle) {
      toggleHidden(this.elements.settings.buttons[setting], !toggle);
    },
    // Update the selected setting
    updateSetting: function updateSetting(setting, container, input) {
      var pane = this.elements.settings.panels[setting];
      var value = null;
      var list = container;

      if (setting === 'captions') {
        value = this.currentTrack;
      } else {
        value = !is$1.empty(input) ? input : this[setting]; // Get default

        if (is$1.empty(value)) {
          value = this.config[setting].default;
        } // Unsupported value


        if (!is$1.empty(this.options[setting]) && !this.options[setting].includes(value)) {
          this.debug.warn("Unsupported value of '".concat(value, "' for ").concat(setting));
          return;
        } // Disabled value


        if (!this.config[setting].options.includes(value)) {
          this.debug.warn("Disabled value of '".concat(value, "' for ").concat(setting));
          return;
        }
      } // Get the list if we need to


      if (!is$1.element(list)) {
        list = pane && pane.querySelector('[role="menu"]');
      } // If there's no list it means it's not been rendered...


      if (!is$1.element(list)) {
        return;
      } // Update the label


      var label = this.elements.settings.buttons[setting].querySelector(".".concat(this.config.classNames.menu.value));
      label.innerHTML = controls.getLabel.call(this, setting, value); // Find the radio option and check it

      var target = list && list.querySelector("[value=\"".concat(value, "\"]"));

      if (is$1.element(target)) {
        target.checked = true;
      }
    },
    // Translate a value into a nice label
    getLabel: function getLabel(setting, value) {
      switch (setting) {
        case 'speed':
          return value === 1 ? i18n.get('normal', this.config) : "".concat(value, "&times;");

        case 'quality':
          if (is$1.number(value)) {
            var label = i18n.get("qualityLabel.".concat(value), this.config);

            if (!label.length) {
              return "".concat(value, "p");
            }

            return label;
          }

          return toTitleCase(value);

        case 'captions':
          return captions.getLabel.call(this);

        default:
          return null;
      }
    },
    // Set the quality menu
    setQualityMenu: function setQualityMenu(options) {
      var _this6 = this;

      // Menu required
      if (!is$1.element(this.elements.settings.panels.quality)) {
        return;
      }

      var type = 'quality';
      var list = this.elements.settings.panels.quality.querySelector('[role="menu"]'); // Set options if passed and filter based on uniqueness and config

      if (is$1.array(options)) {
        this.options.quality = dedupe(options).filter(function (quality) {
          return _this6.config.quality.options.includes(quality);
        });
      } // Toggle the pane and tab


      var toggle = !is$1.empty(this.options.quality) && this.options.quality.length > 1;
      controls.toggleMenuButton.call(this, type, toggle); // Empty the menu

      emptyElement(list); // Check if we need to toggle the parent

      controls.checkMenu.call(this); // If we're hiding, nothing more to do

      if (!toggle) {
        return;
      } // Get the badge HTML for HD, 4K etc


      var getBadge = function getBadge(quality) {
        var label = i18n.get("qualityBadge.".concat(quality), _this6.config);

        if (!label.length) {
          return null;
        }

        return controls.createBadge.call(_this6, label);
      }; // Sort options by the config and then render options


      this.options.quality.sort(function (a, b) {
        var sorting = _this6.config.quality.options;
        return sorting.indexOf(a) > sorting.indexOf(b) ? 1 : -1;
      }).forEach(function (quality) {
        controls.createMenuItem.call(_this6, {
          value: quality,
          list: list,
          type: type,
          title: controls.getLabel.call(_this6, 'quality', quality),
          badge: getBadge(quality)
        });
      });
      controls.updateSetting.call(this, type, list);
    },
    // Set the looping options

    /* setLoopMenu() {
        // Menu required
        if (!is.element(this.elements.settings.panels.loop)) {
            return;
        }
         const options = ['start', 'end', 'all', 'reset'];
        const list = this.elements.settings.panels.loop.querySelector('[role="menu"]');
         // Show the pane and tab
        toggleHidden(this.elements.settings.buttons.loop, false);
        toggleHidden(this.elements.settings.panels.loop, false);
         // Toggle the pane and tab
        const toggle = !is.empty(this.loop.options);
        controls.toggleMenuButton.call(this, 'loop', toggle);
         // Empty the menu
        emptyElement(list);
         options.forEach(option => {
            const item = createElement('li');
             const button = createElement(
                'button',
                extend(getAttributesFromSelector(this.config.selectors.buttons.loop), {
                    type: 'button',
                    class: this.config.classNames.control,
                    'data-plyr-loop-action': option,
                }),
                i18n.get(option, this.config)
            );
             if (['start', 'end'].includes(option)) {
                const badge = controls.createBadge.call(this, '00:00');
                button.appendChild(badge);
            }
             item.appendChild(button);
            list.appendChild(item);
        });
    }, */
    // Get current selected caption language
    // TODO: rework this to user the getter in the API?
    // Set a list of available captions languages
    setCaptionsMenu: function setCaptionsMenu() {
      var _this7 = this;

      // Menu required
      if (!is$1.element(this.elements.settings.panels.captions)) {
        return;
      } // TODO: Captions or language? Currently it's mixed


      var type = 'captions';
      var list = this.elements.settings.panels.captions.querySelector('[role="menu"]');
      var tracks = captions.getTracks.call(this);
      var toggle = Boolean(tracks.length); // Toggle the pane and tab

      controls.toggleMenuButton.call(this, type, toggle); // Empty the menu

      emptyElement(list); // Check if we need to toggle the parent

      controls.checkMenu.call(this); // If there's no captions, bail

      if (!toggle) {
        return;
      } // Generate options data


      var options = tracks.map(function (track, value) {
        return {
          value: value,
          checked: _this7.captions.toggled && _this7.currentTrack === value,
          title: captions.getLabel.call(_this7, track),
          badge: track.language && controls.createBadge.call(_this7, track.language.toUpperCase()),
          list: list,
          type: 'language'
        };
      }); // Add the "Disabled" option to turn off captions

      options.unshift({
        value: -1,
        checked: !this.captions.toggled,
        title: i18n.get('disabled', this.config),
        list: list,
        type: 'language'
      }); // Generate options

      options.forEach(controls.createMenuItem.bind(this));
      controls.updateSetting.call(this, type, list);
    },
    // Set a list of available captions languages
    setSpeedMenu: function setSpeedMenu(options) {
      var _this8 = this;

      // Menu required
      if (!is$1.element(this.elements.settings.panels.speed)) {
        return;
      }

      var type = 'speed';
      var list = this.elements.settings.panels.speed.querySelector('[role="menu"]'); // Set the speed options

      if (is$1.array(options)) {
        this.options.speed = options;
      } else if (this.isHTML5 || this.isVimeo) {
        this.options.speed = [0.5, 0.75, 1, 1.25, 1.5, 1.75, 2];
      } // Set options if passed and filter based on config


      this.options.speed = this.options.speed.filter(function (speed) {
        return _this8.config.speed.options.includes(speed);
      }); // Toggle the pane and tab

      var toggle = !is$1.empty(this.options.speed) && this.options.speed.length > 1;
      controls.toggleMenuButton.call(this, type, toggle); // Empty the menu

      emptyElement(list); // Check if we need to toggle the parent

      controls.checkMenu.call(this); // If we're hiding, nothing more to do

      if (!toggle) {
        return;
      } // Create items


      this.options.speed.forEach(function (speed) {
        controls.createMenuItem.call(_this8, {
          value: speed,
          list: list,
          type: type,
          title: controls.getLabel.call(_this8, 'speed', speed)
        });
      });
      controls.updateSetting.call(this, type, list);
    },
    // Check if we need to hide/show the settings menu
    checkMenu: function checkMenu() {
      var buttons = this.elements.settings.buttons;
      var visible = !is$1.empty(buttons) && Object.values(buttons).some(function (button) {
        return !button.hidden;
      });
      toggleHidden(this.elements.settings.menu, !visible);
    },
    // Focus the first menu item in a given (or visible) menu
    focusFirstMenuItem: function focusFirstMenuItem(pane) {
      var tabFocus = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

      if (this.elements.settings.popup.hidden) {
        return;
      }

      var target = pane;

      if (!is$1.element(target)) {
        target = Object.values(this.elements.settings.panels).find(function (p) {
          return !p.hidden;
        });
      }

      var firstItem = target.querySelector('[role^="menuitem"]');
      setFocus.call(this, firstItem, tabFocus);
    },
    // Show/hide menu
    toggleMenu: function toggleMenu(input) {
      var popup = this.elements.settings.popup;
      var button = this.elements.buttons.settings; // Menu and button are required

      if (!is$1.element(popup) || !is$1.element(button)) {
        return;
      } // True toggle by default


      var hidden = popup.hidden;
      var show = hidden;

      if (is$1.boolean(input)) {
        show = input;
      } else if (is$1.keyboardEvent(input) && input.which === 27) {
        show = false;
      } else if (is$1.event(input)) {
        // If Plyr is in a shadowDOM, the event target is set to the component, instead of the
        // Element in the shadowDOM. The path, if available, is complete.
        var target = is$1.function(input.composedPath) ? input.composedPath()[0] : input.target;
        var isMenuItem = popup.contains(target); // If the click was inside the menu or if the click
        // wasn't the button or menu item and we're trying to
        // show the menu (a doc click shouldn't show the menu)

        if (isMenuItem || !isMenuItem && input.target !== button && show) {
          return;
        }
      } // Set button attributes


      button.setAttribute('aria-expanded', show); // Show the actual popup

      toggleHidden(popup, !show); // Add class hook

      toggleClass(this.elements.container, this.config.classNames.menu.open, show); // Focus the first item if key interaction

      if (show && is$1.keyboardEvent(input)) {
        controls.focusFirstMenuItem.call(this, null, true);
      } else if (!show && !hidden) {
        // If closing, re-focus the button
        setFocus.call(this, button, is$1.keyboardEvent(input));
      }
    },
    // Get the natural size of a menu panel
    getMenuSize: function getMenuSize(tab) {
      var clone = tab.cloneNode(true);
      clone.style.position = 'absolute';
      clone.style.opacity = 0;
      clone.removeAttribute('hidden'); // Append to parent so we get the "real" size

      tab.parentNode.appendChild(clone); // Get the sizes before we remove

      var width = clone.scrollWidth;
      var height = clone.scrollHeight; // Remove from the DOM

      removeElement(clone);
      return {
        width: width,
        height: height
      };
    },
    // Show a panel in the menu
    showMenuPanel: function showMenuPanel() {
      var _this9 = this;

      var type = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
      var tabFocus = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var target = this.elements.container.querySelector("#plyr-settings-".concat(this.id, "-").concat(type)); // Nothing to show, bail

      if (!is$1.element(target)) {
        return;
      } // Hide all other panels


      var container = target.parentNode;
      var current = Array.from(container.children).find(function (node) {
        return !node.hidden;
      }); // If we can do fancy animations, we'll animate the height/width

      if (support.transitions && !support.reducedMotion) {
        // Set the current width as a base
        container.style.width = "".concat(current.scrollWidth, "px");
        container.style.height = "".concat(current.scrollHeight, "px"); // Get potential sizes

        var size = controls.getMenuSize.call(this, target); // Restore auto height/width

        var restore = function restore(event) {
          // We're only bothered about height and width on the container
          if (event.target !== container || !['width', 'height'].includes(event.propertyName)) {
            return;
          } // Revert back to auto


          container.style.width = '';
          container.style.height = ''; // Only listen once

          off.call(_this9, container, transitionEndEvent, restore);
        }; // Listen for the transition finishing and restore auto height/width


        on.call(this, container, transitionEndEvent, restore); // Set dimensions to target

        container.style.width = "".concat(size.width, "px");
        container.style.height = "".concat(size.height, "px");
      } // Set attributes on current tab


      toggleHidden(current, true); // Set attributes on target

      toggleHidden(target, false); // Focus the first item

      controls.focusFirstMenuItem.call(this, target, tabFocus);
    },
    // Set the download URL
    setDownloadUrl: function setDownloadUrl() {
      var button = this.elements.buttons.download; // Bail if no button

      if (!is$1.element(button)) {
        return;
      } // Set attribute


      button.setAttribute('href', this.download);
    },
    // Build the default HTML
    create: function create(data) {
      var _this10 = this;

      var bindMenuItemShortcuts = controls.bindMenuItemShortcuts,
          createButton = controls.createButton,
          createProgress = controls.createProgress,
          createRange = controls.createRange,
          createTime = controls.createTime,
          setQualityMenu = controls.setQualityMenu,
          setSpeedMenu = controls.setSpeedMenu,
          showMenuPanel = controls.showMenuPanel;
      this.elements.controls = null; // Larger overlaid play button

      if (this.config.controls.includes('play-large')) {
        this.elements.container.appendChild(createButton.call(this, 'play-large'));
      } // Create the container


      var container = createElement('div', getAttributesFromSelector(this.config.selectors.controls.wrapper));
      this.elements.controls = container; // Default item attributes

      var defaultAttributes = {
        class: 'plyr__controls__item'
      }; // Loop through controls in order

      dedupe(this.config.controls).forEach(function (control) {
        // Restart button
        if (control === 'restart') {
          container.appendChild(createButton.call(_this10, 'restart', defaultAttributes));
        } // Rewind button


        if (control === 'rewind') {
          container.appendChild(createButton.call(_this10, 'rewind', defaultAttributes));
        } // Play/Pause button


        if (control === 'play') {
          container.appendChild(createButton.call(_this10, 'play', defaultAttributes));
        } // Fast forward button


        if (control === 'fast-forward') {
          container.appendChild(createButton.call(_this10, 'fast-forward', defaultAttributes));
        } // Progress


        if (control === 'progress') {
          var progressContainer = createElement('div', {
            class: "".concat(defaultAttributes.class, " plyr__progress__container")
          });
          var progress = createElement('div', getAttributesFromSelector(_this10.config.selectors.progress)); // Seek range slider

          progress.appendChild(createRange.call(_this10, 'seek', {
            id: "plyr-seek-".concat(data.id)
          })); // Buffer progress

          progress.appendChild(createProgress.call(_this10, 'buffer')); // TODO: Add loop display indicator
          // Seek tooltip

          if (_this10.config.tooltips.seek) {
            var tooltip = createElement('span', {
              class: _this10.config.classNames.tooltip
            }, '00:00');
            progress.appendChild(tooltip);
            _this10.elements.display.seekTooltip = tooltip;
          }

          _this10.elements.progress = progress;
          progressContainer.appendChild(_this10.elements.progress);
          container.appendChild(progressContainer);
        } // Media current time display


        if (control === 'current-time') {
          container.appendChild(createTime.call(_this10, 'currentTime', defaultAttributes));
        } // Media duration display


        if (control === 'duration') {
          container.appendChild(createTime.call(_this10, 'duration', defaultAttributes));
        } // Volume controls


        if (control === 'mute' || control === 'volume') {
          var volume = _this10.elements.volume; // Create the volume container if needed

          if (!is$1.element(volume) || !container.contains(volume)) {
            volume = createElement('div', extend({}, defaultAttributes, {
              class: "".concat(defaultAttributes.class, " plyr__volume").trim()
            }));
            _this10.elements.volume = volume;
            container.appendChild(volume);
          } // Toggle mute button


          if (control === 'mute') {
            volume.appendChild(createButton.call(_this10, 'mute'));
          } // Volume range control


          if (control === 'volume') {
            // Set the attributes
            var attributes = {
              max: 1,
              step: 0.05,
              value: _this10.config.volume
            }; // Create the volume range slider

            volume.appendChild(createRange.call(_this10, 'volume', extend(attributes, {
              id: "plyr-volume-".concat(data.id)
            })));
          }
        } // Toggle captions button


        if (control === 'captions') {
          container.appendChild(createButton.call(_this10, 'captions', defaultAttributes));
        } // Settings button / menu


        if (control === 'settings' && !is$1.empty(_this10.config.settings)) {
          var wrapper = createElement('div', extend({}, defaultAttributes, {
            class: "".concat(defaultAttributes.class, " plyr__menu").trim(),
            hidden: ''
          }));
          wrapper.appendChild(createButton.call(_this10, 'settings', {
            'aria-haspopup': true,
            'aria-controls': "plyr-settings-".concat(data.id),
            'aria-expanded': false
          }));
          var popup = createElement('div', {
            class: 'plyr__menu__container',
            id: "plyr-settings-".concat(data.id),
            hidden: ''
          });
          var inner = createElement('div');
          var home = createElement('div', {
            id: "plyr-settings-".concat(data.id, "-home")
          }); // Create the menu

          var menu = createElement('div', {
            role: 'menu'
          });
          home.appendChild(menu);
          inner.appendChild(home);
          _this10.elements.settings.panels.home = home; // Build the menu items

          _this10.config.settings.forEach(function (type) {
            // TODO: bundle this with the createMenuItem helper and bindings
            var menuItem = createElement('button', extend(getAttributesFromSelector(_this10.config.selectors.buttons.settings), {
              type: 'button',
              class: "".concat(_this10.config.classNames.control, " ").concat(_this10.config.classNames.control, "--forward"),
              role: 'menuitem',
              'aria-haspopup': true,
              hidden: ''
            })); // Bind menu shortcuts for keyboard users

            bindMenuItemShortcuts.call(_this10, menuItem, type); // Show menu on click

            on(menuItem, 'click', function () {
              showMenuPanel.call(_this10, type, false);
            });
            var flex = createElement('span', null, i18n.get(type, _this10.config));
            var value = createElement('span', {
              class: _this10.config.classNames.menu.value
            }); // Speed contains HTML entities

            value.innerHTML = data[type];
            flex.appendChild(value);
            menuItem.appendChild(flex);
            menu.appendChild(menuItem); // Build the panes

            var pane = createElement('div', {
              id: "plyr-settings-".concat(data.id, "-").concat(type),
              hidden: ''
            }); // Back button

            var backButton = createElement('button', {
              type: 'button',
              class: "".concat(_this10.config.classNames.control, " ").concat(_this10.config.classNames.control, "--back")
            }); // Visible label

            backButton.appendChild(createElement('span', {
              'aria-hidden': true
            }, i18n.get(type, _this10.config))); // Screen reader label

            backButton.appendChild(createElement('span', {
              class: _this10.config.classNames.hidden
            }, i18n.get('menuBack', _this10.config))); // Go back via keyboard

            on(pane, 'keydown', function (event) {
              // We only care about <-
              if (event.which !== 37) {
                return;
              } // Prevent seek


              event.preventDefault();
              event.stopPropagation(); // Show the respective menu

              showMenuPanel.call(_this10, 'home', true);
            }, false); // Go back via button click

            on(backButton, 'click', function () {
              showMenuPanel.call(_this10, 'home', false);
            }); // Add to pane

            pane.appendChild(backButton); // Menu

            pane.appendChild(createElement('div', {
              role: 'menu'
            }));
            inner.appendChild(pane);
            _this10.elements.settings.buttons[type] = menuItem;
            _this10.elements.settings.panels[type] = pane;
          });

          popup.appendChild(inner);
          wrapper.appendChild(popup);
          container.appendChild(wrapper);
          _this10.elements.settings.popup = popup;
          _this10.elements.settings.menu = wrapper;
        } // Picture in picture button


        if (control === 'pip' && support.pip) {
          container.appendChild(createButton.call(_this10, 'pip', defaultAttributes));
        } // Airplay button


        if (control === 'airplay' && support.airplay) {
          container.appendChild(createButton.call(_this10, 'airplay', defaultAttributes));
        } // Download button


        if (control === 'download') {
          var _attributes = extend({}, defaultAttributes, {
            element: 'a',
            href: _this10.download,
            target: '_blank'
          });

          var download = _this10.config.urls.download;

          if (!is$1.url(download) && _this10.isEmbed) {
            extend(_attributes, {
              icon: "logo-".concat(_this10.provider),
              label: _this10.provider
            });
          }

          container.appendChild(createButton.call(_this10, 'download', _attributes));
        } // Toggle fullscreen button


        if (control === 'fullscreen') {
          container.appendChild(createButton.call(_this10, 'fullscreen', defaultAttributes));
        }
      }); // Set available quality levels

      if (this.isHTML5) {
        setQualityMenu.call(this, html5.getQualityOptions.call(this));
      }

      setSpeedMenu.call(this);
      return container;
    },
    // Insert controls
    inject: function inject() {
      var _this11 = this;

      // Sprite
      if (this.config.loadSprite) {
        var icon = controls.getIconUrl.call(this); // Only load external sprite using AJAX

        if (icon.cors) {
          loadSprite(icon.url, 'sprite-plyr');
        }
      } // Create a unique ID


      this.id = Math.floor(Math.random() * 10000); // Null by default

      var container = null;
      this.elements.controls = null; // Set template properties

      var props = {
        id: this.id,
        seektime: this.config.seekTime,
        title: this.config.title
      };
      var update = true; // If function, run it and use output

      if (is$1.function(this.config.controls)) {
        this.config.controls = this.config.controls.call(this, props);
      } // Convert falsy controls to empty array (primarily for empty strings)


      if (!this.config.controls) {
        this.config.controls = [];
      }

      if (is$1.element(this.config.controls) || is$1.string(this.config.controls)) {
        // HTMLElement or Non-empty string passed as the option
        container = this.config.controls;
      } else {
        // Create controls
        container = controls.create.call(this, {
          id: this.id,
          seektime: this.config.seekTime,
          speed: this.speed,
          quality: this.quality,
          captions: captions.getLabel.call(this) // TODO: Looping
          // loop: 'None',

        });
        update = false;
      } // Replace props with their value


      var replace = function replace(input) {
        var result = input;
        Object.entries(props).forEach(function (_ref2) {
          var _ref3 = _slicedToArray(_ref2, 2),
              key = _ref3[0],
              value = _ref3[1];

          result = replaceAll(result, "{".concat(key, "}"), value);
        });
        return result;
      }; // Update markup


      if (update) {
        if (is$1.string(this.config.controls)) {
          container = replace(container);
        } else if (is$1.element(container)) {
          container.innerHTML = replace(container.innerHTML);
        }
      } // Controls container


      var target; // Inject to custom location

      if (is$1.string(this.config.selectors.controls.container)) {
        target = document.querySelector(this.config.selectors.controls.container);
      } // Inject into the container by default


      if (!is$1.element(target)) {
        target = this.elements.container;
      } // Inject controls HTML (needs to be before captions, hence "afterbegin")


      var insertMethod = is$1.element(container) ? 'insertAdjacentElement' : 'insertAdjacentHTML';
      target[insertMethod]('afterbegin', container); // Find the elements if need be

      if (!is$1.element(this.elements.controls)) {
        controls.findElements.call(this);
      } // Add pressed property to buttons


      if (!is$1.empty(this.elements.buttons)) {
        var addProperty = function addProperty(button) {
          var className = _this11.config.classNames.controlPressed;
          Object.defineProperty(button, 'pressed', {
            enumerable: true,
            get: function get() {
              return hasClass(button, className);
            },
            set: function set() {
              var pressed = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
              toggleClass(button, className, pressed);
            }
          });
        }; // Toggle classname when pressed property is set


        Object.values(this.elements.buttons).filter(Boolean).forEach(function (button) {
          if (is$1.array(button) || is$1.nodeList(button)) {
            Array.from(button).filter(Boolean).forEach(addProperty);
          } else {
            addProperty(button);
          }
        });
      } // Edge sometimes doesn't finish the paint so force a repaint


      if (browser.isEdge) {
        repaint(target);
      } // Setup tooltips


      if (this.config.tooltips.controls) {
        var _this$config = this.config,
            classNames = _this$config.classNames,
            selectors = _this$config.selectors;
        var selector = "".concat(selectors.controls.wrapper, " ").concat(selectors.labels, " .").concat(classNames.hidden);
        var labels = getElements.call(this, selector);
        Array.from(labels).forEach(function (label) {
          toggleClass(label, _this11.config.classNames.hidden, false);
          toggleClass(label, _this11.config.classNames.tooltip, true);
        });
      }
    }
  };

  /**
   * Parse a string to a URL object
   * @param {String} input - the URL to be parsed
   * @param {Boolean} safe - failsafe parsing
   */

  function parseUrl(input) {
    var safe = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
    var url = input;

    if (safe) {
      var parser = document.createElement('a');
      parser.href = url;
      url = parser.href;
    }

    try {
      return new URL(url);
    } catch (e) {
      return null;
    }
  } // Convert object to URLSearchParams

  function buildUrlParams(input) {
    var params = new URLSearchParams();

    if (is$1.object(input)) {
      Object.entries(input).forEach(function (_ref) {
        var _ref2 = _slicedToArray(_ref, 2),
            key = _ref2[0],
            value = _ref2[1];

        params.set(key, value);
      });
    }

    return params;
  }

  var captions = {
    // Setup captions
    setup: function setup() {
      // Requires UI support
      if (!this.supported.ui) {
        return;
      } // Only Vimeo and HTML5 video supported at this point


      if (!this.isVideo || this.isYouTube || this.isHTML5 && !support.textTracks) {
        // Clear menu and hide
        if (is$1.array(this.config.controls) && this.config.controls.includes('settings') && this.config.settings.includes('captions')) {
          controls.setCaptionsMenu.call(this);
        }

        return;
      } // Inject the container


      if (!is$1.element(this.elements.captions)) {
        this.elements.captions = createElement('div', getAttributesFromSelector(this.config.selectors.captions));
        insertAfter(this.elements.captions, this.elements.wrapper);
      } // Fix IE captions if CORS is used
      // Fetch captions and inject as blobs instead (data URIs not supported!)


      if (browser.isIE && window.URL) {
        var elements = this.media.querySelectorAll('track');
        Array.from(elements).forEach(function (track) {
          var src = track.getAttribute('src');
          var url = parseUrl(src);

          if (url !== null && url.hostname !== window.location.href.hostname && ['http:', 'https:'].includes(url.protocol)) {
            fetch(src, 'blob').then(function (blob) {
              track.setAttribute('src', window.URL.createObjectURL(blob));
            }).catch(function () {
              removeElement(track);
            });
          }
        });
      } // Get and set initial data
      // The "preferred" options are not realized unless / until the wanted language has a match
      // * languages: Array of user's browser languages.
      // * language:  The language preferred by user settings or config
      // * active:    The state preferred by user settings or config
      // * toggled:   The real captions state


      var browserLanguages = navigator.languages || [navigator.language || navigator.userLanguage || 'en'];
      var languages = dedupe(browserLanguages.map(function (language) {
        return language.split('-')[0];
      }));
      var language = (this.storage.get('language') || this.config.captions.language || 'auto').toLowerCase(); // Use first browser language when language is 'auto'

      if (language === 'auto') {
        var _languages = _slicedToArray(languages, 1);

        language = _languages[0];
      }

      var active = this.storage.get('captions');

      if (!is$1.boolean(active)) {
        active = this.config.captions.active;
      }

      Object.assign(this.captions, {
        toggled: false,
        active: active,
        language: language,
        languages: languages
      }); // Watch changes to textTracks and update captions menu

      if (this.isHTML5) {
        var trackEvents = this.config.captions.update ? 'addtrack removetrack' : 'removetrack';
        on.call(this, this.media.textTracks, trackEvents, captions.update.bind(this));
      } // Update available languages in list next tick (the event must not be triggered before the listeners)


      setTimeout(captions.update.bind(this), 0);
    },
    // Update available language options in settings based on tracks
    update: function update() {
      var _this = this;

      var tracks = captions.getTracks.call(this, true); // Get the wanted language

      var _this$captions = this.captions,
          active = _this$captions.active,
          language = _this$captions.language,
          meta = _this$captions.meta,
          currentTrackNode = _this$captions.currentTrackNode;
      var languageExists = Boolean(tracks.find(function (track) {
        return track.language === language;
      })); // Handle tracks (add event listener and "pseudo"-default)

      if (this.isHTML5 && this.isVideo) {
        tracks.filter(function (track) {
          return !meta.get(track);
        }).forEach(function (track) {
          _this.debug.log('Track added', track); // Attempt to store if the original dom element was "default"


          meta.set(track, {
            default: track.mode === 'showing'
          }); // Turn off native caption rendering to avoid double captions
          // eslint-disable-next-line no-param-reassign

          track.mode = 'hidden'; // Add event listener for cue changes

          on.call(_this, track, 'cuechange', function () {
            return captions.updateCues.call(_this);
          });
        });
      } // Update language first time it matches, or if the previous matching track was removed


      if (languageExists && this.language !== language || !tracks.includes(currentTrackNode)) {
        captions.setLanguage.call(this, language);
        captions.toggle.call(this, active && languageExists);
      } // Enable or disable captions based on track length


      toggleClass(this.elements.container, this.config.classNames.captions.enabled, !is$1.empty(tracks)); // Update available languages in list

      if ((this.config.controls || []).includes('settings') && this.config.settings.includes('captions')) {
        controls.setCaptionsMenu.call(this);
      }
    },
    // Toggle captions display
    // Used internally for the toggleCaptions method, with the passive option forced to false
    toggle: function toggle(input) {
      var passive = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;

      // If there's no full support
      if (!this.supported.ui) {
        return;
      }

      var toggled = this.captions.toggled; // Current state

      var activeClass = this.config.classNames.captions.active; // Get the next state
      // If the method is called without parameter, toggle based on current value

      var active = is$1.nullOrUndefined(input) ? !toggled : input; // Update state and trigger event

      if (active !== toggled) {
        // When passive, don't override user preferences
        if (!passive) {
          this.captions.active = active;
          this.storage.set({
            captions: active
          });
        } // Force language if the call isn't passive and there is no matching language to toggle to


        if (!this.language && active && !passive) {
          var tracks = captions.getTracks.call(this);
          var track = captions.findTrack.call(this, [this.captions.language].concat(_toConsumableArray(this.captions.languages)), true); // Override user preferences to avoid switching languages if a matching track is added

          this.captions.language = track.language; // Set caption, but don't store in localStorage as user preference

          captions.set.call(this, tracks.indexOf(track));
          return;
        } // Toggle button if it's enabled


        if (this.elements.buttons.captions) {
          this.elements.buttons.captions.pressed = active;
        } // Add class hook


        toggleClass(this.elements.container, activeClass, active);
        this.captions.toggled = active; // Update settings menu

        controls.updateSetting.call(this, 'captions'); // Trigger event (not used internally)

        triggerEvent.call(this, this.media, active ? 'captionsenabled' : 'captionsdisabled');
      }
    },
    // Set captions by track index
    // Used internally for the currentTrack setter with the passive option forced to false
    set: function set(index) {
      var passive = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
      var tracks = captions.getTracks.call(this); // Disable captions if setting to -1

      if (index === -1) {
        captions.toggle.call(this, false, passive);
        return;
      }

      if (!is$1.number(index)) {
        this.debug.warn('Invalid caption argument', index);
        return;
      }

      if (!(index in tracks)) {
        this.debug.warn('Track not found', index);
        return;
      }

      if (this.captions.currentTrack !== index) {
        this.captions.currentTrack = index;
        var track = tracks[index];

        var _ref = track || {},
            language = _ref.language; // Store reference to node for invalidation on remove


        this.captions.currentTrackNode = track; // Update settings menu

        controls.updateSetting.call(this, 'captions'); // When passive, don't override user preferences

        if (!passive) {
          this.captions.language = language;
          this.storage.set({
            language: language
          });
        } // Handle Vimeo captions


        if (this.isVimeo) {
          this.embed.enableTextTrack(language);
        } // Trigger event


        triggerEvent.call(this, this.media, 'languagechange');
      } // Show captions


      captions.toggle.call(this, true, passive);

      if (this.isHTML5 && this.isVideo) {
        // If we change the active track while a cue is already displayed we need to update it
        captions.updateCues.call(this);
      }
    },
    // Set captions by language
    // Used internally for the language setter with the passive option forced to false
    setLanguage: function setLanguage(input) {
      var passive = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;

      if (!is$1.string(input)) {
        this.debug.warn('Invalid language argument', input);
        return;
      } // Normalize


      var language = input.toLowerCase();
      this.captions.language = language; // Set currentTrack

      var tracks = captions.getTracks.call(this);
      var track = captions.findTrack.call(this, [language]);
      captions.set.call(this, tracks.indexOf(track), passive);
    },
    // Get current valid caption tracks
    // If update is false it will also ignore tracks without metadata
    // This is used to "freeze" the language options when captions.update is false
    getTracks: function getTracks() {
      var _this2 = this;

      var update = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      // Handle media or textTracks missing or null
      var tracks = Array.from((this.media || {}).textTracks || []); // For HTML5, use cache instead of current tracks when it exists (if captions.update is false)
      // Filter out removed tracks and tracks that aren't captions/subtitles (for example metadata)

      return tracks.filter(function (track) {
        return !_this2.isHTML5 || update || _this2.captions.meta.has(track);
      }).filter(function (track) {
        return ['captions', 'subtitles'].includes(track.kind);
      });
    },
    // Match tracks based on languages and get the first
    findTrack: function findTrack(languages) {
      var _this3 = this;

      var force = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var tracks = captions.getTracks.call(this);

      var sortIsDefault = function sortIsDefault(track) {
        return Number((_this3.captions.meta.get(track) || {}).default);
      };

      var sorted = Array.from(tracks).sort(function (a, b) {
        return sortIsDefault(b) - sortIsDefault(a);
      });
      var track;
      languages.every(function (language) {
        track = sorted.find(function (t) {
          return t.language === language;
        });
        return !track; // Break iteration if there is a match
      }); // If no match is found but is required, get first

      return track || (force ? sorted[0] : undefined);
    },
    // Get the current track
    getCurrentTrack: function getCurrentTrack() {
      return captions.getTracks.call(this)[this.currentTrack];
    },
    // Get UI label for track
    getLabel: function getLabel(track) {
      var currentTrack = track;

      if (!is$1.track(currentTrack) && support.textTracks && this.captions.toggled) {
        currentTrack = captions.getCurrentTrack.call(this);
      }

      if (is$1.track(currentTrack)) {
        if (!is$1.empty(currentTrack.label)) {
          return currentTrack.label;
        }

        if (!is$1.empty(currentTrack.language)) {
          return track.language.toUpperCase();
        }

        return i18n.get('enabled', this.config);
      }

      return i18n.get('disabled', this.config);
    },
    // Update captions using current track's active cues
    // Also optional array argument in case there isn't any track (ex: vimeo)
    updateCues: function updateCues(input) {
      // Requires UI
      if (!this.supported.ui) {
        return;
      }

      if (!is$1.element(this.elements.captions)) {
        this.debug.warn('No captions element to render to');
        return;
      } // Only accept array or empty input


      if (!is$1.nullOrUndefined(input) && !Array.isArray(input)) {
        this.debug.warn('updateCues: Invalid input', input);
        return;
      }

      var cues = input; // Get cues from track

      if (!cues) {
        var track = captions.getCurrentTrack.call(this);
        cues = Array.from((track || {}).activeCues || []).map(function (cue) {
          return cue.getCueAsHTML();
        }).map(getHTML);
      } // Set new caption text


      var content = cues.map(function (cueText) {
        return cueText.trim();
      }).join('\n');
      var changed = content !== this.elements.captions.innerHTML;

      if (changed) {
        // Empty the container and create a new child element
        emptyElement(this.elements.captions);
        var caption = createElement('span', getAttributesFromSelector(this.config.selectors.caption));
        caption.innerHTML = content;
        this.elements.captions.appendChild(caption); // Trigger event

        triggerEvent.call(this, this.media, 'cuechange');
      }
    }
  };

  // ==========================================================================
  // Plyr default config
  // ==========================================================================
  var defaults$1 = {
    // Disable
    enabled: true,
    // Custom media title
    title: '',
    // Logging to console
    debug: false,
    // Auto play (if supported)
    autoplay: false,
    // Only allow one media playing at once (vimeo only)
    autopause: true,
    // Allow inline playback on iOS (this effects YouTube/Vimeo - HTML5 requires the attribute present)
    // TODO: Remove iosNative fullscreen option in favour of this (logic needs work)
    playsinline: true,
    // Default time to skip when rewind/fast forward
    seekTime: 10,
    // Default volume
    volume: 1,
    muted: false,
    // Pass a custom duration
    duration: null,
    // Display the media duration on load in the current time position
    // If you have opted to display both duration and currentTime, this is ignored
    displayDuration: true,
    // Invert the current time to be a countdown
    invertTime: true,
    // Clicking the currentTime inverts it's value to show time left rather than elapsed
    toggleInvert: true,
    // Force an aspect ratio
    // The format must be `'w:h'` (e.g. `'16:9'`)
    ratio: null,
    // Click video container to play/pause
    clickToPlay: true,
    // Auto hide the controls
    hideControls: true,
    // Reset to start when playback ended
    resetOnEnd: false,
    // Disable the standard context menu
    disableContextMenu: true,
    // Sprite (for icons)
    loadSprite: true,
    iconPrefix: 'plyr',
    iconUrl: 'https://cdn.plyr.io/3.5.6/plyr.svg',
    // Blank video (used to prevent errors on source change)
    blankVideo: 'https://cdn.plyr.io/static/blank.mp4',
    // Quality default
    quality: {
      default: 576,
      options: [4320, 2880, 2160, 1440, 1080, 720, 576, 480, 360, 240]
    },
    // Set loops
    loop: {
      active: false // start: null,
      // end: null,

    },
    // Speed default and options to display
    speed: {
      selected: 1,
      options: [0.5, 0.75, 1, 1.25, 1.5, 1.75, 2]
    },
    // Keyboard shortcut settings
    keyboard: {
      focused: true,
      global: false
    },
    // Display tooltips
    tooltips: {
      controls: false,
      seek: true
    },
    // Captions settings
    captions: {
      active: false,
      language: 'auto',
      // Listen to new tracks added after Plyr is initialized.
      // This is needed for streaming captions, but may result in unselectable options
      update: false
    },
    // Fullscreen settings
    fullscreen: {
      enabled: true,
      // Allow fullscreen?
      fallback: true,
      // Fallback using full viewport/window
      iosNative: false // Use the native fullscreen in iOS (disables custom controls)

    },
    // Local storage
    storage: {
      enabled: true,
      key: 'plyr'
    },
    // Default controls
    controls: ['play-large', // 'restart',
    // 'rewind',
    'play', // 'fast-forward',
    'progress', 'current-time', // 'duration',
    'mute', 'volume', 'captions', 'settings', 'pip', 'airplay', // 'download',
    'fullscreen'],
    settings: ['captions', 'quality', 'speed'],
    // Localisation
    i18n: {
      restart: 'Restart',
      rewind: 'Rewind {seektime}s',
      play: 'Play',
      pause: 'Pause',
      fastForward: 'Forward {seektime}s',
      seek: 'Seek',
      seekLabel: '{currentTime} of {duration}',
      played: 'Played',
      buffered: 'Buffered',
      currentTime: 'Current time',
      duration: 'Duration',
      volume: 'Volume',
      mute: 'Mute',
      unmute: 'Unmute',
      enableCaptions: 'Enable captions',
      disableCaptions: 'Disable captions',
      download: 'Download',
      enterFullscreen: 'Enter fullscreen',
      exitFullscreen: 'Exit fullscreen',
      frameTitle: 'Player for {title}',
      captions: 'Captions',
      settings: 'Settings',
      menuBack: 'Go back to previous menu',
      speed: 'Speed',
      normal: 'Normal',
      quality: 'Quality',
      loop: 'Loop',
      start: 'Start',
      end: 'End',
      all: 'All',
      reset: 'Reset',
      disabled: 'Disabled',
      enabled: 'Enabled',
      advertisement: 'Ad',
      qualityBadge: {
        2160: '4K',
        1440: 'HD',
        1080: 'HD',
        720: 'HD',
        576: 'SD',
        480: 'SD'
      }
    },
    // URLs
    urls: {
      download: null,
      vimeo: {
        sdk: 'https://player.vimeo.com/api/player.js',
        iframe: 'https://player.vimeo.com/video/{0}?{1}',
        api: 'https://vimeo.com/api/v2/video/{0}.json'
      },
      youtube: {
        sdk: 'https://www.youtube.com/iframe_api',
        api: 'https://noembed.com/embed?url=https://www.youtube.com/watch?v={0}'
      },
      googleIMA: {
        sdk: 'https://imasdk.googleapis.com/js/sdkloader/ima3.js'
      }
    },
    // Custom control listeners
    listeners: {
      seek: null,
      play: null,
      pause: null,
      restart: null,
      rewind: null,
      fastForward: null,
      mute: null,
      volume: null,
      captions: null,
      download: null,
      fullscreen: null,
      pip: null,
      airplay: null,
      speed: null,
      quality: null,
      loop: null,
      language: null
    },
    // Events to watch and bubble
    events: [// Events to watch on HTML5 media elements and bubble
    // https://developer.mozilla.org/en/docs/Web/Guide/Events/Media_events
    'ended', 'progress', 'stalled', 'playing', 'waiting', 'canplay', 'canplaythrough', 'loadstart', 'loadeddata', 'loadedmetadata', 'timeupdate', 'volumechange', 'play', 'pause', 'error', 'seeking', 'seeked', 'emptied', 'ratechange', 'cuechange', // Custom events
    'download', 'enterfullscreen', 'exitfullscreen', 'captionsenabled', 'captionsdisabled', 'languagechange', 'controlshidden', 'controlsshown', 'ready', // YouTube
    'statechange', // Quality
    'qualitychange', // Ads
    'adsloaded', 'adscontentpause', 'adscontentresume', 'adstarted', 'adsmidpoint', 'adscomplete', 'adsallcomplete', 'adsimpression', 'adsclick'],
    // Selectors
    // Change these to match your template if using custom HTML
    selectors: {
      editable: 'input, textarea, select, [contenteditable]',
      container: '.plyr',
      controls: {
        container: null,
        wrapper: '.plyr__controls'
      },
      labels: '[data-plyr]',
      buttons: {
        play: '[data-plyr="play"]',
        pause: '[data-plyr="pause"]',
        restart: '[data-plyr="restart"]',
        rewind: '[data-plyr="rewind"]',
        fastForward: '[data-plyr="fast-forward"]',
        mute: '[data-plyr="mute"]',
        captions: '[data-plyr="captions"]',
        download: '[data-plyr="download"]',
        fullscreen: '[data-plyr="fullscreen"]',
        pip: '[data-plyr="pip"]',
        airplay: '[data-plyr="airplay"]',
        settings: '[data-plyr="settings"]',
        loop: '[data-plyr="loop"]'
      },
      inputs: {
        seek: '[data-plyr="seek"]',
        volume: '[data-plyr="volume"]',
        speed: '[data-plyr="speed"]',
        language: '[data-plyr="language"]',
        quality: '[data-plyr="quality"]'
      },
      display: {
        currentTime: '.plyr__time--current',
        duration: '.plyr__time--duration',
        buffer: '.plyr__progress__buffer',
        loop: '.plyr__progress__loop',
        // Used later
        volume: '.plyr__volume--display'
      },
      progress: '.plyr__progress',
      captions: '.plyr__captions',
      caption: '.plyr__caption'
    },
    // Class hooks added to the player in different states
    classNames: {
      type: 'plyr--{0}',
      provider: 'plyr--{0}',
      video: 'plyr__video-wrapper',
      embed: 'plyr__video-embed',
      videoFixedRatio: 'plyr__video-wrapper--fixed-ratio',
      embedContainer: 'plyr__video-embed__container',
      poster: 'plyr__poster',
      posterEnabled: 'plyr__poster-enabled',
      ads: 'plyr__ads',
      control: 'plyr__control',
      controlPressed: 'plyr__control--pressed',
      playing: 'plyr--playing',
      paused: 'plyr--paused',
      stopped: 'plyr--stopped',
      loading: 'plyr--loading',
      hover: 'plyr--hover',
      tooltip: 'plyr__tooltip',
      cues: 'plyr__cues',
      hidden: 'plyr__sr-only',
      hideControls: 'plyr--hide-controls',
      isIos: 'plyr--is-ios',
      isTouch: 'plyr--is-touch',
      uiSupported: 'plyr--full-ui',
      noTransition: 'plyr--no-transition',
      display: {
        time: 'plyr__time'
      },
      menu: {
        value: 'plyr__menu__value',
        badge: 'plyr__badge',
        open: 'plyr--menu-open'
      },
      captions: {
        enabled: 'plyr--captions-enabled',
        active: 'plyr--captions-active'
      },
      fullscreen: {
        enabled: 'plyr--fullscreen-enabled',
        fallback: 'plyr--fullscreen-fallback'
      },
      pip: {
        supported: 'plyr--pip-supported',
        active: 'plyr--pip-active'
      },
      airplay: {
        supported: 'plyr--airplay-supported',
        active: 'plyr--airplay-active'
      },
      tabFocus: 'plyr__tab-focus',
      previewThumbnails: {
        // Tooltip thumbs
        thumbContainer: 'plyr__preview-thumb',
        thumbContainerShown: 'plyr__preview-thumb--is-shown',
        imageContainer: 'plyr__preview-thumb__image-container',
        timeContainer: 'plyr__preview-thumb__time-container',
        // Scrubbing
        scrubbingContainer: 'plyr__preview-scrubbing',
        scrubbingContainerShown: 'plyr__preview-scrubbing--is-shown'
      }
    },
    // Embed attributes
    attributes: {
      embed: {
        provider: 'data-plyr-provider',
        id: 'data-plyr-embed-id'
      }
    },
    // Advertisements plugin
    // Register for an account here: http://vi.ai/publisher-video-monetization/?aid=plyrio
    ads: {
      enabled: false,
      publisherId: '',
      tagUrl: ''
    },
    // Preview Thumbnails plugin
    previewThumbnails: {
      enabled: false,
      src: ''
    },
    // Vimeo plugin
    vimeo: {
      byline: false,
      portrait: false,
      title: false,
      speed: true,
      transparent: false
    },
    // YouTube plugin
    youtube: {
      noCookie: false,
      // Whether to use an alternative version of YouTube without cookies
      rel: 0,
      // No related vids
      showinfo: 0,
      // Hide info
      iv_load_policy: 3,
      // Hide annotations
      modestbranding: 1 // Hide logos as much as possible (they still show one in the corner when paused)

    }
  };

  // ==========================================================================
  // Plyr states
  // ==========================================================================
  var pip = {
    active: 'picture-in-picture',
    inactive: 'inline'
  };

  // ==========================================================================
  // Plyr supported types and providers
  // ==========================================================================
  var providers = {
    html5: 'html5',
    youtube: 'youtube',
    vimeo: 'vimeo'
  };
  var types = {
    audio: 'audio',
    video: 'video'
  };
  /**
   * Get provider by URL
   * @param {String} url
   */

  function getProviderByUrl(url) {
    // YouTube
    if (/^(https?:\/\/)?(www\.)?(youtube\.com|youtube-nocookie\.com|youtu\.?be)\/.+$/.test(url)) {
      return providers.youtube;
    } // Vimeo


    if (/^https?:\/\/player.vimeo.com\/video\/\d{0,9}(?=\b|\/)/.test(url)) {
      return providers.vimeo;
    }

    return null;
  }

  // ==========================================================================
  // Console wrapper
  // ==========================================================================
  var noop = function noop() {};

  var Console =
  /*#__PURE__*/
  function () {
    function Console() {
      var enabled = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

      _classCallCheck(this, Console);

      this.enabled = window.console && enabled;

      if (this.enabled) {
        this.log('Debugging enabled');
      }
    }

    _createClass(Console, [{
      key: "log",
      get: function get() {
        // eslint-disable-next-line no-console
        return this.enabled ? Function.prototype.bind.call(console.log, console) : noop;
      }
    }, {
      key: "warn",
      get: function get() {
        // eslint-disable-next-line no-console
        return this.enabled ? Function.prototype.bind.call(console.warn, console) : noop;
      }
    }, {
      key: "error",
      get: function get() {
        // eslint-disable-next-line no-console
        return this.enabled ? Function.prototype.bind.call(console.error, console) : noop;
      }
    }]);

    return Console;
  }();

  function onChange() {
    if (!this.enabled) {
      return;
    } // Update toggle button


    var button = this.player.elements.buttons.fullscreen;

    if (is$1.element(button)) {
      button.pressed = this.active;
    } // Trigger an event


    triggerEvent.call(this.player, this.target, this.active ? 'enterfullscreen' : 'exitfullscreen', true); // Trap focus in container

    if (!browser.isIos) {
      trapFocus.call(this.player, this.target, this.active);
    }
  }

  function toggleFallback() {
    var toggle = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

    // Store or restore scroll position
    if (toggle) {
      this.scrollPosition = {
        x: window.scrollX || 0,
        y: window.scrollY || 0
      };
    } else {
      window.scrollTo(this.scrollPosition.x, this.scrollPosition.y);
    } // Toggle scroll


    document.body.style.overflow = toggle ? 'hidden' : ''; // Toggle class hook

    toggleClass(this.target, this.player.config.classNames.fullscreen.fallback, toggle); // Force full viewport on iPhone X+

    if (browser.isIos) {
      var viewport = document.head.querySelector('meta[name="viewport"]');
      var property = 'viewport-fit=cover'; // Inject the viewport meta if required

      if (!viewport) {
        viewport = document.createElement('meta');
        viewport.setAttribute('name', 'viewport');
      } // Check if the property already exists


      var hasProperty = is$1.string(viewport.content) && viewport.content.includes(property);

      if (toggle) {
        this.cleanupViewport = !hasProperty;

        if (!hasProperty) {
          viewport.content += ",".concat(property);
        }
      } else if (this.cleanupViewport) {
        viewport.content = viewport.content.split(',').filter(function (part) {
          return part.trim() !== property;
        }).join(',');
      }
    } // Toggle button and fire events


    onChange.call(this);
  }

  var Fullscreen =
  /*#__PURE__*/
  function () {
    function Fullscreen(player) {
      var _this = this;

      _classCallCheck(this, Fullscreen);

      // Keep reference to parent
      this.player = player; // Get prefix

      this.prefix = Fullscreen.prefix;
      this.property = Fullscreen.property; // Scroll position

      this.scrollPosition = {
        x: 0,
        y: 0
      }; // Force the use of 'full window/browser' rather than fullscreen

      this.forceFallback = player.config.fullscreen.fallback === 'force'; // Register event listeners
      // Handle event (incase user presses escape etc)

      on.call(this.player, document, this.prefix === 'ms' ? 'MSFullscreenChange' : "".concat(this.prefix, "fullscreenchange"), function () {
        // TODO: Filter for target??
        onChange.call(_this);
      }); // Fullscreen toggle on double click

      on.call(this.player, this.player.elements.container, 'dblclick', function (event) {
        // Ignore double click in controls
        if (is$1.element(_this.player.elements.controls) && _this.player.elements.controls.contains(event.target)) {
          return;
        }

        _this.toggle();
      }); // Update the UI

      this.update();
    } // Determine if native supported


    _createClass(Fullscreen, [{
      key: "update",
      // Update UI
      value: function update() {
        if (this.enabled) {
          var mode;

          if (this.forceFallback) {
            mode = 'Fallback (forced)';
          } else if (Fullscreen.native) {
            mode = 'Native';
          } else {
            mode = 'Fallback';
          }

          this.player.debug.log("".concat(mode, " fullscreen enabled"));
        } else {
          this.player.debug.log('Fullscreen not supported and fallback disabled');
        } // Add styling hook to show button


        toggleClass(this.player.elements.container, this.player.config.classNames.fullscreen.enabled, this.enabled);
      } // Make an element fullscreen

    }, {
      key: "enter",
      value: function enter() {
        if (!this.enabled) {
          return;
        } // iOS native fullscreen doesn't need the request step


        if (browser.isIos && this.player.config.fullscreen.iosNative) {
          this.target.webkitEnterFullscreen();
        } else if (!Fullscreen.native || this.forceFallback) {
          toggleFallback.call(this, true);
        } else if (!this.prefix) {
          this.target.requestFullscreen();
        } else if (!is$1.empty(this.prefix)) {
          this.target["".concat(this.prefix, "Request").concat(this.property)]();
        }
      } // Bail from fullscreen

    }, {
      key: "exit",
      value: function exit() {
        if (!this.enabled) {
          return;
        } // iOS native fullscreen


        if (browser.isIos && this.player.config.fullscreen.iosNative) {
          this.target.webkitExitFullscreen();
          this.player.play();
        } else if (!Fullscreen.native || this.forceFallback) {
          toggleFallback.call(this, false);
        } else if (!this.prefix) {
          (document.cancelFullScreen || document.exitFullscreen).call(document);
        } else if (!is$1.empty(this.prefix)) {
          var action = this.prefix === 'moz' ? 'Cancel' : 'Exit';
          document["".concat(this.prefix).concat(action).concat(this.property)]();
        }
      } // Toggle state

    }, {
      key: "toggle",
      value: function toggle() {
        if (!this.active) {
          this.enter();
        } else {
          this.exit();
        }
      }
    }, {
      key: "usingNative",
      // If we're actually using native
      get: function get() {
        return Fullscreen.native && !this.forceFallback;
      } // Get the prefix for handlers

    }, {
      key: "enabled",
      // Determine if fullscreen is enabled
      get: function get() {
        return (Fullscreen.native || this.player.config.fullscreen.fallback) && this.player.config.fullscreen.enabled && this.player.supported.ui && this.player.isVideo;
      } // Get active state

    }, {
      key: "active",
      get: function get() {
        if (!this.enabled) {
          return false;
        } // Fallback using classname


        if (!Fullscreen.native || this.forceFallback) {
          return hasClass(this.target, this.player.config.classNames.fullscreen.fallback);
        }

        var element = !this.prefix ? document.fullscreenElement : document["".concat(this.prefix).concat(this.property, "Element")];
        return element === this.target;
      } // Get target element

    }, {
      key: "target",
      get: function get() {
        return browser.isIos && this.player.config.fullscreen.iosNative ? this.player.media : this.player.elements.container;
      }
    }], [{
      key: "native",
      get: function get() {
        return !!(document.fullscreenEnabled || document.webkitFullscreenEnabled || document.mozFullScreenEnabled || document.msFullscreenEnabled);
      }
    }, {
      key: "prefix",
      get: function get() {
        // No prefix
        if (is$1.function(document.exitFullscreen)) {
          return '';
        } // Check for fullscreen support by vendor prefix


        var value = '';
        var prefixes = ['webkit', 'moz', 'ms'];
        prefixes.some(function (pre) {
          if (is$1.function(document["".concat(pre, "ExitFullscreen")]) || is$1.function(document["".concat(pre, "CancelFullScreen")])) {
            value = pre;
            return true;
          }

          return false;
        });
        return value;
      }
    }, {
      key: "property",
      get: function get() {
        return this.prefix === 'moz' ? 'FullScreen' : 'Fullscreen';
      }
    }]);

    return Fullscreen;
  }();

  // ==========================================================================
  // Load image avoiding xhr/fetch CORS issues
  // Server status can't be obtained this way unfortunately, so this uses "naturalWidth" to determine if the image has loaded
  // By default it checks if it is at least 1px, but you can add a second argument to change this
  // ==========================================================================
  function loadImage(src) {
    var minWidth = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;
    return new Promise(function (resolve, reject) {
      var image = new Image();

      var handler = function handler() {
        delete image.onload;
        delete image.onerror;
        (image.naturalWidth >= minWidth ? resolve : reject)(image);
      };

      Object.assign(image, {
        onload: handler,
        onerror: handler,
        src: src
      });
    });
  }

  // ==========================================================================
  var ui = {
    addStyleHook: function addStyleHook() {
      toggleClass(this.elements.container, this.config.selectors.container.replace('.', ''), true);
      toggleClass(this.elements.container, this.config.classNames.uiSupported, this.supported.ui);
    },
    // Toggle native HTML5 media controls
    toggleNativeControls: function toggleNativeControls() {
      var toggle = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

      if (toggle && this.isHTML5) {
        this.media.setAttribute('controls', '');
      } else {
        this.media.removeAttribute('controls');
      }
    },
    // Setup the UI
    build: function build() {
      var _this = this;

      // Re-attach media element listeners
      // TODO: Use event bubbling?
      this.listeners.media(); // Don't setup interface if no support

      if (!this.supported.ui) {
        this.debug.warn("Basic support only for ".concat(this.provider, " ").concat(this.type)); // Restore native controls

        ui.toggleNativeControls.call(this, true); // Bail

        return;
      } // Inject custom controls if not present


      if (!is$1.element(this.elements.controls)) {
        // Inject custom controls
        controls.inject.call(this); // Re-attach control listeners

        this.listeners.controls();
      } // Remove native controls


      ui.toggleNativeControls.call(this); // Setup captions for HTML5

      if (this.isHTML5) {
        captions.setup.call(this);
      } // Reset volume


      this.volume = null; // Reset mute state

      this.muted = null; // Reset loop state

      this.loop = null; // Reset quality setting

      this.quality = null; // Reset speed

      this.speed = null; // Reset volume display

      controls.updateVolume.call(this); // Reset time display

      controls.timeUpdate.call(this); // Update the UI

      ui.checkPlaying.call(this); // Check for picture-in-picture support

      toggleClass(this.elements.container, this.config.classNames.pip.supported, support.pip && this.isHTML5 && this.isVideo); // Check for airplay support

      toggleClass(this.elements.container, this.config.classNames.airplay.supported, support.airplay && this.isHTML5); // Add iOS class

      toggleClass(this.elements.container, this.config.classNames.isIos, browser.isIos); // Add touch class

      toggleClass(this.elements.container, this.config.classNames.isTouch, this.touch); // Ready for API calls

      this.ready = true; // Ready event at end of execution stack

      setTimeout(function () {
        triggerEvent.call(_this, _this.media, 'ready');
      }, 0); // Set the title

      ui.setTitle.call(this); // Assure the poster image is set, if the property was added before the element was created

      if (this.poster) {
        ui.setPoster.call(this, this.poster, false).catch(function () {});
      } // Manually set the duration if user has overridden it.
      // The event listeners for it doesn't get called if preload is disabled (#701)


      if (this.config.duration) {
        controls.durationUpdate.call(this);
      }
    },
    // Setup aria attribute for play and iframe title
    setTitle: function setTitle() {
      // Find the current text
      var label = i18n.get('play', this.config); // If there's a media title set, use that for the label

      if (is$1.string(this.config.title) && !is$1.empty(this.config.title)) {
        label += ", ".concat(this.config.title);
      } // If there's a play button, set label


      Array.from(this.elements.buttons.play || []).forEach(function (button) {
        button.setAttribute('aria-label', label);
      }); // Set iframe title
      // https://github.com/sampotts/plyr/issues/124

      if (this.isEmbed) {
        var iframe = getElement.call(this, 'iframe');

        if (!is$1.element(iframe)) {
          return;
        } // Default to media type


        var title = !is$1.empty(this.config.title) ? this.config.title : 'video';
        var format = i18n.get('frameTitle', this.config);
        iframe.setAttribute('title', format.replace('{title}', title));
      }
    },
    // Toggle poster
    togglePoster: function togglePoster(enable) {
      toggleClass(this.elements.container, this.config.classNames.posterEnabled, enable);
    },
    // Set the poster image (async)
    // Used internally for the poster setter, with the passive option forced to false
    setPoster: function setPoster(poster) {
      var _this2 = this;

      var passive = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;

      // Don't override if call is passive
      if (passive && this.poster) {
        return Promise.reject(new Error('Poster already set'));
      } // Set property synchronously to respect the call order


      this.media.setAttribute('poster', poster); // Wait until ui is ready

      return ready.call(this) // Load image
      .then(function () {
        return loadImage(poster);
      }).catch(function (err) {
        // Hide poster on error unless it's been set by another call
        if (poster === _this2.poster) {
          ui.togglePoster.call(_this2, false);
        } // Rethrow


        throw err;
      }).then(function () {
        // Prevent race conditions
        if (poster !== _this2.poster) {
          throw new Error('setPoster cancelled by later call to setPoster');
        }
      }).then(function () {
        Object.assign(_this2.elements.poster.style, {
          backgroundImage: "url('".concat(poster, "')"),
          // Reset backgroundSize as well (since it can be set to "cover" for padded thumbnails for youtube)
          backgroundSize: ''
        });
        ui.togglePoster.call(_this2, true);
        return poster;
      });
    },
    // Check playing state
    checkPlaying: function checkPlaying(event) {
      var _this3 = this;

      // Class hooks
      toggleClass(this.elements.container, this.config.classNames.playing, this.playing);
      toggleClass(this.elements.container, this.config.classNames.paused, this.paused);
      toggleClass(this.elements.container, this.config.classNames.stopped, this.stopped); // Set state

      Array.from(this.elements.buttons.play || []).forEach(function (target) {
        Object.assign(target, {
          pressed: _this3.playing
        });
      }); // Only update controls on non timeupdate events

      if (is$1.event(event) && event.type === 'timeupdate') {
        return;
      } // Toggle controls


      ui.toggleControls.call(this);
    },
    // Check if media is loading
    checkLoading: function checkLoading(event) {
      var _this4 = this;

      this.loading = ['stalled', 'waiting'].includes(event.type); // Clear timer

      clearTimeout(this.timers.loading); // Timer to prevent flicker when seeking

      this.timers.loading = setTimeout(function () {
        // Update progress bar loading class state
        toggleClass(_this4.elements.container, _this4.config.classNames.loading, _this4.loading); // Update controls visibility

        ui.toggleControls.call(_this4);
      }, this.loading ? 250 : 0);
    },
    // Toggle controls based on state and `force` argument
    toggleControls: function toggleControls(force) {
      var controlsElement = this.elements.controls;

      if (controlsElement && this.config.hideControls) {
        // Don't hide controls if a touch-device user recently seeked. (Must be limited to touch devices, or it occasionally prevents desktop controls from hiding.)
        var recentTouchSeek = this.touch && this.lastSeekTime + 2000 > Date.now(); // Show controls if force, loading, paused, button interaction, or recent seek, otherwise hide

        this.toggleControls(Boolean(force || this.loading || this.paused || controlsElement.pressed || controlsElement.hover || recentTouchSeek));
      }
    }
  };

  var Listeners =
  /*#__PURE__*/
  function () {
    function Listeners(player) {
      _classCallCheck(this, Listeners);

      this.player = player;
      this.lastKey = null;
      this.focusTimer = null;
      this.lastKeyDown = null;
      this.handleKey = this.handleKey.bind(this);
      this.toggleMenu = this.toggleMenu.bind(this);
      this.setTabFocus = this.setTabFocus.bind(this);
      this.firstTouch = this.firstTouch.bind(this);
    } // Handle key presses


    _createClass(Listeners, [{
      key: "handleKey",
      value: function handleKey(event) {
        var player = this.player;
        var elements = player.elements;
        var code = event.keyCode ? event.keyCode : event.which;
        var pressed = event.type === 'keydown';
        var repeat = pressed && code === this.lastKey; // Bail if a modifier key is set

        if (event.altKey || event.ctrlKey || event.metaKey || event.shiftKey) {
          return;
        } // If the event is bubbled from the media element
        // Firefox doesn't get the keycode for whatever reason


        if (!is$1.number(code)) {
          return;
        } // Seek by the number keys


        var seekByKey = function seekByKey() {
          // Divide the max duration into 10th's and times by the number value
          player.currentTime = player.duration / 10 * (code - 48);
        }; // Handle the key on keydown
        // Reset on keyup


        if (pressed) {
          // Check focused element
          // and if the focused element is not editable (e.g. text input)
          // and any that accept key input http://webaim.org/techniques/keyboard/
          var focused = document.activeElement;

          if (is$1.element(focused)) {
            var editable = player.config.selectors.editable;
            var seek = elements.inputs.seek;

            if (focused !== seek && matches$1(focused, editable)) {
              return;
            }

            if (event.which === 32 && matches$1(focused, 'button, [role^="menuitem"]')) {
              return;
            }
          } // Which keycodes should we prevent default


          var preventDefault = [32, 37, 38, 39, 40, 48, 49, 50, 51, 52, 53, 54, 56, 57, 67, 70, 73, 75, 76, 77, 79]; // If the code is found prevent default (e.g. prevent scrolling for arrows)

          if (preventDefault.includes(code)) {
            event.preventDefault();
            event.stopPropagation();
          }

          switch (code) {
            case 48:
            case 49:
            case 50:
            case 51:
            case 52:
            case 53:
            case 54:
            case 55:
            case 56:
            case 57:
              // 0-9
              if (!repeat) {
                seekByKey();
              }

              break;

            case 32:
            case 75:
              // Space and K key
              if (!repeat) {
                player.togglePlay();
              }

              break;

            case 38:
              // Arrow up
              player.increaseVolume(0.1);
              break;

            case 40:
              // Arrow down
              player.decreaseVolume(0.1);
              break;

            case 77:
              // M key
              if (!repeat) {
                player.muted = !player.muted;
              }

              break;

            case 39:
              // Arrow forward
              player.forward();
              break;

            case 37:
              // Arrow back
              player.rewind();
              break;

            case 70:
              // F key
              player.fullscreen.toggle();
              break;

            case 67:
              // C key
              if (!repeat) {
                player.toggleCaptions();
              }

              break;

            case 76:
              // L key
              player.loop = !player.loop;
              break;

            /* case 73:
                this.setLoop('start');
                break;
             case 76:
                this.setLoop();
                break;
             case 79:
                this.setLoop('end');
                break; */

            default:
              break;
          } // Escape is handle natively when in full screen
          // So we only need to worry about non native


          if (code === 27 && !player.fullscreen.usingNative && player.fullscreen.active) {
            player.fullscreen.toggle();
          } // Store last code for next cycle


          this.lastKey = code;
        } else {
          this.lastKey = null;
        }
      } // Toggle menu

    }, {
      key: "toggleMenu",
      value: function toggleMenu(event) {
        controls.toggleMenu.call(this.player, event);
      } // Device is touch enabled

    }, {
      key: "firstTouch",
      value: function firstTouch() {
        var player = this.player;
        var elements = player.elements;
        player.touch = true; // Add touch class

        toggleClass(elements.container, player.config.classNames.isTouch, true);
      }
    }, {
      key: "setTabFocus",
      value: function setTabFocus(event) {
        var player = this.player;
        var elements = player.elements;
        clearTimeout(this.focusTimer); // Ignore any key other than tab

        if (event.type === 'keydown' && event.which !== 9) {
          return;
        } // Store reference to event timeStamp


        if (event.type === 'keydown') {
          this.lastKeyDown = event.timeStamp;
        } // Remove current classes


        var removeCurrent = function removeCurrent() {
          var className = player.config.classNames.tabFocus;
          var current = getElements.call(player, ".".concat(className));
          toggleClass(current, className, false);
        }; // Determine if a key was pressed to trigger this event


        var wasKeyDown = event.timeStamp - this.lastKeyDown <= 20; // Ignore focus events if a key was pressed prior

        if (event.type === 'focus' && !wasKeyDown) {
          return;
        } // Remove all current


        removeCurrent(); // Delay the adding of classname until the focus has changed
        // This event fires before the focusin event

        this.focusTimer = setTimeout(function () {
          var focused = document.activeElement; // Ignore if current focus element isn't inside the player

          if (!elements.container.contains(focused)) {
            return;
          }

          toggleClass(document.activeElement, player.config.classNames.tabFocus, true);
        }, 10);
      } // Global window & document listeners

    }, {
      key: "global",
      value: function global() {
        var toggle = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
        var player = this.player; // Keyboard shortcuts

        if (player.config.keyboard.global) {
          toggleListener.call(player, window, 'keydown keyup', this.handleKey, toggle, false);
        } // Click anywhere closes menu


        toggleListener.call(player, document.body, 'click', this.toggleMenu, toggle); // Detect touch by events

        once.call(player, document.body, 'touchstart', this.firstTouch); // Tab focus detection

        toggleListener.call(player, document.body, 'keydown focus blur', this.setTabFocus, toggle, false, true);
      } // Container listeners

    }, {
      key: "container",
      value: function container() {
        var player = this.player;
        var config = player.config,
            elements = player.elements,
            timers = player.timers; // Keyboard shortcuts

        if (!config.keyboard.global && config.keyboard.focused) {
          on.call(player, elements.container, 'keydown keyup', this.handleKey, false);
        } // Toggle controls on mouse events and entering fullscreen


        on.call(player, elements.container, 'mousemove mouseleave touchstart touchmove enterfullscreen exitfullscreen', function (event) {
          var controlsElement = elements.controls; // Remove button states for fullscreen

          if (controlsElement && event.type === 'enterfullscreen') {
            controlsElement.pressed = false;
            controlsElement.hover = false;
          } // Show, then hide after a timeout unless another control event occurs


          var show = ['touchstart', 'touchmove', 'mousemove'].includes(event.type);
          var delay = 0;

          if (show) {
            ui.toggleControls.call(player, true); // Use longer timeout for touch devices

            delay = player.touch ? 3000 : 2000;
          } // Clear timer


          clearTimeout(timers.controls); // Set new timer to prevent flicker when seeking

          timers.controls = setTimeout(function () {
            return ui.toggleControls.call(player, false);
          }, delay);
        }); // Set a gutter for Vimeo

        var setGutter = function setGutter(ratio, padding, toggle) {
          if (!player.isVimeo) {
            return;
          }

          var target = player.elements.wrapper.firstChild;

          var _ratio = _slicedToArray(ratio, 2),
              y = _ratio[1];

          var _getAspectRatio$call = getAspectRatio.call(player),
              _getAspectRatio$call2 = _slicedToArray(_getAspectRatio$call, 2),
              videoX = _getAspectRatio$call2[0],
              videoY = _getAspectRatio$call2[1];

          target.style.maxWidth = toggle ? "".concat(y / videoY * videoX, "px") : null;
          target.style.margin = toggle ? '0 auto' : null;
        }; // Resize on fullscreen change


        var setPlayerSize = function setPlayerSize(measure) {
          // If we don't need to measure the viewport
          if (!measure) {
            return setAspectRatio.call(player);
          }

          var rect = elements.container.getBoundingClientRect();
          var width = rect.width,
              height = rect.height;
          return setAspectRatio.call(player, "".concat(width, ":").concat(height));
        };

        var resized = function resized() {
          clearTimeout(timers.resized);
          timers.resized = setTimeout(setPlayerSize, 50);
        };

        on.call(player, elements.container, 'enterfullscreen exitfullscreen', function (event) {
          var _player$fullscreen = player.fullscreen,
              target = _player$fullscreen.target,
              usingNative = _player$fullscreen.usingNative; // Ignore events not from target

          if (target !== elements.container) {
            return;
          } // If it's not an embed and no ratio specified


          if (!player.isEmbed && is$1.empty(player.config.ratio)) {
            return;
          }

          var isEnter = event.type === 'enterfullscreen'; // Set the player size when entering fullscreen to viewport size

          var _setPlayerSize = setPlayerSize(isEnter),
              padding = _setPlayerSize.padding,
              ratio = _setPlayerSize.ratio; // Set Vimeo gutter


          setGutter(ratio, padding, isEnter); // If not using native fullscreen, we need to check for resizes of viewport

          if (!usingNative) {
            if (isEnter) {
              on.call(player, window, 'resize', resized);
            } else {
              off.call(player, window, 'resize', resized);
            }
          }
        });
      } // Listen for media events

    }, {
      key: "media",
      value: function media() {
        var _this = this;

        var player = this.player;
        var elements = player.elements; // Time change on media

        on.call(player, player.media, 'timeupdate seeking seeked', function (event) {
          return controls.timeUpdate.call(player, event);
        }); // Display duration

        on.call(player, player.media, 'durationchange loadeddata loadedmetadata', function (event) {
          return controls.durationUpdate.call(player, event);
        }); // Check for audio tracks on load
        // We can't use `loadedmetadata` as it doesn't seem to have audio tracks at that point

        on.call(player, player.media, 'canplay loadeddata', function () {
          toggleHidden(elements.volume, !player.hasAudio);
          toggleHidden(elements.buttons.mute, !player.hasAudio);
        }); // Handle the media finishing

        on.call(player, player.media, 'ended', function () {
          // Show poster on end
          if (player.isHTML5 && player.isVideo && player.config.resetOnEnd) {
            // Restart
            player.restart();
          }
        }); // Check for buffer progress

        on.call(player, player.media, 'progress playing seeking seeked', function (event) {
          return controls.updateProgress.call(player, event);
        }); // Handle volume changes

        on.call(player, player.media, 'volumechange', function (event) {
          return controls.updateVolume.call(player, event);
        }); // Handle play/pause

        on.call(player, player.media, 'playing play pause ended emptied timeupdate', function (event) {
          return ui.checkPlaying.call(player, event);
        }); // Loading state

        on.call(player, player.media, 'waiting canplay seeked playing', function (event) {
          return ui.checkLoading.call(player, event);
        }); // Click video

        if (player.supported.ui && player.config.clickToPlay && !player.isAudio) {
          // Re-fetch the wrapper
          var wrapper = getElement.call(player, ".".concat(player.config.classNames.video)); // Bail if there's no wrapper (this should never happen)

          if (!is$1.element(wrapper)) {
            return;
          } // On click play, pause or restart


          on.call(player, elements.container, 'click', function (event) {
            var targets = [elements.container, wrapper]; // Ignore if click if not container or in video wrapper

            if (!targets.includes(event.target) && !wrapper.contains(event.target)) {
              return;
            } // Touch devices will just show controls (if hidden)


            if (player.touch && player.config.hideControls) {
              return;
            }

            if (player.ended) {
              _this.proxy(event, player.restart, 'restart');

              _this.proxy(event, player.play, 'play');
            } else {
              _this.proxy(event, player.togglePlay, 'play');
            }
          });
        } // Disable right click


        if (player.supported.ui && player.config.disableContextMenu) {
          on.call(player, elements.wrapper, 'contextmenu', function (event) {
            event.preventDefault();
          }, false);
        } // Volume change


        on.call(player, player.media, 'volumechange', function () {
          // Save to storage
          player.storage.set({
            volume: player.volume,
            muted: player.muted
          });
        }); // Speed change

        on.call(player, player.media, 'ratechange', function () {
          // Update UI
          controls.updateSetting.call(player, 'speed'); // Save to storage


          player.storage.set({
            speed: player.speed
          });
        }); // Quality change

        on.call(player, player.media, 'qualitychange', function (event) {
          // Update UI
          controls.updateSetting.call(player, 'quality', null, event.detail.quality);
        }); // Update download link when ready and if quality changes

        on.call(player, player.media, 'ready qualitychange', function () {
          controls.setDownloadUrl.call(player);
        }); // Proxy events to container
        // Bubble up key events for Edge

        var proxyEvents = player.config.events.concat(['keyup', 'keydown']).join(' ');
        on.call(player, player.media, proxyEvents, function (event) {
          var _event$detail = event.detail,
              detail = _event$detail === void 0 ? {} : _event$detail; // Get error details from media

          if (event.type === 'error') {
            detail = player.media.error;
          }

          triggerEvent.call(player, elements.container, event.type, true, detail);
        });
      } // Run default and custom handlers

    }, {
      key: "proxy",
      value: function proxy(event, defaultHandler, customHandlerKey) {
        var player = this.player;
        var customHandler = player.config.listeners[customHandlerKey];
        var hasCustomHandler = is$1.function(customHandler);
        var returned = true; // Execute custom handler

        if (hasCustomHandler) {
          returned = customHandler.call(player, event);
        } // Only call default handler if not prevented in custom handler


        if (returned && is$1.function(defaultHandler)) {
          defaultHandler.call(player, event);
        }
      } // Trigger custom and default handlers

    }, {
      key: "bind",
      value: function bind(element, type, defaultHandler, customHandlerKey) {
        var _this2 = this;

        var passive = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : true;
        var player = this.player;
        var customHandler = player.config.listeners[customHandlerKey];
        var hasCustomHandler = is$1.function(customHandler);
        on.call(player, element, type, function (event) {
          return _this2.proxy(event, defaultHandler, customHandlerKey);
        }, passive && !hasCustomHandler);
      } // Listen for control events

    }, {
      key: "controls",
      value: function controls$1() {
        var _this3 = this;

        var player = this.player;
        var elements = player.elements; // IE doesn't support input event, so we fallback to change

        var inputEvent = browser.isIE ? 'change' : 'input'; // Play/pause toggle

        if (elements.buttons.play) {
          Array.from(elements.buttons.play).forEach(function (button) {
            _this3.bind(button, 'click', player.togglePlay, 'play');
          });
        } // Pause


        this.bind(elements.buttons.restart, 'click', player.restart, 'restart'); // Rewind

        this.bind(elements.buttons.rewind, 'click', player.rewind, 'rewind'); // Rewind

        this.bind(elements.buttons.fastForward, 'click', player.forward, 'fastForward'); // Mute toggle

        this.bind(elements.buttons.mute, 'click', function () {
          player.muted = !player.muted;
        }, 'mute'); // Captions toggle

        this.bind(elements.buttons.captions, 'click', function () {
          return player.toggleCaptions();
        }); // Download

        this.bind(elements.buttons.download, 'click', function () {
          triggerEvent.call(player, player.media, 'download');
        }, 'download'); // Fullscreen toggle

        this.bind(elements.buttons.fullscreen, 'click', function () {
          player.fullscreen.toggle();
        }, 'fullscreen'); // Picture-in-Picture

        this.bind(elements.buttons.pip, 'click', function () {
          player.pip = 'toggle';
        }, 'pip'); // Airplay

        this.bind(elements.buttons.airplay, 'click', player.airplay, 'airplay'); // Settings menu - click toggle

        this.bind(elements.buttons.settings, 'click', function (event) {
          // Prevent the document click listener closing the menu
          event.stopPropagation();

          controls.toggleMenu.call(player, event);
        }); // Settings menu - keyboard toggle
        // We have to bind to keyup otherwise Firefox triggers a click when a keydown event handler shifts focus
        // https://bugzilla.mozilla.org/show_bug.cgi?id=1220143

        this.bind(elements.buttons.settings, 'keyup', function (event) {
          var code = event.which; // We only care about space and return

          if (![13, 32].includes(code)) {
            return;
          } // Because return triggers a click anyway, all we need to do is set focus


          if (code === 13) {
            controls.focusFirstMenuItem.call(player, null, true);

            return;
          } // Prevent scroll


          event.preventDefault(); // Prevent playing video (Firefox)

          event.stopPropagation(); // Toggle menu

          controls.toggleMenu.call(player, event);
        }, null, false // Can't be passive as we're preventing default
        ); // Escape closes menu

        this.bind(elements.settings.menu, 'keydown', function (event) {
          if (event.which === 27) {
            controls.toggleMenu.call(player, event);
          }
        }); // Set range input alternative "value", which matches the tooltip time (#954)

        this.bind(elements.inputs.seek, 'mousedown mousemove', function (event) {
          var rect = elements.progress.getBoundingClientRect();
          var percent = 100 / rect.width * (event.pageX - rect.left);
          event.currentTarget.setAttribute('seek-value', percent);
        }); // Pause while seeking

        this.bind(elements.inputs.seek, 'mousedown mouseup keydown keyup touchstart touchend', function (event) {
          var seek = event.currentTarget;
          var code = event.keyCode ? event.keyCode : event.which;
          var attribute = 'play-on-seeked';

          if (is$1.keyboardEvent(event) && code !== 39 && code !== 37) {
            return;
          } // Record seek time so we can prevent hiding controls for a few seconds after seek


          player.lastSeekTime = Date.now(); // Was playing before?

          var play = seek.hasAttribute(attribute); // Done seeking

          var done = ['mouseup', 'touchend', 'keyup'].includes(event.type); // If we're done seeking and it was playing, resume playback

          if (play && done) {
            seek.removeAttribute(attribute);
            player.play();
          } else if (!done && player.playing) {
            seek.setAttribute(attribute, '');
            player.pause();
          }
        }); // Fix range inputs on iOS
        // Super weird iOS bug where after you interact with an <input type="range">,
        // it takes over further interactions on the page. This is a hack

        if (browser.isIos) {
          var inputs = getElements.call(player, 'input[type="range"]');
          Array.from(inputs).forEach(function (input) {
            return _this3.bind(input, inputEvent, function (event) {
              return repaint(event.target);
            });
          });
        } // Seek


        this.bind(elements.inputs.seek, inputEvent, function (event) {
          var seek = event.currentTarget; // If it exists, use seek-value instead of "value" for consistency with tooltip time (#954)

          var seekTo = seek.getAttribute('seek-value');

          if (is$1.empty(seekTo)) {
            seekTo = seek.value;
          }

          seek.removeAttribute('seek-value');
          player.currentTime = seekTo / seek.max * player.duration;
        }, 'seek'); // Seek tooltip

        this.bind(elements.progress, 'mouseenter mouseleave mousemove', function (event) {
          return controls.updateSeekTooltip.call(player, event);
        }); // Preview thumbnails plugin
        // TODO: Really need to work on some sort of plug-in wide event bus or pub-sub for this

        this.bind(elements.progress, 'mousemove touchmove', function (event) {
          var previewThumbnails = player.previewThumbnails;

          if (previewThumbnails && previewThumbnails.loaded) {
            previewThumbnails.startMove(event);
          }
        }); // Hide thumbnail preview - on mouse click, mouse leave, and video play/seek. All four are required, e.g., for buffering

        this.bind(elements.progress, 'mouseleave click', function () {
          var previewThumbnails = player.previewThumbnails;

          if (previewThumbnails && previewThumbnails.loaded) {
            previewThumbnails.endMove(false, true);
          }
        }); // Show scrubbing preview

        this.bind(elements.progress, 'mousedown touchstart', function (event) {
          var previewThumbnails = player.previewThumbnails;

          if (previewThumbnails && previewThumbnails.loaded) {
            previewThumbnails.startScrubbing(event);
          }
        });
        this.bind(elements.progress, 'mouseup touchend', function (event) {
          var previewThumbnails = player.previewThumbnails;

          if (previewThumbnails && previewThumbnails.loaded) {
            previewThumbnails.endScrubbing(event);
          }
        }); // Polyfill for lower fill in <input type="range"> for webkit

        if (browser.isWebkit) {
          Array.from(getElements.call(player, 'input[type="range"]')).forEach(function (element) {
            _this3.bind(element, 'input', function (event) {
              return controls.updateRangeFill.call(player, event.target);
            });
          });
        } // Current time invert
        // Only if one time element is used for both currentTime and duration


        if (player.config.toggleInvert && !is$1.element(elements.display.duration)) {
          this.bind(elements.display.currentTime, 'click', function () {
            // Do nothing if we're at the start
            if (player.currentTime === 0) {
              return;
            }

            player.config.invertTime = !player.config.invertTime;

            controls.timeUpdate.call(player);
          });
        } // Volume


        this.bind(elements.inputs.volume, inputEvent, function (event) {
          player.volume = event.target.value;
        }, 'volume'); // Update controls.hover state (used for ui.toggleControls to avoid hiding when interacting)

        this.bind(elements.controls, 'mouseenter mouseleave', function (event) {
          elements.controls.hover = !player.touch && event.type === 'mouseenter';
        }); // Update controls.pressed state (used for ui.toggleControls to avoid hiding when interacting)

        this.bind(elements.controls, 'mousedown mouseup touchstart touchend touchcancel', function (event) {
          elements.controls.pressed = ['mousedown', 'touchstart'].includes(event.type);
        }); // Show controls when they receive focus (e.g., when using keyboard tab key)

        this.bind(elements.controls, 'focusin', function () {
          var config = player.config,
              timers = player.timers; // Skip transition to prevent focus from scrolling the parent element

          toggleClass(elements.controls, config.classNames.noTransition, true); // Toggle

          ui.toggleControls.call(player, true); // Restore transition

          setTimeout(function () {
            toggleClass(elements.controls, config.classNames.noTransition, false);
          }, 0); // Delay a little more for mouse users

          var delay = _this3.touch ? 3000 : 4000; // Clear timer

          clearTimeout(timers.controls); // Hide again after delay

          timers.controls = setTimeout(function () {
            return ui.toggleControls.call(player, false);
          }, delay);
        }); // Mouse wheel for volume

        this.bind(elements.inputs.volume, 'wheel', function (event) {
          // Detect "natural" scroll - suppored on OS X Safari only
          // Other browsers on OS X will be inverted until support improves
          var inverted = event.webkitDirectionInvertedFromDevice; // Get delta from event. Invert if `inverted` is true

          var _map = [event.deltaX, -event.deltaY].map(function (value) {
            return inverted ? -value : value;
          }),
              _map2 = _slicedToArray(_map, 2),
              x = _map2[0],
              y = _map2[1]; // Using the biggest delta, normalize to 1 or -1 (or 0 if no delta)


          var direction = Math.sign(Math.abs(x) > Math.abs(y) ? x : y); // Change the volume by 2%

          player.increaseVolume(direction / 50); // Don't break page scrolling at max and min

          var volume = player.media.volume;

          if (direction === 1 && volume < 1 || direction === -1 && volume > 0) {
            event.preventDefault();
          }
        }, 'volume', false);
      }
    }]);

    return Listeners;
  }();

  var commonjsGlobal = typeof globalThis !== 'undefined' ? globalThis : typeof window !== 'undefined' ? window : typeof global !== 'undefined' ? global : typeof self !== 'undefined' ? self : {};

  function createCommonjsModule(fn, module) {
  	return module = { exports: {} }, fn(module, module.exports), module.exports;
  }

  var loadjs_umd = createCommonjsModule(function (module, exports) {
    (function (root, factory) {
      {
        module.exports = factory();
      }
    })(commonjsGlobal, function () {
      /**
       * Global dependencies.
       * @global {Object} document - DOM
       */
      var devnull = function devnull() {},
          bundleIdCache = {},
          bundleResultCache = {},
          bundleCallbackQueue = {};
      /**
       * Subscribe to bundle load event.
       * @param {string[]} bundleIds - Bundle ids
       * @param {Function} callbackFn - The callback function
       */


      function subscribe(bundleIds, callbackFn) {
        // listify
        bundleIds = bundleIds.push ? bundleIds : [bundleIds];
        var depsNotFound = [],
            i = bundleIds.length,
            numWaiting = i,
            fn,
            bundleId,
            r,
            q; // define callback function

        fn = function fn(bundleId, pathsNotFound) {
          if (pathsNotFound.length) depsNotFound.push(bundleId);
          numWaiting--;
          if (!numWaiting) callbackFn(depsNotFound);
        }; // register callback


        while (i--) {
          bundleId = bundleIds[i]; // execute callback if in result cache

          r = bundleResultCache[bundleId];

          if (r) {
            fn(bundleId, r);
            continue;
          } // add to callback queue


          q = bundleCallbackQueue[bundleId] = bundleCallbackQueue[bundleId] || [];
          q.push(fn);
        }
      }
      /**
       * Publish bundle load event.
       * @param {string} bundleId - Bundle id
       * @param {string[]} pathsNotFound - List of files not found
       */


      function publish(bundleId, pathsNotFound) {
        // exit if id isn't defined
        if (!bundleId) return;
        var q = bundleCallbackQueue[bundleId]; // cache result

        bundleResultCache[bundleId] = pathsNotFound; // exit if queue is empty

        if (!q) return; // empty callback queue

        while (q.length) {
          q[0](bundleId, pathsNotFound);
          q.splice(0, 1);
        }
      }
      /**
       * Execute callbacks.
       * @param {Object or Function} args - The callback args
       * @param {string[]} depsNotFound - List of dependencies not found
       */


      function executeCallbacks(args, depsNotFound) {
        // accept function as argument
        if (args.call) args = {
          success: args
        }; // success and error callbacks

        if (depsNotFound.length) (args.error || devnull)(depsNotFound);else (args.success || devnull)(args);
      }
      /**
       * Load individual file.
       * @param {string} path - The file path
       * @param {Function} callbackFn - The callback function
       */


      function loadFile(path, callbackFn, args, numTries) {
        var doc = document,
            async = args.async,
            maxTries = (args.numRetries || 0) + 1,
            beforeCallbackFn = args.before || devnull,
            pathStripped = path.replace(/^(css|img)!/, ''),
            isLegacyIECss,
            e;
        numTries = numTries || 0;

        if (/(^css!|\.css$)/.test(path)) {
          // css
          e = doc.createElement('link');
          e.rel = 'stylesheet';
          e.href = pathStripped; // tag IE9+

          isLegacyIECss = 'hideFocus' in e; // use preload in IE Edge (to detect load errors)

          if (isLegacyIECss && e.relList) {
            isLegacyIECss = 0;
            e.rel = 'preload';
            e.as = 'style';
          }
        } else if (/(^img!|\.(png|gif|jpg|svg)$)/.test(path)) {
          // image
          e = doc.createElement('img');
          e.src = pathStripped;
        } else {
          // javascript
          e = doc.createElement('script');
          e.src = path;
          e.async = async === undefined ? true : async;
        }

        e.onload = e.onerror = e.onbeforeload = function (ev) {
          var result = ev.type[0]; // treat empty stylesheets as failures to get around lack of onerror
          // support in IE9-11

          if (isLegacyIECss) {
            try {
              if (!e.sheet.cssText.length) result = 'e';
            } catch (x) {
              // sheets objects created from load errors don't allow access to
              // `cssText` (unless error is Code:18 SecurityError)
              if (x.code != 18) result = 'e';
            }
          } // handle retries in case of load failure


          if (result == 'e') {
            // increment counter
            numTries += 1; // exit function and try again

            if (numTries < maxTries) {
              return loadFile(path, callbackFn, args, numTries);
            }
          } else if (e.rel == 'preload' && e.as == 'style') {
            // activate preloaded stylesheets
            return e.rel = 'stylesheet'; // jshint ignore:line
          } // execute callback


          callbackFn(path, result, ev.defaultPrevented);
        }; // add to document (unless callback returns `false`)


        if (beforeCallbackFn(path, e) !== false) doc.head.appendChild(e);
      }
      /**
       * Load multiple files.
       * @param {string[]} paths - The file paths
       * @param {Function} callbackFn - The callback function
       */


      function loadFiles(paths, callbackFn, args) {
        // listify paths
        paths = paths.push ? paths : [paths];
        var numWaiting = paths.length,
            x = numWaiting,
            pathsNotFound = [],
            fn,
            i; // define callback function

        fn = function fn(path, result, defaultPrevented) {
          // handle error
          if (result == 'e') pathsNotFound.push(path); // handle beforeload event. If defaultPrevented then that means the load
          // will be blocked (ex. Ghostery/ABP on Safari)

          if (result == 'b') {
            if (defaultPrevented) pathsNotFound.push(path);else return;
          }

          numWaiting--;
          if (!numWaiting) callbackFn(pathsNotFound);
        }; // load scripts


        for (i = 0; i < x; i++) {
          loadFile(paths[i], fn, args);
        }
      }
      /**
       * Initiate script load and register bundle.
       * @param {(string|string[])} paths - The file paths
       * @param {(string|Function|Object)} [arg1] - The (1) bundleId or (2) success
       *   callback or (3) object literal with success/error arguments, numRetries,
       *   etc.
       * @param {(Function|Object)} [arg2] - The (1) success callback or (2) object
       *   literal with success/error arguments, numRetries, etc.
       */


      function loadjs(paths, arg1, arg2) {
        var bundleId, args; // bundleId (if string)

        if (arg1 && arg1.trim) bundleId = arg1; // args (default is {})

        args = (bundleId ? arg2 : arg1) || {}; // throw error if bundle is already defined

        if (bundleId) {
          if (bundleId in bundleIdCache) {
            throw "LoadJS";
          } else {
            bundleIdCache[bundleId] = true;
          }
        }

        function loadFn(resolve, reject) {
          loadFiles(paths, function (pathsNotFound) {
            // execute callbacks
            executeCallbacks(args, pathsNotFound); // resolve Promise

            if (resolve) {
              executeCallbacks({
                success: resolve,
                error: reject
              }, pathsNotFound);
            } // publish bundle load event


            publish(bundleId, pathsNotFound);
          }, args);
        }

        if (args.returnPromise) return new Promise(loadFn);else loadFn();
      }
      /**
       * Execute callbacks when dependencies have been satisfied.
       * @param {(string|string[])} deps - List of bundle ids
       * @param {Object} args - success/error arguments
       */


      loadjs.ready = function ready(deps, args) {
        // subscribe to bundle load event
        subscribe(deps, function (depsNotFound) {
          // execute callbacks
          executeCallbacks(args, depsNotFound);
        });
        return loadjs;
      };
      /**
       * Manually satisfy bundle dependencies.
       * @param {string} bundleId - The bundle id
       */


      loadjs.done = function done(bundleId) {
        publish(bundleId, []);
      };
      /**
       * Reset loadjs dependencies statuses
       */


      loadjs.reset = function reset() {
        bundleIdCache = {};
        bundleResultCache = {};
        bundleCallbackQueue = {};
      };
      /**
       * Determine if bundle has already been defined
       * @param String} bundleId - The bundle id
       */


      loadjs.isDefined = function isDefined(bundleId) {
        return bundleId in bundleIdCache;
      }; // export


      return loadjs;
    });
  });

  // ==========================================================================
  function loadScript(url) {
    return new Promise(function (resolve, reject) {
      loadjs_umd(url, {
        success: resolve,
        error: reject
      });
    });
  }

  function parseId(url) {
    if (is$1.empty(url)) {
      return null;
    }

    if (is$1.number(Number(url))) {
      return url;
    }

    var regex = /^.*(vimeo.com\/|video\/)(\d+).*/;
    return url.match(regex) ? RegExp.$2 : url;
  } // Set playback state and trigger change (only on actual change)


  function assurePlaybackState(play) {
    if (play && !this.embed.hasPlayed) {
      this.embed.hasPlayed = true;
    }

    if (this.media.paused === play) {
      this.media.paused = !play;
      triggerEvent.call(this, this.media, play ? 'play' : 'pause');
    }
  }

  var vimeo = {
    setup: function setup() {
      var _this = this;

      // Add embed class for responsive
      toggleClass(this.elements.wrapper, this.config.classNames.embed, true); // Set intial ratio

      setAspectRatio.call(this); // Load the SDK if not already

      if (!is$1.object(window.Vimeo)) {
        loadScript(this.config.urls.vimeo.sdk).then(function () {
          vimeo.ready.call(_this);
        }).catch(function (error) {
          _this.debug.warn('Vimeo SDK (player.js) failed to load', error);
        });
      } else {
        vimeo.ready.call(this);
      }
    },
    // API Ready
    ready: function ready() {
      var _this2 = this;

      var player = this;
      var config = player.config.vimeo; // Get Vimeo params for the iframe

      var params = buildUrlParams(extend({}, {
        loop: player.config.loop.active,
        autoplay: player.autoplay,
        muted: player.muted,
        gesture: 'media',
        playsinline: !this.config.fullscreen.iosNative
      }, config)); // Get the source URL or ID

      var source = player.media.getAttribute('src'); // Get from <div> if needed

      if (is$1.empty(source)) {
        source = player.media.getAttribute(player.config.attributes.embed.id);
      }

      var id = parseId(source); // Build an iframe

      var iframe = createElement('iframe');
      var src = format(player.config.urls.vimeo.iframe, id, params);
      iframe.setAttribute('src', src);
      iframe.setAttribute('allowfullscreen', '');
      iframe.setAttribute('allowtransparency', '');
      iframe.setAttribute('allow', 'autoplay'); // Get poster, if already set

      var poster = player.poster; // Inject the package

      var wrapper = createElement('div', {
        poster: poster,
        class: player.config.classNames.embedContainer
      });
      wrapper.appendChild(iframe);
      player.media = replaceElement(wrapper, player.media); // Get poster image

      fetch(format(player.config.urls.vimeo.api, id), 'json').then(function (response) {
        if (is$1.empty(response)) {
          return;
        } // Get the URL for thumbnail


        var url = new URL(response[0].thumbnail_large); // Get original image

        url.pathname = "".concat(url.pathname.split('_')[0], ".jpg"); // Set and show poster

        ui.setPoster.call(player, url.href).catch(function () {});
      }); // Setup instance
      // https://github.com/vimeo/player.js

      player.embed = new window.Vimeo.Player(iframe, {
        autopause: player.config.autopause,
        muted: player.muted
      });
      player.media.paused = true;
      player.media.currentTime = 0; // Disable native text track rendering

      if (player.supported.ui) {
        player.embed.disableTextTrack();
      } // Create a faux HTML5 API using the Vimeo API


      player.media.play = function () {
        assurePlaybackState.call(player, true);
        return player.embed.play();
      };

      player.media.pause = function () {
        assurePlaybackState.call(player, false);
        return player.embed.pause();
      };

      player.media.stop = function () {
        player.pause();
        player.currentTime = 0;
      }; // Seeking


      var currentTime = player.media.currentTime;
      Object.defineProperty(player.media, 'currentTime', {
        get: function get() {
          return currentTime;
        },
        set: function set(time) {
          // Vimeo will automatically play on seek if the video hasn't been played before
          // Get current paused state and volume etc
          var embed = player.embed,
              media = player.media,
              paused = player.paused,
              volume = player.volume;
          var restorePause = paused && !embed.hasPlayed; // Set seeking state and trigger event

          media.seeking = true;
          triggerEvent.call(player, media, 'seeking'); // If paused, mute until seek is complete

          Promise.resolve(restorePause && embed.setVolume(0)) // Seek
          .then(function () {
            return embed.setCurrentTime(time);
          }) // Restore paused
          .then(function () {
            return restorePause && embed.pause();
          }) // Restore volume
          .then(function () {
            return restorePause && embed.setVolume(volume);
          }).catch(function () {// Do nothing
          });
        }
      }); // Playback speed

      var speed = player.config.speed.selected;
      Object.defineProperty(player.media, 'playbackRate', {
        get: function get() {
          return speed;
        },
        set: function set(input) {
          player.embed.setPlaybackRate(input).then(function () {
            speed = input;
            triggerEvent.call(player, player.media, 'ratechange');
          }).catch(function (error) {
            // Hide menu item (and menu if empty)
            if (error.name === 'Error') {
              controls.setSpeedMenu.call(player, []);
            }
          });
        }
      }); // Volume

      var volume = player.config.volume;
      Object.defineProperty(player.media, 'volume', {
        get: function get() {
          return volume;
        },
        set: function set(input) {
          player.embed.setVolume(input).then(function () {
            volume = input;
            triggerEvent.call(player, player.media, 'volumechange');
          });
        }
      }); // Muted

      var muted = player.config.muted;
      Object.defineProperty(player.media, 'muted', {
        get: function get() {
          return muted;
        },
        set: function set(input) {
          var toggle = is$1.boolean(input) ? input : false;
          player.embed.setVolume(toggle ? 0 : player.config.volume).then(function () {
            muted = toggle;
            triggerEvent.call(player, player.media, 'volumechange');
          });
        }
      }); // Loop

      var loop = player.config.loop;
      Object.defineProperty(player.media, 'loop', {
        get: function get() {
          return loop;
        },
        set: function set(input) {
          var toggle = is$1.boolean(input) ? input : player.config.loop.active;
          player.embed.setLoop(toggle).then(function () {
            loop = toggle;
          });
        }
      }); // Source

      var currentSrc;
      player.embed.getVideoUrl().then(function (value) {
        currentSrc = value;
        controls.setDownloadUrl.call(player);
      }).catch(function (error) {
        _this2.debug.warn(error);
      });
      Object.defineProperty(player.media, 'currentSrc', {
        get: function get() {
          return currentSrc;
        }
      }); // Ended

      Object.defineProperty(player.media, 'ended', {
        get: function get() {
          return player.currentTime === player.duration;
        }
      }); // Set aspect ratio based on video size

      Promise.all([player.embed.getVideoWidth(), player.embed.getVideoHeight()]).then(function (dimensions) {
        var _dimensions = _slicedToArray(dimensions, 2),
            width = _dimensions[0],
            height = _dimensions[1];

        player.embed.ratio = [width, height];
        setAspectRatio.call(_this2);
      }); // Set autopause

      player.embed.setAutopause(player.config.autopause).then(function (state) {
        player.config.autopause = state;
      }); // Get title

      player.embed.getVideoTitle().then(function (title) {
        player.config.title = title;
        ui.setTitle.call(_this2);
      }); // Get current time

      player.embed.getCurrentTime().then(function (value) {
        currentTime = value;
        triggerEvent.call(player, player.media, 'timeupdate');
      }); // Get duration

      player.embed.getDuration().then(function (value) {
        player.media.duration = value;
        triggerEvent.call(player, player.media, 'durationchange');
      }); // Get captions

      player.embed.getTextTracks().then(function (tracks) {
        player.media.textTracks = tracks;
        captions.setup.call(player);
      });
      player.embed.on('cuechange', function (_ref) {
        var _ref$cues = _ref.cues,
            cues = _ref$cues === void 0 ? [] : _ref$cues;
        var strippedCues = cues.map(function (cue) {
          return stripHTML(cue.text);
        });
        captions.updateCues.call(player, strippedCues);
      });
      player.embed.on('loaded', function () {
        // Assure state and events are updated on autoplay
        player.embed.getPaused().then(function (paused) {
          assurePlaybackState.call(player, !paused);

          if (!paused) {
            triggerEvent.call(player, player.media, 'playing');
          }
        });

        if (is$1.element(player.embed.element) && player.supported.ui) {
          var frame = player.embed.element; // Fix keyboard focus issues
          // https://github.com/sampotts/plyr/issues/317

          frame.setAttribute('tabindex', -1);
        }
      });
      player.embed.on('play', function () {
        assurePlaybackState.call(player, true);
        triggerEvent.call(player, player.media, 'playing');
      });
      player.embed.on('pause', function () {
        assurePlaybackState.call(player, false);
      });
      player.embed.on('timeupdate', function (data) {
        player.media.seeking = false;
        currentTime = data.seconds;
        triggerEvent.call(player, player.media, 'timeupdate');
      });
      player.embed.on('progress', function (data) {
        player.media.buffered = data.percent;
        triggerEvent.call(player, player.media, 'progress'); // Check all loaded

        if (parseInt(data.percent, 10) === 1) {
          triggerEvent.call(player, player.media, 'canplaythrough');
        } // Get duration as if we do it before load, it gives an incorrect value
        // https://github.com/sampotts/plyr/issues/891


        player.embed.getDuration().then(function (value) {
          if (value !== player.media.duration) {
            player.media.duration = value;
            triggerEvent.call(player, player.media, 'durationchange');
          }
        });
      });
      player.embed.on('seeked', function () {
        player.media.seeking = false;
        triggerEvent.call(player, player.media, 'seeked');
      });
      player.embed.on('ended', function () {
        player.media.paused = true;
        triggerEvent.call(player, player.media, 'ended');
      });
      player.embed.on('error', function (detail) {
        player.media.error = detail;
        triggerEvent.call(player, player.media, 'error');
      }); // Rebuild UI

      setTimeout(function () {
        return ui.build.call(player);
      }, 0);
    }
  };

  // ==========================================================================

  function parseId$1(url) {
    if (is$1.empty(url)) {
      return null;
    }

    var regex = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    return url.match(regex) ? RegExp.$2 : url;
  } // Set playback state and trigger change (only on actual change)


  function assurePlaybackState$1(play) {
    if (play && !this.embed.hasPlayed) {
      this.embed.hasPlayed = true;
    }

    if (this.media.paused === play) {
      this.media.paused = !play;
      triggerEvent.call(this, this.media, play ? 'play' : 'pause');
    }
  }

  function getHost(config) {
    if (config.noCookie) {
      return 'https://www.youtube-nocookie.com';
    }

    if (window.location.protocol === 'http:') {
      return 'http://www.youtube.com';
    } // Use YouTube's default


    return undefined;
  }

  var youtube = {
    setup: function setup() {
      var _this = this;

      // Add embed class for responsive
      toggleClass(this.elements.wrapper, this.config.classNames.embed, true); // Setup API

      if (is$1.object(window.YT) && is$1.function(window.YT.Player)) {
        youtube.ready.call(this);
      } else {
        // Reference current global callback
        var callback = window.onYouTubeIframeAPIReady; // Set callback to process queue

        window.onYouTubeIframeAPIReady = function () {
          // Call global callback if set
          if (is$1.function(callback)) {
            callback();
          }

          youtube.ready.call(_this);
        }; // Load the SDK


        loadScript(this.config.urls.youtube.sdk).catch(function (error) {
          _this.debug.warn('YouTube API failed to load', error);
        });
      }
    },
    // Get the media title
    getTitle: function getTitle(videoId) {
      var _this2 = this;

      var url = format(this.config.urls.youtube.api, videoId);
      fetch(url).then(function (data) {
        if (is$1.object(data)) {
          var title = data.title,
              height = data.height,
              width = data.width; // Set title

          _this2.config.title = title;
          ui.setTitle.call(_this2); // Set aspect ratio

          _this2.embed.ratio = [width, height];
        }

        setAspectRatio.call(_this2);
      }).catch(function () {
        // Set aspect ratio
        setAspectRatio.call(_this2);
      });
    },
    // API ready
    ready: function ready() {
      var player = this; // Ignore already setup (race condition)

      var currentId = player.media && player.media.getAttribute('id');

      if (!is$1.empty(currentId) && currentId.startsWith('youtube-')) {
        return;
      } // Get the source URL or ID


      var source = player.media.getAttribute('src'); // Get from <div> if needed

      if (is$1.empty(source)) {
        source = player.media.getAttribute(this.config.attributes.embed.id);
      } // Replace the <iframe> with a <div> due to YouTube API issues


      var videoId = parseId$1(source);
      var id = generateId(player.provider); // Get poster, if already set

      var poster = player.poster; // Replace media element

      var container = createElement('div', {
        id: id,
        poster: poster
      });
      player.media = replaceElement(container, player.media); // Id to poster wrapper

      var posterSrc = function posterSrc(s) {
        return "https://i.ytimg.com/vi/".concat(videoId, "/").concat(s, "default.jpg");
      }; // Check thumbnail images in order of quality, but reject fallback thumbnails (120px wide)


      loadImage(posterSrc('maxres'), 121) // Higest quality and unpadded
      .catch(function () {
        return loadImage(posterSrc('sd'), 121);
      }) // 480p padded 4:3
      .catch(function () {
        return loadImage(posterSrc('hq'));
      }) // 360p padded 4:3. Always exists
      .then(function (image) {
        return ui.setPoster.call(player, image.src);
      }).then(function (src) {
        // If the image is padded, use background-size "cover" instead (like youtube does too with their posters)
        if (!src.includes('maxres')) {
          player.elements.poster.style.backgroundSize = 'cover';
        }
      }).catch(function () {});
      var config = player.config.youtube; // Setup instance
      // https://developers.google.com/youtube/iframe_api_reference

      player.embed = new window.YT.Player(id, {
        videoId: videoId,
        host: getHost(config),
        playerVars: extend({}, {
          autoplay: player.config.autoplay ? 1 : 0,
          // Autoplay
          hl: player.config.hl,
          // iframe interface language
          controls: player.supported.ui ? 0 : 1,
          // Only show controls if not fully supported
          disablekb: 1,
          // Disable keyboard as we handle it
          playsinline: !player.config.fullscreen.iosNative ? 1 : 0,
          // Allow iOS inline playback
          // Captions are flaky on YouTube
          cc_load_policy: player.captions.active ? 1 : 0,
          cc_lang_pref: player.config.captions.language,
          // Tracking for stats
          widget_referrer: window ? window.location.href : null
        }, config),
        events: {
          onError: function onError(event) {
            // YouTube may fire onError twice, so only handle it once
            if (!player.media.error) {
              var code = event.data; // Messages copied from https://developers.google.com/youtube/iframe_api_reference#onError

              var message = {
                2: 'The request contains an invalid parameter value. For example, this error occurs if you specify a video ID that does not have 11 characters, or if the video ID contains invalid characters, such as exclamation points or asterisks.',
                5: 'The requested content cannot be played in an HTML5 player or another error related to the HTML5 player has occurred.',
                100: 'The video requested was not found. This error occurs when a video has been removed (for any reason) or has been marked as private.',
                101: 'The owner of the requested video does not allow it to be played in embedded players.',
                150: 'The owner of the requested video does not allow it to be played in embedded players.'
              }[code] || 'An unknown error occured';
              player.media.error = {
                code: code,
                message: message
              };
              triggerEvent.call(player, player.media, 'error');
            }
          },
          onPlaybackRateChange: function onPlaybackRateChange(event) {
            // Get the instance
            var instance = event.target; // Get current speed

            player.media.playbackRate = instance.getPlaybackRate();
            triggerEvent.call(player, player.media, 'ratechange');
          },
          onReady: function onReady(event) {
            // Bail if onReady has already been called. See issue #1108
            if (is$1.function(player.media.play)) {
              return;
            } // Get the instance


            var instance = event.target; // Get the title

            youtube.getTitle.call(player, videoId); // Create a faux HTML5 API using the YouTube API

            player.media.play = function () {
              assurePlaybackState$1.call(player, true);
              instance.playVideo();
            };

            player.media.pause = function () {
              assurePlaybackState$1.call(player, false);
              instance.pauseVideo();
            };

            player.media.stop = function () {
              instance.stopVideo();
            };

            player.media.duration = instance.getDuration();
            player.media.paused = true; // Seeking

            player.media.currentTime = 0;
            Object.defineProperty(player.media, 'currentTime', {
              get: function get() {
                return Number(instance.getCurrentTime());
              },
              set: function set(time) {
                // If paused and never played, mute audio preventively (YouTube starts playing on seek if the video hasn't been played yet).
                if (player.paused && !player.embed.hasPlayed) {
                  player.embed.mute();
                } // Set seeking state and trigger event


                player.media.seeking = true;
                triggerEvent.call(player, player.media, 'seeking'); // Seek after events sent

                instance.seekTo(time);
              }
            }); // Playback speed

            Object.defineProperty(player.media, 'playbackRate', {
              get: function get() {
                return instance.getPlaybackRate();
              },
              set: function set(input) {
                instance.setPlaybackRate(input);
              }
            }); // Volume

            var volume = player.config.volume;
            Object.defineProperty(player.media, 'volume', {
              get: function get() {
                return volume;
              },
              set: function set(input) {
                volume = input;
                instance.setVolume(volume * 100);
                triggerEvent.call(player, player.media, 'volumechange');
              }
            }); // Muted

            var muted = player.config.muted;
            Object.defineProperty(player.media, 'muted', {
              get: function get() {
                return muted;
              },
              set: function set(input) {
                var toggle = is$1.boolean(input) ? input : muted;
                muted = toggle;
                instance[toggle ? 'mute' : 'unMute']();
                triggerEvent.call(player, player.media, 'volumechange');
              }
            }); // Source

            Object.defineProperty(player.media, 'currentSrc', {
              get: function get() {
                return instance.getVideoUrl();
              }
            }); // Ended

            Object.defineProperty(player.media, 'ended', {
              get: function get() {
                return player.currentTime === player.duration;
              }
            }); // Get available speeds

            player.options.speed = instance.getAvailablePlaybackRates(); // Set the tabindex to avoid focus entering iframe

            if (player.supported.ui) {
              player.media.setAttribute('tabindex', -1);
            }

            triggerEvent.call(player, player.media, 'timeupdate');
            triggerEvent.call(player, player.media, 'durationchange'); // Reset timer

            clearInterval(player.timers.buffering); // Setup buffering

            player.timers.buffering = setInterval(function () {
              // Get loaded % from YouTube
              player.media.buffered = instance.getVideoLoadedFraction(); // Trigger progress only when we actually buffer something

              if (player.media.lastBuffered === null || player.media.lastBuffered < player.media.buffered) {
                triggerEvent.call(player, player.media, 'progress');
              } // Set last buffer point


              player.media.lastBuffered = player.media.buffered; // Bail if we're at 100%

              if (player.media.buffered === 1) {
                clearInterval(player.timers.buffering); // Trigger event

                triggerEvent.call(player, player.media, 'canplaythrough');
              }
            }, 200); // Rebuild UI

            setTimeout(function () {
              return ui.build.call(player);
            }, 50);
          },
          onStateChange: function onStateChange(event) {
            // Get the instance
            var instance = event.target; // Reset timer

            clearInterval(player.timers.playing);
            var seeked = player.media.seeking && [1, 2].includes(event.data);

            if (seeked) {
              // Unset seeking and fire seeked event
              player.media.seeking = false;
              triggerEvent.call(player, player.media, 'seeked');
            } // Handle events
            // -1   Unstarted
            // 0    Ended
            // 1    Playing
            // 2    Paused
            // 3    Buffering
            // 5    Video cued


            switch (event.data) {
              case -1:
                // Update scrubber
                triggerEvent.call(player, player.media, 'timeupdate'); // Get loaded % from YouTube

                player.media.buffered = instance.getVideoLoadedFraction();
                triggerEvent.call(player, player.media, 'progress');
                break;

              case 0:
                assurePlaybackState$1.call(player, false); // YouTube doesn't support loop for a single video, so mimick it.

                if (player.media.loop) {
                  // YouTube needs a call to `stopVideo` before playing again
                  instance.stopVideo();
                  instance.playVideo();
                } else {
                  triggerEvent.call(player, player.media, 'ended');
                }

                break;

              case 1:
                // Restore paused state (YouTube starts playing on seek if the video hasn't been played yet)
                if (!player.config.autoplay && player.media.paused && !player.embed.hasPlayed) {
                  player.media.pause();
                } else {
                  assurePlaybackState$1.call(player, true);
                  triggerEvent.call(player, player.media, 'playing'); // Poll to get playback progress

                  player.timers.playing = setInterval(function () {
                    triggerEvent.call(player, player.media, 'timeupdate');
                  }, 50); // Check duration again due to YouTube bug
                  // https://github.com/sampotts/plyr/issues/374
                  // https://code.google.com/p/gdata-issues/issues/detail?id=8690

                  if (player.media.duration !== instance.getDuration()) {
                    player.media.duration = instance.getDuration();
                    triggerEvent.call(player, player.media, 'durationchange');
                  }
                }

                break;

              case 2:
                // Restore audio (YouTube starts playing on seek if the video hasn't been played yet)
                if (!player.muted) {
                  player.embed.unMute();
                }

                assurePlaybackState$1.call(player, false);
                break;

              default:
                break;
            }

            triggerEvent.call(player, player.elements.container, 'statechange', false, {
              code: event.data
            });
          }
        }
      });
    }
  };

  // ==========================================================================
  var media = {
    // Setup media
    setup: function setup() {
      // If there's no media, bail
      if (!this.media) {
        this.debug.warn('No media element found!');
        return;
      } // Add type class


      toggleClass(this.elements.container, this.config.classNames.type.replace('{0}', this.type), true); // Add provider class

      toggleClass(this.elements.container, this.config.classNames.provider.replace('{0}', this.provider), true); // Add video class for embeds
      // This will require changes if audio embeds are added

      if (this.isEmbed) {
        toggleClass(this.elements.container, this.config.classNames.type.replace('{0}', 'video'), true);
      } // Inject the player wrapper


      if (this.isVideo) {
        // Create the wrapper div
        this.elements.wrapper = createElement('div', {
          class: this.config.classNames.video
        }); // Wrap the video in a container

        wrap(this.media, this.elements.wrapper); // Faux poster container

        this.elements.poster = createElement('div', {
          class: this.config.classNames.poster
        });
        this.elements.wrapper.appendChild(this.elements.poster);
      }

      if (this.isHTML5) {
        html5.extend.call(this);
      } else if (this.isYouTube) {
        youtube.setup.call(this);
      } else if (this.isVimeo) {
        vimeo.setup.call(this);
      }
    }
  };

  var destroy = function destroy(instance) {
    // Destroy our adsManager
    if (instance.manager) {
      instance.manager.destroy();
    } // Destroy our adsManager


    if (instance.elements.displayContainer) {
      instance.elements.displayContainer.destroy();
    }

    instance.elements.container.remove();
  };

  var Ads =
  /*#__PURE__*/
  function () {
    /**
     * Ads constructor.
     * @param {Object} player
     * @return {Ads}
     */
    function Ads(player) {
      var _this = this;

      _classCallCheck(this, Ads);

      this.player = player;
      this.config = player.config.ads;
      this.playing = false;
      this.initialized = false;
      this.elements = {
        container: null,
        displayContainer: null
      };
      this.manager = null;
      this.loader = null;
      this.cuePoints = null;
      this.events = {};
      this.safetyTimer = null;
      this.countdownTimer = null; // Setup a promise to resolve when the IMA manager is ready

      this.managerPromise = new Promise(function (resolve, reject) {
        // The ad is loaded and ready
        _this.on('loaded', resolve); // Ads failed


        _this.on('error', reject);
      });
      this.load();
    }

    _createClass(Ads, [{
      key: "load",

      /**
       * Load the IMA SDK
       */
      value: function load() {
        var _this2 = this;

        if (!this.enabled) {
          return;
        } // Check if the Google IMA3 SDK is loaded or load it ourselves


        if (!is$1.object(window.google) || !is$1.object(window.google.ima)) {
          loadScript(this.player.config.urls.googleIMA.sdk).then(function () {
            _this2.ready();
          }).catch(function () {
            // Script failed to load or is blocked
            _this2.trigger('error', new Error('Google IMA SDK failed to load'));
          });
        } else {
          this.ready();
        }
      }
      /**
       * Get the ads instance ready
       */

    }, {
      key: "ready",
      value: function ready() {
        var _this3 = this;

        // Double check we're enabled
        if (!this.enabled) {
          destroy(this);
        } // Start ticking our safety timer. If the whole advertisement
        // thing doesn't resolve within our set time; we bail


        this.startSafetyTimer(12000, 'ready()'); // Clear the safety timer

        this.managerPromise.then(function () {
          _this3.clearSafetyTimer('onAdsManagerLoaded()');
        }); // Set listeners on the Plyr instance

        this.listeners(); // Setup the IMA SDK

        this.setupIMA();
      } // Build the tag URL

    }, {
      key: "setupIMA",

      /**
       * In order for the SDK to display ads for our video, we need to tell it where to put them,
       * so here we define our ad container. This div is set up to render on top of the video player.
       * Using the code below, we tell the SDK to render ads within that div. We also provide a
       * handle to the content video player - the SDK will poll the current time of our player to
       * properly place mid-rolls. After we create the ad display container, we initialize it. On
       * mobile devices, this initialization is done as the result of a user action.
       */
      value: function setupIMA() {
        // Create the container for our advertisements
        this.elements.container = createElement('div', {
          class: this.player.config.classNames.ads
        });
        this.player.elements.container.appendChild(this.elements.container); // So we can run VPAID2

        google.ima.settings.setVpaidMode(google.ima.ImaSdkSettings.VpaidMode.ENABLED); // Set language

        google.ima.settings.setLocale(this.player.config.ads.language); // Set playback for iOS10+

        google.ima.settings.setDisableCustomPlaybackForIOS10Plus(this.player.config.playsinline); // We assume the adContainer is the video container of the plyr element that will house the ads

        this.elements.displayContainer = new google.ima.AdDisplayContainer(this.elements.container, this.player.media); // Request video ads to be pre-loaded

        this.requestAds();
      }
      /**
       * Request advertisements
       */

    }, {
      key: "requestAds",
      value: function requestAds() {
        var _this4 = this;

        var container = this.player.elements.container;

        try {
          // Create ads loader
          this.loader = new google.ima.AdsLoader(this.elements.displayContainer); // Listen and respond to ads loaded and error events

          this.loader.addEventListener(google.ima.AdsManagerLoadedEvent.Type.ADS_MANAGER_LOADED, function (event) {
            return _this4.onAdsManagerLoaded(event);
          }, false);
          this.loader.addEventListener(google.ima.AdErrorEvent.Type.AD_ERROR, function (error) {
            return _this4.onAdError(error);
          }, false); // Request video ads

          var request = new google.ima.AdsRequest();
          request.adTagUrl = this.tagUrl; // Specify the linear and nonlinear slot sizes. This helps the SDK
          // to select the correct creative if multiple are returned

          request.linearAdSlotWidth = container.offsetWidth;
          request.linearAdSlotHeight = container.offsetHeight;
          request.nonLinearAdSlotWidth = container.offsetWidth;
          request.nonLinearAdSlotHeight = container.offsetHeight; // We only overlay ads as we only support video.

          request.forceNonLinearFullSlot = false; // Mute based on current state

          request.setAdWillPlayMuted(!this.player.muted);
          this.loader.requestAds(request);
        } catch (e) {
          this.onAdError(e);
        }
      }
      /**
       * Update the ad countdown
       * @param {Boolean} start
       */

    }, {
      key: "pollCountdown",
      value: function pollCountdown() {
        var _this5 = this;

        var start = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

        if (!start) {
          clearInterval(this.countdownTimer);
          this.elements.container.removeAttribute('data-badge-text');
          return;
        }

        var update = function update() {
          var time = formatTime(Math.max(_this5.manager.getRemainingTime(), 0));
          var label = "".concat(i18n.get('advertisement', _this5.player.config), " - ").concat(time);

          _this5.elements.container.setAttribute('data-badge-text', label);
        };

        this.countdownTimer = setInterval(update, 100);
      }
      /**
       * This method is called whenever the ads are ready inside the AdDisplayContainer
       * @param {Event} adsManagerLoadedEvent
       */

    }, {
      key: "onAdsManagerLoaded",
      value: function onAdsManagerLoaded(event) {
        var _this6 = this;

        // Load could occur after a source change (race condition)
        if (!this.enabled) {
          return;
        } // Get the ads manager


        var settings = new google.ima.AdsRenderingSettings(); // Tell the SDK to save and restore content video state on our behalf

        settings.restoreCustomPlaybackStateOnAdBreakComplete = true;
        settings.enablePreloading = true; // The SDK is polling currentTime on the contentPlayback. And needs a duration
        // so it can determine when to start the mid- and post-roll

        this.manager = event.getAdsManager(this.player, settings); // Get the cue points for any mid-rolls by filtering out the pre- and post-roll

        this.cuePoints = this.manager.getCuePoints(); // Add listeners to the required events
        // Advertisement error events

        this.manager.addEventListener(google.ima.AdErrorEvent.Type.AD_ERROR, function (error) {
          return _this6.onAdError(error);
        }); // Advertisement regular events

        Object.keys(google.ima.AdEvent.Type).forEach(function (type) {
          _this6.manager.addEventListener(google.ima.AdEvent.Type[type], function (e) {
            return _this6.onAdEvent(e);
          });
        }); // Resolve our adsManager

        this.trigger('loaded');
      }
    }, {
      key: "addCuePoints",
      value: function addCuePoints() {
        var _this7 = this;

        // Add advertisement cue's within the time line if available
        if (!is$1.empty(this.cuePoints)) {
          this.cuePoints.forEach(function (cuePoint) {
            if (cuePoint !== 0 && cuePoint !== -1 && cuePoint < _this7.player.duration) {
              var seekElement = _this7.player.elements.progress;

              if (is$1.element(seekElement)) {
                var cuePercentage = 100 / _this7.player.duration * cuePoint;
                var cue = createElement('span', {
                  class: _this7.player.config.classNames.cues
                });
                cue.style.left = "".concat(cuePercentage.toString(), "%");
                seekElement.appendChild(cue);
              }
            }
          });
        }
      }
      /**
       * This is where all the event handling takes place. Retrieve the ad from the event. Some
       * events (e.g. ALL_ADS_COMPLETED) don't have the ad object associated
       * https://developers.google.com/interactive-media-ads/docs/sdks/html5/v3/apis#ima.AdEvent.Type
       * @param {Event} event
       */

    }, {
      key: "onAdEvent",
      value: function onAdEvent(event) {
        var _this8 = this;

        var container = this.player.elements.container; // Retrieve the ad from the event. Some events (e.g. ALL_ADS_COMPLETED)
        // don't have ad object associated

        var ad = event.getAd();
        var adData = event.getAdData(); // Proxy event

        var dispatchEvent = function dispatchEvent(type) {
          triggerEvent.call(_this8.player, _this8.player.media, "ads".concat(type.replace(/_/g, '').toLowerCase()));
        }; // Bubble the event


        dispatchEvent(event.type);

        switch (event.type) {
          case google.ima.AdEvent.Type.LOADED:
            // This is the first event sent for an ad - it is possible to determine whether the
            // ad is a video ad or an overlay
            this.trigger('loaded'); // Start countdown

            this.pollCountdown(true);

            if (!ad.isLinear()) {
              // Position AdDisplayContainer correctly for overlay
              ad.width = container.offsetWidth;
              ad.height = container.offsetHeight;
            } // console.info('Ad type: ' + event.getAd().getAdPodInfo().getPodIndex());
            // console.info('Ad time: ' + event.getAd().getAdPodInfo().getTimeOffset());


            break;

          case google.ima.AdEvent.Type.STARTED:
            // Set volume to match player
            this.manager.setVolume(this.player.volume);
            break;

          case google.ima.AdEvent.Type.ALL_ADS_COMPLETED:
            // All ads for the current videos are done. We can now request new advertisements
            // in case the video is re-played
            // TODO: Example for what happens when a next video in a playlist would be loaded.
            // So here we load a new video when all ads are done.
            // Then we load new ads within a new adsManager. When the video
            // Is started - after - the ads are loaded, then we get ads.
            // You can also easily test cancelling and reloading by running
            // player.ads.cancel() and player.ads.play from the console I guess.
            // this.player.source = {
            //     type: 'video',
            //     title: 'View From A Blue Moon',
            //     sources: [{
            //         src:
            // 'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.mp4', type:
            // 'video/mp4', }], poster:
            // 'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg', tracks:
            // [ { kind: 'captions', label: 'English', srclang: 'en', src:
            // 'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.en.vtt',
            // default: true, }, { kind: 'captions', label: 'French', srclang: 'fr', src:
            // 'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.fr.vtt', }, ],
            // };
            // TODO: So there is still this thing where a video should only be allowed to start
            // playing when the IMA SDK is ready or has failed
            this.loadAds();
            break;

          case google.ima.AdEvent.Type.CONTENT_PAUSE_REQUESTED:
            // This event indicates the ad has started - the video player can adjust the UI,
            // for example display a pause button and remaining time. Fired when content should
            // be paused. This usually happens right before an ad is about to cover the content
            this.pauseContent();
            break;

          case google.ima.AdEvent.Type.CONTENT_RESUME_REQUESTED:
            // This event indicates the ad has finished - the video player can perform
            // appropriate UI actions, such as removing the timer for remaining time detection.
            // Fired when content should be resumed. This usually happens when an ad finishes
            // or collapses
            this.pollCountdown();
            this.resumeContent();
            break;

          case google.ima.AdEvent.Type.LOG:
            if (adData.adError) {
              this.player.debug.warn("Non-fatal ad error: ".concat(adData.adError.getMessage()));
            }

            break;

          default:
            break;
        }
      }
      /**
       * Any ad error handling comes through here
       * @param {Event} event
       */

    }, {
      key: "onAdError",
      value: function onAdError(event) {
        this.cancel();
        this.player.debug.warn('Ads error', event);
      }
      /**
       * Setup hooks for Plyr and window events. This ensures
       * the mid- and post-roll launch at the correct time. And
       * resize the advertisement when the player resizes
       */

    }, {
      key: "listeners",
      value: function listeners() {
        var _this9 = this;

        var container = this.player.elements.container;
        var time;
        this.player.on('canplay', function () {
          _this9.addCuePoints();
        });
        this.player.on('ended', function () {
          _this9.loader.contentComplete();
        });
        this.player.on('timeupdate', function () {
          time = _this9.player.currentTime;
        });
        this.player.on('seeked', function () {
          var seekedTime = _this9.player.currentTime;

          if (is$1.empty(_this9.cuePoints)) {
            return;
          }

          _this9.cuePoints.forEach(function (cuePoint, index) {
            if (time < cuePoint && cuePoint < seekedTime) {
              _this9.manager.discardAdBreak();

              _this9.cuePoints.splice(index, 1);
            }
          });
        }); // Listen to the resizing of the window. And resize ad accordingly
        // TODO: eventually implement ResizeObserver

        window.addEventListener('resize', function () {
          if (_this9.manager) {
            _this9.manager.resize(container.offsetWidth, container.offsetHeight, google.ima.ViewMode.NORMAL);
          }
        });
      }
      /**
       * Initialize the adsManager and start playing advertisements
       */

    }, {
      key: "play",
      value: function play() {
        var _this10 = this;

        var container = this.player.elements.container;

        if (!this.managerPromise) {
          this.resumeContent();
        } // Play the requested advertisement whenever the adsManager is ready


        this.managerPromise.then(function () {
          // Set volume to match player
          _this10.manager.setVolume(_this10.player.volume); // Initialize the container. Must be done via a user action on mobile devices


          _this10.elements.displayContainer.initialize();

          try {
            if (!_this10.initialized) {
              // Initialize the ads manager. Ad rules playlist will start at this time
              _this10.manager.init(container.offsetWidth, container.offsetHeight, google.ima.ViewMode.NORMAL); // Call play to start showing the ad. Single video and overlay ads will
              // start at this time; the call will be ignored for ad rules


              _this10.manager.start();
            }

            _this10.initialized = true;
          } catch (adError) {
            // An error may be thrown if there was a problem with the
            // VAST response
            _this10.onAdError(adError);
          }
        }).catch(function () {});
      }
      /**
       * Resume our video
       */

    }, {
      key: "resumeContent",
      value: function resumeContent() {
        // Hide the advertisement container
        this.elements.container.style.zIndex = ''; // Ad is stopped

        this.playing = false; // Play video

        this.player.media.play();
      }
      /**
       * Pause our video
       */

    }, {
      key: "pauseContent",
      value: function pauseContent() {
        // Show the advertisement container
        this.elements.container.style.zIndex = 3; // Ad is playing

        this.playing = true; // Pause our video.

        this.player.media.pause();
      }
      /**
       * Destroy the adsManager so we can grab new ads after this. If we don't then we're not
       * allowed to call new ads based on google policies, as they interpret this as an accidental
       * video requests. https://developers.google.com/interactive-
       * media-ads/docs/sdks/android/faq#8
       */

    }, {
      key: "cancel",
      value: function cancel() {
        // Pause our video
        if (this.initialized) {
          this.resumeContent();
        } // Tell our instance that we're done for now


        this.trigger('error'); // Re-create our adsManager

        this.loadAds();
      }
      /**
       * Re-create our adsManager
       */

    }, {
      key: "loadAds",
      value: function loadAds() {
        var _this11 = this;

        // Tell our adsManager to go bye bye
        this.managerPromise.then(function () {
          // Destroy our adsManager
          if (_this11.manager) {
            _this11.manager.destroy();
          } // Re-set our adsManager promises


          _this11.managerPromise = new Promise(function (resolve) {
            _this11.on('loaded', resolve);

            _this11.player.debug.log(_this11.manager);
          }); // Now request some new advertisements

          _this11.requestAds();
        }).catch(function () {});
      }
      /**
       * Handles callbacks after an ad event was invoked
       * @param {String} event - Event type
       */

    }, {
      key: "trigger",
      value: function trigger(event) {
        var _this12 = this;

        for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
          args[_key - 1] = arguments[_key];
        }

        var handlers = this.events[event];

        if (is$1.array(handlers)) {
          handlers.forEach(function (handler) {
            if (is$1.function(handler)) {
              handler.apply(_this12, args);
            }
          });
        }
      }
      /**
       * Add event listeners
       * @param {String} event - Event type
       * @param {Function} callback - Callback for when event occurs
       * @return {Ads}
       */

    }, {
      key: "on",
      value: function on(event, callback) {
        if (!is$1.array(this.events[event])) {
          this.events[event] = [];
        }

        this.events[event].push(callback);
        return this;
      }
      /**
       * Setup a safety timer for when the ad network doesn't respond for whatever reason.
       * The advertisement has 12 seconds to get its things together. We stop this timer when the
       * advertisement is playing, or when a user action is required to start, then we clear the
       * timer on ad ready
       * @param {Number} time
       * @param {String} from
       */

    }, {
      key: "startSafetyTimer",
      value: function startSafetyTimer(time, from) {
        var _this13 = this;

        this.player.debug.log("Safety timer invoked from: ".concat(from));
        this.safetyTimer = setTimeout(function () {
          _this13.cancel();

          _this13.clearSafetyTimer('startSafetyTimer()');
        }, time);
      }
      /**
       * Clear our safety timer(s)
       * @param {String} from
       */

    }, {
      key: "clearSafetyTimer",
      value: function clearSafetyTimer(from) {
        if (!is$1.nullOrUndefined(this.safetyTimer)) {
          this.player.debug.log("Safety timer cleared from: ".concat(from));
          clearTimeout(this.safetyTimer);
          this.safetyTimer = null;
        }
      }
    }, {
      key: "enabled",
      get: function get() {
        var config = this.config;
        return this.player.isHTML5 && this.player.isVideo && config.enabled && (!is$1.empty(config.publisherId) || is$1.url(config.tagUrl));
      }
    }, {
      key: "tagUrl",
      get: function get() {
        var config = this.config;

        if (is$1.url(config.tagUrl)) {
          return config.tagUrl;
        }

        var params = {
          AV_PUBLISHERID: '58c25bb0073ef448b1087ad6',
          AV_CHANNELID: '5a0458dc28a06145e4519d21',
          AV_URL: window.location.hostname,
          cb: Date.now(),
          AV_WIDTH: 640,
          AV_HEIGHT: 480,
          AV_CDIM2: this.publisherId
        };
        var base = 'https://go.aniview.com/api/adserver6/vast/';
        return "".concat(base, "?").concat(buildUrlParams(params));
      }
    }]);

    return Ads;
  }();

  var parseVtt = function parseVtt(vttDataString) {
    var processedList = [];
    var frames = vttDataString.split(/\r\n\r\n|\n\n|\r\r/);
    frames.forEach(function (frame) {
      var result = {};
      var lines = frame.split(/\r\n|\n|\r/);
      lines.forEach(function (line) {
        if (!is$1.number(result.startTime)) {
          // The line with start and end times on it is the first line of interest
          var matchTimes = line.match(/([0-9]{2})?:?([0-9]{2}):([0-9]{2}).([0-9]{2,3})( ?--> ?)([0-9]{2})?:?([0-9]{2}):([0-9]{2}).([0-9]{2,3})/); // Note that this currently ignores caption formatting directives that are optionally on the end of this line - fine for non-captions VTT

          if (matchTimes) {
            result.startTime = Number(matchTimes[1] || 0) * 60 * 60 + Number(matchTimes[2]) * 60 + Number(matchTimes[3]) + Number("0.".concat(matchTimes[4]));
            result.endTime = Number(matchTimes[6] || 0) * 60 * 60 + Number(matchTimes[7]) * 60 + Number(matchTimes[8]) + Number("0.".concat(matchTimes[9]));
          }
        } else if (!is$1.empty(line.trim()) && is$1.empty(result.text)) {
          // If we already have the startTime, then we're definitely up to the text line(s)
          var lineSplit = line.trim().split('#xywh=');

          var _lineSplit = _slicedToArray(lineSplit, 1);

          result.text = _lineSplit[0];

          // If there's content in lineSplit[1], then we have sprites. If not, then it's just one frame per image
          if (lineSplit[1]) {
            var _lineSplit$1$split = lineSplit[1].split(',');

            var _lineSplit$1$split2 = _slicedToArray(_lineSplit$1$split, 4);

            result.x = _lineSplit$1$split2[0];
            result.y = _lineSplit$1$split2[1];
            result.w = _lineSplit$1$split2[2];
            result.h = _lineSplit$1$split2[3];
          }
        }
      });

      if (result.text) {
        processedList.push(result);
      }
    });
    return processedList;
  };
  /**
   * Preview thumbnails for seek hover and scrubbing
   * Seeking: Hover over the seek bar (desktop only): shows a small preview container above the seek bar
   * Scrubbing: Click and drag the seek bar (desktop and mobile): shows the preview image over the entire video, as if the video is scrubbing at very high speed
   *
   * Notes:
   * - Thumbs are set via JS settings on Plyr init, not HTML5 'track' property. Using the track property would be a bit gross, because it doesn't support custom 'kinds'. kind=metadata might be used for something else, and we want to allow multiple thumbnails tracks. Tracks must have a unique combination of 'kind' and 'label'. We would have to do something like kind=metadata,label=thumbnails1 / kind=metadata,label=thumbnails2. Square peg, round hole
   * - VTT info: the image URL is relative to the VTT, not the current document. But if the url starts with a slash, it will naturally be relative to the current domain. https://support.jwplayer.com/articles/how-to-add-preview-thumbnails
   * - This implementation uses multiple separate img elements. Other implementations use background-image on one element. This would be nice and simple, but Firefox and Safari have flickering issues with replacing backgrounds of larger images. It seems that YouTube perhaps only avoids this because they don't have the option for high-res previews (even the fullscreen ones, when mousedown/seeking). Images appear over the top of each other, and previous ones are discarded once the new ones have been rendered
   */


  var PreviewThumbnails =
  /*#__PURE__*/
  function () {
    /**
     * PreviewThumbnails constructor.
     * @param {Plyr} player
     * @return {PreviewThumbnails}
     */
    function PreviewThumbnails(player) {
      _classCallCheck(this, PreviewThumbnails);

      this.player = player;
      this.thumbnails = [];
      this.loaded = false;
      this.lastMouseMoveTime = Date.now();
      this.mouseDown = false;
      this.loadedImages = [];
      this.elements = {
        thumb: {},
        scrubbing: {}
      };
      this.load();
    }

    _createClass(PreviewThumbnails, [{
      key: "load",
      value: function load() {
        var _this = this;

        // Togglethe regular seek tooltip
        if (this.player.elements.display.seekTooltip) {
          this.player.elements.display.seekTooltip.hidden = this.enabled;
        }

        if (!this.enabled) {
          return;
        }

        this.getThumbnails().then(function () {
          if (!_this.enabled) {
            return;
          } // Render DOM elements


          _this.render(); // Check to see if thumb container size was specified manually in CSS


          _this.determineContainerAutoSizing();

          _this.loaded = true;
        });
      } // Download VTT files and parse them

    }, {
      key: "getThumbnails",
      value: function getThumbnails() {
        var _this2 = this;

        return new Promise(function (resolve) {
          var src = _this2.player.config.previewThumbnails.src;

          if (is$1.empty(src)) {
            throw new Error('Missing previewThumbnails.src config attribute');
          } // If string, convert into single-element list


          var urls = is$1.string(src) ? [src] : src; // Loop through each src URL. Download and process the VTT file, storing the resulting data in this.thumbnails

          var promises = urls.map(function (u) {
            return _this2.getThumbnail(u);
          });
          Promise.all(promises).then(function () {
            // Sort smallest to biggest (e.g., [120p, 480p, 1080p])
            _this2.thumbnails.sort(function (x, y) {
              return x.height - y.height;
            });

            _this2.player.debug.log('Preview thumbnails', _this2.thumbnails);

            resolve();
          });
        });
      } // Process individual VTT file

    }, {
      key: "getThumbnail",
      value: function getThumbnail(url) {
        var _this3 = this;

        return new Promise(function (resolve) {
          fetch(url).then(function (response) {
            var thumbnail = {
              frames: parseVtt(response),
              height: null,
              urlPrefix: ''
            }; // If the URLs don't start with '/', then we need to set their relative path to be the location of the VTT file
            // If the URLs do start with '/', then they obviously don't need a prefix, so it will remain blank
            // If the thumbnail URLs start with with none of '/', 'http://' or 'https://', then we need to set their relative path to be the location of the VTT file

            if (!thumbnail.frames[0].text.startsWith('/') && !thumbnail.frames[0].text.startsWith('http://') && !thumbnail.frames[0].text.startsWith('https://')) {
              thumbnail.urlPrefix = url.substring(0, url.lastIndexOf('/') + 1);
            } // Download the first frame, so that we can determine/set the height of this thumbnailsDef


            var tempImage = new Image();

            tempImage.onload = function () {
              thumbnail.height = tempImage.naturalHeight;
              thumbnail.width = tempImage.naturalWidth;

              _this3.thumbnails.push(thumbnail);

              resolve();
            };

            tempImage.src = thumbnail.urlPrefix + thumbnail.frames[0].text;
          });
        });
      }
    }, {
      key: "startMove",
      value: function startMove(event) {
        if (!this.loaded) {
          return;
        }

        if (!is$1.event(event) || !['touchmove', 'mousemove'].includes(event.type)) {
          return;
        } // Wait until media has a duration


        if (!this.player.media.duration) {
          return;
        }

        if (event.type === 'touchmove') {
          // Calculate seek hover position as approx video seconds
          this.seekTime = this.player.media.duration * (this.player.elements.inputs.seek.value / 100);
        } else {
          // Calculate seek hover position as approx video seconds
          var clientRect = this.player.elements.progress.getBoundingClientRect();
          var percentage = 100 / clientRect.width * (event.pageX - clientRect.left);
          this.seekTime = this.player.media.duration * (percentage / 100);

          if (this.seekTime < 0) {
            // The mousemove fires for 10+px out to the left
            this.seekTime = 0;
          }

          if (this.seekTime > this.player.media.duration - 1) {
            // Took 1 second off the duration for safety, because different players can disagree on the real duration of a video
            this.seekTime = this.player.media.duration - 1;
          }

          this.mousePosX = event.pageX; // Set time text inside image container

          this.elements.thumb.time.innerText = formatTime(this.seekTime);
        } // Download and show image


        this.showImageAtCurrentTime();
      }
    }, {
      key: "endMove",
      value: function endMove() {
        this.toggleThumbContainer(false, true);
      }
    }, {
      key: "startScrubbing",
      value: function startScrubbing(event) {
        // Only act on left mouse button (0), or touch device (event.button is false)
        if (event.button === false || event.button === 0) {
          this.mouseDown = true; // Wait until media has a duration

          if (this.player.media.duration) {
            this.toggleScrubbingContainer(true);
            this.toggleThumbContainer(false, true); // Download and show image

            this.showImageAtCurrentTime();
          }
        }
      }
    }, {
      key: "endScrubbing",
      value: function endScrubbing() {
        var _this4 = this;

        this.mouseDown = false; // Hide scrubbing preview. But wait until the video has successfully seeked before hiding the scrubbing preview

        if (Math.ceil(this.lastTime) === Math.ceil(this.player.media.currentTime)) {
          // The video was already seeked/loaded at the chosen time - hide immediately
          this.toggleScrubbingContainer(false);
        } else {
          // The video hasn't seeked yet. Wait for that
          once.call(this.player, this.player.media, 'timeupdate', function () {
            // Re-check mousedown - we might have already started scrubbing again
            if (!_this4.mouseDown) {
              _this4.toggleScrubbingContainer(false);
            }
          });
        }
      }
      /**
       * Setup hooks for Plyr and window events
       */

    }, {
      key: "listeners",
      value: function listeners() {
        var _this5 = this;

        // Hide thumbnail preview - on mouse click, mouse leave (in listeners.js for now), and video play/seek. All four are required, e.g., for buffering
        this.player.on('play', function () {
          _this5.toggleThumbContainer(false, true);
        });
        this.player.on('seeked', function () {
          _this5.toggleThumbContainer(false);
        });
        this.player.on('timeupdate', function () {
          _this5.lastTime = _this5.player.media.currentTime;
        });
      }
      /**
       * Create HTML elements for image containers
       */

    }, {
      key: "render",
      value: function render() {
        // Create HTML element: plyr__preview-thumbnail-container
        this.elements.thumb.container = createElement('div', {
          class: this.player.config.classNames.previewThumbnails.thumbContainer
        }); // Wrapper for the image for styling

        this.elements.thumb.imageContainer = createElement('div', {
          class: this.player.config.classNames.previewThumbnails.imageContainer
        });
        this.elements.thumb.container.appendChild(this.elements.thumb.imageContainer); // Create HTML element, parent+span: time text (e.g., 01:32:00)

        var timeContainer = createElement('div', {
          class: this.player.config.classNames.previewThumbnails.timeContainer
        });
        this.elements.thumb.time = createElement('span', {}, '00:00');
        timeContainer.appendChild(this.elements.thumb.time);
        this.elements.thumb.container.appendChild(timeContainer); // Inject the whole thumb

        if (is$1.element(this.player.elements.progress)) {
          this.player.elements.progress.appendChild(this.elements.thumb.container);
        } // Create HTML element: plyr__preview-scrubbing-container


        this.elements.scrubbing.container = createElement('div', {
          class: this.player.config.classNames.previewThumbnails.scrubbingContainer
        });
        this.player.elements.wrapper.appendChild(this.elements.scrubbing.container);
      }
    }, {
      key: "showImageAtCurrentTime",
      value: function showImageAtCurrentTime() {
        var _this6 = this;

        if (this.mouseDown) {
          this.setScrubbingContainerSize();
        } else {
          this.setThumbContainerSizeAndPos();
        } // Find the desired thumbnail index
        // TODO: Handle a video longer than the thumbs where thumbNum is null


        var thumbNum = this.thumbnails[0].frames.findIndex(function (frame) {
          return _this6.seekTime >= frame.startTime && _this6.seekTime <= frame.endTime;
        });
        var hasThumb = thumbNum >= 0;
        var qualityIndex = 0; // Show the thumb container if we're not scrubbing

        if (!this.mouseDown) {
          this.toggleThumbContainer(hasThumb);
        } // No matching thumb found


        if (!hasThumb) {
          return;
        } // Check to see if we've already downloaded higher quality versions of this image


        this.thumbnails.forEach(function (thumbnail, index) {
          if (_this6.loadedImages.includes(thumbnail.frames[thumbNum].text)) {
            qualityIndex = index;
          }
        }); // Only proceed if either thumbnum or thumbfilename has changed

        if (thumbNum !== this.showingThumb) {
          this.showingThumb = thumbNum;
          this.loadImage(qualityIndex);
        }
      } // Show the image that's currently specified in this.showingThumb

    }, {
      key: "loadImage",
      value: function loadImage() {
        var _this7 = this;

        var qualityIndex = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
        var thumbNum = this.showingThumb;
        var thumbnail = this.thumbnails[qualityIndex];
        var urlPrefix = thumbnail.urlPrefix;
        var frame = thumbnail.frames[thumbNum];
        var thumbFilename = thumbnail.frames[thumbNum].text;
        var thumbUrl = urlPrefix + thumbFilename;

        if (!this.currentImageElement || this.currentImageElement.dataset.filename !== thumbFilename) {
          // If we're already loading a previous image, remove its onload handler - we don't want it to load after this one
          // Only do this if not using sprites. Without sprites we really want to show as many images as possible, as a best-effort
          if (this.loadingImage && this.usingSprites) {
            this.loadingImage.onload = null;
          } // We're building and adding a new image. In other implementations of similar functionality (YouTube), background image
          // is instead used. But this causes issues with larger images in Firefox and Safari - switching between background
          // images causes a flicker. Putting a new image over the top does not


          var previewImage = new Image();
          previewImage.src = thumbUrl;
          previewImage.dataset.index = thumbNum;
          previewImage.dataset.filename = thumbFilename;
          this.showingThumbFilename = thumbFilename;
          this.player.debug.log("Loading image: ".concat(thumbUrl)); // For some reason, passing the named function directly causes it to execute immediately. So I've wrapped it in an anonymous function...

          previewImage.onload = function () {
            return _this7.showImage(previewImage, frame, qualityIndex, thumbNum, thumbFilename, true);
          };

          this.loadingImage = previewImage;
          this.removeOldImages(previewImage);
        } else {
          // Update the existing image
          this.showImage(this.currentImageElement, frame, qualityIndex, thumbNum, thumbFilename, false);
          this.currentImageElement.dataset.index = thumbNum;
          this.removeOldImages(this.currentImageElement);
        }
      }
    }, {
      key: "showImage",
      value: function showImage(previewImage, frame, qualityIndex, thumbNum, thumbFilename) {
        var newImage = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : true;
        this.player.debug.log("Showing thumb: ".concat(thumbFilename, ". num: ").concat(thumbNum, ". qual: ").concat(qualityIndex, ". newimg: ").concat(newImage));
        this.setImageSizeAndOffset(previewImage, frame);

        if (newImage) {
          this.currentImageContainer.appendChild(previewImage);
          this.currentImageElement = previewImage;

          if (!this.loadedImages.includes(thumbFilename)) {
            this.loadedImages.push(thumbFilename);
          }
        } // Preload images before and after the current one
        // Show higher quality of the same frame
        // Each step here has a short time delay, and only continues if still hovering/seeking the same spot. This is to protect slow connections from overloading


        this.preloadNearby(thumbNum, true).then(this.preloadNearby(thumbNum, false)).then(this.getHigherQuality(qualityIndex, previewImage, frame, thumbFilename));
      } // Remove all preview images that aren't the designated current image

    }, {
      key: "removeOldImages",
      value: function removeOldImages(currentImage) {
        var _this8 = this;

        // Get a list of all images, convert it from a DOM list to an array
        Array.from(this.currentImageContainer.children).forEach(function (image) {
          if (image.tagName.toLowerCase() !== 'img') {
            return;
          }

          var removeDelay = _this8.usingSprites ? 500 : 1000;

          if (image.dataset.index !== currentImage.dataset.index && !image.dataset.deleting) {
            // Wait 200ms, as the new image can take some time to show on certain browsers (even though it was downloaded before showing). This will prevent flicker, and show some generosity towards slower clients
            // First set attribute 'deleting' to prevent multi-handling of this on repeat firing of this function
            // eslint-disable-next-line no-param-reassign
            image.dataset.deleting = true; // This has to be set before the timeout - to prevent issues switching between hover and scrub

            var currentImageContainer = _this8.currentImageContainer;
            setTimeout(function () {
              currentImageContainer.removeChild(image);

              _this8.player.debug.log("Removing thumb: ".concat(image.dataset.filename));
            }, removeDelay);
          }
        });
      } // Preload images before and after the current one. Only if the user is still hovering/seeking the same frame
      // This will only preload the lowest quality

    }, {
      key: "preloadNearby",
      value: function preloadNearby(thumbNum) {
        var _this9 = this;

        var forward = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
        return new Promise(function (resolve) {
          setTimeout(function () {
            var oldThumbFilename = _this9.thumbnails[0].frames[thumbNum].text;

            if (_this9.showingThumbFilename === oldThumbFilename) {
              // Find the nearest thumbs with different filenames. Sometimes it'll be the next index, but in the case of sprites, it might be 100+ away
              var thumbnailsClone;

              if (forward) {
                thumbnailsClone = _this9.thumbnails[0].frames.slice(thumbNum);
              } else {
                thumbnailsClone = _this9.thumbnails[0].frames.slice(0, thumbNum).reverse();
              }

              var foundOne = false;
              thumbnailsClone.forEach(function (frame) {
                var newThumbFilename = frame.text;

                if (newThumbFilename !== oldThumbFilename) {
                  // Found one with a different filename. Make sure it hasn't already been loaded on this page visit
                  if (!_this9.loadedImages.includes(newThumbFilename)) {
                    foundOne = true;

                    _this9.player.debug.log("Preloading thumb filename: ".concat(newThumbFilename));

                    var urlPrefix = _this9.thumbnails[0].urlPrefix;
                    var thumbURL = urlPrefix + newThumbFilename;
                    var previewImage = new Image();
                    previewImage.src = thumbURL;

                    previewImage.onload = function () {
                      _this9.player.debug.log("Preloaded thumb filename: ".concat(newThumbFilename));

                      if (!_this9.loadedImages.includes(newThumbFilename)) _this9.loadedImages.push(newThumbFilename); // We don't resolve until the thumb is loaded

                      resolve();
                    };
                  }
                }
              }); // If there are none to preload then we want to resolve immediately

              if (!foundOne) {
                resolve();
              }
            }
          }, 300);
        });
      } // If user has been hovering current image for half a second, look for a higher quality one

    }, {
      key: "getHigherQuality",
      value: function getHigherQuality(currentQualityIndex, previewImage, frame, thumbFilename) {
        var _this10 = this;

        if (currentQualityIndex < this.thumbnails.length - 1) {
          // Only use the higher quality version if it's going to look any better - if the current thumb is of a lower pixel density than the thumbnail container
          var previewImageHeight = previewImage.naturalHeight;

          if (this.usingSprites) {
            previewImageHeight = frame.h;
          }

          if (previewImageHeight < this.thumbContainerHeight) {
            // Recurse back to the loadImage function - show a higher quality one, but only if the viewer is on this frame for a while
            setTimeout(function () {
              // Make sure the mouse hasn't already moved on and started hovering at another image
              if (_this10.showingThumbFilename === thumbFilename) {
                _this10.player.debug.log("Showing higher quality thumb for: ".concat(thumbFilename));

                _this10.loadImage(currentQualityIndex + 1);
              }
            }, 300);
          }
        }
      }
    }, {
      key: "toggleThumbContainer",
      value: function toggleThumbContainer() {
        var toggle = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
        var clearShowing = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
        var className = this.player.config.classNames.previewThumbnails.thumbContainerShown;
        this.elements.thumb.container.classList.toggle(className, toggle);

        if (!toggle && clearShowing) {
          this.showingThumb = null;
          this.showingThumbFilename = null;
        }
      }
    }, {
      key: "toggleScrubbingContainer",
      value: function toggleScrubbingContainer() {
        var toggle = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
        var className = this.player.config.classNames.previewThumbnails.scrubbingContainerShown;
        this.elements.scrubbing.container.classList.toggle(className, toggle);

        if (!toggle) {
          this.showingThumb = null;
          this.showingThumbFilename = null;
        }
      }
    }, {
      key: "determineContainerAutoSizing",
      value: function determineContainerAutoSizing() {
        if (this.elements.thumb.imageContainer.clientHeight > 20) {
          // This will prevent auto sizing in this.setThumbContainerSizeAndPos()
          this.sizeSpecifiedInCSS = true;
        }
      } // Set the size to be about a quarter of the size of video. Unless option dynamicSize === false, in which case it needs to be set in CSS

    }, {
      key: "setThumbContainerSizeAndPos",
      value: function setThumbContainerSizeAndPos() {
        if (!this.sizeSpecifiedInCSS) {
          var thumbWidth = Math.floor(this.thumbContainerHeight * this.thumbAspectRatio);
          this.elements.thumb.imageContainer.style.height = "".concat(this.thumbContainerHeight, "px");
          this.elements.thumb.imageContainer.style.width = "".concat(thumbWidth, "px");
        }

        this.setThumbContainerPos();
      }
    }, {
      key: "setThumbContainerPos",
      value: function setThumbContainerPos() {
        var seekbarRect = this.player.elements.progress.getBoundingClientRect();
        var plyrRect = this.player.elements.container.getBoundingClientRect();
        var container = this.elements.thumb.container; // Find the lowest and highest desired left-position, so we don't slide out the side of the video container

        var minVal = plyrRect.left - seekbarRect.left + 10;
        var maxVal = plyrRect.right - seekbarRect.left - container.clientWidth - 10; // Set preview container position to: mousepos, minus seekbar.left, minus half of previewContainer.clientWidth

        var previewPos = this.mousePosX - seekbarRect.left - container.clientWidth / 2;

        if (previewPos < minVal) {
          previewPos = minVal;
        }

        if (previewPos > maxVal) {
          previewPos = maxVal;
        }

        container.style.left = "".concat(previewPos, "px");
      } // Can't use 100% width, in case the video is a different aspect ratio to the video container

    }, {
      key: "setScrubbingContainerSize",
      value: function setScrubbingContainerSize() {
        this.elements.scrubbing.container.style.width = "".concat(this.player.media.clientWidth, "px"); // Can't use media.clientHeight - html5 video goes big and does black bars above and below

        this.elements.scrubbing.container.style.height = "".concat(this.player.media.clientWidth / this.thumbAspectRatio, "px");
      } // Sprites need to be offset to the correct location

    }, {
      key: "setImageSizeAndOffset",
      value: function setImageSizeAndOffset(previewImage, frame) {
        if (!this.usingSprites) {
          return;
        } // Find difference between height and preview container height


        var multiplier = this.thumbContainerHeight / frame.h; // eslint-disable-next-line no-param-reassign

        previewImage.style.height = "".concat(Math.floor(previewImage.naturalHeight * multiplier), "px"); // eslint-disable-next-line no-param-reassign

        previewImage.style.width = "".concat(Math.floor(previewImage.naturalWidth * multiplier), "px"); // eslint-disable-next-line no-param-reassign

        previewImage.style.left = "-".concat(frame.x * multiplier, "px"); // eslint-disable-next-line no-param-reassign

        previewImage.style.top = "-".concat(frame.y * multiplier, "px");
      }
    }, {
      key: "enabled",
      get: function get() {
        return this.player.isHTML5 && this.player.isVideo && this.player.config.previewThumbnails.enabled;
      }
    }, {
      key: "currentImageContainer",
      get: function get() {
        if (this.mouseDown) {
          return this.elements.scrubbing.container;
        }

        return this.elements.thumb.imageContainer;
      }
    }, {
      key: "usingSprites",
      get: function get() {
        return Object.keys(this.thumbnails[0].frames[0]).includes('w');
      }
    }, {
      key: "thumbAspectRatio",
      get: function get() {
        if (this.usingSprites) {
          return this.thumbnails[0].frames[0].w / this.thumbnails[0].frames[0].h;
        }

        return this.thumbnails[0].width / this.thumbnails[0].height;
      }
    }, {
      key: "thumbContainerHeight",
      get: function get() {
        if (this.mouseDown) {
          // Can't use media.clientHeight - HTML5 video goes big and does black bars above and below
          return Math.floor(this.player.media.clientWidth / this.thumbAspectRatio);
        }

        return Math.floor(this.player.media.clientWidth / this.thumbAspectRatio / 4);
      }
    }, {
      key: "currentImageElement",
      get: function get() {
        if (this.mouseDown) {
          return this.currentScrubbingImageElement;
        }

        return this.currentThumbnailImageElement;
      },
      set: function set(element) {
        if (this.mouseDown) {
          this.currentScrubbingImageElement = element;
        } else {
          this.currentThumbnailImageElement = element;
        }
      }
    }]);

    return PreviewThumbnails;
  }();

  var source = {
    // Add elements to HTML5 media (source, tracks, etc)
    insertElements: function insertElements(type, attributes) {
      var _this = this;

      if (is$1.string(attributes)) {
        insertElement(type, this.media, {
          src: attributes
        });
      } else if (is$1.array(attributes)) {
        attributes.forEach(function (attribute) {
          insertElement(type, _this.media, attribute);
        });
      }
    },
    // Update source
    // Sources are not checked for support so be careful
    change: function change(input) {
      var _this2 = this;

      if (!getDeep(input, 'sources.length')) {
        this.debug.warn('Invalid source format');
        return;
      } // Cancel current network requests


      html5.cancelRequests.call(this); // Destroy instance and re-setup

      this.destroy.call(this, function () {
        // Reset quality options
        _this2.options.quality = []; // Remove elements

        removeElement(_this2.media);
        _this2.media = null; // Reset class name

        if (is$1.element(_this2.elements.container)) {
          _this2.elements.container.removeAttribute('class');
        } // Set the type and provider


        var sources = input.sources,
            type = input.type;

        var _sources = _slicedToArray(sources, 1),
            _sources$ = _sources[0],
            _sources$$provider = _sources$.provider,
            provider = _sources$$provider === void 0 ? providers.html5 : _sources$$provider,
            src = _sources$.src;

        var tagName = provider === 'html5' ? type : 'div';
        var attributes = provider === 'html5' ? {} : {
          src: src
        };
        Object.assign(_this2, {
          provider: provider,
          type: type,
          // Check for support
          supported: support.check(type, provider, _this2.config.playsinline),
          // Create new element
          media: createElement(tagName, attributes)
        }); // Inject the new element

        _this2.elements.container.appendChild(_this2.media); // Autoplay the new source?


        if (is$1.boolean(input.autoplay)) {
          _this2.config.autoplay = input.autoplay;
        } // Set attributes for audio and video


        if (_this2.isHTML5) {
          if (_this2.config.crossorigin) {
            _this2.media.setAttribute('crossorigin', '');
          }

          if (_this2.config.autoplay) {
            _this2.media.setAttribute('autoplay', '');
          }

          if (!is$1.empty(input.poster)) {
            _this2.poster = input.poster;
          }

          if (_this2.config.loop.active) {
            _this2.media.setAttribute('loop', '');
          }

          if (_this2.config.muted) {
            _this2.media.setAttribute('muted', '');
          }

          if (_this2.config.playsinline) {
            _this2.media.setAttribute('playsinline', '');
          }
        } // Restore class hook


        ui.addStyleHook.call(_this2); // Set new sources for html5

        if (_this2.isHTML5) {
          source.insertElements.call(_this2, 'source', sources);
        } // Set video title


        _this2.config.title = input.title; // Set up from scratch

        media.setup.call(_this2); // HTML5 stuff

        if (_this2.isHTML5) {
          // Setup captions
          if (Object.keys(input).includes('tracks')) {
            source.insertElements.call(_this2, 'track', input.tracks);
          }
        } // If HTML5 or embed but not fully supported, setupInterface and call ready now


        if (_this2.isHTML5 || _this2.isEmbed && !_this2.supported.ui) {
          // Setup interface
          ui.build.call(_this2);
        } // Load HTML5 sources


        if (_this2.isHTML5) {
          _this2.media.load();
        } // Reload thumbnails


        if (_this2.previewThumbnails) {
          _this2.previewThumbnails.load();
        } // Update the fullscreen support


        _this2.fullscreen.update();
      }, true);
    }
  };

  /**
   * Returns a number whose value is limited to the given range.
   *
   * Example: limit the output of this computation to between 0 and 255
   * (x * 255).clamp(0, 255)
   *
   * @param {Number} input
   * @param {Number} min The lower boundary of the output range
   * @param {Number} max The upper boundary of the output range
   * @returns A number in the range [min, max]
   * @type Number
   */
  function clamp() {
    var input = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
    var min = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
    var max = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 255;
    return Math.min(Math.max(input, min), max);
  }

  // TODO: Use a WeakMap for private globals
  // const globals = new WeakMap();
  // Plyr instance

  var Plyr =
  /*#__PURE__*/
  function () {
    function Plyr(target, options) {
      var _this = this;

      _classCallCheck(this, Plyr);

      this.timers = {}; // State

      this.ready = false;
      this.loading = false;
      this.failed = false; // Touch device

      this.touch = support.touch; // Set the media element

      this.media = target; // String selector passed

      if (is$1.string(this.media)) {
        this.media = document.querySelectorAll(this.media);
      } // jQuery, NodeList or Array passed, use first element


      if (window.jQuery && this.media instanceof jQuery || is$1.nodeList(this.media) || is$1.array(this.media)) {
        // eslint-disable-next-line
        this.media = this.media[0];
      } // Set config


      this.config = extend({}, defaults$1, Plyr.defaults, options || {}, function () {
        try {
          return JSON.parse(_this.media.getAttribute('data-plyr-config'));
        } catch (e) {
          return {};
        }
      }()); // Elements cache

      this.elements = {
        container: null,
        captions: null,
        buttons: {},
        display: {},
        progress: {},
        inputs: {},
        settings: {
          popup: null,
          menu: null,
          panels: {},
          buttons: {}
        }
      }; // Captions

      this.captions = {
        active: null,
        currentTrack: -1,
        meta: new WeakMap()
      }; // Fullscreen

      this.fullscreen = {
        active: false
      }; // Options

      this.options = {
        speed: [],
        quality: []
      }; // Debugging
      // TODO: move to globals

      this.debug = new Console(this.config.debug); // Log config options and support

      this.debug.log('Config', this.config);
      this.debug.log('Support', support); // We need an element to setup

      if (is$1.nullOrUndefined(this.media) || !is$1.element(this.media)) {
        this.debug.error('Setup failed: no suitable element passed');
        return;
      } // Bail if the element is initialized


      if (this.media.plyr) {
        this.debug.warn('Target already setup');
        return;
      } // Bail if not enabled


      if (!this.config.enabled) {
        this.debug.error('Setup failed: disabled by config');
        return;
      } // Bail if disabled or no basic support
      // You may want to disable certain UAs etc


      if (!support.check().api) {
        this.debug.error('Setup failed: no support');
        return;
      } // Cache original element state for .destroy()


      var clone = this.media.cloneNode(true);
      clone.autoplay = false;
      this.elements.original = clone; // Set media type based on tag or data attribute
      // Supported: video, audio, vimeo, youtube

      var type = this.media.tagName.toLowerCase(); // Embed properties

      var iframe = null;
      var url = null; // Different setup based on type

      switch (type) {
        case 'div':
          // Find the frame
          iframe = this.media.querySelector('iframe'); // <iframe> type

          if (is$1.element(iframe)) {
            // Detect provider
            url = parseUrl(iframe.getAttribute('src'));
            this.provider = getProviderByUrl(url.toString()); // Rework elements

            this.elements.container = this.media;
            this.media = iframe; // Reset classname

            this.elements.container.className = ''; // Get attributes from URL and set config

            if (url.search.length) {
              var truthy = ['1', 'true'];

              if (truthy.includes(url.searchParams.get('autoplay'))) {
                this.config.autoplay = true;
              }

              if (truthy.includes(url.searchParams.get('loop'))) {
                this.config.loop.active = true;
              } // TODO: replace fullscreen.iosNative with this playsinline config option
              // YouTube requires the playsinline in the URL


              if (this.isYouTube) {
                this.config.playsinline = truthy.includes(url.searchParams.get('playsinline'));
                this.config.youtube.hl = url.searchParams.get('hl'); // TODO: Should this be setting language?
              } else {
                this.config.playsinline = true;
              }
            }
          } else {
            // <div> with attributes
            this.provider = this.media.getAttribute(this.config.attributes.embed.provider); // Remove attribute

            this.media.removeAttribute(this.config.attributes.embed.provider);
          } // Unsupported or missing provider


          if (is$1.empty(this.provider) || !Object.keys(providers).includes(this.provider)) {
            this.debug.error('Setup failed: Invalid provider');
            return;
          } // Audio will come later for external providers


          this.type = types.video;
          break;

        case 'video':
        case 'audio':
          this.type = type;
          this.provider = providers.html5; // Get config from attributes

          if (this.media.hasAttribute('crossorigin')) {
            this.config.crossorigin = true;
          }

          if (this.media.hasAttribute('autoplay')) {
            this.config.autoplay = true;
          }

          if (this.media.hasAttribute('playsinline') || this.media.hasAttribute('webkit-playsinline')) {
            this.config.playsinline = true;
          }

          if (this.media.hasAttribute('muted')) {
            this.config.muted = true;
          }

          if (this.media.hasAttribute('loop')) {
            this.config.loop.active = true;
          }

          break;

        default:
          this.debug.error('Setup failed: unsupported type');
          return;
      } // Check for support again but with type


      this.supported = support.check(this.type, this.provider, this.config.playsinline); // If no support for even API, bail

      if (!this.supported.api) {
        this.debug.error('Setup failed: no support');
        return;
      }

      this.eventListeners = []; // Create listeners

      this.listeners = new Listeners(this); // Setup local storage for user settings

      this.storage = new Storage(this); // Store reference

      this.media.plyr = this; // Wrap media

      if (!is$1.element(this.elements.container)) {
        this.elements.container = createElement('div', {
          tabindex: 0
        });
        wrap(this.media, this.elements.container);
      } // Add style hook


      ui.addStyleHook.call(this); // Setup media

      media.setup.call(this); // Listen for events if debugging

      if (this.config.debug) {
        on.call(this, this.elements.container, this.config.events.join(' '), function (event) {
          _this.debug.log("event: ".concat(event.type));
        });
      } // Setup interface
      // If embed but not fully supported, build interface now to avoid flash of controls


      if (this.isHTML5 || this.isEmbed && !this.supported.ui) {
        ui.build.call(this);
      } // Container listeners


      this.listeners.container(); // Global listeners

      this.listeners.global(); // Setup fullscreen

      this.fullscreen = new Fullscreen(this); // Setup ads if provided

      if (this.config.ads.enabled) {
        this.ads = new Ads(this);
      } // Autoplay if required


      if (this.isHTML5 && this.config.autoplay) {
        setTimeout(function () {
          return _this.play();
        }, 10);
      } // Seek time will be recorded (in listeners.js) so we can prevent hiding controls for a few seconds after seek


      this.lastSeekTime = 0; // Setup preview thumbnails if enabled

      if (this.config.previewThumbnails.enabled) {
        this.previewThumbnails = new PreviewThumbnails(this);
      }
    } // ---------------------------------------
    // API
    // ---------------------------------------

    /**
     * Types and provider helpers
     */


    _createClass(Plyr, [{
      key: "play",

      /**
       * Play the media, or play the advertisement (if they are not blocked)
       */
      value: function play() {
        var _this2 = this;

        if (!is$1.function(this.media.play)) {
          return null;
        } // Intecept play with ads


        if (this.ads && this.ads.enabled) {
          this.ads.managerPromise.then(function () {
            return _this2.ads.play();
          }).catch(function () {
            return _this2.media.play();
          });
        } // Return the promise (for HTML5)


        return this.media.play();
      }
      /**
       * Pause the media
       */

    }, {
      key: "pause",
      value: function pause() {
        if (!this.playing || !is$1.function(this.media.pause)) {
          return;
        }

        this.media.pause();
      }
      /**
       * Get playing state
       */

    }, {
      key: "togglePlay",

      /**
       * Toggle playback based on current status
       * @param {Boolean} input
       */
      value: function togglePlay(input) {
        // Toggle based on current state if nothing passed
        var toggle = is$1.boolean(input) ? input : !this.playing;

        if (toggle) {
          this.play();
        } else {
          this.pause();
        }
      }
      /**
       * Stop playback
       */

    }, {
      key: "stop",
      value: function stop() {
        if (this.isHTML5) {
          this.pause();
          this.restart();
        } else if (is$1.function(this.media.stop)) {
          this.media.stop();
        }
      }
      /**
       * Restart playback
       */

    }, {
      key: "restart",
      value: function restart() {
        this.currentTime = 0;
      }
      /**
       * Rewind
       * @param {Number} seekTime - how far to rewind in seconds. Defaults to the config.seekTime
       */

    }, {
      key: "rewind",
      value: function rewind(seekTime) {
        this.currentTime = this.currentTime - (is$1.number(seekTime) ? seekTime : this.config.seekTime);
      }
      /**
       * Fast forward
       * @param {Number} seekTime - how far to fast forward in seconds. Defaults to the config.seekTime
       */

    }, {
      key: "forward",
      value: function forward(seekTime) {
        this.currentTime = this.currentTime + (is$1.number(seekTime) ? seekTime : this.config.seekTime);
      }
      /**
       * Seek to a time
       * @param {Number} input - where to seek to in seconds. Defaults to 0 (the start)
       */

    }, {
      key: "increaseVolume",

      /**
       * Increase volume
       * @param {Boolean} step - How much to decrease by (between 0 and 1)
       */
      value: function increaseVolume(step) {
        var volume = this.media.muted ? 0 : this.volume;
        this.volume = volume + (is$1.number(step) ? step : 0);
      }
      /**
       * Decrease volume
       * @param {Boolean} step - How much to decrease by (between 0 and 1)
       */

    }, {
      key: "decreaseVolume",
      value: function decreaseVolume(step) {
        this.increaseVolume(-step);
      }
      /**
       * Set muted state
       * @param {Boolean} mute
       */

    }, {
      key: "toggleCaptions",

      /**
       * Toggle captions
       * @param {Boolean} input - Whether to enable captions
       */
      value: function toggleCaptions(input) {
        captions.toggle.call(this, input, false);
      }
      /**
       * Set the caption track by index
       * @param {Number} - Caption index
       */

    }, {
      key: "airplay",

      /**
       * Trigger the airplay dialog
       * TODO: update player with state, support, enabled
       */
      value: function airplay() {
        // Show dialog if supported
        if (support.airplay) {
          this.media.webkitShowPlaybackTargetPicker();
        }
      }
      /**
       * Toggle the player controls
       * @param {Boolean} [toggle] - Whether to show the controls
       */

    }, {
      key: "toggleControls",
      value: function toggleControls(toggle) {
        // Don't toggle if missing UI support or if it's audio
        if (this.supported.ui && !this.isAudio) {
          // Get state before change
          var isHidden = hasClass(this.elements.container, this.config.classNames.hideControls); // Negate the argument if not undefined since adding the class to hides the controls

          var force = typeof toggle === 'undefined' ? undefined : !toggle; // Apply and get updated state

          var hiding = toggleClass(this.elements.container, this.config.classNames.hideControls, force); // Close menu

          if (hiding && this.config.controls.includes('settings') && !is$1.empty(this.config.settings)) {
            controls.toggleMenu.call(this, false);
          } // Trigger event on change


          if (hiding !== isHidden) {
            var eventName = hiding ? 'controlshidden' : 'controlsshown';
            triggerEvent.call(this, this.media, eventName);
          }

          return !hiding;
        }

        return false;
      }
      /**
       * Add event listeners
       * @param {String} event - Event type
       * @param {Function} callback - Callback for when event occurs
       */

    }, {
      key: "on",
      value: function on$1(event, callback) {
        on.call(this, this.elements.container, event, callback);
      }
      /**
       * Add event listeners once
       * @param {String} event - Event type
       * @param {Function} callback - Callback for when event occurs
       */

    }, {
      key: "once",
      value: function once$1(event, callback) {
        once.call(this, this.elements.container, event, callback);
      }
      /**
       * Remove event listeners
       * @param {String} event - Event type
       * @param {Function} callback - Callback for when event occurs
       */

    }, {
      key: "off",
      value: function off$1(event, callback) {
        off(this.elements.container, event, callback);
      }
      /**
       * Destroy an instance
       * Event listeners are removed when elements are removed
       * http://stackoverflow.com/questions/12528049/if-a-dom-element-is-removed-are-its-listeners-also-removed-from-memory
       * @param {Function} callback - Callback for when destroy is complete
       * @param {Boolean} soft - Whether it's a soft destroy (for source changes etc)
       */

    }, {
      key: "destroy",
      value: function destroy(callback) {
        var _this3 = this;

        var soft = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

        if (!this.ready) {
          return;
        }

        var done = function done() {
          // Reset overflow (incase destroyed while in fullscreen)
          document.body.style.overflow = ''; // GC for embed

          _this3.embed = null; // If it's a soft destroy, make minimal changes

          if (soft) {
            if (Object.keys(_this3.elements).length) {
              // Remove elements
              removeElement(_this3.elements.buttons.play);
              removeElement(_this3.elements.captions);
              removeElement(_this3.elements.controls);
              removeElement(_this3.elements.wrapper); // Clear for GC

              _this3.elements.buttons.play = null;
              _this3.elements.captions = null;
              _this3.elements.controls = null;
              _this3.elements.wrapper = null;
            } // Callback


            if (is$1.function(callback)) {
              callback();
            }
          } else {
            // Unbind listeners
            unbindListeners.call(_this3); // Replace the container with the original element provided

            replaceElement(_this3.elements.original, _this3.elements.container); // Event

            triggerEvent.call(_this3, _this3.elements.original, 'destroyed', true); // Callback

            if (is$1.function(callback)) {
              callback.call(_this3.elements.original);
            } // Reset state


            _this3.ready = false; // Clear for garbage collection

            setTimeout(function () {
              _this3.elements = null;
              _this3.media = null;
            }, 200);
          }
        }; // Stop playback


        this.stop(); // Clear timeouts

        clearTimeout(this.timers.loading);
        clearTimeout(this.timers.controls);
        clearTimeout(this.timers.resized); // Provider specific stuff

        if (this.isHTML5) {
          // Restore native video controls
          ui.toggleNativeControls.call(this, true); // Clean up

          done();
        } else if (this.isYouTube) {
          // Clear timers
          clearInterval(this.timers.buffering);
          clearInterval(this.timers.playing); // Destroy YouTube API

          if (this.embed !== null && is$1.function(this.embed.destroy)) {
            this.embed.destroy();
          } // Clean up


          done();
        } else if (this.isVimeo) {
          // Destroy Vimeo API
          // then clean up (wait, to prevent postmessage errors)
          if (this.embed !== null) {
            this.embed.unload().then(done);
          } // Vimeo does not always return


          setTimeout(done, 200);
        }
      }
      /**
       * Check for support for a mime type (HTML5 only)
       * @param {String} type - Mime type
       */

    }, {
      key: "supports",
      value: function supports(type) {
        return support.mime.call(this, type);
      }
      /**
       * Check for support
       * @param {String} type - Player type (audio/video)
       * @param {String} provider - Provider (html5/youtube/vimeo)
       * @param {Boolean} inline - Where player has `playsinline` sttribute
       */

    }, {
      key: "isHTML5",
      get: function get() {
        return this.provider === providers.html5;
      }
    }, {
      key: "isEmbed",
      get: function get() {
        return this.isYouTube || this.isVimeo;
      }
    }, {
      key: "isYouTube",
      get: function get() {
        return this.provider === providers.youtube;
      }
    }, {
      key: "isVimeo",
      get: function get() {
        return this.provider === providers.vimeo;
      }
    }, {
      key: "isVideo",
      get: function get() {
        return this.type === types.video;
      }
    }, {
      key: "isAudio",
      get: function get() {
        return this.type === types.audio;
      }
    }, {
      key: "playing",
      get: function get() {
        return Boolean(this.ready && !this.paused && !this.ended);
      }
      /**
       * Get paused state
       */

    }, {
      key: "paused",
      get: function get() {
        return Boolean(this.media.paused);
      }
      /**
       * Get stopped state
       */

    }, {
      key: "stopped",
      get: function get() {
        return Boolean(this.paused && this.currentTime === 0);
      }
      /**
       * Get ended state
       */

    }, {
      key: "ended",
      get: function get() {
        return Boolean(this.media.ended);
      }
    }, {
      key: "currentTime",
      set: function set(input) {
        // Bail if media duration isn't available yet
        if (!this.duration) {
          return;
        } // Validate input


        var inputIsValid = is$1.number(input) && input > 0; // Set

        this.media.currentTime = inputIsValid ? Math.min(input, this.duration) : 0; // Logging

        this.debug.log("Seeking to ".concat(this.currentTime, " seconds"));
      }
      /**
       * Get current time
       */
      ,
      get: function get() {
        return Number(this.media.currentTime);
      }
      /**
       * Get buffered
       */

    }, {
      key: "buffered",
      get: function get() {
        var buffered = this.media.buffered; // YouTube / Vimeo return a float between 0-1

        if (is$1.number(buffered)) {
          return buffered;
        } // HTML5
        // TODO: Handle buffered chunks of the media
        // (i.e. seek to another section buffers only that section)


        if (buffered && buffered.length && this.duration > 0) {
          return buffered.end(0) / this.duration;
        }

        return 0;
      }
      /**
       * Get seeking status
       */

    }, {
      key: "seeking",
      get: function get() {
        return Boolean(this.media.seeking);
      }
      /**
       * Get the duration of the current media
       */

    }, {
      key: "duration",
      get: function get() {
        // Faux duration set via config
        var fauxDuration = parseFloat(this.config.duration); // Media duration can be NaN or Infinity before the media has loaded

        var realDuration = (this.media || {}).duration;
        var duration = !is$1.number(realDuration) || realDuration === Infinity ? 0 : realDuration; // If config duration is funky, use regular duration

        return fauxDuration || duration;
      }
      /**
       * Set the player volume
       * @param {Number} value - must be between 0 and 1. Defaults to the value from local storage and config.volume if not set in storage
       */

    }, {
      key: "volume",
      set: function set(value) {
        var volume = value;
        var max = 1;
        var min = 0;

        if (is$1.string(volume)) {
          volume = Number(volume);
        } // Load volume from storage if no value specified


        if (!is$1.number(volume)) {
          volume = this.storage.get('volume');
        } // Use config if all else fails


        if (!is$1.number(volume)) {
          volume = this.config.volume;
        } // Maximum is volumeMax


        if (volume > max) {
          volume = max;
        } // Minimum is volumeMin


        if (volume < min) {
          volume = min;
        } // Update config


        this.config.volume = volume; // Set the player volume

        this.media.volume = volume; // If muted, and we're increasing volume manually, reset muted state

        if (!is$1.empty(value) && this.muted && volume > 0) {
          this.muted = false;
        }
      }
      /**
       * Get the current player volume
       */
      ,
      get: function get() {
        return Number(this.media.volume);
      }
    }, {
      key: "muted",
      set: function set(mute) {
        var toggle = mute; // Load muted state from storage

        if (!is$1.boolean(toggle)) {
          toggle = this.storage.get('muted');
        } // Use config if all else fails


        if (!is$1.boolean(toggle)) {
          toggle = this.config.muted;
        } // Update config


        this.config.muted = toggle; // Set mute on the player

        this.media.muted = toggle;
      }
      /**
       * Get current muted state
       */
      ,
      get: function get() {
        return Boolean(this.media.muted);
      }
      /**
       * Check if the media has audio
       */

    }, {
      key: "hasAudio",
      get: function get() {
        // Assume yes for all non HTML5 (as we can't tell...)
        if (!this.isHTML5) {
          return true;
        }

        if (this.isAudio) {
          return true;
        } // Get audio tracks


        return Boolean(this.media.mozHasAudio) || Boolean(this.media.webkitAudioDecodedByteCount) || Boolean(this.media.audioTracks && this.media.audioTracks.length);
      }
      /**
       * Set playback speed
       * @param {Number} speed - the speed of playback (0.5-2.0)
       */

    }, {
      key: "speed",
      set: function set(input) {
        var _this4 = this;

        var speed = null;

        if (is$1.number(input)) {
          speed = input;
        }

        if (!is$1.number(speed)) {
          speed = this.storage.get('speed');
        }

        if (!is$1.number(speed)) {
          speed = this.config.speed.selected;
        } // Clamp to min/max


        var min = this.minimumSpeed,
            max = this.maximumSpeed;
        speed = clamp(speed, min, max); // Update config

        this.config.speed.selected = speed; // Set media speed

        setTimeout(function () {
          _this4.media.playbackRate = speed;
        }, 0);
      }
      /**
       * Get current playback speed
       */
      ,
      get: function get() {
        return Number(this.media.playbackRate);
      }
      /**
       * Get the minimum allowed speed
       */

    }, {
      key: "minimumSpeed",
      get: function get() {
        if (this.isYouTube) {
          // https://developers.google.com/youtube/iframe_api_reference#setPlaybackRate
          return Math.min.apply(Math, _toConsumableArray(this.options.speed));
        }

        if (this.isVimeo) {
          // https://github.com/vimeo/player.js/#setplaybackrateplaybackrate-number-promisenumber-rangeerrorerror
          return 0.5;
        } // https://stackoverflow.com/a/32320020/1191319


        return 0.0625;
      }
      /**
       * Get the maximum allowed speed
       */

    }, {
      key: "maximumSpeed",
      get: function get() {
        if (this.isYouTube) {
          // https://developers.google.com/youtube/iframe_api_reference#setPlaybackRate
          return Math.max.apply(Math, _toConsumableArray(this.options.speed));
        }

        if (this.isVimeo) {
          // https://github.com/vimeo/player.js/#setplaybackrateplaybackrate-number-promisenumber-rangeerrorerror
          return 2;
        } // https://stackoverflow.com/a/32320020/1191319


        return 16;
      }
      /**
       * Set playback quality
       * Currently HTML5 & YouTube only
       * @param {Number} input - Quality level
       */

    }, {
      key: "quality",
      set: function set(input) {
        var config = this.config.quality;
        var options = this.options.quality;

        if (!options.length) {
          return;
        }

        var quality = [!is$1.empty(input) && Number(input), this.storage.get('quality'), config.selected, config.default].find(is$1.number);
        var updateStorage = true;

        if (!options.includes(quality)) {
          var value = closest(options, quality);
          this.debug.warn("Unsupported quality option: ".concat(quality, ", using ").concat(value, " instead"));
          quality = value; // Don't update storage if quality is not supported

          updateStorage = false;
        } // Update config


        config.selected = quality; // Set quality

        this.media.quality = quality; // Save to storage

        if (updateStorage) {
          this.storage.set({
            quality: quality
          });
        }
      }
      /**
       * Get current quality level
       */
      ,
      get: function get() {
        return this.media.quality;
      }
      /**
       * Toggle loop
       * TODO: Finish fancy new logic. Set the indicator on load as user may pass loop as config
       * @param {Boolean} input - Whether to loop or not
       */

    }, {
      key: "loop",
      set: function set(input) {
        var toggle = is$1.boolean(input) ? input : this.config.loop.active;
        this.config.loop.active = toggle;
        this.media.loop = toggle; // Set default to be a true toggle

        /* const type = ['start', 'end', 'all', 'none', 'toggle'].includes(input) ? input : 'toggle';
         switch (type) {
            case 'start':
                if (this.config.loop.end && this.config.loop.end <= this.currentTime) {
                    this.config.loop.end = null;
                }
                this.config.loop.start = this.currentTime;
                // this.config.loop.indicator.start = this.elements.display.played.value;
                break;
             case 'end':
                if (this.config.loop.start >= this.currentTime) {
                    return this;
                }
                this.config.loop.end = this.currentTime;
                // this.config.loop.indicator.end = this.elements.display.played.value;
                break;
             case 'all':
                this.config.loop.start = 0;
                this.config.loop.end = this.duration - 2;
                this.config.loop.indicator.start = 0;
                this.config.loop.indicator.end = 100;
                break;
             case 'toggle':
                if (this.config.loop.active) {
                    this.config.loop.start = 0;
                    this.config.loop.end = null;
                } else {
                    this.config.loop.start = 0;
                    this.config.loop.end = this.duration - 2;
                }
                break;
             default:
                this.config.loop.start = 0;
                this.config.loop.end = null;
                break;
        } */
      }
      /**
       * Get current loop state
       */
      ,
      get: function get() {
        return Boolean(this.media.loop);
      }
      /**
       * Set new media source
       * @param {Object} input - The new source object (see docs)
       */

    }, {
      key: "source",
      set: function set(input) {
        source.change.call(this, input);
      }
      /**
       * Get current source
       */
      ,
      get: function get() {
        return this.media.currentSrc;
      }
      /**
       * Get a download URL (either source or custom)
       */

    }, {
      key: "download",
      get: function get() {
        var download = this.config.urls.download;
        return is$1.url(download) ? download : this.source;
      }
      /**
       * Set the download URL
       */
      ,
      set: function set(input) {
        if (!is$1.url(input)) {
          return;
        }

        this.config.urls.download = input;
        controls.setDownloadUrl.call(this);
      }
      /**
       * Set the poster image for a video
       * @param {String} input - the URL for the new poster image
       */

    }, {
      key: "poster",
      set: function set(input) {
        if (!this.isVideo) {
          this.debug.warn('Poster can only be set for video');
          return;
        }

        ui.setPoster.call(this, input, false).catch(function () {});
      }
      /**
       * Get the current poster image
       */
      ,
      get: function get() {
        if (!this.isVideo) {
          return null;
        }

        return this.media.getAttribute('poster');
      }
      /**
       * Get the current aspect ratio in use
       */

    }, {
      key: "ratio",
      get: function get() {
        if (!this.isVideo) {
          return null;
        }

        var ratio = reduceAspectRatio(getAspectRatio.call(this));
        return is$1.array(ratio) ? ratio.join(':') : ratio;
      }
      /**
       * Set video aspect ratio
       */
      ,
      set: function set(input) {
        if (!this.isVideo) {
          this.debug.warn('Aspect ratio can only be set for video');
          return;
        }

        if (!is$1.string(input) || !validateRatio(input)) {
          this.debug.error("Invalid aspect ratio specified (".concat(input, ")"));
          return;
        }

        this.config.ratio = input;
        setAspectRatio.call(this);
      }
      /**
       * Set the autoplay state
       * @param {Boolean} input - Whether to autoplay or not
       */

    }, {
      key: "autoplay",
      set: function set(input) {
        var toggle = is$1.boolean(input) ? input : this.config.autoplay;
        this.config.autoplay = toggle;
      }
      /**
       * Get the current autoplay state
       */
      ,
      get: function get() {
        return Boolean(this.config.autoplay);
      }
    }, {
      key: "currentTrack",
      set: function set(input) {
        captions.set.call(this, input, false);
      }
      /**
       * Get the current caption track index (-1 if disabled)
       */
      ,
      get: function get() {
        var _this$captions = this.captions,
            toggled = _this$captions.toggled,
            currentTrack = _this$captions.currentTrack;
        return toggled ? currentTrack : -1;
      }
      /**
       * Set the wanted language for captions
       * Since tracks can be added later it won't update the actual caption track until there is a matching track
       * @param {String} - Two character ISO language code (e.g. EN, FR, PT, etc)
       */

    }, {
      key: "language",
      set: function set(input) {
        captions.setLanguage.call(this, input, false);
      }
      /**
       * Get the current track's language
       */
      ,
      get: function get() {
        return (captions.getCurrentTrack.call(this) || {}).language;
      }
      /**
       * Toggle picture-in-picture playback on WebKit/MacOS
       * TODO: update player with state, support, enabled
       * TODO: detect outside changes
       */

    }, {
      key: "pip",
      set: function set(input) {
        // Bail if no support
        if (!support.pip) {
          return;
        } // Toggle based on current state if not passed


        var toggle = is$1.boolean(input) ? input : !this.pip; // Toggle based on current state
        // Safari

        if (is$1.function(this.media.webkitSetPresentationMode)) {
          this.media.webkitSetPresentationMode(toggle ? pip.active : pip.inactive);
        } // Chrome


        if (is$1.function(this.media.requestPictureInPicture)) {
          if (!this.pip && toggle) {
            this.media.requestPictureInPicture();
          } else if (this.pip && !toggle) {
            document.exitPictureInPicture();
          }
        }
      }
      /**
       * Get the current picture-in-picture state
       */
      ,
      get: function get() {
        if (!support.pip) {
          return null;
        } // Safari


        if (!is$1.empty(this.media.webkitPresentationMode)) {
          return this.media.webkitPresentationMode === pip.active;
        } // Chrome


        return this.media === document.pictureInPictureElement;
      }
    }], [{
      key: "supported",
      value: function supported(type, provider, inline) {
        return support.check(type, provider, inline);
      }
      /**
       * Load an SVG sprite into the page
       * @param {String} url - URL for the SVG sprite
       * @param {String} [id] - Unique ID
       */

    }, {
      key: "loadSprite",
      value: function loadSprite$1(url, id) {
        return loadSprite(url, id);
      }
      /**
       * Setup multiple instances
       * @param {*} selector
       * @param {Object} options
       */

    }, {
      key: "setup",
      value: function setup(selector) {
        var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
        var targets = null;

        if (is$1.string(selector)) {
          targets = Array.from(document.querySelectorAll(selector));
        } else if (is$1.nodeList(selector)) {
          targets = Array.from(selector);
        } else if (is$1.array(selector)) {
          targets = selector.filter(is$1.element);
        }

        if (is$1.empty(targets)) {
          return null;
        }

        return targets.map(function (t) {
          return new Plyr(t, options);
        });
      }
    }]);

    return Plyr;
  }();

  Plyr.defaults = cloneDeep(defaults$1);

  return Plyr;

}));

(function (root, factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module unless amdModuleId is set
    define(["jquery"], function (a0) {
      return (factory(a0));
    });
  } else if (typeof exports === 'object') {
    // Node. Does not work with strict CommonJS, but
    // only CommonJS-like environments that support module.exports,
    // like Node.
    module.exports = factory(require("jquery"));
  } else {
    factory(jQuery);
  }
}(this, function ($) {

// This file will be UMDified by a build task.

var defaults = {
		animation: 'fade',
		animationDuration: 350,
		content: null,
		contentAsHTML: false,
		contentCloning: false,
		debug: true,
		delay: 300,
		delayTouch: [300, 500],
		functionInit: null,
		functionBefore: null,
		functionReady: null,
		functionAfter: null,
		functionFormat: null,
		IEmin: 6,
		interactive: false,
		multiple: false,
		// will default to document.body, or must be an element positioned at (0, 0)
		// in the document, typically like the very top views of an app.
		parent: null,
		plugins: ['sideTip'],
		repositionOnScroll: false,
		restoration: 'none',
		selfDestruction: true,
		theme: [],
		timer: 0,
		trackerInterval: 500,
		trackOrigin: false,
		trackTooltip: false,
		trigger: 'hover',
		triggerClose: {
			click: false,
			mouseleave: false,
			originClick: false,
			scroll: false,
			tap: false,
			touchleave: false
		},
		triggerOpen: {
			click: false,
			mouseenter: false,
			tap: false,
			touchstart: false
		},
		updateAnimation: 'rotate',
		zIndex: 9999999
	},
	// we'll avoid using the 'window' global as a good practice but npm's
	// jquery@<2.1.0 package actually requires a 'window' global, so not sure
	// it's useful at all
	win = (typeof window != 'undefined') ? window : null,
	// env will be proxied by the core for plugins to have access its properties
	env = {
		// detect if this device can trigger touch events. Better have a false
		// positive (unused listeners, that's ok) than a false negative.
		// https://github.com/Modernizr/Modernizr/blob/master/feature-detects/touchevents.js
		// http://stackoverflow.com/questions/4817029/whats-the-best-way-to-detect-a-touch-screen-device-using-javascript
		hasTouchCapability: !!(
			win
			&&	(	'ontouchstart' in win
				||	(win.DocumentTouch && win.document instanceof win.DocumentTouch)
				||	win.navigator.maxTouchPoints
			)
		),
		hasTransitions: transitionSupport(),
		IE: false,
		// don't set manually, it will be updated by a build task after the manifest
		semVer: '4.2.6',
		window: win
	},
	core = function() {
		
		// core variables
		
		// the core emitters
		this.__$emitterPrivate = $({});
		this.__$emitterPublic = $({});
		this.__instancesLatestArr = [];
		// collects plugin constructors
		this.__plugins = {};
		// proxy env variables for plugins who might use them
		this._env = env;
	};

// core methods
core.prototype = {
	
	/**
	 * A function to proxy the public methods of an object onto another
	 *
	 * @param {object} constructor The constructor to bridge
	 * @param {object} obj The object that will get new methods (an instance or the core)
	 * @param {string} pluginName A plugin name for the console log message
	 * @return {core}
	 * @private
	 */
	__bridge: function(constructor, obj, pluginName) {
		
		// if it's not already bridged
		if (!obj[pluginName]) {
			
			var fn = function() {};
			fn.prototype = constructor;
			
			var pluginInstance = new fn();
			
			// the _init method has to exist in instance constructors but might be missing
			// in core constructors
			if (pluginInstance.__init) {
				pluginInstance.__init(obj);
			}
			
			$.each(constructor, function(methodName, fn) {
				
				// don't proxy "private" methods, only "protected" and public ones
				if (methodName.indexOf('__') != 0) {
					
					// if the method does not exist yet
					if (!obj[methodName]) {
						
						obj[methodName] = function() {
							return pluginInstance[methodName].apply(pluginInstance, Array.prototype.slice.apply(arguments));
						};
						
						// remember to which plugin this method corresponds (several plugins may
						// have methods of the same name, we need to be sure)
						obj[methodName].bridged = pluginInstance;
					}
					else if (defaults.debug) {
						
						console.log('The '+ methodName +' method of the '+ pluginName
							+' plugin conflicts with another plugin or native methods');
					}
				}
			});
			
			obj[pluginName] = pluginInstance;
		}
		
		return this;
	},
	
	/**
	 * For mockup in Node env if need be, for testing purposes
	 *
	 * @return {core}
	 * @private
	 */
	__setWindow: function(window) {
		env.window = window;
		return this;
	},
	
	/**
	 * Returns a ruler, a tool to help measure the size of a tooltip under
	 * various settings. Meant for plugins
	 * 
	 * @see Ruler
	 * @return {object} A Ruler instance
	 * @protected
	 */
	_getRuler: function($tooltip) {
		return new Ruler($tooltip);
	},
	
	/**
	 * For internal use by plugins, if needed
	 *
	 * @return {core}
	 * @protected
	 */
	_off: function() {
		this.__$emitterPrivate.off.apply(this.__$emitterPrivate, Array.prototype.slice.apply(arguments));
		return this;
	},
	
	/**
	 * For internal use by plugins, if needed
	 *
	 * @return {core}
	 * @protected
	 */
	_on: function() {
		this.__$emitterPrivate.on.apply(this.__$emitterPrivate, Array.prototype.slice.apply(arguments));
		return this;
	},
	
	/**
	 * For internal use by plugins, if needed
	 *
	 * @return {core}
	 * @protected
	 */
	_one: function() {
		this.__$emitterPrivate.one.apply(this.__$emitterPrivate, Array.prototype.slice.apply(arguments));
		return this;
	},
	
	/**
	 * Returns (getter) or adds (setter) a plugin
	 *
	 * @param {string|object} plugin Provide a string (in the full form
	 * "namespace.name") to use as as getter, an object to use as a setter
	 * @return {object|core}
	 * @protected
	 */
	_plugin: function(plugin) {
		
		var self = this;
		
		// getter
		if (typeof plugin == 'string') {
			
			var pluginName = plugin,
				p = null;
			
			// if the namespace is provided, it's easy to search
			if (pluginName.indexOf('.') > 0) {
				p = self.__plugins[pluginName];
			}
			// otherwise, return the first name that matches
			else {
				$.each(self.__plugins, function(i, plugin) {
					
					if (plugin.name.substring(plugin.name.length - pluginName.length - 1) == '.'+ pluginName) {
						p = plugin;
						return false;
					}
				});
			}
			
			return p;
		}
		// setter
		else {
			
			// force namespaces
			if (plugin.name.indexOf('.') < 0) {
				throw new Error('Plugins must be namespaced');
			}
			
			self.__plugins[plugin.name] = plugin;
			
			// if the plugin has core features
			if (plugin.core) {
				
				// bridge non-private methods onto the core to allow new core methods
				self.__bridge(plugin.core, self, plugin.name);
			}
			
			return this;
		}
	},
	
	/**
	 * Trigger events on the core emitters
	 * 
	 * @returns {core}
	 * @protected
	 */
	_trigger: function() {
		
		var args = Array.prototype.slice.apply(arguments);
		
		if (typeof args[0] == 'string') {
			args[0] = { type: args[0] };
		}
		
		// note: the order of emitters matters
		this.__$emitterPrivate.trigger.apply(this.__$emitterPrivate, args);
		this.__$emitterPublic.trigger.apply(this.__$emitterPublic, args);
		
		return this;
	},
	
	/**
	 * Returns instances of all tooltips in the page or an a given element
	 *
	 * @param {string|HTML object collection} selector optional Use this
	 * parameter to restrict the set of objects that will be inspected
	 * for the retrieval of instances. By default, all instances in the
	 * page are returned.
	 * @return {array} An array of instance objects
	 * @public
	 */
	instances: function(selector) {
		
		var instances = [],
			sel = selector || '.tooltipstered';
		
		$(sel).each(function() {
			
			var $this = $(this),
				ns = $this.data('tooltipster-ns');
			
			if (ns) {
				
				$.each(ns, function(i, namespace) {
					instances.push($this.data(namespace));
				});
			}
		});
		
		return instances;
	},
	
	/**
	 * Returns the Tooltipster objects generated by the last initializing call
	 *
	 * @return {array} An array of instance objects
	 * @public
	 */
	instancesLatest: function() {
		return this.__instancesLatestArr;
	},
	
	/**
	 * For public use only, not to be used by plugins (use ::_off() instead)
	 *
	 * @return {core}
	 * @public
	 */
	off: function() {
		this.__$emitterPublic.off.apply(this.__$emitterPublic, Array.prototype.slice.apply(arguments));
		return this;
	},
	
	/**
	 * For public use only, not to be used by plugins (use ::_on() instead)
	 *
	 * @return {core}
	 * @public
	 */
	on: function() {
		this.__$emitterPublic.on.apply(this.__$emitterPublic, Array.prototype.slice.apply(arguments));
		return this;
	},
	
	/**
	 * For public use only, not to be used by plugins (use ::_one() instead)
	 * 
	 * @return {core}
	 * @public
	 */
	one: function() {
		this.__$emitterPublic.one.apply(this.__$emitterPublic, Array.prototype.slice.apply(arguments));
		return this;
	},
	
	/**
	 * Returns all HTML elements which have one or more tooltips
	 *
	 * @param {string} selector optional Use this to restrict the results
	 * to the descendants of an element
	 * @return {array} An array of HTML elements
	 * @public
	 */
	origins: function(selector) {
		
		var sel = selector ?
			selector +' ' :
			'';
		
		return $(sel +'.tooltipstered').toArray();
	},
	
	/**
	 * Change default options for all future instances
	 *
	 * @param {object} d The options that should be made defaults
	 * @return {core}
	 * @public
	 */
	setDefaults: function(d) {
		$.extend(defaults, d);
		return this;
	},
	
	/**
	 * For users to trigger their handlers on the public emitter
	 * 
	 * @returns {core}
	 * @public
	 */
	triggerHandler: function() {
		this.__$emitterPublic.triggerHandler.apply(this.__$emitterPublic, Array.prototype.slice.apply(arguments));
		return this;
	}
};

// $.tooltipster will be used to call core methods
$.tooltipster = new core();

// the Tooltipster instance class (mind the capital T)
$.Tooltipster = function(element, options) {
	
	// list of instance variables
	
	// stack of custom callbacks provided as parameters to API methods
	this.__callbacks = {
		close: [],
		open: []
	};
	// the schedule time of DOM removal
	this.__closingTime;
	// this will be the user content shown in the tooltip. A capital "C" is used
	// because there is also a method called content()
	this.__Content;
	// for the size tracker
	this.__contentBcr;
	// to disable the tooltip after destruction
	this.__destroyed = false;
	// we can't emit directly on the instance because if a method with the same
	// name as the event exists, it will be called by jQuery. Se we use a plain
	// object as emitter. This emitter is for internal use by plugins,
	// if needed.
	this.__$emitterPrivate = $({});
	// this emitter is for the user to listen to events without risking to mess
	// with our internal listeners
	this.__$emitterPublic = $({});
	this.__enabled = true;
	// the reference to the gc interval
	this.__garbageCollector;
	// various position and size data recomputed before each repositioning
	this.__Geometry;
	// the tooltip position, saved after each repositioning by a plugin
	this.__lastPosition;
	// a unique namespace per instance
	this.__namespace = 'tooltipster-'+ Math.round(Math.random()*1000000);
	this.__options;
	// will be used to support origins in scrollable areas
	this.__$originParents;
	this.__pointerIsOverOrigin = false;
	// to remove themes if needed
	this.__previousThemes = [];
	// the state can be either: appearing, stable, disappearing, closed
	this.__state = 'closed';
	// timeout references
	this.__timeouts = {
		close: [],
		open: null
	};
	// store touch events to be able to detect emulated mouse events
	this.__touchEvents = [];
	// the reference to the tracker interval
	this.__tracker = null;
	// the element to which this tooltip is associated
	this._$origin;
	// this will be the tooltip element (jQuery wrapped HTML element).
	// It's the job of a plugin to create it and append it to the DOM
	this._$tooltip;
	
	// launch
	this.__init(element, options);
};

$.Tooltipster.prototype = {
	
	/**
	 * @param origin
	 * @param options
	 * @private
	 */
	__init: function(origin, options) {
		
		var self = this;
		
		self._$origin = $(origin);
		self.__options = $.extend(true, {}, defaults, options);
		
		// some options may need to be reformatted
		self.__optionsFormat();
		
		// don't run on old IE if asked no to
		if (	!env.IE
			||	env.IE >= self.__options.IEmin
		) {
			
			// note: the content is null (empty) by default and can stay that
			// way if the plugin remains initialized but not fed any content. The
			// tooltip will just not appear.
			
			// let's save the initial value of the title attribute for later
			// restoration if need be.
			var initialTitle = null;
			
			// it will already have been saved in case of multiple tooltips
			if (self._$origin.data('tooltipster-initialTitle') === undefined) {
				
				initialTitle = self._$origin.attr('title');
				
				// we do not want initialTitle to be "undefined" because
				// of how jQuery's .data() method works
				if (initialTitle === undefined) initialTitle = null;
				
				self._$origin.data('tooltipster-initialTitle', initialTitle);
			}
			
			// If content is provided in the options, it has precedence over the
			// title attribute.
			// Note: an empty string is considered content, only 'null' represents
			// the absence of content.
			// Also, an existing title="" attribute will result in an empty string
			// content
			if (self.__options.content !== null) {
				self.__contentSet(self.__options.content);
			}
			else {
				
				var selector = self._$origin.attr('data-tooltip-content'),
					$el;
				
				if (selector){
					$el = $(selector);
				}
				
				if ($el && $el[0]) {
					self.__contentSet($el.first());
				}
				else {
					self.__contentSet(initialTitle);
				}
			}
			
			self._$origin
				// strip the title off of the element to prevent the default tooltips
				// from popping up
				.removeAttr('title')
				// to be able to find all instances on the page later (upon window
				// events in particular)
				.addClass('tooltipstered');
			
			// set listeners on the origin
			self.__prepareOrigin();
			
			// set the garbage collector
			self.__prepareGC();
			
			// init plugins
			$.each(self.__options.plugins, function(i, pluginName) {
				self._plug(pluginName);
			});
			
			// to detect swiping
			if (env.hasTouchCapability) {
				$(env.window.document.body).on('touchmove.'+ self.__namespace +'-triggerOpen', function(event) {
					self._touchRecordEvent(event);
				});
			}
			
			self
				// prepare the tooltip when it gets created. This event must
				// be fired by a plugin
				._on('created', function() {
					self.__prepareTooltip();
				})
				// save position information when it's sent by a plugin
				._on('repositioned', function(e) {
					self.__lastPosition = e.position;
				});
		}
		else {
			self.__options.disabled = true;
		}
	},
	
	/**
	 * Insert the content into the appropriate HTML element of the tooltip
	 * 
	 * @returns {self}
	 * @private
	 */
	__contentInsert: function() {
		
		var self = this,
			$el = self._$tooltip.find('.tooltipster-content'),
			formattedContent = self.__Content,
			format = function(content) {
				formattedContent = content;
			};
		
		self._trigger({
			type: 'format',
			content: self.__Content,
			format: format
		});
		
		if (self.__options.functionFormat) {
			
			formattedContent = self.__options.functionFormat.call(
				self,
				self,
				{ origin: self._$origin[0] },
				self.__Content
			);
		}
		
		if (typeof formattedContent === 'string' && !self.__options.contentAsHTML) {
			$el.text(formattedContent);
		}
		else {
			$el
				.empty()
				.append(formattedContent);
		}
		
		return self;
	},
	
	/**
	 * Save the content, cloning it beforehand if need be
	 * 
	 * @param content
	 * @returns {self}
	 * @private
	 */
	__contentSet: function(content) {
		
		// clone if asked. Cloning the object makes sure that each instance has its
		// own version of the content (in case a same object were provided for several
		// instances)
		// reminder: typeof null === object
		if (content instanceof $ && this.__options.contentCloning) {
			content = content.clone(true);
		}
		
		this.__Content = content;
		
		this._trigger({
			type: 'updated',
			content: content
		});
		
		return this;
	},
	
	/**
	 * Error message about a method call made after destruction
	 * 
	 * @private
	 */
	__destroyError: function() {
		throw new Error('This tooltip has been destroyed and cannot execute your method call.');
	},
	
	/**
	 * Gather all information about dimensions and available space,
	 * called before every repositioning
	 * 
	 * @private
	 * @returns {object}
	 */
	__geometry: function() {
		
		var	self = this,
			$target = self._$origin,
			originIsArea = self._$origin.is('area');
		
		// if this._$origin is a map area, the target we'll need
		// the dimensions of is actually the image using the map,
		// not the area itself
		if (originIsArea) {
			
			var mapName = self._$origin.parent().attr('name');
			
			$target = $('img[usemap="#'+ mapName +'"]');
		}
		
		var bcr = $target[0].getBoundingClientRect(),
			$document = $(env.window.document),
			$window = $(env.window),
			$parent = $target,
			// some useful properties of important elements
			geo = {
				// available space for the tooltip, see down below
				available: {
					document: null,
					window: null
				},
				document: {
					size: {
						height: $document.height(),
						width: $document.width()
					}
				},
				window: {
					scroll: {
						// the second ones are for IE compatibility
						left: env.window.scrollX || env.window.document.documentElement.scrollLeft,
						top: env.window.scrollY || env.window.document.documentElement.scrollTop
					},
					size: {
						height: $window.height(),
						width: $window.width()
					}
				},
				origin: {
					// the origin has a fixed lineage if itself or one of its
					// ancestors has a fixed position
					fixedLineage: false,
					// relative to the document
					offset: {},
					size: {
						height: bcr.bottom - bcr.top,
						width: bcr.right - bcr.left
					},
					usemapImage: originIsArea ? $target[0] : null,
					// relative to the window
					windowOffset: {
						bottom: bcr.bottom,
						left: bcr.left,
						right: bcr.right,
						top: bcr.top
					}
				}
			},
			geoFixed = false;
		
		// if the element is a map area, some properties may need
		// to be recalculated
		if (originIsArea) {
			
			var shape = self._$origin.attr('shape'),
				coords = self._$origin.attr('coords');
			
			if (coords) {
				
				coords = coords.split(',');
				
				$.map(coords, function(val, i) {
					coords[i] = parseInt(val);
				});
			}
			
			// if the image itself is the area, nothing more to do
			if (shape != 'default') {
				
				switch(shape) {
					
					case 'circle':
						
						var circleCenterLeft = coords[0],
							circleCenterTop = coords[1],
							circleRadius = coords[2],
							areaTopOffset = circleCenterTop - circleRadius,
							areaLeftOffset = circleCenterLeft - circleRadius;
						
						geo.origin.size.height = circleRadius * 2;
						geo.origin.size.width = geo.origin.size.height;
						
						geo.origin.windowOffset.left += areaLeftOffset;
						geo.origin.windowOffset.top += areaTopOffset;
						
						break;
					
					case 'rect':
						
						var areaLeft = coords[0],
							areaTop = coords[1],
							areaRight = coords[2],
							areaBottom = coords[3];
						
						geo.origin.size.height = areaBottom - areaTop;
						geo.origin.size.width = areaRight - areaLeft;
						
						geo.origin.windowOffset.left += areaLeft;
						geo.origin.windowOffset.top += areaTop;
						
						break;
					
					case 'poly':
						
						var areaSmallestX = 0,
							areaSmallestY = 0,
							areaGreatestX = 0,
							areaGreatestY = 0,
							arrayAlternate = 'even';
						
						for (var i = 0; i < coords.length; i++) {
							
							var areaNumber = coords[i];
							
							if (arrayAlternate == 'even') {
								
								if (areaNumber > areaGreatestX) {
									
									areaGreatestX = areaNumber;
									
									if (i === 0) {
										areaSmallestX = areaGreatestX;
									}
								}
								
								if (areaNumber < areaSmallestX) {
									areaSmallestX = areaNumber;
								}
								
								arrayAlternate = 'odd';
							}
							else {
								if (areaNumber > areaGreatestY) {
									
									areaGreatestY = areaNumber;
									
									if (i == 1) {
										areaSmallestY = areaGreatestY;
									}
								}
								
								if (areaNumber < areaSmallestY) {
									areaSmallestY = areaNumber;
								}
								
								arrayAlternate = 'even';
							}
						}
						
						geo.origin.size.height = areaGreatestY - areaSmallestY;
						geo.origin.size.width = areaGreatestX - areaSmallestX;
						
						geo.origin.windowOffset.left += areaSmallestX;
						geo.origin.windowOffset.top += areaSmallestY;
						
						break;
				}
			}
		}
		
		// user callback through an event
		var edit = function(r) {
			geo.origin.size.height = r.height,
				geo.origin.windowOffset.left = r.left,
				geo.origin.windowOffset.top = r.top,
				geo.origin.size.width = r.width
		};
		
		self._trigger({
			type: 'geometry',
			edit: edit,
			geometry: {
				height: geo.origin.size.height,
				left: geo.origin.windowOffset.left,
				top: geo.origin.windowOffset.top,
				width: geo.origin.size.width
			}
		});
		
		// calculate the remaining properties with what we got
		
		geo.origin.windowOffset.right = geo.origin.windowOffset.left + geo.origin.size.width;
		geo.origin.windowOffset.bottom = geo.origin.windowOffset.top + geo.origin.size.height;
		
		geo.origin.offset.left = geo.origin.windowOffset.left + geo.window.scroll.left;
		geo.origin.offset.top = geo.origin.windowOffset.top + geo.window.scroll.top;
		geo.origin.offset.bottom = geo.origin.offset.top + geo.origin.size.height;
		geo.origin.offset.right = geo.origin.offset.left + geo.origin.size.width;
		
		// the space that is available to display the tooltip relatively to the document
		geo.available.document = {
			bottom: {
				height: geo.document.size.height - geo.origin.offset.bottom,
				width: geo.document.size.width
			},
			left: {
				height: geo.document.size.height,
				width: geo.origin.offset.left
			},
			right: {
				height: geo.document.size.height,
				width: geo.document.size.width - geo.origin.offset.right
			},
			top: {
				height: geo.origin.offset.top,
				width: geo.document.size.width
			}
		};
		
		// the space that is available to display the tooltip relatively to the viewport
		// (the resulting values may be negative if the origin overflows the viewport)
		geo.available.window = {
			bottom: {
				// the inner max is here to make sure the available height is no bigger
				// than the viewport height (when the origin is off screen at the top).
				// The outer max just makes sure that the height is not negative (when
				// the origin overflows at the bottom).
				height: Math.max(geo.window.size.height - Math.max(geo.origin.windowOffset.bottom, 0), 0),
				width: geo.window.size.width
			},
			left: {
				height: geo.window.size.height,
				width: Math.max(geo.origin.windowOffset.left, 0)
			},
			right: {
				height: geo.window.size.height,
				width: Math.max(geo.window.size.width - Math.max(geo.origin.windowOffset.right, 0), 0)
			},
			top: {
				height: Math.max(geo.origin.windowOffset.top, 0),
				width: geo.window.size.width
			}
		};
		
		while ($parent[0].tagName.toLowerCase() != 'html') {
			
			if ($parent.css('position') == 'fixed') {
				geo.origin.fixedLineage = true;
				break;
			}
			
			$parent = $parent.parent();
		}
		
		return geo;
	},
	
	/**
	 * Some options may need to be formated before being used
	 * 
	 * @returns {self}
	 * @private
	 */
	__optionsFormat: function() {
		
		if (typeof this.__options.animationDuration == 'number') {
			this.__options.animationDuration = [this.__options.animationDuration, this.__options.animationDuration];
		}
		
		if (typeof this.__options.delay == 'number') {
			this.__options.delay = [this.__options.delay, this.__options.delay];
		}
		
		if (typeof this.__options.delayTouch == 'number') {
			this.__options.delayTouch = [this.__options.delayTouch, this.__options.delayTouch];
		}
		
		if (typeof this.__options.theme == 'string') {
			this.__options.theme = [this.__options.theme];
		}
		
		// determine the future parent
		if (this.__options.parent === null) {
			this.__options.parent = $(env.window.document.body);
		}
		else if (typeof this.__options.parent == 'string') {
			this.__options.parent = $(this.__options.parent);
		}
		
		if (this.__options.trigger == 'hover') {
			
			this.__options.triggerOpen = {
				mouseenter: true,
				touchstart: true
			};
			
			this.__options.triggerClose = {
				mouseleave: true,
				originClick: true,
				touchleave: true
			};
		}
		else if (this.__options.trigger == 'click') {
			
			this.__options.triggerOpen = {
				click: true,
				tap: true
			};
			
			this.__options.triggerClose = {
				click: true,
				tap: true
			};
		}
		
		// for the plugins
		this._trigger('options');
		
		return this;
	},
	
	/**
	 * Schedules or cancels the garbage collector task
	 *
	 * @returns {self}
	 * @private
	 */
	__prepareGC: function() {
		
		var self = this;
		
		// in case the selfDestruction option has been changed by a method call
		if (self.__options.selfDestruction) {
			
			// the GC task
			self.__garbageCollector = setInterval(function() {
				
				var now = new Date().getTime();
				
				// forget the old events
				self.__touchEvents = $.grep(self.__touchEvents, function(event, i) {
					// 1 minute
					return now - event.time > 60000;
				});
				
				// auto-destruct if the origin is gone
				if (!bodyContains(self._$origin)) {
					
					self.close(function(){
						self.destroy();
					});
				}
			}, 20000);
		}
		else {
			clearInterval(self.__garbageCollector);
		}
		
		return self;
	},
	
	/**
	 * Sets listeners on the origin if the open triggers require them.
	 * Unlike the listeners set at opening time, these ones
	 * remain even when the tooltip is closed. It has been made a
	 * separate method so it can be called when the triggers are
	 * changed in the options. Closing is handled in _open()
	 * because of the bindings that may be needed on the tooltip
	 * itself
	 *
	 * @returns {self}
	 * @private
	 */
	__prepareOrigin: function() {
		
		var self = this;
		
		// in case we're resetting the triggers
		self._$origin.off('.'+ self.__namespace +'-triggerOpen');
		
		// if the device is touch capable, even if only mouse triggers
		// are asked, we need to listen to touch events to know if the mouse
		// events are actually emulated (so we can ignore them)
		if (env.hasTouchCapability) {
			
			self._$origin.on(
				'touchstart.'+ self.__namespace +'-triggerOpen ' +
				'touchend.'+ self.__namespace +'-triggerOpen ' +
				'touchcancel.'+ self.__namespace +'-triggerOpen',
				function(event){
					self._touchRecordEvent(event);
				}
			);
		}
		
		// mouse click and touch tap work the same way
		if (	self.__options.triggerOpen.click
			||	(self.__options.triggerOpen.tap && env.hasTouchCapability)
		) {
			
			var eventNames = '';
			if (self.__options.triggerOpen.click) {
				eventNames += 'click.'+ self.__namespace +'-triggerOpen ';
			}
			if (self.__options.triggerOpen.tap && env.hasTouchCapability) {
				eventNames += 'touchend.'+ self.__namespace +'-triggerOpen';
			}
			
			self._$origin.on(eventNames, function(event) {
				if (self._touchIsMeaningfulEvent(event)) {
					self._open(event);
				}
			});
		}
		
		// mouseenter and touch start work the same way
		if (	self.__options.triggerOpen.mouseenter
			||	(self.__options.triggerOpen.touchstart && env.hasTouchCapability)
		) {
			
			var eventNames = '';
			if (self.__options.triggerOpen.mouseenter) {
				eventNames += 'mouseenter.'+ self.__namespace +'-triggerOpen ';
			}
			if (self.__options.triggerOpen.touchstart && env.hasTouchCapability) {
				eventNames += 'touchstart.'+ self.__namespace +'-triggerOpen';
			}
			
			self._$origin.on(eventNames, function(event) {
				if (	self._touchIsTouchEvent(event)
					||	!self._touchIsEmulatedEvent(event)
				) {
					self.__pointerIsOverOrigin = true;
					self._openShortly(event);
				}
			});
		}
		
		// info for the mouseleave/touchleave close triggers when they use a delay
		if (	self.__options.triggerClose.mouseleave
			||	(self.__options.triggerClose.touchleave && env.hasTouchCapability)
		) {
			
			var eventNames = '';
			if (self.__options.triggerClose.mouseleave) {
				eventNames += 'mouseleave.'+ self.__namespace +'-triggerOpen ';
			}
			if (self.__options.triggerClose.touchleave && env.hasTouchCapability) {
				eventNames += 'touchend.'+ self.__namespace +'-triggerOpen touchcancel.'+ self.__namespace +'-triggerOpen';
			}
			
			self._$origin.on(eventNames, function(event) {
				
				if (self._touchIsMeaningfulEvent(event)) {
					self.__pointerIsOverOrigin = false;
				}
			});
		}
		
		return self;
	},
	
	/**
	 * Do the things that need to be done only once after the tooltip
	 * HTML element it has been created. It has been made a separate
	 * method so it can be called when options are changed. Remember
	 * that the tooltip may actually exist in the DOM before it is
	 * opened, and present after it has been closed: it's the display
	 * plugin that takes care of handling it.
	 * 
	 * @returns {self}
	 * @private
	 */
	__prepareTooltip: function() {
		
		var self = this,
			p = self.__options.interactive ? 'auto' : '';
		
		// this will be useful to know quickly if the tooltip is in
		// the DOM or not 
		self._$tooltip
			.attr('id', self.__namespace)
			.css({
				// pointer events
				'pointer-events': p,
				zIndex: self.__options.zIndex
			});
		
		// themes
		// remove the old ones and add the new ones
		$.each(self.__previousThemes, function(i, theme) {
			self._$tooltip.removeClass(theme);
		});
		$.each(self.__options.theme, function(i, theme) {
			self._$tooltip.addClass(theme);
		});
		
		self.__previousThemes = $.merge([], self.__options.theme);
		
		return self;
	},
	
	/**
	 * Handles the scroll on any of the parents of the origin (when the
	 * tooltip is open)
	 *
	 * @param {object} event
	 * @returns {self}
	 * @private
	 */
	__scrollHandler: function(event) {
		
		var self = this;
		
		if (self.__options.triggerClose.scroll) {
			self._close(event);
		}
		else {
			
			// if the origin or tooltip have been removed: do nothing, the tracker will
			// take care of it later
			if (bodyContains(self._$origin) && bodyContains(self._$tooltip)) {
				
				var geo = null;
				
				// if the scroll happened on the window
				if (event.target === env.window.document) {
					
					// if the origin has a fixed lineage, window scroll will have no
					// effect on its position nor on the position of the tooltip
					if (!self.__Geometry.origin.fixedLineage) {
						
						// we don't need to do anything unless repositionOnScroll is true
						// because the tooltip will already have moved with the window
						// (and of course with the origin)
						if (self.__options.repositionOnScroll) {
							self.reposition(event);
						}
					}
				}
				// if the scroll happened on another parent of the tooltip, it means
				// that it's in a scrollable area and now needs to have its position
				// adjusted or recomputed, depending ont the repositionOnScroll
				// option. Also, if the origin is partly hidden due to a parent that
				// hides its overflow, we'll just hide (not close) the tooltip.
				else {
					
					geo = self.__geometry();
					
					var overflows = false;
					
					// a fixed position origin is not affected by the overflow hiding
					// of a parent
					if (self._$origin.css('position') != 'fixed') {
						
						self.__$originParents.each(function(i, el) {
							
							var $el = $(el),
								overflowX = $el.css('overflow-x'),
								overflowY = $el.css('overflow-y');
							
							if (overflowX != 'visible' || overflowY != 'visible') {
								
								var bcr = el.getBoundingClientRect();
								
								if (overflowX != 'visible') {
									
									if (	geo.origin.windowOffset.left < bcr.left
										||	geo.origin.windowOffset.right > bcr.right
									) {
										overflows = true;
										return false;
									}
								}
								
								if (overflowY != 'visible') {
									
									if (	geo.origin.windowOffset.top < bcr.top
										||	geo.origin.windowOffset.bottom > bcr.bottom
									) {
										overflows = true;
										return false;
									}
								}
							}
							
							// no need to go further if fixed, for the same reason as above
							if ($el.css('position') == 'fixed') {
								return false;
							}
						});
					}
					
					if (overflows) {
						self._$tooltip.css('visibility', 'hidden');
					}
					else {
						
						self._$tooltip.css('visibility', 'visible');
						
						// reposition
						if (self.__options.repositionOnScroll) {
							self.reposition(event);
						}
						// or just adjust offset
						else {
							
							// we have to use offset and not windowOffset because this way,
							// only the scroll distance of the scrollable areas are taken into
							// account (the scrolltop value of the main window must be
							// ignored since the tooltip already moves with it)
							var offsetLeft = geo.origin.offset.left - self.__Geometry.origin.offset.left,
								offsetTop = geo.origin.offset.top - self.__Geometry.origin.offset.top;
							
							// add the offset to the position initially computed by the display plugin
							self._$tooltip.css({
								left: self.__lastPosition.coord.left + offsetLeft,
								top: self.__lastPosition.coord.top + offsetTop
							});
						}
					}
				}
				
				self._trigger({
					type: 'scroll',
					event: event,
					geo: geo
				});
			}
		}
		
		return self;
	},
	
	/**
	 * Changes the state of the tooltip
	 *
	 * @param {string} state
	 * @returns {self}
	 * @private
	 */
	__stateSet: function(state) {
		
		this.__state = state;
		
		this._trigger({
			type: 'state',
			state: state
		});
		
		return this;
	},
	
	/**
	 * Clear appearance timeouts
	 *
	 * @returns {self}
	 * @private
	 */
	__timeoutsClear: function() {
		
		// there is only one possible open timeout: the delayed opening
		// when the mouseenter/touchstart open triggers are used
		clearTimeout(this.__timeouts.open);
		this.__timeouts.open = null;
		
		// ... but several close timeouts: the delayed closing when the
		// mouseleave close trigger is used and the timer option
		$.each(this.__timeouts.close, function(i, timeout) {
			clearTimeout(timeout);
		});
		this.__timeouts.close = [];
		
		return this;
	},
	
	/**
	 * Start the tracker that will make checks at regular intervals
	 * 
	 * @returns {self}
	 * @private
	 */
	__trackerStart: function() {
		
		var self = this,
			$content = self._$tooltip.find('.tooltipster-content');
		
		// get the initial content size
		if (self.__options.trackTooltip) {
			self.__contentBcr = $content[0].getBoundingClientRect();
		}
		
		self.__tracker = setInterval(function() {
			
			// if the origin or tooltip elements have been removed.
			// Note: we could destroy the instance now if the origin has
			// been removed but we'll leave that task to our garbage collector
			if (!bodyContains(self._$origin) || !bodyContains(self._$tooltip)) {
				self._close();
			}
			// if everything is alright
			else {
				
				// compare the former and current positions of the origin to reposition
				// the tooltip if need be
				if (self.__options.trackOrigin) {
					
					var g = self.__geometry(),
						identical = false;
					
					// compare size first (a change requires repositioning too)
					if (areEqual(g.origin.size, self.__Geometry.origin.size)) {
						
						// for elements that have a fixed lineage (see __geometry()), we track the
						// top and left properties (relative to window)
						if (self.__Geometry.origin.fixedLineage) {
							if (areEqual(g.origin.windowOffset, self.__Geometry.origin.windowOffset)) {
								identical = true;
							}
						}
						// otherwise, track total offset (relative to document)
						else {
							if (areEqual(g.origin.offset, self.__Geometry.origin.offset)) {
								identical = true;
							}
						}
					}
					
					if (!identical) {
						
						// close the tooltip when using the mouseleave close trigger
						// (see https://github.com/iamceege/tooltipster/pull/253)
						if (self.__options.triggerClose.mouseleave) {
							self._close();
						}
						else {
							self.reposition();
						}
					}
				}
				
				if (self.__options.trackTooltip) {
					
					var currentBcr = $content[0].getBoundingClientRect();
					
					if (	currentBcr.height !== self.__contentBcr.height
						||	currentBcr.width !== self.__contentBcr.width
					) {
						self.reposition();
						self.__contentBcr = currentBcr;
					}
				}
			}
		}, self.__options.trackerInterval);
		
		return self;
	},
	
	/**
	 * Closes the tooltip (after the closing delay)
	 * 
	 * @param event
	 * @param callback
	 * @param force Set to true to override a potential refusal of the user's function
	 * @returns {self}
	 * @protected
	 */
	_close: function(event, callback, force) {
		
		var self = this,
			ok = true;
		
		self._trigger({
			type: 'close',
			event: event,
			stop: function() {
				ok = false;
			}
		});
		
		// a destroying tooltip (force == true) may not refuse to close
		if (ok || force) {
			
			// save the method custom callback and cancel any open method custom callbacks
			if (callback) self.__callbacks.close.push(callback);
			self.__callbacks.open = [];
			
			// clear open/close timeouts
			self.__timeoutsClear();
			
			var finishCallbacks = function() {
				
				// trigger any close method custom callbacks and reset them
				$.each(self.__callbacks.close, function(i,c) {
					c.call(self, self, {
						event: event,
						origin: self._$origin[0]
					});
				});
				
				self.__callbacks.close = [];
			};
			
			if (self.__state != 'closed') {
				
				var necessary = true,
					d = new Date(),
					now = d.getTime(),
					newClosingTime = now + self.__options.animationDuration[1];
				
				// the tooltip may already already be disappearing, but if a new
				// call to close() is made after the animationDuration was changed
				// to 0 (for example), we ought to actually close it sooner than
				// previously scheduled. In that case it should be noted that the
				// browser will not adapt the animation duration to the new
				// animationDuration that was set after the start of the closing
				// animation.
				// Note: the same thing could be considered at opening, but is not
				// really useful since the tooltip is actually opened immediately
				// upon a call to _open(). Since it would not make the opening
				// animation finish sooner, its sole impact would be to trigger the
				// state event and the open callbacks sooner than the actual end of
				// the opening animation, which is not great.
				if (self.__state == 'disappearing') {
					
					if (	newClosingTime > self.__closingTime
						// in case closing is actually overdue because the script
						// execution was suspended. See #679
						&&	self.__options.animationDuration[1] > 0
					) {
						necessary = false;
					}
				}
				
				if (necessary) {
					
					self.__closingTime = newClosingTime;
					
					if (self.__state != 'disappearing') {
						self.__stateSet('disappearing');
					}
					
					var finish = function() {
						
						// stop the tracker
						clearInterval(self.__tracker);
						
						// a "beforeClose" option has been asked several times but would
						// probably useless since the content element is still accessible
						// via ::content(), and because people can always use listeners
						// inside their content to track what's going on. For the sake of
						// simplicity, this has been denied. Bur for the rare people who
						// really need the option (for old browsers or for the case where
						// detaching the content is actually destructive, for file or
						// password inputs for example), this event will do the work.
						self._trigger({
							type: 'closing',
							event: event
						});
						
						// unbind listeners which are no longer needed
						
						self._$tooltip
							.off('.'+ self.__namespace +'-triggerClose')
							.removeClass('tooltipster-dying');
						
						// orientationchange, scroll and resize listeners
						$(env.window).off('.'+ self.__namespace +'-triggerClose');
						
						// scroll listeners
						self.__$originParents.each(function(i, el) {
							$(el).off('scroll.'+ self.__namespace +'-triggerClose');
						});
						// clear the array to prevent memory leaks
						self.__$originParents = null;
						
						$(env.window.document.body).off('.'+ self.__namespace +'-triggerClose');
						
						self._$origin.off('.'+ self.__namespace +'-triggerClose');
						
						self._off('dismissable');
						
						// a plugin that would like to remove the tooltip from the
						// DOM when closed should bind on this
						self.__stateSet('closed');
						
						// trigger event
						self._trigger({
							type: 'after',
							event: event
						});
						
						// call our constructor custom callback function
						if (self.__options.functionAfter) {
							self.__options.functionAfter.call(self, self, {
								event: event,
								origin: self._$origin[0]
							});
						}
						
						// call our method custom callbacks functions
						finishCallbacks();
					};
					
					if (env.hasTransitions) {
						
						self._$tooltip.css({
							'-moz-animation-duration': self.__options.animationDuration[1] + 'ms',
							'-ms-animation-duration': self.__options.animationDuration[1] + 'ms',
							'-o-animation-duration': self.__options.animationDuration[1] + 'ms',
							'-webkit-animation-duration': self.__options.animationDuration[1] + 'ms',
							'animation-duration': self.__options.animationDuration[1] + 'ms',
							'transition-duration': self.__options.animationDuration[1] + 'ms'
						});
						
						self._$tooltip
							// clear both potential open and close tasks
							.clearQueue()
							.removeClass('tooltipster-show')
							// for transitions only
							.addClass('tooltipster-dying');
						
						if (self.__options.animationDuration[1] > 0) {
							self._$tooltip.delay(self.__options.animationDuration[1]);
						}
						
						self._$tooltip.queue(finish);
					}
					else {
						
						self._$tooltip
							.stop()
							.fadeOut(self.__options.animationDuration[1], finish);
					}
				}
			}
			// if the tooltip is already closed, we still need to trigger
			// the method custom callbacks
			else {
				finishCallbacks();
			}
		}
		
		return self;
	},
	
	/**
	 * For internal use by plugins, if needed
	 * 
	 * @returns {self}
	 * @protected
	 */
	_off: function() {
		this.__$emitterPrivate.off.apply(this.__$emitterPrivate, Array.prototype.slice.apply(arguments));
		return this;
	},
	
	/**
	 * For internal use by plugins, if needed
	 *
	 * @returns {self}
	 * @protected
	 */
	_on: function() {
		this.__$emitterPrivate.on.apply(this.__$emitterPrivate, Array.prototype.slice.apply(arguments));
		return this;
	},
	
	/**
	 * For internal use by plugins, if needed
	 *
	 * @returns {self}
	 * @protected
	 */
	_one: function() {
		this.__$emitterPrivate.one.apply(this.__$emitterPrivate, Array.prototype.slice.apply(arguments));
		return this;
	},
	
	/**
	 * Opens the tooltip right away.
	 *
	 * @param event
	 * @param callback Will be called when the opening animation is over
	 * @returns {self}
	 * @protected
	 */
	_open: function(event, callback) {
		
		var self = this;
		
		// if the destruction process has not begun and if this was not
		// triggered by an unwanted emulated click event
		if (!self.__destroying) {
			
			// check that the origin is still in the DOM
			if (	bodyContains(self._$origin)
				// if the tooltip is enabled
				&&	self.__enabled
			) {
				
				var ok = true;
				
				// if the tooltip is not open yet, we need to call functionBefore.
				// otherwise we can jst go on
				if (self.__state == 'closed') {
					
					// trigger an event. The event.stop function allows the callback
					// to prevent the opening of the tooltip
					self._trigger({
						type: 'before',
						event: event,
						stop: function() {
							ok = false;
						}
					});
					
					if (ok && self.__options.functionBefore) {
						
						// call our custom function before continuing
						ok = self.__options.functionBefore.call(self, self, {
							event: event,
							origin: self._$origin[0]
						});
					}
				}
				
				if (ok !== false) {
					
					// if there is some content
					if (self.__Content !== null) {
						
						// save the method callback and cancel close method callbacks
						if (callback) {
							self.__callbacks.open.push(callback);
						}
						self.__callbacks.close = [];
						
						// get rid of any appearance timeouts
						self.__timeoutsClear();
						
						var extraTime,
							finish = function() {
								
								if (self.__state != 'stable') {
									self.__stateSet('stable');
								}
								
								// trigger any open method custom callbacks and reset them
								$.each(self.__callbacks.open, function(i,c) {
									c.call(self, self, {
										origin: self._$origin[0],
										tooltip: self._$tooltip[0]
									});
								});
								
								self.__callbacks.open = [];
							};
						
						// if the tooltip is already open
						if (self.__state !== 'closed') {
							
							// the timer (if any) will start (or restart) right now
							extraTime = 0;
							
							// if it was disappearing, cancel that
							if (self.__state === 'disappearing') {
								
								self.__stateSet('appearing');
								
								if (env.hasTransitions) {
									
									self._$tooltip
										.clearQueue()
										.removeClass('tooltipster-dying')
										.addClass('tooltipster-show');
									
									if (self.__options.animationDuration[0] > 0) {
										self._$tooltip.delay(self.__options.animationDuration[0]);
									}
									
									self._$tooltip.queue(finish);
								}
								else {
									// in case the tooltip was currently fading out, bring it back
									// to life
									self._$tooltip
										.stop()
										.fadeIn(finish);
								}
							}
							// if the tooltip is already open, we still need to trigger the method
							// custom callback
							else if (self.__state == 'stable') {
								finish();
							}
						}
						// if the tooltip isn't already open, open it
						else {
							
							// a plugin must bind on this and store the tooltip in this._$tooltip
							self.__stateSet('appearing');
							
							// the timer (if any) will start when the tooltip has fully appeared
							// after its transition
							extraTime = self.__options.animationDuration[0];
							
							// insert the content inside the tooltip
							self.__contentInsert();
							
							// reposition the tooltip and attach to the DOM
							self.reposition(event, true);
							
							// animate in the tooltip. If the display plugin wants no css
							// animations, it may override the animation option with a
							// dummy value that will produce no effect
							if (env.hasTransitions) {
								
								// note: there seems to be an issue with start animations which
								// are randomly not played on fast devices in both Chrome and FF,
								// couldn't find a way to solve it yet. It seems that applying
								// the classes before appending to the DOM helps a little, but
								// it messes up some CSS transitions. The issue almost never
								// happens when delay[0]==0 though
								self._$tooltip
									.addClass('tooltipster-'+ self.__options.animation)
									.addClass('tooltipster-initial')
									.css({
										'-moz-animation-duration': self.__options.animationDuration[0] + 'ms',
										'-ms-animation-duration': self.__options.animationDuration[0] + 'ms',
										'-o-animation-duration': self.__options.animationDuration[0] + 'ms',
										'-webkit-animation-duration': self.__options.animationDuration[0] + 'ms',
										'animation-duration': self.__options.animationDuration[0] + 'ms',
										'transition-duration': self.__options.animationDuration[0] + 'ms'
									});
								
								setTimeout(
									function() {
										
										// a quick hover may have already triggered a mouseleave
										if (self.__state != 'closed') {
											
											self._$tooltip
												.addClass('tooltipster-show')
												.removeClass('tooltipster-initial');
											
											if (self.__options.animationDuration[0] > 0) {
												self._$tooltip.delay(self.__options.animationDuration[0]);
											}
											
											self._$tooltip.queue(finish);
										}
									},
									0
								);
							}
							else {
								
								// old browsers will have to live with this
								self._$tooltip
									.css('display', 'none')
									.fadeIn(self.__options.animationDuration[0], finish);
							}
							
							// checks if the origin is removed while the tooltip is open
							self.__trackerStart();
							
							// NOTE: the listeners below have a '-triggerClose' namespace
							// because we'll remove them when the tooltip closes (unlike
							// the '-triggerOpen' listeners). So some of them are actually
							// not about close triggers, rather about positioning.
							
							$(env.window)
								// reposition on resize
								.on('resize.'+ self.__namespace +'-triggerClose', function(e) {
									
									var $ae = $(document.activeElement);
									
									// reposition only if the resize event was not triggered upon the opening
									// of a virtual keyboard due to an input field being focused within the tooltip
									// (otherwise the repositioning would lose the focus)
									if (	(!$ae.is('input') && !$ae.is('textarea'))
										||	!$.contains(self._$tooltip[0], $ae[0])
									) {
										self.reposition(e);
									}
								})
								// same as below for parents
								.on('scroll.'+ self.__namespace +'-triggerClose', function(e) {
									self.__scrollHandler(e);
								});
							
							self.__$originParents = self._$origin.parents();
							
							// scrolling may require the tooltip to be moved or even
							// repositioned in some cases
							self.__$originParents.each(function(i, parent) {
								
								$(parent).on('scroll.'+ self.__namespace +'-triggerClose', function(e) {
									self.__scrollHandler(e);
								});
							});
							
							if (	self.__options.triggerClose.mouseleave
								||	(self.__options.triggerClose.touchleave && env.hasTouchCapability)
							) {
								
								// we use an event to allow users/plugins to control when the mouseleave/touchleave
								// close triggers will come to action. It allows to have more triggering elements
								// than just the origin and the tooltip for example, or to cancel/delay the closing,
								// or to make the tooltip interactive even if it wasn't when it was open, etc.
								self._on('dismissable', function(event) {
									
									if (event.dismissable) {
										
										if (event.delay) {
											
											timeout = setTimeout(function() {
												// event.event may be undefined
												self._close(event.event);
											}, event.delay);
											
											self.__timeouts.close.push(timeout);
										}
										else {
											self._close(event);
										}
									}
									else {
										clearTimeout(timeout);
									}
								});
								
								// now set the listeners that will trigger 'dismissable' events
								var $elements = self._$origin,
									eventNamesIn = '',
									eventNamesOut = '',
									timeout = null;
								
								// if we have to allow interaction, bind on the tooltip too
								if (self.__options.interactive) {
									$elements = $elements.add(self._$tooltip);
								}
								
								if (self.__options.triggerClose.mouseleave) {
									eventNamesIn += 'mouseenter.'+ self.__namespace +'-triggerClose ';
									eventNamesOut += 'mouseleave.'+ self.__namespace +'-triggerClose ';
								}
								if (self.__options.triggerClose.touchleave && env.hasTouchCapability) {
									eventNamesIn += 'touchstart.'+ self.__namespace +'-triggerClose';
									eventNamesOut += 'touchend.'+ self.__namespace +'-triggerClose touchcancel.'+ self.__namespace +'-triggerClose';
								}
								
								$elements
									// close after some time spent outside of the elements
									.on(eventNamesOut, function(event) {
										
										// it's ok if the touch gesture ended up to be a swipe,
										// it's still a "touch leave" situation
										if (	self._touchIsTouchEvent(event)
											||	!self._touchIsEmulatedEvent(event)
										) {
											
											var delay = (event.type == 'mouseleave') ?
												self.__options.delay :
												self.__options.delayTouch;
											
											self._trigger({
												delay: delay[1],
												dismissable: true,
												event: event,
												type: 'dismissable'
											});
										}
									})
									// suspend the mouseleave timeout when the pointer comes back
									// over the elements
									.on(eventNamesIn, function(event) {
										
										// it's also ok if the touch event is a swipe gesture
										if (	self._touchIsTouchEvent(event)
											||	!self._touchIsEmulatedEvent(event)
										) {
											self._trigger({
												dismissable: false,
												event: event,
												type: 'dismissable'
											});
										}
									});
							}
							
							// close the tooltip when the origin gets a mouse click (common behavior of
							// native tooltips)
							if (self.__options.triggerClose.originClick) {
								
								self._$origin.on('click.'+ self.__namespace + '-triggerClose', function(event) {
									
									// we could actually let a tap trigger this but this feature just
									// does not make sense on touch devices
									if (	!self._touchIsTouchEvent(event)
										&&	!self._touchIsEmulatedEvent(event)
									) {
										self._close(event);
									}
								});
							}
							
							// set the same bindings for click and touch on the body to close the tooltip
							if (	self.__options.triggerClose.click
								||	(self.__options.triggerClose.tap && env.hasTouchCapability)
							) {
								
								// don't set right away since the click/tap event which triggered this method
								// (if it was a click/tap) is going to bubble up to the body, we don't want it
								// to close the tooltip immediately after it opened
								setTimeout(function() {
									
									if (self.__state != 'closed') {
										
										var eventNames = '',
											$body = $(env.window.document.body);
										
										if (self.__options.triggerClose.click) {
											eventNames += 'click.'+ self.__namespace +'-triggerClose ';
										}
										if (self.__options.triggerClose.tap && env.hasTouchCapability) {
											eventNames += 'touchend.'+ self.__namespace +'-triggerClose';
										}
										
										$body.on(eventNames, function(event) {
											
											if (self._touchIsMeaningfulEvent(event)) {
												
												self._touchRecordEvent(event);
												
												if (!self.__options.interactive || !$.contains(self._$tooltip[0], event.target)) {
													self._close(event);
												}
											}
										});
										
										// needed to detect and ignore swiping
										if (self.__options.triggerClose.tap && env.hasTouchCapability) {
											
											$body.on('touchstart.'+ self.__namespace +'-triggerClose', function(event) {
												self._touchRecordEvent(event);
											});
										}
									}
								}, 0);
							}
							
							self._trigger('ready');
							
							// call our custom callback
							if (self.__options.functionReady) {
								self.__options.functionReady.call(self, self, {
									origin: self._$origin[0],
									tooltip: self._$tooltip[0]
								});
							}
						}
						
						// if we have a timer set, let the countdown begin
						if (self.__options.timer > 0) {
							
							var timeout = setTimeout(function() {
								self._close();
							}, self.__options.timer + extraTime);
							
							self.__timeouts.close.push(timeout);
						}
					}
				}
			}
		}
		
		return self;
	},
	
	/**
	 * When using the mouseenter/touchstart open triggers, this function will
	 * schedule the opening of the tooltip after the delay, if there is one
	 *
	 * @param event
	 * @returns {self}
	 * @protected
 	 */
	_openShortly: function(event) {
		
		var self = this,
			ok = true;
		
		if (self.__state != 'stable' && self.__state != 'appearing') {
			
			// if a timeout is not already running
			if (!self.__timeouts.open) {
				
				self._trigger({
					type: 'start',
					event: event,
					stop: function() {
						ok = false;
					}
				});
				
				if (ok) {
					
					var delay = (event.type.indexOf('touch') == 0) ?
						self.__options.delayTouch :
						self.__options.delay;
					
					if (delay[0]) {
						
						self.__timeouts.open = setTimeout(function() {
							
							self.__timeouts.open = null;
							
							// open only if the pointer (mouse or touch) is still over the origin.
							// The check on the "meaningful event" can only be made here, after some
							// time has passed (to know if the touch was a swipe or not)
							if (self.__pointerIsOverOrigin && self._touchIsMeaningfulEvent(event)) {
								
								// signal that we go on
								self._trigger('startend');
								
								self._open(event);
							}
							else {
								// signal that we cancel
								self._trigger('startcancel');
							}
						}, delay[0]);
					}
					else {
						// signal that we go on
						self._trigger('startend');
						
						self._open(event);
					}
				}
			}
		}
		
		return self;
	},
	
	/**
	 * Meant for plugins to get their options
	 * 
	 * @param {string} pluginName The name of the plugin that asks for its options
	 * @param {object} defaultOptions The default options of the plugin
	 * @returns {object} The options
	 * @protected
	 */
	_optionsExtract: function(pluginName, defaultOptions) {
		
		var self = this,
			options = $.extend(true, {}, defaultOptions);
		
		// if the plugin options were isolated in a property named after the
		// plugin, use them (prevents conflicts with other plugins)
		var pluginOptions = self.__options[pluginName];
		
		// if not, try to get them as regular options
		if (!pluginOptions){
			
			pluginOptions = {};
			
			$.each(defaultOptions, function(optionName, value) {
				
				var o = self.__options[optionName];
				
				if (o !== undefined) {
					pluginOptions[optionName] = o;
				}
			});
		}
		
		// let's merge the default options and the ones that were provided. We'd want
		// to do a deep copy but not let jQuery merge arrays, so we'll do a shallow
		// extend on two levels, that will be enough if options are not more than 1
		// level deep
		$.each(options, function(optionName, value) {
			
			if (pluginOptions[optionName] !== undefined) {
				
				if ((		typeof value == 'object'
						&&	!(value instanceof Array)
						&&	value != null
					)
					&&
					(		typeof pluginOptions[optionName] == 'object'
						&&	!(pluginOptions[optionName] instanceof Array)
						&&	pluginOptions[optionName] != null
					)
				) {
					$.extend(options[optionName], pluginOptions[optionName]);
				}
				else {
					options[optionName] = pluginOptions[optionName];
				}
			}
		});
		
		return options;
	},
	
	/**
	 * Used at instantiation of the plugin, or afterwards by plugins that activate themselves
	 * on existing instances
	 * 
	 * @param {object} pluginName
	 * @returns {self}
	 * @protected
	 */
	_plug: function(pluginName) {
		
		var plugin = $.tooltipster._plugin(pluginName);
		
		if (plugin) {
			
			// if there is a constructor for instances
			if (plugin.instance) {
				
				// proxy non-private methods on the instance to allow new instance methods
				$.tooltipster.__bridge(plugin.instance, this, plugin.name);
			}
		}
		else {
			throw new Error('The "'+ pluginName +'" plugin is not defined');
		}
		
		return this;
	},
	
	/**
	 * This will return true if the event is a mouse event which was
	 * emulated by the browser after a touch event. This allows us to
	 * really dissociate mouse and touch triggers.
	 * 
	 * There is a margin of error if a real mouse event is fired right
	 * after (within the delay shown below) a touch event on the same
	 * element, but hopefully it should not happen often.
	 * 
	 * @returns {boolean}
	 * @protected
	 */
	_touchIsEmulatedEvent: function(event) {
		
		var isEmulated = false,
			now = new Date().getTime();
		
		for (var i = this.__touchEvents.length - 1; i >= 0; i--) {
			
			var e = this.__touchEvents[i];
			
			// delay, in milliseconds. It's supposed to be 300ms in
			// most browsers (350ms on iOS) to allow a double tap but
			// can be less (check out FastClick for more info)
			if (now - e.time < 500) {
				
				if (e.target === event.target) {
					isEmulated = true;
				}
			}
			else {
				break;
			}
		}
		
		return isEmulated;
	},
	
	/**
	 * Returns false if the event was an emulated mouse event or
	 * a touch event involved in a swipe gesture.
	 * 
	 * @param {object} event
	 * @returns {boolean}
	 * @protected
	 */
	_touchIsMeaningfulEvent: function(event) {
		return (
				(this._touchIsTouchEvent(event) && !this._touchSwiped(event.target))
			||	(!this._touchIsTouchEvent(event) && !this._touchIsEmulatedEvent(event))
		);
	},
	
	/**
	 * Checks if an event is a touch event
	 * 
	 * @param {object} event
	 * @returns {boolean}
	 * @protected
	 */
	_touchIsTouchEvent: function(event){
		return event.type.indexOf('touch') == 0;
	},
	
	/**
	 * Store touch events for a while to detect swiping and emulated mouse events
	 * 
	 * @param {object} event
	 * @returns {self}
	 * @protected
	 */
	_touchRecordEvent: function(event) {
		
		if (this._touchIsTouchEvent(event)) {
			event.time = new Date().getTime();
			this.__touchEvents.push(event);
		}
		
		return this;
	},
	
	/**
	 * Returns true if a swipe happened after the last touchstart event fired on
	 * event.target.
	 * 
	 * We need to differentiate a swipe from a tap before we let the event open
	 * or close the tooltip. A swipe is when a touchmove (scroll) event happens
	 * on the body between the touchstart and the touchend events of an element.
	 * 
	 * @param {object} target The HTML element that may have triggered the swipe
	 * @returns {boolean}
	 * @protected
	 */
	_touchSwiped: function(target) {
		
		var swiped = false;
		
		for (var i = this.__touchEvents.length - 1; i >= 0; i--) {
			
			var e = this.__touchEvents[i];
			
			if (e.type == 'touchmove') {
				swiped = true;
				break;
			}
			else if (
				e.type == 'touchstart'
				&&	target === e.target
			) {
				break;
			}
		}
		
		return swiped;
	},
	
	/**
	 * Triggers an event on the instance emitters
	 * 
	 * @returns {self}
	 * @protected
	 */
	_trigger: function() {
		
		var args = Array.prototype.slice.apply(arguments);
		
		if (typeof args[0] == 'string') {
			args[0] = { type: args[0] };
		}
		
		// add properties to the event
		args[0].instance = this;
		args[0].origin = this._$origin ? this._$origin[0] : null;
		args[0].tooltip = this._$tooltip ? this._$tooltip[0] : null;
		
		// note: the order of emitters matters
		this.__$emitterPrivate.trigger.apply(this.__$emitterPrivate, args);
		$.tooltipster._trigger.apply($.tooltipster, args);
		this.__$emitterPublic.trigger.apply(this.__$emitterPublic, args);
		
		return this;
	},
	
	/**
	 * Deactivate a plugin on this instance
	 * 
	 * @returns {self}
	 * @protected
	 */
	_unplug: function(pluginName) {
		
		var self = this;
		
		// if the plugin has been activated on this instance
		if (self[pluginName]) {
			
			var plugin = $.tooltipster._plugin(pluginName);
			
			// if there is a constructor for instances
			if (plugin.instance) {
				
				// unbridge
				$.each(plugin.instance, function(methodName, fn) {
					
					// if the method exists (privates methods do not) and comes indeed from
					// this plugin (may be missing or come from a conflicting plugin).
					if (	self[methodName]
						&&	self[methodName].bridged === self[pluginName]
					) {
						delete self[methodName];
					}
				});
			}
			
			// destroy the plugin
			if (self[pluginName].__destroy) {
				self[pluginName].__destroy();
			}
			
			// remove the reference to the plugin instance
			delete self[pluginName];
		}
		
		return self;
	},
	
	/**
	 * @see self::_close
	 * @returns {self}
	 * @public
	 */
	close: function(callback) {
		
		if (!this.__destroyed) {
			this._close(null, callback);
		}
		else {
			this.__destroyError();
		}
		
		return this;
	},
	
	/**
	 * Sets or gets the content of the tooltip
	 * 
	 * @returns {mixed|self}
	 * @public
	 */
	content: function(content) {
		
		var self = this;
		
		// getter method
		if (content === undefined) {
			return self.__Content;
		}
		// setter method
		else {
			
			if (!self.__destroyed) {
				
				// change the content
				self.__contentSet(content);
				
				if (self.__Content !== null) {
					
					// update the tooltip if it is open
					if (self.__state !== 'closed') {
						
						// reset the content in the tooltip
						self.__contentInsert();
						
						// reposition and resize the tooltip
						self.reposition();
						
						// if we want to play a little animation showing the content changed
						if (self.__options.updateAnimation) {
							
							if (env.hasTransitions) {
								
								// keep the reference in the local scope
								var animation = self.__options.updateAnimation;
								
								self._$tooltip.addClass('tooltipster-update-'+ animation);
								
								// remove the class after a while. The actual duration of the
								// update animation may be shorter, it's set in the CSS rules
								setTimeout(function() {
									
									if (self.__state != 'closed') {
										
										self._$tooltip.removeClass('tooltipster-update-'+ animation);
									}
								}, 1000);
							}
							else {
								self._$tooltip.fadeTo(200, 0.5, function() {
									if (self.__state != 'closed') {
										self._$tooltip.fadeTo(200, 1);
									}
								});
							}
						}
					}
				}
				else {
					self._close();
				}
			}
			else {
				self.__destroyError();
			}
			
			return self;
		}
	},
	
	/**
	 * Destroys the tooltip
	 * 
	 * @returns {self}
	 * @public
	 */
	destroy: function() {
		
		var self = this;
		
		if (!self.__destroyed) {
			
			if(self.__state != 'closed'){
				
				// no closing delay
				self.option('animationDuration', 0)
					// force closing
					._close(null, null, true);
			}
			else {
				// there might be an open timeout still running
				self.__timeoutsClear();
			}
			
			// send event
			self._trigger('destroy');
			
			self.__destroyed = true;
			
			self._$origin
				.removeData(self.__namespace)
				// remove the open trigger listeners
				.off('.'+ self.__namespace +'-triggerOpen');
			
			// remove the touch listener
			$(env.window.document.body).off('.' + self.__namespace +'-triggerOpen');
			
			var ns = self._$origin.data('tooltipster-ns');
			
			// if the origin has been removed from DOM, its data may
			// well have been destroyed in the process and there would
			// be nothing to clean up or restore
			if (ns) {
				
				// if there are no more tooltips on this element
				if (ns.length === 1) {
					
					// optional restoration of a title attribute
					var title = null;
					if (self.__options.restoration == 'previous') {
						title = self._$origin.data('tooltipster-initialTitle');
					}
					else if (self.__options.restoration == 'current') {
						
						// old school technique to stringify when outerHTML is not supported
						title = (typeof self.__Content == 'string') ?
							self.__Content :
							$('<div></div>').append(self.__Content).html();
					}
					
					if (title) {
						self._$origin.attr('title', title);
					}
					
					// final cleaning
					
					self._$origin.removeClass('tooltipstered');
					
					self._$origin
						.removeData('tooltipster-ns')
						.removeData('tooltipster-initialTitle');
				}
				else {
					// remove the instance namespace from the list of namespaces of
					// tooltips present on the element
					ns = $.grep(ns, function(el, i) {
						return el !== self.__namespace;
					});
					self._$origin.data('tooltipster-ns', ns);
				}
			}
			
			// last event
			self._trigger('destroyed');
			
			// unbind private and public event listeners
			self._off();
			self.off();
			
			// remove external references, just in case
			self.__Content = null;
			self.__$emitterPrivate = null;
			self.__$emitterPublic = null;
			self.__options.parent = null;
			self._$origin = null;
			self._$tooltip = null;
			
			// make sure the object is no longer referenced in there to prevent
			// memory leaks
			$.tooltipster.__instancesLatestArr = $.grep($.tooltipster.__instancesLatestArr, function(el, i) {
				return self !== el;
			});
			
			clearInterval(self.__garbageCollector);
		}
		else {
			self.__destroyError();
		}
		
		// we return the scope rather than true so that the call to
		// .tooltipster('destroy') actually returns the matched elements
		// and applies to all of them
		return self;
	},
	
	/**
	 * Disables the tooltip
	 * 
	 * @returns {self}
	 * @public
	 */
	disable: function() {
		
		if (!this.__destroyed) {
			
			// close first, in case the tooltip would not disappear on
			// its own (no close trigger)
			this._close();
			this.__enabled = false;
			
			return this;
		}
		else {
			this.__destroyError();
		}
		
		return this;
	},
	
	/**
	 * Returns the HTML element of the origin
	 *
	 * @returns {self}
	 * @public
	 */
	elementOrigin: function() {
		
		if (!this.__destroyed) {
			return this._$origin[0];
		}
		else {
			this.__destroyError();
		}
	},
	
	/**
	 * Returns the HTML element of the tooltip
	 *
	 * @returns {self}
	 * @public
	 */
	elementTooltip: function() {
		return this._$tooltip ? this._$tooltip[0] : null;
	},
	
	/**
	 * Enables the tooltip
	 * 
	 * @returns {self}
	 * @public
	 */
	enable: function() {
		this.__enabled = true;
		return this;
	},
	
	/**
	 * Alias, deprecated in 4.0.0
	 * 
	 * @param {function} callback
	 * @returns {self}
	 * @public
	 */
	hide: function(callback) {
		return this.close(callback);
	},
	
	/**
	 * Returns the instance
	 * 
	 * @returns {self}
	 * @public
	 */
	instance: function() {
		return this;
	},
	
	/**
	 * For public use only, not to be used by plugins (use ::_off() instead)
	 * 
	 * @returns {self}
	 * @public
	 */
	off: function() {
		
		if (!this.__destroyed) {
			this.__$emitterPublic.off.apply(this.__$emitterPublic, Array.prototype.slice.apply(arguments));
		}
		
		return this;
	},
	
	/**
	 * For public use only, not to be used by plugins (use ::_on() instead)
	 *
	 * @returns {self}
	 * @public
	 */
	on: function() {
		
		if (!this.__destroyed) {
			this.__$emitterPublic.on.apply(this.__$emitterPublic, Array.prototype.slice.apply(arguments));
		}
		else {
			this.__destroyError();
		}
		
		return this;
	},
	
	/**
	 * For public use only, not to be used by plugins
	 *
	 * @returns {self}
	 * @public
	 */
	one: function() {
		
		if (!this.__destroyed) {
			this.__$emitterPublic.one.apply(this.__$emitterPublic, Array.prototype.slice.apply(arguments));
		}
		else {
			this.__destroyError();
		}
		
		return this;
	},
	
	/**
	 * @see self::_open
	 * @returns {self}
	 * @public
	 */
	open: function(callback) {
		
		if (!this.__destroyed) {
			this._open(null, callback);
		}
		else {
			this.__destroyError();
		}
		
		return this;
	},
	
	/**
	 * Get or set options. For internal use and advanced users only.
	 * 
	 * @param {string} o Option name
	 * @param {mixed} val optional A new value for the option
	 * @return {mixed|self} If val is omitted, the value of the option
	 * is returned, otherwise the instance itself is returned
	 * @public
	 */ 
	option: function(o, val) {
		
		// getter
		if (val === undefined) {
			return this.__options[o];
		}
		// setter
		else {
			
			if (!this.__destroyed) {
				
				// change value
				this.__options[o] = val;
				
				// format
				this.__optionsFormat();
				
				// re-prepare the triggers if needed
				if ($.inArray(o, ['trigger', 'triggerClose', 'triggerOpen']) >= 0) {
					this.__prepareOrigin();
				}
				
				if (o === 'selfDestruction') {
					this.__prepareGC();
				}
			}
			else {
				this.__destroyError();
			}
			
			return this;
		}
	},
	
	/**
	 * This method is in charge of setting the position and size properties of the tooltip.
	 * All the hard work is delegated to the display plugin.
	 * Note: The tooltip may be detached from the DOM at the moment the method is called 
	 * but must be attached by the end of the method call.
	 * 
	 * @param {object} event For internal use only. Defined if an event such as
	 * window resizing triggered the repositioning
	 * @param {boolean} tooltipIsDetached For internal use only. Set this to true if you
	 * know that the tooltip not being in the DOM is not an issue (typically when the
	 * tooltip element has just been created but has not been added to the DOM yet).
	 * @returns {self}
	 * @public
	 */
	reposition: function(event, tooltipIsDetached) {
		
		var self = this;
		
		if (!self.__destroyed) {
			
			// if the tooltip is still open and the origin is still in the DOM
			if (self.__state != 'closed' && bodyContains(self._$origin)) {
				
				// if the tooltip has not been removed from DOM manually (or if it
				// has been detached on purpose)
				if (tooltipIsDetached || bodyContains(self._$tooltip)) {
					
					if (!tooltipIsDetached) {
						// detach in case the tooltip overflows the window and adds
						// scrollbars to it, so __geometry can be accurate
						self._$tooltip.detach();
					}
					
					// refresh the geometry object before passing it as a helper
					self.__Geometry = self.__geometry();
					
					// let a plugin fo the rest
					self._trigger({
						type: 'reposition',
						event: event,
						helper: {
							geo: self.__Geometry
						}
					});
				}
			}
		}
		else {
			self.__destroyError();
		}
		
		return self;
	},
	
	/**
	 * Alias, deprecated in 4.0.0
	 *
	 * @param callback
	 * @returns {self}
	 * @public
	 */
	show: function(callback) {
		return this.open(callback);
	},
	
	/**
	 * Returns some properties about the instance
	 * 
	 * @returns {object}
	 * @public
	 */
	status: function() {
		
		return {
			destroyed: this.__destroyed,
			enabled: this.__enabled,
			open: this.__state !== 'closed',
			state: this.__state
		};
	},
	
	/**
	 * For public use only, not to be used by plugins
	 *
	 * @returns {self}
	 * @public
	 */
	triggerHandler: function() {
		
		if (!this.__destroyed) {
			this.__$emitterPublic.triggerHandler.apply(this.__$emitterPublic, Array.prototype.slice.apply(arguments));
		}
		else {
			this.__destroyError();
		}
		
		return this;
	}
};

$.fn.tooltipster = function() {
	
	// for using in closures
	var args = Array.prototype.slice.apply(arguments),
		// common mistake: an HTML element can't be in several tooltips at the same time
		contentCloningWarning = 'You are using a single HTML element as content for several tooltips. You probably want to set the contentCloning option to TRUE.';
	
	// this happens with $(sel).tooltipster(...) when $(sel) does not match anything
	if (this.length === 0) {
		
		// still chainable
		return this;
	}
	// this happens when calling $(sel).tooltipster('methodName or options')
	// where $(sel) matches one or more elements
	else {
		
		// method calls
		if (typeof args[0] === 'string') {
			
			var v = '#*$~&';
			
			this.each(function() {
				
				// retrieve the namepaces of the tooltip(s) that exist on that element.
				// We will interact with the first tooltip only.
				var ns = $(this).data('tooltipster-ns'),
					// self represents the instance of the first tooltipster plugin
					// associated to the current HTML object of the loop
					self = ns ? $(this).data(ns[0]) : null;
				
				// if the current element holds a tooltipster instance
				if (self) {
					
					if (typeof self[args[0]] === 'function') {
						
						if (	this.length > 1
							&&	args[0] == 'content'
							&&	(	args[1] instanceof $
								|| (typeof args[1] == 'object' && args[1] != null && args[1].tagName)
							)
							&&	!self.__options.contentCloning
							&&	self.__options.debug
						) {
							console.log(contentCloningWarning);
						}
						
						// note : args[1] and args[2] may not be defined
						var resp = self[args[0]](args[1], args[2]);
					}
					else {
						throw new Error('Unknown method "'+ args[0] +'"');
					}
					
					// if the function returned anything other than the instance
					// itself (which implies chaining, except for the `instance` method)
					if (resp !== self || args[0] === 'instance') {
						
						v = resp;
						
						// return false to stop .each iteration on the first element
						// matched by the selector
						return false;
					}
				}
				else {
					throw new Error('You called Tooltipster\'s "'+ args[0] +'" method on an uninitialized element');
				}
			});
			
			return (v !== '#*$~&') ? v : this;
		}
		// first argument is undefined or an object: the tooltip is initializing
		else {
			
			// reset the array of last initialized objects
			$.tooltipster.__instancesLatestArr = [];
			
			// is there a defined value for the multiple option in the options object ?
			var	multipleIsSet = args[0] && args[0].multiple !== undefined,
				// if the multiple option is set to true, or if it's not defined but
				// set to true in the defaults
				multiple = (multipleIsSet && args[0].multiple) || (!multipleIsSet && defaults.multiple),
				// same for content
				contentIsSet = args[0] && args[0].content !== undefined,
				content = (contentIsSet && args[0].content) || (!contentIsSet && defaults.content),
				// same for contentCloning
				contentCloningIsSet = args[0] && args[0].contentCloning !== undefined,
				contentCloning =
						(contentCloningIsSet && args[0].contentCloning)
					||	(!contentCloningIsSet && defaults.contentCloning),
				// same for debug
				debugIsSet = args[0] && args[0].debug !== undefined,
				debug = (debugIsSet && args[0].debug) || (!debugIsSet && defaults.debug);
			
			if (	this.length > 1
				&&	(	content instanceof $
					|| (typeof content == 'object' && content != null && content.tagName)
				)
				&&	!contentCloning
				&&	debug
			) {
				console.log(contentCloningWarning);
			}
			
			// create a tooltipster instance for each element if it doesn't
			// already have one or if the multiple option is set, and attach the
			// object to it
			this.each(function() {
				
				var go = false,
					$this = $(this),
					ns = $this.data('tooltipster-ns'),
					obj = null;
				
				if (!ns) {
					go = true;
				}
				else if (multiple) {
					go = true;
				}
				else if (debug) {
					console.log('Tooltipster: one or more tooltips are already attached to the element below. Ignoring.');
					console.log(this);
				}
				
				if (go) {
					obj = new $.Tooltipster(this, args[0]);
					
					// save the reference of the new instance
					if (!ns) ns = [];
					ns.push(obj.__namespace);
					$this.data('tooltipster-ns', ns);
					
					// save the instance itself
					$this.data(obj.__namespace, obj);
					
					// call our constructor custom function.
					// we do this here and not in ::init() because we wanted
					// the object to be saved in $this.data before triggering
					// it
					if (obj.__options.functionInit) {
						obj.__options.functionInit.call(obj, obj, {
							origin: this
						});
					}
					
					// and now the event, for the plugins and core emitter
					obj._trigger('init');
				}
				
				$.tooltipster.__instancesLatestArr.push(obj);
			});
			
			return this;
		}
	}
};

// Utilities

/**
 * A class to check if a tooltip can fit in given dimensions
 * 
 * @param {object} $tooltip The jQuery wrapped tooltip element, or a clone of it
 */
function Ruler($tooltip) {
	
	// list of instance variables
	
	this.$container;
	this.constraints = null;
	this.__$tooltip;
	
	this.__init($tooltip);
}

Ruler.prototype = {
	
	/**
	 * Move the tooltip into an invisible div that does not allow overflow to make
	 * size tests. Note: the tooltip may or may not be attached to the DOM at the
	 * moment this method is called, it does not matter.
	 * 
	 * @param {object} $tooltip The object to test. May be just a clone of the
	 * actual tooltip.
	 * @private
	 */
	__init: function($tooltip) {
		
		this.__$tooltip = $tooltip;
		
		this.__$tooltip
			.css({
				// for some reason we have to specify top and left 0
				left: 0,
				// any overflow will be ignored while measuring
				overflow: 'hidden',
				// positions at (0,0) without the div using 100% of the available width
				position: 'absolute',
				top: 0
			})
			// overflow must be auto during the test. We re-set this in case
			// it were modified by the user
			.find('.tooltipster-content')
				.css('overflow', 'auto');
		
		this.$container = $('<div class="tooltipster-ruler"></div>')
			.append(this.__$tooltip)
			.appendTo(env.window.document.body);
	},
	
	/**
	 * Force the browser to redraw (re-render) the tooltip immediately. This is required
	 * when you changed some CSS properties and need to make something with it
	 * immediately, without waiting for the browser to redraw at the end of instructions.
	 *
	 * @see http://stackoverflow.com/questions/3485365/how-can-i-force-webkit-to-redraw-repaint-to-propagate-style-changes
	 * @private
	 */
	__forceRedraw: function() {
		
		// note: this would work but for Webkit only
		//this.__$tooltip.close();
		//this.__$tooltip[0].offsetHeight;
		//this.__$tooltip.open();
		
		// works in FF too
		var $p = this.__$tooltip.parent();
		this.__$tooltip.detach();
		this.__$tooltip.appendTo($p);
	},
	
	/**
	 * Set maximum dimensions for the tooltip. A call to ::measure afterwards
	 * will tell us if the content overflows or if it's ok
	 *
	 * @param {int} width
	 * @param {int} height
	 * @return {Ruler}
	 * @public
	 */
	constrain: function(width, height) {
		
		this.constraints = {
			width: width,
			height: height
		};
		
		this.__$tooltip.css({
			// we disable display:flex, otherwise the content would overflow without
			// creating horizontal scrolling (which we need to detect).
			display: 'block',
			// reset any previous height
			height: '',
			// we'll check if horizontal scrolling occurs
			overflow: 'auto',
			// we'll set the width and see what height is generated and if there
			// is horizontal overflow
			width: width
		});
		
		return this;
	},
	
	/**
	 * Reset the tooltip content overflow and remove the test container
	 * 
	 * @returns {Ruler}
	 * @public
	 */
	destroy: function() {
		
		// in case the element was not a clone
		this.__$tooltip
			.detach()
			.find('.tooltipster-content')
				.css({
					// reset to CSS value
					display: '',
					overflow: ''
				});
		
		this.$container.remove();
	},
	
	/**
	 * Removes any constraints
	 * 
	 * @returns {Ruler}
	 * @public
	 */
	free: function() {
		
		this.constraints = null;
		
		// reset to natural size
		this.__$tooltip.css({
			display: '',
			height: '',
			overflow: 'visible',
			width: ''
		});
		
		return this;
	},
	
	/**
	 * Returns the size of the tooltip. When constraints are applied, also returns
	 * whether the tooltip fits in the provided dimensions.
	 * The idea is to see if the new height is small enough and if the content does
	 * not overflow horizontally.
	 *
	 * @param {int} width
	 * @param {int} height
	 * @returns {object} An object with a bool `fits` property and a `size` property
	 * @public
	 */
	measure: function() {
		
		this.__forceRedraw();
		
		var tooltipBcr = this.__$tooltip[0].getBoundingClientRect(),
			result = { size: {
				// bcr.width/height are not defined in IE8- but in this
				// case, bcr.right/bottom will have the same value
				// except in iOS 8+ where tooltipBcr.bottom/right are wrong
				// after scrolling for reasons yet to be determined.
				// tooltipBcr.top/left might not be 0, see issue #514
				height: tooltipBcr.height || (tooltipBcr.bottom - tooltipBcr.top),
				width: tooltipBcr.width || (tooltipBcr.right - tooltipBcr.left)
			}};
		
		if (this.constraints) {
			
			// note: we used to use offsetWidth instead of boundingRectClient but
			// it returned rounded values, causing issues with sub-pixel layouts.
			
			// note2: noticed that the bcrWidth of text content of a div was once
			// greater than the bcrWidth of its container by 1px, causing the final
			// tooltip box to be too small for its content. However, evaluating
			// their widths one against the other (below) surprisingly returned
			// equality. Happened only once in Chrome 48, was not able to reproduce
			// => just having fun with float position values...
			
			var $content = this.__$tooltip.find('.tooltipster-content'),
				height = this.__$tooltip.outerHeight(),
				contentBcr = $content[0].getBoundingClientRect(),
				fits = {
					height: height <= this.constraints.height,
					width: (
						// this condition accounts for min-width property that
						// may apply
						tooltipBcr.width <= this.constraints.width
							// the -1 is here because scrollWidth actually returns
							// a rounded value, and may be greater than bcr.width if
							// it was rounded up. This may cause an issue for contents
							// which actually really overflow  by 1px or so, but that
							// should be rare. Not sure how to solve this efficiently.
							// See http://blogs.msdn.com/b/ie/archive/2012/02/17/sub-pixel-rendering-and-the-css-object-model.aspx
						&&	contentBcr.width >= $content[0].scrollWidth - 1
					)
				};
			
			result.fits = fits.height && fits.width;
		}
		
		// old versions of IE get the width wrong for some reason and it causes
		// the text to be broken to a new line, so we round it up. If the width
		// is the width of the screen though, we can assume it is accurate.
		if (	env.IE
			&&	env.IE <= 11
			&&	result.size.width !== env.window.document.documentElement.clientWidth
		) {
			result.size.width = Math.ceil(result.size.width) + 1;
		}
		
		return result;
	}
};

// quick & dirty compare function, not bijective nor multidimensional
function areEqual(a,b) {
	var same = true;
	$.each(a, function(i, _) {
		if (b[i] === undefined || a[i] !== b[i]) {
			same = false;
			return false;
		}
	});
	return same;
}

/**
 * A fast function to check if an element is still in the DOM. It
 * tries to use an id as ids are indexed by the browser, or falls
 * back to jQuery's `contains` method. May fail if two elements
 * have the same id, but so be it
 *
 * @param {object} $obj A jQuery-wrapped HTML element
 * @return {boolean}
 */
function bodyContains($obj) {
	var id = $obj.attr('id'),
		el = id ? env.window.document.getElementById(id) : null;
	// must also check that the element with the id is the one we want
	return el ? el === $obj[0] : $.contains(env.window.document.body, $obj[0]);
}

// detect IE versions for dirty fixes
var uA = navigator.userAgent.toLowerCase();
if (uA.indexOf('msie') != -1) env.IE = parseInt(uA.split('msie')[1]);
else if (uA.toLowerCase().indexOf('trident') !== -1 && uA.indexOf(' rv:11') !== -1) env.IE = 11;
else if (uA.toLowerCase().indexOf('edge/') != -1) env.IE = parseInt(uA.toLowerCase().split('edge/')[1]);

// detecting support for CSS transitions
function transitionSupport() {
	
	// env.window is not defined yet when this is called
	if (!win) return false;
	
	var b = win.document.body || win.document.documentElement,
		s = b.style,
		p = 'transition',
		v = ['Moz', 'Webkit', 'Khtml', 'O', 'ms'];
	
	if (typeof s[p] == 'string') { return true; }
	
	p = p.charAt(0).toUpperCase() + p.substr(1);
	for (var i=0; i<v.length; i++) {
		if (typeof s[v[i] + p] == 'string') { return true; }
	}
	return false;
}

// we'll return jQuery for plugins not to have to declare it as a dependency,
// but it's done by a build task since it should be included only once at the
// end when we concatenate the main file with a plugin
// sideTip is Tooltipster's default plugin.
// This file will be UMDified by a build task.

var pluginName = 'tooltipster.sideTip';

$.tooltipster._plugin({
	name: pluginName,
	instance: {
		/**
		 * Defaults are provided as a function for an easy override by inheritance
		 *
		 * @return {object} An object with the defaults options
		 * @private
		 */
		__defaults: function() {
			
			return {
				// if the tooltip should display an arrow that points to the origin
				arrow: true,
				// the distance in pixels between the tooltip and the origin
				distance: 6,
				// allows to easily change the position of the tooltip
				functionPosition: null,
				maxWidth: null,
				// used to accomodate the arrow of tooltip if there is one.
				// First to make sure that the arrow target is not too close
				// to the edge of the tooltip, so the arrow does not overflow
				// the tooltip. Secondly when we reposition the tooltip to
				// make sure that it's positioned in such a way that the arrow is
				// still pointing at the target (and not a few pixels beyond it).
				// It should be equal to or greater than half the width of
				// the arrow (by width we mean the size of the side which touches
				// the side of the tooltip).
				minIntersection: 16,
				minWidth: 0,
				// deprecated in 4.0.0. Listed for _optionsExtract to pick it up
				position: null,
				side: 'top',
				// set to false to position the tooltip relatively to the document rather
				// than the window when we open it
				viewportAware: true
			};
		},
		
		/**
		 * Run once: at instantiation of the plugin
		 *
		 * @param {object} instance The tooltipster object that instantiated this plugin
		 * @private
		 */
		__init: function(instance) {
			
			var self = this;
			
			// list of instance variables
			
			self.__instance = instance;
			self.__namespace = 'tooltipster-sideTip-'+ Math.round(Math.random()*1000000);
			self.__previousState = 'closed';
			self.__options;
			
			// initial formatting
			self.__optionsFormat();
			
			self.__instance._on('state.'+ self.__namespace, function(event) {
				
				if (event.state == 'closed') {
					self.__close();
				}
				else if (event.state == 'appearing' && self.__previousState == 'closed') {
					self.__create();
				}
				
				self.__previousState = event.state;
			});
			
			// reformat every time the options are changed
			self.__instance._on('options.'+ self.__namespace, function() {
				self.__optionsFormat();
			});
			
			self.__instance._on('reposition.'+ self.__namespace, function(e) {
				self.__reposition(e.event, e.helper);
			});
		},
		
		/**
		 * Called when the tooltip has closed
		 * 
		 * @private
		 */
		__close: function() {
			
			// detach our content object first, so the next jQuery's remove()
			// call does not unbind its event handlers
			if (this.__instance.content() instanceof $) {
				this.__instance.content().detach();
			}
			
			// remove the tooltip from the DOM
			this.__instance._$tooltip.remove();
			this.__instance._$tooltip = null;
		},
		
		/**
		 * Creates the HTML element of the tooltip.
		 * 
		 * @private
		 */
		__create: function() {
			
			// note: we wrap with a .tooltipster-box div to be able to set a margin on it
			// (.tooltipster-base must not have one)
			var $html = $(
				'<div class="tooltipster-base tooltipster-sidetip">' +
					'<div class="tooltipster-box">' +
						'<div class="tooltipster-content"></div>' +
					'</div>' +
					'<div class="tooltipster-arrow">' +
						'<div class="tooltipster-arrow-uncropped">' +
							'<div class="tooltipster-arrow-border"></div>' +
							'<div class="tooltipster-arrow-background"></div>' +
						'</div>' +
					'</div>' +
				'</div>'
			);
			
			// hide arrow if asked
			if (!this.__options.arrow) {
				$html
					.find('.tooltipster-box')
						.css('margin', 0)
						.end()
					.find('.tooltipster-arrow')
						.hide();
			}
			
			// apply min/max width if asked
			if (this.__options.minWidth) {
				$html.css('min-width', this.__options.minWidth + 'px');
			}
			if (this.__options.maxWidth) {
				$html.css('max-width', this.__options.maxWidth + 'px');
			}
			
			this.__instance._$tooltip = $html;
			
			// tell the instance that the tooltip element has been created
			this.__instance._trigger('created');
		},
		
		/**
		 * Used when the plugin is to be unplugged
		 *
		 * @private
		 */
		__destroy: function() {
			this.__instance._off('.'+ self.__namespace);
		},
		
		/**
		 * (Re)compute this.__options from the options declared to the instance
		 *
		 * @private
		 */
		__optionsFormat: function() {
			
			var self = this;
			
			// get the options
			self.__options = self.__instance._optionsExtract(pluginName, self.__defaults());
			
			// for backward compatibility, deprecated in v4.0.0
			if (self.__options.position) {
				self.__options.side = self.__options.position;
			}
			
			// options formatting
			
			// format distance as a four-cell array if it ain't one yet and then make
			// it an object with top/bottom/left/right properties
			if (typeof self.__options.distance != 'object') {
				self.__options.distance = [self.__options.distance];
			}
			if (self.__options.distance.length < 4) {
				
				if (self.__options.distance[1] === undefined) self.__options.distance[1] = self.__options.distance[0];
				if (self.__options.distance[2] === undefined) self.__options.distance[2] = self.__options.distance[0];
				if (self.__options.distance[3] === undefined) self.__options.distance[3] = self.__options.distance[1];
				
				self.__options.distance = {
					top: self.__options.distance[0],
					right: self.__options.distance[1],
					bottom: self.__options.distance[2],
					left: self.__options.distance[3]
				};
			}
			
			// let's transform:
			// 'top' into ['top', 'bottom', 'right', 'left']
			// 'right' into ['right', 'left', 'top', 'bottom']
			// 'bottom' into ['bottom', 'top', 'right', 'left']
			// 'left' into ['left', 'right', 'top', 'bottom']
			if (typeof self.__options.side == 'string') {
				
				var opposites = {
					'top': 'bottom',
					'right': 'left',
					'bottom': 'top',
					'left': 'right'
				};
				
				self.__options.side = [self.__options.side, opposites[self.__options.side]];
				
				if (self.__options.side[0] == 'left' || self.__options.side[0] == 'right') {
					self.__options.side.push('top', 'bottom');
				}
				else {
					self.__options.side.push('right', 'left');
				}
			}
			
			// misc
			// disable the arrow in IE6 unless the arrow option was explicitly set to true
			if (	$.tooltipster._env.IE === 6
				&&	self.__options.arrow !== true
			) {
				self.__options.arrow = false;
			}
		},
		
		/**
		 * This method must compute and set the positioning properties of the
		 * tooltip (left, top, width, height, etc.). It must also make sure the
		 * tooltip is eventually appended to its parent (since the element may be
		 * detached from the DOM at the moment the method is called).
		 *
		 * We'll evaluate positioning scenarios to find which side can contain the
		 * tooltip in the best way. We'll consider things relatively to the window
		 * (unless the user asks not to), then to the document (if need be, or if the
		 * user explicitly requires the tests to run on the document). For each
		 * scenario, measures are taken, allowing us to know how well the tooltip
		 * is going to fit. After that, a sorting function will let us know what
		 * the best scenario is (we also allow the user to choose his favorite
		 * scenario by using an event).
		 * 
		 * @param {object} helper An object that contains variables that plugin
		 * creators may find useful (see below)
		 * @param {object} helper.geo An object with many layout properties
		 * about objects of interest (window, document, origin). This should help
		 * plugin users compute the optimal position of the tooltip
		 * @private
		 */
		__reposition: function(event, helper) {
			
			var self = this,
				finalResult,
				// to know where to put the tooltip, we need to know on which point
				// of the x or y axis we should center it. That coordinate is the target
				targets = self.__targetFind(helper),
				testResults = [];
			
			// make sure the tooltip is detached while we make tests on a clone
			self.__instance._$tooltip.detach();
			
			// we could actually provide the original element to the Ruler and
			// not a clone, but it just feels right to keep it out of the
			// machinery.
			var $clone = self.__instance._$tooltip.clone(),
				// start position tests session
				ruler = $.tooltipster._getRuler($clone),
				satisfied = false,
				animation = self.__instance.option('animation');
			
			// an animation class could contain properties that distort the size
			if (animation) {
				$clone.removeClass('tooltipster-'+ animation);
			}
			
			// start evaluating scenarios
			$.each(['window', 'document'], function(i, container) {
				
				var takeTest = null;
				
				// let the user decide to keep on testing or not
				self.__instance._trigger({
					container: container,
					helper: helper,
					satisfied: satisfied,
					takeTest: function(bool) {
						takeTest = bool;
					},
					results: testResults,
					type: 'positionTest'
				});
				
				if (	takeTest == true
					||	(	takeTest != false
						&&	satisfied == false
							// skip the window scenarios if asked. If they are reintegrated by
							// the callback of the positionTest event, they will have to be
							// excluded using the callback of positionTested
						&&	(container != 'window' || self.__options.viewportAware)
					)
				) {
					
					// for each allowed side
					for (var i=0; i < self.__options.side.length; i++) {
						
						var distance = {
								horizontal: 0,
								vertical: 0
							},
							side = self.__options.side[i];
						
						if (side == 'top' || side == 'bottom') {
							distance.vertical = self.__options.distance[side];
						}
						else {
							distance.horizontal = self.__options.distance[side];
						}
						
						// this may have an effect on the size of the tooltip if there are css
						// rules for the arrow or something else
						self.__sideChange($clone, side);
						
						$.each(['natural', 'constrained'], function(i, mode) {
							
							takeTest = null;
							
							// emit an event on the instance
							self.__instance._trigger({
								container: container,
								event: event,
								helper: helper,
								mode: mode,
								results: testResults,
								satisfied: satisfied,
								side: side,
								takeTest: function(bool) {
									takeTest = bool;
								},
								type: 'positionTest'
							});
							
							if (	takeTest == true
								||	(	takeTest != false
									&&	satisfied == false
								)
							) {
								
								var testResult = {
									container: container,
									// we let the distance as an object here, it can make things a little easier
									// during the user's calculations at positionTest/positionTested
									distance: distance,
									// whether the tooltip can fit in the size of the viewport (does not mean
									// that we'll be able to make it initially entirely visible, see 'whole')
									fits: null,
									mode: mode,
									outerSize: null,
									side: side,
									size: null,
									target: targets[side],
									// check if the origin has enough surface on screen for the tooltip to
									// aim at it without overflowing the viewport (this is due to the thickness
									// of the arrow represented by the minIntersection length).
									// If not, the tooltip will have to be partly or entirely off screen in
									// order to stay docked to the origin. This value will stay null when the
									// container is the document, as it is not relevant
									whole: null
								};
								
								// get the size of the tooltip with or without size constraints
								var rulerConfigured = (mode == 'natural') ?
										ruler.free() :
										ruler.constrain(
											helper.geo.available[container][side].width - distance.horizontal,
											helper.geo.available[container][side].height - distance.vertical
										),
									rulerResults = rulerConfigured.measure();
								
								testResult.size = rulerResults.size;
								testResult.outerSize = {
									height: rulerResults.size.height + distance.vertical,
									width: rulerResults.size.width + distance.horizontal
								};
								
								if (mode == 'natural') {
									
									if(		helper.geo.available[container][side].width >= testResult.outerSize.width
										&&	helper.geo.available[container][side].height >= testResult.outerSize.height
									) {
										testResult.fits = true;
									}
									else {
										testResult.fits = false;
									}
								}
								else {
									testResult.fits = rulerResults.fits;
								}
								
								if (container == 'window') {
									
									if (!testResult.fits) {
										testResult.whole = false;
									}
									else {
										if (side == 'top' || side == 'bottom') {
											
											testResult.whole = (
													helper.geo.origin.windowOffset.right >= self.__options.minIntersection
												&&	helper.geo.window.size.width - helper.geo.origin.windowOffset.left >= self.__options.minIntersection
											);
										}
										else {
											testResult.whole = (
													helper.geo.origin.windowOffset.bottom >= self.__options.minIntersection
												&&	helper.geo.window.size.height - helper.geo.origin.windowOffset.top >= self.__options.minIntersection
											);
										}
									}
								}
								
								testResults.push(testResult);
								
								// we don't need to compute more positions if we have one fully on screen
								if (testResult.whole) {
									satisfied = true;
								}
								else {
									// don't run the constrained test unless the natural width was greater
									// than the available width, otherwise it's pointless as we know it
									// wouldn't fit either
									if (	testResult.mode == 'natural'
										&&	(	testResult.fits
											||	testResult.size.width <= helper.geo.available[container][side].width
										)
									) {
										return false;
									}
								}
							}
						});
					}
				}
			});
			
			// the user may eliminate the unwanted scenarios from testResults, but he's
			// not supposed to alter them at this point. functionPosition and the
			// position event serve that purpose.
			self.__instance._trigger({
				edit: function(r) {
					testResults = r;
				},
				event: event,
				helper: helper,
				results: testResults,
				type: 'positionTested'
			});
			
			/**
			 * Sort the scenarios to find the favorite one.
			 * 
			 * The favorite scenario is when we can fully display the tooltip on screen,
			 * even if it means that the middle of the tooltip is no longer centered on
			 * the middle of the origin (when the origin is near the edge of the screen
			 * or even partly off screen). We want the tooltip on the preferred side,
			 * even if it means that we have to use a constrained size rather than a
			 * natural one (as long as it fits). When the origin is off screen at the top
			 * the tooltip will be positioned at the bottom (if allowed), if the origin
			 * is off screen on the right, it will be positioned on the left, etc.
			 * If there are no scenarios where the tooltip can fit on screen, or if the
			 * user does not want the tooltip to fit on screen (viewportAware == false),
			 * we fall back to the scenarios relative to the document.
			 * 
			 * When the tooltip is bigger than the viewport in either dimension, we stop
			 * looking at the window scenarios and consider the document scenarios only,
			 * with the same logic to find on which side it would fit best.
			 * 
			 * If the tooltip cannot fit the document on any side, we force it at the
			 * bottom, so at least the user can scroll to see it.
 			 */
			testResults.sort(function(a, b) {
				
				// best if it's whole (the tooltip fits and adapts to the viewport)
				if (a.whole && !b.whole) {
					return -1;
				}
				else if (!a.whole && b.whole) {
					return 1;
				}
				else if (a.whole && b.whole) {
					
					var ai = self.__options.side.indexOf(a.side),
						bi = self.__options.side.indexOf(b.side);
					
					// use the user's sides fallback array
					if (ai < bi) {
						return -1;
					}
					else if (ai > bi) {
						return 1;
					}
					else {
						// will be used if the user forced the tests to continue
						return a.mode == 'natural' ? -1 : 1;
					}
				}
				else {
					
					// better if it fits
					if (a.fits && !b.fits) {
						return -1;
					}
					else if (!a.fits && b.fits) {
						return 1;
					}
					else if (a.fits && b.fits) {
						
						var ai = self.__options.side.indexOf(a.side),
							bi = self.__options.side.indexOf(b.side);
						
						// use the user's sides fallback array
						if (ai < bi) {
							return -1;
						}
						else if (ai > bi) {
							return 1;
						}
						else {
							// will be used if the user forced the tests to continue
							return a.mode == 'natural' ? -1 : 1;
						}
					}
					else {
						
						// if everything failed, this will give a preference to the case where
						// the tooltip overflows the document at the bottom
						if (	a.container == 'document'
							&&	a.side == 'bottom'
							&&	a.mode == 'natural'
						) {
							return -1;
						}
						else {
							return 1;
						}
					}
				}
			});
			
			finalResult = testResults[0];
			
			
			// now let's find the coordinates of the tooltip relatively to the window
			finalResult.coord = {};
			
			switch (finalResult.side) {
				
				case 'left':
				case 'right':
					finalResult.coord.top = Math.floor(finalResult.target - finalResult.size.height / 2);
					break;
				
				case 'bottom':
				case 'top':
					finalResult.coord.left = Math.floor(finalResult.target - finalResult.size.width / 2);
					break;
			}
			
			switch (finalResult.side) {
				
				case 'left':
					finalResult.coord.left = helper.geo.origin.windowOffset.left - finalResult.outerSize.width;
					break;
				
				case 'right':
					finalResult.coord.left = helper.geo.origin.windowOffset.right + finalResult.distance.horizontal;
					break;
				
				case 'top':
					finalResult.coord.top = helper.geo.origin.windowOffset.top - finalResult.outerSize.height;
					break;
				
				case 'bottom':
					finalResult.coord.top = helper.geo.origin.windowOffset.bottom + finalResult.distance.vertical;
					break;
			}
			
			// if the tooltip can potentially be contained within the viewport dimensions
			// and that we are asked to make it fit on screen
			if (finalResult.container == 'window') {
				
				// if the tooltip overflows the viewport, we'll move it accordingly (then it will
				// not be centered on the middle of the origin anymore). We only move horizontally
				// for top and bottom tooltips and vice versa.
				if (finalResult.side == 'top' || finalResult.side == 'bottom') {
					
					// if there is an overflow on the left
					if (finalResult.coord.left < 0) {
						
						// prevent the overflow unless the origin itself gets off screen (minus the
						// margin needed to keep the arrow pointing at the target)
						if (helper.geo.origin.windowOffset.right - this.__options.minIntersection >= 0) {
							finalResult.coord.left = 0;
						}
						else {
							finalResult.coord.left = helper.geo.origin.windowOffset.right - this.__options.minIntersection - 1;
						}
					}
					// or an overflow on the right
					else if (finalResult.coord.left > helper.geo.window.size.width - finalResult.size.width) {
						
						if (helper.geo.origin.windowOffset.left + this.__options.minIntersection <= helper.geo.window.size.width) {
							finalResult.coord.left = helper.geo.window.size.width - finalResult.size.width;
						}
						else {
							finalResult.coord.left = helper.geo.origin.windowOffset.left + this.__options.minIntersection + 1 - finalResult.size.width;
						}
					}
				}
				else {
					
					// overflow at the top
					if (finalResult.coord.top < 0) {
						
						if (helper.geo.origin.windowOffset.bottom - this.__options.minIntersection >= 0) {
							finalResult.coord.top = 0;
						}
						else {
							finalResult.coord.top = helper.geo.origin.windowOffset.bottom - this.__options.minIntersection - 1;
						}
					}
					// or at the bottom
					else if (finalResult.coord.top > helper.geo.window.size.height - finalResult.size.height) {
						
						if (helper.geo.origin.windowOffset.top + this.__options.minIntersection <= helper.geo.window.size.height) {
							finalResult.coord.top = helper.geo.window.size.height - finalResult.size.height;
						}
						else {
							finalResult.coord.top = helper.geo.origin.windowOffset.top + this.__options.minIntersection + 1 - finalResult.size.height;
						}
					}
				}
			}
			else {
				
				// there might be overflow here too but it's easier to handle. If there has
				// to be an overflow, we'll make sure it's on the right side of the screen
				// (because the browser will extend the document size if there is an overflow
				// on the right, but not on the left). The sort function above has already
				// made sure that a bottom document overflow is preferred to a top overflow,
				// so we don't have to care about it.
				
				// if there is an overflow on the right
				if (finalResult.coord.left > helper.geo.window.size.width - finalResult.size.width) {
					
					// this may actually create on overflow on the left but we'll fix it in a sec
					finalResult.coord.left = helper.geo.window.size.width - finalResult.size.width;
				}
				
				// if there is an overflow on the left
				if (finalResult.coord.left < 0) {
					
					// don't care if it overflows the right after that, we made our best
					finalResult.coord.left = 0;
				}
			}
			
			
			// submit the positioning proposal to the user function which may choose to change
			// the side, size and/or the coordinates
			
			// first, set the rules that corresponds to the proposed side: it may change
			// the size of the tooltip, and the custom functionPosition may want to detect the
			// size of something before making a decision. So let's make things easier for the
			// implementor
			self.__sideChange($clone, finalResult.side);
			
			// add some variables to the helper
			helper.tooltipClone = $clone[0];
			helper.tooltipParent = self.__instance.option('parent').parent[0];
			// move informative values to the helper
			helper.mode = finalResult.mode;
			helper.whole = finalResult.whole;
			// add some variables to the helper for the functionPosition callback (these
			// will also be added to the event fired by self.__instance._trigger but that's
			// ok, we're just being consistent)
			helper.origin = self.__instance._$origin[0];
			helper.tooltip = self.__instance._$tooltip[0];
			
			// leave only the actionable values in there for functionPosition
			delete finalResult.container;
			delete finalResult.fits;
			delete finalResult.mode;
			delete finalResult.outerSize;
			delete finalResult.whole;
			
			// keep only the distance on the relevant side, for clarity
			finalResult.distance = finalResult.distance.horizontal || finalResult.distance.vertical;
			
			// beginners may not be comfortable with the concept of editing the object
			//  passed by reference, so we provide an edit function and pass a clone
			var finalResultClone = $.extend(true, {}, finalResult);
			
			// emit an event on the instance
			self.__instance._trigger({
				edit: function(result) {
					finalResult = result;
				},
				event: event,
				helper: helper,
				position: finalResultClone,
				type: 'position'
			});
			
			if (self.__options.functionPosition) {
				
				var result = self.__options.functionPosition.call(self, self.__instance, helper, finalResultClone);
				
				if (result) finalResult = result;
			}
			
			// end the positioning tests session (the user might have had a
			// use for it during the position event, now it's over)
			ruler.destroy();
			
			// compute the position of the target relatively to the tooltip root
			// element so we can place the arrow and make the needed adjustments
			var arrowCoord,
				maxVal;
			
			if (finalResult.side == 'top' || finalResult.side == 'bottom') {
				
				arrowCoord = {
					prop: 'left',
					val: finalResult.target - finalResult.coord.left
				};
				maxVal = finalResult.size.width - this.__options.minIntersection;
			}
			else {
				
				arrowCoord = {
					prop: 'top',
					val: finalResult.target - finalResult.coord.top
				};
				maxVal = finalResult.size.height - this.__options.minIntersection;
			}
			
			// cannot lie beyond the boundaries of the tooltip, minus the
			// arrow margin
			if (arrowCoord.val < this.__options.minIntersection) {
				arrowCoord.val = this.__options.minIntersection;
			}
			else if (arrowCoord.val > maxVal) {
				arrowCoord.val = maxVal;
			}
			
			var originParentOffset;
			
			// let's convert the window-relative coordinates into coordinates relative to the
			// future positioned parent that the tooltip will be appended to
			if (helper.geo.origin.fixedLineage) {
				
				// same as windowOffset when the position is fixed
				originParentOffset = helper.geo.origin.windowOffset;
			}
			else {
				
				// this assumes that the parent of the tooltip is located at
				// (0, 0) in the document, typically like when the parent is
				// <body>.
				// If we ever allow other types of parent, .tooltipster-ruler
				// will have to be appended to the parent to inherit css style
				// values that affect the display of the text and such.
				originParentOffset = {
					left: helper.geo.origin.windowOffset.left + helper.geo.window.scroll.left,
					top: helper.geo.origin.windowOffset.top + helper.geo.window.scroll.top
				};
			}
			
			finalResult.coord = {
				left: originParentOffset.left + (finalResult.coord.left - helper.geo.origin.windowOffset.left),
				top: originParentOffset.top + (finalResult.coord.top - helper.geo.origin.windowOffset.top)
			};
			
			// set position values on the original tooltip element
			
			self.__sideChange(self.__instance._$tooltip, finalResult.side);
			
			if (helper.geo.origin.fixedLineage) {
				self.__instance._$tooltip
					.css('position', 'fixed');
			}
			else {
				// CSS default
				self.__instance._$tooltip
					.css('position', '');
			}
			
			self.__instance._$tooltip
				.css({
					left: finalResult.coord.left,
					top: finalResult.coord.top,
					// we need to set a size even if the tooltip is in its natural size
					// because when the tooltip is positioned beyond the width of the body
					// (which is by default the width of the window; it will happen when
					// you scroll the window horizontally to get to the origin), its text
					// content will otherwise break lines at each word to keep up with the
					// body overflow strategy.
					height: finalResult.size.height,
					width: finalResult.size.width
				})
				.find('.tooltipster-arrow')
					.css({
						'left': '',
						'top': ''
					})
					.css(arrowCoord.prop, arrowCoord.val);
			
			// append the tooltip HTML element to its parent
			self.__instance._$tooltip.appendTo(self.__instance.option('parent'));
			
			self.__instance._trigger({
				type: 'repositioned',
				event: event,
				position: finalResult
			});
		},
		
		/**
		 * Make whatever modifications are needed when the side is changed. This has
		 * been made an independant method for easy inheritance in custom plugins based
		 * on this default plugin.
		 *
		 * @param {object} $obj
		 * @param {string} side
		 * @private
		 */
		__sideChange: function($obj, side) {
			
			$obj
				.removeClass('tooltipster-bottom')
				.removeClass('tooltipster-left')
				.removeClass('tooltipster-right')
				.removeClass('tooltipster-top')
				.addClass('tooltipster-'+ side);
		},
		
		/**
		 * Returns the target that the tooltip should aim at for a given side.
		 * The calculated value is a distance from the edge of the window
		 * (left edge for top/bottom sides, top edge for left/right side). The
		 * tooltip will be centered on that position and the arrow will be
		 * positioned there (as much as possible).
		 *
		 * @param {object} helper
		 * @return {integer}
		 * @private
		 */
		__targetFind: function(helper) {
			
			var target = {},
				rects = this.__instance._$origin[0].getClientRects();
			
			// these lines fix a Chrome bug (issue #491)
			if (rects.length > 1) {
				var opacity = this.__instance._$origin.css('opacity');
				if(opacity == 1) {
					this.__instance._$origin.css('opacity', 0.99);
					rects = this.__instance._$origin[0].getClientRects();
					this.__instance._$origin.css('opacity', 1);
				}
			}
			
			// by default, the target will be the middle of the origin
			if (rects.length < 2) {
				
				target.top = Math.floor(helper.geo.origin.windowOffset.left + (helper.geo.origin.size.width / 2));
				target.bottom = target.top;
				
				target.left = Math.floor(helper.geo.origin.windowOffset.top + (helper.geo.origin.size.height / 2));
				target.right = target.left;
			}
			// if multiple client rects exist, the element may be text split
			// up into multiple lines and the middle of the origin may not be
			// best option anymore. We need to choose the best target client rect
			else {
				
				// top: the first
				var targetRect = rects[0];
				target.top = Math.floor(targetRect.left + (targetRect.right - targetRect.left) / 2);
		
				// right: the middle line, rounded down in case there is an even
				// number of lines (looks more centered => check out the
				// demo with 4 split lines)
				if (rects.length > 2) {
					targetRect = rects[Math.ceil(rects.length / 2) - 1];
				}
				else {
					targetRect = rects[0];
				}
				target.right = Math.floor(targetRect.top + (targetRect.bottom - targetRect.top) / 2);
		
				// bottom: the last
				targetRect = rects[rects.length - 1];
				target.bottom = Math.floor(targetRect.left + (targetRect.right - targetRect.left) / 2);
		
				// left: the middle line, rounded up
				if (rects.length > 2) {
					targetRect = rects[Math.ceil((rects.length + 1) / 2) - 1];
				}
				else {
					targetRect = rects[rects.length - 1];
				}
				
				target.left = Math.floor(targetRect.top + (targetRect.bottom - targetRect.top) / 2);
			}
			
			return target;
		}
	}
});

/* a build task will add "return $;" here */
return $;

}));

/*!
 * Isotope PACKAGED v3.0.6
 *
 * Licensed GPLv3 for open source use
 * or Isotope Commercial License for commercial use
 *
 * https://isotope.metafizzy.co
 * Copyright 2010-2018 Metafizzy
 */

/**
 * Bridget makes jQuery widgets
 * v2.0.1
 * MIT license
 */

/* jshint browser: true, strict: true, undef: true, unused: true */

( function( window, factory ) {
  // universal module definition
  /*jshint strict: false */ /* globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'jquery-bridget/jquery-bridget',[ 'jquery' ], function( jQuery ) {
      return factory( window, jQuery );
    });
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      window,
      require('jquery')
    );
  } else {
    // browser global
    window.jQueryBridget = factory(
      window,
      window.jQuery
    );
  }

}( window, function factory( window, jQuery ) {
'use strict';

// ----- utils ----- //

var arraySlice = Array.prototype.slice;

// helper function for logging errors
// $.error breaks jQuery chaining
var console = window.console;
var logError = typeof console == 'undefined' ? function() {} :
  function( message ) {
    console.error( message );
  };

// ----- jQueryBridget ----- //

function jQueryBridget( namespace, PluginClass, $ ) {
  $ = $ || jQuery || window.jQuery;
  if ( !$ ) {
    return;
  }

  // add option method -> $().plugin('option', {...})
  if ( !PluginClass.prototype.option ) {
    // option setter
    PluginClass.prototype.option = function( opts ) {
      // bail out if not an object
      if ( !$.isPlainObject( opts ) ){
        return;
      }
      this.options = $.extend( true, this.options, opts );
    };
  }

  // make jQuery plugin
  $.fn[ namespace ] = function( arg0 /*, arg1 */ ) {
    if ( typeof arg0 == 'string' ) {
      // method call $().plugin( 'methodName', { options } )
      // shift arguments by 1
      var args = arraySlice.call( arguments, 1 );
      return methodCall( this, arg0, args );
    }
    // just $().plugin({ options })
    plainCall( this, arg0 );
    return this;
  };

  // $().plugin('methodName')
  function methodCall( $elems, methodName, args ) {
    var returnValue;
    var pluginMethodStr = '$().' + namespace + '("' + methodName + '")';

    $elems.each( function( i, elem ) {
      // get instance
      var instance = $.data( elem, namespace );
      if ( !instance ) {
        logError( namespace + ' not initialized. Cannot call methods, i.e. ' +
          pluginMethodStr );
        return;
      }

      var method = instance[ methodName ];
      if ( !method || methodName.charAt(0) == '_' ) {
        logError( pluginMethodStr + ' is not a valid method' );
        return;
      }

      // apply method, get return value
      var value = method.apply( instance, args );
      // set return value if value is returned, use only first value
      returnValue = returnValue === undefined ? value : returnValue;
    });

    return returnValue !== undefined ? returnValue : $elems;
  }

  function plainCall( $elems, options ) {
    $elems.each( function( i, elem ) {
      var instance = $.data( elem, namespace );
      if ( instance ) {
        // set options & init
        instance.option( options );
        instance._init();
      } else {
        // initialize new instance
        instance = new PluginClass( elem, options );
        $.data( elem, namespace, instance );
      }
    });
  }

  updateJQuery( $ );

}

// ----- updateJQuery ----- //

// set $.bridget for v1 backwards compatibility
function updateJQuery( $ ) {
  if ( !$ || ( $ && $.bridget ) ) {
    return;
  }
  $.bridget = jQueryBridget;
}

updateJQuery( jQuery || window.jQuery );

// -----  ----- //

return jQueryBridget;

}));

/**
 * EvEmitter v1.1.0
 * Lil' event emitter
 * MIT License
 */

/* jshint unused: true, undef: true, strict: true */

( function( global, factory ) {
  // universal module definition
  /* jshint strict: false */ /* globals define, module, window */
  if ( typeof define == 'function' && define.amd ) {
    // AMD - RequireJS
    define( 'ev-emitter/ev-emitter',factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS - Browserify, Webpack
    module.exports = factory();
  } else {
    // Browser globals
    global.EvEmitter = factory();
  }

}( typeof window != 'undefined' ? window : this, function() {



function EvEmitter() {}

var proto = EvEmitter.prototype;

proto.on = function( eventName, listener ) {
  if ( !eventName || !listener ) {
    return;
  }
  // set events hash
  var events = this._events = this._events || {};
  // set listeners array
  var listeners = events[ eventName ] = events[ eventName ] || [];
  // only add once
  if ( listeners.indexOf( listener ) == -1 ) {
    listeners.push( listener );
  }

  return this;
};

proto.once = function( eventName, listener ) {
  if ( !eventName || !listener ) {
    return;
  }
  // add event
  this.on( eventName, listener );
  // set once flag
  // set onceEvents hash
  var onceEvents = this._onceEvents = this._onceEvents || {};
  // set onceListeners object
  var onceListeners = onceEvents[ eventName ] = onceEvents[ eventName ] || {};
  // set flag
  onceListeners[ listener ] = true;

  return this;
};

proto.off = function( eventName, listener ) {
  var listeners = this._events && this._events[ eventName ];
  if ( !listeners || !listeners.length ) {
    return;
  }
  var index = listeners.indexOf( listener );
  if ( index != -1 ) {
    listeners.splice( index, 1 );
  }

  return this;
};

proto.emitEvent = function( eventName, args ) {
  var listeners = this._events && this._events[ eventName ];
  if ( !listeners || !listeners.length ) {
    return;
  }
  // copy over to avoid interference if .off() in listener
  listeners = listeners.slice(0);
  args = args || [];
  // once stuff
  var onceListeners = this._onceEvents && this._onceEvents[ eventName ];

  for ( var i=0; i < listeners.length; i++ ) {
    var listener = listeners[i]
    var isOnce = onceListeners && onceListeners[ listener ];
    if ( isOnce ) {
      // remove listener
      // remove before trigger to prevent recursion
      this.off( eventName, listener );
      // unset once flag
      delete onceListeners[ listener ];
    }
    // trigger listener
    listener.apply( this, args );
  }

  return this;
};

proto.allOff = function() {
  delete this._events;
  delete this._onceEvents;
};

return EvEmitter;

}));

/*!
 * getSize v2.0.3
 * measure size of elements
 * MIT license
 */

/* jshint browser: true, strict: true, undef: true, unused: true */
/* globals console: false */

( function( window, factory ) {
  /* jshint strict: false */ /* globals define, module */
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'get-size/get-size',factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory();
  } else {
    // browser global
    window.getSize = factory();
  }

})( window, function factory() {
'use strict';

// -------------------------- helpers -------------------------- //

// get a number from a string, not a percentage
function getStyleSize( value ) {
  var num = parseFloat( value );
  // not a percent like '100%', and a number
  var isValid = value.indexOf('%') == -1 && !isNaN( num );
  return isValid && num;
}

function noop() {}

var logError = typeof console == 'undefined' ? noop :
  function( message ) {
    console.error( message );
  };

// -------------------------- measurements -------------------------- //

var measurements = [
  'paddingLeft',
  'paddingRight',
  'paddingTop',
  'paddingBottom',
  'marginLeft',
  'marginRight',
  'marginTop',
  'marginBottom',
  'borderLeftWidth',
  'borderRightWidth',
  'borderTopWidth',
  'borderBottomWidth'
];

var measurementsLength = measurements.length;

function getZeroSize() {
  var size = {
    width: 0,
    height: 0,
    innerWidth: 0,
    innerHeight: 0,
    outerWidth: 0,
    outerHeight: 0
  };
  for ( var i=0; i < measurementsLength; i++ ) {
    var measurement = measurements[i];
    size[ measurement ] = 0;
  }
  return size;
}

// -------------------------- getStyle -------------------------- //

/**
 * getStyle, get style of element, check for Firefox bug
 * https://bugzilla.mozilla.org/show_bug.cgi?id=548397
 */
function getStyle( elem ) {
  var style = getComputedStyle( elem );
  if ( !style ) {
    logError( 'Style returned ' + style +
      '. Are you running this code in a hidden iframe on Firefox? ' +
      'See https://bit.ly/getsizebug1' );
  }
  return style;
}

// -------------------------- setup -------------------------- //

var isSetup = false;

var isBoxSizeOuter;

/**
 * setup
 * check isBoxSizerOuter
 * do on first getSize() rather than on page load for Firefox bug
 */
function setup() {
  // setup once
  if ( isSetup ) {
    return;
  }
  isSetup = true;

  // -------------------------- box sizing -------------------------- //

  /**
   * Chrome & Safari measure the outer-width on style.width on border-box elems
   * IE11 & Firefox<29 measures the inner-width
   */
  var div = document.createElement('div');
  div.style.width = '200px';
  div.style.padding = '1px 2px 3px 4px';
  div.style.borderStyle = 'solid';
  div.style.borderWidth = '1px 2px 3px 4px';
  div.style.boxSizing = 'border-box';

  var body = document.body || document.documentElement;
  body.appendChild( div );
  var style = getStyle( div );
  // round value for browser zoom. desandro/masonry#928
  isBoxSizeOuter = Math.round( getStyleSize( style.width ) ) == 200;
  getSize.isBoxSizeOuter = isBoxSizeOuter;

  body.removeChild( div );
}

// -------------------------- getSize -------------------------- //

function getSize( elem ) {
  setup();

  // use querySeletor if elem is string
  if ( typeof elem == 'string' ) {
    elem = document.querySelector( elem );
  }

  // do not proceed on non-objects
  if ( !elem || typeof elem != 'object' || !elem.nodeType ) {
    return;
  }

  var style = getStyle( elem );

  // if hidden, everything is 0
  if ( style.display == 'none' ) {
    return getZeroSize();
  }

  var size = {};
  size.width = elem.offsetWidth;
  size.height = elem.offsetHeight;

  var isBorderBox = size.isBorderBox = style.boxSizing == 'border-box';

  // get all measurements
  for ( var i=0; i < measurementsLength; i++ ) {
    var measurement = measurements[i];
    var value = style[ measurement ];
    var num = parseFloat( value );
    // any 'auto', 'medium' value will be 0
    size[ measurement ] = !isNaN( num ) ? num : 0;
  }

  var paddingWidth = size.paddingLeft + size.paddingRight;
  var paddingHeight = size.paddingTop + size.paddingBottom;
  var marginWidth = size.marginLeft + size.marginRight;
  var marginHeight = size.marginTop + size.marginBottom;
  var borderWidth = size.borderLeftWidth + size.borderRightWidth;
  var borderHeight = size.borderTopWidth + size.borderBottomWidth;

  var isBorderBoxSizeOuter = isBorderBox && isBoxSizeOuter;

  // overwrite width and height if we can get it from style
  var styleWidth = getStyleSize( style.width );
  if ( styleWidth !== false ) {
    size.width = styleWidth +
      // add padding and border unless it's already including it
      ( isBorderBoxSizeOuter ? 0 : paddingWidth + borderWidth );
  }

  var styleHeight = getStyleSize( style.height );
  if ( styleHeight !== false ) {
    size.height = styleHeight +
      // add padding and border unless it's already including it
      ( isBorderBoxSizeOuter ? 0 : paddingHeight + borderHeight );
  }

  size.innerWidth = size.width - ( paddingWidth + borderWidth );
  size.innerHeight = size.height - ( paddingHeight + borderHeight );

  size.outerWidth = size.width + marginWidth;
  size.outerHeight = size.height + marginHeight;

  return size;
}

return getSize;

});

/**
 * matchesSelector v2.0.2
 * matchesSelector( element, '.selector' )
 * MIT license
 */

/*jshint browser: true, strict: true, undef: true, unused: true */

( function( window, factory ) {
  /*global define: false, module: false */
  'use strict';
  // universal module definition
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'desandro-matches-selector/matches-selector',factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory();
  } else {
    // browser global
    window.matchesSelector = factory();
  }

}( window, function factory() {
  'use strict';

  var matchesMethod = ( function() {
    var ElemProto = window.Element.prototype;
    // check for the standard method name first
    if ( ElemProto.matches ) {
      return 'matches';
    }
    // check un-prefixed
    if ( ElemProto.matchesSelector ) {
      return 'matchesSelector';
    }
    // check vendor prefixes
    var prefixes = [ 'webkit', 'moz', 'ms', 'o' ];

    for ( var i=0; i < prefixes.length; i++ ) {
      var prefix = prefixes[i];
      var method = prefix + 'MatchesSelector';
      if ( ElemProto[ method ] ) {
        return method;
      }
    }
  })();

  return function matchesSelector( elem, selector ) {
    return elem[ matchesMethod ]( selector );
  };

}));

/**
 * Fizzy UI utils v2.0.7
 * MIT license
 */

/*jshint browser: true, undef: true, unused: true, strict: true */

( function( window, factory ) {
  // universal module definition
  /*jshint strict: false */ /*globals define, module, require */

  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'fizzy-ui-utils/utils',[
      'desandro-matches-selector/matches-selector'
    ], function( matchesSelector ) {
      return factory( window, matchesSelector );
    });
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      window,
      require('desandro-matches-selector')
    );
  } else {
    // browser global
    window.fizzyUIUtils = factory(
      window,
      window.matchesSelector
    );
  }

}( window, function factory( window, matchesSelector ) {



var utils = {};

// ----- extend ----- //

// extends objects
utils.extend = function( a, b ) {
  for ( var prop in b ) {
    a[ prop ] = b[ prop ];
  }
  return a;
};

// ----- modulo ----- //

utils.modulo = function( num, div ) {
  return ( ( num % div ) + div ) % div;
};

// ----- makeArray ----- //

var arraySlice = Array.prototype.slice;

// turn element or nodeList into an array
utils.makeArray = function( obj ) {
  if ( Array.isArray( obj ) ) {
    // use object if already an array
    return obj;
  }
  // return empty array if undefined or null. #6
  if ( obj === null || obj === undefined ) {
    return [];
  }

  var isArrayLike = typeof obj == 'object' && typeof obj.length == 'number';
  if ( isArrayLike ) {
    // convert nodeList to array
    return arraySlice.call( obj );
  }

  // array of single index
  return [ obj ];
};

// ----- removeFrom ----- //

utils.removeFrom = function( ary, obj ) {
  var index = ary.indexOf( obj );
  if ( index != -1 ) {
    ary.splice( index, 1 );
  }
};

// ----- getParent ----- //

utils.getParent = function( elem, selector ) {
  while ( elem.parentNode && elem != document.body ) {
    elem = elem.parentNode;
    if ( matchesSelector( elem, selector ) ) {
      return elem;
    }
  }
};

// ----- getQueryElement ----- //

// use element as selector string
utils.getQueryElement = function( elem ) {
  if ( typeof elem == 'string' ) {
    return document.querySelector( elem );
  }
  return elem;
};

// ----- handleEvent ----- //

// enable .ontype to trigger from .addEventListener( elem, 'type' )
utils.handleEvent = function( event ) {
  var method = 'on' + event.type;
  if ( this[ method ] ) {
    this[ method ]( event );
  }
};

// ----- filterFindElements ----- //

utils.filterFindElements = function( elems, selector ) {
  // make array of elems
  elems = utils.makeArray( elems );
  var ffElems = [];

  elems.forEach( function( elem ) {
    // check that elem is an actual element
    if ( !( elem instanceof HTMLElement ) ) {
      return;
    }
    // add elem if no selector
    if ( !selector ) {
      ffElems.push( elem );
      return;
    }
    // filter & find items if we have a selector
    // filter
    if ( matchesSelector( elem, selector ) ) {
      ffElems.push( elem );
    }
    // find children
    var childElems = elem.querySelectorAll( selector );
    // concat childElems to filterFound array
    for ( var i=0; i < childElems.length; i++ ) {
      ffElems.push( childElems[i] );
    }
  });

  return ffElems;
};

// ----- debounceMethod ----- //

utils.debounceMethod = function( _class, methodName, threshold ) {
  threshold = threshold || 100;
  // original method
  var method = _class.prototype[ methodName ];
  var timeoutName = methodName + 'Timeout';

  _class.prototype[ methodName ] = function() {
    var timeout = this[ timeoutName ];
    clearTimeout( timeout );

    var args = arguments;
    var _this = this;
    this[ timeoutName ] = setTimeout( function() {
      method.apply( _this, args );
      delete _this[ timeoutName ];
    }, threshold );
  };
};

// ----- docReady ----- //

utils.docReady = function( callback ) {
  var readyState = document.readyState;
  if ( readyState == 'complete' || readyState == 'interactive' ) {
    // do async to allow for other scripts to run. metafizzy/flickity#441
    setTimeout( callback );
  } else {
    document.addEventListener( 'DOMContentLoaded', callback );
  }
};

// ----- htmlInit ----- //

// http://jamesroberts.name/blog/2010/02/22/string-functions-for-javascript-trim-to-camel-case-to-dashed-and-to-underscore/
utils.toDashed = function( str ) {
  return str.replace( /(.)([A-Z])/g, function( match, $1, $2 ) {
    return $1 + '-' + $2;
  }).toLowerCase();
};

var console = window.console;
/**
 * allow user to initialize classes via [data-namespace] or .js-namespace class
 * htmlInit( Widget, 'widgetName' )
 * options are parsed from data-namespace-options
 */
utils.htmlInit = function( WidgetClass, namespace ) {
  utils.docReady( function() {
    var dashedNamespace = utils.toDashed( namespace );
    var dataAttr = 'data-' + dashedNamespace;
    var dataAttrElems = document.querySelectorAll( '[' + dataAttr + ']' );
    var jsDashElems = document.querySelectorAll( '.js-' + dashedNamespace );
    var elems = utils.makeArray( dataAttrElems )
      .concat( utils.makeArray( jsDashElems ) );
    var dataOptionsAttr = dataAttr + '-options';
    var jQuery = window.jQuery;

    elems.forEach( function( elem ) {
      var attr = elem.getAttribute( dataAttr ) ||
        elem.getAttribute( dataOptionsAttr );
      var options;
      try {
        options = attr && JSON.parse( attr );
      } catch ( error ) {
        // log error, do not initialize
        if ( console ) {
          console.error( 'Error parsing ' + dataAttr + ' on ' + elem.className +
          ': ' + error );
        }
        return;
      }
      // initialize
      var instance = new WidgetClass( elem, options );
      // make available via $().data('namespace')
      if ( jQuery ) {
        jQuery.data( elem, namespace, instance );
      }
    });

  });
};

// -----  ----- //

return utils;

}));

/**
 * Outlayer Item
 */

( function( window, factory ) {
  // universal module definition
  /* jshint strict: false */ /* globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD - RequireJS
    define( 'outlayer/item',[
        'ev-emitter/ev-emitter',
        'get-size/get-size'
      ],
      factory
    );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS - Browserify, Webpack
    module.exports = factory(
      require('ev-emitter'),
      require('get-size')
    );
  } else {
    // browser global
    window.Outlayer = {};
    window.Outlayer.Item = factory(
      window.EvEmitter,
      window.getSize
    );
  }

}( window, function factory( EvEmitter, getSize ) {
'use strict';

// ----- helpers ----- //

function isEmptyObj( obj ) {
  for ( var prop in obj ) {
    return false;
  }
  prop = null;
  return true;
}

// -------------------------- CSS3 support -------------------------- //


var docElemStyle = document.documentElement.style;

var transitionProperty = typeof docElemStyle.transition == 'string' ?
  'transition' : 'WebkitTransition';
var transformProperty = typeof docElemStyle.transform == 'string' ?
  'transform' : 'WebkitTransform';

var transitionEndEvent = {
  WebkitTransition: 'webkitTransitionEnd',
  transition: 'transitionend'
}[ transitionProperty ];

// cache all vendor properties that could have vendor prefix
var vendorProperties = {
  transform: transformProperty,
  transition: transitionProperty,
  transitionDuration: transitionProperty + 'Duration',
  transitionProperty: transitionProperty + 'Property',
  transitionDelay: transitionProperty + 'Delay'
};

// -------------------------- Item -------------------------- //

function Item( element, layout ) {
  if ( !element ) {
    return;
  }

  this.element = element;
  // parent layout class, i.e. Masonry, Isotope, or Packery
  this.layout = layout;
  this.position = {
    x: 0,
    y: 0
  };

  this._create();
}

// inherit EvEmitter
var proto = Item.prototype = Object.create( EvEmitter.prototype );
proto.constructor = Item;

proto._create = function() {
  // transition objects
  this._transn = {
    ingProperties: {},
    clean: {},
    onEnd: {}
  };

  this.css({
    position: 'absolute'
  });
};

// trigger specified handler for event type
proto.handleEvent = function( event ) {
  var method = 'on' + event.type;
  if ( this[ method ] ) {
    this[ method ]( event );
  }
};

proto.getSize = function() {
  this.size = getSize( this.element );
};

/**
 * apply CSS styles to element
 * @param {Object} style
 */
proto.css = function( style ) {
  var elemStyle = this.element.style;

  for ( var prop in style ) {
    // use vendor property if available
    var supportedProp = vendorProperties[ prop ] || prop;
    elemStyle[ supportedProp ] = style[ prop ];
  }
};

 // measure position, and sets it
proto.getPosition = function() {
  var style = getComputedStyle( this.element );
  var isOriginLeft = this.layout._getOption('originLeft');
  var isOriginTop = this.layout._getOption('originTop');
  var xValue = style[ isOriginLeft ? 'left' : 'right' ];
  var yValue = style[ isOriginTop ? 'top' : 'bottom' ];
  var x = parseFloat( xValue );
  var y = parseFloat( yValue );
  // convert percent to pixels
  var layoutSize = this.layout.size;
  if ( xValue.indexOf('%') != -1 ) {
    x = ( x / 100 ) * layoutSize.width;
  }
  if ( yValue.indexOf('%') != -1 ) {
    y = ( y / 100 ) * layoutSize.height;
  }
  // clean up 'auto' or other non-integer values
  x = isNaN( x ) ? 0 : x;
  y = isNaN( y ) ? 0 : y;
  // remove padding from measurement
  x -= isOriginLeft ? layoutSize.paddingLeft : layoutSize.paddingRight;
  y -= isOriginTop ? layoutSize.paddingTop : layoutSize.paddingBottom;

  this.position.x = x;
  this.position.y = y;
};

// set settled position, apply padding
proto.layoutPosition = function() {
  var layoutSize = this.layout.size;
  var style = {};
  var isOriginLeft = this.layout._getOption('originLeft');
  var isOriginTop = this.layout._getOption('originTop');

  // x
  var xPadding = isOriginLeft ? 'paddingLeft' : 'paddingRight';
  var xProperty = isOriginLeft ? 'left' : 'right';
  var xResetProperty = isOriginLeft ? 'right' : 'left';

  var x = this.position.x + layoutSize[ xPadding ];
  // set in percentage or pixels
  style[ xProperty ] = this.getXValue( x );
  // reset other property
  style[ xResetProperty ] = '';

  // y
  var yPadding = isOriginTop ? 'paddingTop' : 'paddingBottom';
  var yProperty = isOriginTop ? 'top' : 'bottom';
  var yResetProperty = isOriginTop ? 'bottom' : 'top';

  var y = this.position.y + layoutSize[ yPadding ];
  // set in percentage or pixels
  style[ yProperty ] = this.getYValue( y );
  // reset other property
  style[ yResetProperty ] = '';

  this.css( style );
  this.emitEvent( 'layout', [ this ] );
};

proto.getXValue = function( x ) {
  var isHorizontal = this.layout._getOption('horizontal');
  return this.layout.options.percentPosition && !isHorizontal ?
    ( ( x / this.layout.size.width ) * 100 ) + '%' : x + 'px';
};

proto.getYValue = function( y ) {
  var isHorizontal = this.layout._getOption('horizontal');
  return this.layout.options.percentPosition && isHorizontal ?
    ( ( y / this.layout.size.height ) * 100 ) + '%' : y + 'px';
};

proto._transitionTo = function( x, y ) {
  this.getPosition();
  // get current x & y from top/left
  var curX = this.position.x;
  var curY = this.position.y;

  var didNotMove = x == this.position.x && y == this.position.y;

  // save end position
  this.setPosition( x, y );

  // if did not move and not transitioning, just go to layout
  if ( didNotMove && !this.isTransitioning ) {
    this.layoutPosition();
    return;
  }

  var transX = x - curX;
  var transY = y - curY;
  var transitionStyle = {};
  transitionStyle.transform = this.getTranslate( transX, transY );

  this.transition({
    to: transitionStyle,
    onTransitionEnd: {
      transform: this.layoutPosition
    },
    isCleaning: true
  });
};

proto.getTranslate = function( x, y ) {
  // flip cooridinates if origin on right or bottom
  var isOriginLeft = this.layout._getOption('originLeft');
  var isOriginTop = this.layout._getOption('originTop');
  x = isOriginLeft ? x : -x;
  y = isOriginTop ? y : -y;
  return 'translate3d(' + x + 'px, ' + y + 'px, 0)';
};

// non transition + transform support
proto.goTo = function( x, y ) {
  this.setPosition( x, y );
  this.layoutPosition();
};

proto.moveTo = proto._transitionTo;

proto.setPosition = function( x, y ) {
  this.position.x = parseFloat( x );
  this.position.y = parseFloat( y );
};

// ----- transition ----- //

/**
 * @param {Object} style - CSS
 * @param {Function} onTransitionEnd
 */

// non transition, just trigger callback
proto._nonTransition = function( args ) {
  this.css( args.to );
  if ( args.isCleaning ) {
    this._removeStyles( args.to );
  }
  for ( var prop in args.onTransitionEnd ) {
    args.onTransitionEnd[ prop ].call( this );
  }
};

/**
 * proper transition
 * @param {Object} args - arguments
 *   @param {Object} to - style to transition to
 *   @param {Object} from - style to start transition from
 *   @param {Boolean} isCleaning - removes transition styles after transition
 *   @param {Function} onTransitionEnd - callback
 */
proto.transition = function( args ) {
  // redirect to nonTransition if no transition duration
  if ( !parseFloat( this.layout.options.transitionDuration ) ) {
    this._nonTransition( args );
    return;
  }

  var _transition = this._transn;
  // keep track of onTransitionEnd callback by css property
  for ( var prop in args.onTransitionEnd ) {
    _transition.onEnd[ prop ] = args.onTransitionEnd[ prop ];
  }
  // keep track of properties that are transitioning
  for ( prop in args.to ) {
    _transition.ingProperties[ prop ] = true;
    // keep track of properties to clean up when transition is done
    if ( args.isCleaning ) {
      _transition.clean[ prop ] = true;
    }
  }

  // set from styles
  if ( args.from ) {
    this.css( args.from );
    // force redraw. http://blog.alexmaccaw.com/css-transitions
    var h = this.element.offsetHeight;
    // hack for JSHint to hush about unused var
    h = null;
  }
  // enable transition
  this.enableTransition( args.to );
  // set styles that are transitioning
  this.css( args.to );

  this.isTransitioning = true;

};

// dash before all cap letters, including first for
// WebkitTransform => -webkit-transform
function toDashedAll( str ) {
  return str.replace( /([A-Z])/g, function( $1 ) {
    return '-' + $1.toLowerCase();
  });
}

var transitionProps = 'opacity,' + toDashedAll( transformProperty );

proto.enableTransition = function(/* style */) {
  // HACK changing transitionProperty during a transition
  // will cause transition to jump
  if ( this.isTransitioning ) {
    return;
  }

  // make `transition: foo, bar, baz` from style object
  // HACK un-comment this when enableTransition can work
  // while a transition is happening
  // var transitionValues = [];
  // for ( var prop in style ) {
  //   // dash-ify camelCased properties like WebkitTransition
  //   prop = vendorProperties[ prop ] || prop;
  //   transitionValues.push( toDashedAll( prop ) );
  // }
  // munge number to millisecond, to match stagger
  var duration = this.layout.options.transitionDuration;
  duration = typeof duration == 'number' ? duration + 'ms' : duration;
  // enable transition styles
  this.css({
    transitionProperty: transitionProps,
    transitionDuration: duration,
    transitionDelay: this.staggerDelay || 0
  });
  // listen for transition end event
  this.element.addEventListener( transitionEndEvent, this, false );
};

// ----- events ----- //

proto.onwebkitTransitionEnd = function( event ) {
  this.ontransitionend( event );
};

proto.onotransitionend = function( event ) {
  this.ontransitionend( event );
};

// properties that I munge to make my life easier
var dashedVendorProperties = {
  '-webkit-transform': 'transform'
};

proto.ontransitionend = function( event ) {
  // disregard bubbled events from children
  if ( event.target !== this.element ) {
    return;
  }
  var _transition = this._transn;
  // get property name of transitioned property, convert to prefix-free
  var propertyName = dashedVendorProperties[ event.propertyName ] || event.propertyName;

  // remove property that has completed transitioning
  delete _transition.ingProperties[ propertyName ];
  // check if any properties are still transitioning
  if ( isEmptyObj( _transition.ingProperties ) ) {
    // all properties have completed transitioning
    this.disableTransition();
  }
  // clean style
  if ( propertyName in _transition.clean ) {
    // clean up style
    this.element.style[ event.propertyName ] = '';
    delete _transition.clean[ propertyName ];
  }
  // trigger onTransitionEnd callback
  if ( propertyName in _transition.onEnd ) {
    var onTransitionEnd = _transition.onEnd[ propertyName ];
    onTransitionEnd.call( this );
    delete _transition.onEnd[ propertyName ];
  }

  this.emitEvent( 'transitionEnd', [ this ] );
};

proto.disableTransition = function() {
  this.removeTransitionStyles();
  this.element.removeEventListener( transitionEndEvent, this, false );
  this.isTransitioning = false;
};

/**
 * removes style property from element
 * @param {Object} style
**/
proto._removeStyles = function( style ) {
  // clean up transition styles
  var cleanStyle = {};
  for ( var prop in style ) {
    cleanStyle[ prop ] = '';
  }
  this.css( cleanStyle );
};

var cleanTransitionStyle = {
  transitionProperty: '',
  transitionDuration: '',
  transitionDelay: ''
};

proto.removeTransitionStyles = function() {
  // remove transition
  this.css( cleanTransitionStyle );
};

// ----- stagger ----- //

proto.stagger = function( delay ) {
  delay = isNaN( delay ) ? 0 : delay;
  this.staggerDelay = delay + 'ms';
};

// ----- show/hide/remove ----- //

// remove element from DOM
proto.removeElem = function() {
  this.element.parentNode.removeChild( this.element );
  // remove display: none
  this.css({ display: '' });
  this.emitEvent( 'remove', [ this ] );
};

proto.remove = function() {
  // just remove element if no transition support or no transition
  if ( !transitionProperty || !parseFloat( this.layout.options.transitionDuration ) ) {
    this.removeElem();
    return;
  }

  // start transition
  this.once( 'transitionEnd', function() {
    this.removeElem();
  });
  this.hide();
};

proto.reveal = function() {
  delete this.isHidden;
  // remove display: none
  this.css({ display: '' });

  var options = this.layout.options;

  var onTransitionEnd = {};
  var transitionEndProperty = this.getHideRevealTransitionEndProperty('visibleStyle');
  onTransitionEnd[ transitionEndProperty ] = this.onRevealTransitionEnd;

  this.transition({
    from: options.hiddenStyle,
    to: options.visibleStyle,
    isCleaning: true,
    onTransitionEnd: onTransitionEnd
  });
};

proto.onRevealTransitionEnd = function() {
  // check if still visible
  // during transition, item may have been hidden
  if ( !this.isHidden ) {
    this.emitEvent('reveal');
  }
};

/**
 * get style property use for hide/reveal transition end
 * @param {String} styleProperty - hiddenStyle/visibleStyle
 * @returns {String}
 */
proto.getHideRevealTransitionEndProperty = function( styleProperty ) {
  var optionStyle = this.layout.options[ styleProperty ];
  // use opacity
  if ( optionStyle.opacity ) {
    return 'opacity';
  }
  // get first property
  for ( var prop in optionStyle ) {
    return prop;
  }
};

proto.hide = function() {
  // set flag
  this.isHidden = true;
  // remove display: none
  this.css({ display: '' });

  var options = this.layout.options;

  var onTransitionEnd = {};
  var transitionEndProperty = this.getHideRevealTransitionEndProperty('hiddenStyle');
  onTransitionEnd[ transitionEndProperty ] = this.onHideTransitionEnd;

  this.transition({
    from: options.visibleStyle,
    to: options.hiddenStyle,
    // keep hidden stuff hidden
    isCleaning: true,
    onTransitionEnd: onTransitionEnd
  });
};

proto.onHideTransitionEnd = function() {
  // check if still hidden
  // during transition, item may have been un-hidden
  if ( this.isHidden ) {
    this.css({ display: 'none' });
    this.emitEvent('hide');
  }
};

proto.destroy = function() {
  this.css({
    position: '',
    left: '',
    right: '',
    top: '',
    bottom: '',
    transition: '',
    transform: ''
  });
};

return Item;

}));

/*!
 * Outlayer v2.1.1
 * the brains and guts of a layout library
 * MIT license
 */

( function( window, factory ) {
  'use strict';
  // universal module definition
  /* jshint strict: false */ /* globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD - RequireJS
    define( 'outlayer/outlayer',[
        'ev-emitter/ev-emitter',
        'get-size/get-size',
        'fizzy-ui-utils/utils',
        './item'
      ],
      function( EvEmitter, getSize, utils, Item ) {
        return factory( window, EvEmitter, getSize, utils, Item);
      }
    );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS - Browserify, Webpack
    module.exports = factory(
      window,
      require('ev-emitter'),
      require('get-size'),
      require('fizzy-ui-utils'),
      require('./item')
    );
  } else {
    // browser global
    window.Outlayer = factory(
      window,
      window.EvEmitter,
      window.getSize,
      window.fizzyUIUtils,
      window.Outlayer.Item
    );
  }

}( window, function factory( window, EvEmitter, getSize, utils, Item ) {
'use strict';

// ----- vars ----- //

var console = window.console;
var jQuery = window.jQuery;
var noop = function() {};

// -------------------------- Outlayer -------------------------- //

// globally unique identifiers
var GUID = 0;
// internal store of all Outlayer intances
var instances = {};


/**
 * @param {Element, String} element
 * @param {Object} options
 * @constructor
 */
function Outlayer( element, options ) {
  var queryElement = utils.getQueryElement( element );
  if ( !queryElement ) {
    if ( console ) {
      console.error( 'Bad element for ' + this.constructor.namespace +
        ': ' + ( queryElement || element ) );
    }
    return;
  }
  this.element = queryElement;
  // add jQuery
  if ( jQuery ) {
    this.$element = jQuery( this.element );
  }

  // options
  this.options = utils.extend( {}, this.constructor.defaults );
  this.option( options );

  // add id for Outlayer.getFromElement
  var id = ++GUID;
  this.element.outlayerGUID = id; // expando
  instances[ id ] = this; // associate via id

  // kick it off
  this._create();

  var isInitLayout = this._getOption('initLayout');
  if ( isInitLayout ) {
    this.layout();
  }
}

// settings are for internal use only
Outlayer.namespace = 'outlayer';
Outlayer.Item = Item;

// default options
Outlayer.defaults = {
  containerStyle: {
    position: 'relative'
  },
  initLayout: true,
  originLeft: true,
  originTop: true,
  resize: true,
  resizeContainer: true,
  // item options
  transitionDuration: '0.4s',
  hiddenStyle: {
    opacity: 0,
    transform: 'scale(0.001)'
  },
  visibleStyle: {
    opacity: 1,
    transform: 'scale(1)'
  }
};

var proto = Outlayer.prototype;
// inherit EvEmitter
utils.extend( proto, EvEmitter.prototype );

/**
 * set options
 * @param {Object} opts
 */
proto.option = function( opts ) {
  utils.extend( this.options, opts );
};

/**
 * get backwards compatible option value, check old name
 */
proto._getOption = function( option ) {
  var oldOption = this.constructor.compatOptions[ option ];
  return oldOption && this.options[ oldOption ] !== undefined ?
    this.options[ oldOption ] : this.options[ option ];
};

Outlayer.compatOptions = {
  // currentName: oldName
  initLayout: 'isInitLayout',
  horizontal: 'isHorizontal',
  layoutInstant: 'isLayoutInstant',
  originLeft: 'isOriginLeft',
  originTop: 'isOriginTop',
  resize: 'isResizeBound',
  resizeContainer: 'isResizingContainer'
};

proto._create = function() {
  // get items from children
  this.reloadItems();
  // elements that affect layout, but are not laid out
  this.stamps = [];
  this.stamp( this.options.stamp );
  // set container style
  utils.extend( this.element.style, this.options.containerStyle );

  // bind resize method
  var canBindResize = this._getOption('resize');
  if ( canBindResize ) {
    this.bindResize();
  }
};

// goes through all children again and gets bricks in proper order
proto.reloadItems = function() {
  // collection of item elements
  this.items = this._itemize( this.element.children );
};


/**
 * turn elements into Outlayer.Items to be used in layout
 * @param {Array or NodeList or HTMLElement} elems
 * @returns {Array} items - collection of new Outlayer Items
 */
proto._itemize = function( elems ) {

  var itemElems = this._filterFindItemElements( elems );
  var Item = this.constructor.Item;

  // create new Outlayer Items for collection
  var items = [];
  for ( var i=0; i < itemElems.length; i++ ) {
    var elem = itemElems[i];
    var item = new Item( elem, this );
    items.push( item );
  }

  return items;
};

/**
 * get item elements to be used in layout
 * @param {Array or NodeList or HTMLElement} elems
 * @returns {Array} items - item elements
 */
proto._filterFindItemElements = function( elems ) {
  return utils.filterFindElements( elems, this.options.itemSelector );
};

/**
 * getter method for getting item elements
 * @returns {Array} elems - collection of item elements
 */
proto.getItemElements = function() {
  return this.items.map( function( item ) {
    return item.element;
  });
};

// ----- init & layout ----- //

/**
 * lays out all items
 */
proto.layout = function() {
  this._resetLayout();
  this._manageStamps();

  // don't animate first layout
  var layoutInstant = this._getOption('layoutInstant');
  var isInstant = layoutInstant !== undefined ?
    layoutInstant : !this._isLayoutInited;
  this.layoutItems( this.items, isInstant );

  // flag for initalized
  this._isLayoutInited = true;
};

// _init is alias for layout
proto._init = proto.layout;

/**
 * logic before any new layout
 */
proto._resetLayout = function() {
  this.getSize();
};


proto.getSize = function() {
  this.size = getSize( this.element );
};

/**
 * get measurement from option, for columnWidth, rowHeight, gutter
 * if option is String -> get element from selector string, & get size of element
 * if option is Element -> get size of element
 * else use option as a number
 *
 * @param {String} measurement
 * @param {String} size - width or height
 * @private
 */
proto._getMeasurement = function( measurement, size ) {
  var option = this.options[ measurement ];
  var elem;
  if ( !option ) {
    // default to 0
    this[ measurement ] = 0;
  } else {
    // use option as an element
    if ( typeof option == 'string' ) {
      elem = this.element.querySelector( option );
    } else if ( option instanceof HTMLElement ) {
      elem = option;
    }
    // use size of element, if element
    this[ measurement ] = elem ? getSize( elem )[ size ] : option;
  }
};

/**
 * layout a collection of item elements
 * @api public
 */
proto.layoutItems = function( items, isInstant ) {
  items = this._getItemsForLayout( items );

  this._layoutItems( items, isInstant );

  this._postLayout();
};

/**
 * get the items to be laid out
 * you may want to skip over some items
 * @param {Array} items
 * @returns {Array} items
 */
proto._getItemsForLayout = function( items ) {
  return items.filter( function( item ) {
    return !item.isIgnored;
  });
};

/**
 * layout items
 * @param {Array} items
 * @param {Boolean} isInstant
 */
proto._layoutItems = function( items, isInstant ) {
  this._emitCompleteOnItems( 'layout', items );

  if ( !items || !items.length ) {
    // no items, emit event with empty array
    return;
  }

  var queue = [];

  items.forEach( function( item ) {
    // get x/y object from method
    var position = this._getItemLayoutPosition( item );
    // enqueue
    position.item = item;
    position.isInstant = isInstant || item.isLayoutInstant;
    queue.push( position );
  }, this );

  this._processLayoutQueue( queue );
};

/**
 * get item layout position
 * @param {Outlayer.Item} item
 * @returns {Object} x and y position
 */
proto._getItemLayoutPosition = function( /* item */ ) {
  return {
    x: 0,
    y: 0
  };
};

/**
 * iterate over array and position each item
 * Reason being - separating this logic prevents 'layout invalidation'
 * thx @paul_irish
 * @param {Array} queue
 */
proto._processLayoutQueue = function( queue ) {
  this.updateStagger();
  queue.forEach( function( obj, i ) {
    this._positionItem( obj.item, obj.x, obj.y, obj.isInstant, i );
  }, this );
};

// set stagger from option in milliseconds number
proto.updateStagger = function() {
  var stagger = this.options.stagger;
  if ( stagger === null || stagger === undefined ) {
    this.stagger = 0;
    return;
  }
  this.stagger = getMilliseconds( stagger );
  return this.stagger;
};

/**
 * Sets position of item in DOM
 * @param {Outlayer.Item} item
 * @param {Number} x - horizontal position
 * @param {Number} y - vertical position
 * @param {Boolean} isInstant - disables transitions
 */
proto._positionItem = function( item, x, y, isInstant, i ) {
  if ( isInstant ) {
    // if not transition, just set CSS
    item.goTo( x, y );
  } else {
    item.stagger( i * this.stagger );
    item.moveTo( x, y );
  }
};

/**
 * Any logic you want to do after each layout,
 * i.e. size the container
 */
proto._postLayout = function() {
  this.resizeContainer();
};

proto.resizeContainer = function() {
  var isResizingContainer = this._getOption('resizeContainer');
  if ( !isResizingContainer ) {
    return;
  }
  var size = this._getContainerSize();
  if ( size ) {
    this._setContainerMeasure( size.width, true );
    this._setContainerMeasure( size.height, false );
  }
};

/**
 * Sets width or height of container if returned
 * @returns {Object} size
 *   @param {Number} width
 *   @param {Number} height
 */
proto._getContainerSize = noop;

/**
 * @param {Number} measure - size of width or height
 * @param {Boolean} isWidth
 */
proto._setContainerMeasure = function( measure, isWidth ) {
  if ( measure === undefined ) {
    return;
  }

  var elemSize = this.size;
  // add padding and border width if border box
  if ( elemSize.isBorderBox ) {
    measure += isWidth ? elemSize.paddingLeft + elemSize.paddingRight +
      elemSize.borderLeftWidth + elemSize.borderRightWidth :
      elemSize.paddingBottom + elemSize.paddingTop +
      elemSize.borderTopWidth + elemSize.borderBottomWidth;
  }

  measure = Math.max( measure, 0 );
  this.element.style[ isWidth ? 'width' : 'height' ] = measure + 'px';
};

/**
 * emit eventComplete on a collection of items events
 * @param {String} eventName
 * @param {Array} items - Outlayer.Items
 */
proto._emitCompleteOnItems = function( eventName, items ) {
  var _this = this;
  function onComplete() {
    _this.dispatchEvent( eventName + 'Complete', null, [ items ] );
  }

  var count = items.length;
  if ( !items || !count ) {
    onComplete();
    return;
  }

  var doneCount = 0;
  function tick() {
    doneCount++;
    if ( doneCount == count ) {
      onComplete();
    }
  }

  // bind callback
  items.forEach( function( item ) {
    item.once( eventName, tick );
  });
};

/**
 * emits events via EvEmitter and jQuery events
 * @param {String} type - name of event
 * @param {Event} event - original event
 * @param {Array} args - extra arguments
 */
proto.dispatchEvent = function( type, event, args ) {
  // add original event to arguments
  var emitArgs = event ? [ event ].concat( args ) : args;
  this.emitEvent( type, emitArgs );

  if ( jQuery ) {
    // set this.$element
    this.$element = this.$element || jQuery( this.element );
    if ( event ) {
      // create jQuery event
      var $event = jQuery.Event( event );
      $event.type = type;
      this.$element.trigger( $event, args );
    } else {
      // just trigger with type if no event available
      this.$element.trigger( type, args );
    }
  }
};

// -------------------------- ignore & stamps -------------------------- //


/**
 * keep item in collection, but do not lay it out
 * ignored items do not get skipped in layout
 * @param {Element} elem
 */
proto.ignore = function( elem ) {
  var item = this.getItem( elem );
  if ( item ) {
    item.isIgnored = true;
  }
};

/**
 * return item to layout collection
 * @param {Element} elem
 */
proto.unignore = function( elem ) {
  var item = this.getItem( elem );
  if ( item ) {
    delete item.isIgnored;
  }
};

/**
 * adds elements to stamps
 * @param {NodeList, Array, Element, or String} elems
 */
proto.stamp = function( elems ) {
  elems = this._find( elems );
  if ( !elems ) {
    return;
  }

  this.stamps = this.stamps.concat( elems );
  // ignore
  elems.forEach( this.ignore, this );
};

/**
 * removes elements to stamps
 * @param {NodeList, Array, or Element} elems
 */
proto.unstamp = function( elems ) {
  elems = this._find( elems );
  if ( !elems ){
    return;
  }

  elems.forEach( function( elem ) {
    // filter out removed stamp elements
    utils.removeFrom( this.stamps, elem );
    this.unignore( elem );
  }, this );
};

/**
 * finds child elements
 * @param {NodeList, Array, Element, or String} elems
 * @returns {Array} elems
 */
proto._find = function( elems ) {
  if ( !elems ) {
    return;
  }
  // if string, use argument as selector string
  if ( typeof elems == 'string' ) {
    elems = this.element.querySelectorAll( elems );
  }
  elems = utils.makeArray( elems );
  return elems;
};

proto._manageStamps = function() {
  if ( !this.stamps || !this.stamps.length ) {
    return;
  }

  this._getBoundingRect();

  this.stamps.forEach( this._manageStamp, this );
};

// update boundingLeft / Top
proto._getBoundingRect = function() {
  // get bounding rect for container element
  var boundingRect = this.element.getBoundingClientRect();
  var size = this.size;
  this._boundingRect = {
    left: boundingRect.left + size.paddingLeft + size.borderLeftWidth,
    top: boundingRect.top + size.paddingTop + size.borderTopWidth,
    right: boundingRect.right - ( size.paddingRight + size.borderRightWidth ),
    bottom: boundingRect.bottom - ( size.paddingBottom + size.borderBottomWidth )
  };
};

/**
 * @param {Element} stamp
**/
proto._manageStamp = noop;

/**
 * get x/y position of element relative to container element
 * @param {Element} elem
 * @returns {Object} offset - has left, top, right, bottom
 */
proto._getElementOffset = function( elem ) {
  var boundingRect = elem.getBoundingClientRect();
  var thisRect = this._boundingRect;
  var size = getSize( elem );
  var offset = {
    left: boundingRect.left - thisRect.left - size.marginLeft,
    top: boundingRect.top - thisRect.top - size.marginTop,
    right: thisRect.right - boundingRect.right - size.marginRight,
    bottom: thisRect.bottom - boundingRect.bottom - size.marginBottom
  };
  return offset;
};

// -------------------------- resize -------------------------- //

// enable event handlers for listeners
// i.e. resize -> onresize
proto.handleEvent = utils.handleEvent;

/**
 * Bind layout to window resizing
 */
proto.bindResize = function() {
  window.addEventListener( 'resize', this );
  this.isResizeBound = true;
};

/**
 * Unbind layout to window resizing
 */
proto.unbindResize = function() {
  window.removeEventListener( 'resize', this );
  this.isResizeBound = false;
};

proto.onresize = function() {
  this.resize();
};

utils.debounceMethod( Outlayer, 'onresize', 100 );

proto.resize = function() {
  // don't trigger if size did not change
  // or if resize was unbound. See #9
  if ( !this.isResizeBound || !this.needsResizeLayout() ) {
    return;
  }

  this.layout();
};

/**
 * check if layout is needed post layout
 * @returns Boolean
 */
proto.needsResizeLayout = function() {
  var size = getSize( this.element );
  // check that this.size and size are there
  // IE8 triggers resize on body size change, so they might not be
  var hasSizes = this.size && size;
  return hasSizes && size.innerWidth !== this.size.innerWidth;
};

// -------------------------- methods -------------------------- //

/**
 * add items to Outlayer instance
 * @param {Array or NodeList or Element} elems
 * @returns {Array} items - Outlayer.Items
**/
proto.addItems = function( elems ) {
  var items = this._itemize( elems );
  // add items to collection
  if ( items.length ) {
    this.items = this.items.concat( items );
  }
  return items;
};

/**
 * Layout newly-appended item elements
 * @param {Array or NodeList or Element} elems
 */
proto.appended = function( elems ) {
  var items = this.addItems( elems );
  if ( !items.length ) {
    return;
  }
  // layout and reveal just the new items
  this.layoutItems( items, true );
  this.reveal( items );
};

/**
 * Layout prepended elements
 * @param {Array or NodeList or Element} elems
 */
proto.prepended = function( elems ) {
  var items = this._itemize( elems );
  if ( !items.length ) {
    return;
  }
  // add items to beginning of collection
  var previousItems = this.items.slice(0);
  this.items = items.concat( previousItems );
  // start new layout
  this._resetLayout();
  this._manageStamps();
  // layout new stuff without transition
  this.layoutItems( items, true );
  this.reveal( items );
  // layout previous items
  this.layoutItems( previousItems );
};

/**
 * reveal a collection of items
 * @param {Array of Outlayer.Items} items
 */
proto.reveal = function( items ) {
  this._emitCompleteOnItems( 'reveal', items );
  if ( !items || !items.length ) {
    return;
  }
  var stagger = this.updateStagger();
  items.forEach( function( item, i ) {
    item.stagger( i * stagger );
    item.reveal();
  });
};

/**
 * hide a collection of items
 * @param {Array of Outlayer.Items} items
 */
proto.hide = function( items ) {
  this._emitCompleteOnItems( 'hide', items );
  if ( !items || !items.length ) {
    return;
  }
  var stagger = this.updateStagger();
  items.forEach( function( item, i ) {
    item.stagger( i * stagger );
    item.hide();
  });
};

/**
 * reveal item elements
 * @param {Array}, {Element}, {NodeList} items
 */
proto.revealItemElements = function( elems ) {
  var items = this.getItems( elems );
  this.reveal( items );
};

/**
 * hide item elements
 * @param {Array}, {Element}, {NodeList} items
 */
proto.hideItemElements = function( elems ) {
  var items = this.getItems( elems );
  this.hide( items );
};

/**
 * get Outlayer.Item, given an Element
 * @param {Element} elem
 * @param {Function} callback
 * @returns {Outlayer.Item} item
 */
proto.getItem = function( elem ) {
  // loop through items to get the one that matches
  for ( var i=0; i < this.items.length; i++ ) {
    var item = this.items[i];
    if ( item.element == elem ) {
      // return item
      return item;
    }
  }
};

/**
 * get collection of Outlayer.Items, given Elements
 * @param {Array} elems
 * @returns {Array} items - Outlayer.Items
 */
proto.getItems = function( elems ) {
  elems = utils.makeArray( elems );
  var items = [];
  elems.forEach( function( elem ) {
    var item = this.getItem( elem );
    if ( item ) {
      items.push( item );
    }
  }, this );

  return items;
};

/**
 * remove element(s) from instance and DOM
 * @param {Array or NodeList or Element} elems
 */
proto.remove = function( elems ) {
  var removeItems = this.getItems( elems );

  this._emitCompleteOnItems( 'remove', removeItems );

  // bail if no items to remove
  if ( !removeItems || !removeItems.length ) {
    return;
  }

  removeItems.forEach( function( item ) {
    item.remove();
    // remove item from collection
    utils.removeFrom( this.items, item );
  }, this );
};

// ----- destroy ----- //

// remove and disable Outlayer instance
proto.destroy = function() {
  // clean up dynamic styles
  var style = this.element.style;
  style.height = '';
  style.position = '';
  style.width = '';
  // destroy items
  this.items.forEach( function( item ) {
    item.destroy();
  });

  this.unbindResize();

  var id = this.element.outlayerGUID;
  delete instances[ id ]; // remove reference to instance by id
  delete this.element.outlayerGUID;
  // remove data for jQuery
  if ( jQuery ) {
    jQuery.removeData( this.element, this.constructor.namespace );
  }

};

// -------------------------- data -------------------------- //

/**
 * get Outlayer instance from element
 * @param {Element} elem
 * @returns {Outlayer}
 */
Outlayer.data = function( elem ) {
  elem = utils.getQueryElement( elem );
  var id = elem && elem.outlayerGUID;
  return id && instances[ id ];
};


// -------------------------- create Outlayer class -------------------------- //

/**
 * create a layout class
 * @param {String} namespace
 */
Outlayer.create = function( namespace, options ) {
  // sub-class Outlayer
  var Layout = subclass( Outlayer );
  // apply new options and compatOptions
  Layout.defaults = utils.extend( {}, Outlayer.defaults );
  utils.extend( Layout.defaults, options );
  Layout.compatOptions = utils.extend( {}, Outlayer.compatOptions  );

  Layout.namespace = namespace;

  Layout.data = Outlayer.data;

  // sub-class Item
  Layout.Item = subclass( Item );

  // -------------------------- declarative -------------------------- //

  utils.htmlInit( Layout, namespace );

  // -------------------------- jQuery bridge -------------------------- //

  // make into jQuery plugin
  if ( jQuery && jQuery.bridget ) {
    jQuery.bridget( namespace, Layout );
  }

  return Layout;
};

function subclass( Parent ) {
  function SubClass() {
    Parent.apply( this, arguments );
  }

  SubClass.prototype = Object.create( Parent.prototype );
  SubClass.prototype.constructor = SubClass;

  return SubClass;
}

// ----- helpers ----- //

// how many milliseconds are in each unit
var msUnits = {
  ms: 1,
  s: 1000
};

// munge time-like parameter into millisecond number
// '0.4s' -> 40
function getMilliseconds( time ) {
  if ( typeof time == 'number' ) {
    return time;
  }
  var matches = time.match( /(^\d*\.?\d*)(\w*)/ );
  var num = matches && matches[1];
  var unit = matches && matches[2];
  if ( !num.length ) {
    return 0;
  }
  num = parseFloat( num );
  var mult = msUnits[ unit ] || 1;
  return num * mult;
}

// ----- fin ----- //

// back in global
Outlayer.Item = Item;

return Outlayer;

}));

/**
 * Isotope Item
**/

( function( window, factory ) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'isotope-layout/js/item',[
        'outlayer/outlayer'
      ],
      factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      require('outlayer')
    );
  } else {
    // browser global
    window.Isotope = window.Isotope || {};
    window.Isotope.Item = factory(
      window.Outlayer
    );
  }

}( window, function factory( Outlayer ) {
'use strict';

// -------------------------- Item -------------------------- //

// sub-class Outlayer Item
function Item() {
  Outlayer.Item.apply( this, arguments );
}

var proto = Item.prototype = Object.create( Outlayer.Item.prototype );

var _create = proto._create;
proto._create = function() {
  // assign id, used for original-order sorting
  this.id = this.layout.itemGUID++;
  _create.call( this );
  this.sortData = {};
};

proto.updateSortData = function() {
  if ( this.isIgnored ) {
    return;
  }
  // default sorters
  this.sortData.id = this.id;
  // for backward compatibility
  this.sortData['original-order'] = this.id;
  this.sortData.random = Math.random();
  // go thru getSortData obj and apply the sorters
  var getSortData = this.layout.options.getSortData;
  var sorters = this.layout._sorters;
  for ( var key in getSortData ) {
    var sorter = sorters[ key ];
    this.sortData[ key ] = sorter( this.element, this );
  }
};

var _destroy = proto.destroy;
proto.destroy = function() {
  // call super
  _destroy.apply( this, arguments );
  // reset display, #741
  this.css({
    display: ''
  });
};

return Item;

}));

/**
 * Isotope LayoutMode
 */

( function( window, factory ) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'isotope-layout/js/layout-mode',[
        'get-size/get-size',
        'outlayer/outlayer'
      ],
      factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      require('get-size'),
      require('outlayer')
    );
  } else {
    // browser global
    window.Isotope = window.Isotope || {};
    window.Isotope.LayoutMode = factory(
      window.getSize,
      window.Outlayer
    );
  }

}( window, function factory( getSize, Outlayer ) {
  'use strict';

  // layout mode class
  function LayoutMode( isotope ) {
    this.isotope = isotope;
    // link properties
    if ( isotope ) {
      this.options = isotope.options[ this.namespace ];
      this.element = isotope.element;
      this.items = isotope.filteredItems;
      this.size = isotope.size;
    }
  }

  var proto = LayoutMode.prototype;

  /**
   * some methods should just defer to default Outlayer method
   * and reference the Isotope instance as `this`
  **/
  var facadeMethods = [
    '_resetLayout',
    '_getItemLayoutPosition',
    '_manageStamp',
    '_getContainerSize',
    '_getElementOffset',
    'needsResizeLayout',
    '_getOption'
  ];

  facadeMethods.forEach( function( methodName ) {
    proto[ methodName ] = function() {
      return Outlayer.prototype[ methodName ].apply( this.isotope, arguments );
    };
  });

  // -----  ----- //

  // for horizontal layout modes, check vertical size
  proto.needsVerticalResizeLayout = function() {
    // don't trigger if size did not change
    var size = getSize( this.isotope.element );
    // check that this.size and size are there
    // IE8 triggers resize on body size change, so they might not be
    var hasSizes = this.isotope.size && size;
    return hasSizes && size.innerHeight != this.isotope.size.innerHeight;
  };

  // ----- measurements ----- //

  proto._getMeasurement = function() {
    this.isotope._getMeasurement.apply( this, arguments );
  };

  proto.getColumnWidth = function() {
    this.getSegmentSize( 'column', 'Width' );
  };

  proto.getRowHeight = function() {
    this.getSegmentSize( 'row', 'Height' );
  };

  /**
   * get columnWidth or rowHeight
   * segment: 'column' or 'row'
   * size 'Width' or 'Height'
  **/
  proto.getSegmentSize = function( segment, size ) {
    var segmentName = segment + size;
    var outerSize = 'outer' + size;
    // columnWidth / outerWidth // rowHeight / outerHeight
    this._getMeasurement( segmentName, outerSize );
    // got rowHeight or columnWidth, we can chill
    if ( this[ segmentName ] ) {
      return;
    }
    // fall back to item of first element
    var firstItemSize = this.getFirstItemSize();
    this[ segmentName ] = firstItemSize && firstItemSize[ outerSize ] ||
      // or size of container
      this.isotope.size[ 'inner' + size ];
  };

  proto.getFirstItemSize = function() {
    var firstItem = this.isotope.filteredItems[0];
    return firstItem && firstItem.element && getSize( firstItem.element );
  };

  // ----- methods that should reference isotope ----- //

  proto.layout = function() {
    this.isotope.layout.apply( this.isotope, arguments );
  };

  proto.getSize = function() {
    this.isotope.getSize();
    this.size = this.isotope.size;
  };

  // -------------------------- create -------------------------- //

  LayoutMode.modes = {};

  LayoutMode.create = function( namespace, options ) {

    function Mode() {
      LayoutMode.apply( this, arguments );
    }

    Mode.prototype = Object.create( proto );
    Mode.prototype.constructor = Mode;

    // default options
    if ( options ) {
      Mode.options = options;
    }

    Mode.prototype.namespace = namespace;
    // register in Isotope
    LayoutMode.modes[ namespace ] = Mode;

    return Mode;
  };

  return LayoutMode;

}));

/*!
 * Masonry v4.2.1
 * Cascading grid layout library
 * https://masonry.desandro.com
 * MIT License
 * by David DeSandro
 */

( function( window, factory ) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'masonry-layout/masonry',[
        'outlayer/outlayer',
        'get-size/get-size'
      ],
      factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      require('outlayer'),
      require('get-size')
    );
  } else {
    // browser global
    window.Masonry = factory(
      window.Outlayer,
      window.getSize
    );
  }

}( window, function factory( Outlayer, getSize ) {



// -------------------------- masonryDefinition -------------------------- //

  // create an Outlayer layout class
  var Masonry = Outlayer.create('masonry');
  // isFitWidth -> fitWidth
  Masonry.compatOptions.fitWidth = 'isFitWidth';

  var proto = Masonry.prototype;

  proto._resetLayout = function() {
    this.getSize();
    this._getMeasurement( 'columnWidth', 'outerWidth' );
    this._getMeasurement( 'gutter', 'outerWidth' );
    this.measureColumns();

    // reset column Y
    this.colYs = [];
    for ( var i=0; i < this.cols; i++ ) {
      this.colYs.push( 0 );
    }

    this.maxY = 0;
    this.horizontalColIndex = 0;
  };

  proto.measureColumns = function() {
    this.getContainerWidth();
    // if columnWidth is 0, default to outerWidth of first item
    if ( !this.columnWidth ) {
      var firstItem = this.items[0];
      var firstItemElem = firstItem && firstItem.element;
      // columnWidth fall back to item of first element
      this.columnWidth = firstItemElem && getSize( firstItemElem ).outerWidth ||
        // if first elem has no width, default to size of container
        this.containerWidth;
    }

    var columnWidth = this.columnWidth += this.gutter;

    // calculate columns
    var containerWidth = this.containerWidth + this.gutter;
    var cols = containerWidth / columnWidth;
    // fix rounding errors, typically with gutters
    var excess = columnWidth - containerWidth % columnWidth;
    // if overshoot is less than a pixel, round up, otherwise floor it
    var mathMethod = excess && excess < 1 ? 'round' : 'floor';
    cols = Math[ mathMethod ]( cols );
    this.cols = Math.max( cols, 1 );
  };

  proto.getContainerWidth = function() {
    // container is parent if fit width
    var isFitWidth = this._getOption('fitWidth');
    var container = isFitWidth ? this.element.parentNode : this.element;
    // check that this.size and size are there
    // IE8 triggers resize on body size change, so they might not be
    var size = getSize( container );
    this.containerWidth = size && size.innerWidth;
  };

  proto._getItemLayoutPosition = function( item ) {
    item.getSize();
    // how many columns does this brick span
    var remainder = item.size.outerWidth % this.columnWidth;
    var mathMethod = remainder && remainder < 1 ? 'round' : 'ceil';
    // round if off by 1 pixel, otherwise use ceil
    var colSpan = Math[ mathMethod ]( item.size.outerWidth / this.columnWidth );
    colSpan = Math.min( colSpan, this.cols );
    // use horizontal or top column position
    var colPosMethod = this.options.horizontalOrder ?
      '_getHorizontalColPosition' : '_getTopColPosition';
    var colPosition = this[ colPosMethod ]( colSpan, item );
    // position the brick
    var position = {
      x: this.columnWidth * colPosition.col,
      y: colPosition.y
    };
    // apply setHeight to necessary columns
    var setHeight = colPosition.y + item.size.outerHeight;
    var setMax = colSpan + colPosition.col;
    for ( var i = colPosition.col; i < setMax; i++ ) {
      this.colYs[i] = setHeight;
    }

    return position;
  };

  proto._getTopColPosition = function( colSpan ) {
    var colGroup = this._getTopColGroup( colSpan );
    // get the minimum Y value from the columns
    var minimumY = Math.min.apply( Math, colGroup );

    return {
      col: colGroup.indexOf( minimumY ),
      y: minimumY,
    };
  };

  /**
   * @param {Number} colSpan - number of columns the element spans
   * @returns {Array} colGroup
   */
  proto._getTopColGroup = function( colSpan ) {
    if ( colSpan < 2 ) {
      // if brick spans only one column, use all the column Ys
      return this.colYs;
    }

    var colGroup = [];
    // how many different places could this brick fit horizontally
    var groupCount = this.cols + 1 - colSpan;
    // for each group potential horizontal position
    for ( var i = 0; i < groupCount; i++ ) {
      colGroup[i] = this._getColGroupY( i, colSpan );
    }
    return colGroup;
  };

  proto._getColGroupY = function( col, colSpan ) {
    if ( colSpan < 2 ) {
      return this.colYs[ col ];
    }
    // make an array of colY values for that one group
    var groupColYs = this.colYs.slice( col, col + colSpan );
    // and get the max value of the array
    return Math.max.apply( Math, groupColYs );
  };

  // get column position based on horizontal index. #873
  proto._getHorizontalColPosition = function( colSpan, item ) {
    var col = this.horizontalColIndex % this.cols;
    var isOver = colSpan > 1 && col + colSpan > this.cols;
    // shift to next row if item can't fit on current row
    col = isOver ? 0 : col;
    // don't let zero-size items take up space
    var hasSize = item.size.outerWidth && item.size.outerHeight;
    this.horizontalColIndex = hasSize ? col + colSpan : this.horizontalColIndex;

    return {
      col: col,
      y: this._getColGroupY( col, colSpan ),
    };
  };

  proto._manageStamp = function( stamp ) {
    var stampSize = getSize( stamp );
    var offset = this._getElementOffset( stamp );
    // get the columns that this stamp affects
    var isOriginLeft = this._getOption('originLeft');
    var firstX = isOriginLeft ? offset.left : offset.right;
    var lastX = firstX + stampSize.outerWidth;
    var firstCol = Math.floor( firstX / this.columnWidth );
    firstCol = Math.max( 0, firstCol );
    var lastCol = Math.floor( lastX / this.columnWidth );
    // lastCol should not go over if multiple of columnWidth #425
    lastCol -= lastX % this.columnWidth ? 0 : 1;
    lastCol = Math.min( this.cols - 1, lastCol );
    // set colYs to bottom of the stamp

    var isOriginTop = this._getOption('originTop');
    var stampMaxY = ( isOriginTop ? offset.top : offset.bottom ) +
      stampSize.outerHeight;
    for ( var i = firstCol; i <= lastCol; i++ ) {
      this.colYs[i] = Math.max( stampMaxY, this.colYs[i] );
    }
  };

  proto._getContainerSize = function() {
    this.maxY = Math.max.apply( Math, this.colYs );
    var size = {
      height: this.maxY
    };

    if ( this._getOption('fitWidth') ) {
      size.width = this._getContainerFitWidth();
    }

    return size;
  };

  proto._getContainerFitWidth = function() {
    var unusedCols = 0;
    // count unused columns
    var i = this.cols;
    while ( --i ) {
      if ( this.colYs[i] !== 0 ) {
        break;
      }
      unusedCols++;
    }
    // fit container to columns that have been used
    return ( this.cols - unusedCols ) * this.columnWidth - this.gutter;
  };

  proto.needsResizeLayout = function() {
    var previousWidth = this.containerWidth;
    this.getContainerWidth();
    return previousWidth != this.containerWidth;
  };

  return Masonry;

}));

/*!
 * Masonry layout mode
 * sub-classes Masonry
 * https://masonry.desandro.com
 */

( function( window, factory ) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'isotope-layout/js/layout-modes/masonry',[
        '../layout-mode',
        'masonry-layout/masonry'
      ],
      factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      require('../layout-mode'),
      require('masonry-layout')
    );
  } else {
    // browser global
    factory(
      window.Isotope.LayoutMode,
      window.Masonry
    );
  }

}( window, function factory( LayoutMode, Masonry ) {
'use strict';

// -------------------------- masonryDefinition -------------------------- //

  // create an Outlayer layout class
  var MasonryMode = LayoutMode.create('masonry');

  var proto = MasonryMode.prototype;

  var keepModeMethods = {
    _getElementOffset: true,
    layout: true,
    _getMeasurement: true
  };

  // inherit Masonry prototype
  for ( var method in Masonry.prototype ) {
    // do not inherit mode methods
    if ( !keepModeMethods[ method ] ) {
      proto[ method ] = Masonry.prototype[ method ];
    }
  }

  var measureColumns = proto.measureColumns;
  proto.measureColumns = function() {
    // set items, used if measuring first item
    this.items = this.isotope.filteredItems;
    measureColumns.call( this );
  };

  // point to mode options for fitWidth
  var _getOption = proto._getOption;
  proto._getOption = function( option ) {
    if ( option == 'fitWidth' ) {
      return this.options.isFitWidth !== undefined ?
        this.options.isFitWidth : this.options.fitWidth;
    }
    return _getOption.apply( this.isotope, arguments );
  };

  return MasonryMode;

}));

/**
 * fitRows layout mode
 */

( function( window, factory ) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'isotope-layout/js/layout-modes/fit-rows',[
        '../layout-mode'
      ],
      factory );
  } else if ( typeof exports == 'object' ) {
    // CommonJS
    module.exports = factory(
      require('../layout-mode')
    );
  } else {
    // browser global
    factory(
      window.Isotope.LayoutMode
    );
  }

}( window, function factory( LayoutMode ) {
'use strict';

var FitRows = LayoutMode.create('fitRows');

var proto = FitRows.prototype;

proto._resetLayout = function() {
  this.x = 0;
  this.y = 0;
  this.maxY = 0;
  this._getMeasurement( 'gutter', 'outerWidth' );
};

proto._getItemLayoutPosition = function( item ) {
  item.getSize();

  var itemWidth = item.size.outerWidth + this.gutter;
  // if this element cannot fit in the current row
  var containerWidth = this.isotope.size.innerWidth + this.gutter;
  if ( this.x !== 0 && itemWidth + this.x > containerWidth ) {
    this.x = 0;
    this.y = this.maxY;
  }

  var position = {
    x: this.x,
    y: this.y
  };

  this.maxY = Math.max( this.maxY, this.y + item.size.outerHeight );
  this.x += itemWidth;

  return position;
};

proto._getContainerSize = function() {
  return { height: this.maxY };
};

return FitRows;

}));

/**
 * vertical layout mode
 */

( function( window, factory ) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( 'isotope-layout/js/layout-modes/vertical',[
        '../layout-mode'
      ],
      factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      require('../layout-mode')
    );
  } else {
    // browser global
    factory(
      window.Isotope.LayoutMode
    );
  }

}( window, function factory( LayoutMode ) {
'use strict';

var Vertical = LayoutMode.create( 'vertical', {
  horizontalAlignment: 0
});

var proto = Vertical.prototype;

proto._resetLayout = function() {
  this.y = 0;
};

proto._getItemLayoutPosition = function( item ) {
  item.getSize();
  var x = ( this.isotope.size.innerWidth - item.size.outerWidth ) *
    this.options.horizontalAlignment;
  var y = this.y;
  this.y += item.size.outerHeight;
  return { x: x, y: y };
};

proto._getContainerSize = function() {
  return { height: this.y };
};

return Vertical;

}));

/*!
 * Isotope v3.0.6
 *
 * Licensed GPLv3 for open source use
 * or Isotope Commercial License for commercial use
 *
 * https://isotope.metafizzy.co
 * Copyright 2010-2018 Metafizzy
 */

( function( window, factory ) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( [
        'outlayer/outlayer',
        'get-size/get-size',
        'desandro-matches-selector/matches-selector',
        'fizzy-ui-utils/utils',
        'isotope-layout/js/item',
        'isotope-layout/js/layout-mode',
        // include default layout modes
        'isotope-layout/js/layout-modes/masonry',
        'isotope-layout/js/layout-modes/fit-rows',
        'isotope-layout/js/layout-modes/vertical'
      ],
      function( Outlayer, getSize, matchesSelector, utils, Item, LayoutMode ) {
        return factory( window, Outlayer, getSize, matchesSelector, utils, Item, LayoutMode );
      });
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      window,
      require('outlayer'),
      require('get-size'),
      require('desandro-matches-selector'),
      require('fizzy-ui-utils'),
      require('isotope-layout/js/item'),
      require('isotope-layout/js/layout-mode'),
      // include default layout modes
      require('isotope-layout/js/layout-modes/masonry'),
      require('isotope-layout/js/layout-modes/fit-rows'),
      require('isotope-layout/js/layout-modes/vertical')
    );
  } else {
    // browser global
    window.Isotope = factory(
      window,
      window.Outlayer,
      window.getSize,
      window.matchesSelector,
      window.fizzyUIUtils,
      window.Isotope.Item,
      window.Isotope.LayoutMode
    );
  }

}( window, function factory( window, Outlayer, getSize, matchesSelector, utils,
  Item, LayoutMode ) {



// -------------------------- vars -------------------------- //

var jQuery = window.jQuery;

// -------------------------- helpers -------------------------- //

var trim = String.prototype.trim ?
  function( str ) {
    return str.trim();
  } :
  function( str ) {
    return str.replace( /^\s+|\s+$/g, '' );
  };

// -------------------------- isotopeDefinition -------------------------- //

  // create an Outlayer layout class
  var Isotope = Outlayer.create( 'isotope', {
    layoutMode: 'masonry',
    isJQueryFiltering: true,
    sortAscending: true
  });

  Isotope.Item = Item;
  Isotope.LayoutMode = LayoutMode;

  var proto = Isotope.prototype;

  proto._create = function() {
    this.itemGUID = 0;
    // functions that sort items
    this._sorters = {};
    this._getSorters();
    // call super
    Outlayer.prototype._create.call( this );

    // create layout modes
    this.modes = {};
    // start filteredItems with all items
    this.filteredItems = this.items;
    // keep of track of sortBys
    this.sortHistory = [ 'original-order' ];
    // create from registered layout modes
    for ( var name in LayoutMode.modes ) {
      this._initLayoutMode( name );
    }
  };

  proto.reloadItems = function() {
    // reset item ID counter
    this.itemGUID = 0;
    // call super
    Outlayer.prototype.reloadItems.call( this );
  };

  proto._itemize = function() {
    var items = Outlayer.prototype._itemize.apply( this, arguments );
    // assign ID for original-order
    for ( var i=0; i < items.length; i++ ) {
      var item = items[i];
      item.id = this.itemGUID++;
    }
    this._updateItemsSortData( items );
    return items;
  };


  // -------------------------- layout -------------------------- //

  proto._initLayoutMode = function( name ) {
    var Mode = LayoutMode.modes[ name ];
    // set mode options
    // HACK extend initial options, back-fill in default options
    var initialOpts = this.options[ name ] || {};
    this.options[ name ] = Mode.options ?
      utils.extend( Mode.options, initialOpts ) : initialOpts;
    // init layout mode instance
    this.modes[ name ] = new Mode( this );
  };


  proto.layout = function() {
    // if first time doing layout, do all magic
    if ( !this._isLayoutInited && this._getOption('initLayout') ) {
      this.arrange();
      return;
    }
    this._layout();
  };

  // private method to be used in layout() & magic()
  proto._layout = function() {
    // don't animate first layout
    var isInstant = this._getIsInstant();
    // layout flow
    this._resetLayout();
    this._manageStamps();
    this.layoutItems( this.filteredItems, isInstant );

    // flag for initalized
    this._isLayoutInited = true;
  };

  // filter + sort + layout
  proto.arrange = function( opts ) {
    // set any options pass
    this.option( opts );
    this._getIsInstant();
    // filter, sort, and layout

    // filter
    var filtered = this._filter( this.items );
    this.filteredItems = filtered.matches;

    this._bindArrangeComplete();

    if ( this._isInstant ) {
      this._noTransition( this._hideReveal, [ filtered ] );
    } else {
      this._hideReveal( filtered );
    }

    this._sort();
    this._layout();
  };
  // alias to _init for main plugin method
  proto._init = proto.arrange;

  proto._hideReveal = function( filtered ) {
    this.reveal( filtered.needReveal );
    this.hide( filtered.needHide );
  };

  // HACK
  // Don't animate/transition first layout
  // Or don't animate/transition other layouts
  proto._getIsInstant = function() {
    var isLayoutInstant = this._getOption('layoutInstant');
    var isInstant = isLayoutInstant !== undefined ? isLayoutInstant :
      !this._isLayoutInited;
    this._isInstant = isInstant;
    return isInstant;
  };

  // listen for layoutComplete, hideComplete and revealComplete
  // to trigger arrangeComplete
  proto._bindArrangeComplete = function() {
    // listen for 3 events to trigger arrangeComplete
    var isLayoutComplete, isHideComplete, isRevealComplete;
    var _this = this;
    function arrangeParallelCallback() {
      if ( isLayoutComplete && isHideComplete && isRevealComplete ) {
        _this.dispatchEvent( 'arrangeComplete', null, [ _this.filteredItems ] );
      }
    }
    this.once( 'layoutComplete', function() {
      isLayoutComplete = true;
      arrangeParallelCallback();
    });
    this.once( 'hideComplete', function() {
      isHideComplete = true;
      arrangeParallelCallback();
    });
    this.once( 'revealComplete', function() {
      isRevealComplete = true;
      arrangeParallelCallback();
    });
  };

  // -------------------------- filter -------------------------- //

  proto._filter = function( items ) {
    var filter = this.options.filter;
    filter = filter || '*';
    var matches = [];
    var hiddenMatched = [];
    var visibleUnmatched = [];

    var test = this._getFilterTest( filter );

    // test each item
    for ( var i=0; i < items.length; i++ ) {
      var item = items[i];
      if ( item.isIgnored ) {
        continue;
      }
      // add item to either matched or unmatched group
      var isMatched = test( item );
      // item.isFilterMatched = isMatched;
      // add to matches if its a match
      if ( isMatched ) {
        matches.push( item );
      }
      // add to additional group if item needs to be hidden or revealed
      if ( isMatched && item.isHidden ) {
        hiddenMatched.push( item );
      } else if ( !isMatched && !item.isHidden ) {
        visibleUnmatched.push( item );
      }
    }

    // return collections of items to be manipulated
    return {
      matches: matches,
      needReveal: hiddenMatched,
      needHide: visibleUnmatched
    };
  };

  // get a jQuery, function, or a matchesSelector test given the filter
  proto._getFilterTest = function( filter ) {
    if ( jQuery && this.options.isJQueryFiltering ) {
      // use jQuery
      return function( item ) {
        return jQuery( item.element ).is( filter );
      };
    }
    if ( typeof filter == 'function' ) {
      // use filter as function
      return function( item ) {
        return filter( item.element );
      };
    }
    // default, use filter as selector string
    return function( item ) {
      return matchesSelector( item.element, filter );
    };
  };

  // -------------------------- sorting -------------------------- //

  /**
   * @params {Array} elems
   * @public
   */
  proto.updateSortData = function( elems ) {
    // get items
    var items;
    if ( elems ) {
      elems = utils.makeArray( elems );
      items = this.getItems( elems );
    } else {
      // update all items if no elems provided
      items = this.items;
    }

    this._getSorters();
    this._updateItemsSortData( items );
  };

  proto._getSorters = function() {
    var getSortData = this.options.getSortData;
    for ( var key in getSortData ) {
      var sorter = getSortData[ key ];
      this._sorters[ key ] = mungeSorter( sorter );
    }
  };

  /**
   * @params {Array} items - of Isotope.Items
   * @private
   */
  proto._updateItemsSortData = function( items ) {
    // do not update if no items
    var len = items && items.length;

    for ( var i=0; len && i < len; i++ ) {
      var item = items[i];
      item.updateSortData();
    }
  };

  // ----- munge sorter ----- //

  // encapsulate this, as we just need mungeSorter
  // other functions in here are just for munging
  var mungeSorter = ( function() {
    // add a magic layer to sorters for convienent shorthands
    // `.foo-bar` will use the text of .foo-bar querySelector
    // `[foo-bar]` will use attribute
    // you can also add parser
    // `.foo-bar parseInt` will parse that as a number
    function mungeSorter( sorter ) {
      // if not a string, return function or whatever it is
      if ( typeof sorter != 'string' ) {
        return sorter;
      }
      // parse the sorter string
      var args = trim( sorter ).split(' ');
      var query = args[0];
      // check if query looks like [an-attribute]
      var attrMatch = query.match( /^\[(.+)\]$/ );
      var attr = attrMatch && attrMatch[1];
      var getValue = getValueGetter( attr, query );
      // use second argument as a parser
      var parser = Isotope.sortDataParsers[ args[1] ];
      // parse the value, if there was a parser
      sorter = parser ? function( elem ) {
        return elem && parser( getValue( elem ) );
      } :
      // otherwise just return value
      function( elem ) {
        return elem && getValue( elem );
      };

      return sorter;
    }

    // get an attribute getter, or get text of the querySelector
    function getValueGetter( attr, query ) {
      // if query looks like [foo-bar], get attribute
      if ( attr ) {
        return function getAttribute( elem ) {
          return elem.getAttribute( attr );
        };
      }

      // otherwise, assume its a querySelector, and get its text
      return function getChildText( elem ) {
        var child = elem.querySelector( query );
        return child && child.textContent;
      };
    }

    return mungeSorter;
  })();

  // parsers used in getSortData shortcut strings
  Isotope.sortDataParsers = {
    'parseInt': function( val ) {
      return parseInt( val, 10 );
    },
    'parseFloat': function( val ) {
      return parseFloat( val );
    }
  };

  // ----- sort method ----- //

  // sort filteredItem order
  proto._sort = function() {
    if ( !this.options.sortBy ) {
      return;
    }
    // keep track of sortBy History
    var sortBys = utils.makeArray( this.options.sortBy );
    if ( !this._getIsSameSortBy( sortBys ) ) {
      // concat all sortBy and sortHistory, add to front, oldest goes in last
      this.sortHistory = sortBys.concat( this.sortHistory );
    }
    // sort magic
    var itemSorter = getItemSorter( this.sortHistory, this.options.sortAscending );
    this.filteredItems.sort( itemSorter );
  };

  // check if sortBys is same as start of sortHistory
  proto._getIsSameSortBy = function( sortBys ) {
    for ( var i=0; i < sortBys.length; i++ ) {
      if ( sortBys[i] != this.sortHistory[i] ) {
        return false;
      }
    }
    return true;
  };

  // returns a function used for sorting
  function getItemSorter( sortBys, sortAsc ) {
    return function sorter( itemA, itemB ) {
      // cycle through all sortKeys
      for ( var i = 0; i < sortBys.length; i++ ) {
        var sortBy = sortBys[i];
        var a = itemA.sortData[ sortBy ];
        var b = itemB.sortData[ sortBy ];
        if ( a > b || a < b ) {
          // if sortAsc is an object, use the value given the sortBy key
          var isAscending = sortAsc[ sortBy ] !== undefined ? sortAsc[ sortBy ] : sortAsc;
          var direction = isAscending ? 1 : -1;
          return ( a > b ? 1 : -1 ) * direction;
        }
      }
      return 0;
    };
  }

  // -------------------------- methods -------------------------- //

  // get layout mode
  proto._mode = function() {
    var layoutMode = this.options.layoutMode;
    var mode = this.modes[ layoutMode ];
    if ( !mode ) {
      // TODO console.error
      throw new Error( 'No layout mode: ' + layoutMode );
    }
    // HACK sync mode's options
    // any options set after init for layout mode need to be synced
    mode.options = this.options[ layoutMode ];
    return mode;
  };

  proto._resetLayout = function() {
    // trigger original reset layout
    Outlayer.prototype._resetLayout.call( this );
    this._mode()._resetLayout();
  };

  proto._getItemLayoutPosition = function( item  ) {
    return this._mode()._getItemLayoutPosition( item );
  };

  proto._manageStamp = function( stamp ) {
    this._mode()._manageStamp( stamp );
  };

  proto._getContainerSize = function() {
    return this._mode()._getContainerSize();
  };

  proto.needsResizeLayout = function() {
    return this._mode().needsResizeLayout();
  };

  // -------------------------- adding & removing -------------------------- //

  // HEADS UP overwrites default Outlayer appended
  proto.appended = function( elems ) {
    var items = this.addItems( elems );
    if ( !items.length ) {
      return;
    }
    // filter, layout, reveal new items
    var filteredItems = this._filterRevealAdded( items );
    // add to filteredItems
    this.filteredItems = this.filteredItems.concat( filteredItems );
  };

  // HEADS UP overwrites default Outlayer prepended
  proto.prepended = function( elems ) {
    var items = this._itemize( elems );
    if ( !items.length ) {
      return;
    }
    // start new layout
    this._resetLayout();
    this._manageStamps();
    // filter, layout, reveal new items
    var filteredItems = this._filterRevealAdded( items );
    // layout previous items
    this.layoutItems( this.filteredItems );
    // add to items and filteredItems
    this.filteredItems = filteredItems.concat( this.filteredItems );
    this.items = items.concat( this.items );
  };

  proto._filterRevealAdded = function( items ) {
    var filtered = this._filter( items );
    this.hide( filtered.needHide );
    // reveal all new items
    this.reveal( filtered.matches );
    // layout new items, no transition
    this.layoutItems( filtered.matches, true );
    return filtered.matches;
  };

  /**
   * Filter, sort, and layout newly-appended item elements
   * @param {Array or NodeList or Element} elems
   */
  proto.insert = function( elems ) {
    var items = this.addItems( elems );
    if ( !items.length ) {
      return;
    }
    // append item elements
    var i, item;
    var len = items.length;
    for ( i=0; i < len; i++ ) {
      item = items[i];
      this.element.appendChild( item.element );
    }
    // filter new stuff
    var filteredInsertItems = this._filter( items ).matches;
    // set flag
    for ( i=0; i < len; i++ ) {
      items[i].isLayoutInstant = true;
    }
    this.arrange();
    // reset flag
    for ( i=0; i < len; i++ ) {
      delete items[i].isLayoutInstant;
    }
    this.reveal( filteredInsertItems );
  };

  var _remove = proto.remove;
  proto.remove = function( elems ) {
    elems = utils.makeArray( elems );
    var removeItems = this.getItems( elems );
    // do regular thing
    _remove.call( this, elems );
    // bail if no items to remove
    var len = removeItems && removeItems.length;
    // remove elems from filteredItems
    for ( var i=0; len && i < len; i++ ) {
      var item = removeItems[i];
      // remove item from collection
      utils.removeFrom( this.filteredItems, item );
    }
  };

  proto.shuffle = function() {
    // update random sortData
    for ( var i=0; i < this.items.length; i++ ) {
      var item = this.items[i];
      item.sortData.random = Math.random();
    }
    this.options.sortBy = 'random';
    this._sort();
    this._layout();
  };

  /**
   * trigger fn without transition
   * kind of hacky to have this in the first place
   * @param {Function} fn
   * @param {Array} args
   * @returns ret
   * @private
   */
  proto._noTransition = function( fn, args ) {
    // save transitionDuration before disabling
    var transitionDuration = this.options.transitionDuration;
    // disable transition
    this.options.transitionDuration = 0;
    // do it
    var returnValue = fn.apply( this, args );
    // re-enable transition for reveal
    this.options.transitionDuration = transitionDuration;
    return returnValue;
  };

  // ----- helper methods ----- //

  /**
   * getter method for getting filtered item elements
   * @returns {Array} elems - collection of item elements
   */
  proto.getFilteredItemElements = function() {
    return this.filteredItems.map( function( item ) {
      return item.element;
    });
  };

  // -----  ----- //

  return Isotope;

}));


/*!
 * imagesLoaded PACKAGED v4.1.4
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

/**
 * EvEmitter v1.1.0
 * Lil' event emitter
 * MIT License
 */

/* jshint unused: true, undef: true, strict: true */

( function( global, factory ) {
  // universal module definition
  /* jshint strict: false */ /* globals define, module, window */
  if ( typeof define == 'function' && define.amd ) {
    // AMD - RequireJS
    define( 'ev-emitter/ev-emitter',factory );
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS - Browserify, Webpack
    module.exports = factory();
  } else {
    // Browser globals
    global.EvEmitter = factory();
  }

}( typeof window != 'undefined' ? window : this, function() {



function EvEmitter() {}

var proto = EvEmitter.prototype;

proto.on = function( eventName, listener ) {
  if ( !eventName || !listener ) {
    return;
  }
  // set events hash
  var events = this._events = this._events || {};
  // set listeners array
  var listeners = events[ eventName ] = events[ eventName ] || [];
  // only add once
  if ( listeners.indexOf( listener ) == -1 ) {
    listeners.push( listener );
  }

  return this;
};

proto.once = function( eventName, listener ) {
  if ( !eventName || !listener ) {
    return;
  }
  // add event
  this.on( eventName, listener );
  // set once flag
  // set onceEvents hash
  var onceEvents = this._onceEvents = this._onceEvents || {};
  // set onceListeners object
  var onceListeners = onceEvents[ eventName ] = onceEvents[ eventName ] || {};
  // set flag
  onceListeners[ listener ] = true;

  return this;
};

proto.off = function( eventName, listener ) {
  var listeners = this._events && this._events[ eventName ];
  if ( !listeners || !listeners.length ) {
    return;
  }
  var index = listeners.indexOf( listener );
  if ( index != -1 ) {
    listeners.splice( index, 1 );
  }

  return this;
};

proto.emitEvent = function( eventName, args ) {
  var listeners = this._events && this._events[ eventName ];
  if ( !listeners || !listeners.length ) {
    return;
  }
  // copy over to avoid interference if .off() in listener
  listeners = listeners.slice(0);
  args = args || [];
  // once stuff
  var onceListeners = this._onceEvents && this._onceEvents[ eventName ];

  for ( var i=0; i < listeners.length; i++ ) {
    var listener = listeners[i]
    var isOnce = onceListeners && onceListeners[ listener ];
    if ( isOnce ) {
      // remove listener
      // remove before trigger to prevent recursion
      this.off( eventName, listener );
      // unset once flag
      delete onceListeners[ listener ];
    }
    // trigger listener
    listener.apply( this, args );
  }

  return this;
};

proto.allOff = function() {
  delete this._events;
  delete this._onceEvents;
};

return EvEmitter;

}));

/*!
 * imagesLoaded v4.1.4
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

( function( window, factory ) { 'use strict';
  // universal module definition

  /*global define: false, module: false, require: false */

  if ( typeof define == 'function' && define.amd ) {
    // AMD
    define( [
      'ev-emitter/ev-emitter'
    ], function( EvEmitter ) {
      return factory( window, EvEmitter );
    });
  } else if ( typeof module == 'object' && module.exports ) {
    // CommonJS
    module.exports = factory(
      window,
      require('ev-emitter')
    );
  } else {
    // browser global
    window.imagesLoaded = factory(
      window,
      window.EvEmitter
    );
  }

})( typeof window !== 'undefined' ? window : this,

// --------------------------  factory -------------------------- //

function factory( window, EvEmitter ) {



var $ = window.jQuery;
var console = window.console;

// -------------------------- helpers -------------------------- //

// extend objects
function extend( a, b ) {
  for ( var prop in b ) {
    a[ prop ] = b[ prop ];
  }
  return a;
}

var arraySlice = Array.prototype.slice;

// turn element or nodeList into an array
function makeArray( obj ) {
  if ( Array.isArray( obj ) ) {
    // use object if already an array
    return obj;
  }

  var isArrayLike = typeof obj == 'object' && typeof obj.length == 'number';
  if ( isArrayLike ) {
    // convert nodeList to array
    return arraySlice.call( obj );
  }

  // array of single index
  return [ obj ];
}

// -------------------------- imagesLoaded -------------------------- //

/**
 * @param {Array, Element, NodeList, String} elem
 * @param {Object or Function} options - if function, use as callback
 * @param {Function} onAlways - callback function
 */
function ImagesLoaded( elem, options, onAlways ) {
  // coerce ImagesLoaded() without new, to be new ImagesLoaded()
  if ( !( this instanceof ImagesLoaded ) ) {
    return new ImagesLoaded( elem, options, onAlways );
  }
  // use elem as selector string
  var queryElem = elem;
  if ( typeof elem == 'string' ) {
    queryElem = document.querySelectorAll( elem );
  }
  // bail if bad element
  if ( !queryElem ) {
    console.error( 'Bad element for imagesLoaded ' + ( queryElem || elem ) );
    return;
  }

  this.elements = makeArray( queryElem );
  this.options = extend( {}, this.options );
  // shift arguments if no options set
  if ( typeof options == 'function' ) {
    onAlways = options;
  } else {
    extend( this.options, options );
  }

  if ( onAlways ) {
    this.on( 'always', onAlways );
  }

  this.getImages();

  if ( $ ) {
    // add jQuery Deferred object
    this.jqDeferred = new $.Deferred();
  }

  // HACK check async to allow time to bind listeners
  setTimeout( this.check.bind( this ) );
}

ImagesLoaded.prototype = Object.create( EvEmitter.prototype );

ImagesLoaded.prototype.options = {};

ImagesLoaded.prototype.getImages = function() {
  this.images = [];

  // filter & find items if we have an item selector
  this.elements.forEach( this.addElementImages, this );
};

/**
 * @param {Node} element
 */
ImagesLoaded.prototype.addElementImages = function( elem ) {
  // filter siblings
  if ( elem.nodeName == 'IMG' ) {
    this.addImage( elem );
  }
  // get background image on element
  if ( this.options.background === true ) {
    this.addElementBackgroundImages( elem );
  }

  // find children
  // no non-element nodes, #143
  var nodeType = elem.nodeType;
  if ( !nodeType || !elementNodeTypes[ nodeType ] ) {
    return;
  }
  var childImgs = elem.querySelectorAll('img');
  // concat childElems to filterFound array
  for ( var i=0; i < childImgs.length; i++ ) {
    var img = childImgs[i];
    this.addImage( img );
  }

  // get child background images
  if ( typeof this.options.background == 'string' ) {
    var children = elem.querySelectorAll( this.options.background );
    for ( i=0; i < children.length; i++ ) {
      var child = children[i];
      this.addElementBackgroundImages( child );
    }
  }
};

var elementNodeTypes = {
  1: true,
  9: true,
  11: true
};

ImagesLoaded.prototype.addElementBackgroundImages = function( elem ) {
  var style = getComputedStyle( elem );
  if ( !style ) {
    // Firefox returns null if in a hidden iframe https://bugzil.la/548397
    return;
  }
  // get url inside url("...")
  var reURL = /url\((['"])?(.*?)\1\)/gi;
  var matches = reURL.exec( style.backgroundImage );
  while ( matches !== null ) {
    var url = matches && matches[2];
    if ( url ) {
      this.addBackground( url, elem );
    }
    matches = reURL.exec( style.backgroundImage );
  }
};

/**
 * @param {Image} img
 */
ImagesLoaded.prototype.addImage = function( img ) {
  var loadingImage = new LoadingImage( img );
  this.images.push( loadingImage );
};

ImagesLoaded.prototype.addBackground = function( url, elem ) {
  var background = new Background( url, elem );
  this.images.push( background );
};

ImagesLoaded.prototype.check = function() {
  var _this = this;
  this.progressedCount = 0;
  this.hasAnyBroken = false;
  // complete if no images
  if ( !this.images.length ) {
    this.complete();
    return;
  }

  function onProgress( image, elem, message ) {
    // HACK - Chrome triggers event before object properties have changed. #83
    setTimeout( function() {
      _this.progress( image, elem, message );
    });
  }

  this.images.forEach( function( loadingImage ) {
    loadingImage.once( 'progress', onProgress );
    loadingImage.check();
  });
};

ImagesLoaded.prototype.progress = function( image, elem, message ) {
  this.progressedCount++;
  this.hasAnyBroken = this.hasAnyBroken || !image.isLoaded;
  // progress event
  this.emitEvent( 'progress', [ this, image, elem ] );
  if ( this.jqDeferred && this.jqDeferred.notify ) {
    this.jqDeferred.notify( this, image );
  }
  // check if completed
  if ( this.progressedCount == this.images.length ) {
    this.complete();
  }

  if ( this.options.debug && console ) {
    console.log( 'progress: ' + message, image, elem );
  }
};

ImagesLoaded.prototype.complete = function() {
  var eventName = this.hasAnyBroken ? 'fail' : 'done';
  this.isComplete = true;
  this.emitEvent( eventName, [ this ] );
  this.emitEvent( 'always', [ this ] );
  if ( this.jqDeferred ) {
    var jqMethod = this.hasAnyBroken ? 'reject' : 'resolve';
    this.jqDeferred[ jqMethod ]( this );
  }
};

// --------------------------  -------------------------- //

function LoadingImage( img ) {
  this.img = img;
}

LoadingImage.prototype = Object.create( EvEmitter.prototype );

LoadingImage.prototype.check = function() {
  // If complete is true and browser supports natural sizes,
  // try to check for image status manually.
  var isComplete = this.getIsImageComplete();
  if ( isComplete ) {
    // report based on naturalWidth
    this.confirm( this.img.naturalWidth !== 0, 'naturalWidth' );
    return;
  }

  // If none of the checks above matched, simulate loading on detached element.
  this.proxyImage = new Image();
  this.proxyImage.addEventListener( 'load', this );
  this.proxyImage.addEventListener( 'error', this );
  // bind to image as well for Firefox. #191
  this.img.addEventListener( 'load', this );
  this.img.addEventListener( 'error', this );
  this.proxyImage.src = this.img.src;
};

LoadingImage.prototype.getIsImageComplete = function() {
  // check for non-zero, non-undefined naturalWidth
  // fixes Safari+InfiniteScroll+Masonry bug infinite-scroll#671
  return this.img.complete && this.img.naturalWidth;
};

LoadingImage.prototype.confirm = function( isLoaded, message ) {
  this.isLoaded = isLoaded;
  this.emitEvent( 'progress', [ this, this.img, message ] );
};

// ----- events ----- //

// trigger specified handler for event type
LoadingImage.prototype.handleEvent = function( event ) {
  var method = 'on' + event.type;
  if ( this[ method ] ) {
    this[ method ]( event );
  }
};

LoadingImage.prototype.onload = function() {
  this.confirm( true, 'onload' );
  this.unbindEvents();
};

LoadingImage.prototype.onerror = function() {
  this.confirm( false, 'onerror' );
  this.unbindEvents();
};

LoadingImage.prototype.unbindEvents = function() {
  this.proxyImage.removeEventListener( 'load', this );
  this.proxyImage.removeEventListener( 'error', this );
  this.img.removeEventListener( 'load', this );
  this.img.removeEventListener( 'error', this );
};

// -------------------------- Background -------------------------- //

function Background( url, element ) {
  this.url = url;
  this.element = element;
  this.img = new Image();
}

// inherit LoadingImage prototype
Background.prototype = Object.create( LoadingImage.prototype );

Background.prototype.check = function() {
  this.img.addEventListener( 'load', this );
  this.img.addEventListener( 'error', this );
  this.img.src = this.url;
  // check if image is already complete
  var isComplete = this.getIsImageComplete();
  if ( isComplete ) {
    this.confirm( this.img.naturalWidth !== 0, 'naturalWidth' );
    this.unbindEvents();
  }
};

Background.prototype.unbindEvents = function() {
  this.img.removeEventListener( 'load', this );
  this.img.removeEventListener( 'error', this );
};

Background.prototype.confirm = function( isLoaded, message ) {
  this.isLoaded = isLoaded;
  this.emitEvent( 'progress', [ this, this.element, message ] );
};

// -------------------------- jQuery -------------------------- //

ImagesLoaded.makeJQueryPlugin = function( jQuery ) {
  jQuery = jQuery || window.jQuery;
  if ( !jQuery ) {
    return;
  }
  // set local variable
  $ = jQuery;
  // $().imagesLoaded()
  $.fn.imagesLoaded = function( options, callback ) {
    var instance = new ImagesLoaded( this, options, callback );
    return instance.jqDeferred.promise( $(this) );
  };
};
// try making plugin
ImagesLoaded.makeJQueryPlugin();

// --------------------------  -------------------------- //

return ImagesLoaded;

});


(function ($) {
    "use strict";

    $(document).on("click", ".eael-load-more-button", function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var $this = $(this),
            $text = $("span", $this).html(),
            $widget_id = $this.data("widget"),
            $scope = $(".elementor-element-" + $widget_id),
            $class = $this.data("class"),
            $args = $this.data("args"),
            $settings = $this.data("settings"),
            $layout = $this.data("layout"),
            $page = parseInt($this.data("page")) + 1;

        if (typeof $widget_id == 'undefined' || typeof $args == 'undefined') {
            return;
        }

        var obj = {};
        var $data = {
            action: "load_more",
            class: $class,
            args: $args,
            settings: $settings,
            page: $page
        };

        String($args).split('&').forEach(function(item, index) {
            var arr = String(item).split('=');
            obj[arr[0]] = arr[1];
        });



        if(obj.orderby == 'rand') {
            var $printed = $('.eael-grid-post');

            if($printed.length)  {
                var $ids = [];
                $printed.each(function(index, item) {
                    var $id = $(item).data('id');
                    $ids.push($id);
                });

                $data.post__not_in  = $ids;
            }
        }

        $this.addClass("button--loading");
        $("span", $this).html("Loading...");

        $.ajax({
            url: localize.ajaxurl,
            type: "post",
            data: $data,
            success: function (response) {
                var $content = $(response);

                if (
                    $content.hasClass("no-posts-found") ||
                    $content.length == 0
                ) {
                    $this.remove();
                } else {
                    $(".eael-post-appender", $scope).append($content);

                    if ($layout == "masonry") {
                        var $isotope = $(".eael-post-appender", $scope).isotope();
                        $isotope.isotope("appended", $content).isotope("layout");

                        $isotope.imagesLoaded().progress(function () {
                            $isotope.isotope("layout");
                        });
                    }

                    $this.removeClass("button--loading");
                    $("span", $this).html($text);

                    $this.data("page", $page);
                }
            },
            error: function (response) {
                console.log(response);
            }
        });
    });
})(jQuery);

var AdvAccordionHandler = function($scope, $) {
    var $advanceAccordion = $scope.find(".eael-adv-accordion"),
        $accordionHeader = $scope.find(".eael-accordion-header"),
        $accordionType = $advanceAccordion.data("accordion-type"),
        $accordionSpeed = $advanceAccordion.data("toogle-speed");

    // Open default actived tab
    $accordionHeader.each(function() {
        if ($(this).hasClass("active-default")) {
            $(this).addClass("show active");
            $(this)
                .next()
                .slideDown($accordionSpeed);
        }
    });

    // Remove multiple click event for nested accordion
    $accordionHeader.unbind("click");

    $accordionHeader.click(function(e) {
        e.preventDefault();

        var $this = $(this);

        if ($accordionType === "accordion") {
            if ($this.hasClass("show")) {
                $this.removeClass("show active");
                $this.next().slideUp($accordionSpeed);
            } else {
                $this
                    .parent()
                    .parent()
                    .find(".eael-accordion-header")
                    .removeClass("show active");
                $this
                    .parent()
                    .parent()
                    .find(".eael-accordion-content")
                    .slideUp($accordionSpeed);
                $this.toggleClass("show active");
                $this.next().slideToggle($accordionSpeed);
            }
        } else {
            // For acccordion type 'toggle'
            if ($this.hasClass("show")) {
                $this.removeClass("show active");
                $this.next().slideUp($accordionSpeed);
            } else {
                $this.addClass("show active");
                $this.next().slideDown($accordionSpeed);
            }
        }
    });
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-adv-accordion.default",
        AdvAccordionHandler
    );
});

var advanced_data_table_timeout,
	advanced_data_table_active_cell = null,
	advanced_data_table_drag_start_x,
	advanced_data_table_drag_start_width,
	advanced_data_table_drag_el,
	advanced_data_table_dragging = false;

var Advanced_Data_Table_Update_View = function(view, refresh, value) {
	var model = view.model;

	// disable elementor remote server render
	model.remoteRender = refresh;

	if (elementor.config.version > "2.7.6") {
		var container = view.getContainer();
		var settings = view.getContainer().settings.attributes;

		Object.keys(value).forEach(function(key) {
			settings[key] = value[key];
		});

		parent.window.$e.run("document/elements/settings", {
			container: container,
			settings: settings,
			options: {
				external: refresh
			}
		});
	} else {
		// update backbone model
		Object.keys(value).forEach(function(key) {
			model.setSetting(key, value[key]);
		});
	}

	// enable elementor remote server render just after elementor throttle
	// ignore multiple assign
	advanced_data_table_timeout = setTimeout(function() {
		model.remoteRender = true;
	}, 1001);
};

var Advanced_Data_Table_Update_Model = function(model, container, refresh, value) {
	// disable elementor remote server render
	model.remoteRender = refresh;

	if (elementor.config.version > "2.7.6") {
		var settings = container.settings.attributes;

		Object.keys(value).forEach(function(key) {
			settings[key] = value[key];
		});

		parent.window.$e.run("document/elements/settings", {
			container: container,
			settings: settings,
			options: {
				external: refresh
			}
		});
	} else {
		// update backbone model
		Object.keys(value).forEach(function(key) {
			model.setSetting(key, value[key]);
		});
	}

	// enable elementor remote server render just after elementor throttle
	// ignore multiple assign
	advanced_data_table_timeout = setTimeout(function() {
		model.remoteRender = true;
	}, 1001);
};

var Advanced_Data_Table = function($scope, $) {
	var table = $scope.context.querySelector(".ea-advanced-data-table");
	var search = $scope.context.querySelector(".ea-advanced-data-table-search");
	var pagination = $scope.context.querySelector(".ea-advanced-data-table-pagination");
	var classCollection = {};

	if (isEditMode) {
		// add edit class
		table.classList.add("ea-advanced-data-table-editable");

		// insert editable area
		table.querySelectorAll("th, td").forEach(function(el) {
			var value = el.innerHTML;

			if (value.indexOf('<textarea rows="1">') !== 0) {
				el.innerHTML = '<textarea rows="1">' + value + "</textarea>";
			}
		});

		// drag
		table.addEventListener("mousedown", function(e) {
			if (e.target.tagName.toLowerCase() === "th") {
				e.stopPropagation();

				advanced_data_table_dragging = true;
				advanced_data_table_drag_el = e.target;
				advanced_data_table_drag_start_x = e.pageX;
				advanced_data_table_drag_start_width = e.target.offsetWidth;
			}
		});

		document.addEventListener("mousemove", function(e) {
			if (advanced_data_table_dragging) {
				advanced_data_table_drag_el.style.width = advanced_data_table_drag_start_width + (event.pageX - advanced_data_table_drag_start_x) + "px";
			}
		});
		document.addEventListener("mouseup", function(e) {
			if (advanced_data_table_dragging) {
				advanced_data_table_dragging = false;
			}
		});
	} else {
		// search
		if (search) {
			search.addEventListener("input", function(e) {
				var input = this.value.toLowerCase();
				var hasSort = table.classList.contains("ea-advanced-data-table-sortable");

				if (table.rows.length > 1) {
					if (input.length > 0) {
						if (hasSort) {
							table.classList.add("ea-advanced-data-table-unsortable");
						}

						if (pagination && pagination.innerHTML.length > 0) {
							pagination.style.display = "none";
						}

						for (var i = 1; i < table.rows.length; i++) {
							var matchFound = false;

							if (table.rows[i].cells.length > 0) {
								for (var j = 0; j < table.rows[i].cells.length; j++) {
									if (table.rows[i].cells[j].textContent.toLowerCase().indexOf(input) > -1) {
										matchFound = true;
										break;
									}
								}
							}

							if (matchFound) {
								table.rows[i].style.display = "table-row";
							} else {
								table.rows[i].style.display = "none";
							}
						}
					} else {
						if (hasSort) {
							table.classList.remove("ea-advanced-data-table-unsortable");
						}

						if (pagination && pagination.innerHTML.length > 0) {
							pagination.style.display = "";

							var currentPage = pagination.querySelector(".ea-advanced-data-table-pagination-current").dataset.page;
							var startIndex = (currentPage - 1) * table.dataset.itemsPerPage + 1;
							var endIndex = currentPage * table.dataset.itemsPerPage;

							for (var i = 1; i <= table.rows.length - 1; i++) {
								if (i >= startIndex && i <= endIndex) {
									table.rows[i].style.display = "table-row";
								} else {
									table.rows[i].style.display = "none";
								}
							}
						} else {
							for (var i = 1; i <= table.rows.length - 1; i++) {
								table.rows[i].style.display = "table-row";
							}
						}
					}
				}
			});
		}

		// sort
		if (table.classList.contains("ea-advanced-data-table-sortable")) {
			table.addEventListener("click", function(e) {
				if (e.target.tagName.toLowerCase() === "th") {
					var index = e.target.cellIndex;
					var currentPage = 1;
					var startIndex = 1;
					var endIndex = table.rows.length - 1;
					var sort = "";
					var classList = e.target.classList;
					var collection = [];
					var origTable = table.cloneNode(true);

					if (classList.contains("asc")) {
						e.target.classList.remove("asc");
						e.target.classList.add("desc");
						sort = "desc";
					} else if (classList.contains("desc")) {
						e.target.classList.remove("desc");
						e.target.classList.add("asc");
						sort = "asc";
					} else {
						e.target.classList.add("asc");
						sort = "asc";
					}

					if (pagination && pagination.innerHTML.length > 0) {
						currentPage = pagination.querySelector(".ea-advanced-data-table-pagination-current").dataset.page;
						startIndex = (currentPage - 1) * table.dataset.itemsPerPage + 1;
						endIndex =
							endIndex - (currentPage - 1) * table.dataset.itemsPerPage >= table.dataset.itemsPerPage ? currentPage * table.dataset.itemsPerPage : endIndex;
					}

					// collect header class
					classCollection[currentPage] = [];

					table.querySelectorAll("th").forEach(function(el) {
						if (el.cellIndex != index) {
							el.classList.remove("asc", "desc");
						}

						classCollection[currentPage].push(el.classList.contains("asc") ? "asc" : el.classList.contains("desc") ? "desc" : "");
					});

					// collect table cells value
					for (var i = startIndex; i <= endIndex; i++) {
						var value;
						var cell = table.rows[i].cells[index];

						if (isNaN(parseInt(cell.innerText))) {
							value = cell.innerText.toLowerCase();
						} else {
							value = parseInt(cell.innerText);
						}

						collection.push({ index: i, value: value });
					}

					// sort collection array
					if (sort == "asc") {
						collection.sort(function(x, y) {
							return x.value > y.value ? 1 : -1;
						});
					} else if (sort == "desc") {
						collection.sort(function(x, y) {
							return x.value < y.value ? 1 : -1;
						});
					}

					// sort table
					collection.forEach(function(row, index) {
						table.rows[startIndex + index].innerHTML = origTable.rows[row.index].innerHTML;
					});
				}
			});
		}

		// paginated table
		if (table.classList.contains("ea-advanced-data-table-paginated")) {
			var paginationHTML = "";
			var currentPage = 1;
			var startIndex = 1;
			var endIndex = currentPage * table.dataset.itemsPerPage;
			var maxPages = Math.ceil((table.rows.length - 1) / table.dataset.itemsPerPage);

			// insert pagination
			if (maxPages > 1) {
				for (var i = 1; i <= maxPages; i++) {
					paginationHTML += '<a href="#" data-page="' + i + '" class="' + (i == 1 ? "ea-advanced-data-table-pagination-current" : "") + '">' + i + "</a>";
				}

				pagination.insertAdjacentHTML(
					"beforeend",
					'<a href="#" data-page="1">&laquo;</a>' + paginationHTML + '<a href="#" data-page="' + maxPages + '">&raquo;</a>'
				);
			}

			// make initial item visible
			for (var i = 0; i <= endIndex; i++) {
				if (i >= table.rows.length) {
					break;
				}

				table.rows[i].style.display = "table-row";
			}

			// paginate on click
			pagination.addEventListener("click", function(e) {
				e.preventDefault();

				if (e.target.tagName.toLowerCase() == "a") {
					currentPage = e.target.dataset.page;
					startIndex = (currentPage - 1) * table.dataset.itemsPerPage + 1;
					endIndex = currentPage * table.dataset.itemsPerPage;

					pagination.querySelectorAll(".ea-advanced-data-table-pagination-current").forEach(function(el) {
						el.classList.remove("ea-advanced-data-table-pagination-current");
					});

					pagination.querySelectorAll('[data-page="' + currentPage + '"]').forEach(function(el) {
						el.classList.add("ea-advanced-data-table-pagination-current");
					});

					for (var i = 1; i <= table.rows.length - 1; i++) {
						if (i >= startIndex && i <= endIndex) {
							table.rows[i].style.display = "table-row";
						} else {
							table.rows[i].style.display = "none";
						}
					}

					table.querySelectorAll("th").forEach(function(el, index) {
						el.classList.remove("asc", "desc");

						if (typeof classCollection[currentPage] != "undefined") {
							if (classCollection[currentPage][index]) {
								el.classList.add(classCollection[currentPage][index]);
							}
						}
					});
				}
			});
		}
	}
};

var Advanced_Data_Table_Click_Handler = function(panel, model, view) {
	if (event.target.dataset.event == "ea:advTable:export") {
		// export
		var table = view.el.querySelector(".ea-advanced-data-table-" + model.attributes.id);
		var rows = table.querySelectorAll("table tr");
		var csv = [];

		// generate csv
		for (var i = 0; i < rows.length; i++) {
			var row = [];
			var cols = rows[i].querySelectorAll("th, td");

			for (var j = 0; j < cols.length; j++) {
				row.push(
					JSON.stringify(
						cols[j]
							.querySelector("textarea")
							.value.replace(/(\r\n|\n|\r)/gm, " ")
							.trim()
					)
				);
			}

			csv.push(row.join(","));
		}

		// download
		var csv_file = new Blob([csv.join("\n")], { type: "text/csv" });
		var download_link = parent.document.createElement("a");

		download_link.classList.add("ea-adv-data-table-download-" + model.attributes.id);
		download_link.download = "ea-adv-data-table-" + model.attributes.id + ".csv";
		download_link.href = window.URL.createObjectURL(csv_file);
		download_link.style.display = "none";
		parent.document.body.appendChild(download_link);
		download_link.click();

		parent.document.querySelector(".ea-adv-data-table-download-" + model.attributes.id).remove();
	} else if (event.target.dataset.event == "ea:advTable:import") {
		// import
		var textarea = panel.el.querySelector(".ea_adv_table_csv_string");
		var enableHeader = panel.el.querySelector(".ea_adv_table_csv_string_table").checked;
		var csvArr = textarea.value.split("\n");
		var header = "";
		var body = "";

		if (textarea.value.length > 0) {
			body += "<tbody>";
			csvArr.forEach(function(row, index) {
				cols = row.match(/"([^\\"]|\\")*"/g) || row.split(",");

				if (cols.length > 0) {
					if (enableHeader && index == 0) {
						header += "<thead><tr>";
						cols.forEach(function(col) {
							if (col.match(/(^"")|(^")|("$)|(""$)/g)) {
								header += "<th>" + JSON.parse(col) + "</th>";
							} else {
								header += "<th>" + col + "</th>";
							}
						});
						header += "</tr></thead>";
					} else {
						body += "<tr>";
						cols.forEach(function(col) {
							if (col.match(/(^"")|(^")|("$)|(""$)/g)) {
								body += "<td>" + JSON.parse(col) + "</td>";
							} else {
								body += "<td>" + col + "</td>";
							}
						});
						body += "</tr>";
					}
				}
			});
			body += "</tbody>";

			if (header.length > 0 || body.length > 0) {
				Advanced_Data_Table_Update_View(view, true, {
					ea_adv_data_table_static_html: header + body
				});
			}
		}

		textarea.value = "";
	}
};

// Inline edit
var Advanced_Data_Table_Inline_Edit = function(panel, model, view) {
	var localRender = function() {
		var interval = setInterval(function() {
			if (view.el.querySelector(".ea-advanced-data-table")) {
				var table = view.el.querySelector(".ea-advanced-data-table-" + model.attributes.id);

				table.addEventListener("focusin", function(e) {
					if (e.target.tagName.toLowerCase() == "textarea") {
						advanced_data_table_active_cell = e.target;
					}
				});

				table.addEventListener("input", function(e) {
					if (e.target.tagName.toLowerCase() == "textarea") {
						clearTimeout(advanced_data_table_timeout);

						// clone current table
						var origTable = table.cloneNode(true);

						// remove editable area
						origTable.querySelectorAll("th, td").forEach(function(el) {
							var value = el.querySelector("textarea").value;
							el.innerHTML = value;
						});

						// update table
						Advanced_Data_Table_Update_View(view, false, {
							ea_adv_data_table_static_html: origTable.innerHTML
						});
					}
				});

				// drag
				table.addEventListener("mouseup", function(e) {
					if (e.target.tagName.toLowerCase() === "th") {
						clearTimeout(advanced_data_table_timeout);

						// clone current table
						var origTable = table.cloneNode(true);

						// remove editable area
						origTable.querySelectorAll("th, td").forEach(function(el) {
							var value = el.querySelector("textarea").value;
							el.innerHTML = value;
						});

						// update table
						Advanced_Data_Table_Update_View(view, false, {
							ea_adv_data_table_static_html: origTable.innerHTML
						});
					}
				});

				// clear style
				table.addEventListener("dblclick", function(e) {
					if (e.target.tagName.toLowerCase() === "th") {
						e.stopPropagation();

						e.target.style.width = "";
					}
				});

				clearInterval(interval);
			}
		}, 10);
	};

	// init
	localRender();

	// after render
	model.on("remote:render", function() {
		localRender();
	});

	// export import handler
	var handler = Advanced_Data_Table_Click_Handler.bind(this, panel, model, view);

	panel.el.addEventListener("click", handler);

	panel.currentPageView.on("destroy", function() {
		panel.el.removeEventListener("click", handler);
	});
};

Advanced_Data_Table_Context_Menu = function(groups, element) {
	if (element.options.model.attributes.widgetType == "eael-advanced-data-table") {
		groups.push({
			name: "ea_advanced_data_table",
			actions: [
				{
					name: "add_row_above",
					title: "Add Row Above",
					callback: function() {
						var table = document.querySelector(".ea-advanced-data-table-" + element.options.model.attributes.id);

						if (advanced_data_table_active_cell !== null && advanced_data_table_active_cell.parentNode.tagName.toLowerCase() != "th") {
							var index = advanced_data_table_active_cell.parentNode.parentNode.rowIndex;
							var row = table.insertRow(index);

							for (var i = 0; i < table.rows[0].cells.length; i++) {
								var cell = row.insertCell(i);
								cell.innerHTML = '<textarea rows="1"></textarea>';
							}

							advanced_data_table_active_cell = null;

							// clone current table
							var origTable = table.cloneNode(true);

							// remove editable area
							origTable.querySelectorAll("th, td").forEach(function(el) {
								var value = el.querySelector("textarea").value;
								el.innerHTML = value;
							});

							// update model
							Advanced_Data_Table_Update_Model(element.options.model, element.container, false, {
								ea_adv_data_table_static_html: origTable.innerHTML
							});
						}
					}
				},
				{
					name: "add_row_below",
					title: "Add Row Below",
					callback: function() {
						var table = document.querySelector(".ea-advanced-data-table-" + element.options.model.attributes.id);

						if (advanced_data_table_active_cell !== null) {
							var index = advanced_data_table_active_cell.parentNode.parentNode.rowIndex + 1;
							var row = table.insertRow(index);

							for (var i = 0; i < table.rows[0].cells.length; i++) {
								var cell = row.insertCell(i);
								cell.innerHTML = '<textarea rows="1"></textarea>';
							}

							advanced_data_table_active_cell = null;

							// clone current table
							var origTable = table.cloneNode(true);

							// remove editable area
							origTable.querySelectorAll("th, td").forEach(function(el) {
								var value = el.querySelector("textarea").value;
								el.innerHTML = value;
							});

							// update model
							Advanced_Data_Table_Update_Model(element.options.model, element.container, false, {
								ea_adv_data_table_static_html: origTable.innerHTML
							});
						}
					}
				},
				{
					name: "add_column_left",
					title: "Add Column Left",
					callback: function() {
						var table = document.querySelector(".ea-advanced-data-table-" + element.options.model.attributes.id);

						if (advanced_data_table_active_cell !== null) {
							var index = advanced_data_table_active_cell.parentNode.cellIndex;

							for (var i = 0; i < table.rows.length; i++) {
								if (table.rows[i].cells[0].tagName.toLowerCase() == "th") {
									var cell = table.rows[i].insertBefore(document.createElement("th"), table.rows[i].cells[index]);
								} else {
									var cell = table.rows[i].insertCell(index);
								}

								cell.innerHTML = '<textarea rows="1"></textarea>';
							}

							advanced_data_table_active_cell = null;

							// clone current table
							var origTable = table.cloneNode(true);

							// remove editable area
							origTable.querySelectorAll("th, td").forEach(function(el) {
								var value = el.querySelector("textarea").value;
								el.innerHTML = value;
							});

							// update model
							Advanced_Data_Table_Update_Model(element.options.model, element.container, false, {
								ea_adv_data_table_static_html: origTable.innerHTML
							});
						}
					}
				},
				{
					name: "add_column_right",
					title: "Add Column Right",
					callback: function() {
						var table = document.querySelector(".ea-advanced-data-table-" + element.options.model.attributes.id);

						if (advanced_data_table_active_cell !== null) {
							var index = advanced_data_table_active_cell.parentNode.cellIndex + 1;

							for (var i = 0; i < table.rows.length; i++) {
								if (table.rows[i].cells[0].tagName.toLowerCase() == "th") {
									var cell = table.rows[i].insertBefore(document.createElement("th"), table.rows[i].cells[index]);
								} else {
									var cell = table.rows[i].insertCell(index);
								}

								cell.innerHTML = '<textarea rows="1"></textarea>';
							}

							advanced_data_table_active_cell = null;

							// clone current table
							var origTable = table.cloneNode(true);

							// remove editable area
							origTable.querySelectorAll("th, td").forEach(function(el) {
								var value = el.querySelector("textarea").value;
								el.innerHTML = value;
							});

							// update model
							Advanced_Data_Table_Update_Model(element.options.model, element.container, false, {
								ea_adv_data_table_static_html: origTable.innerHTML
							});
						}
					}
				},
				{
					name: "delete_row",
					title: "Delete Row",
					callback: function() {
						var table = document.querySelector(".ea-advanced-data-table-" + element.options.model.attributes.id);

						if (advanced_data_table_active_cell !== null) {
							var index = advanced_data_table_active_cell.parentNode.parentNode.rowIndex;

							table.deleteRow(index);

							advanced_data_table_active_cell = null;

							// clone current table
							var origTable = table.cloneNode(true);

							// remove editable area
							origTable.querySelectorAll("th, td").forEach(function(el) {
								var value = el.querySelector("textarea").value;
								el.innerHTML = value;
							});

							// update model
							Advanced_Data_Table_Update_Model(element.options.model, element.container, false, {
								ea_adv_data_table_static_html: origTable.innerHTML
							});
						}
					}
				},
				{
					name: "delete_column",
					title: "Delete Column",
					callback: function() {
						var table = document.querySelector(".ea-advanced-data-table-" + element.options.model.attributes.id);

						if (advanced_data_table_active_cell !== null) {
							var index = advanced_data_table_active_cell.parentNode.cellIndex;

							for (var i = 0; i < table.rows.length; i++) {
								table.rows[i].deleteCell(index);
							}

							advanced_data_table_active_cell = null;

							// clone current table
							var origTable = table.cloneNode(true);

							// remove editable area
							origTable.querySelectorAll("th, td").forEach(function(el) {
								var value = el.querySelector("textarea").value;
								el.innerHTML = value;
							});

							// update model
							Advanced_Data_Table_Update_Model(element.options.model, element.container, false, {
								ea_adv_data_table_static_html: origTable.innerHTML
							});
						}
					}
				}
			]
		});
	}

	return groups;
};

jQuery(window).on("elementor/frontend/init", function() {
	if (isEditMode) {
		elementor.hooks.addFilter("elements/widget/contextMenuGroups", Advanced_Data_Table_Context_Menu);
		elementor.hooks.addAction("panel/open_editor/widget/eael-advanced-data-table", Advanced_Data_Table_Inline_Edit);
	}

	elementorFrontend.hooks.addAction("frontend/element_ready/eael-advanced-data-table.default", Advanced_Data_Table);
});

var AdvanceTabHandler = function($scope, $) {
    var $currentTab = $scope.find(".eael-advance-tabs"),
        $currentTabId = "#" + $currentTab.attr("id").toString();

    $($currentTabId + " .eael-tabs-nav ul li").each(function(index) {
        if ($(this).hasClass("active-default")) {
            $($currentTabId + " .eael-tabs-nav > ul li")
                .removeClass("active")
                .addClass("inactive");
            $(this).removeClass("inactive");
        } else {
            if (index == 0) {
                $(this)
                    .removeClass("inactive")
                    .addClass("active");
            }
        }
    });

    $($currentTabId + " .eael-tabs-content div").each(function(index) {
        if ($(this).hasClass("active-default")) {
            $($currentTabId + " .eael-tabs-content > div").removeClass(
                "active"
            );
        } else {
            if (index == 0) {
                $(this)
                    .removeClass("inactive")
                    .addClass("active");
            }
        }
    });

    $($currentTabId + " .eael-tabs-nav ul li").click(function() {
        var currentTabIndex = $(this).index();
        var tabsContainer = $(this).closest(".eael-advance-tabs");

        var tabsNav = $(tabsContainer)
            .children(".eael-tabs-nav")
            .children("ul")
            .children("li");
        var tabsContent = $(tabsContainer)
            .children(".eael-tabs-content")
            .children("div");

        $(this)
            .parent("li")
            .addClass("active");

        $(tabsNav)
            .removeClass("active active-default")
            .addClass("inactive");
        $(this)
            .addClass("active")
            .removeClass("inactive");

        $(tabsContent)
            .removeClass("active")
            .addClass("inactive");
        $(tabsContent)
            .eq(currentTabIndex)
            .addClass("active")
            .removeClass("inactive");

        var $filterGallery = tabsContent.eq(currentTabIndex).find('.eael-filter-gallery-container'),
            $postGridGallery = tabsContent.eq(currentTabIndex).find('.eael-post-grid.eael-post-appender'),
            $twitterfeedGallery = tabsContent.eq(currentTabIndex).find('.eael-twitter-feed-masonry'),
            $instaGallery = tabsContent.eq(currentTabIndex).find('.eael-instafeed');

        if($postGridGallery.length) {
            $postGridGallery.isotope();
        }

        if($twitterfeedGallery.length) {
            $twitterfeedGallery.isotope("layout");
        }
        
        if($filterGallery.length) {
            $filterGallery.isotope("layout");
        }
        
        if($instaGallery.length) {
            $instaGallery.isotope("layout");
        }

        $(tabsContent).each(function(index) {
            $(this).removeClass("active-default");
        });
    });
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-adv-tabs.default",
        AdvanceTabHandler
    );
});

var ContentTicker = function($scope, $) {
    var $contentTicker = $scope.find(".eael-content-ticker").eq(0),
        $items =
            $contentTicker.data("items") !== undefined
                ? $contentTicker.data("items")
                : 1,
        $items_tablet =
            $contentTicker.data("items-tablet") !== undefined
                ? $contentTicker.data("items-tablet")
                : 1,
        $items_mobile =
            $contentTicker.data("items-mobile") !== undefined
                ? $contentTicker.data("items-mobile")
                : 1,
        $margin =
            $contentTicker.data("margin") !== undefined
                ? $contentTicker.data("margin")
                : 10,
        $margin_tablet =
            $contentTicker.data("margin-tablet") !== undefined
                ? $contentTicker.data("margin-tablet")
                : 10,
        $margin_mobile =
            $contentTicker.data("margin-mobile") !== undefined
                ? $contentTicker.data("margin-mobile")
                : 10,
        $effect =
            $contentTicker.data("effect") !== undefined
                ? $contentTicker.data("effect")
                : "slide",
        $speed =
            $contentTicker.data("speed") !== undefined
                ? $contentTicker.data("speed")
                : 400,
        $autoplay =
            $contentTicker.data("autoplay") !== undefined
                ? $contentTicker.data("autoplay")
                : 5000,
        $loop =
            $contentTicker.data("loop") !== undefined
                ? $contentTicker.data("loop")
                : false,
        $grab_cursor =
            $contentTicker.data("grab-cursor") !== undefined
                ? $contentTicker.data("grab-cursor")
                : false,
        $pagination =
            $contentTicker.data("pagination") !== undefined
                ? $contentTicker.data("pagination")
                : ".swiper-pagination",
        $arrow_next =
            $contentTicker.data("arrow-next") !== undefined
                ? $contentTicker.data("arrow-next")
                : ".swiper-button-next",
        $arrow_prev =
            $contentTicker.data("arrow-prev") !== undefined
                ? $contentTicker.data("arrow-prev")
                : ".swiper-button-prev",
        $pause_on_hover =
            $contentTicker.data("pause-on-hover") !== undefined
                ? $contentTicker.data("pause-on-hover")
                : "",
        $contentTickerOptions = {
            direction: "horizontal",
            loop: $loop,
            speed: $speed,
            effect: $effect,
            slidesPerView: $items,
            spaceBetween: $margin,
            grabCursor: $grab_cursor,
            paginationClickable: true,
            autoHeight: true,
            autoplay: {
                delay: $autoplay
            },
            pagination: {
                el: $pagination,
                clickable: true
            },
            navigation: {
                nextEl: $arrow_next,
                prevEl: $arrow_prev
            },
            breakpoints: {
                // when window width is <= 480px
                480: {
                    slidesPerView: $items_mobile,
                    spaceBetween: $margin_mobile
                },
                // when window width is <= 640px
                768: {
                    slidesPerView: $items_tablet,
                    spaceBetween: $margin_tablet
                }
            }
        };

    var $contentTickerSlider = new Swiper(
        $contentTicker,
        $contentTickerOptions
    );
    if ($autoplay === 0) {
        $contentTickerSlider.autoplay.stop();
    }
    if ($pause_on_hover && $autoplay !== 0) {
        $contentTicker.on("mouseenter", function() {
            $contentTickerSlider.autoplay.stop();
        });
        $contentTicker.on("mouseleave", function() {
            $contentTickerSlider.autoplay.start();
        });
    }
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-content-ticker.default",
        ContentTicker
    );
});
var CountDown = function($scope, $) {
    var $coundDown = $scope.find(".eael-countdown-wrapper").eq(0),
        $countdown_id =
            $coundDown.data("countdown-id") !== undefined
                ? $coundDown.data("countdown-id")
                : "",
        $expire_type =
            $coundDown.data("expire-type") !== undefined
                ? $coundDown.data("expire-type")
                : "",
        $expiry_text =
            $coundDown.data("expiry-text") !== undefined
                ? $coundDown.data("expiry-text")
                : "",
        $expiry_title =
            $coundDown.data("expiry-title") !== undefined
                ? $coundDown.data("expiry-title")
                : "",
        $redirect_url =
            $coundDown.data("redirect-url") !== undefined
                ? $coundDown.data("redirect-url")
                : "",
        $template =
            $coundDown.data("template") !== undefined
                ? $coundDown.data("template")
                : "";

    jQuery(document).ready(function($) {
        "use strict";
        var countDown = $("#eael-countdown-" + $countdown_id);

        countDown.countdown({
            end: function() {
                if ($expire_type == "text") {
                    countDown.html(
                        '<div class="eael-countdown-finish-message"><h4 class="expiry-title">' +
                            $expiry_title +
                            "</h4>" +
                            '<div class="eael-countdown-finish-text">' +
                            $expiry_text +
                            "</div></div>"
                    );
                } else if ($expire_type === "url") {
                    var editMode = $("body").find("#elementor").length;
                    if (editMode > 0) {
                        countDown.html(
                            "Your Page will be redirected to given URL (only on Frontend)."
                        );
                    } else {
                        window.location.href = $redirect_url;
                    }
                } else if ($expire_type === "template") {
                    countDown.html($template);
                } else {
                    //do nothing!
                }
            }
        });
    });
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-countdown.default",
        CountDown
    );
});

var dataTable = function($scope, $) {
	var $_this = $scope.find(".eael-data-table-wrap"),
		$id = $_this.data("table_id");

	if (typeof enableProSorter !== "undefined" && $.isFunction(enableProSorter)) {
		$(document).ready(function() {
			enableProSorter(jQuery, $_this);
		});
	}

	var responsive = $_this.data("custom_responsive");
	if (true == responsive) {
		var $th = $scope.find(".eael-data-table").find("th");
		var $tbody = $scope.find(".eael-data-table").find("tbody");

		$tbody.find("tr").each(function(i, item) {
			$(item)
				.find("td .td-content-wrapper")
				.each(function(index, item) {
					$(this).prepend('<div class="th-mobile-screen">' + $th.eq(index).html() + "</div>");
				});
		});
	}
};

var Data_Table_Click_Handler = function(panel, model, view) {
	if (event.target.dataset.event == "ea:table:export") {
		// export
		var table = view.el.querySelector("#eael-data-table-" + model.attributes.id);
		var rows = table.querySelectorAll("table tr");
		var csv = [];

		// generate csv
		for (var i = 0; i < rows.length; i++) {
			var row = [];
			var cols = rows[i].querySelectorAll("th, td");

			for (var j = 0; j < cols.length; j++) {
				row.push(JSON.stringify(cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim()));
			}

			csv.push(row.join(","));
		}

		// download
		var csv_file = new Blob([csv.join("\n")], { type: "text/csv" });
		var download_link = parent.document.createElement("a");

		download_link.classList.add("eael-data-table-download-" + model.attributes.id);
		download_link.download = "eael-data-table-" + model.attributes.id + ".csv";
		download_link.href = window.URL.createObjectURL(csv_file);
		download_link.style.display = "none";
		parent.document.body.appendChild(download_link);
		download_link.click();

		parent.document.querySelector(".eael-data-table-download-" + model.attributes.id).remove();
	}
};

var data_table_panel = function(panel, model, view) {
	var handler = Data_Table_Click_Handler.bind(this, panel, model, view);

	panel.el.addEventListener("click", handler);

	panel.currentPageView.on("destroy", function() {
		panel.el.removeEventListener("click", handler);
	});
};

jQuery(window).on("elementor/frontend/init", function() {
	// export table
	if (isEditMode) {
		elementor.hooks.addAction("panel/open_editor/widget/eael-data-table", data_table_panel);
	}

	elementorFrontend.hooks.addAction("frontend/element_ready/eael-data-table.default", dataTable);
});

var FacebookFeed = function($scope, $) {
    if (!isEditMode) {
        $facebook_gallery = $(".eael-facebook-feed", $scope).isotope({
            itemSelector: ".eael-facebook-feed-item",
            percentPosition: true,
            columnWidth: ".eael-facebook-feed-item"
        });

        $facebook_gallery.imagesLoaded().progress(function() {
            $facebook_gallery.isotope("layout");
        });
    }

    // ajax load more
    $(".eael-load-more-button", $scope).on("click", function(e) {
        e.preventDefault();

        $this = $(this);
        $settings = $this.attr("data-settings");
        $page = $this.attr("data-page");

        // update load moer button
        $this.addClass("button--loading");
        $("span", $this).html("Loading...");

        $.ajax({
            url: localize.ajaxurl,
            type: "post",
            data: {
                action: "facebook_feed_load_more",
                security: localize.nonce,
                settings: $settings,
                page: $page
            },
            success: function(response) {
                $html = $(response.html);

                // append items
                $facebook_gallery = $(".eael-facebook-feed", $scope).isotope();
                $(".eael-facebook-feed", $scope).append($html);
                $facebook_gallery.isotope("appended", $html);
                $facebook_gallery.imagesLoaded().progress(function() {
                    $facebook_gallery.isotope("layout");
                });

                // update load more button
                if (response.num_pages > $page) {
                    $this.attr("data-page", parseInt($page) + 1);
                    $this.removeClass("button--loading");
                    $("span", $this).html("Load more");
                } else {
                    $this.remove();
                }
            },
            error: function() {}
        });
    });
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-facebook-feed.default",
        FacebookFeed
    );
});

var FancyText = function($scope, $) {
    var $fancyText = $scope.find(".eael-fancy-text-container").eq(0),
        $id =
            $fancyText.data("fancy-text-id") !== undefined
                ? $fancyText.data("fancy-text-id")
                : "",
        $fancy_text =
            $fancyText.data("fancy-text") !== undefined
                ? $fancyText.data("fancy-text")
                : "",
        $transition_type =
            $fancyText.data("fancy-text-transition-type") !== undefined
                ? $fancyText.data("fancy-text-transition-type")
                : "",
        $fancy_text_speed =
            $fancyText.data("fancy-text-speed") !== undefined
                ? $fancyText.data("fancy-text-speed")
                : "",
        $fancy_text_delay =
            $fancyText.data("fancy-text-delay") !== undefined
                ? $fancyText.data("fancy-text-delay")
                : "",
        $fancy_text_cursor =
            $fancyText.data("fancy-text-cursor") === 'yes' ? true : false,
        $fancy_text_loop =
            $fancyText.data("fancy-text-loop") !== undefined
                ? $fancyText.data("fancy-text-loop") == "yes"
                    ? true
                    : false
                : false;
    $fancy_text = $fancy_text.split("|");

    if ($transition_type == "typing") {
        $("#eael-fancy-text-" + $id).typed({
            strings: $fancy_text,
            typeSpeed: $fancy_text_speed,
            backSpeed: 0,
            startDelay: 300,
            backDelay: $fancy_text_delay,
            showCursor: $fancy_text_cursor,
            loop: $fancy_text_loop
        });
    }

    if ($transition_type != "typing") {
        $("#eael-fancy-text-" + $id).Morphext({
            animation: $transition_type,
            separator: ", ",
            speed: $fancy_text_delay,
            complete: function() {
                // Overrides default empty function
            }
        });
    }

    jQuery(window).on('load', function() {
        setTimeout(function() {
            $('.eael-fancy-text-strings', $scope).css('display', 'inline-block');
        }, 500);
    });

    if(isEditMode) {
        setTimeout(function() {
            $('.eael-fancy-text-strings', $scope).css('display', 'inline-block');
        }, 800);
    }
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-fancy-text.default",
        FancyText
    );
});

var filterableGalleryHandler = function($scope, $) {

    var filterControls = $scope.find('.fg-layout-3-filter-controls').eq(0),
        filterTrigger = $scope.find('#fg-filter-trigger'),
        form = $scope.find('.fg-layout-3-search-box'),
        input = $scope.find('#fg-search-box-input'),
        searchRegex, buttonFilter, timer;

    if(form.length) {
        form.on('submit', function(e) {
            e.preventDefault();
        });
    }

    filterTrigger.on('click', function() {
        filterControls.toggleClass('open-filters');
    }).blur(function() {
        filterControls.toggleClass('open-filters');
    });

    if (!isEditMode) {
        var $gallery = $(".eael-filter-gallery-container", $scope),
            $settings = $gallery.data("settings"),
            $gallery_items = $gallery.data("gallery-items"),
            $layout_mode =
                $settings.grid_style == "masonry" ? "masonry" : "fitRows",
            $gallery_enabled =
                $settings.gallery_enabled == "yes" ? true : false;

        // init isotope
        var layoutMode = $('.eael-filter-gallery-wrapper').data('layout-mode');
        var $isotope_gallery = $gallery.isotope({
            itemSelector: ".eael-filterable-gallery-item-wrap",
            layoutMode: $layout_mode,
            percentPosition: true,
            stagger: 30,
            transitionDuration: $settings.duration + "ms",
            filter: function() {
                var $this = $(this);
                var $result = searchRegex ? $this.text().match( searchRegex ) : true;
                if(buttonFilter == undefined) {
                    if(layoutMode != 'layout_3') {
                        buttonFilter = $scope.find('.eael-filter-gallery-control ul li').first().data('filter');
                    }else {
                        buttonFilter = $scope.find('.fg-layout-3-filter-controls li').first().data('filter');
                    }
                }
                var buttonResult = buttonFilter ? $this.is( buttonFilter ) : true;
                return $result && buttonResult;
            }
        });


        // filter
        $scope.on("click", ".control", function() {

            var $this = $(this);
            buttonFilter = $( this ).attr('data-filter');

            if($scope.find('#fg-filter-trigger > span')) {
                $scope.find('#fg-filter-trigger > span').text($this.text());
            }

            $this.siblings().removeClass("active");
            $this.addClass("active");

            $isotope_gallery.isotope();
        });



        //quick search
        input.on('input', function() {
            var $this = $(this);

            clearTimeout(timer);
            timer = setTimeout(function() {
                searchRegex = new RegExp($this.val(), 'gi');
                $isotope_gallery.isotope();
            }, 600);

        });

        // layout gal, while images are loading
        $isotope_gallery.imagesLoaded().progress(function() {
            $isotope_gallery.isotope("layout");
        });

        // layout gal, on click tabs
        $isotope_gallery.on("arrangeComplete", function() {
            $isotope_gallery.isotope("layout");
        });

        // layout gal, after window loaded
        $(window).on("load", function() {
            $isotope_gallery.isotope("layout");
        });

        

        // popup
        $(".eael-magnific-link", $scope).magnificPopup({
            type: "image",
            gallery: {
                enabled: $gallery_enabled
            },
            callbacks: {
                close: function() {
                    $("#elementor-lightbox").hide();
                }
            }
        });

        $($scope).magnificPopup({
            delegate: ".eael-magnific-video-link",
            type: "iframe",
            callbacks: {
                close: function() {
                    $("#elementor-lightbox").hide();
                }
            }
        });

        // Load more button
        $scope.on("click", ".eael-gallery-load-more", function(e) {
            e.preventDefault();

            var $this = $(this),
                $init_show = $(
                    ".eael-filter-gallery-container",
                    $scope
                ).children(".eael-filterable-gallery-item-wrap").length,
                $total_items = $gallery.data("total-gallery-items"),
                $images_per_page = $gallery.data("images-per-page"),
                $nomore_text = $gallery.data("nomore-item-text"),
                $items = [];

            if ($init_show == $total_items) {
                $this.html(
                    '<div class="no-more-items-text">' + $nomore_text + "</div>"
                );
                setTimeout(function() {
                    $this.fadeOut("slow");
                }, 600);
            }

            // new items html
            for (var i = $init_show; i < $init_show + $images_per_page; i++) {
                $items.push($($gallery_items[i])[0]);
            }

            // append items
            $gallery.append($items);
            $isotope_gallery.isotope("appended", $items);
            $isotope_gallery.imagesLoaded().progress(function() {
                $isotope_gallery.isotope("layout");
            });

            // reinit magnificPopup
            $(".eael-magnific-link", $scope).magnificPopup({
                type: "image",
                gallery: {
                    enabled: $gallery_enabled
                },
                callbacks: {
                    close: function() {
                        $("#elementor-lightbox").hide();
                    }
                }
            });
        });
    }
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-filterable-gallery.default",
        filterableGalleryHandler
    );
});

(function($) {
    window.isEditMode = false;

    $(window).on("elementor/frontend/init", function() {
        window.isEditMode = elementorFrontend.isEditMode();
    });
})(jQuery);

var ImageAccordion = function($scope, $) {
    var $imageAccordion = $scope.find(".eael-img-accordion").eq(0),
        $id =
            $imageAccordion.data("img-accordion-id") !== undefined
                ? $imageAccordion.data("img-accordion-id")
                : "",
        $type =
            $imageAccordion.data("img-accordion-type") !== undefined
                ? $imageAccordion.data("img-accordion-type")
                : "";

    if ("on-click" === $type) {
        $("#eael-img-accordion-" + $id + " a").on("click", function(e) {
            if ($(this).hasClass("overlay-active") == false) {
                e.preventDefault();
            }

            $("#eael-img-accordion-" + $id + " a").css("flex", "1");
            $(this)
                .find(".overlay")
                .parent("a")
                .addClass("overlay-active");
            $("#eael-img-accordion-" + $id + " a")
                .find(".overlay-inner")
                .removeClass("overlay-inner-show");
            $(this)
                .find(".overlay-inner")
                .addClass("overlay-inner-show");
            $(this).css("flex", "3");
        });
        $("#eael-img-accordion-" + $id + " a").on("blur", function(e) {
            $("#eael-img-accordion-" + $id + " a").css("flex", "1");
            $("#eael-img-accordion-" + $id + " a")
                .find(".overlay-inner")
                .removeClass("overlay-inner-show");
            $(this)
                .find(".overlay")
                .parent("a")
                .removeClass("overlay-active");
        });
    }
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-image-accordion.default",
        ImageAccordion
    );
});

var PostGrid = function($scope, $) {
    var $gallery = $(".eael-post-appender", $scope).isotope({
        itemSelector: ".eael-grid-post",
        masonry: {
            columnWidth: ".eael-post-grid-column",
            percentPosition: true
        }
    });

    // layout gal, while images are loading
    $gallery.imagesLoaded().progress(function() {
        $gallery.isotope("layout");
    });
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-post-grid.default",
        PostGrid
    );
});

var PricingTooltip = function($scope, $) {
    if ($.fn.tooltipster) {
        var $tooltip = $scope.find(".tooltip"),
            i;

        for (i = 0; i < $tooltip.length; i++) {
            var $currentTooltip = $("#" + $($tooltip[i]).attr("id")),
                $tooltipSide =
                    $currentTooltip.data("side") !== undefined
                        ? $currentTooltip.data("side")
                        : false,
                $tooltipTrigger =
                    $currentTooltip.data("trigger") !== undefined
                        ? $currentTooltip.data("trigger")
                        : "hover",
                $animation =
                    $currentTooltip.data("animation") !== undefined
                        ? $currentTooltip.data("animation")
                        : "fade",
                $anim_duration =
                    $currentTooltip.data("animation_duration") !== undefined
                        ? $currentTooltip.data("animation_duration")
                        : 300,
                $theme =
                    $currentTooltip.data("theme") !== undefined
                        ? $currentTooltip.data("theme")
                        : "default",
                $arrow = "yes" == $currentTooltip.data("arrow") ? true : false;

            $currentTooltip.tooltipster({
                animation: $animation,
                trigger: $tooltipTrigger,
                side: $tooltipSide,
                delay: $anim_duration,
                arrow: $arrow,
                theme: "tooltipster-" + $theme
            });
        }
    }
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-pricing-table.default",
        PricingTooltip
    );
});

var ProgressBar = function($scope, $) {
    $(".eael-progressbar", $scope).eaelProgressBar();
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-progress-bar.default",
        ProgressBar
    );
});

jQuery(document).ready(function() {
    // scroll func
    jQuery(window).scroll(function() {
        var winScroll =
            document.body.scrollTop || document.documentElement.scrollTop;
        var height =
            document.documentElement.scrollHeight -
            document.documentElement.clientHeight;
        var scrolled = (winScroll / height) * 100;

        jQuery(".eael-reading-progress-fill").css({
            width: scrolled + "%"
        });
    });

    // live prev
    if (isEditMode) {
        elementor.settings.page.addChangeCallback(
            "eael_ext_reading_progress",
            function(newValue) {
                var $settings = elementor.settings.page.getSettings();

                if (newValue == "yes") {
                    if (jQuery(".eael-reading-progress-wrap").length == 0) {
                        jQuery("body").append(
                            '<div class="eael-reading-progress-wrap eael-reading-progress-wrap-local"><div class="eael-reading-progress eael-reading-progress-local eael-reading-progress-' +
                                $settings.settings
                                    .eael_ext_reading_progress_position +
                                '"><div class="eael-reading-progress-fill"></div></div><div class="eael-reading-progress eael-reading-progress-global eael-reading-progress-' +
                                $settings.settings
                                    .eael_ext_reading_progress_position +
                                '"><div class="eael-reading-progress-fill"></div></div></div>'
                        );
                    }

                    jQuery(".eael-reading-progress-wrap")
                        .addClass("eael-reading-progress-wrap-local")
                        .removeClass(
                            "eael-reading-progress-wrap-global eael-reading-progress-wrap-disabled"
                        );
                } else {
                    jQuery(".eael-reading-progress-wrap").removeClass(
                        "eael-reading-progress-wrap-local eael-reading-progress-wrap-global"
                    );

                    if (
                        $settings.settings
                            .eael_ext_reading_progress_has_global == true
                    ) {
                        jQuery(".eael-reading-progress-wrap").addClass(
                            "eael-reading-progress-wrap-global"
                        );
                    } else {
                        jQuery(".eael-reading-progress-wrap").addClass(
                            "eael-reading-progress-wrap-disabled"
                        );
                    }
                }
            }
        );

        elementor.settings.page.addChangeCallback(
            "eael_ext_reading_progress_position",
            function(newValue) {
                elementor.settings.page.setSettings(
                    "eael_ext_reading_progress_position",
                    newValue
                );
                jQuery(".eael-reading-progress")
                    .removeClass(
                        "eael-reading-progress-top eael-reading-progress-bottom"
                    )
                    .addClass("eael-reading-progress-" + newValue);
            }
        );
    }
});

var eaelsvPosition = '';
var eaelsvWidth = 0;
var eaelsvHeight = 0;
var eaelsvDomHeight = 0;
var videoIsActive = 0;
var eaelMakeItSticky = 0;
var scrollHeight = 0;

jQuery(window).on('elementor/frontend/init', function () {
    
    if (isEditMode) {
        elementor.hooks.addAction('panel/open_editor/widget/eael-sticky-video', function(panel, model, view) {
            var interval;

            model.attributes.settings.on('change:eaelsv_sticky_width', function() {
                clearTimeout(interval);

                interval = setTimeout(function() {
                    var height = Math.ceil(model.getSetting('eaelsv_sticky_width') / 1.78);

                    model.attributes.settings.attributes.eaelsv_sticky_height = height;
                    panel.el.querySelector('[data-setting="eaelsv_sticky_height"]').value = height;
                }, 250);
            });
            
            model.attributes.settings.on('change:eaelsv_sticky_height', function() {
                clearTimeout(interval);

                interval = setTimeout(function() {
                    var width = Math.ceil(model.getSetting('eaelsv_sticky_height') * 1.78);

                    model.attributes.settings.attributes.eaelsv_sticky_width = width;
                    panel.el.querySelector('[data-setting="eaelsv_sticky_width"]').value = width;
                }, 250);
            });
        });
    }

    elementorFrontend.hooks.addAction('frontend/element_ready/eael-sticky-video.default', function ($scope, $) {
        $('.eaelsv-sticky-player-close', $scope).hide();

        var element = $scope.find('.eael-sticky-video-player2');
        var sticky = '';
        var autoplay = '';
        var overlay = '';

        sticky = element.data('sticky');
        autoplay = element.data('autoplay');
        eaelsvPosition = element.data('position');
        eaelsvHeight = element.data('sheight');
        eaelsvWidth = element.data('swidth');
        overlay = element.data('overlay');
        scrollHeight = element.data('scroll_height');

        PositionStickyPlayer(eaelsvPosition, eaelsvHeight, eaelsvWidth);

        var playerAbc = new Plyr('#eaelsv-player-' + $scope.data('id'));

        // If element is Sticky video
        if (sticky === 'yes') {
            // If autoplay is enable
            if ('yes' === autoplay && overlay === 'no') {
                eaelsvDomHeight = GetDomElementHeight(element);
                element.attr('id', 'videobox');

                if (videoIsActive == 0) {
                    videoIsActive = 1;
                }
            }

            // When play event is cliked
            // Do the sticky process
            PlayerPlay(playerAbc, element);
        }

        // Overlay Operation Started
        if (overlay === 'yes') {
            var ovrlyElmnt = element.prev();

            $(ovrlyElmnt).on('click', function () {
                $(this).css('display', 'none');

                if (
                    $(this)
                        .next()
                        .data('autoplay') === 'yes'
                ) {
                    playerAbc.restart();
                    eaelsvDomHeight = GetDomElementHeight(this);
                    $(this)
                        .next()
                        .attr('id', 'videobox');
                    videoIsActive = 1;
                }
            });
        }
        playerAbc.on('pause', function (event) {
            if (videoIsActive == 1) {
                videoIsActive = 0;
            }
        });

        $('.eaelsv-sticky-player-close').on('click', function () {
            element.removeClass('out').addClass('in');
            $('.eael-sticky-video-player2').removeAttr('style');
            videoIsActive = 0;
        });

        element.parent().css('height', element.height() + 'px');

        $(window).resize(function() {
            element.parent().css('height', element.height() + 'px');
        });
    });

});

jQuery(window).scroll(function () {
    var scrollTop = jQuery(window).scrollTop();
    var scrollBottom = jQuery(document).height() - scrollTop;

    if (scrollBottom > jQuery(window).height() + 400) {
        if (scrollTop >= eaelsvDomHeight) {
            if (videoIsActive == 1) {
                jQuery('#videobox')
                    .find('.eaelsv-sticky-player-close')
                    .css('display', 'block');
                jQuery('#videobox')
                    .removeClass('in')
                    .addClass('out');
                PositionStickyPlayer(eaelsvPosition, eaelsvHeight, eaelsvWidth);
            }
        } else {
            jQuery('.eaelsv-sticky-player-close').hide();
            jQuery('#videobox')
                .removeClass('out')
                .addClass('in');
            jQuery('.eael-sticky-video-player2').removeAttr('style');
        }
    }
});

function GetDomElementHeight(elem) {
    var contentHeight = jQuery(elem).parent().height();
    var expHeight = ((scrollHeight * contentHeight) / 100);
    var hght =
        jQuery(elem)
            .parent()
            .offset().top + expHeight;

    return hght;
}

function PositionStickyPlayer(p, h, w) {
    if (p == 'top-left') {
        jQuery('.eael-sticky-video-player2.out').css('top', '40px');
        jQuery('.eael-sticky-video-player2.out').css('left', '40px');
    }
    if (p == 'top-right') {
        jQuery('.eael-sticky-video-player2.out').css('top', '40px');
        jQuery('.eael-sticky-video-player2.out').css('right', '40px');
    }
    if (p == 'bottom-right') {
        jQuery('.eael-sticky-video-player2.out').css('bottom', '40px');
        jQuery('.eael-sticky-video-player2.out').css('right', '40px');
    }
    if (p == 'bottom-left') {
        jQuery('.eael-sticky-video-player2.out').css('bottom', '40px');
        jQuery('.eael-sticky-video-player2.out').css('left', '40px');
    }
    jQuery('.eael-sticky-video-player2.out').css('width', w + 'px');
    jQuery('.eael-sticky-video-player2.out').css('height', h + 'px');
}

function PlayerPlay(a, b) {
    a.on('play', function (event) {
        eaelsvDomHeight = GetDomElementHeight(b);
        jQuery('.eael-sticky-video-player2').removeAttr('id');
        jQuery('.eael-sticky-video-player2').removeClass('out');
        b.attr('id', 'videobox');

        videoIsActive = 1;
        eaelsvPosition = b.data('position');
        eaelsvHeight = b.data('sheight');
        eaelsvWidth = b.data('swidth');
    });
}

function RunStickyPlayer(elem) {
    var ovrplyer = new Plyr('#' + elem);
    ovrplyer.start();
}

var TwitterFeedHandler = function($scope, $) {
    if (!isEditMode) {
        $gutter = $(".eael-twitter-feed-masonry", $scope).data("gutter");
        $settings = {
            itemSelector: ".eael-twitter-feed-item",
            percentPosition: true,
            masonry: {
                columnWidth: ".eael-twitter-feed-item",
                gutter: $gutter
            }
        };

        // init isotope
        $twitter_feed_gallery = $(".eael-twitter-feed-masonry", $scope).isotope(
            $settings
        );

        // layout gal, while images are loading
        $twitter_feed_gallery.imagesLoaded().progress(function() {
            $twitter_feed_gallery.isotope("layout");
        });
    }
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-twitter-feed.default",
        TwitterFeedHandler
    );
});
