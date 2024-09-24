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
		multiDaysEventDayCount = typeof element.data("multidays_event_day_count") !== 'undefined' ? element.data("multidays_event_day_count") : 0,
		eventLimit = element.data("event_limit"),
		popupDateFormate = element.data("popup_date_formate"),
		monthColumnHeaderFormat = element.data("monthcolumnheaderformat"),
		weekColumnHeaderFormat = element.data("weekcolumnheaderformat"),
		time_format = element.data("time_format") == "yes" ? true : false;

	if ( wrapper.hasClass( 'layout-calendar' ) ){
		var calendar = new Calendar(
			$scope[0].querySelector(".eael-event-calendar-cls"), {
				views: {
					month: { // will produce something like "Tuesday, September 18, 2018"
						dayHeaderContent: (args) => {
							console.log('monthColumnHeaderFormat', monthColumnHeaderFormat)
							if (args.view.type === 'dayGridMonth' && monthColumnHeaderFormat ) {
								return moment(args.date).format(monthColumnHeaderFormat)
							}
						},
					},
					week: {
						dayHeaderContent: (args) => {
							console.log('weekColumnHeaderFormat', weekColumnHeaderFormat)

							if (weekColumnHeaderFormat) {
								return moment(args.date).format(weekColumnHeaderFormat);
							}
						},
					},
				},
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

					if(multiDaysEventDayCount && event.endStr > event.startStr){
						let startDate 	= typeof event.startStr !== 'undefined' ? new Date(event.startStr) : '';
						let endDate 	= typeof event.endStr 	!== 'undefined' ? new Date(event.endStr) : '';

						let oneDay = 24 * 60 * 60 * 1000;
						let totalDays = (endDate - startDate ) / oneDay;

						let currentCellDate = $(element).prevAll('tr.fc-list-day:first')?.data('date');
							currentCellDate	= typeof currentCellDate !== 'undefined' ? new Date(currentCellDate) : '';

						let eventDayCount 	= startDate && currentCellDate ? Math.ceil((currentCellDate - startDate) / oneDay) + 1 : '';
						let eventTitle = `${event.title} (Day ${eventDayCount}/${totalDays} )`;
						element.find(".fc-list-event-title a").text(eventTitle);
					}

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
                            moment.locale(locale);
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
									startView = moment(event.start).format(popupDateFormate + " " + timeFormate);
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
									endView = moment(endDate).format(popupDateFormate + " " + timeFormate);
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

						if ( $(".eael-event-calendar-cls", $scope).data('hidedetailslink') !== 'yes'){
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
			modalFooterText = $(".eael-event-calendar-cls", $scope).attr( 'data-detailsButtonText' );
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

		eael.hooks.addAction("eventCalendar.reinit", "ea", () => {
			calendar.today();
		});
	}
	else{
		let table = $('.eael-event-calendar-table', wrapper),
			pagination = table.hasClass('ea-ec-table-paginated'),
			itemPerPage = pagination ? table.data('items-per-page') : 10,
			sortColumn = $('.eael-ec-event-date', table).index();

		$(".eael-event-calendar-table", wrapper).fancyTable({
			sortColumn: sortColumn >=0 ? sortColumn : 0,
			pagination: pagination,
			perPage:itemPerPage,
			globalSearch:true,
			searchInput:$(".ea-ec-search-wrap", wrapper),
			paginationElement:$(".eael-event-calendar-pagination", wrapper),
		});
	}
};

jQuery(window).on("elementor/frontend/init", function () {

	if (eael.elementStatusCheck('eaelEventCalendar')) {
		return false;
	}

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-event-calendar.default",
		EventCalendar
	);
});