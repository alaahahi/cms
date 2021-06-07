


<?php $__env->startSection('content'); ?>

<h1>My Company</h1>
<div class="container">
<div class="container">
<div class="row">
<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="col-md-4">
<div class="card" style="border-radius:15px">
  <img src="http://savingapp.co/AdminCp/storage/app/public/<?php echo e($customer->photo); ?>" alt="Avatar" style="width:100%" >
  <img src="http://savingapp.co/AdminCp/storage/app/public/<?php echo e($customer->logo); ?>" alt="Avatar" style="width:15%;position: relative;
    bottom: 60px;
    left: 10px;" >
  <div class="container">
    <h4><b><?php echo e($customer->title); ?></b></h4>
    <p><?php echo e($customer->desc); ?></p>
    <p>Open Time: <?php echo e($customer->open_minute  / 60); ?></p>
    <p>Close Time: <?php echo e((int)($customer->close_minute / 60)); ?></p>
  </div>
  <div class="card-footer text-center">
          <div class="btn-wrapper  justify-content-between">
          <a href="https://www.savingapp.co/AdminCp/public/admin/company/<?php echo e($customer->company_id); ?>/edit" data-toggle="tooltip"  id="<?php echo e($customer->id); ?>"  data-id="<?php echo e($customer->id); ?>" class="btn btn-warning approval">Edit</a>
          </div>
    </div>
</div> 
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
</div>
    
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('voyager::master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\savingappApi\resources\views/my_company.blade.php ENDPATH**/ ?>