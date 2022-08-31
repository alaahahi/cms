@extends('voyager::master')
@section('content')
<style>

.mr {
    width: 90%; 
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-5">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3><strong>{{ __('voyager::generic.client') }}</strong></h3>
                </div>
            </div>
            <div class="row">
            <div class="col-md-6 text-center">
                    <div class="form-group">
                    <?php $total = 0;$count =0 ?>
                           @foreach ($data as $datas)
                            <?php $count =  $count + 1;  ?>
                           @endforeach
                    <label for="totaltody">{{ __('voyager::generic.Client Count') }}</label>
                    <input value="<?php echo $count ?? 0 ?>"  type="text"  class="form-control mx-sm-3" disabled>
                    </div>
                </div>
                <div class="col-md-6 text-center">           
                    <div class="form-group">
                    <br>
                        <a href="javascript:void(0)" class=" col-md-12 btn btn-primary add">{{ __('voyager::generic.new_client') }}</a>
                    </div>
                </div>
            </div>
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>{{ __('voyager::generic.full_name') }}</th>
                        <th>{{ __('voyager::generic.phone') }}</th>
                        <th>{{ __('voyager::generic.strat_active') }}</th>
                        <th>{{ __('voyager::generic.end_active') }}</th>
                        <th>{{ __('voyager::generic.card_type') }}</th>
                        <th>{{ __('voyager::generic.user') }}</th>
                        <th>{{ __('voyager::generic.Action') }}</th>
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
        <div class="col-md-6">
            <h4 class="modal-title" id="exampleModalLabel">{{ __('voyager::generic.new_client') }}</h4>
        </div>
        <div class="col-md-6">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="alert alert-danger text-center" id="danger-alert">
                <strong>{{ __('voyager::generic.Warning') }}</strong>
         </div>
        <form method="post" id="upload-image-form" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="full_name" class="col-form-label">{{ __('voyager::generic.full_name') }}:</label>
                    <input type="text" class="form-control" name="full_name"  id="full_name" require>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="phone" class="col-form-label">{{ __('voyager::generic.phone') }}</label>
                <input type="text" class="form-control" name="phone" id="phone" require>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label for="birth_date" class="col-form-label">{{ __('voyager::generic.birth_date') }}:</label>
                <input type="date" class="form-control" name="birth_date" id="birth_date">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="address" class="col-form-label">{{ __('voyager::generic.address') }}:</label>
                <input type="text" class="form-control" name="address" id="address" >
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-md-6">
                <div class="form-group">
                <label for="card_number" class="col-form-label">{{ __('voyager::generic.card_number') }}:</label>
                <input type="number" class="form-control" name="card_number" id="card_number" require>
                </div>
            </div>
            <div class="col-md-3">
            <label for="phone" class="col-form-label">{{ __('voyager::generic.card_type') }}:</label>
            <input type="text" class="form-control"  id="card_type_id" disabled>
            <span id="card_type">
            <select class="form-control select2-ajax select2-hidden-accessible " name="card_type_id" data-get-items-route="https://savingservices.net/cms/public/admin/cards/relation" data-get-items-field="card_belongsto_card_type_relationship_1" data-method="add" data-select2-id="1" tabindex="-1" aria-hidden="true">
            </select>
            </span>
            </div>
            <div class="col-md-3">
            <label for="phone" class="col-form-label">{{ __('voyager::generic.user') }}:</label>
            <input type="text" class="form-control"  id="card_user_id" disabled>
            <span id="card_user">
            <select class="form-control select2-ajax select2-hidden-accessible " name="card_user_id" data-get-items-route="https://savingservices.net/cms/public/admin/cards/relation" data-get-items-field="card_belongsto_user_relationship" data-method="add" data-select3-id="1" tabindex="-1" aria-hidden="true">
    
            </select>
            </span>
            </div>
        </div>
        <p class='text-center'>أسماء أفراد العائلة</p>
        <div class="col-md-12" >
                <div class="form-group">
                    <input type="text" class="form-control" name="names"  id='names'>
                </div>
            </div>
        <div class="row names">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="name1" class="col-form-label">1</label>
                    <input type="text" class="form-control" name="name1"  id="name1" >
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                <label for="name2" class="col-form-label">2</label>
                <input type="text" class="form-control" name="name2" id="name2">
            </div>
          </div>
          <div class="col-md-2">
                <div class="form-group">
                <label for="name3" class="col-form-label">3</label>
                <input type="text" class="form-control" name="name3" id="name3" >
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                <label for="name4" class="col-form-label">4</label>
                <input type="text" class="form-control" name="name4" id="name4">
            </div>
          </div>
          <div class="col-md-2">
                <div class="form-group">
                <label for="name5" class="col-form-label">5</label>
                <input type="text" class="form-control" name="name5" id="name5">
                </div>
            </div>
        </div>
        <div class="row names">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="name6" class="col-form-label">6</label>
                    <input type="text" class="form-control" name="name6"  id="name6" >
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                <label for="name7" class="col-form-label">7</label>
                <input type="text" class="form-control" name="name7" id="name7" >
            </div>
          </div>
          <div class="col-md-2">
                <div class="form-group">
                <label for="name8" class="col-form-label">8</label>
                <input type="text" class="form-control" name="name8" id="name8" >
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                <label for="name9" class="col-form-label">9</label>
                <input type="text" class="form-control" name="name9" id="name9" >
            </div>
          </div>
          <div class="col-md-2">
                <div class="form-group">
                <label for="name10" class="col-form-label">10</label>
                <input type="text" class="form-control" name="name10" id="name10">
                </div>
            </div>
        </div>
        <div class="row">
          
            <div class="col-md-6">
                <div class="form-group">
                <label for="strat_active" class="col-form-label">{{ __('voyager::generic.strat_active') }}:</label>
                <input type="date" class="form-control" name="strat_active" id="strat_active" require>
                </div>
            </div>
            <div class="col-md-6 text-center">
                    <div class="form-group">
                    <br>
                    <a href="javascript:void(0)" class="btn btn-success col-md-12 download">Download Order Pdf</a>
                    </div>
                </div>
        </div>
        </div>
      </div>
      <div class="modal-footer text-center">
        <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __('voyager::generic.close') }}</button>
        <button type="submit" class="btn btn-success" id="save_btn">{{ __('voyager::generic.save') }}</button></div>
    </div>
    </form>
  </div>
