
var data,title,date_type,date_status,medical_review_type,doctor_name,note,phone,mobile, _start,_end,x,x1,color,folder;
var url_all_date_type= url+'all_date_type';
$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function () {  
        $.getJSON(url_all_date_type, function (data) {
        $.each(data, function (index, value) {
        $('#select').append('<option value="' + value.id + '">' + value.title + '</option>');
                                                });
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
    
                    date_type=$('#select').val();
                    full_name =$('#full_name').val();
                    note=$('#Note_text').val();
                    phone =$('#phone').val();
                    mobile =$('#mobile').val();
                    folder =$('#folder').val();
                    title =full_name+" - "+folder+" - "+phone+" - "+mobile+" - "+$('#select').find('option:selected').text() ;
                        if (full_name) {
                    var eventData = {
                        title: title,
                        full_name:full_name,
                        date_type:date_type,
                        date_status:date_status,
                        doctor_name:doctor_name,
                        medical_review_type:medical_review_type,
                        note:note,
                        start: _start,
                        end: _end,
                        phone:phone,
                        folder:folder,
                        mobile:mobile,
                        color:$('#date_status').find("option:selected").css('backgroundColor')
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
            slotDuration: '00:12:00',
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
                    $('#select').val(event.date_type);
                    $('#full_name').val(event.full_name);
                    $('#phone').val(event.phone);
                    $('#mobile').val(event.mobile);
                    $('#folder').val(event.folder);
                    $('#Note_text').val(event.note);
                    btnModelControl("edit");
                    $("#ModalTitle").text("Date Informtion "+event.full_name);
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
        date_type=$('#select').val();
        date_status=$('#date_status').val();
        doctor_name=$('#doctor_name').val();
        medical_review_type=$('#medical_review_type').val();
        full_name =$('#full_name').val();
        note=$('#Note_text').val();
        phone =$('#phone').val();
        mobile =$('#mobile').val();
        folder =$('#folder').val();
        title =full_name+" - "+folder+" - "+phone+" - "+mobile+" - "+$('#select').find('option:selected').text() +" - "+$('#doctor_name').find('option:selected').text()+" - "+$('#medical_review_type').find('option:selected').text();
        if(!full_name) alert("Please Enter name");
        else{
        $.ajax({
            url: url + 'edit/' + id,
            type: 'post',
            dataType: 'json',
            data:{
            title: title,color:$('#date_status').find("option:selected").css('backgroundColor'),
            full_name:full_name,
            date_type:date_type,
            date_status:date_status,
            doctor_name:doctor_name,
            medical_review_type:medical_review_type,
            note:note,phone:phone
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
        $.ajax({
            url: url + 'delete/' + id,
            dataType: 'json',
            contentType: 'application/json',
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
        full_name=eventData["full_name"];
        start=JSON.stringify(eventData["start"]).slice(1,-1);
        end=JSON.stringify(eventData["end"]).slice(1,-1);
        date_type = eventData["date_type"];
        date_status = eventData["date_status"];
        doctor_name = eventData["doctor_name"];
        medical_review_type = eventData["medical_review_type"];
        note = eventData["note"];
        color=eventData["color"];
        phone=eventData["phone"];
        mobile=eventData["mobile"];
        folder=eventData["folder"];
        type="add";
        $.ajax({
            url: url+"action",
            type: 'post',
            dataType: 'json',
            data:{type:type,title: title,full_name:full_name,folder:folder,start:start,end:end,date_type:date_type,date_status:date_status,doctor_name:doctor_name,medical_review_type:medical_review_type,note:note,color:color,phone:phone,mobile:mobile},
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
        //$('#calendar').fullCalendar('refetchEvents');
      
    }

  
    $( "nav" ).dblclick(function() {
        $( "nav" ).slideUp();
      });
      $( ".div-Logo" ).dblclick(function() {
        $( "nav" ).slideDown();
      });
});
    function modelReset (){
        $('#full_name').val('');
        $('#Note_text').val('');
        $('#phone').val('');
        $('#mobile').val('');
        $('#folder').val('');
        $('#doctor_name').val(1);
        $('#date_status').val(1);
        $('#select').val(1);
        $('#medical_review_type').val(1);
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

     