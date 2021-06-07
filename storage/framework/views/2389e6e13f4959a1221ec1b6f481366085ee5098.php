


<?php $__env->startSection('content'); ?>

<h1></h1>
<div class="container">
<div class="row">
<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="col-md-4">
<div class="card" style="border-radius:15px">
<div class="card-header text-center" style="padding: 3%;">
Customer Mobile: <?php echo e($customer->user_phone); ?>

</div>
<?php $__currentLoopData = $customer->product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $products): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div style="border-radius: 5px;margin: 3%;border: 1px solid #eee;">
<img  class="img-circle" src="http://savingapp.co/AdminCp/storage/app/public/<?php echo e($products->photo); ?>" alt="Avatar" style="height:100px;width:100px;float: left;padding: 2%;" >
<p>Product : <?php echo e($products->title_translation); ?></p>
<p>Price : <?php echo e($products->discount_price); ?></p>
<p>Quantity : <?php echo e($products->pivot->quantity); ?></p>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <div class="container">
    <h4><b>Total : <?php echo e($customer->order_total); ?></b></h4>
    <p>Date : <?php echo e($customer->created_at); ?></p>
    <p>Accepted : <?php echo e($customer->is_accepted); ?></p>
    <div class="card-footer text-center">
          <div class="btn-wrapper  justify-content-between">
          <?php if($customer->is_accepted==0): ?>
          <a href="javascript:void(0)" data-toggle="tooltip"    data-id="<?php echo e($customer->id); ?>"  class="btn btn-danger rejection">Rejection</a>
          <a href="javascript:void(0)" data-toggle="tooltip"  id="<?php echo e($customer->id); ?>"  data-id="<?php echo e($customer->id); ?>" class="btn btn-warning approval">Approval</a>
          <?php endif; ?>
          <?php if($customer->is_accepted==1): ?>
          <a href="javascript:void(0)" data-toggle="tooltip"     class="btn btn-success ">Compleated</a>
          <?php endif; ?>
          <?php if($customer->is_accepted==2): ?>
          <a href="javascript:void(0)" data-toggle="tooltip"     class="btn btn-danger">Rejected</a>
          <?php endif; ?>
          </div>
    </div>
</div>
</div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
  $('body').on('click', '.approval', function () {
      $(this).attr('disabled', true);      
      var Item_id = $(this).data('id');
      debugger;
    
      $.get("<?php echo e(route('admin.approval')); ?>/"+Item_id ).done(function() {
        $('#'+Item_id).attr('disabled', false); 
        $('#'+Item_id).css("background-color", "#2ecc71").text("Done Approval");
});
   });
   $('body').on('click', '.rejection', function () {
      $(this).attr('disabled', true);      
      var Item_id = $(this).data('id');
      debugger;
    
      $.get("<?php echo e(route('admin.rejection')); ?>/"+Item_id ).done(function() {
        $('#'+Item_id).attr('disabled', false); 
        $('#'+Item_id).css("background-color", "#2ecc71").text("Done Rejection");
});
   });
</script>
  <?php $__env->stopSection(); ?>
<?php echo $__env->make('voyager::master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\savingappApi\resources\views/my_orders.blade.php ENDPATH**/ ?>