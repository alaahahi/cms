@can('browse_media')
@extends('voyager::master')
@section('content')
<style>
#pageMessages {
  position: fixed;
  bottom: 15px;
  right: 15px;
  width: 30%;
}

.alert {
  position: relative;
}

.alert .close {
  position: absolute;
  top: 5px;
  right: 5px;
  font-size: 1em;
}

.alert .fa {
  margin-right:.3em;
}

.mr {
    width: 90%; 
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-5">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3><strong>Reports Cards</strong></h3>
                </div>
            </div>
           
            <!-- <a href="" class="btn btn-primary">Download ALL Order</a> -->
            <br>
            
            <div class="row">
                <div class="col-md-5 text-center">           
                    <div class="form-group">
                        <label for="card_number_input">{{ __('voyager::generic.card_number') }}</label>
                        <input  value = "0" type="text" id="card_number_input"  class="form-control mx-sm-3" >
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="form-group">
                    <br>
                    <a href="javascript:void(0)" class="btn btn-primary col-md-12  add">{{ __('voyager::generic.check_card') }}</a>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="form-group">
                    <br>
                    <a href="javascript:void(0)" class="btn btn-success col-md-12 download_order">Download Order</a>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="form-group">
                    <br>
                    <a href="javascript:void(0)" class="btn btn-success col-md-12 download">Download</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 text-center">           
                    <div class="form-group">
                        <label for="date-from">From</label>
                        <input   type="date" id="date-from"  class="form-control mx-sm-3" >
                    </div>
                </div>
                <div class="col-md-3 text-center">           
                    <div class="form-group">
                        <label for="date-to">To</label>
                        <input   type="date" id="date-to"  class="form-control mx-sm-3" >
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="form-group">
                    <label for="date-to">Type</label>
                    <select class="form-control " id="card_type_select">
                    <option value="all">All</option>
                    <option value="started">Activation Strated</option>
                    <option value="ended" >Activation End</option>
                    <option value="active"  >Active</option>
                    <option value="finished" >Finished</option>
                    </select>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="form-group">
                    <br>
                    <a href="javascript:void(0)" class="btn btn-primary col-md-12  search">Search</a>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="form-group">
                    <br>
                    <a href="javascript:void(0)" class="btn btn-success col-md-12  download_cards">Download</a>
                    </div>
                </div>

            </div>
            <div class="alert alert-success text-center" id="success-alert">
                <strong>Success!</strong>
                This Card is validate to <span id="active_to"></span>.
            </div>
            <div class="alert alert-danger text-center" id="danger-alert">
                <strong>Warning!</strong>
                This Card is not validate.
            </div>
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>{{ __('voyager::generic.serives_title') }}</th>
                        <th>{{ __('voyager::generic.admin_check') }}</th>
                        <th>{{ __('voyager::generic.date') }}</th>
                        <th>{{ __('voyager::generic.number') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <table class="table table-bordered data-table1">
                <thead>
                    <tr>
                    <th>{{ __('voyager::generic.full_name') }}</th>
                        <th>{{ __('voyager::generic.phone') }}</th>
                        <th>{{ __('voyager::generic.card_number') }}</th>
                        <th>{{ __('voyager::generic.card_type') }}</th>
                        <th>{{ __('voyager::generic.strat_active') }}</th>
                        <th>{{ __('voyager::generic.end_active') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade modal-product" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
    <div class="container-fluid">
    <div class="row">
        <div class="col-md-10">
            <h4 class="modal-title" id="exampleModalLabel">{{ __('voyager::generic.check_card') }}</h4>
        </div>
        <div class="col-md-2">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
      <div class="modal-body">
        <div class="container-fluid">
        <form method="post" id="upload-image-form" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="full_name" class="col-form-label">{{ __('voyager::generic.full_name') }}:</label>
                    <input type="text" class="form-control" name="full_name"  id="full_name" require>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                <label for="phone" class="col-form-label">{{ __('voyager::generic.phone') }}</label>
                <input type="text" class="form-control" name="phone" id="phone" require>
            </div>
          </div>
          <div class="col-md-4">
                <div class="form-group">
                <label for="card_number" class="col-form-label">{{ __('voyager::generic.card_number') }}:</label>
                <input type="number" class="form-control" name="card_number" id="card_number" require>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
            <label for="phone" class="col-form-label">{{ __('voyager::generic.card_type') }}:</label>
            <input type="text" class="form-control"  id="card_type_id" disabled>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                <label for="strat_active" class="col-form-label">{{ __('voyager::generic.strat_active') }}:</label>
                <input type="date" class="form-control" name="strat_active" id="strat_active" require>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                <label for="end_active" class="col-form-label">{{ __('voyager::generic.end_active') }}:</label>
                <input type="date" class="form-control" name="end_active" id="end_active" require>
                </div>
            </div>
        </div>
        <h4 class="text-center">Services</h4>
        <div class="row" id="Services">
        </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>

<script type="text/javascript">
  $(function () {
    $("#success-alert").hide();
    $("#danger-alert").hide();
    $('.data-table').hide();
    $('.data-table1').hide();
    $('#date-from').prop('disabled', true);
    $('#date-to').prop('disabled', true);
    var table;
    $('body').on('click', '.add', function () { 
        var q = $('#card_number_input').val();

        $.ajax({
            type: "GET",
            url:"{{ route('check_card_no') }}/"+q ,
            success: function (client) {
                if(client.card_number){
                $("#active_to").text(client.end_active+' Type Card is '+client.title);
                $("#success-alert").alert();
                $("#success-alert").fadeTo(5000, 500).slideUp(500, function(){
                $("#success-alert").slideUp(500);
                });
                $('#full_name').val(client.full_name).prop('disabled', true);
                $('#phone').val(client.phone).prop('disabled', true);
                $('#card_number').val(client.card_number).prop('disabled', true);
                $('#strat_active').val(client.strat_active).prop('disabled', true);
                $('#end_active').val(client.end_active).prop('disabled', true);
                $('#card_type_id').val(client.title);
                $.ajax({
                type: "GET",
                url:"{{ route('card_service') }}/"+q ,
                success: function (service) {
                if(service !=0 ){
                $('#Services').empty();
                $.each(service, function(index,value) {
                $('#Services').append(
                    '<div class="form-group col-md-4 text-center"><a href="javascript:void(0)" class="mr btn btn-primary service_action" data-client="'+value.client_id+ '" data-services="'+value.services_id+ '" > '+value.title+ '</a></div>'
                                     );
                 });
                 }
                 },
                 error: function (data) {
                 console.log('Error:', data);
                }             
                }); 
                window.setTimeout(function () { 
                   // $('.modal-product').modal('show');
                }, 5050); 
                $('.dataTables_wrapper').hide();
                $('.data-table').show();
                table = $('.data-table').DataTable({
                            ajax: "{{ route('check_card') }}/"+q ,
                                    columns: [
                                    {data: 'title', name: 'title'},
                                    {data: 'name', name: 'name'},
                                    {data: 'date', name: 'date'},
                                    {data: 'number', name: 'number'},
                                    ],
                                    "bDestroy": true
                            });
                    }
            else {
                $('.dataTables_wrapper').hide();
                $("#danger-alert").alert();
                $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                $("#danger-alert").slideUp(500);
                });
            }
            },
            error: function (data) {

                console.log('Error:', data);
            }
            
        });  
    });

    $('body').on('click', '.service_action', function () 
    {
    Item_client = $(this).data('client');
    Item_services = $(this).data('services');

    $.ajax({
            type: "GET",
            url:"{{ route('submit_service') }}/"+Item_client+"/"+Item_services,
            success: function (client) {
                $('.data-table').DataTable().ajax.reload();
                $('.modal-product').modal('hide');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
    $('body').on('click', '.download', function () 
    {
    var q = $('#card_number_input').val();
    window.location.href = "{{ route('generatePDF_card') }}/"+q ;
    });
    $('body').on('click', '.download_order', function () 
    {
    var q = $('#card_number_input').val();
    window.location.href = "{{ route('generatePDF_card_info') }}/"+q ;
    });
    $('#card_type_select').on('change', function() {
    if( $(this).find(":selected").val() === "all"  ){
        $('#date-from').prop('disabled', true);
        $('#date-to').prop('disabled', true);
    }
    if( $(this).find(":selected").val() === "started"  ){
        $('#date-from').prop('disabled', false);
        $('#date-to').prop('disabled', false);
    }
    if( $(this).find(":selected").val() === "ended"  ){
        $('#date-from').prop('disabled', false);
        $('#date-to').prop('disabled', false);
    }
    if( $(this).find(":selected").val() === "active"  ){
        $('#date-from').prop('disabled', true);
        $('#date-to').prop('disabled', true);
    }
    if( $(this).find(":selected").val() === "finished"  ){
        $('#date-from').prop('disabled', true);
        $('#date-to').prop('disabled', true);
    }
    });

    $('body').on('click', '.search', function () 
    {
    var from = $('#date-from').val();
    var to = $('#date-to').val();
    if (from == "") from =0;
    if (to == "") to = 0;
    var type  = $('#card_type_select').find(":selected").val();
    $('.dataTables_wrapper').hide();
    $('.data-table1').show();
    table = $('.data-table1').DataTable({
                            ajax:" {{ route('cards_from_to') }}/"+from+"/"+to+"/"+type+"/"+false,
                                    columns: [
                                    {data: 'full_name', name: 'full_name'},
                                    {data: 'phone', name: 'phone'},
                                    {data: 'card_number', name: 'card_number'},
                                    {data: 'title', name: 'title'},
                                    {data: 'strat_active', name: 'strat_active'},
                                    {data: 'end_active', name: 'end_active'},
                                    
                                    ],
                                    "bDestroy": true
                            });
          
    });
    $('body').on('click', '.download_cards', function () 
    {
    var type  = $('#card_type_select').find(":selected").val();
    var from = $('#date-from').val();
    var to = $('#date-to').val();
    if (from == "") from =0;
    if (to == "") to = 0;
    window.location.href =  "{{ route('cards_from_to') }}/"+from+"/"+to+"/"+type+"/"+true ;
    });
});
</script>
@endsection 
@else
Not have permissions To Veiw
@endcan
