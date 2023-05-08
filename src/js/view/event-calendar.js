var EventCalendar = function ($scope, $) {
	var Calendar = FullCalendar.Calendar;
	var element = $(".eael-event-calendar-cls", $scope),
		wrapper = $(".eael-event-calendar-wrapper", $scope),
		CloseButton = $(".eaelec-modal-close", $scope).eq(0),
		ecModal = $("#eaelecModal", $scope),
		eventAll = element.data("events"),
		firstDay = element.data("first_day"),
		calendarID = element.data("cal_id"),
		locale = element.data("locale"),
		translate = element.data("translate"),
		defaultView = element.data("defaultview"),
		defaultDate = element.data("defaultdate"),
		eventLimit = element.data("event_limit"),
		time_format = element.data("time_format") == "yes" ? true : false;

	if ( wrapper.hasClass( 'layout-calendar' ) ){
		var calendar = new Calendar(
			$scope[0].querySelector(".eael-event-calendar-cls"), {
				editable: false,
				selectable: false,
				firstDay: firstDay,
				eventTimeFormat: {
					hour: "2-digit",
					minute: "2-digit",
					hour12: !time_format,
				},
				nextDayThreshold: "00:00:00",
				headerToolbar: {
					start: "prev,next today",
					center: "title",
					end: "timeGridDay,timeGridWeek,dayGridMonth,listMonth",
				},
				events: eventAll,
				locale: locale,
				dayMaxEventRows: typeof eventLimit !== "undefined" && eventLimit > 0 ? parseInt( eventLimit ) : 3,
				initialView: defaultView,
				initialDate: defaultDate,
				eventClassNames: function(info) {},
				eventContent: function(info) {},
				eventDidMount: function (info) {
					var element = $(info.el),
						event = info.event;
					moment.locale(locale);
					// when event is finished event text are cross
					if ( element.hasClass("fc-event-past") ) {
						element.find(".fc-event-title").addClass("eael-event-completed");
					}
					translate.today = info.event._context.dateEnv.locale.options.buttonText.today;

					element.attr("style", "color:"+ event.textColor +";background:"+ event.backgroundColor +";");
					element.find(".fc-list-event-dot").attr("style", "border-color:"+ event.backgroundColor +";");
					element.find(".fc-daygrid-event-dot").remove();

					if ( event._def.extendedProps.is_redirect === 'yes' ) {
						element.attr("href", event.url);
						if (event._def.extendedProps.external === "on") {
							element.attr("target", "_blank");
						}

						if (event._def.extendedProps.nofollow === "on") {
							element.attr("rel", "nofollow");
						}

						if (event._def.extendedProps.custom_attributes !== '' ) {
							$.each(event._def.extendedProps.custom_attributes, function(index,item){
								element.attr(item.key, item.value);
							});
						}

						if (element.hasClass('fc-list-item')) {
							element.removeAttr("href target rel");
							element.removeClass("fc-has-url");
							element.css('cursor', 'default');
						}
					}
					else {
						element.attr("href", "javascript:void(0);");
						element.click(function (e) {
							e.preventDefault();
							e.stopPropagation();
							var startDate = event.start,
								timeFormate = time_format ? "H:mm" : "h:mm A",
								endDate = event.end,
								startSelector = $("span.eaelec-event-date-start"),
								endSelector = $("span.eaelec-event-date-end"),
								modalFooterLink = $(".eaelec-modal-footer a");

							if (event.allDay) {
								var newEnd = moment(endDate).subtract(1, "days");
								endDate = newEnd._d;
								timeFormate = " ";
							}

							var startYear = moment(startDate).format("YYYY"),
								endYear = moment(endDate).format("YYYY"),
								yearDiff = endYear > startYear,
								startView = "",
								endView = "";

							startSelector.html(" ");
							endSelector.html(" ");
							ecModal
								.addClass("eael-ec-popup-ready")
								.removeClass("eael-ec-modal-removing");

							if (
								event.allDay &&
								moment(startDate).format("MM-DD-YYYY") ===
								moment(endDate).format("MM-DD-YYYY")
							) {
								startView = moment(startDate).format("MMM Do");
								if (moment(startDate).isSame(Date.now(), "day") === true) {
									startView = translate.today;
								} else if (
									moment(startDate).format("MM-DD-YYYY") ===
									moment(new Date()).add(1, "days").format("MM-DD-YYYY")
								) {
									startView = translate.tomorrow;
								}
							} else {
								if (moment(event.start).isSame(Date.now(), "day") === true) {
									startView =
										translate.today + " " + moment(event.start).format(timeFormate);
								}
								if (
									moment(startDate).format("MM-DD-YYYY") ===
									moment(new Date()).add(1, "days").format("MM-DD-YYYY")
								) {
									startView =
										translate.tomorrow +
										" " +
										moment(event.start).format(timeFormate);
								}

								if (
									moment(startDate).format("MM-DD-YYYY") <
									moment(new Date()).format("MM-DD-YYYY") ||
									moment(startDate).format("MM-DD-YYYY") >
									moment(new Date()).add(1, "days").format("MM-DD-YYYY")
								) {
									startView = moment(event.start).format("MMM Do " + timeFormate);
								}

								startView = yearDiff ? startYear + " " + startView : startView;

								if (moment(endDate).isSame(Date.now(), "day") === true) {
									if (moment(startDate).isSame(Date.now(), "day") !== true) {
										endView =
											translate.today + " " + moment(endDate).format(timeFormate);
									} else {
										endView = moment(endDate).format(timeFormate);
									}
								}

								if (
									moment(startDate).format("MM-DD-YYYY") !==
									moment(new Date()).add(1, "days").format("MM-DD-YYYY") &&
									moment(endDate).format("MM-DD-YYYY") ===
									moment(new Date()).add(1, "days").format("MM-DD-YYYY")
								) {
									endView =
										translate.tomorrow + " " + moment(endDate).format(timeFormate);
								}
								if (
									moment(startDate).format("MM-DD-YYYY") ===
									moment(new Date()).add(1, "days").format("MM-DD-YYYY") &&
									moment(endDate).format("MM-DD-YYYY") ===
									moment(new Date()).add(1, "days").format("MM-DD-YYYY")
								) {
									endView = moment(endDate).format(timeFormate);
								}
								if (
									moment(endDate).diff(moment(startDate), "days") > 0 &&
									endSelector.text().trim().length < 1
								) {
									endView = moment(endDate).format("MMM Do " + timeFormate);
								}

								if (
									moment(startDate).format("MM-DD-YYYY") ===
									moment(endDate).format("MM-DD-YYYY")
								) {
									endView = moment(endDate).format(timeFormate);
								}

								endView = yearDiff ? endYear + " " + endView : endView;
							}

							if (
								event.extendedProps.hideEndDate !== undefined &&
								event.extendedProps.hideEndDate === "yes"
							) {
								endSelector.html(" ");
							} else {
								endSelector.html(endView != "" ? "- " + endView : "");
							}
							startSelector.html('<i class="eicon-calendar"></i> ' + startView);

							$(".eaelec-modal-header h2").html(event.title);
							$(".eaelec-modal-body").html(event.extendedProps.description);
							if (event.extendedProps.description.length < 1) {
								$(".eaelec-modal-body").css("height", "auto");
							} else {
								$(".eaelec-modal-body").css("height", "300px");
							}

							if (event.extendedProps.hide_details_link !== 'yes' ){
								modalFooterLink.attr("href", event.url).css("display", "block");
							}else {
								modalFooterLink.css("display", "none");
							}

							if (event.extendedProps.external === "on") {
								modalFooterLink.attr("target", "_blank");
							}
							if (event.extendedProps.nofollow === "on") {
								modalFooterLink.attr("rel", "nofollow");
							}
							if (event.extendedProps.custom_attributes != '' ) {
								$.each(event.extendedProps.custom_attributes, function(index,item){
									modalFooterLink.attr(item.key, item.value);
								});
							}

							// Popup color
							$(".eaelec-modal-header").css(
								"border-left",
								"5px solid " + event.borderColor
							);

							// Popup color
							$(".eaelec-modal-header").css(
								"border-left",
								"5px solid " + event.borderColor
							);
						});
					}
				},
				eventWillUnmount: function(arg) {}
			});

		function refreshPopUpDetailsLink(){
			var modalFooter = $(".eaelec-modal-footer"),
				modalFooterClass = modalFooter.find('a').attr('class'),
				modalFooterText = modalFooter.find('a').text();
			modalFooter.html('<a class="'+modalFooterClass+'">'+modalFooterText+'</a>');
		}

		CloseButton.on("click", function (event) {
			event.stopPropagation();
			ecModal
				.addClass("eael-ec-modal-removing")
				.removeClass("eael-ec-popup-ready");
			refreshPopUpDetailsLink();
		});

		$(document).on("click", function (event) {
			if (event.target.closest(".eaelec-modal-content")) return;
			if (ecModal.hasClass("eael-ec-popup-ready")) {
				ecModal
					.addClass("eael-ec-modal-removing")
					.removeClass("eael-ec-popup-ready");
				refreshPopUpDetailsLink();
			}
		});

		calendar.render();
		const observer = new IntersectionObserver((entries) => {
			for (const entry of entries) {
				window.dispatchEvent(new Event('resize'));
				setTimeout(function (){
					window.dispatchEvent(new Event('resize'));
				},200)
			}
		});
		observer.observe(element[0]);

		ea.hooks.addAction("eventCalendar.reinit", "ea", () => {
			calendar.today();
		});
	}
	else{
		let table = $scope[0].querySelector(".eael-event-calendar-table");
		let search = $scope[0].querySelector(".eael-event-calendar-table-search");
		let pagination = $scope[0].querySelector(
			".eael-event-calendar-pagination"
		);
		let classCollection = {};
		// if (!ea.isEditMode && table !== null) {
			// search
			initTableSearch(table, search, pagination);

			// sort
			initTableSort(table, pagination, classCollection);

			// paginated table
			initTablePagination(table, pagination, classCollection);
		// }
		// frontend - search
		function initTableSearch(table, search, pagination) {
			if (search) {
				search.addEventListener("input", (e) => {
					let input = e.target.value.toLowerCase();
					let hasSort = table.classList.contains(
						"ea-ec-table-sortable"
					);
					let offset =
						table.rows[0].parentNode.tagName.toLowerCase() == "thead" ? 1 : 0;

					if (table.rows.length > 1) {
						if (input.length > 0) {
							if (hasSort) {
								table.classList.add("ea-ec-table-unsortable");
							}

							if (pagination && pagination.innerHTML.length > 0) {
								pagination.style.display = "none";
							}

							for (let i = offset; i < table.rows.length; i++) {
								let matchFound = false;

								if (table.rows[i].cells.length > 0) {
									for (let j = 0; j < table.rows[i].cells.length; j++) {
										if (
											table.rows[i].cells[j].textContent
												.toLowerCase()
												.indexOf(input) > -1
										) {
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
								table.classList.remove("ea-ec-table-unsortable");
							}

							if (pagination && pagination.innerHTML.length > 0) {
								pagination.style.display = "";

								let paginationType = pagination.classList.contains(
									"ea-ec-table-pagination-button"
								)
									? "button"
									: "select";
								let currentPage =
									paginationType == "button"
										? pagination.querySelector(
											".ea-advanced-data-table-pagination-current"
										).dataset.page
										: pagination.querySelector("select").value;
								let startIndex =
									(currentPage - 1) * table.dataset.itemsPerPage + 1;
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

		// frontend - sort
		function initTableSort(table, pagination, classCollection) {
			if (table.classList.contains("ea-ec-table-sortable")) {
				table.addEventListener("click", (e) => {
					let target = null;

					if (e.target.tagName.toLowerCase() === "th") {
						target = e.target;
					}

					if (e.target.parentNode.tagName.toLowerCase() === "th") {
						target = e.target.parentNode;
					}

					if (e.target.parentNode.parentNode.tagName.toLowerCase() === "th") {
						target = e.target.parentNode.parentNode;
					}

					if (target === null) {
						return;
					}

					let index = target.cellIndex;
					let currentPage = 1;
					let startIndex = 1;
					let endIndex = table.rows.length - 1;
					let sort = "";
					let classList = target.classList;
					let collection = [];
					let origTable = table.cloneNode(true);

					if (classList.contains("asc")) {
						target.classList.remove("asc");
						target.classList.add("desc");
						sort = "desc";
					} else if (classList.contains("desc")) {
						target.classList.remove("desc");
						target.classList.add("asc");
						sort = "asc";
					} else {
						target.classList.add("asc");
						sort = "asc";
					}

					if (pagination && pagination.innerHTML.length > 0) {
						let paginationType = pagination.classList.contains(
							"ea-ec-pagination-button"
						)
							? "button"
							: "select";
						console.log(pagination.classList)
						currentPage =
							paginationType == "button"
								? pagination.querySelector(
									".ea-ec-table-pagination-current"
								).dataset.page
								: pagination.querySelector("select").value;
						startIndex = (currentPage - 1) * table.dataset.itemsPerPage + 1;
						endIndex =
							endIndex - (currentPage - 1) * table.dataset.itemsPerPage >=
							table.dataset.itemsPerPage
								? currentPage * table.dataset.itemsPerPage
								: endIndex;
					}

					// collect header class
					classCollection[currentPage] = [];

					table.querySelectorAll("th").forEach((el) => {
						if (el.cellIndex != index) {
							el.classList.remove("asc", "desc");
						}

						classCollection[currentPage].push(
							el.classList.contains("asc")
								? "asc"
								: el.classList.contains("desc")
									? "desc"
									: ""
						);
					});

					// collect table cells value
					for (let i = 1; i <= table.rows.length-1; i++) {
						let value;
						let cell = table.rows[i].cells[index];

						let data = cell.innerText;

						var regex = new RegExp(
							"([0-9]{4}[-./*](0[1-9]|1[0-2])[-./*]([0-2]{1}[0-9]{1}|3[0-1]{1})|([0-2]{1}[0-9]{1}|3[0-1]{1})[-./*](0[1-9]|1[0-2])[-./*][0-9]{4})"
						);

						if (data.match(regex)) {
							dataString = data.split(/[\.\-\/\*]/);
							if (dataString[0].length == 4) {
								data = dataString[0] + "-" + dataString[1] + "-" + dataString[2];
							} else {
								data = dataString[2] + "-" + dataString[1] + "-" + dataString[0];
							}
							value = Date.parse(data);
						} else if (isNaN(parseInt(data))) {
							value = data.toLowerCase();
						} else {
							value = parseFloat(data);
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
						table.rows[1 + index].innerHTML =
							origTable.rows[row.index].innerHTML;
					});
				});
			}
		}

		// frontend - pagination
		function initTablePagination(table, pagination, classCollection) {
			if (table.classList.contains("ea-ec-table-paginated")) {
				let paginationHTML = "";
				let paginationType = pagination.classList.contains(
					"ea-ec-pagination-button"
				)
					? "button"
					: "select";
				let currentPage = 1;
				let startIndex =
					table.rows[0].parentNode.tagName.toLowerCase() === "thead" ? 1 : 0;
				let endIndex = currentPage * table.dataset.itemsPerPage;
				let maxPages = Math.ceil(
					(table.rows.length - 1) / table.dataset.itemsPerPage
				);
				pagination.insertAdjacentHTML(
					"beforeend", '');      // insert pagination

				if (maxPages > 1) {
					if (paginationType == "button") {
						for (let i = 1; i <= maxPages; i++) {
							paginationHTML += `<a href="#" data-page="${i}" class="${
								i == 1 ? "ea-ec-table-pagination-current" : ""
							}">${i}</a>`;
						}

						pagination.insertAdjacentHTML(
							"beforeend",
							`<a href="#" data-page="1">&laquo;</a>${paginationHTML}<a href="#" data-page="${maxPages}">&raquo;</a>`
						);
					} else {
						for (let i = 1; i <= maxPages; i++) {
							paginationHTML += `<option value="${i}">${i}</option>`;
						}

						pagination.insertAdjacentHTML(
							"beforeend",
							`<select>${paginationHTML}</select>`
						);
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
							offset =
								table.rows[0].parentNode.tagName.toLowerCase() == "thead" ? 1 : 0;
							startIndex =
								(currentPage - 1) * table.dataset.itemsPerPage + offset;
							endIndex = currentPage * table.dataset.itemsPerPage;

							pagination
								.querySelectorAll(".ea-advanced-data-table-pagination-current")
								.forEach((el) => {
									el.classList.remove(
										"ea-ec-table-pagination-current"
									);
								});

							pagination
								.querySelectorAll(`[data-page="${currentPage}"]`)
								.forEach((el) => {
									el.classList.add("ea-ec-table-pagination-current");
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
							offset =
								table.rows[0].parentNode.tagName.toLowerCase() == "thead" ? 1 : 0;
							startIndex =
								(currentPage - 1) * table.dataset.itemsPerPage + offset;
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
	}
};

jQuery(window).on("elementor/frontend/init", function () {

	if (ea.elementStatusCheck('eaelEventCalendar')) {
		return false;
	}

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-event-calendar.default",
		EventCalendar
	);
});