
var data,title,date_type,date_status,medical_review_type,doctor_name,note,phone,mobile,id, _start,_end,x,x1,color,folder;
var url_all_date_type= url+'all_date_type';
var url_date_status= url+'all_date_status';
var url_doctor_name= url+'all_doctor_name';
var url_medical_review_type= url+'all_medical_review_type';
var socket;
$(document).ready(function () {
	var dataTable = $('#user_data').DataTable({  
		"processing":true,  
		"serverSide":true,  
		"order":[],  
		"ajax":{  
			 url:base_url+"Edit/fetch_user",
			 type:"POST"  
		},  
		"columnDefs":[  
			 {  
				  "targets":[0, 3, 4],  
				  "orderable":false,  
			 },  
		],  
   });  


   $.getJSON(url_all_date_type, function (data) {
   $.each(data, function (index, value) {
   $('#select').append('<option value="' + value.id + '">' + value.title + '</option>');
										   });
								   });
   $.getJSON(url_date_status, function (data) {
   $.each(data, function (index, value) {
   $('#date_status').append('<option style="background-Color:'+value.color+'"  value="' + value.id + '">' + value.title + '</option>');
										   });
								   });
   $.getJSON(url_doctor_name, function (data) {
   $.each(data, function (index, value) {
   $('#doctor_name').append('<option  value="' + value.id + '">' + value.title + '</option>');
										   });
								   });
   $.getJSON(url_medical_review_type, function (data) {
   $.each(data, function (index, value) {
   $('#medical_review_type').append('<option     value="' + value.id + '">' + value.title + '</option>');
										   });
								   });

	});
   function editEvents(id) {  
   $( "#save" ).hide();
   $('#Modal').modal('show');
   $( "#edit" ).show();
   $( "#delete" ).hide();
   $.ajax({
	   url: base_url+'edit/get_event_by_id/' + id,
	   type: 'get',
	   dataType: 'json',

	   success: function(data){
		   $('#select').val(data[0].date_type);
		   $('#date_status').val(data[0].date_status);
		   $('#doctor_name').val(data[0].doctor_name);
		   $('#medical_review_type').val(data[0].medical_review_type);
		   $('#full_name').val(data[0].full_name);
			$('#Note_text').val(data[0].note);
		   $('#phone').val(data[0].phone);
		   $('#mobile').val(data[0].mobile);
		   $('#folder').val(data[0].folder);
		   x=data[0].id;	
	   },
	   error: function () {
	   
	   },
   });
   
   }
   function moreEvent (id) {  
   $( "#save" ).hide();
   $( "#edit" ).hide();
   $( "#delete" ).hide();
   $('#Modal').modal('show');
   $.ajax({
	   url: base_url+'edit/get_event_by_id/' + id,
	   type: 'get',
	   dataType: 'json',

	   success: function(data){
		   $('#select').val(data[0].date_type);
		   $('#date_status').val(data[0].date_status);
		   $('#doctor_name').val(data[0].doctor_name);
		   $('#medical_review_type').val(data[0].medical_review_type);
		   $('#full_name').val(data[0].full_name);
			$('#Note_text').val(data[0].note);
		   $('#phone').val(data[0].phone);
		   $('#mobile').val(data[0].mobile);
		   $('#folder').val(data[0].folder);
		   x=data[0].id;	
	   },
	   error: function () {
	   
	   },
   });
   
   }
   



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
function deleteEvent(id) 
{
   if (confirm('are you sure you want delete it?')) {
					   deleteEvents(id);
					   $('#Modal').modal('hide'); 
					   $( "#delete" ).hide();
					   $( "#edit" ).hide();
					   modelReset ();
				   }

}
function deleteEvents (id) {
   $.ajax({
	   url: base_url+'calendar/delete/' + id,
	   dataType: 'json',
	   contentType: 'application/json',
	   success: function () {
   
		   refreshTable();
	   },
	   error: function () {
		   refreshTable();
	   }
   });
}
function updateEvents() {
   full_name=$('#full_name').val();
   date_type = $('#select').val();
   date_status = $('#date_status').val();;
   doctor_name = $('#doctor_name').val();
   medical_review_type = $('#medical_review_type').val();
   note =$('#Note_text').val();
   phone=$('#phone').val();
   mobile=$('#mobile').val();
   folder=$('#folder').val();
   $.ajax({
	   url: base_url+"calendar/edit/"+ x,
	   type: 'post',
	   dataType: 'json',
	   data:{full_name:full_name,folder:folder,date_type:date_type,date_status:date_status,doctor_name:doctor_name,medical_review_type:medical_review_type,note:note,phone:phone,mobile:mobile},
	   success: function () {
		 $('#Modal').modal('hide');
		 modelReset ()
	   },
	   error: function () {
	   $('#Modal').modal('hide');
	   modelReset ();
	   refreshTable();
	   },


   });		
	 };
	 function refreshTable() {
$('#user_data').each(function() {
 dt = $(this).dataTable();
 dt.fnDraw();
})
}
