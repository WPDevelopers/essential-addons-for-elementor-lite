class advancedDataTable {
	constructor() {
		// class props
		this.timeout = null;
		this.activeCell = null;
		this.dragStartX = null;
		this.dragStartWidth = null;
		this.dragEl = null;
		this.dragging = false;
		this.inlineEditInitiated = false;
		this.panelActionInitiated = false;

		// hooks
		elementorFrontend.hooks.addAction("frontend/element_ready/eael-advanced-data-table.default", this.initFrontend.bind(this));

		if (ea.isEditMode) {
			ea.hooks.addAction("ea.advDataTable.updateModel", "ea", this.updateModel.bind(this));
			ea.hooks.addAction("ea.advDataTable.updateView", "ea", this.updateView.bind(this));
			ea.hooks.addAction("ea.advDataTable.initInlineEdit", "ea", this.initInlineEdit.bind(this));
			ea.hooks.addAction("ea.advDataTable.initPanelAction", "ea", this.initPanelAction.bind(this));
			elementor.hooks.addFilter("elements/widget/contextMenuGroups", this.initContextMenu.bind(this));
			elementor.hooks.addAction("panel/open_editor/widget/eael-advanced-data-table", this.initPanel.bind(this));
		}
	}

	updateModel(model, container, refresh, value) {
		// disable elementor remote server render
		model.remoteRender = refresh;

		if (elementor.config.version > "2.7.6") {
			let settings = container.settings.attributes;

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

	updateView(view, refresh, value) {
		let model = view.model;

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

	initInlineEdit(model, view) {
		if (this.inlineEditInitiated) {
			return;
		}

		let interval = setInterval(() => {
			if (view.el.querySelector(".ea-advanced-data-table")) {
				this.inlineEditInitiated = true;
				let table = view.el.querySelector(`.ea-advanced-data-table-${model.attributes.id}`);

				// init tinymce
				if (table.classList.contains("ea-advanced-data-table-editable")) {
					tinymce.init({
						selector: ".inline-edit",
						menubar: false,
						inline: true,
						plugins: ["lists", "link", "autolink"],
						toolbar: "bold italic underline strikethrough link | alignleft aligncenter alignright | numlist bullist",
						forced_root_block: false,
					});
				}

				// mousedown
				table.addEventListener("mousedown", (e) => {
					e.stopPropagation();

					console.log("mos");

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
					}
				});

				// mousemove
				table.addEventListener("mousemove", (e) => {
					if (this.dragging) {
						this.dragEl.style.width = `${this.dragStartWidth + (event.pageX - this.dragStartX)}px`;
					}
				});

				// mouseup
				table.addEventListener("mouseup", (e) => {
					if (this.dragging) {
						this.dragging = false;

						clearTimeout(this.timeout);

						if (table.classList.contains("ea-advanced-data-table-static")) {
							// clone current table
							let origTable = table.cloneNode(true);

							// remove editable area
							origTable.querySelectorAll("th, td").forEach((el) => {
								el.removeAttribute("id");
								el.removeAttribute("class");
								el.removeAttribute("contenteditable");
								el.removeAttribute("spellcheck");
							});

							// update table
							ea.hooks.doAction("ea.advDataTable.updateView", view, false, {
								ea_adv_data_table_static_html: origTable.innerHTML,
							});
						} else {
							// th width store
							let widths = [];

							// collect width of th
							table.querySelectorAll("th").forEach((el, index) => {
								widths[index] = el.style.width;
							});

							// update table
							ea.hooks.doAction("ea.advDataTable.updateView", view, false, {
								ea_adv_data_table_dynamic_th_width: widths,
							});
						}
					}
				});

				// input
				table.addEventListener("input", (e) => {
					if (table.classList.contains("ea-advanced-data-table-static")) {
						clearTimeout(this.timeout);

						// clone current table
						let origTable = table.cloneNode(true);

						// remove editable area
						origTable.querySelectorAll("th, td").forEach((el) => {
							el.removeAttribute("id");
							el.removeAttribute("class");
							el.removeAttribute("contenteditable");
							el.removeAttribute("spellcheck");
						});

						// update table
						ea.hooks.doAction("ea.advDataTable.updateView", view, false, {
							ea_adv_data_table_static_html: origTable.innerHTML,
						});
					}
				});

				// clear style
				table.addEventListener("dblclick", (e) => {
					if (e.target.tagName.toLowerCase() === "th") {
						e.stopPropagation();

						clearTimeout(this.timeout);

						if (table.classList.contains("ea-advanced-data-table-static")) {
							// clone current table
							let origTable = table.cloneNode(true);

							// remove editable area
							origTable.querySelectorAll("th, td").forEach((el) => {
								el.removeAttribute("id");
								el.removeAttribute("class");
								el.removeAttribute("contenteditable");
								el.removeAttribute("spellcheck");
							});

							// update table
							ea.hooks.doAction("ea.advDataTable.updateView", view, false, {
								ea_adv_data_table_static_html: origTable.innerHTML,
							});
						} else {
							// th width store
							let widths = [];

							// collect width of th
							table.querySelectorAll("th").forEach((el, index) => {
								widths[index] = el.style.width;
							});

							// update table
							ea.hooks.doAction("ea.advDataTable.updateView", view, false, {
								ea_adv_data_table_dynamic_th_width: widths,
							});
						}
					}
				});
			}

			clearInterval(interval);
		}, 100);
	}

	initPanelAction(panel, model, view) {
		if (this.panelActionInitiated) {
			return;
		}

		panel.el.addEventListener("click", (e) => {
			this.panelActionInitiated = true;

			if (event.target.dataset.event == "ea:advTable:export") {
				// export
				let table = view.el.querySelector(`.ea-advanced-data-table-${model.attributes.id}`);
				let rows = table.querySelectorAll("table tr");
				let csv = [];

				// generate csv
				for (let i = 0; i < rows.length; i++) {
					let row = [];
					let cols = rows[i].querySelectorAll("th, td");

					if (table.classList.contains("ea-advanced-data-table-static")) {
						for (let j = 0; j < cols.length; j++) {
							row.push(JSON.stringify(cols[j].innerHTML.trim()));
						}
					} else {
						for (let j = 0; j < cols.length; j++) {
							row.push(JSON.stringify(cols[j].innerHTML.replace(/(\r\n|\n|\r)/gm, " ").trim()));
						}
					}

					csv.push(row.join(","));
				}

				// download
				let csv_file = new Blob([csv.join("\n")], { type: "text/csv" });
				let download_link = parent.document.createElement("a");

				download_link.classList.add(`ea-adv-data-table-download-${model.attributes.id}`);
				download_link.download = `ea-adv-data-table-${model.attributes.id}.csv`;
				download_link.href = window.URL.createObjectURL(csv_file);
				download_link.style.display = "none";
				parent.document.body.appendChild(download_link);
				download_link.click();

				parent.document.querySelector(`.ea-adv-data-table-download-${model.attributes.id}`).remove();
			} else if (event.target.dataset.event == "ea:advTable:import") {
				// import
				let textarea = panel.el.querySelector(".ea_adv_table_csv_string");
				let enableHeader = panel.el.querySelector(".ea_adv_table_csv_string_table").checked;
				let csletr = textarea.value.split("\n");
				let header = "";
				let body = "";

				if (textarea.value.length > 0) {
					body += "<tbody>";
					csletr.forEach((row, index) => {
						if (row.length > 0) {
							cols = row.match(/("(?:[^"\\]|\\.)*"|[^","]+)/gm);

							if (cols.length > 0) {
								if (enableHeader && index == 0) {
									header += "<thead><tr>";
									cols.forEach((col) => {
										if (col.match(/(^"")|(^")|("$)|(""$)/g)) {
											header += `<th>${JSON.parse(col)}</th>`;
										} else {
											header += `<th>${col}</th>`;
										}
									});
									header += "</tr></thead>";
								} else {
									body += "<tr>";
									cols.forEach((col) => {
										if (col.match(/(^"")|(^")|("$)|(""$)/g)) {
											body += `<td>${JSON.parse(col)}</td>`;
										} else {
											body += `<td>${col}</td>`;
										}
									});
									body += "</tr>";
								}
							}
						}
					});
					body += "</tbody>";

					if (header.length > 0 || body.length > 0) {
						ea.hooks.doAction("ea.advDataTable.updateView", view, true, {
							ea_adv_data_table_static_html: header + body,
						});
					}
				}

				textarea.value = "";
			} else if (event.target.dataset.event == "ea:advTable:connect") {
				let button = event.target;
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
						database: model.attributes.settings.attributes.ea_adv_data_table_source_remote_database,
					},
					success(response) {
						if (response.connected == true) {
							button.innerHTML = "Connected";

							ea.hooks.doAction("ea.advDataTable.updateView", view, true, {
								ea_adv_data_table_source_remote_connected: true,
								ea_adv_data_table_source_remote_tables: response.tables,
							});

							// reload panel
							panel.content.el.querySelector(".elementor-section-title").click();
							panel.content.el.querySelector(".elementor-section-title").click();

							let select = panel.el.querySelector('[data-setting="ea_adv_data_table_source_remote_table"]');
							select.length = 0;
							response.tables.forEach((opt, index) => {
								select[index] = new Option(opt, opt);
							});
						} else {
							button.innerHTML = "Failed";
						}
					},
					error() {
						button.innerHTML = "Failed";
					},
				});

				setTimeout(() => {
					button.innerHTML = "Connect";
				}, 2000);
			} else if (event.target.dataset.event == "ea:advTable:disconnect") {
				ea.hooks.doAction("ea.advDataTable.updateView", view, true, {
					ea_adv_data_table_source_remote_connected: false,
					ea_adv_data_table_source_remote_tables: [],
				});

				// reload panel
				panel.content.el.querySelector(".elementor-section-title").click();
				panel.content.el.querySelector(".elementor-section-title").click();
			}
		});
	}

	initPanel(panel, model, view) {
		// init inline edit for first time
		ea.hooks.doAction("ea.advDataTable.initInlineEdit", model, view);
		ea.hooks.doAction("ea.advDataTable.initPanelAction", panel, model, view);

		// re init inline edit after render
		model.on("remote:render", () => {
			this.inlineEditInitiated = false;
			ea.hooks.doAction("ea.advDataTable.initInlineEdit", model, view);
		});
	}

	initFrontend($scope, $) {
		let table = $scope.context.querySelector(".ea-advanced-data-table");
		let search = $scope.context.querySelector(".ea-advanced-data-table-search");
		let pagination = $scope.context.querySelector(".ea-advanced-data-table-pagination");
		let classCollection = {};

		if (ea.isEditMode) {
			table.querySelectorAll("th, td").forEach((el) => {
				if (el.innerHTML.indexOf('<div class="inline-edit">') !== 0) {
					el.innerHTML = `<div class="inline-edit">${el.innerHTML}</div>`;
				}
			});
		} else {
			// search
			this.initTableSearch(table, search, pagination);

			// sort
			this.initTableSort(table, pagination, classCollection);

			// paginated table
			this.initTablePagination(table, pagination, classCollection);

			// woocommerce
			this.initWooFeatures(table);
		}
	}

	initTableSearch(table, search, pagination) {
		if (search) {
			search.addEventListener("input", (e) => {
				let input = this.value.toLowerCase();
				let hasSort = table.classList.contains("ea-advanced-data-table-sortable");
				let offset = table.rows[0].parentNode.tagName.toLowerCase() == "thead" ? 1 : 0;

				if (table.rows.length > 1) {
					if (input.length > 0) {
						if (hasSort) {
							table.classList.add("ea-advanced-data-table-unsortable");
						}

						if (pagination && pagination.innerHTML.length > 0) {
							pagination.style.display = "none";
						}

						for (let i = offset; i < table.rows.length; i++) {
							let matchFound = false;

							if (table.rows[i].cells.length > 0) {
								for (let j = 0; j < table.rows[i].cells.length; j++) {
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

							let currentPage = pagination.querySelector(".ea-advanced-data-table-pagination-current").dataset.page;
							let startIndex = (currentPage - 1) * table.dataset.itemsPerPage + 1;
							let endIndex = currentPage * table.dataset.itemsPerPage;

							for (let i = 1; i <= table.rows.length - 1; i++) {
								if (i >= startIndex && i <= endIndex) {
									table.rows[i].style.display = "table-row";
								} else {
									table.rows[i].style.display = "none";
								}
							}
						} else {
							for (let i = 1; i <= table.rows.length - 1; i++) {
								table.rows[i].style.display = "table-row";
							}
						}
					}
				}
			});
		}
	}

	initTableSort(table, pagination, classCollection) {
		if (table.classList.contains("ea-advanced-data-table-sortable")) {
			table.addEventListener("click", (e) => {
				if (e.target.tagName.toLowerCase() === "th") {
					let index = e.target.cellIndex;
					let currentPage = 1;
					let startIndex = 1;
					let endIndex = table.rows.length - 1;
					let sort = "";
					let classList = e.target.classList;
					let collection = [];
					let origTable = table.cloneNode(true);

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

					table.querySelectorAll("th").forEach((el) => {
						if (el.cellIndex != index) {
							el.classList.remove("asc", "desc");
						}

						classCollection[currentPage].push(el.classList.contains("asc") ? "asc" : el.classList.contains("desc") ? "desc" : "");
					});

					// collect table cells value
					for (let i = startIndex; i <= endIndex; i++) {
						let value;
						let cell = table.rows[i].cells[index];

						if (isNaN(parseInt(cell.innerText))) {
							value = cell.innerText.toLowerCase();
						} else {
							value = parseInt(cell.innerText);
						}

						collection.push({ index: i, value });
					}

					// sort collection array
					if (sort == "asc") {
						collection.sort((x, y) => {
							return x.value > y.value ? 1 : -1;
						});
					} else if (sort == "desc") {
						collection.sort((x, y) => {
							return x.value < y.value ? 1 : -1;
						});
					}

					// sort table
					collection.forEach((row, index) => {
						table.rows[startIndex + index].innerHTML = origTable.rows[row.index].innerHTML;
					});
				}
			});
		}
	}

	initTablePagination(table, pagination, classCollection) {
		if (table.classList.contains("ea-advanced-data-table-paginated")) {
			let paginationHTML = "";
			let paginationType = pagination.classList.contains("ea-advanced-data-table-pagination-button") ? "button" : "select";
			let currentPage = 1;
			let startIndex = table.rows[0].parentNode.tagName.toLowerCase() == "thead" ? 1 : 0;
			let endIndex = currentPage * table.dataset.itemsPerPage;
			let maxPages = Math.ceil((table.rows.length - 1) / table.dataset.itemsPerPage);

			// insert pagination
			if (maxPages > 1) {
				if (paginationType == "button") {
					for (let i = 1; i <= maxPages; i++) {
						paginationHTML += `<a href="#" data-page="${i}" class="${i == 1 ? "ea-advanced-data-table-pagination-current" : ""}">${i}</a>`;
					}

					pagination.insertAdjacentHTML("beforeend", `<a href="#" data-page="1">&laquo;</a>${paginationHTML}<a href="#" data-page="${maxPages}">&raquo;</a>`);
				} else {
					for (let i = 1; i <= maxPages; i++) {
						paginationHTML += `<option value="${i}">${i}</option>`;
					}

					pagination.insertAdjacentHTML("beforeend", `<select>${paginationHTML}</select>`);
				}
			}

			// make initial item visible
			for (let i = 0; i <= endIndex; i++) {
				if (i >= table.rows.length) {
					break;
				}

				table.rows[i].style.display = "table-row";
			}

			// paginate on click
			if (paginationType == "button") {
				pagination.addEventListener("click", (e) => {
					e.preventDefault();

					if (e.target.tagName.toLowerCase() == "a") {
						currentPage = e.target.dataset.page;
						offset = table.rows[0].parentNode.tagName.toLowerCase() == "thead" ? 1 : 0;
						startIndex = (currentPage - 1) * table.dataset.itemsPerPage + offset;
						endIndex = currentPage * table.dataset.itemsPerPage;

						pagination.querySelectorAll(".ea-advanced-data-table-pagination-current").forEach((el) => {
							el.classList.remove("ea-advanced-data-table-pagination-current");
						});

						pagination.querySelectorAll(`[data-page="${currentPage}"]`).forEach((el) => {
							el.classList.add("ea-advanced-data-table-pagination-current");
						});

						for (let i = offset; i <= table.rows.length - 1; i++) {
							if (i >= startIndex && i <= endIndex) {
								table.rows[i].style.display = "table-row";
							} else {
								table.rows[i].style.display = "none";
							}
						}

						table.querySelectorAll("th").forEach((el, index) => {
							el.classList.remove("asc", "desc");

							if (typeof classCollection[currentPage] != "undefined") {
								if (classCollection[currentPage][index]) {
									el.classList.add(classCollection[currentPage][index]);
								}
							}
						});
					}
				});
			} else {
				if (pagination.hasChildNodes()) {
					pagination.querySelector("select").addEventListener("input", (e) => {
						e.preventDefault();

						currentPage = e.target.value;
						offset = table.rows[0].parentNode.tagName.toLowerCase() == "thead" ? 1 : 0;
						startIndex = (currentPage - 1) * table.dataset.itemsPerPage + offset;
						endIndex = currentPage * table.dataset.itemsPerPage;

						for (let i = offset; i <= table.rows.length - 1; i++) {
							if (i >= startIndex && i <= endIndex) {
								table.rows[i].style.display = "table-row";
							} else {
								table.rows[i].style.display = "none";
							}
						}

						table.querySelectorAll("th").forEach((el, index) => {
							el.classList.remove("asc", "desc");

							if (typeof classCollection[currentPage] != "undefined") {
								if (classCollection[currentPage][index]) {
									el.classList.add(classCollection[currentPage][index]);
								}
							}
						});
					});
				}
			}
		}
	}

	initWooFeatures(table) {
		table.querySelectorAll(".nt_button_woo").forEach((el) => {
			el.classList.add("add_to_cart_button", "ajax_add_to_cart");
		});

		table.querySelectorAll(".nt_woo_quantity").forEach((el) => {
			el.addEventListener("input", (e) => {
				let product_id = e.target.dataset.product_id;
				let quantity = e.target.value;

				$(`.nt_add_to_cart_${product_id}`, $(table)).data("quantity", quantity);
			});
		});
	}

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
							let table = document.querySelector(`.ea-advanced-data-table-${element.options.model.attributes.id}`);

							if (advanced_data_table_active_cell !== null && advanced_data_table_active_cell.tagName.toLowerCase() != "th") {
								let index = advanced_data_table_active_cell.parentNode.rowIndex;
								let row = table.insertRow(index);

								for (let i = 0; i < table.rows[0].cells.length; i++) {
									let cell = row.insertCell(i);
									cell.classList.add("inline-edit");
								}

								advanced_data_table_active_cell = null;

								// reinit tinymce
								tinymce.init({
									selector: ".ea-advanced-data-table-editable th, .ea-advanced-data-table-editable td",
									menubar: false,
									inline: true,
									plugins: ["lists", "link", "autolink"],
									toolbar: "bold italic underline strikethrough link | alignleft aligncenter alignright | numlist bullist",
									forced_root_block: false,
								});

								// clone current table
								let origTable = table.cloneNode(true);

								// remove editable area
								origTable.querySelectorAll("th, td").forEach((el) => {
									el.removeAttribute("id");
									el.removeAttribute("class");
									el.removeAttribute("contenteditable");
									el.removeAttribute("spellcheck");
								});

								// update model
								ea.hooks.doAction("ea.advDataTable.updateModel", element.options.model, element.container, false, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});
							}
						},
					},
					{
						name: "add_row_below",
						title: "Add Row Below",
						callback() {
							let table = document.querySelector(`.ea-advanced-data-table-${element.options.model.attributes.id}`);

							if (advanced_data_table_active_cell !== null) {
								let index = advanced_data_table_active_cell.parentNode.rowIndex + 1;
								let row = table.insertRow(index);

								for (let i = 0; i < table.rows[0].cells.length; i++) {
									let cell = row.insertCell(i);
									cell.classList.add("inline-edit");
								}

								advanced_data_table_active_cell = null;

								// reinit tinymce
								tinymce.init({
									selector: ".ea-advanced-data-table-editable th, .ea-advanced-data-table-editable td",
									menubar: false,
									inline: true,
									plugins: ["lists", "link", "autolink"],
									toolbar: "bold italic underline strikethrough link | alignleft aligncenter alignright | numlist bullist",
									forced_root_block: false,
								});

								// clone current table
								let origTable = table.cloneNode(true);

								// remove editable area
								origTable.querySelectorAll("th, td").forEach((el) => {
									el.removeAttribute("id");
									el.removeAttribute("class");
									el.removeAttribute("contenteditable");
									el.removeAttribute("spellcheck");
								});

								// update model
								ea.hooks.doAction("ea.advDataTable.updateModel", element.options.model, element.container, false, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});
							}
						},
					},
					{
						name: "add_column_left",
						title: "Add Column Left",
						callback() {
							let table = document.querySelector(`.ea-advanced-data-table-${element.options.model.attributes.id}`);

							if (advanced_data_table_active_cell !== null) {
								let index = advanced_data_table_active_cell.cellIndex;

								for (let i = 0; i < table.rows.length; i++) {
									if (table.rows[i].cells[0].tagName.toLowerCase() == "th") {
										let cell = table.rows[i].insertBefore(document.createElement("th"), table.rows[i].cells[index]);
									} else {
										let cell = table.rows[i].insertCell(index);
									}

									cell.classList.add("inline-edit");
								}

								advanced_data_table_active_cell = null;

								// reinit tinymce
								tinymce.init({
									selector: ".ea-advanced-data-table-editable th, .ea-advanced-data-table-editable td",
									menubar: false,
									inline: true,
									plugins: ["lists", "link", "autolink"],
									toolbar: "bold italic underline strikethrough link | alignleft aligncenter alignright | numlist bullist",
									forced_root_block: false,
								});

								// clone current table
								let origTable = table.cloneNode(true);

								// remove editable area
								origTable.querySelectorAll("th, td").forEach((el) => {
									el.removeAttribute("id");
									el.removeAttribute("class");
									el.removeAttribute("contenteditable");
									el.removeAttribute("spellcheck");
								});

								// update model
								ea.hooks.doAction("ea.advDataTable.updateModel", element.options.model, element.container, false, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});
							}
						},
					},
					{
						name: "add_column_right",
						title: "Add Column Right",
						callback() {
							let table = document.querySelector(`.ea-advanced-data-table-${element.options.model.attributes.id}`);

							if (advanced_data_table_active_cell !== null) {
								let index = advanced_data_table_active_cell.cellIndex + 1;

								for (let i = 0; i < table.rows.length; i++) {
									if (table.rows[i].cells[0].tagName.toLowerCase() == "th") {
										let cell = table.rows[i].insertBefore(document.createElement("th"), table.rows[i].cells[index]);
									} else {
										let cell = table.rows[i].insertCell(index);
									}

									cell.classList.add("inline-edit");
								}

								advanced_data_table_active_cell = null;

								// reinit tinymce
								tinymce.init({
									selector: ".ea-advanced-data-table-editable th, .ea-advanced-data-table-editable td",
									menubar: false,
									inline: true,
									plugins: ["lists", "link", "autolink"],
									toolbar: "bold italic underline strikethrough link | alignleft aligncenter alignright | numlist bullist",
									forced_root_block: false,
								});

								// clone current table
								let origTable = table.cloneNode(true);

								// remove editable area
								origTable.querySelectorAll("th, td").forEach((el) => {
									el.removeAttribute("id");
									el.removeAttribute("class");
									el.removeAttribute("contenteditable");
									el.removeAttribute("spellcheck");
								});

								// update model
								ea.hooks.doAction("ea.advDataTable.updateModel", element.options.model, element.container, false, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});
							}
						},
					},
					{
						name: "delete_row",
						title: "Delete Row",
						callback() {
							let table = document.querySelector(`.ea-advanced-data-table-${element.options.model.attributes.id}`);

							if (advanced_data_table_active_cell !== null) {
								let index = advanced_data_table_active_cell.parentNode.rowIndex;

								table.deleteRow(index);

								advanced_data_table_active_cell = null;

								// clone current table
								let origTable = table.cloneNode(true);

								// remove editable area
								origTable.querySelectorAll("th, td").forEach((el) => {
									el.removeAttribute("id");
									el.removeAttribute("class");
									el.removeAttribute("contenteditable");
									el.removeAttribute("spellcheck");
								});

								// update model
								ea.hooks.doAction("ea.advDataTable.updateModel", element.options.model, element.container, false, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});
							}
						},
					},
					{
						name: "delete_column",
						title: "Delete Column",
						callback() {
							let table = document.querySelector(`.ea-advanced-data-table-${element.options.model.attributes.id}`);

							if (advanced_data_table_active_cell !== null) {
								let index = advanced_data_table_active_cell.cellIndex;

								for (let i = 0; i < table.rows.length; i++) {
									table.rows[i].deleteCell(index);
								}

								advanced_data_table_active_cell = null;

								// clone current table
								let origTable = table.cloneNode(true);

								// remove editable area
								origTable.querySelectorAll("th, td").forEach((el) => {
									el.removeAttribute("id");
									el.removeAttribute("class");
									el.removeAttribute("contenteditable");
									el.removeAttribute("spellcheck");
								});

								// update model
								ea.hooks.doAction("ea.advDataTable.updateModel", element.options.model, element.container, false, {
									ea_adv_data_table_static_html: origTable.innerHTML,
								});
							}
						},
					},
				],
			});
		}

		return groups;
	}
}

ea.hooks.addAction("ea.frontend.init", "ea", () => {
	new advancedDataTable();
});
