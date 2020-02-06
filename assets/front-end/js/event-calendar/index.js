var EventCalendar = function($scope, $) {

	var Calendar = FullCalendar.Calendar;
	var element	= $(".eael-event-calendar-cls", $scope),
		CloseButton	= $(".eaelec-modal-close", $scope).eq(0),
		ecModal		= $('#eaelecModal', $scope),
		eventAll	= element.data("events"),
		firstDay	= element.data("first_day"),
		calendarID	= element.data("cal_id");
		calendarEl = document.getElementById('eael-event-calendar-'+ calendarID);
	var calendar = new Calendar(calendarEl, {
		plugins: ['dayGrid', 'timeGrid', 'list' ],
		editable: false,
		selectable: false,
		draggable: false,
		firstDay: firstDay,
		timeFormat: "hh:mm a",
		nextDayThreshold: "00:00:00",
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
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
			element.click(function() {
				var endDate = event.end;
				var timeFormate = "h:mm A";
				if(event.allDay === "yes"){
					var newEnd = moment(endDate).subtract(1, "days");
					endDate = newEnd._d;
					timeFormate = " ";
				}
				var timeFormatLen = timeFormate.trim().length;
				ecModal.addClass('eael-ec-popup-ready').removeClass('eael-ec-modal-removing');
				if (event.allDay === "yes" && event.end === null) {
					$("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> ' + moment(event.start).format("MMM Do"));
				} else {
					if (moment(event.start).isSame(Date.now(), "day") == true) {
						$("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> Today, ' + moment(event.start).format(timeFormate));
					}
					if (
						moment(event.start).format("MM-DD-YYYY") ==
						moment(new Date())
							.add(1, "days")
							.format("MM-DD-YYYY")
					) {
						$("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> Tomorrow, ' + moment(event.start).format(timeFormate));
					}
					if (
						moment(event.start).format("MM-DD-YYYY") < moment(new Date()).format("MM-DD-YYYY") ||
						moment(event.start).format("MM-DD-YYYY") >
						moment(new Date())
							.add(1, "days")
							.format("MM-DD-YYYY")
					) {
						$("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> ' + moment(event.start).format("MMM Do, "+timeFormate));
					}

					if (moment(endDate).isSame(Date.now(), "day") == true && timeFormatLen>0) {
						$("span.eaelec-event-date-end").html("- " + moment(endDate).format(timeFormate));
					}
					if (
						moment(event.start).format("MM-DD-YYYY") !=
						moment(new Date())
							.add(1, "days")
							.format("MM-DD-YYYY") &&
						moment(endDate).format("MM-DD-YYYY") ==
						moment(new Date())
							.add(1, "days")
							.format("MM-DD-YYYY")
					) {
						$("span.eaelec-event-date-end").html("- Tomorrow, " + moment(endDate).format(timeFormate));
					}
					if (
						moment(event.start).format("MM-DD-YYYY") ==
						moment(new Date())
							.add(1, "days")
							.format("MM-DD-YYYY") &&
						moment(endDate).format("MM-DD-YYYY") ==
						moment(new Date())
							.add(1, "days")
							.format("MM-DD-YYYY")
					) {
						if(timeFormatLen>0){
							$("span.eaelec-event-date-end").html("- " + moment(endDate).format(timeFormate));
						}
					}
					if (
						moment(endDate).format("MM-DD-YYYY") >
						moment(new Date())
							.add(1, "days")
							.format("MM-DD-YYYY")
					) {
						$("span.eaelec-event-date-end").html("- " + moment(endDate).format("MMM Do, "+timeFormate));
					}

					if (
						moment(event.start).format("MM-DD-YYYY") >
						moment(new Date())
							.add(1, "days")
							.format("MM-DD-YYYY") &&
						moment(event.start).format("MM-DD-YYYY") == moment(endDate).format("MM-DD-YYYY")
					) {
						if(timeFormatLen>0){
							$("span.eaelec-event-date-end").html("- " + moment(endDate).format(timeFormate));
						}

					}
				}

				$(".eaelec-modal-header h2").html(event.title);
				$(".eaelec-modal-body p").html(event.extendedProps.description);
				$(".eaelec-modal-footer a").attr("href", event.url);
				if (event.external == "on") {
					$(".eaelec-modal-footer a").attr("target", "_blank");
				}
				if (event.nofollow == "on") {
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

	CloseButton.on('click', function() {
		ecModal.addClass('eael-ec-modal-removing').removeClass('eael-ec-popup-ready');
	});

	calendar.render();
};

jQuery(window).on("elementor/frontend/init", function() {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-event-calendar.default",
		EventCalendar
	);
});