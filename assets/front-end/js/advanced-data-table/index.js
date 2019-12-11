var advanced_data_table_active_cell = null;
var advanced_data_table_drag_start_x,
	advanced_data_table_drag_start_width,
	advanced_data_table_drag_el,
	advanced_data_table_dragging = false;

var Advanced_Data_Table = function($scope, $) {
	var table = $scope.context.querySelector(".ea-advanced-data-table");
	var search = $scope.context.querySelector(".ea-advanced-data-table-search");
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
		search.addEventListener("input", function(e) {
			var input = this.value.toLowerCase();
			var paginated =
				table.parentNode.querySelector(".ea-advanced-data-table-pagination").querySelectorAll(".ea-advanced-data-table-pagination-current").length > 0;

			if (table.rows.length > 1) {
				if (input.length > 0) {
					table.parentNode.querySelector(".ea-advanced-data-table-pagination").style.display = "none";

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
					table.parentNode.querySelector(".ea-advanced-data-table-pagination").style.display = "";

					if (paginated) {
						var currentPage = table.parentNode.querySelector(".ea-advanced-data-table-pagination-current").dataset.page;
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

		// sort
		table.addEventListener("click", function(e) {
			if (e.target.tagName.toLowerCase() === "th") {
				var index = e.target.cellIndex;
				var desc = e.target.classList.toggle("desc");
				var switching = true;
				var paginated =
					table.parentNode.querySelector(".ea-advanced-data-table-pagination").querySelectorAll(".ea-advanced-data-table-pagination-current").length > 0;
				var currentPage = 1;
				var startIndex = 1;
				var endIndex = table.rows.length - 1;

				if (paginated) {
					currentPage = table.parentNode.querySelector(".ea-advanced-data-table-pagination-current").dataset.page;
					startIndex = (currentPage - 1) * table.dataset.itemsPerPage + 1;
					endIndex = currentPage * table.dataset.itemsPerPage;
				}

				classCollection[currentPage] = [];

				table.querySelectorAll("th").forEach(function(el) {
					classCollection[currentPage].push(el.classList.contains("desc"));
				});

				while (switching) {
					switching = false;

					for (var i = startIndex; i < endIndex; i++) {
						var x = table.rows[i].cells[index];
						var y = table.rows[i + 1].cells[index];

						if (desc === true && x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
							table.rows[i].parentNode.insertBefore(table.rows[i + 1], table.rows[i]);
							switching = true;

							break;
						} else if (desc === false && x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
							table.rows[i].parentNode.insertBefore(table.rows[i + 1], table.rows[i]);
							switching = true;

							break;
						}
					}
				}
			}
		});

		// paginated table
		if (table.classList.contains("ea-advanced-data-table-paginated")) {
			var pagination = table.parentNode.querySelector(".ea-advanced-data-table-pagination");
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
			for (var i = 1; i <= endIndex; i++) {
				if (table.rows.length <= i) {
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
						el.classList.remove("desc");

						if (typeof classCollection[currentPage] != "undefined") {
							if (classCollection[currentPage][index]) {
								el.classList.add("desc");
							}
						}
					});
				}
			});
		}
	}
};

// Inline edit
var Advanced_Data_Table_Inline_Edit = function(panel, model, view) {
	var localRender = function() {
		var interval = setInterval(function() {
			if (view.el.querySelector(".ea-advanced-data-table")) {
				var timeout;
				var table = view.el.querySelector(".ea-advanced-data-table");

				table.addEventListener("focusin", function(e) {
					if (e.target.tagName.toLowerCase() == "textarea") {
						advanced_data_table_active_cell = e.target;
					}
				});

				table.addEventListener("input", function(e) {
					if (e.target.tagName.toLowerCase() == "textarea") {
						clearTimeout(timeout);

						// clone current table
						var origTable = table.cloneNode(true);

						// remove editable area
						origTable.querySelectorAll("th, td").forEach(function(el) {
							var value = el.querySelector("textarea").value;
							el.innerHTML = value;
						});

						// disable elementor remote server render
						model.remoteRender = false;

						// update backbone model
						model.setSetting("ea_adv_data_table_static_html", origTable.innerHTML);

						// enable elementor remote server render just after elementor throttle
						// ignore multiple assign
						timeout = setTimeout(function() {
							model.remoteRender = true;
						}, 1001);
					}
				});

				// drag
				table.addEventListener("mouseup", function(e) {
					if (e.target.tagName.toLowerCase() === "th") {
						clearTimeout(timeout);

						// clone current table
						var origTable = table.cloneNode(true);

						// remove editable area
						origTable.querySelectorAll("th, td").forEach(function(el) {
							var value = el.querySelector("textarea").value;
							el.innerHTML = value;
						});

						// disable elementor remote server render
						model.remoteRender = false;

						// update backbone model
						model.setSetting("ea_adv_data_table_static_html", origTable.innerHTML);

						// enable elementor remote server render just after elementor throttle
						// ignore multiple assign
						timeout = setTimeout(function() {
							model.remoteRender = true;
						}, 1001);
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

	model.on("remote:render", function() {
		localRender();
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
