@extends('voyager::master')
@section('content')

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
                    <label for="totaltody">Client Count</label>
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
                <input type="date" class="form-control" name="birth_date" id="birth_date" require>
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
            <div class="col-md-6">
            <label for="phone" class="col-form-label">{{ __('voyager::generic.card_type') }}:</label>
            <input type="text" class="form-control"  id="card_type_id" disabled>
            <span id="card_type">
            <select class="form-control select2-ajax select2-hidden-accessible " name="card_type_id" data-get-items-route="http://localhost/cms/public/admin/cards/relation" data-get-items-field="card_belongsto_card_type_relationship_1" data-method="add" data-select2-id="1" tabindex="-1" aria-hidden="true">
                    <option value="" data-select2-id="3">None</option>
            </select>
            </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label for="strat_active" class="col-form-label">{{ __('voyager::generic.strat_active') }}:</label>
                <input type="date" class="form-control" name="strat_active" id="strat_active" require>
                </div>
            </div>
        </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" id="save_btn">Save</button></div>
    </div>
    </form>
  </div>
</div>
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
  $(function () {

    var table = $('.data-table').DataTable({
        ajax: "{{ route('clients') }}",
        columns: [
            {data: 'card_number', name: 'card_number'},
            {data: 'full_name', name: 'full_name'},
            {data: 'phone', name: 'phone'},
            {data: 'strat_active', name: 'strat_active'},
            {data: 'end_active', name: 'end_active'},
            {data: 'title', name: 'title'},
            {data: 'action', name: 'action'},
        ]
    });
    $('body').on('click', '.add', function () { 
        $("#card_type").show();
        $("#card_type_id").hide();
        $('#full_name').val("");
        $('#phone').val("");
        $('#card_number').val("").prop('disabled', false);
        $('#strat_active').val("").prop('disabled', false);
        $('#birth_date').val("");
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
                $('#card_type_id').val(client.title);
                $("#card_type").hide();
                $("#card_type_id").show();
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