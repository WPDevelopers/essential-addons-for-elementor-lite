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
		var attr = "readonly";

		// add edit class
		table.classList.add("ea-advanced-data-table-editable");

		if (table.classList.contains("ea-advanced-data-table-static")) {
			attr = "";

			// insert editable area
			table.querySelectorAll("th, td").forEach(function(el) {
				var value = el.innerHTML;

				if (value.indexOf('<textarea rows="1">') !== 0) {
					el.innerHTML = '<textarea rows="1" ' + attr + ">" + value + "</textarea>";
				}
			});
		}

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
				var offset = table.rows[0].parentNode.tagName.toLowerCase() == "thead" ? 1 : 0;

				if (table.rows.length > 1) {
					if (input.length > 0) {
						if (hasSort) {
							table.classList.add("ea-advanced-data-table-unsortable");
						}

						if (pagination && pagination.innerHTML.length > 0) {
							pagination.style.display = "none";
						}

						for (var i = offset; i < table.rows.length; i++) {
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
			var startIndex = table.rows[0].parentNode.tagName.toLowerCase() == "thead" ? 1 : 0;
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
					offset = table.rows[0].parentNode.tagName.toLowerCase() == "thead" ? 1 : 0;
					startIndex = (currentPage - 1) * table.dataset.itemsPerPage + offset;
					endIndex = currentPage * table.dataset.itemsPerPage;

					pagination.querySelectorAll(".ea-advanced-data-table-pagination-current").forEach(function(el) {
						el.classList.remove("ea-advanced-data-table-pagination-current");
					});

					pagination.querySelectorAll('[data-page="' + currentPage + '"]').forEach(function(el) {
						el.classList.add("ea-advanced-data-table-pagination-current");
					});

					for (var i = offset; i <= table.rows.length - 1; i++) {
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

			if (table.classList.contains("ea-advanced-data-table-static")) {
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
			} else {
				for (var j = 0; j < cols.length; j++) {
					row.push(JSON.stringify(cols[j].innerHTML.replace(/(\r\n|\n|\r)/gm, " ").trim()));
				}
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
				cols = row.match(/("(?:[^"\\]|\\.)*"|[^","]+)/gm);

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
	} else if (event.target.dataset.event == "ea:advTable:connect") {
		var button = event.target;
		button.innerHTML = "Connecting";

		jQuery.ajax({
			url: localize.ajaxurl,
			type: "post",
			data: {
				action: "connect_remote_db",
				security: localize.nonce,
				host: model.attributes.settings.attributes.ea_adv_data_table_source_remote_host,
				username: model.attributes.settings.attributes.ea_adv_data_table_source_remote_username,
				password: model.attributes.settings.attributes.ea_adv_data_table_source_remote_password,
				database: model.attributes.settings.attributes.ea_adv_data_table_source_remote_database
			},
			success: function(response) {
				if (response.connected == true) {
					button.innerHTML = "Connected";

					Advanced_Data_Table_Update_View(view, true, {
						ea_adv_data_table_source_remote_connected: true,
						ea_adv_data_table_source_remote_tables: response.tables
					});

					// reload panel
					panel.content.el.querySelector(".elementor-section-title").click();
					panel.content.el.querySelector(".elementor-section-title").click();

					var select = panel.el.querySelector('[data-setting="ea_adv_data_table_source_remote_table"]');
					select.length = 0;
					response.tables.forEach(function(opt, index) {
						select[index] = new Option(opt, opt);
					});
				} else {
					button.innerHTML = "Failed";
				}
			},
			error: function() {
				button.innerHTML = "Failed";
			}
		});

		setTimeout(function() {
			button.innerHTML = "Connect";
		}, 2000);
	} else if (event.target.dataset.event == "ea:advTable:disconnect") {
		Advanced_Data_Table_Update_View(view, true, {
			ea_adv_data_table_source_remote_connected: false,
			ea_adv_data_table_source_remote_tables: []
		});

		// reload panel
		panel.content.el.querySelector(".elementor-section-title").click();
		panel.content.el.querySelector(".elementor-section-title").click();
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
					clearTimeout(advanced_data_table_timeout);

					if (e.target.tagName.toLowerCase() === "th") {
						if (table.classList.contains("ea-advanced-data-table-static")) {
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
						} else {
							var widths = [];

							// collect width of th
							table.querySelectorAll("th").forEach(function(el, index) {
								widths[index] = el.style.width;
							});

							// update table
							Advanced_Data_Table_Update_View(view, false, {
								ea_adv_data_table_dynamic_th_width: widths
							});
						}
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

	// fill remote db list
	var initRemoteTables = function() {
		setTimeout(function() {
			var select = panel.el.querySelector('[data-setting="ea_adv_data_table_source_remote_table"]');

			if (select != null && select.length == 0) {
				model.attributes.settings.attributes.ea_adv_data_table_source_remote_tables.forEach(function(opt, index) {
					select[index] = new Option(opt, opt, false, opt == model.attributes.settings.attributes.ea_adv_data_table_source_remote_table);
				});
			}
		}, 50);
	};

	initRemoteTables();

	panel.el.addEventListener("mousedown", function(e) {
		if (e.target.classList.contains("elementor-section-title") || e.target.parentNode.classList.contains("elementor-panel-navigation-tab")) {
			initRemoteTables();
		}
	});
};

Advanced_Data_Table_Context_Menu = function(groups, element) {
	if (
		element.options.model.attributes.widgetType == "eael-advanced-data-table" &&
		element.options.model.attributes.settings.attributes.ea_adv_data_table_source == "static"
	) {
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
