jQuery(document).ready(function() {
    
});

jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/eael-event-calendar.default', function ($scope, $) {
        
        var element = $scope.find('.calendar2');
        var eventAll = element.data('events');
        //alert(eventAll);
        var calendar = jQuery('#calendar').fullCalendar({
            editable:true,
            header:{
            left:'prev,next today',
            center:'title',
            right:'month,agendaWeek,agendaDay'
            },
            events: eventAll,
            selectable:true,
            selectHelper:true
        });
    });
});