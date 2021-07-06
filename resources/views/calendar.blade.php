<!DOCTYPE html>
<html>
<head>
    <title>cms</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="{{ asset('js/jquery.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/jquery.validate.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script> var url ='<?php echo url('/'); ?>/';</script>
  
</head>
<body>
<div class="container-fluid">
		<div class="row div-Logo" style="border-bottom:4px #20286D solid"  data-toggle="tooltip" data-placement="top" title="double click  to show sittings">
		<div class="col-sm-9 div-brand"><img width="250px" src="<?php echo url('/'); ?> "></div>
		<div class="col-sm-3 div-Logos"><img width="100px" src="<?php echo url('/'); ?> "></div>	
		</div>
		</div>
		<nav class="navbar navbar-expand-lg navbar-dark bg-main" data-toggle="tooltip" data-placement="top" title="double click  to hide sittings">
  <a class="navbar-brand" href="#"><button class="btn btn-outline-light my-2 my-sm-0" type="submit">user </button></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
	<form class="form-inline my-2 my-lg-0"  method="get" action="<?php echo url('/') ?>calendar/editdatatable">
		<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Advance Search</button>
    </form>
     
    </ul>
	<form class="form-inline my-2 my-lg-0"  method="get" action='<?php echo url('/') ?>main/logout'>
		<button class="btn btn-outline-light my-2 my-sm-0" type="submit">Logout</button>
    </form>
  </div>
</nav>
<div class="container-fluid">
		<div class="row">
	<div class='top'>
		<!--
		<select id='locale-selector'>
			<option value="en" selected> English </option>
			<option value="ar"> عربي </option>
		</select>
		-->
		<div class="form">
		<form method="post">
		<input  type="search" class="inputform" placeholder='search' id='searchQuery' />
		</form>
  		<i class="fa fa-search" data-toggle="tooltip" data-placement="top" title="Search"></i>
		</div>
	</div>
	<div id='calendar' style="background-color: #fff" ></div>
	<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	<div class="row">
	  <div class="col-md-6">
          <div class="form-group">
            <label for="full_name" class="col-form-label">Name:</label>
            <input type="text" class="form-control" id="full_name" required>
          </div>
		</div>
		<div class="col-md-6">
		<div class="form-group">
            <label for="folder" class="col-form-label">Folder:</label>
			<input type="text" class="form-control" id="folder" required>
          </div>
					</div>
		  <div class="col-md-4">
			<div class="form-group">
            <label for="phone" class="col-form-label">Telphone:</label>
			<input type="text" class="form-control" id="phone" required>
          </div>
		  </div>
			<div class="col-md-4">
			<div class="form-group">
            <label for="mobile" class="col-form-label">Mobile:</label>
			<input type="text" class="form-control" id="mobile" required>
          </div>
		  </div>
		  <div class="col-md-4">
		  <div class="form-group ">
            <label for="Type-name" class="col-form-label">Doctor :</label>
			<select class="custom-select" id="doctor_name">
			</select>
          </div>
		  </div>
		<div class="col-md-4">
		  <div class="form-group ">
            <label for="Type-name" class="col-form-label">Cause :</label>
			<select class="custom-select" id="select">
			</select>
          </div>
		</div>
		<div class="col-md-4">
		  <div class="form-group ">
            <label for="Type-name" class="col-form-label">Date Status :</label>
			<select class="custom-select" id="date_status">
			</select>
          </div>
		</div>

		  <div class="col-md-4">
		  <div class="form-group ">
            <label for="Type-name" class="col-form-label">Medical Review :</label>
			<select class="custom-select" id="medical_review_type">
			</select>
          </div>
		  </div>
		  <div class="col-md-12">
          <div class="form-group">
            <label for="Note-text" class="col-form-label">Note:</label>
			<input type="text" class="form-control" id="Note_text" required>
          </div>
		</div>
      </div>

    </div>
      <div class="modal-footer">
        <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<button type="button" id="delete" class="btn btn-danger">Delete</button>
        <button type="button" id="save" class="btn btn-primary">Save changes</button>
		<button type="button" id="edit" class="btn btn-success">Edit</button>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-12" >
	<div class="row" style="height: 60px;border-top:4px #20286D solid">
		<div class="col-sm-9" style="background-color: rgb(253, 150, 255);"><p style="text-align: right;padding-right: 20%;;color: #20286D;padding-top: 20PX;font-weight:700">All rights reserved developer Solutions Inc 2019</p></div>
		<div class="col-sm-3" style="background-color: rgb(202, 231, 240);"></div>	</div>
</div>

</div>
</div>
</div>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
