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
        var calId = element.data('cal_id');
        
        //daysWeek1 = ['s1', 's2'];
        //alert('Hello there');
        
        var calendar = $('#eael-event-calendar-' + calId).fullCalendar({
            editable:false,
            selectable: false,
            draggable:false,
            firstDay: firstDay,
            slotLabelFormat:"HH:mm",
            timeFormat: 'hh:mm a',
            nextDayThreshold : "00:00:00",
            header:{
            left:'prev,next today',
            center:'title',
            right:'month,agendaWeek,agendaDay'
            },
            buttonText: {
                    today: 'Today' 
            },
            allDayText: 'All day',
            events: eventAll,
            selectHelper:true,
            dayNamesShort: daysWeek,
            monthNames: monthNames,
            eventRender: function (event, element) {
                element.attr('href', 'javascript:void(0);');
                element.click(function() {
                    eaelevModal.style.display = "block"; 
                    if(event.allDay=='yes'){
                        $("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> ' + moment(event.start).format('MMM Do'));
                    } else{
                        if(moment(event.start).isSame(Date.now(), 'day')==true){
                            $("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> Today, ' + moment(event.start).format('h:mm A'));
                        }
                        if(moment(event.start).format('MM-DD-YYYY')==moment(new Date()).add(1,'days').format('MM-DD-YYYY')){
                            $("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> Tomorrow, ' + moment(event.start).format('h:mm A'));
                        }
                        if((moment(event.start).format('MM-DD-YYYY') < moment(new Date()).format('MM-DD-YYYY')) || (moment(event.start).format('MM-DD-YYYY') > moment(new Date()).add(1,'days').format('MM-DD-YYYY'))){
                            $("span.eaelec-event-date-start").html('<i class="eicon-calendar"></i> ' + moment(event.start).format('MMM Do, h:mm A'));
                        }
                        
                        if(moment(event.end).isSame(Date.now(), 'day')==true){
                            $("span.eaelec-event-date-end").html('- ' + moment(event.end).format('h:mm A'));
                        }
                        if( (moment(event.start).format('MM-DD-YYYY')!=moment(new Date()).add(1,'days').format('MM-DD-YYYY')) && (moment(event.end).format('MM-DD-YYYY')==moment(new Date()).add(1,'days').format('MM-DD-YYYY'))){
                            $("span.eaelec-event-date-end").html('- Tomorrow, ' + moment(event.end).format('h:mm A'));
                        }
                        if( (moment(event.start).format('MM-DD-YYYY')==moment(new Date()).add(1,'days').format('MM-DD-YYYY')) && (moment(event.end).format('MM-DD-YYYY')==moment(new Date()).add(1,'days').format('MM-DD-YYYY'))){
                            $("span.eaelec-event-date-end").html('- ' + moment(event.end).format('h:mm A'));
                        }
                        if(moment(event.end).format('MM-DD-YYYY') > moment(new Date()).add(1,'days').format('MM-DD-YYYY')){
                            $("span.eaelec-event-date-end").html('- ' + moment(event.end).format('MMM Do, h:mm A'));
                        }

                        if( (moment(event.start).format('MM-DD-YYYY') > moment(new Date()).add(1,'days').format('MM-DD-YYYY')) && (moment(event.start).format('MM-DD-YYYY')==moment(event.end).format('MM-DD-YYYY'))){
                            $("span.eaelec-event-date-end").html('- ' + moment(event.end).format('h:mm A'));
                        }
                    }
                    
                    $(".eaelec-modal-header h2").html(event.title);
                    $(".eaelec-modal-body p").html(event.description);
                    $(".eaelec-modal-footer a").attr('href', event.url);
                    if(event.external == 'on'){
                        $(".eaelec-modal-footer a").attr('target', '_blank');
                    }
                    if(event.nofollow == 'on'){
                        $(".eaelec-modal-footer a").attr('rel', 'nofollow');
                    }
                    if(event.url == ''){
                        $(".eaelec-modal-footer a").css('display', 'none');
                    }
                    // Popup color
                    $('.eaelec-modal-close').css('background-color', event.borderColor );
                    $('.eaelec-modal-header').css('border-left', '5px solid ' + event.borderColor );
                    $('.eaelec-modal-header span').css('color', event.borderColor );
                });
            }
        });

        $('#eael-event-calendar-' + calId + ' .fc-right .fc-button-group').css('display', 'none');
        $('#eael-event-calendar-' + calId + ' .fc-right').append('<select id="eaelec-select-mwd-' + calId + '" class="eaelec-select-view form-control">' +
                                '<option value="month">Month</option>' +
                                '<option value="week">Week</option>' +
                                '<option value="day">Day</option>' +
                                '</select>');
        
        $("#eaelec-select-mwd-" + calId).on("change", function(event) {
            if($(this).val()==='month'){
                $('#eael-event-calendar-' + calId).fullCalendar('changeView', 'month');
            }
            if($(this).val()==='week'){
                $('#eael-event-calendar-' + calId).fullCalendar('changeView', 'agendaWeek');
            }
            if($(this).val()==='day'){
                $('#eael-event-calendar-' + calId).fullCalendar('changeView', 'agendaDay');
            }
        });

        // When the user clicks on <span> (x), close the modal
        eaelevSpan.onclick = function() {
            eaelevModal.style.display = "none";
        }

        function eaelecChkTomorrow(){
            var date1_tomorrow = new Date(date1.getFullYear(), date1.getMonth(), date1.getDate() + 1);
        }
    });
});