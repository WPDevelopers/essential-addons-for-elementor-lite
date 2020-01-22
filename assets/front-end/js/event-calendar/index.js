var EventCalendar = function($scope, $) {
	var element = $(".eael-event-calendar-cls", $scope),
		eventAll = element.data("events"),
		daysWeek = element.data("days_week"),
		monthNames = element.data("month_names"),
		firstDay = element.data("first_day"),
		ecModal = $('#eaelecModal'),
		CloseButton = $(".eaelec-modal-close").eq(0),
		calendarID = element.data("cal_id");

		
		$("#eael-event-calendar-" + calendarID).fullCalendar({
			editable: false,
			selectable: false,
			draggable: false,
			firstDay: firstDay,
			slotLabelFormat: "HH:mm",
			timeFormat: "hh:mm a",
			nextDayThreshold: "00:00:00",
			header: {
				left: "prev,next,today",
				center: "title",
				right: "agendaDay,agendaWeek,month",
			},
			buttonText: {
				today: "Today"
			},
			allDayText: "All day",
			events: eventAll,
			selectHelper: true,
			dayNamesShort: daysWeek,
			monthNames: monthNames,
			eventRender: function(event, element) {
				element.attr("href", "javascript:void(0);");
				element.click(function() {
					ecModal.addClass('open-this-event');
					ecModal.css('display', 'block');
					if (event.allDay == "yes") {
						$("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> ' + moment(event.start).format("MMM Do"));
					} else {
						if (moment(event.start).isSame(Date.now(), "day") == true) {
							$("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> Today, ' + moment(event.start).format("h:mm A"));
						}
						if (
							moment(event.start).format("MM-DD-YYYY") ==
							moment(new Date())
								.add(1, "days")
								.format("MM-DD-YYYY")
						) {
							$("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> Tomorrow, ' + moment(event.start).format("h:mm A"));
						}
						if (
							moment(event.start).format("MM-DD-YYYY") < moment(new Date()).format("MM-DD-YYYY") ||
							moment(event.start).format("MM-DD-YYYY") >
								moment(new Date())
									.add(1, "days")
									.format("MM-DD-YYYY")
						) {
							$("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> ' + moment(event.start).format("MMM Do, h:mm A"));
						}

						if (moment(event.end).isSame(Date.now(), "day") == true) {
							$("span.eaelec-event-date-end").html("- " + moment(event.end).format("h:mm A"));
						}
						if (
							moment(event.start).format("MM-DD-YYYY") !=
								moment(new Date())
									.add(1, "days")
									.format("MM-DD-YYYY") &&
							moment(event.end).format("MM-DD-YYYY") ==
								moment(new Date())
									.add(1, "days")
									.format("MM-DD-YYYY")
						) {
							$("span.eaelec-event-date-end").html("- Tomorrow, " + moment(event.end).format("h:mm A"));
						}
						if (
							moment(event.start).format("MM-DD-YYYY") ==
								moment(new Date())
									.add(1, "days")
									.format("MM-DD-YYYY") &&
							moment(event.end).format("MM-DD-YYYY") ==
								moment(new Date())
									.add(1, "days")
									.format("MM-DD-YYYY")
						) {
							$("span.eaelec-event-date-end").html("- " + moment(event.end).format("h:mm A"));
						}
						if (
							moment(event.end).format("MM-DD-YYYY") >
							moment(new Date())
								.add(1, "days")
								.format("MM-DD-YYYY")
						) {
							$("span.eaelec-event-date-end").html("- " + moment(event.end).format("MMM Do, h:mm A"));
						}

						if (
							moment(event.start).format("MM-DD-YYYY") >
								moment(new Date())
									.add(1, "days")
									.format("MM-DD-YYYY") &&
							moment(event.start).format("MM-DD-YYYY") == moment(event.end).format("MM-DD-YYYY")
						) {
							$("span.eaelec-event-date-end").html("- " + moment(event.end).format("h:mm A"));
						}
					}

					$(".eaelec-modal-header h2").html(event.title);
					$(".eaelec-modal-body p").html(event.description);
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
					$(".eaelec-modal-close").css("background-color", event.borderColor);
					$(".eaelec-modal-header").css("border-left", "5px solid " + event.borderColor);
					$(".eaelec-modal-header span").css("color", event.borderColor);
				});
			}
		});

		// When the user clicks on <span> (x), close the modal
		CloseButton.on('click', function() {
			ecModal.css('display', 'none');
		});
};

jQuery(window).on("elementor/frontend/init", function() {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-event-calendar.default",
		EventCalendar
	);
});