jQuery(document).ready(function() {
    
});

jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/eael-event-calendar.default', function ($scope, $) {
        
        var element = $scope.find('.eael-event-calendar-cls');
        var eventAll = element.data('events');
        //alert(eventAll);
        var calendar = $('#eael-event-calendar').fullCalendar({
            editable:true,
            header:{
            left:'prev,next today',
            center:'title',
            right:'month,agendaWeek,agendaDay'
            },
            events: eventAll,
            selectable:true,
            selectHelper:true,
            eventRender: function (event, element) {
                element.attr('href', 'javascript:void(0);');
                element.click(function() {
                    /*$("#startTime").html(moment(event.start).format('MMM Do h:mm A'));
                    $("#endTime").html(moment(event.end).format('MMM Do h:mm A'));*/
                    $("#eventInfo").html(event.description);
                    $("#eventLink").attr('href', event.url);
                    $("#eventContent").dialog({ modal: true, title: event.title, width:350});
                });
            }
        });
    });
});