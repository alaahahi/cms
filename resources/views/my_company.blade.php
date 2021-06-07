@extends('voyager::master')


@section('content')

<h1>Add Client</h1>
<div class="container">
<div class="container">
<div class="row">
@foreach ($data as $customer)
<div class="col-md-4">
<div class="card" style="border-radius:15px">
  <img src="http://savingapp.co/AdminCp/storage/app/public/{{ $customer->photo }}" alt="Avatar" style="width:100%" >
  <img src="http://savingapp.co/AdminCp/storage/app/public/{{ $customer->logo }}" alt="Avatar" style="width:15%;position: relative;
    bottom: 60px;
    left: 10px;" >
  <div class="container">
    <h4><b>{{ $customer->title }}</b></h4>
    <p>{{ $customer->desc }}</p>
    <p>Open Time: {{ $customer->open_minute  / 60 }}</p>
    <p>Close Time: {{ (int)($customer->close_minute / 60)}}</p>
  </div>
  <div class="card-footer text-center">
          <div class="btn-wrapper  justify-content-between">
          <a href="https://www.savingapp.co/AdminCp/public/admin/company/{{$customer->company_id}}/edit" data-toggle="tooltip"  id="{{$customer->id}}"  data-id="{{$customer->id}}" class="btn btn-warning approval">Edit</a>
          </div>
    </div>
</div> 
</div>
@endforeach
</div>
</div>
    
</div>
@endsection