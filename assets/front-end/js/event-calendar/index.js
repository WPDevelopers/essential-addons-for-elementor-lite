jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/eael-event-calendar.default', function ($scope, $) {
        
        var element = $scope.find('.eael-event-calendar-cls');
        var eventAll = element.data('events');
        var daysWeek = element.data('days_week');
        var monthNames = element.data('month_names');
        var firstDay = element.data('first_day');
        var eventBgColor = element.data('event_bg_color');
        var eaelevModal = document.getElementById("eaelecModal");
        var eaelevSpan = document.getElementsByClassName("eaelec-modal-close")[0];
       
        //daysWeek1 = ['s1', 's2'];
        //alert('Hello there');

        var calendar = $('#eael-event-calendar').fullCalendar({
            editable:false,
            draggable:false,
            firstDay: firstDay,
            slotLabelFormat:"HH:mm",
            nextDayThreshold : "00:00:00",
            header:{
            left:'prev,next today',
            center:'title',
            right:'month,agendaWeek,agendaDay'
            },
            events: eventAll,
            selectable:true,
            selectHelper:true,
            dayNamesShort: daysWeek,
            monthNames: monthNames,
            eventRender: function (event, element) {
                element.attr('href', 'javascript:void(0);');
                element.click(function() {
                    eaelevModal.style.display = "block";
                    /*
                    $("#startTime").html(moment(event.start).format('MMM Do h:mm A'));
                    $("#endTime").html(moment(event.end).format('MMM Do h:mm A'));
                    */
                    $("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> ' + moment(event.start).format('MMM Do, h:mm A'));
                    $("span.eaelec-event-date-end").html('<i class="eicon-calendar"></i> ' + moment(event.end).format('MMM Do, h:mm A'));
                    $(".eaelec-modal-header h2").html(event.title);
                    $(".eaelec-modal-body p").html(event.description);
                    $(".eaelec-modal-footer a").attr('href', event.url);

                    // Popup color
                    $('.eaelec-modal-close').css('background-color', event.color );
                    $('.eaelec-modal-header').css('border-left', '5px solid ' + event.color );
                    $('.eaelec-modal-header span').css('color', event.color );
                });
            }
        });

        $('.fc-right .fc-button-group').css('display', 'none');
        $('.fc-right').append('<select class="eaelec_select_view form-control">' +
                                '<option value="month">Month</option>' +
                                '<option value="week">Week</option>' +
                                '<option value="day">Day</option>' +
                                '</select>');
        
        $(".eaelec_select_view").on("change", function(event) {
            if($(this).val()==='month'){
                $('#eael-event-calendar').fullCalendar('changeView', 'month');
            }
            if($(this).val()==='week'){
                $('#eael-event-calendar').fullCalendar('changeView', 'agendaWeek');
            }
            if($(this).val()==='day'){
                $('#eael-event-calendar').fullCalendar('changeView', 'agendaDay');
            }
            //$('#calendar').fullCalendar('gotoDate', "2018-"+this.value+"-1");
        });

        // When the user clicks on <span> (x), close the modal
        eaelevSpan.onclick = function() {
            eaelevModal.style.display = "none";
        }
    });
});