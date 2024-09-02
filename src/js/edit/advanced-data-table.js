class advancedDataTableEdit {
	constructor() {
		// class props
		this.panel = null;
		this.model = null;
		this.view = null;
		this.table = null;
		this.tableInnerHTML = null;

		this.timeout = null;
		this.activeCell = null;
		this.dragStartX = null;
		this.dragStartWidth = null;
		this.dragEl = null;
		this.dragging = false;

		// register hooks
		eael.hooks.addFilter("advancedDataTable.getClassProps", "ea", this.getClassProps.bind(this));
		eael.hooks.addFilter("advancedDataTable.setClassProps", "ea", this.setClassProps.bind(this));
		eael.hooks.addFilter("advancedDataTable.parseHTML", "ea", this.parseHTML);
		eael.hooks.addAction("advancedDataTable.initEditor", "ea", this.initEditor.bind(this));
		eael.hooks.addAction("advancedDataTable.updateFromView", "ea", this.updateFromView.bind(this));
		eael.hooks.addAction("advancedDataTable.initInlineEdit", "ea", this.initInlineEdit.bind(this));
		eael.hooks.addAction("advancedDataTable.initPanelAction", "ea", this.initPanelAction.bind(this));
		eael.hooks.addAction("advancedDataTable.triggerTextChange", "ea", this.triggerTextChange.bind(this));

		elementor.hooks.addFilter("elements/widget/contextMenuGroups", this.initContextMenu);
		elementor.hooks.addAction("panel/open_editor/widget/eael-advanced-data-table", this.initPanel.bind(this));
	}

	// update model from view
	updateFromView(view, value, refresh = false) {
		let { model } = view;

		// disable elementor remote server render
		model.remoteRender = refresh;

		if (elementor.config.version > "2.7.6") {
			let container = view.getContainer();
			let settings = view.getContainer().settings.attributes;

			Object.keys(value).forEach((key) => {
				settings[key] = value[key];
			});

			parent.window.$e.run("document/elements/settings", {
				container,
				settings,
				options: {
					external: refresh,
				},
			});
		} else {
			// update backbone model
			Object.keys(value).forEach((key) => {
				model.setSetting(key, value[key]);
			});
		}

		// enable elementor remote server render just after elementor throttle
		// ignore multiple assign
		this.timeout = setTimeout(() => {
			model.remoteRender = true;
		}, 1001);
	}

	// get class properties
	getClassProps() {
		return {
			view: this.view,
			model: this.model,
			table: this.table,
			activeCell: this.activeCell,
		};
	}

	// get class properties
	setClassProps(props) {
		Object.keys(props).forEach((key) => {
			this[key] = props[key];
		});
	}

	// parse table html
	parseHTML(tableHTML) {
		tableHTML.querySelectorAll("th, td").forEach((el) => {
			if (el.querySelector(".inline-editor") !== null) {
				el.innerHTML = decodeURI(el.dataset.quill || "");
				delete el.dataset.quill;
			}
		});

		return tableHTML;
	}

	// init editor
	initEditor(cell) {
		// init value
		cell.dataset.quill = encodeURI(cell.innerHTML);

		// insert editor dom
		cell.innerHTML = `<div class="inline-editor">${cell.innerHTML}</div>`;

		// init quill
		let quill = new Quill(cell.querySelector(".inline-editor"), {
			theme: "bubble",
			modules: {
				toolbar: ["bold", "italic", "underline", "strike", "link", { list: "ordered" }, { list: "bullet" }],
			},
		});

		// bind change
		quill.on("text-change", (delta, oldDelta, source) => {
			clearTimeout(this.timeout);

			// update data
			cell.dataset.quill = encodeURI(quill.root.innerHTML);

			// parse table html
			let origTable = this.parseHTML(this.table.cloneNode(true));
			this.tableInnerHTML = origTable.innerHTML;
			// update table
			this.updateFromView(this.view, {
				ea_adv_data_table_static_html: origTable.innerHTML,
			});
		});
	}

	// init inline editing features
	initInlineEdit() {
		let interval = setInterval(() => {
			if (this.view.el.querySelector(".ea-advanced-data-table")) {
				// init table
				if (this.table !== this.view.el.querySelector(".ea-advanced-data-table")) {
					this.table = this.view.el.querySelector(".ea-advanced-data-table");

					// iniline editor
					if (this.table.classList.contains("ea-advanced-data-table-static")) {
						this.table.querySelectorAll("th, td").forEach((cell) => {
							this.initEditor(cell);
						});
					}

					// mousedown
					this.table.addEventListener("mousedown", (e) => {
						e.stopPropagation();

						if (e.target.tagName.toLowerCase() === "th") {
							this.dragging = true;
							this.dragEl = e.target;
							this.dragStartX = e.pageX;
							this.dragStartWidth = e.target.offsetWidth;
						}

						if (e.target.tagName.toLowerCase() === "th" || e.target.tagName.toLowerCase() === "td") {
							this.activeCell = e.target;
						} else if (e.target.parentNode.tagName.toLowerCase() === "th" || e.target.parentNode.tagName.toLowerCase() === "td") {
							this.activeCell = e.target.parentNode;
						} else if (e.target.parentNode.parentNode.tagName.toLowerCase() === "th" || e.target.parentNode.parentNode.tagName.toLowerCase() === "td") {
							this.activeCell = e.target.parentNode.parentNode;
						} else if (
							e.target.parentNode.parentNode.parentNode.tagName.toLowerCase() === "th" ||
							e.target.parentNode.parentNode.parentNode.tagName.toLowerCase() === "td"
						) {
							this.activeCell = e.target.parentNode.parentNode.parentNode;
						}
					});

					// mousemove
					this.table.addEventListener("mousemove", (e) => {
						if (this.dragging) {
							this.dragEl.style.width = `${this.dragStartWidth + (event.pageX - this.dragStartX)}px`;
						}
					});

					// mouseup
					this.table.addEventListener("mouseup", (e) => {
						if (this.dragging) {
							this.dragging = false;

							clearTimeout(this.timeout);

							if (this.table.classList.contains("ea-advanced-data-table-static")) {
								// parse table html
								let origTable = this.parseHTML(this.table.cloneNode(true));

								// update table
								this.updateFromView(this.view, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});
							} else {
								// th width store
								let widths = [];

								// collect width of th
								this.table.querySelectorAll("th").forEach((el, index) => {
									widths[index] = el.style.width;
								});

								// update table
								this.updateFromView(this.view, {
									ea_adv_data_table_dynamic_th_width: widths,
								});
							}
						}
					});

					// clear style
					this.table.addEventListener("dblclick", (e) => {
						if (e.target.tagName.toLowerCase() === "th") {
							e.stopPropagation();

							clearTimeout(this.timeout);

							if (this.table.classList.contains("ea-advanced-data-table-static")) {
								// parse table html
								let origTable = this.parseHTML(this.table.cloneNode(true));

								// update table
								this.updateFromView(this.view, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});
							} else {
								// th width store
								let widths = [];

								// collect width of th
								this.table.querySelectorAll("th").forEach((el, index) => {
									widths[index] = el.style.width;
								});

								// update table
								this.updateFromView(this.view, {
									ea_adv_data_table_dynamic_th_width: widths,
								});
							}
						}
					});
				}

				clearInterval(interval);
			}
		}, 500);
	}

	// panel action
	initPanelAction() {
		this.panel.content.el.onclick = (event) => {
			if (event.target.dataset.event == "ea:advTable:export") {
				// export
				let rows = this.table.querySelectorAll("table tr");
				let csv = [];

				// generate csv
				for (let i = 0; i < rows.length; i++) {
					let row = [];
					let cols = rows[i].querySelectorAll("th, td");

					if (this.table.classList.contains("ea-advanced-data-table-static")) {
						for (let j = 0; j < cols.length; j++) {
							let encodedText = decodeURI( cols[j].dataset.quill );
							let modifiedString = encodedText.replace(/"/g, '""');
							modifiedString = `"${modifiedString}"`;
							row.push( modifiedString );
						}
					} else {
						for (let j = 0; j < cols.length; j++) {
							row.push(JSON.stringify(cols[j].innerHTML.replace( /,"""([^"]+)""",/g, ',"$1",' ).trim()));
						}
					}

					csv.push(row.join(","));
				}

				// download
				let csv_file = new Blob([csv.join("\n")], { type: "text/csv" });
				let downloadLink = parent.document.createElement("a");

				downloadLink.classList.add(`ea-adv-data-table-download-${this.model.attributes.id}`);
				downloadLink.download = `ea-adv-data-table-${this.model.attributes.id}.csv`;
				downloadLink.href = window.URL.createObjectURL(csv_file);
				downloadLink.style.display = "none";
				parent.document.body.appendChild(downloadLink);
				downloadLink.click();

				parent.document.querySelector(`.ea-adv-data-table-download-${this.model.attributes.id}`).remove();
			} else if (event.target.dataset.event == "ea:advTable:import") {
				// import
				let textarea = this.panel.content.el.querySelector(".ea_adv_table_csv_string");
				let enableHeader = this.panel.content.el.querySelector(".ea_adv_table_csv_string_table").checked;
				let csletr = textarea.value.split("\n");
				let header = "";
				let body = "";

				if ( textarea.value.length > 0 ) {
					body += "<tbody>";
					csletr.forEach( (row, index) => {
						const result = [];
						let field = '';
						let inQuotes = false;
						let i = 0;
						while ( i < row.length ) {
							const char = row[i];
							if ( char === '"' ) {
								if ( inQuotes && row[i + 1] === '"' ) {
									//Handle escaped double quote
									field += '"';
									i++;
								} else {
									inQuotes = !inQuotes; //Toggle inQuotes
								}
							} else if ( char === ',' && !inQuotes ) {
								//End of field
								result.push(field);
								field = '';
							} else {
								field += char; //Regular character
							}
							i++;
						}
						result.push(field);

						//Generate HTML table
						if ( result.length > 0 ) {
							if ( enableHeader && index == 0 ) {
								header += "<thead><tr>";
								result.forEach( (col) => {
									header += `<th>${col}</th>`;
								} );
								header += "</tr></thead>";
							} else {
								body += "<tr>";
								result.forEach( (col) => {
									body += `<td>${col}</td>`;
								} );
								body += "</tr>";
							}
						}
					} );
					body += "</tbody>";

					if (header.length > 0 || body.length > 0) {
						this.tableInnerHTML = header + body;

						this.updateFromView(
							this.view,
							{
								ea_adv_data_table_static_html: header + body,
							},
							true
						);

						// init inline edit
						let interval = setInterval(() => {
							if (this.view.el.querySelector(".ea-advanced-data-table").innerHTML == header + body) {
								clearInterval(interval);

								eael.hooks.doAction("advancedDataTable.initInlineEdit");
							}
						}, 500);
					}
				}

				textarea.value = "";
			}

			eael.hooks.doAction("advancedDataTable.panelAction", this.panel, this.model, this.view, event);
		};
	}

	// init panel
	initPanel(panel, model, view) {
		this.panel = panel;
		this.model = model;
		this.view = view;
		const elClass = `.ea-advanced-data-table-${this.view.container.args.id}`;
		const eaTable  = this.view.el.querySelector( ".ea-advanced-data-table" + elClass )
		// init inline edit
		eael.hooks.doAction("advancedDataTable.initInlineEdit");

		// init panel action
		eael.hooks.doAction("advancedDataTable.initPanelAction");

		// after panel init hook
		eael.hooks.doAction("advancedDataTable.afterInitPanel", panel, model, view);
		
		model.once("editor:close", () => {

			if ( !eaTable ) {
				return false;
			}
			// parse table html
			let origTable = this.parseHTML(eaTable.cloneNode(true));
			this.tableInnerHTML = origTable.innerHTML;
			
			// update table
			// this.updateFromView(
			// 	this.view,
			// 	{
			// 		ea_adv_data_table_static_html: this.tableInnerHTML,
			// 	},
			// 	true
			// );
		});
	}

	// context menu
	initContextMenu(groups, element) {
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
						callback() {
							const { view, table, activeCell } = eael.hooks.applyFilters("advancedDataTable.getClassProps");
							// remove blank tr if any
							jQuery(table).find('tr:empty').each(function() {
								if(jQuery(this).find('td').length == 0) {
									this.remove();
								}
							});

							if ( activeCell !== null && activeCell.tagName.toLowerCase() != "th" && activeCell.parentNode.rowIndex ) {
								let index = activeCell.parentNode.rowIndex;
								let row = table.insertRow(index);
								// insert cells in row
								for (let i = 0; i < table.rows[0].cells.length; i++) {
									let cell = row.insertCell(i);

									// init inline editor
									eael.hooks.doAction("advancedDataTable.initEditor", cell);
								}

								// remove active cell
								eael.hooks.applyFilters("advancedDataTable.setClassProps", { activeCell: null });

								// parse table html
								let origTable = eael.hooks.applyFilters("advancedDataTable.parseHTML", table.cloneNode(true));

								// update model
								eael.hooks.doAction("advancedDataTable.updateFromView", view, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});

								// trigger text-change event
								eael.hooks.doAction("advancedDataTable.triggerTextChange", table);
							}
						},
					},
					{
						name: "add_row_below",
						title: "Add Row Below",
						callback() {
							const { view, table, activeCell } = eael.hooks.applyFilters("advancedDataTable.getClassProps");

							if (activeCell !== null) {
								let index = activeCell.parentNode.rowIndex + 1;
								let row = table.insertRow(index);

								for (let i = 0; i < table.rows[0].cells.length; i++) {
									let cell = row.insertCell(i);

									// init inline editor
									eael.hooks.doAction("advancedDataTable.initEditor", cell);
								}

								// remove active cell
								eael.hooks.applyFilters("advancedDataTable.setClassProps", { activeCell: null });

								// parse table html
								let origTable = eael.hooks.applyFilters("advancedDataTable.parseHTML", table.cloneNode(true));

								// update model
								eael.hooks.doAction("advancedDataTable.updateFromView", view, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});

								// trigger text-change event
								eael.hooks.doAction("advancedDataTable.triggerTextChange", table);
							}
						},
					},
					{
						name: "add_column_left",
						title: "Add Column Left",
						callback() {
							const { view, table, activeCell } = eael.hooks.applyFilters("advancedDataTable.getClassProps");

							if (activeCell !== null) {
								let index = activeCell.cellIndex;

								for (let i = 0; i < table.rows.length; i++) {
									if (table.rows[i].cells[0].tagName.toLowerCase() == "th") {
										let cell = table.rows[i].insertBefore(document.createElement("th"), table.rows[i].cells[index]);

										// init inline editor
										eael.hooks.doAction("advancedDataTable.initEditor", cell);
									} else {
										let cell = table.rows[i].insertCell(index);

										// init inline editor
										eael.hooks.doAction("advancedDataTable.initEditor", cell);
									}
								}

								// remove active cell
								eael.hooks.applyFilters("advancedDataTable.setClassProps", { activeCell: null });

								// parse table html
								let origTable = eael.hooks.applyFilters("advancedDataTable.parseHTML", table.cloneNode(true));

								// update model
								eael.hooks.doAction("advancedDataTable.updateFromView", view, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});

								// trigger text-change event
								eael.hooks.doAction("advancedDataTable.triggerTextChange", table);
							}
						},
					},
					{
						name: "add_column_right",
						title: "Add Column Right",
						callback() {
							const { view, table, activeCell } = eael.hooks.applyFilters("advancedDataTable.getClassProps");

							if (activeCell !== null) {
								let index = activeCell.cellIndex + 1;

								for (let i = 0; i < table.rows.length; i++) {
									if (table.rows[i].cells[0].tagName.toLowerCase() == "th") {
										let cell = table.rows[i].insertBefore(document.createElement("th"), table.rows[i].cells[index]);

										// init inline editor
										eael.hooks.doAction("advancedDataTable.initEditor", cell);
									} else {
										let cell = table.rows[i].insertCell(index);

										// init inline editor
										eael.hooks.doAction("advancedDataTable.initEditor", cell);
									}
								}

								// remove active cell
								eael.hooks.applyFilters("advancedDataTable.setClassProps", { activeCell: null });

								// parse table html
								let origTable = eael.hooks.applyFilters("advancedDataTable.parseHTML", table.cloneNode(true));

								// update model
								eael.hooks.doAction("advancedDataTable.updateFromView", view, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});

								// trigger text-change event
								eael.hooks.doAction("advancedDataTable.triggerTextChange", table);
							}
						},
					},
					{
						name: "delete_row",
						title: "Delete Row",
						callback() {
							const { view, table, activeCell } = eael.hooks.applyFilters("advancedDataTable.getClassProps");

							if (activeCell !== null) {
								let index = activeCell.parentNode.rowIndex;

								// delete row
								table.deleteRow(index);

								// remove active cell
								eael.hooks.applyFilters("advancedDataTable.setClassProps", { activeCell: null });

								// parse table html
								let origTable = eael.hooks.applyFilters("advancedDataTable.parseHTML", table.cloneNode(true));

								// update model
								eael.hooks.doAction("advancedDataTable.updateFromView", view, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});

								// trigger text-change event
								eael.hooks.doAction("advancedDataTable.triggerTextChange", table);
							}
						},
					},
					{
						name: "delete_column",
						title: "Delete Column",
						callback() {
							const { view, table, activeCell } = eael.hooks.applyFilters("advancedDataTable.getClassProps");

							if (activeCell !== null) {
								let index = activeCell.cellIndex;

								// delete columns
								for (let i = 0; i < table.rows.length; i++) {
									table.rows[i].deleteCell(index);
								}

								// remove active cell
								eael.hooks.applyFilters("advancedDataTable.setClassProps", { activeCell: null });

								// parse table html
								let origTable = eael.hooks.applyFilters("advancedDataTable.parseHTML", table.cloneNode(true));

								// update model
								eael.hooks.doAction("advancedDataTable.updateFromView", view, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});

								// trigger text-change event
								eael.hooks.doAction("advancedDataTable.triggerTextChange", table);
							}
						},
					},
				],
			});
		}

		return groups;
	}

	triggerTextChange(table) {
		if (table.classList.contains("ea-advanced-data-table-static")) {
			var cellSelector = jQuery('thead tr:first-child th:first-child .ql-editor p', table),
				cellSelector = cellSelector.length ? cellSelector : jQuery('tbody tr:first-child td:first-child .ql-editor p', table),
				cellData = cellSelector.html();
			cellSelector.html(cellData + ' ');

			setTimeout(() => {
				cellSelector.html(cellData);
			}, 1100);
		}
	}
}

eael.hooks.addAction("editMode.init", "ea", () => {
	new advancedDataTableEdit();
});
