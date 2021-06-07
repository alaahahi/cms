
<?php $__env->startSection('content'); ?>

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
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-5">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3><strong><?php echo e(__('voyager::generic.check_card')); ?></strong></h3>
                </div>
            </div>
           
            <!-- <a href="" class="btn btn-primary">Download ALL Order</a> -->
            <br>
            
            <div class="row">
                <div class="col-md-6 text-center">           
                    <div class="form-group">
                        <label for="card_number_input"><?php echo e(__('voyager::generic.card_number')); ?></label>
                        <input  value = "0" type="text" id="card_number_input"  class="form-control mx-sm-3" >
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <div class="form-group">
                    <br>
                    <a href="javascript:void(0)" class="btn btn-primary col-md-12  add"><?php echo e(__('voyager::generic.check_card')); ?></a>
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
                        <th><?php echo e(__('voyager::generic.serives_title')); ?></th>
                        <th><?php echo e(__('voyager::generic.serives_number')); ?></th>
                        <th><?php echo e(__('voyager::generic.admin_check')); ?></th>
                        <th><?php echo e(__('voyager::generic.date')); ?></th>
                        <th><?php echo e(__('voyager::generic.Action')); ?></th>
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
            <h4 class="modal-title" id="exampleModalLabel"><?php echo e(__('voyager::generic.check_card')); ?></h4>
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
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="full_name" class="col-form-label"><?php echo e(__('voyager::generic.full_name')); ?>:</label>
                    <input type="text" class="form-control" name="full_name"  id="full_name" require>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="phone" class="col-form-label"><?php echo e(__('voyager::generic.phone')); ?></label>
                <input type="text" class="form-control" name="phone" id="phone" require>
            </div>
          </div>
        </div>
        <div class="row">
        <div class="col-md-6">
                <div class="form-group">
                <label for="card_number" class="col-form-label"><?php echo e(__('voyager::generic.card_number')); ?>:</label>
                <input type="number" class="form-control" name="card_number" id="card_number" require>
                </div>
            </div>
            <div class="col-md-6">
            <label for="phone" class="col-form-label"><?php echo e(__('voyager::generic.card_type')); ?>:</label>
            <input type="text" class="form-control"  id="card_type_id" disabled>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label for="strat_active" class="col-form-label"><?php echo e(__('voyager::generic.strat_active')); ?>:</label>
                <input type="date" class="form-control" name="strat_active" id="strat_active" require>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="end_active" class="col-form-label"><?php echo e(__('voyager::generic.end_active')); ?>:</label>
                <input type="date" class="form-control" name="end_active" id="end_active" require>
                </div>
            </div>
        </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Save</button></div>
    </div>
    </form>
  </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
  $(function () {
    $("#success-alert").hide();
    //$(".data-table").hide();
    $("#danger-alert").hide();
    var table = $('.data-table').DataTable({
       ajax: "<?php echo e(route('check_card')); ?>",
      columns: [
            {data: 'title', name: 'title'},
            {data: 'number', name: 'number'},
           {data: 'user_chack', name: 'user_chack'},
           {data: 'date', name: 'date'},
            {data: 'action', name: 'action'},
       ]});
    $('body').on('click', '.add', function () { 
        var q = $('#card_number_input').val();
        $.ajax({
            type: "GET",
            url:"<?php echo e(route('check_card_no')); ?>/"+q ,
            success: function (client) {
                if(client.card_number){
                $("#active_to").text(client.end_active+' Type Card is '+client.title);
                $("#success-alert").alert();
                $("#success-alert").fadeTo(10000, 500).slideUp(500, function(){
                $("#success-alert").slideUp(500);
                });
                $('#full_name').val(client.full_name).prop('disabled', true);
                $('#phone').val(client.phone).prop('disabled', true);
                $('#card_number').val(client.card_number).prop('disabled', true);
                $('#strat_active').val(client.strat_active).prop('disabled', true);
                $('#end_active').val(client.end_active).prop('disabled', true);
                $('#card_type_id').val(client.title);
                window.setTimeout(function () { 
                    ///$('.modal-product').modal('show');
                }, 1500);   
            }
            else {
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
    $('body').on('click', '.edit', function () {
        let Item_id;
        if($(this).data('id')){
        Item_id = $(this).data('id');
       }else{
       Item_id = 0 ;
        }
       
      $.ajax({
            type: "GET",
            url:"<?php echo e(route('edit_client')); ?>/"+Item_id,
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
          url:"<?php echo e(route('edit_clients')); ?>/"+Item_id,
           data: formData,
           contentType: false,
           processData: false,
           success: (response) => {
            $('.data-table').DataTable().ajax.reload();
             if (response) {
               this.reset();
               $('.modal-product').modal('hide');
             }
           },
           error: function(response){
              console.log(response);
           }
       });
  });
});

$('body').on('click', '.delete', function () {
            var Item_id = $(this).data('id');
$.ajax({
        type: 'DELETE' ,
        url:"<?php echo e(route('remove_clients')); ?>/"+Item_id,
        dataType: 'json',
        success:function(data){
            $('.data-table').DataTable().ajax.reload();
        }});
    });
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('voyager::master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\cms\resources\views/check_card.blade.php ENDPATH**/ ?>