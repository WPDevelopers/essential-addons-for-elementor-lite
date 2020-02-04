var EventCalendar = function($scope, $) {

	var Calendar = FullCalendar.Calendar;
	var element	= $(".eael-event-calendar-cls", $scope),
		CloseButton	= $(".eaelec-modal-close", $scope).eq(0),
		ecModal		= $('#eaelecModal', $scope),
		eventAll	= element.data("events"),
		daysWeek 	= element.data("days_week"),
		monthNames	= element.data("month_names"),
		firstDay	= element.data("first_day"),
		calendarID	= element.data("cal_id");
		calendarEl = document.getElementById('eael-event-calendar-'+ calendarID);
	var calendar = new Calendar(calendarEl, {
		plugins: ['dayGrid', 'timeGrid', 'list' ],
		editable: false,
		selectable: false,
		draggable: false,
		firstDay: firstDay,
		slotLabelFormat: "HH:mm",
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
		dayNamesShort: daysWeek,
		monthNames: monthNames,
		eventRender: calendarEventRender
	});
	calendar.render();
	var calendarEventRender = function( event, element ) {

	};

};

jQuery(window).on("elementor/frontend/init", function() {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-event-calendar.default",
		EventCalendar
	);
});