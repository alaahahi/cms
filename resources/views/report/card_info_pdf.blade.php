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
    <img src="data:image/png;base64,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" 
         style="width: 210mm; margin: 0;" />
</div>
<p  style="position: absolute;
    left: 158mm;
    top: 22mm;">{{$customers[0]->card_number}}</p>
<p  style="position: absolute;
    left: 153mm;
    top: 29mm;"> {{$customers[0]->full_name}}</p>
<p  style="position: absolute;
      left: 159mm;
    top: 38mm;"> {{$customers[0]->phone}}</p>

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
