<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style type="text/css">
body{
    font-family: Tahoma, Geneva, sans-serif;
}
    h2{
        text-align: left;
        font-size:22px;
        margin-bottom:50px;
    }
   #myTable{
       width: 100%;
       border-collapse: collapse;
   }
   th, td {
  text-align: left;
  border-bottom: 1px solid #ddd;
  padding: 5px;
height: 35px ;
}
thead {
    background-color: #19b5fe;

  padding: 5px;
  border: none;
  height: 50px;
}
td {
  text-align: center;
  vertical-align: middle;
}
th {
  text-align: center;
  vertical-align: middle;
}
.row { width: 100%;}
</style>   
<body>
<div class="container">
<div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;z-index: -1">
    <img src="1.jpg" 
         style="width: 210mm; margin: 0;" />
</div>
<p  style="position: absolute;
    left: 158mm;
    top: 22mm;">{{$customers[0]->card_number}}</p>
<p  style="position: absolute;
    left: 130mm;
    top: 29mm;"> {{$customers[0]->full_name}}</p>
<p  style="position: absolute;
      left: 130mm;
    top: 38mm;"> {{$customers[0]->phone}}</p>
<p  style="position: absolute;
      left: 130mm;
    top: 47mm;"> {{$customers[0]->address}}</p>
<p  style="position: absolute;
 left: 116mm;
    top: 22mm;"> <?php echo $new ?></p>
<p  style="position: absolute;
      left: 108mm;
    top: 134mm;"> @yield('title', Voyager::setting("admin.title"))</p>
<p  style="position: absolute;
      left: 53mm;
    top: 134mm;">  تاريخ التفعيل 
    {{$customers[0]->strat_active}}</p>
  


     <p  style="position: absolute;
      left: 2mm;
    top: 134mm;">     تاريخ الصلاحية
    {{$customers[0]->end_active}}</p>
      
 
         

</div>
</body>
</html
