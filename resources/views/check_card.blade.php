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
                    <h3><strong>{{ __('voyager::generic.check_card') }}</strong></h3>
                </div>
            </div>
           
            <!-- <a href="" class="btn btn-primary">Download ALL Order</a> -->
            <br>
            
            <div class="row">
                <div class="col-md-4 text-center">           
                    <div class="form-group">
                        <label for="card_number_input">{{ __('voyager::generic.card_number') }}</label>
                        <input  value = "0" type="text" id="card_number_input"  class="form-control mx-sm-3" >
                    </div>
                </div>
                <div class="col-md-4 text-center">           
                    <div class="form-group">
                    <label for="card_number_input">{{ __('voyager::generic.card_type') }}</label>
                  <!--  <input type="text" class="form-control"  id="card_user_id" disabled>-->
                  <select id="service_input"  class="form-control select2-ajax select2-hidden-accessible" name="card_type_id" data-get-items-route="https://savingservices.net/cms/public/admin/servicecardtype/relation" data-get-items-field="servicecardtype_belongsto_card_type_relationship" data-method="add" data-select2-id="1" tabindex="-1" aria-hidden="true">
                    <option value="0" >{{ __('voyager::generic.all') }}</option>
                    </select>  
      
                 
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="form-group">
                    <br>
                    <a href="javascript:void(0)" class="btn btn-primary col-md-12  add">{{ __('voyager::generic.check_card') }}</a>
                    </div>
                </div>
            </div>
            <div class="alert alert-success text-center" id="success-alert">
                <strong>{{ __('voyager::generic.Success') }}</strong>
                {{ __('voyager::generic.is_validate_Card') }} <span id="active_to"></span>.
            </div>
            <div class="alert alert-danger text-center" id="danger-alert">
                <strong>{{ __('voyager::generic.Warning') }}</strong>
                {{ __('voyager::generic.not_validate_Card') }}
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
        <h4 class="text-center">{{ __('voyager::generic.Services') }}</h4>
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
    var table;
    $('body').on('click', '.add', function () { 
        var q = $('#card_number_input').val();
        var type  = $('#service_input').find(":selected").val();
        $.ajax({
            type: "GET",
            url:"{{ route('check_card_no') }}/"+q+"/"+ type,
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
                                url:"{{ route('card_service') }}/"+q+"/"+type ,
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
                    $('.modal-product').modal('show');
                }, 5050); 
                $('.dataTables_wrapper').show();
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
                $('.data-table').hide();
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

    $('body').on('click', '.service_action', function () {
    Item_client = $(this).data('client');
    Item_services = $(this).data('services');
    q = $('#card_number_input').val();
    $.ajax({
            type: "GET",
            url:"{{ route('submit_service') }}/"+Item_client+"/"+Item_services+"/"+q,
            success: function (client) {
                $('.data-table').DataTable().ajax.reload();
                $('.modal-product').modal('hide');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });

    });
    });
</script>
@endsection 