</div>

<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
    Date.prototype.toDateInputValue = (function() {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0,10);
});
  $(function () {
    $("#danger-alert").hide();
    var table = $('.data-table').DataTable({
        ajax: "{{ route('clients') }}",
        columns: [
            {data: 'card_number', name: 'card_number'},
            {data: 'full_name', name: 'full_name'},
            {data: 'phone', name: 'phone'},
            {data: 'strat_active', name: 'strat_active'},
            {data: 'end_active', name: 'end_active'},
            {data: 'title', name: 'title'},
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action'},
        ],
        "order": [[ 0, "desc" ]]
    });
    $('body').on('click', '.download', function () 
    {
    var q = $('#card_number').val();
    window.location.href = "{{ route('generatePDF_card_order') }}/"+q ;
    });
    $('body').on('click', '.add', function () { 
        $(".names").show();
        $("#names").hide();
        $("#card_type").show();
        $("#card_type_id").hide();
        $("#card_user_id").hide();
        $('#full_name').val("");
        $('#phone').val("");
        $('#address').val("");
        $('#card_number').val("").prop('disabled', false);
        $('#strat_active').val(new Date().toDateInputValue()).prop('disabled', false);
        $('#birth_date').val(new Date().toDateInputValue());
        $('.modal-product').modal('show');
    });
    $('body').on('click', '.edit', function () {
        let Item_id;
        if($(this).data('id')){
        Item_id = $(this).data('id');
       }else{
       Item_id = 0 ;
        }
       
      $.ajax({
            type: "GET",
            url:"{{ route('edit_client') }}/"+Item_id,
            success: function (client) {
                $('#full_name').val(client.full_name);
                $('#phone').val(client.phone);
                $('#card_number').val(client.card_number).prop('disabled', true);
                $('#strat_active').val(client.strat_active).prop('disabled', true);
                $('#birth_date').val(client.birth_date);
                $('#card_user_id').val(client.name);
                $('#card_type_id').val(client.title);
                $('#address').val(client.address);
                $('#names').val(client.names);
                $(".names").hide();
                $("#names").show();
                $("#card_type").hide();
                $("#card_type_id").show();
                $("#card_user").hide();
                $("#card_user_id").show();
                $('#upload-image-form').attr('data-id' , client.id);
                $('.modal-product').modal('show');
            },
            error: function (data) {
                console.log('Error:', data);

            }
        });
    });
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

   $('#upload-image-form').submit(function(e) {

    $('#save_btn').prop('disabled', true);
       e.preventDefault();
       let Item_id
       let formData = new FormData(this);
       if($(this).data('id')){
        Item_id = $(this).data('id');
       }else{
       Item_id = 0 ;
        }
       $('#image-input-error').text('');
       $.ajax({
          type:'POST',
          url:"{{ route('edit_clients') }}/"+Item_id,
           data: formData,
           contentType: false,
           processData: false,
           success: (response) => {
            $('.data-table').DataTable().ajax.reload();
             if (response) {
               this.reset();
               $('#save_btn').prop('disabled', false);
               $('.modal-product').modal('hide');
             }
           },
           error: function(response){
            $('#save_btn').prop('disabled', false);
            alert(response.responseText)

                $("#danger-alert").alert();
                $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                $("#danger-alert").slideUp(500);});
              console.log(response);
           }
       });
  });
});

$('body').on('click', '.delete', function () {
            var Item_id = $(this).data('id');
            if (confirm('Are you sure to delete this client')) {
                $.ajax({
        type: 'DELETE' ,
        url:"{{ route('remove_clients') }}/"+Item_id,
        dataType: 'json',
        success:function(data){
            $('.data-table').DataTable().ajax.reload();
        }});
} 

    });
</script>
@endsection 