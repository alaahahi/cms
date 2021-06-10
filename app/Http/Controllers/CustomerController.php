<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Company;
use App\Models\City;
use App\Models\Box;
use App\Models\Gift;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Order;
use App\Models\Order_details;
use App\Models\App_config;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use PDF;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function service(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('customers')->select(['customers.id','customers.company','customers.service_date','customers.Address', 'customers.city_id','customers.Machines_Large', 'customers.Machines_Small', 'customers.Starting_Unit', 'customers.phone']);
            return Datatables::of($data)
                    ->addColumn('Starting_Units', function($data){
                         return date('Y-m-d', strtotime($data->Starting_Unit. ' + 0 day')) ;
                    })
                    ->addColumn('Service_Date', function($data){
                        $date =date('Y-m-d', strtotime($data->Starting_Unit. ' + 15 day'));
                        $ids = $data->id;
                        if ($data->service_date)
                        {
                        return $date; 
                        }
                  else
                  DB::update('update customers set service_date = ? where id = ?',[$date, $ids]);
                    })
                    ->make(true);
        }

        return view('customer');
    }
    public function service_order(Request $request)
    {
      
        if ($request->ajax()) {
            $data = DB::table('customers')->join('city', 'customers.city_id', '=', 'city.id')->join('order', 'customers.id', '=', 'order.Customer_id')
            ->select(['customers.id','customers.company','customers.is_service','customers.service_date','order.order_date','order.Customer_id','customers.Address','city.city', 'customers.city_id','order.Machines_Large', 'order.Machines_Small', 'customers.Starting_Unit', 'customers.phone']);
            return Datatables::of($data)
                    ->addColumn('Starting_Units', function($data){
                         return date('Y-m-d', strtotime($data->Starting_Unit. ' + 0 day')) ;
                    })
                    ->addColumn('Order_Date', function($data){
                        $date =date('Y-m-d', strtotime($data->Starting_Unit. ' + 30 day'));
                        $date_order =date('Y-m-d', strtotime($data->Starting_Unit. ' + 15 day'));
                        $ids = $data->id;
                        if ($data->service_date && $data->order_date)
                        {
                        return $date; 
                        }
                        else
                        DB::update('update order set order_date = ? where id = ?',[$date_order, $ids]);
                        DB::update('update customers set service_date	 = ? where id = ?',[$date, $ids]);
                    })
                    ->addColumn('action', function($row){
                        if ($data->is_service)
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"    data-id="'.$data->id.'" data-original-title="customers" class=" btn btn-success btn-sm ">Done</a>';
                        else
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" id="'.$data->id.'" data-id="'.$data->id.'" data-original-title="customers" class=" btn btn-danger btn-sm isdone">Service</a>';
   
                         return $btn;
                    })
                    ->rawColumns(['action'])->filter(
                        function ($query){
                            $today = date('Y-m-d');
                            $query->where('order_date', 'like', "$today");
                    
                    })
                    ->make(true);
        }

        return view('customer_order');

    }
    public function order_today(Request $request)
    {
      
        if ($request->ajax()) {
            $data = DB::table('customers')->join('city', 'customers.city_id', '=', 'city.id')->join('order', 'customers.id', '=', 'order.Customer_id')
            ->select(['customers.id','order.id as order_id','order.Monthly_Rent','order.order_date','order.is_done','customers.company','order.created_at','order.service_date','order.Customer_id','customers.Address','city.city', 'customers.city_id','order.Machines_Large', 'order.Machines_Small','order.Machines_Large_price', 'order.Machines_Small_price', 'customers.Starting_Unit', 'customers.phone']);
            return Datatables::of($data)
                    ->addColumn('orderdate', function($data){  
                        if($data->order_date == date('Y-m-d') ){
                        DB::update('update `order` set is_done = ? where id = ?',[false, $data->order_id]);
                        }
                        return $data->created_at;
                    })
                    ->addColumn('total', function($data){
                        if($data->Monthly_Rent)
                        $total = $data->Monthly_Rent ;
                        else {
                        $total =($data->Machines_Large * $data->Machines_Large_price)+($data->Machines_Small * $data->Machines_Small_price);
                        $ids = $data->order_id;
                        DB::update('update `order` set Monthly_Rent= ? where id = ?',[$total, $ids]);
                        DB::update('update `order` set order_date = ? where id = ?',[$data->created_at, $data->order_id]);
                        $date_servic =date('Y-m-d', strtotime($data->created_at. ' + 15 day'));
                        DB::update('update `order` set service_date=? where id =?',[$date_servic,$data->order_id]);
                        }
                        return $total;
                    })
                    ->addColumn('action', function($data){
                       if ($data->is_done)
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"    data-id="'.$data->order_id.'" data-original-title="IsDone" class=" btn btn-success btn-sm ">Done</a>';
                        else
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" id="'.$data->order_id.'" data-id="'.$data->order_id.'" data-original-title="IsDone" class=" btn btn-danger btn-sm isdone">Pay</a>';
   
                         return $btn;
                    })
                    ->rawColumns(['action'])->filter(
                        function ($query){
                            $today = date('Y-m-d');
                            $next_order =date('Y-m-d', strtotime($today.' +1 month'));
                            $query->where('order.order_date', 'like', "$next_order")->orWhere( 'order.created_at', '=',$today)->orwhere('order.order_date', 'like', "$today");
                    })
                    ->make(true);
        }

        return view('order_today');

    }
    public function service_today(Request $request)
    {
      
        if ($request->ajax()) {
            $data = DB::table('customers')->join('city', 'customers.city_id', '=', 'city.id')->join('order', 'customers.id', '=', 'order.Customer_id')
            ->select(['customers.id','order.is_service','order.id as order_id','order.created_at as order_create_at','customers.company','order.service_date','order.order_date','order.Customer_id','customers.Address','city.city', 'customers.city_id','order.Machines_Large', 'order.Machines_Small', 'customers.Starting_Unit', 'customers.phone','order.service_day']);
            return Datatables::of($data)
                    ->addColumn('action', function($data){
                        $today = date('Y-m-d');
                        if($data->service_date == date('Y-m-d'))
                        DB::update('update `order` set is_service = false   where id =?',[$data->order_id]);
                        if ($data->is_service)
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"    data-id="'.$data->order_id.'" data-original-title="customers" class=" btn btn-success btn-sm ">Done</a>';
                        else
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" id="'.$data->order_id.'" data-id="'.$data->order_id.'" data-original-title="customers" class=" btn btn-danger btn-sm is_service">Service</a>';
                         return $btn;
                    })
                    ->rawColumns(['action'])->filter(
                        function ($query){
                            $today = date('Y-m-d');
                            $query->where('order.service_date', 'like', "$today");
                    
                    })
                    ->make(true);
        }

        return view('service_today');

    }
    public function generatePDF_not_bills($from =0,$to=0)
    {
        $new = date('Y-m-d');
        if ($from == 0) $from ="2020-10-1";
        if ($to == 0) $to =date('Y-m-d');
          $bill = DB::table('order')
          ->join('customers', 'customers.id', '=', 'order.Customer_id')
          ->join('city', 'customers.city_id', '=', 'city.id')
          ->whereBetween('order.order_date', [$from, $to])
          ->where('order.is_done', '=', 0)
          ->where('order.order_date', '<=',$new );
          $customers = $bill->select('*')->get() ;
        $pdf = PDF::loadView('not_bills_pdf',compact('customers','new','from','to'))->setPaper('a4', 'landscape');
        return $pdf->download('Order List Today-'.$new.'..pdf');
    }  

    public function generatePDF_bills($from =0,$to=0,$total=0)
    {
        $new = date('Y-m-d');
        if ($from == 0) $from ="2020-10-1";
        if ($to == 0) $to =date('Y-m-d');
          $bill = DB::table('box')
          ->join('customers', 'customers.id', '=', 'box.Customer_id')
          ->join('order', 'order.id', '=', 'box.order_id')
          ->join('city', 'customers.city_id', '=', 'city.id')
          ->whereBetween('box.created_at', [$from, $to]);
          $customers = $bill->select('*')->get() ;
        $pdf = PDF::loadView('bills_pdf',compact('customers','new','from','to','total'))->setPaper('a4', 'landscape');
        return $pdf->download('Order List Today-'.$new.'..pdf');
    }  
        public function generatePDF_service_today()
        {
            $new = date('Y-m-d');
            $customers = DB::table('customers')
            ->join('order', 'order.Customer_id', '=', 'customers.id')
            ->select('*')->where('order.service_date', '=', $new)
            ->get();
            $pdf = PDF::loadView('service_today_pdf',compact('customers','new'))->setPaper('a4', 'landscape');
            return $pdf->download('Service Today-'.$new.'..pdf');
        }
        public function generatePDF_order_today()
        {
            $new = date('Y-m-d');
            $customers = DB::table('customers')
            ->join('order', 'order.Customer_id', '=', 'customers.id')
            ->select('*')->where('order.order_date', '=',  $new)
            ->orWhere( 'order.created_at', '=',  $new)->orWhere( 'order_date', '=',  $new)
            ->get();
            $pdf = PDF::loadView('order_today_pdf',compact('customers','new'))->setPaper('a4', 'landscape');
            return $pdf->download('Order List Today-'.$new.'..pdf');
        }  
        public function generatePDF_order_today_all()
        {
            $new = date('Y-m-d');
            $customers = DB::table('customers')
            ->join('order', 'order.Customer_id', '=', 'customers.id')
            ->select('*')->where('order.order_date', '=',  $new )
            ->orWhere( 'order.order_date', '=',  $new)->orWhere( 'order_date', '=',  $new)
            ->get();
            $pdf = PDF::loadView('order_today_all_pdf',compact('customers','new'))->setPaper('a4', 'portscape');
            return $pdf->download('ALL Order Today-'.$new.'..pdf');
        }
        public function generatePDF_order_today_all_this_month()
        {
            $new = date('Y-m-d');
            $month = date('m');
            $customers = DB::table('customers')
            ->join('order', 'order.Customer_id', '=', 'customers.id')
            ->select('order.order_date','customers.company','order.customer_id','customers.id','customers.Address','customers.phone','order.Machines_Large','order.Machines_Large_price','order.Machines_Small','order.Machines_Small_price',)
            ->whereMonth( 'order.order_date', '=',  $month )
            ->get();
            $pdf = PDF::loadView('order_today_all_pdf',compact('customers','new'))->setPaper('a4', 'portscape');
            return $pdf->stream('ALL Order Today This Month-'.$month.'..pdf');
        }  
        public function bill_today(Request $request)
        {
            $new = date('Y-m-d');
            $customers = DB::table('box')
            ->join('customers', 'customers.id', '=', 'box.Customer_id')
            ->join('order', 'order.id', '=', 'box.order_id')
            ->join('city', 'customers.city_id', '=', 'city.id')
            ->where('box.created_at', '=',  $new )
            ->select('*')->get();
            if ($request->ajax()) {
            return Datatables::of($customers)->make(true);
            }
            return   response()->json($customers);
          //return view('bill_today',compact('customers'));

        }  
        public function bills(Request $request,$from =0,$to=0)
        {
          if ($from == 0) $from ="2020-10-1";
          if ($to == 0) $to =date('Y-m-d');
            $bill = DB::table('box')
            ->join('customers', 'customers.id', '=', 'box.Customer_id')
            ->join('order', 'order.id', '=', 'box.order_id')
            ->join('city', 'customers.city_id', '=', 'city.id')
            ->whereBetween('box.created_at', [$from, $to]);
            $bills = $bill->select('*','order.id as o_id','box.created_at as created')->get() ;
            if ($request->ajax()) {
            return  Datatables::of($bills)
            ->addColumn('action', function($bills){
                 $btn = '<a href="javascript:void(0)" data-toggle="tooltip"    data-id="'.$bills->o_id.'" data-original-title="print" class=" btn-sm btn-warning  print '.$bills->o_id.' ">Print</a>
                        <a href="javascript:void(0)" data-toggle="tooltip"    data-id="'.$bills->o_id.'" data-original-title="edit" class=" btn-sm btn-success  edit '.$bills->o_id.' ">Edit</a>';
                  return $btn;
             })
            ->make(true);
            }
            return view('bills');

        } 
        public function not_bills(Request $request,$from =0,$to=0)
        {
            $new = date('Y-m-d');
          if ($from == 0) $from ="2020-10-1";
          if ($to == 0) $to =date('Y-m-d');
            $bill = DB::table('order')
            ->join('customers', 'customers.id', '=', 'order.Customer_id')
            ->join('city', 'customers.city_id', '=', 'city.id')
            ->whereBetween('order.order_date', [$from, $to])
            ->where('order.is_done', '=', 0)
            ->where('order.order_date', '<=',$new );
            $bills = $bill->select('*','order.id as o_id')->get() ;
            if ($request->ajax()) {
            return  Datatables::of($bills)
            ->addColumn('action', function($bills){
                if ($bills->is_done)
                 $btn = '<a href="javascript:void(0)" data-toggle="tooltip"    data-id="'.$bills->o_id.'" data-original-title="IsDone" class=" btn btn-success btn-sm ">Done</a>';
                 else
                 $btn = '<a href="javascript:void(0)" data-toggle="tooltip" id="'.$bills->o_id.'" data-id="'.$bills->o_id.'" data-original-title="IsDone" class="btn-danger btn-sm isdone">Pay</a>
                  <a href="javascript:void(0)" data-toggle="tooltip"    data-id="'.$bills->o_id.'" data-original-title="print" class="btn-warning btn-sm print '.$bills->o_id.' ">Print</a>
                  <a href="javascript:void(0)" data-toggle="tooltip"    data-id="'.$bills->o_id.'" data-original-title="edit" class=" btn-sm btn-success  edit '.$bills->o_id.' ">Edit</a>
                 ';
                  return $btn;
             })
            ->make(true);
            
            }
            //return   response()->json($customers);
           return view('not_bills',compact('bills','new'));

        } 
        public function invoice()
        {
            $new = date('Y-m-d');
            $customers = DB::table('customers')
            ->join('order', 'order.Customer_id', '=', 'customers.id')
            ->select('*')->where('order.order_date', '=',  $new)
            ->get();
            return view('invoice',compact('customers','new'));

        }  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\request  $request
     * @return \Illuminate\Http\Response
     */
    public function addorder(Request $request)
    {
        $name = $request->input('names');
      //  $customer   =   Product::updateOrCreate(['id' => $customerId], ['title' => $request->title, 'phone' => $request->phone]);        
        return $name;  //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $where = array('id' => $id);
        $customer  = Customer::where($where)->first();
      
        return Response::json($where);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $id;
    }
    public function isdone($id)
    {
        $new = date('Y-m-d');
        DB::update('update `order` set is_done = true ,order_date =DATE_ADD(order_date, INTERVAL 1 MONTH)  where id =?',[$id]);
        $orders = DB::table('order')
        ->join('customers', 'customers.id', '=', 'order.customer_id')
        ->select('customers.id as customer_id','order.Machines_Large', 'order.id','order.Machines_Small','order.Machines_Large_price', 'order.Machines_Small_price')->where('order.id', '=',  $id)
        ->first();
        //DB::update('update `customers` set  service_date =DATE_ADD(service_date, INTERVAL 0.5 MONTH)  where customers.id =?',[$orders->customer_id]);
        $total = ($orders->Machines_Large * $orders->Machines_Large_price)+($orders->Machines_Small * $orders->Machines_Small_price);
        DB::insert('insert into box (Customer_id, order_id,total,created_at) values (?,?,?,?)', [$orders->customer_id,$orders->id,$total,$new]);
        return response()->json(['success'=>'Item saved successfully.']);
    }
    public function print_order($id)
    {
        $new = date('Y-m-d');
        $customers = DB::table('order')
        ->join('customers', 'customers.id', '=', 'order.customer_id')
        ->select('customers.id as customer_id','order.Machines_Large','order.order_date','customers.company','customers.phone','customers.Address', 'order.id','order.Machines_Small','order.Machines_Large_price', 'order.Machines_Small_price')
        ->where('order.id', '=',  $id)
        ->get();
        $pdf = PDF::loadView('order_today_all_pdf',compact('customers','new'))->setPaper('a4', 'portscape');
        return $pdf->download('ALL Order Today-'.$new.'.pdf');
    }
    public function is_service($id)
    {
        $new = date('Y-m-d');
        $day = DB::table('order')
        ->select('service_day')->where('id', '=',  $id)
        ->first();
        DB::update('update `order` set is_service = true,service_date =DATE_ADD(service_date, INTERVAL ? day)  where id =?',[$day->service_day,$id]);
        return response()->json(['success'=>'Item saved successfully.']);
    }
    public function my_company()
    {
        $userId =  Auth::user()->id;
        $data = DB::table('company')
        ->join('users', 'users.company_id', '=', 'company.id')
        ->join('company_translation', 'company_translation.companyId', '=', 'company.id')
        ->where('users.id', '=',   $userId )
        ->where('company_translation.lang', '=', 'en' )
        ->select('*')
        ->get();
      
        return view('my_company',compact('data'));
    }
    public function my_orders()
    {
        $lang ='en';
        $companyId =  Auth::user()->company_id;
        $data = Order::Where('companyId', $companyId)->get();
        if(!empty($data)){
        foreach ($data as $order )
        {
        $order->setAttribute('user_phone',($order->users->phone));
        foreach ( $order->product as $products )
        $products->setAttribute('title_translation',($products->product_translation($lang)->first()->title));
        }
    }
        return view('my_orders',compact('data'));
    }
    public function edit_products(Request $request,$id)
    {
        $date = Carbon::now();
  
        $monthName = $date->format('F');
        $year = $date->format('Y');
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);
          if ($request->file('file')) {
              $imagePath = $request->file('file');
              $imageName = time().'.'.$request->file->extension();
              //$imageName = $imagePath->getClientOriginalName();
              $path = $request->file('file')->storeAs('product/'.$monthName.$year, $imageName, 'public');
          }
        $item= [ 
         'title'=> $request->title,
         'visible'=> $request->visible,
         'price'=> $request->price,
         'discount_price'=> $request->discount_price,
         'photo'=> 'product/'.$monthName.$year.'/'.$imageName
        ];
        if($id == 0 ){
            $userId =  Auth::user()->id;
            $company_id = DB::table('company')
            ->join('users', 'users.company_id', '=', 'company.id')
            ->where('users.id', '=',  $userId )
            ->select('company.id')
            ->first();
            $product_id= DB::table('product')->insertGetId(array('photo' => $item['photo'],'price' => $item['price'],'discount_price'=>$item['discount_price'],'visible'=>$item['visible'],'companyId'=>$company_id->id,'title'=>$item['title'])); 
            $translation =[
            [ 
                'title' => $request->translation_title_en,
                'desc' => $request->translation_desc_en,
                'lang'=> 'en',
                'productId'=>$product_id
            ],
            [ 
                'title' => $request->translation_title_ar,
                'desc' => $request->translation_desc_ar,
                'lang' => 'ar',
                'productId'=>$product_id
            ],
            [ 
                'title' => $request->translation_title_ku,
                'desc' => $request->translation_desc_ku,
                'lang' => 'ku',
                'productId'=>$product_id
            ]
        ];
        //return response()->json($translation);
        DB::table('product_translation')->insert($translation);
        return response()->json('Added Product');
        }
        else
        {
            $product_trs_en = [ 
                'title' => $request->translation_title_en,
                'desc' => $request->translation_desc_en,
                'lang'=> 'en',
                'productId'=>$id
            ];
            $product_trs_ar = [ 
                'title' => $request->translation_title_ar,
                'desc' => $request->translation_desc_ar,
                'lang' => 'ar',
                'productId'=>$id
            ];
            $product_trs_ku = [ 
                'title' => $request->translation_title_ku,
                'desc' => $request->translation_desc_ku,
                'lang' => 'ku',
                'productId'=>$id
            ];
        DB::table('product')->where('id',$id)->update($item);
        DB::table('product_translation')->where('product_translation.productId',$id)->where('product_translation.lang','en')->update($product_trs_en);
        DB::table('product_translation')->where('product_translation.productId',$id)->where('product_translation.lang','ar')->update($product_trs_ar);
        DB::table('product_translation')->where('product_translation.productId',$id)->where('product_translation.lang','ku')->update($product_trs_ku);
        return response()->json('Update Product');
        }
        //return redirect('path')->with(['message' => "Product Update data Successfully", 'alert-type' => 'success']);
    }
    public function remove_products(Request $request,$id)
    {
        DB::table('product_translation')->where('product_translation.productId',$id)->update(['deleted_at' => Carbon::now()]);
        DB::table('product')->where('id',$id)->update(['deleted_at' => Carbon::now()]);
        return redirect('path')->with(['message' => "Product deleted Successfully", 'alert-type' => 'success']);
    }
    public function edit_product(Request $request,$id)
    {
        //if ($request->ajax()) 
        //{ 
        $data =Product::where('id',$id)->first();
        foreach ($data->product_translation_all as $products )
        return $data;
        //}
    }
    public function my_products(Request $request)
    {
        $userId =  Auth::user()->id;
        $data = DB::table('company')
        ->join('users', 'users.company_id', '=', 'company.id')
        ->join('product', 'product.companyId', '=', 'company.id')
        ->join('product_translation', 'product_translation.productId', '=', 'product.id')
        ->where('users.id', '=',   $userId )
        ->where('product.deleted_at', '=',  null )
        ->where('product_translation.lang', '=', 'en' )
        ->select(['product.id','product.photo','product.price','product.discount_price','product.title', 'product.created_at'])
        ->get();
       if ($request->ajax()) 
       {
 
        return Datatables::of($data)
        ->addColumn('image', function ($data) { 
            $url= asset("http://savingapp.co/AdminCp/storage/app/public/$data->photo");
            return '<img src="'.$url.'" border="0" width="100" height"70" class="img-rounded" align="center" />';
        })
        ->addColumn('action', function ($data) {
           
            return '<a  href="javascript:void(0)" data-toggle="tooltip"    data-id="'.$data->id.'" class="btn btn-sm btn-primary pull-right edit"><i class="voyager-edit"></i></a>
               <a href="javascript:void(0)"  data-id="'.$data->id.'" class="btn btn-sm btn-danger pull-right delete"><i class="voyager-trash"></i></a>';
    })
        ->rawColumns(['image', 'action'])
        ->make(true);
     }
        return view('my_products',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function charge_card(Request $request,$cardNumber,$moblie)
    {
        $new = date('Y-m-d');
        $customers = DB::table('card')
        ->join('card_type', 'card_type.id', '=', 'card.card_type_id')
        ->where('card.card_number', '=', $cardNumber )
        ->where('card.is_valid', '=', 1)
        ->select('card.id')->first();
        if(!empty($customers))
        {
            $day = DB::table('card')
            ->join('card_type', 'card_type.id', '=', 'card.card_type_id')
            ->where('card.id', '=', $customers->id)
            ->select('card_type.validation','card_type.points')->first();
            $day_str = "+$day->validation days";
            $userIds = DB::table('users')
            ->where('users.phone', '=', $moblie )->select('id')->first();
            $card = DB::table('card_user')
            ->join('users', 'users.id', '=', 'card_user.user_id')
            ->join('card', 'card.id', '=', 'card_user.card_id')
            ->where('users.phone', '=', $moblie )->select('users.id as usersId','card_user.id','card_user.end_active')->first();
            if(!empty($card))
                {
                $end_active =date('Y-m-d',strtotime($day_str,strtotime($card->end_active)));
                DB::insert('insert into points ( `userId`, `cardId`, `point`) values (?,?,?)', [$card->usersId, $card->id,$day->points ]);
                DB::table('card_user')->where('id', $card->id)->update(['end_active' => $end_active]);
                DB::table('card')->where('id', $customers->id)->update(['is_valid' => 0]);
                return   response()->json( $end_active);
                }
            if(!empty($userIds))
                {
                    $end_active =date('Y-m-d',strtotime($day_str,strtotime(str_replace('/', '-', $new))));
                    DB::insert('insert into card_user ( `card_id`, `user_id`, `strat_active`, `end_active`) values (?,?,?,?)', [$customers->id,$userIds->id,$new, $end_active]);
                    DB::insert('insert into points ( `userId`, `cardId`, `point`) values (?,?,?)', [$userIds->id,$customers->id,$day->points ]);
                    DB::table('card')->where('id', $customers->id)->update(['is_valid' => 0]);
                    return   response()->json($end_active);
                }
            if(empty($userIds))
            {
            $end_active =date('Y-m-d',strtotime($day_str,strtotime(str_replace('/', '-', $new))));
            $user_id= DB::table('users')->insertGetId(array('phone' => $moblie));
            DB::insert('insert into card_user ( `card_id`, `user_id`, `strat_active`, `end_active`) values (?,?,?,?)', [$customers->id,$user_id,$new, $end_active]);
            DB::insert('insert into points ( `userId`, `cardId`, `point`) values (?,?,?)', [$user_id,$customers->id,$day->points ]);
            DB::table('card')->where('id', $customers->id)->update(['is_valid' => 0]);
            return   response()->json( $end_active);
            }
        }
        else 
        return response()->json('This Card is not Valid');
    } 
    public function getallcart(Request $request ,$moblie,$lang)
    { 
        $userid = DB::table('users')
        ->where('users.phone', '=', $moblie )->select('users.id')->first();
        $cart = DB::table('cart')
        ->join('product', 'product.id', '=', 'cart.productId')
        ->join('product_translation', 'product_translation.productId', '=', 'product.id')
        ->where('cart.userId', '=', $userid->id )
        ->where('product_translation.lang', '=', $lang )
        ->select('*')->get();
        return response()->json($cart);
        
    }
    public function addtocart(Request $request ,$moblie)
    { 
        $card = DB::table('users')
        ->where('users.phone', '=', $moblie )->select('users.id')->first();
        DB::insert('insert into cart ( `quantity`, `productId`, `userId`) values (?,?,?)', [$request->quantity,$request->productId,$card->id]);
    }
    public function removfromcart($moblie,$id)
    { 
        $card = DB::table('users')
        ->where('users.phone', '=', $moblie )->select('users.id')->first();
        DB::table('cart')->where('userId', '=', $card->id)->where('productId', '=', $id)->delete();
    }
    public function removallcart($moblie)
    { 
        $card = DB::table('users')
        ->where('users.phone', '=', $moblie )->select('users.id')->first();
        DB::table('cart')->where('userId', '=', $card->id)->delete();
    }
    public function products(Request $request ,$ids,$lang)
    { 
        $products = DB::table('product')
        ->join('product_translation', 'product_translation.productId', '=', 'product.id')
        ->whereIn('product.id',explode(",",$ids) )
        ->where('product_translation.lang', '=', $lang )
        ->select('*')
        ->get();
        return response()->json($products);
    }
    public function companies(Request $request ,$id ,$lang)
    { 
        $companies = 
        DB::table('company')
        ->join('company_translation', 'company_translation.companyId', '=', 'company.id')
        ->whereIn('company.id',explode(",",$id) )
        ->where('company_translation.lang', '=', $lang )
        ->select('*')
        ->get();
        return response()->json($companies);
        
    }
    public function search(Request $request ,$q ,$lang)
    { 
        $companies = 
        DB::table('company')
        ->join('company_translation', 'company_translation.companyId', '=', 'company.id')
        ->where('company_translation.title', 'like', "%{$q}%")
        ->where('company_translation.lang', '=', $lang )
        ->where('company.visible', '=', '1' )
        ->select('*')
        ->get();
        $products = DB::table('product')
        ->join('product_translation', 'product_translation.productId', '=', 'product.id')
        ->where('product_translation.title', 'like', "%{$q}%")
        ->where('product_translation.lang', '=', $lang )
        ->where('product.visible', '=', '1' )
        ->where('product.deleted_at', '=',  null )
        ->select('*')
        ->get();
         
        return response()->json(['companies'=>$companies,'products'=>$products]);
        
    }
    public function categories(Request $request ,$lang)
    { 
        $category = 
        DB::table('category')
        ->join('category_translation', 'category_translation.categoryId', '=', 'category.id')
        ->where('category.visible', '=', '1' )
        ->where('category_translation.lang', '=', $lang )
        ->select('*')
        ->get();
        return response()->json($category);
        
    }
    public function categories_companies(Request $request ,$categoryId ,$lang)
    { 
        $categories_companies = 
        DB::table('category')
        ->join('company', 'company.categoryId', '=', 'category.id')
        ->join('company_translation', 'company_translation.companyId', '=', 'company.id')
        ->where('company.visible', '=', '1' )
        ->where('category.id', '=', $categoryId )
        ->where('company_translation.lang', '=', $lang )
        ->select('*')
        ->get();
        return response()->json($categories_companies);
        
    }
    public function companies_products(Request $request ,$companyId ,$lang)
    { 
        $companies_products = 
        DB::table('company')
        ->join('product', 'product.companyId', '=', 'company.id')
        ->join('product_translation', 'product_translation.productId', '=', 'product.id')
        ->where('company.id', '=', $companyId )
        ->where('product.deleted_at', '=',  null )
        ->where('product.visible', '=', '1' )
        ->where('product_translation.lang', '=', $lang )
        ->select('*')
        ->get();
        return response()->json($companies_products);
    }
    public function home(Request $request ,$lang)
    { 
        $companies = 
        DB::table('company')
        ->join('company_translation', 'company_translation.companyId', '=', 'company.id')
        ->where('company.featured', '=', true )
        ->where('company.visible', '=', '1' )
        ->where('company_translation.lang', '=', $lang )
        ->select('*')
        ->get();
        $products = DB::table('product')
        ->join('product_translation', 'product_translation.productId', '=', 'product.id')
        ->where('product.featured', '=', true )
        ->where('product.deleted_at', '=',  null )
        ->where('product.visible', '=', '1' )
        ->where('product_translation.lang', '=', $lang )
        ->select('*')
        ->get();
         
        return response()->json(['companies'=>$companies,'products'=>$products]);
        
    }

    public function user_point($moblie)
    { 
        $userId = DB::table('users')
        ->where('users.phone', '=', $moblie )->select('id')->first();
        if(!empty($userId))
        {
            $user_info = DB::table('users')
            ->join('points', 'points.userId', '=', 'users.id')
            ->where('users.id', '=', $userId->id )
            ->sum('points.point');
            return response()->json($user_info);
        }
        
    }
    public function orders(Request $request ,$moblie)
    {
        $userIds = DB::table('users')
        ->join('card_user', 'card_user.user_id', '=', 'users.id')
        ->where('users.phone', '=', $moblie )->select('users.id')->first();
        if(!empty($userIds))
        {
        $now = Carbon::now();
        $products =$request;
        $total=0;
        $item = [];
        $companyId= [];
        foreach($products->data as $product)
        {
            $companyId[]= DB::table('product')
            ->where('product.id', '=',$product['productId'] )
            ->select('product.companyId')->first();
        }
        foreach($companyId as $company)
        {
            if($company->companyId != $companyId[0]->companyId )
              return response()->json("diffrent commpany");
        }
        $order_id= DB::table('order')->insertGetId(array('userId' => $userIds->id,'companyId' => $companyId[0]->companyId,'created_at'=>$now,'updated_at'=>$now),);    
        foreach($products->data as $product)
        {
            if(!empty($product))
            {
                $price = DB::table('product')->where('product.id', '=', $product['productId'] )->select('discount_price')->first();
                $item[] = [ 
                    'orderId'   => $order_id,
                    'productId' => $product['productId'],
                    'quantity'  => $product['quantity'],
                    'price'     => $price->discount_price,
                    'created_at'=>$now,
                    'updated_at'=>$now
                ];
                $total = $total+($product['quantity']*$price->discount_price );
            }
       
        }
        DB::table('order')->where('id',$order_id)->update(['order_total' => $total]);
        DB::table('order_details')->insert($item);
            return response()->json("ok");
    }
    else
    return response()->json("Account is Not Valid,Please Activated");
        
    }
    public function getorders(Request $request ,$moblie ,$lang)
    {
        $userIds = DB::table('users')
        ->where('users.phone', '=', $moblie )->select('id')->first();
        if(!empty($userIds))
        {
        $orders = Order::Where('userId', $userIds->id)->get();
        foreach ($orders as $order ){
        foreach ( $order->product as $products )
        $products->setAttribute('title_translation',($products->product_translation($lang)->first()->title));
        }
        return response()->json($orders);
        }
        else
        return response()->json("user not found");
    }
    public function getusercompany(Request $request ,$moblie )
    {
        $userId = DB::table('users')
        ->where('users.phone', '=', $moblie )->select('id')->first();
        if(!empty($userId))
        {
        $user_company_info = DB::table('company')
        ->join('users', 'users.company_id', '=', 'company.id')
        ->join('company_translation', 'company_translation.companyId', '=', 'company.id')
        ->where('users.id', '=', $userId->id )
        ->select('*')
        ->get();
        return response()->json($user_company_info);
        }
        else
        return response()->json("user not found");
    
    }
    public function productcompany(Request $request ,$moblie)
    {

  
        $product =$request;
        $company_id = DB::table('users')
        ->where('users.phone', '=', $moblie )->select('company_id')->first();
        if(!empty($company_id))
        {
            $item= [
                'lang'                  => $product['lang'],
                'desc'                  => $product['desc'],
                'title'                 => $product['title'],
                'photo'                 => $product['photo'],
                'price'                 => $product['price'],
                'discount_price'        => $product['discount_price'],
                'discount_start_data'   => $product['discount_start_data'],
                'discount_end_data'     => $product['discount_end_data'],
                'visible'               => $product['visible'],
                'companyId'             => $company_id->company_id,
              
            ];
            if(!$product['id']){
            $product_id= DB::table('product')->insertGetId(array('photo' => $product->photo,'price' => $product->price,'discount_price'=>$product->discount_price,'discount_start_data'=>$product->discount_start_data
            ,'discount_end_data'=>$product->discount_end_data,'visible'=>$product->visible,'companyId'=>$company_id->company_id,'title'=>$product->title)); 
            foreach($product->data as $product_tr)
            {
                if(!empty($product_tr))
                {
                    $product_trs[] = [ 
                        'title' => $product_tr['title'],
                        'desc' => $product_tr['desc'],
                        'lang' => $product_tr['lang'],
                        'productId'=> $product_id
                    ];
                }
           
            }
            DB::table('product_translation')->insert($product_trs);  
            return response()->json('Added');
            }
            else{
            $productId = DB::table('product')
            ->where('product.id', '=',$product['id'])->select('id')->first();
            if($productId->id){
            DB::table('product')->where('id',$productId->id)->update($item);
            return response()->json("Updated" );
            }
            else 
            return response()->json("Product Id not valid" );
            }
            
    }
    }
    public function gift(Request $request ,$lang)
    { 
        $gift=Gift::where('visible',1)->get();
        foreach ($gift as $gifts)
        $gifts->setAttribute('gift_translation',($gifts->gift_translation($lang)->first()));
        return response()->json($gift);
        
    }
    public function winner(Request $request ,$lang)
    { 
        $gift = DB::table('winner')
        ->join('users', 'users.id', '=', 'winner.users_id')
        ->join('gift', 'gift.id', '=', 'winner.gift_id')
        ->join('gift_type', 'gift_type.id', '=', 'gift.gift_type_id')
        ->join('gift_translation', 'gift_translation.giftId', '=', 'gift.id')
        ->select('gift.phont','users.phone','users.name','gift_translation.title','gift_translation.desc','winner.created_at as date','gift_type.title as type')
        ->where('gift_translation.lang', '=', $lang )
        ->get();
        return response()->json($gift);
        
    }
    public function approval(Request $request ,$id)
    { 
        $order=DB::table('order')->where('id',$id)->first();
        $point_order =(int) ($order->order_total * 0.001);
        DB::table('order')->where('id',$id)->update(['is_accepted' => '1']);
        DB::insert('insert into points ( `userId`, `orderId`,`point`) values (?,?,?)', [$order->userId,$order->id,$point_order]);
        return redirect('path')->with(['message' => "Product approval Successfully", 'alert-type' => 'success']);
        
    }
    public function rejection(Request $request ,$id)
    { 
        DB::table('order')->where('id',$id)->update(['is_accepted' => '2']);
        return redirect('path')->with(['message' => "Product rejection Successfully", 'alert-type' => 'success']);     return response()->json(['success'=>'Item saved successfully.']);
    }
    public function user_info($moblie)
    { 
        $userId = DB::table('users')
        ->where('users.phone', '=', $moblie )->select('id')->first();
        if(!empty($userId))
        {
            $user_info = DB::table('users')
            ->where('users.id', '=', $userId->id )
            ->select('*')->first();
            return response()->json($user_info);
        }
        
    }
    public function edit_user_info(Request $request ,$moblie)
    { 
        $name=$request->name;
        $userId = DB::table('users')
        ->where('users.phone', '=', $moblie )->select('id')->first();
        if(!empty($userId))
        {
            DB::table('users')->where('id',$userId->id)->update(['name' => $name]);
            return response()->json('Update user info');
        }
        
    }
    public function app_config(Request $request )
    { 
        return response()->json(App_config::all());
    }


    public function clients(Request $request)
    {
        $data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->where('client.deleted_at', '=',  null )
        ->select(['client.id','cards.card_number','client.full_name','client.phone','card_user.strat_active','card_user.end_active', 'card_type.title'])
        ->get();       
       if ($request->ajax()) 
       {
 
        return Datatables::of($data)
   
        ->addColumn('action', function ($data) {
            return '<a  href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" class="btn btn-sm btn-primary pull-right edit"><i class="voyager-edit"></i></a>
               <a href="javascript:void(0)"  data-id="'.$data->id.'" class="btn btn-sm btn-danger pull-right delete"><i class="voyager-trash"></i></a>';
    })
        ->rawColumns(['action'])
        ->make(true);
     }
        return view('clients',compact('data'));
    }

    public function edit_clients(Request $request,$id)
    {
        $date = Carbon::now();
        $userId =  Auth::user()->id;
        $item= [ 
         'full_name'=> $request->full_name,
         'phone'=> $request->phone,
         'birth_date'=> $request->birth_date,
         'card_type_id'=> $request->card_type_id,
         'card_number'=> $request->card_number,
         'strat_active'=> $request->strat_active,
        ];
        if($id == 0 ){
            $request->validate([ 
                'full_name' => 'required',
                'phone' => 'required',
                'birth_date' => 'required',
                'card_number' => 'required|max:12',
                'strat_active' => 'required',]);

            $day = DB::table('card_type')->where('card_type.id', '=', $item['card_type_id'] )->select('validation')->first()->validation;
            $day_str="+$day days";
            $end_active =date('Y-m-d',strtotime($day_str,strtotime($item['strat_active'])));
            DB::table('card_user')->insert(array('card_id' => DB::table('cards')->insertGetId(array('card_number' => $item['card_number'],'card_type_id' => $item['card_type_id'],'author_id'=>$userId,'created_at'=>$date)),'client_id' =>DB::table('client')->insertGetId(array('full_name' => $item['full_name'],'phone' => $item['phone'],'birth_date'=>$item['birth_date'],'author_id'=>$userId,'created_at'=>$date)),'strat_active' => $item['strat_active'],'author_id'=>$userId,'end_active' =>  $end_active,'created_at'=>$date));
            return response()->json('Added Client');
        }
        else
        {
            $request->validate([ 
                'full_name' => 'required',
                'phone' => 'required',
                'birth_date' => 'required']);
        DB::table('client')->where('id',$id)->update(['full_name' => $item['full_name'],'phone' => $item['phone'],'birth_date'=>$item['birth_date'],'updated_at'=>$date]);
        return response()->json('Update client');
        }
        //return redirect('path')->with(['message' => "Product Update data Successfully", 'alert-type' => 'success']);
    }
    public function remove_clients($id)
    {
        DB::table('client')->where('id',$id)->update(['deleted_at' => Carbon::now()]);
        return response()->json('Client deleted Successfully');
    }
    public function edit_client($id)
    {
        $data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->where('client.id', '=',$id)
        ->select(['client.id','cards.card_number','client.full_name','client.phone','card_user.strat_active','card_user.end_active', 'card_type.title' ,'client.birth_date'])
        ->first();
        return response()->json($data);       
    }
    public function check_card(Request $request,$q="")
    { 
        $data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('servicecardtype', 'servicecardtype.card_type_id', '=', 'card_type.id')
        ->join('services', 'services.id', '=', 'servicecardtype.services_id')
        ->join('service_client', 'service_client.service_id', '=', 'services.id')
        ->join('users', 'users.id', '=', 'service_client.user_chack')
        ->where('client.deleted_at', '=',  null )
        ->where('cards.card_number', '=', $q )
        ->select(['client.id','services.title','service_client.date','service_client.number','users.name'])
        ->get();      
        //return response()->json($data);  
       if ($request->ajax()) 
       {
        return Datatables::of($data)->make(true);
     }
        return view('check_card',compact('data'));
    }
    public function check_card_no($q)
    { 
        $data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        //->join('servicecardtype', 'servicecardtype.card_type_id', '=', 'card_type.id')
        //->join('services', 'services.id', '=', 'servicecardtype.services_id')
        ->where('client.deleted_at', '=',  null )
        ->where('cards.card_number', '=', $q )
        ->select("*")
        ->first();
        return response()->json($data);        
    }
    public function card_service($q)
    {  
        $data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('servicecardtype', 'servicecardtype.card_type_id', '=', 'card_type.id')
        ->join('services', 'services.id', '=', 'servicecardtype.services_id')
        ->where('cards.card_number', '=', $q )
        ->select(['services.title','services.id as services_id','client.id as client_id'])
        ->get();
        if($data)      
        return response()->json($data);  
        else 
        return response()->json(0); 

    }
    public function submit_service($client,$services)
    { 
        $date = Carbon::now();
        $userId =  Auth::user()->id;
        $number=DB::table('service_client')->where('service_id', '=', $services )->where('client_id', '=', $client)->count();
        DB::table('service_client')->insert(array('service_id' => $services,'client_id' => $client,'date'=>$date,'number'=>$number+1,'user_chack'=>$userId ,'created_at'=>$date));
        return response()->json('Added Client Services');
    }
}