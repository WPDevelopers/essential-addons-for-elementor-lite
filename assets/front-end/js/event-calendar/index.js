var EventCalendar = function($scope, $) {
	var Calendar = FullCalendar.Calendar;
	var element = $(".eael-event-calendar-cls", $scope),
		CloseButton = $(".eaelec-modal-close", $scope).eq(0),
		ecModal = $("#eaelecModal", $scope),
		eventAll = element.data("events"),
		firstDay = element.data("first_day"),
		calendarID = element.data("cal_id"),
		calendarEl = document.getElementById("eael-event-calendar-" + calendarID);

	var calendar = new Calendar(calendarEl, {
		plugins: ["dayGrid", "timeGrid", "list"],
		editable: false,
		selectable: false,
		draggable: false,
		firstDay: firstDay,
		eventTimeFormat: {
			hour: '2-digit',
			minute: '2-digit',
			meridiem: 'short'
		},
		nextDayThreshold: "00:00:00",
		header: {
			left: "prev,next today",
			center: "title",
			right: "timeGridDay,timeGridWeek,dayGridMonth,listWeek"
		},
		buttonText: {
			today: "Today"
		},
		allDayText: "All day",
		events: eventAll,
		selectHelper: true,
		eventLimit: 3,
		eventRender: function(info) {
			var element = $(info.el),
				event = info.event;

			element.attr("href", "javascript:void(0);");
			element.click(function(e) {
				e.preventDefault();
				var startDate = event.start,
					timeFormate = "h:mm A",
					endDate = event.end,
					startSelector = $("span.eaelec-event-date-start"),
					endSelector = $("span.eaelec-event-date-end");

				if (event.allDay === "yes") {
					var newEnd = moment(endDate).subtract(1, "days");
					endDate = newEnd._d;
					timeFormate = " ";
				}

				startSelector.html(" ");
				endSelector.html(" ");
				ecModal.addClass("eael-ec-popup-ready").removeClass("eael-ec-modal-removing");

				if (event.allDay === "yes" && moment(startDate).format("MM-DD-YYYY") === moment(endDate).format("MM-DD-YYYY")) {
					var allDayTime = moment(startDate).format("MMM Do");
					if (moment(startDate).isSame(Date.now(), "day") === true) {
						allDayTime = 'Today';
					}else if(moment(startDate).format("MM-DD-YYYY") === moment(new Date()).add(1, "days").format("MM-DD-YYYY")){
						allDayTime = 'Tomorrow';
					}
					startSelector.html('<i class="eicon-calendar"></i> ' + allDayTime);
				} else {
					if (moment(event.start).isSame(Date.now(), "day") === true) {
						startSelector.html('<i class="eicon-calendar"></i> Today, ' + moment(event.start).format(timeFormate));
					}
					if (
						moment(startDate).format("MM-DD-YYYY") ===
						moment(new Date())
							.add(1, "days")
							.format("MM-DD-YYYY")
					) {
						startSelector.html('<i class="eicon-calendar"></i> Tomorrow, ' + moment(event.start).format(timeFormate));
					}

					if (
						moment(startDate).format("MM-DD-YYYY") < moment(new Date()).format("MM-DD-YYYY") ||
						moment(startDate).format("MM-DD-YYYY") >
							moment(new Date())
								.add(1, "days")
								.format("MM-DD-YYYY")
					) {
						startSelector.html('<i class="eicon-calendar"></i> ' + moment(event.start).format("MMM Do, " + timeFormate));
					}

					if (moment(endDate).isSame(Date.now(), "day") === true) {
						if (moment(startDate).isSame(Date.now(), "day") !== true) {
							endSelector.html("- Today, " + moment(endDate).format(timeFormate));
						} else {
							endSelector.html("- " + moment(endDate).format(timeFormate));
						}
					}

					if (
						moment(startDate).format("MM-DD-YYYY") !==
							moment(new Date())
								.add(1, "days")
								.format("MM-DD-YYYY") &&
						moment(endDate).format("MM-DD-YYYY") ===
							moment(new Date())
								.add(1, "days")
								.format("MM-DD-YYYY")
					) {
						endSelector.html("- Tomorrow, " + moment(endDate).format(timeFormate));
					}
					if (
						moment(startDate).format("MM-DD-YYYY") ===
							moment(new Date())
								.add(1, "days")
								.format("MM-DD-YYYY") &&
						moment(endDate).format("MM-DD-YYYY") ===
							moment(new Date())
								.add(1, "days")
								.format("MM-DD-YYYY")
					) {
						endSelector.html("- " + moment(endDate).format(timeFormate));
					}
					if (moment(endDate).diff(moment(startDate), "days") > 0 && endSelector.text().trim().length < 1) {
						endSelector.html("- " + moment(endDate).format("MMM Do, " + timeFormate));
					}

					if (moment(startDate).format("MM-DD-YYYY") === moment(endDate).format("MM-DD-YYYY")) {
						endSelector.html("- " + moment(endDate).format(timeFormate));
					}
				}

				$(".eaelec-modal-header h2").html(event.title);
				$(".eaelec-modal-body p").html(event.extendedProps.description);
				if(event.extendedProps.description.length<1){
					$(".eaelec-modal-body").css("height", "auto");
				}else {
					$(".eaelec-modal-body").css("height", "300px");
				}

				$(".eaelec-modal-footer a").attr("href", event.url);
				
				if (event.extendedProps.external === "on") {
					$(".eaelec-modal-footer a").attr("target", "_blank");
				}
				if (event.extendedProps.nofollow === "on") {
					$(".eaelec-modal-footer a").attr("rel", "nofollow");
				}
				if (event.url == "") {
					$(".eaelec-modal-footer a").css("display", "none");
				}

				// Popup color
				$(".eaelec-modal-header").css("border-left", "5px solid " + event.borderColor);
			});
		}
	});

	CloseButton.on("click", function() {
		ecModal.addClass("eael-ec-modal-removing").removeClass("eael-ec-popup-ready");
	});

	calendar.render();
};

jQuery(window).on("elementor/frontend/init", function() {
	elementorFrontend.hooks.addAction("frontend/element_ready/eael-event-calendar.default", EventCalendar);
});
