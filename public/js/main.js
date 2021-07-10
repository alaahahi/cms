
var service,clinet,data,title,date_type,date_status,medical_review_type,doctor_name,note,phone,mobile, _start,_end,x,x1,color,folder;
var url_all_clinet= url+'all_clinet';
//var url_all_card = url+'all_card';
var url_all_services = url+'all_services';
$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function () {  
            $.getJSON(url_all_clinet, function (data) {
            $.each(data, function (index, value) {
            $('#all_clinet').append('<option value="' + value.id + '">' + value.full_name + '</option>');
             });
            });
            
            //$.getJSON(url_all_card, function (data) {
            //$.each(data, function (index, value) {
            //$('#all_card').append('<option value="' + value.id + '">' + value.card_number + '</option>');
             //});
            //});
            
            $.getJSON(url_all_services, function (data) {
                $.each(data, function (index, value) {
                $('#all_services').append('<option style="background-Color:'+value.color+'" value="' + value.id + '">' + value.title + '</option>');
                 });
                });
                $( "#delete" ).on('click',function()  {
                    deleteEvent(id);
                     $('#Modal').modal('hide'); 
                     $( "#delete" ).hide();
                     $( "#edit" ).hide();
                     modelReset ();
                    });
        $( "#edit" ).on('click',function()  {
            editEvent(eventedit,id);
             $('#Modal').modal('hide'); 
             $( "#delete" ).hide();
             $( "#edit" ).hide();
             modelReset ();
                    });
                  $( "#searchQuery" ).focus(function() {
                    $('.fc-listMonth-button').click()
                  });
                 
                  $('#searchQuery').on('input', function() {
                    refresh(true);
        });
        $(".form .fa").click(function() { 
            if ($(".form ").hasClass('hover')) {
                $(".form ").removeClass("hover");   
                $(".fc-month-button").click();
                
            }else{
                $(".form ").addClass("hover");
                $(".form input").focus();   
            
            }
});
                $('#save').on('click',function() 
                {
                    service=$('#all_services').val();
                    clinet =$('#all_clinet').val();
                    note=$('#Note_text').val();
                    title =$('#all_clinet').find('option:selected').text()+" - "+$('#all_services').find('option:selected').text()+" - "+note;
                        if (clinet) {
                    var eventData = {
                        service: service,
                        clinet:clinet,
                        note:note,
                        color:$('#all_services').find("option:selected").css('backgroundColor'),
                        start: _start,
                        end: _end,
                        title:title
                    };
                    addNewEvent(eventData, function () { $('#calendar').fullCalendar('unselect'); });
                    $('#Modal').modal('hide');
                    modelReset ();
        
                }else {
                    alert("Please Enter  Name to date");
                }
                $('#calendar').fullCalendar('unselect');
                });

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listMonth,listDay,listWeek'
            },
            views: {
                listMonth: { buttonText: 'list month' },
                listDay: { buttonText: 'list day' },
                listWeek: { buttonText: 'list week' },
				month: {
               eventLimit: 1 // adjust to 6 only for agendaWeek/agendaDay
               }, agendaWeek: {
                   eventLimit: 1 // adjust to 6 only for agendaWeek/agendaDay
                }
				
				
            },
       
            navLinks: true, // can click day/week names to navigate views
            weekNumbers: false,
            weekNumbersWithinDays: true,
            weekNumberCalculation: 'ISO',
            nowIndicator: true,
			minTime: '10:00',
            businessHours: [
                {
                    dow: [0, 1, 2, 3,4,6], // sunday, Monday, Tuesday, Wednesday
                    start: '10:00', 
                    end: '24:00' 
                }
                //,{dow: [4], // Thursdaystart: '10:00', // 10amend: '16:00' // 4pm}
            ],
            selectable: true,
            selectHelper: true,
            select: function (start, end) 
            {
                if(x!=1){
                modelReset ();
                btnModelControl("new");
                $("#ModalTitle").text("Add New Date on "+start.toLocaleString().substring(0,start.toLocaleString().length - 8));
                $('#Modal').on('shown.bs.modal', function (e) {$( "#full_name" ).focus();})
                _start =start;
                _end = end;
            }
                
            },
            allDaySlot:false,
            editable: false,
            eventLimit: true,
         //   views: {
               // month: {
              //    eventLimit: 1 // adjust to 6 only for agendaWeek/agendaDay
               // }, agendaWeek: {
                //    eventLimit: 1 // adjust to 6 only for agendaWeek/agendaDay
               //   }
             // },
            firstDay:6,
            
            events: url+'all_calendar',
            
   
            eventClick: function (event, jsEvent, view) {
                    $('#all_services').val(event.service);
                    $('#all_clinet').val(event.clinet);
                    $('#Note_text').val(event.note);
                    btnModelControl("edit");
                    $("#ModalTitle").text("Date Informtion "+event.clinet);
                     id=event.id;
                    eventedit=event;
                    return;
            }
            ,
            eventRender: function(event, element, view) {
                if(view.type == 'month') {
                  //$(element).css("display", "none");
                } else {
                
                }
                element.appendTo("<i  class='btn btn-info btn-sm' id='"+event.id+"'>Add Event</i>");
                if (view.name == "month") {
                    // $('.shifts,.tasks,.shiftOut,.shiftIn').hide();
                    var CellDates = [];
                    var EventDates = [];
                    var EventCount = 0, i = 0;

                    $('.fc-month-view').find('.fc-day-number').each(function () {
                        CellDates[i] = $(this).text();
                          // alert( $(this).text());
                        i++;
                    });

                    for (j = 0; j < CellDates.length; j++) {
                        EventsPerDay = $(".fc-month-view>div>.fc-day-top-" + CellDates[j]).length;
                        $(".fc-month-view>div>.fc-day-top-" + CellDates[j]).html(EventsPerDay)
                    }}
                       
              },
              
            
              eventAfterAllRender: function (view) {
                // Count events
                var quantity = $('.fc-event').length;
               // $("html").html(quantity);
              // $( ".fc-event" ).css("color","#fff")
            },
            dayClick: function(date, jsEvent, view) 
            {
            
                
            if(view.name=="month"){
            
                x=1;
                var date = $.fullCalendar.moment(date);
                $('#calendar').fullCalendar('changeView', 'agendaDay');
                $('#calendar').fullCalendar('gotoDate',date);
            
            }
            else{
                x=0;
            }
                                
        }
                        });

        $('#locale-selector').on('change', function () {
            if (this.value) {
                $('#calendar').fullCalendar('option', 'locale', this.value);
            }
        });
        

    
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

    function editEvent(event, id) {
            
        service=$('#all_services').val();
        clinet =$('#all_clinet').val();
        note=$('#Note_text').val();
        type="update";
        title =$('#all_clinet').find('option:selected').text()+" - "+$('#all_services').find('option:selected').text()+" - "+note;
        if(!clinet) alert("Please Enter name");
        else{
        $.ajax({
            url: url+"action",
            type: 'post',
            dataType: 'json',
            data:{
            id:id,
            title: title,
            color:$('#all_services').find("option:selected").css('backgroundColor'),
            service:service,
            clinet:clinet,
            note:note,
            type:type
            },
            success: function () {
                refresh();
            },
            error: function () {
                $('#calendar').fullCalendar('refetchEvents');
                refresh();
            
            },
            complete: function () {
                
            }
        });
        
    }

    }

    function deleteEvent(id, callback) {
        type="delete";
        $.ajax({
            url: url+"action",
            type: 'post',
            dataType: 'json',
            data:
            {
            id:id,
            type:type
            },
            success: function () {
                refresh();
            },
            error: function () {
                refresh();
            },
            complete: function () {
                refresh();
            }
        });
    }
    function addNewEvent(eventData, callback) {
        title=eventData["title"];
        clinet=eventData["clinet"];
        service=eventData["service"];
        note = eventData["note"];
        color=eventData["color"];
        start=JSON.stringify(eventData["start"]).slice(1,-1);
        end=JSON.stringify(eventData["end"]).slice(1,-1);
        type="add";
        debugger;
        $.ajax({
            url: url+"action",
            type: 'post',
            dataType: 'json',
            data:{
                title:title,
                clinet:clinet,
                service:service,
                note:note,
                type:type,
                start:start,
                end:end,
                color:color
            },
            success: function () {
                refresh();
            },
            error: function () {
                console.log('error add, try again later');
                $('#calendar').fullCalendar('refetchEvents');
                refresh();
            },
            complete: function () {
                refresh();
            }

        });		
    }

    var isSearching = false;
    function refresh(isSearch) {
        if (isSearch) {
            isSearching = true;
        }
        $('#calendar').fullCalendar('refetchEvents');
      
    }

  
    $( "nav" ).dblclick(function() {
        $( "nav" ).slideUp();
      });
      $( ".div-Logo" ).dblclick(function() {
        $( "nav" ).slideDown();
      });
});
    function modelReset (){
        $('#note').val('');
        $('#all_services').val(1);
        $('#all_clinet').val(1);
    }
    function btnModelControl (btnModelControl){
        if(btnModelControl=="new"){
            $( "#delete" ).hide();
            $( "#edit" ).hide();
            $( "#save" ).show();
            $('#Modal').modal('show'); 
        }
        if(btnModelControl=="edit"){
            $( "#delete" ).show();
            $( "#edit" ).show();    
             $( "#save" ).hide();
            $('#Modal').modal('show');
        }
    }
    $('#calendar').fullCalendar( 'clientEvents', function(eventObj){
        if (eventObj.start.isSame('2019-2-2')) {
            
            return true;
            debugger;
        } else {
            return false; 
            console.log(eventObj);   
        }
    }).length;

     