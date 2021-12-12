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
                    <h3><strong>Reports Service</strong></h3>
                </div>
            </div>
           
            <!-- <a href="" class="btn btn-primary">Download ALL Order</a> -->
            <br>
            
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
                    <label for="card_number_input">Service</label>
                    <select  id="service_input" class="form-control select2-ajax select2-hidden-accessible" name="services_id" data-get-items-route="https://savingservices.net/cms/public/admin/servicecardtype/relation" data-get-items-field="servicecardtype_belongsto_service_relationship" data-method="add" data-select2-id="4" tabindex="-1" aria-hidden="true">
                    <option value="0" >All</option>
                    </select>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="form-group">
                    <br>
                    <a href="javascript:void(0)" class="btn btn-primary col-md-12  add">Search</a>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="form-group">
                    <br>
                    <a href="javascript:void(0)" class="btn btn-success col-md-12 download_service">Download</a>
                    </div>
                </div>
            </div>
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>{{ __('voyager::generic.serives_title') }}</th>
                        <th>{{ __('voyager::generic.admin_check') }}</th>
                        <th>{{ __('voyager::generic.card_number') }}</th>
                        <th>{{ __('voyager::generic.card_type') }}</th>
                        <th>{{ __('voyager::generic.date') }}</th>
                        <th>{{ __('voyager::generic.number') }}</th>
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
    var table;
    $('.data-table').hide();
    $('body').on('click', '.add', function () { 
    var type  = $('#service_input').find(":selected").val();
    var from = $('#date-from').val();
    var to = $('#date-to').val();
    if (from == "") from =0;
    if (to == "") to = 0;
    $('.dataTables_wrapper').hide();
    $('.data-table').show();
     table = $('.data-table').DataTable({
                            ajax: "{{ route('check_service') }}/"+from+"/"+to+"/"+type+"/"+false,
                                    columns: [
                                    {data: 'title', name: 'title'},
                                    {data: 'name', name: 'name'},
                                    {data: 'card_number', name: 'card_number'},
                                    {data: 'type', name: 'type'},
                                    {data: 'date', name: 'date'},
                                    {data: 'number', name: 'number'},
                                    ],
                                    "bDestroy": true
                            });
        }); 

    $('body').on('click', '.download_service', function () 
    {
    var type  = $('#service_input').find(":selected").val();
    var from = $('#date-from').val();
    var to = $('#date-to').val();
    if (from == "") from =0;
    if (to == "") to = 0;
    window.location.href =  "{{ route('check_service') }}/"+from+"/"+to+"/"+type+"/"+true ;
    });
});
</script>
@endsection
@else
Not have permissions To Veiw
@endcan