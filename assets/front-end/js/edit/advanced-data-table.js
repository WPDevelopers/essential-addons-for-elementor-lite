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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/edit/advanced-data-table.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/edit/advanced-data-table.js":
/*!********************************************!*\
  !*** ./src/js/edit/advanced-data-table.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _typeof(o) { \"@babel/helpers - typeof\"; return _typeof = \"function\" == typeof Symbol && \"symbol\" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && \"function\" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? \"symbol\" : typeof o; }, _typeof(o); }\nfunction _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError(\"Cannot call a class as a function\"); }\nfunction _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, \"value\" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }\nfunction _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, \"prototype\", { writable: !1 }), e; }\nfunction _toPropertyKey(t) { var i = _toPrimitive(t, \"string\"); return \"symbol\" == _typeof(i) ? i : i + \"\"; }\nfunction _toPrimitive(t, r) { if (\"object\" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || \"default\"); if (\"object\" != _typeof(i)) return i; throw new TypeError(\"@@toPrimitive must return a primitive value.\"); } return (\"string\" === r ? String : Number)(t); }\nvar advancedDataTableEdit = /*#__PURE__*/function () {\n  function advancedDataTableEdit() {\n    _classCallCheck(this, advancedDataTableEdit);\n    // class props\n    this.panel = null;\n    this.model = null;\n    this.view = null;\n    this.table = null;\n    this.tableInnerHTML = null;\n    this.timeout = null;\n    this.activeCell = null;\n    this.dragStartX = null;\n    this.dragStartWidth = null;\n    this.dragEl = null;\n    this.dragging = false;\n\n    // register hooks\n    eael.hooks.addFilter(\"advancedDataTable.getClassProps\", \"ea\", this.getClassProps.bind(this));\n    eael.hooks.addFilter(\"advancedDataTable.setClassProps\", \"ea\", this.setClassProps.bind(this));\n    eael.hooks.addFilter(\"advancedDataTable.parseHTML\", \"ea\", this.parseHTML);\n    eael.hooks.addAction(\"advancedDataTable.initEditor\", \"ea\", this.initEditor.bind(this));\n    eael.hooks.addAction(\"advancedDataTable.updateFromView\", \"ea\", this.updateFromView.bind(this));\n    eael.hooks.addAction(\"advancedDataTable.initInlineEdit\", \"ea\", this.initInlineEdit.bind(this));\n    eael.hooks.addAction(\"advancedDataTable.initPanelAction\", \"ea\", this.initPanelAction.bind(this));\n    eael.hooks.addAction(\"advancedDataTable.triggerTextChange\", \"ea\", this.triggerTextChange.bind(this));\n    elementor.hooks.addFilter(\"elements/widget/contextMenuGroups\", this.initContextMenu);\n    elementor.hooks.addAction(\"panel/open_editor/widget/eael-advanced-data-table\", this.initPanel.bind(this));\n  }\n\n  // update model from view\n  return _createClass(advancedDataTableEdit, [{\n    key: \"updateFromView\",\n    value: function updateFromView(view, value) {\n      var refresh = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;\n      var model = view.model;\n\n      // disable elementor remote server render\n      model.remoteRender = refresh;\n      if (elementor.config.version > \"2.7.6\") {\n        var container = view.getContainer();\n        var settings = view.getContainer().settings.attributes;\n        Object.keys(value).forEach(function (key) {\n          settings[key] = value[key];\n        });\n        parent.window.$e.run(\"document/elements/settings\", {\n          container: container,\n          settings: settings,\n          options: {\n            external: refresh\n          }\n        });\n      } else {\n        // update backbone model\n        Object.keys(value).forEach(function (key) {\n          model.setSetting(key, value[key]);\n        });\n      }\n\n      // enable elementor remote server render just after elementor throttle\n      // ignore multiple assign\n      this.timeout = setTimeout(function () {\n        model.remoteRender = true;\n      }, 1001);\n    }\n\n    // get class properties\n  }, {\n    key: \"getClassProps\",\n    value: function getClassProps() {\n      return {\n        view: this.view,\n        model: this.model,\n        table: this.table,\n        activeCell: this.activeCell\n      };\n    }\n\n    // get class properties\n  }, {\n    key: \"setClassProps\",\n    value: function setClassProps(props) {\n      var _this = this;\n      Object.keys(props).forEach(function (key) {\n        _this[key] = props[key];\n      });\n    }\n\n    // parse table html\n  }, {\n    key: \"parseHTML\",\n    value: function parseHTML(tableHTML) {\n      tableHTML.querySelectorAll(\"th, td\").forEach(function (el) {\n        if (el.querySelector(\".inline-editor\") !== null) {\n          el.innerHTML = decodeURI(el.dataset.quill || \"\");\n          delete el.dataset.quill;\n        }\n      });\n      return tableHTML;\n    }\n\n    // init editor\n  }, {\n    key: \"initEditor\",\n    value: function initEditor(cell) {\n      var _this2 = this;\n      // init value\n      cell.dataset.quill = encodeURI(cell.innerHTML);\n\n      // insert editor dom\n      cell.innerHTML = \"<div class=\\\"inline-editor\\\">\".concat(cell.innerHTML, \"</div>\");\n\n      // init quill\n      var quill = new Quill(cell.querySelector(\".inline-editor\"), {\n        theme: \"bubble\",\n        modules: {\n          toolbar: [\"bold\", \"italic\", \"underline\", \"strike\", \"link\", {\n            list: \"ordered\"\n          }, {\n            list: \"bullet\"\n          }]\n        }\n      });\n\n      // bind change\n      quill.on(\"text-change\", function (delta, oldDelta, source) {\n        clearTimeout(_this2.timeout);\n\n        // update data\n        cell.dataset.quill = encodeURI(quill.root.innerHTML);\n\n        // parse table html\n        var origTable = _this2.parseHTML(_this2.table.cloneNode(true));\n        _this2.tableInnerHTML = origTable.innerHTML;\n        // update table\n        _this2.updateFromView(_this2.view, {\n          ea_adv_data_table_static_html: origTable.innerHTML\n        });\n      });\n    }\n\n    // init inline editing features\n  }, {\n    key: \"initInlineEdit\",\n    value: function initInlineEdit() {\n      var _this3 = this;\n      var interval = setInterval(function () {\n        if (_this3.view.el.querySelector(\".ea-advanced-data-table\")) {\n          // init table\n          if (_this3.table !== _this3.view.el.querySelector(\".ea-advanced-data-table\")) {\n            _this3.table = _this3.view.el.querySelector(\".ea-advanced-data-table\");\n\n            // iniline editor\n            if (_this3.table.classList.contains(\"ea-advanced-data-table-static\")) {\n              _this3.table.querySelectorAll(\"th, td\").forEach(function (cell) {\n                _this3.initEditor(cell);\n              });\n            }\n\n            // mousedown\n            _this3.table.addEventListener(\"mousedown\", function (e) {\n              e.stopPropagation();\n              if (e.target.tagName.toLowerCase() === \"th\") {\n                _this3.dragging = true;\n                _this3.dragEl = e.target;\n                _this3.dragStartX = e.pageX;\n                _this3.dragStartWidth = e.target.offsetWidth;\n              }\n              if (e.target.tagName.toLowerCase() === \"th\" || e.target.tagName.toLowerCase() === \"td\") {\n                _this3.activeCell = e.target;\n              } else if (e.target.parentNode.tagName.toLowerCase() === \"th\" || e.target.parentNode.tagName.toLowerCase() === \"td\") {\n                _this3.activeCell = e.target.parentNode;\n              } else if (e.target.parentNode.parentNode.tagName.toLowerCase() === \"th\" || e.target.parentNode.parentNode.tagName.toLowerCase() === \"td\") {\n                _this3.activeCell = e.target.parentNode.parentNode;\n              } else if (e.target.parentNode.parentNode.parentNode.tagName.toLowerCase() === \"th\" || e.target.parentNode.parentNode.parentNode.tagName.toLowerCase() === \"td\") {\n                _this3.activeCell = e.target.parentNode.parentNode.parentNode;\n              }\n            });\n\n            // mousemove\n            _this3.table.addEventListener(\"mousemove\", function (e) {\n              if (_this3.dragging) {\n                _this3.dragEl.style.width = \"\".concat(_this3.dragStartWidth + (event.pageX - _this3.dragStartX), \"px\");\n              }\n            });\n\n            // mouseup\n            _this3.table.addEventListener(\"mouseup\", function (e) {\n              if (_this3.dragging) {\n                _this3.dragging = false;\n                clearTimeout(_this3.timeout);\n                if (_this3.table.classList.contains(\"ea-advanced-data-table-static\")) {\n                  // parse table html\n                  var origTable = _this3.parseHTML(_this3.table.cloneNode(true));\n\n                  // update table\n                  _this3.updateFromView(_this3.view, {\n                    ea_adv_data_table_static_html: origTable.innerHTML\n                  });\n                } else {\n                  // th width store\n                  var widths = [];\n\n                  // collect width of th\n                  _this3.table.querySelectorAll(\"th\").forEach(function (el, index) {\n                    widths[index] = el.style.width;\n                  });\n\n                  // update table\n                  _this3.updateFromView(_this3.view, {\n                    ea_adv_data_table_dynamic_th_width: widths\n                  });\n                }\n              }\n            });\n\n            // clear style\n            _this3.table.addEventListener(\"dblclick\", function (e) {\n              if (e.target.tagName.toLowerCase() === \"th\") {\n                e.stopPropagation();\n                clearTimeout(_this3.timeout);\n                if (_this3.table.classList.contains(\"ea-advanced-data-table-static\")) {\n                  // parse table html\n                  var origTable = _this3.parseHTML(_this3.table.cloneNode(true));\n\n                  // update table\n                  _this3.updateFromView(_this3.view, {\n                    ea_adv_data_table_static_html: origTable.innerHTML\n                  });\n                } else {\n                  // th width store\n                  var widths = [];\n\n                  // collect width of th\n                  _this3.table.querySelectorAll(\"th\").forEach(function (el, index) {\n                    widths[index] = el.style.width;\n                  });\n\n                  // update table\n                  _this3.updateFromView(_this3.view, {\n                    ea_adv_data_table_dynamic_th_width: widths\n                  });\n                }\n              }\n            });\n          }\n          clearInterval(interval);\n        }\n      }, 500);\n    }\n\n    // panel action\n  }, {\n    key: \"initPanelAction\",\n    value: function initPanelAction() {\n      var _this4 = this;\n      this.panel.content.el.onclick = function (event) {\n        if (event.target.dataset.event == \"ea:advTable:export\") {\n          // export\n          var rows = _this4.table.querySelectorAll(\"table tr\");\n          var csv = [];\n\n          // generate csv\n          for (var i = 0; i < rows.length; i++) {\n            var row = [];\n            var cols = rows[i].querySelectorAll(\"th, td\");\n            if (_this4.table.classList.contains(\"ea-advanced-data-table-static\")) {\n              for (var j = 0; j < cols.length; j++) {\n                var encodedText = decodeURI(cols[j].dataset.quill);\n                var modifiedString = encodedText.replace(/\"/g, '\"\"');\n                modifiedString = \"\\\"\".concat(modifiedString, \"\\\"\");\n                row.push(modifiedString);\n              }\n            } else {\n              for (var _j = 0; _j < cols.length; _j++) {\n                row.push(JSON.stringify(cols[_j].innerHTML.replace(/,\"\"\"([^\"]+)\"\"\",/g, ',\"$1\",').trim()));\n              }\n            }\n            csv.push(row.join(\",\"));\n          }\n\n          // download\n          var csv_file = new Blob([csv.join(\"\\n\")], {\n            type: \"text/csv\"\n          });\n          var downloadLink = parent.document.createElement(\"a\");\n          downloadLink.classList.add(\"ea-adv-data-table-download-\".concat(_this4.model.attributes.id));\n          downloadLink.download = \"ea-adv-data-table-\".concat(_this4.model.attributes.id, \".csv\");\n          downloadLink.href = window.URL.createObjectURL(csv_file);\n          downloadLink.style.display = \"none\";\n          parent.document.body.appendChild(downloadLink);\n          downloadLink.click();\n          parent.document.querySelector(\".ea-adv-data-table-download-\".concat(_this4.model.attributes.id)).remove();\n        } else if (event.target.dataset.event == \"ea:advTable:import\") {\n          // import\n          var textarea = _this4.panel.content.el.querySelector(\".ea_adv_table_csv_string\");\n          var enableHeader = _this4.panel.content.el.querySelector(\".ea_adv_table_csv_string_table\").checked;\n          var csletr = textarea.value.split(\"\\n\");\n          var header = \"\";\n          var body = \"\";\n          if (textarea.value.length > 0) {\n            body += \"<tbody>\";\n            csletr.forEach(function (row, index) {\n              var result = [];\n              var field = '';\n              var inQuotes = false;\n              var i = 0;\n              while (i < row.length) {\n                var _char = row[i];\n                if (_char === '\"') {\n                  if (inQuotes && row[i + 1] === '\"') {\n                    //Handle escaped double quote\n                    field += '\"';\n                    i++;\n                  } else {\n                    inQuotes = !inQuotes; //Toggle inQuotes\n                  }\n                } else if (_char === ',' && !inQuotes) {\n                  //End of field\n                  result.push(field);\n                  field = '';\n                } else {\n                  field += _char; //Regular character\n                }\n                i++;\n              }\n              result.push(field);\n\n              //Generate HTML table\n              if (result.length > 0) {\n                if (enableHeader && index == 0) {\n                  header += \"<thead><tr>\";\n                  result.forEach(function (col) {\n                    header += \"<th>\".concat(col, \"</th>\");\n                  });\n                  header += \"</tr></thead>\";\n                } else {\n                  body += \"<tr>\";\n                  result.forEach(function (col) {\n                    body += \"<td>\".concat(col, \"</td>\");\n                  });\n                  body += \"</tr>\";\n                }\n              }\n            });\n            body += \"</tbody>\";\n            if (header.length > 0 || body.length > 0) {\n              _this4.tableInnerHTML = header + body;\n              _this4.updateFromView(_this4.view, {\n                ea_adv_data_table_static_html: header + body\n              }, true);\n\n              // init inline edit\n              var interval = setInterval(function () {\n                if (_this4.view.el.querySelector(\".ea-advanced-data-table\").innerHTML == header + body) {\n                  clearInterval(interval);\n                  eael.hooks.doAction(\"advancedDataTable.initInlineEdit\");\n                }\n              }, 500);\n            }\n          }\n          textarea.value = \"\";\n        }\n        eael.hooks.doAction(\"advancedDataTable.panelAction\", _this4.panel, _this4.model, _this4.view, event);\n      };\n    }\n\n    // init panel\n  }, {\n    key: \"initPanel\",\n    value: function initPanel(panel, model, view) {\n      var _this5 = this;\n      this.panel = panel;\n      this.model = model;\n      this.view = view;\n      var elClass = \".ea-advanced-data-table-\".concat(this.view.container.args.id);\n      var eaTable = this.view.el.querySelector(\".ea-advanced-data-table\" + elClass);\n      // init inline edit\n      eael.hooks.doAction(\"advancedDataTable.initInlineEdit\");\n\n      // init panel action\n      eael.hooks.doAction(\"advancedDataTable.initPanelAction\");\n\n      // after panel init hook\n      eael.hooks.doAction(\"advancedDataTable.afterInitPanel\", panel, model, view);\n      model.once(\"editor:close\", function () {\n        if (!eaTable) {\n          return false;\n        }\n        // parse table html\n        var origTable = _this5.parseHTML(eaTable.cloneNode(true));\n        _this5.tableInnerHTML = origTable.innerHTML;\n\n        // update table\n        // this.updateFromView(\n        // \tthis.view,\n        // \t{\n        // \t\tea_adv_data_table_static_html: this.tableInnerHTML,\n        // \t},\n        // \ttrue\n        // );\n      });\n    }\n\n    // context menu\n  }, {\n    key: \"initContextMenu\",\n    value: function initContextMenu(groups, element) {\n      if (element.options.model.attributes.widgetType == \"eael-advanced-data-table\" && element.options.model.attributes.settings.attributes.ea_adv_data_table_source == \"static\") {\n        groups.push({\n          name: \"ea_advanced_data_table\",\n          actions: [{\n            name: \"add_row_above\",\n            title: \"Add Row Above\",\n            callback: function callback() {\n              var _eael$hooks$applyFilt = eael.hooks.applyFilters(\"advancedDataTable.getClassProps\"),\n                view = _eael$hooks$applyFilt.view,\n                table = _eael$hooks$applyFilt.table,\n                activeCell = _eael$hooks$applyFilt.activeCell;\n              // remove blank tr if any\n              jQuery(table).find('tr:empty').each(function () {\n                if (jQuery(this).find('td').length == 0) {\n                  this.remove();\n                }\n              });\n              if (activeCell !== null && activeCell.tagName.toLowerCase() != \"th\" && activeCell.parentNode.rowIndex) {\n                var index = activeCell.parentNode.rowIndex;\n                var row = table.insertRow(index);\n                // insert cells in row\n                for (var i = 0; i < table.rows[0].cells.length; i++) {\n                  var cell = row.insertCell(i);\n\n                  // init inline editor\n                  eael.hooks.doAction(\"advancedDataTable.initEditor\", cell);\n                }\n\n                // remove active cell\n                eael.hooks.applyFilters(\"advancedDataTable.setClassProps\", {\n                  activeCell: null\n                });\n\n                // parse table html\n                var origTable = eael.hooks.applyFilters(\"advancedDataTable.parseHTML\", table.cloneNode(true));\n\n                // update model\n                eael.hooks.doAction(\"advancedDataTable.updateFromView\", view, {\n                  ea_adv_data_table_static_html: origTable.innerHTML\n                });\n\n                // trigger text-change event\n                eael.hooks.doAction(\"advancedDataTable.triggerTextChange\", table);\n              }\n            }\n          }, {\n            name: \"add_row_below\",\n            title: \"Add Row Below\",\n            callback: function callback() {\n              var _eael$hooks$applyFilt2 = eael.hooks.applyFilters(\"advancedDataTable.getClassProps\"),\n                view = _eael$hooks$applyFilt2.view,\n                table = _eael$hooks$applyFilt2.table,\n                activeCell = _eael$hooks$applyFilt2.activeCell;\n              if (activeCell !== null) {\n                var index = activeCell.parentNode.rowIndex + 1;\n                var row = table.insertRow(index);\n                for (var i = 0; i < table.rows[0].cells.length; i++) {\n                  var cell = row.insertCell(i);\n\n                  // init inline editor\n                  eael.hooks.doAction(\"advancedDataTable.initEditor\", cell);\n                }\n\n                // remove active cell\n                eael.hooks.applyFilters(\"advancedDataTable.setClassProps\", {\n                  activeCell: null\n                });\n\n                // parse table html\n                var origTable = eael.hooks.applyFilters(\"advancedDataTable.parseHTML\", table.cloneNode(true));\n\n                // update model\n                eael.hooks.doAction(\"advancedDataTable.updateFromView\", view, {\n                  ea_adv_data_table_static_html: origTable.innerHTML\n                });\n\n                // trigger text-change event\n                eael.hooks.doAction(\"advancedDataTable.triggerTextChange\", table);\n              }\n            }\n          }, {\n            name: \"add_column_left\",\n            title: \"Add Column Left\",\n            callback: function callback() {\n              var _eael$hooks$applyFilt3 = eael.hooks.applyFilters(\"advancedDataTable.getClassProps\"),\n                view = _eael$hooks$applyFilt3.view,\n                table = _eael$hooks$applyFilt3.table,\n                activeCell = _eael$hooks$applyFilt3.activeCell;\n              if (activeCell !== null) {\n                var index = activeCell.cellIndex;\n                for (var i = 0; i < table.rows.length; i++) {\n                  if (table.rows[i].cells[0].tagName.toLowerCase() == \"th\") {\n                    var cell = table.rows[i].insertBefore(document.createElement(\"th\"), table.rows[i].cells[index]);\n\n                    // init inline editor\n                    eael.hooks.doAction(\"advancedDataTable.initEditor\", cell);\n                  } else {\n                    var _cell = table.rows[i].insertCell(index);\n\n                    // init inline editor\n                    eael.hooks.doAction(\"advancedDataTable.initEditor\", _cell);\n                  }\n                }\n\n                // remove active cell\n                eael.hooks.applyFilters(\"advancedDataTable.setClassProps\", {\n                  activeCell: null\n                });\n\n                // parse table html\n                var origTable = eael.hooks.applyFilters(\"advancedDataTable.parseHTML\", table.cloneNode(true));\n\n                // update model\n                eael.hooks.doAction(\"advancedDataTable.updateFromView\", view, {\n                  ea_adv_data_table_static_html: origTable.innerHTML\n                });\n\n                // trigger text-change event\n                eael.hooks.doAction(\"advancedDataTable.triggerTextChange\", table);\n              }\n            }\n          }, {\n            name: \"add_column_right\",\n            title: \"Add Column Right\",\n            callback: function callback() {\n              var _eael$hooks$applyFilt4 = eael.hooks.applyFilters(\"advancedDataTable.getClassProps\"),\n                view = _eael$hooks$applyFilt4.view,\n                table = _eael$hooks$applyFilt4.table,\n                activeCell = _eael$hooks$applyFilt4.activeCell;\n              if (activeCell !== null) {\n                var index = activeCell.cellIndex + 1;\n                for (var i = 0; i < table.rows.length; i++) {\n                  if (table.rows[i].cells[0].tagName.toLowerCase() == \"th\") {\n                    var cell = table.rows[i].insertBefore(document.createElement(\"th\"), table.rows[i].cells[index]);\n\n                    // init inline editor\n                    eael.hooks.doAction(\"advancedDataTable.initEditor\", cell);\n                  } else {\n                    var _cell2 = table.rows[i].insertCell(index);\n\n                    // init inline editor\n                    eael.hooks.doAction(\"advancedDataTable.initEditor\", _cell2);\n                  }\n                }\n\n                // remove active cell\n                eael.hooks.applyFilters(\"advancedDataTable.setClassProps\", {\n                  activeCell: null\n                });\n\n                // parse table html\n                var origTable = eael.hooks.applyFilters(\"advancedDataTable.parseHTML\", table.cloneNode(true));\n\n                // update model\n                eael.hooks.doAction(\"advancedDataTable.updateFromView\", view, {\n                  ea_adv_data_table_static_html: origTable.innerHTML\n                });\n\n                // trigger text-change event\n                eael.hooks.doAction(\"advancedDataTable.triggerTextChange\", table);\n              }\n            }\n          }, {\n            name: \"delete_row\",\n            title: \"Delete Row\",\n            callback: function callback() {\n              var _eael$hooks$applyFilt5 = eael.hooks.applyFilters(\"advancedDataTable.getClassProps\"),\n                view = _eael$hooks$applyFilt5.view,\n                table = _eael$hooks$applyFilt5.table,\n                activeCell = _eael$hooks$applyFilt5.activeCell;\n              if (activeCell !== null) {\n                var index = activeCell.parentNode.rowIndex;\n\n                // delete row\n                table.deleteRow(index);\n\n                // remove active cell\n                eael.hooks.applyFilters(\"advancedDataTable.setClassProps\", {\n                  activeCell: null\n                });\n\n                // parse table html\n                var origTable = eael.hooks.applyFilters(\"advancedDataTable.parseHTML\", table.cloneNode(true));\n\n                // update model\n                eael.hooks.doAction(\"advancedDataTable.updateFromView\", view, {\n                  ea_adv_data_table_static_html: origTable.innerHTML\n                });\n\n                // trigger text-change event\n                eael.hooks.doAction(\"advancedDataTable.triggerTextChange\", table);\n              }\n            }\n          }, {\n            name: \"delete_column\",\n            title: \"Delete Column\",\n            callback: function callback() {\n              var _eael$hooks$applyFilt6 = eael.hooks.applyFilters(\"advancedDataTable.getClassProps\"),\n                view = _eael$hooks$applyFilt6.view,\n                table = _eael$hooks$applyFilt6.table,\n                activeCell = _eael$hooks$applyFilt6.activeCell;\n              if (activeCell !== null) {\n                var index = activeCell.cellIndex;\n\n                // delete columns\n                for (var i = 0; i < table.rows.length; i++) {\n                  table.rows[i].deleteCell(index);\n                }\n\n                // remove active cell\n                eael.hooks.applyFilters(\"advancedDataTable.setClassProps\", {\n                  activeCell: null\n                });\n\n                // parse table html\n                var origTable = eael.hooks.applyFilters(\"advancedDataTable.parseHTML\", table.cloneNode(true));\n\n                // update model\n                eael.hooks.doAction(\"advancedDataTable.updateFromView\", view, {\n                  ea_adv_data_table_static_html: origTable.innerHTML\n                });\n\n                // trigger text-change event\n                eael.hooks.doAction(\"advancedDataTable.triggerTextChange\", table);\n              }\n            }\n          }]\n        });\n      }\n      return groups;\n    }\n  }, {\n    key: \"triggerTextChange\",\n    value: function triggerTextChange(table) {\n      if (table.classList.contains(\"ea-advanced-data-table-static\")) {\n        var cellSelector = jQuery('thead tr:first-child th:first-child .ql-editor p', table),\n          cellSelector = cellSelector.length ? cellSelector : jQuery('tbody tr:first-child td:first-child .ql-editor p', table),\n          cellData = cellSelector.html();\n        cellSelector.html(cellData + ' ');\n        setTimeout(function () {\n          cellSelector.html(cellData);\n        }, 1100);\n      }\n    }\n  }]);\n}();\neael.hooks.addAction(\"editMode.init\", \"ea\", function () {\n  new advancedDataTableEdit();\n});\n\n//# sourceURL=webpack:///./src/js/edit/advanced-data-table.js?");

/***/ })

/******/ });