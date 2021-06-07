
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-5">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3><strong>My Product</strong></h3>
                </div>
            </div>
             <a href="" class="btn btn-primary">Add Product</a>
            <!-- <a href="" class="btn btn-primary">Download ALL Order</a> -->
            <br>
            <div class="row">
                <div class="col-md-6 text-center">           
                    <div class="form-group">
                        <label for="totaltody">Total Today</label>
                        <?php $total = 0;$count =0 ?>
                           <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $datas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $count =  $count + 1;  ?>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <input  value = "<?php echo $total ?? 0 ?>" type="text"  class="form-control mx-sm-3" disabled>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <div class="form-group">
                    <label for="totaltody">Product Count</label>
                    <input value="<?php echo $count ?? 0 ?>"  type="text"  class="form-control mx-sm-3" disabled>
                    </div>
                </div>
            </div>
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Discount Price</th>
                        <th>Image</th>
                        <th>Action</th>
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
            <h4 class="modal-title" id="exampleModalLabel">My Product</h4>
        </div>
        <div class="col-md-6">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    </div>
      <div class="modal-body">
        <form>
        <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title" class="col-form-label">Title:</label>
                    <input type="text" class="form-control" id="title">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="visible" class="col-form-label">Visible:</label>
                <input type="text" class="form-control" id="visible">
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label for="price" class="col-form-label">Price:</label>
                <input type="text" class="form-control" id="price">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="discount_price-name" class="col-form-label">Discount Price:</label>
                <input type="text" class="form-control" id="discount_price">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
        <a href="#" class="voyager-x remove-single-image" style="position:absolute;"></a>
        <img id="image" style="max-width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
    </div>
                <img src="" border="0" width="100" height"70" class="img-rounded" align="center" " id="image" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label for="translation_title_en" class="col-form-label">Translation Title<Title>En</Title> </label>
                <input type="text" class="form-control" id="translation_title_en">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="translation_desc_en" class="col-form-label">Translation Desc<Title>En</Title> </label>
                <input type="text" class="form-control" id="translation_desc_en">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label for="translation_title_ar" class="col-form-label">Translation Title<Title>Ar</Title> </label>
                <input type="text" class="form-control" id="translation_title_ar">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="translation_desc_ar" class="col-form-label">Translation Desc<Title>Ar</Title> </label>
                <input type="text" class="form-control" id="translation_desc_ar">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label for="translation_title_ku" class="col-form-label">Translation Title<Title>Ku</Title> </label>
                <input type="text" class="form-control" id="translation_title_ku">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="translation_desc_ku" class="col-form-label">Translation Desc<Title>Ku</Title> </label>
                <input type="text" class="form-control" id="translation_desc_ku">
                </div>
            </div>
        </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save">Save</button>
      </div>
    </div>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
  $(function () {

    var table = $('.data-table').DataTable({
        ajax: "<?php echo e(route('admin.my_products')); ?>",
        columns: [
            {data: 'title', name: 'title'},
            {data: 'price', name: 'price'},
            {data: 'discount_price', name: 'discount_price'},
            {data: 'image', name: 'image'},
            {data: 'action', name: 'action'},
        ]
    });


    $('body').on('click', '.edit', function () {
      var Item_id = $(this).data('id');
      $.ajax({
            type: "GET",
            url:"<?php echo e(route('admin.edit_product')); ?>/"+Item_id,
            success: function (product) {
                $('#title').val(product.title);
                $('#visible').val(product.visible);
                $('#price').val(product.price);
                $('#discount_price').val(product.discount_price);
                $('#image').attr("src","http://savingapp.co/AdminCp/storage/app/public/"+product.photo);
                $('#translation_title_en').val(product.product_translation_all.filter(x => x.lang==='en').map(x => x.title));
                $('#translation_desc_en').val(product.product_translation_all.filter(x => x.lang==='en').map(x => x.desc));
                $('#translation_title_ar').val(product.product_translation_all.filter(x => x.lang==='ar').map(x => x.title));
                $('#translation_desc_ar').val(product.product_translation_all.filter(x => x.lang==='ar').map(x => x.desc));
                $('#translation_title_ku').val(product.product_translation_all.filter(x => x.lang==='ku').map(x => x.title));
                $('#translation_desc_ku').val(product.product_translation_all.filter(x => x.lang==='ku').map(x => x.desc));
                $('.save').attr('data-id' , product.id);
                $('.modal-product').modal('show');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
        $('body').on('click', '.save', function () {
            var Item_id = $(this).data('id');
$.ajax({
        type: 'PUT' ,
        url:"<?php echo e(route('admin.edit_products')); ?>/"+Item_id,
        data: 
        {
            title:$('#title').val(),
            visible:$('#visible').val() ,
            price:$('#price').val() ,
            discount_price:$('#discount_price').val() ,
            //image:$('#image').val(),
            translation_title_en:$('#translation_title_en').val() ,
            translation_desc_en:$('#translation_desc_en').val(),
            translation_title_ar:$('#translation_title_ar').val() ,
            translation_desc_ar:$('#translation_desc_ar').val() ,
            translation_title_ku:$('#translation_title_ku').val() ,
            translation_desc_ku:$('#translation_desc_ku').val() ,

        },
        dataType: 'json',
        success:function(data){
            $('.modal-product').modal('hide');
            table.draw();
        }});
  });
  });


});
$('body').on('click', '.delete', function () {
            var Item_id = $(this).data('id');
$.ajax({
        type: 'DELETE' ,
        url:"<?php echo e(route('admin.remove_products')); ?>/"+Item_id,
        dataType: 'json',
        success:function(data){
            table.draw();
        }});
    });
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('voyager::master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\savingappApi\resources\views/my_products.blade.php ENDPATH**/ ?>