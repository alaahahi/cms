@extends('voyager::master')


@section('content')

<h1></h1>
<div class="container">
<div class="row">
@foreach ($data as $customer)
<div class="col-md-4">
<div class="card" style="border-radius:15px">
<div class="card-header text-center" style="padding: 3%;">
Customer Mobile: {{ $customer->user_phone }}
</div>
@foreach ($customer->product as $products)
<div style="border-radius: 5px;margin: 3%;border: 1px solid #eee;">
<img  class="img-circle" src="http://savingapp.co/AdminCp/storage/app/public/{{ $products->photo }}" alt="Avatar" style="height:100px;width:100px;float: left;padding: 2%;" >
<p>Product : {{ $products->title_translation }}</p>
<p>Price : {{ $products->discount_price }}</p>
<p>Quantity : {{$products->pivot->quantity}}</p>
</div>
@endforeach
  <div class="container">
    <h4><b>Total : {{ $customer->order_total }}</b></h4>
    <p>Date : {{$customer->created_at}}</p>
    <p>Accepted : {{ $customer->is_accepted }}</p>
    <div class="card-footer text-center">
          <div class="btn-wrapper  justify-content-between">
          @if ($customer->is_accepted==0)
          <a href="javascript:void(0)" data-toggle="tooltip"    data-id="{{$customer->id}}"  class="btn btn-danger rejection">Rejection</a>
          <a href="javascript:void(0)" data-toggle="tooltip"  id="{{$customer->id}}"  data-id="{{$customer->id}}" class="btn btn-warning approval">Approval</a>
          @endif
          @if ($customer->is_accepted==1)
          <a href="javascript:void(0)" data-toggle="tooltip"     class="btn btn-success ">Compleated</a>
          @endif
          @if ($customer->is_accepted==2)
          <a href="javascript:void(0)" data-toggle="tooltip"     class="btn btn-danger">Rejected</a>
          @endif
          </div>
    </div>
</div>
</div>
</div>
@endforeach

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
    
      $.get("{{ route('admin.approval') }}/"+Item_id ).done(function() {
        $('#'+Item_id).attr('disabled', false); 
        $('#'+Item_id).css("background-color", "#2ecc71").text("Done Approval");
});
   });
   $('body').on('click', '.rejection', function () {
      $(this).attr('disabled', true);      
      var Item_id = $(this).data('id');
      debugger;
    
      $.get("{{ route('admin.rejection') }}/"+Item_id ).done(function() {
        $('#'+Item_id).attr('disabled', false); 
        $('#'+Item_id).css("background-color", "#2ecc71").text("Done Rejection");
});
   });
</script>
  @endsection