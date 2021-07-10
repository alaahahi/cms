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
		<div class="row">

		<!--
		<select id='locale-selector'>
			<option value="en" selected> English </option>
			<option value="ar"> عربي </option>
		</select>

		<div class="form">
		<form method="post">
		<input  type="search" class="inputform" placeholder='search' id='searchQuery' />
		</form>
  		<i class="fa fa-search" data-toggle="tooltip" data-placement="top" title="Search"></i>
		</div>
				-->

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
            <label for="all_clinet" class="col-form-label">Client:</label>
			 <select class="custom-select" id="all_clinet">
			</select>
            </span>
          </div>
		  </div>
		  <div class="col-md-6">
		  <div class="form-group ">
            <label for="all_services" class="col-form-label">Services :</label>
			<select class="custom-select" id="all_services">
			</select>
          </div>
		  </div>
	</div>

	<div class="row">
		  <div class="col-md-12">
          <div class="form-group">
            <label for="Note-text" class="col-form-label">Note:</label>
			<input type="text" class="form-control" id="Note_text" >
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

</div>
</div>
</div>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>