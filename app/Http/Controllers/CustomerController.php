<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
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
use PHPUnit\TextUI\XmlConfiguration\Group;

class CustomerController extends Controller
{
    public function clients(Request $request)
    {
        $userId =  Auth::user();
        $date = date('Y-m-d h:i');
        $data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('users', 'users.id', '=', 'cards.author_id')
        ->where('client.deleted_at', '=',  null )
        ->where('card_user.enter_id', '=', $userId->id )
        ->where('cards.is_valid', '=', 1 )
        ->where('card_user.end_active', '>=',   $date  )
        ->select(['client.id','cards.card_number','card_user.created_at','users.name','client.full_name','client.phone','card_user.strat_active','card_user.end_active', 'card_type.title'])
        ->orderBy('card_user.created_at','desc')
        ->get();       
       if ($request->ajax()) 
       {
        return Datatables::of($data)
        ->addColumn('action', function ($data) {
            return '<a href="javascript:void(0)"  data-id="'.$data->id.'" class="btn  btn-sm btn-primary pull-right edit"><i class="voyager-edit"></i></a>';
            // <a href="javascript:void(0)"  data-id="'.$data->id.'" class="btn btn-sm btn-danger pull-right delete"><i class="voyager-trash"></i></a>
        }
        )
        ->rawColumns(['action'])
        ->make(true);
     }
        return view('clients',compact('data'));
    }

    public function edit_clients(Request $request,$id)
    {
        $date = date('Y-m-d h:i');
        $userId = $request->card_user_id;// Auth::user()->id;
        $names=  $request->names ? $request->names : $request->name1.','.$request->name2.','.$request->name3.','.$request->name4.','.$request->name5.','.$request->name6.','
        .$request->name7.','.$request->name8.','.$request->name9.','.$request->name10;

        $item= [ 
         'full_name'=> $request->full_name,
         'phone'=> $request->phone,
         'birth_date'=> $request->birth_date,
         'card_type_id'=> $request->card_type_id,
         'card_user_id'=> $request->card_user_id,
         'card_number'=> $request->card_number,
         'address'=> $request->address,
         'strat_active'=> $request->strat_active,
         'names'=> $names
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
            DB::table('card_user')->insert(array('card_id' => DB::table('cards')->insertGetId(array('card_number' => $item['card_number'],'card_type_id' => $item['card_type_id'],'author_id'=>$userId,'created_at'=>$date)),'client_id' =>DB::table('client')->insertGetId(array('full_name' => $item['full_name'],'phone' => $item['phone'],'address'=>$item['address'],'birth_date'=>$item['birth_date'],'author_id'=>$userId,'created_at'=>$date)),'author_id'=>$userId,'strat_active' => $item['strat_active'],'names' =>$item['names'],'end_active' =>  $end_active,'created_at'=>$date,'enter_id'=> Auth::user()->id));
            return response()->json('Added Client');
        }
        else
        {
            $request->validate([ 
                'full_name' => 'required',
                'phone' => 'required',
                'birth_date' => 'required']);
        DB::table('client')->where('id',$id)->update(['full_name' => $item['full_name'],'phone' => $item['phone'],'birth_date'=>$item['birth_date'],'address'=>$item['address'],'updated_at'=>$date]);
        DB::table('card_user')->where('card_user.client_id',$id)->update(['names' => $item['names']]);

        return response()->json("done");
        }
        //return redirect('path')->with(['message' => "Product Update data Successfully", 'alert-type' => 'success']);
    }
    public function remove_clients($id)
    {
        DB::table('client')->where('id',$id)->update(['deleted_at' => date('Y-m-d h:i')]);
        return response()->json('Client deleted Successfully');
    }
    public function edit_client($id)
    {
        $userId =  Auth::user();
        $data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('users', 'users.id', '=', 'cards.author_id')
        ->where('client.id', '=',$id)
        ->where('cards.is_valid', '=', 1 )
        ->where('client.deleted_at', '=',  null )
        ->select(['client.id','cards.card_number','users.name','client.full_name','client.phone','client.address','card_user.strat_active','card_user.names','card_user.end_active', 'card_type.title' ,'client.birth_date'])
        ->first();
        return response()->json($data);       
    }
    
    public function check_card(Request $request,$q="",$type="")
    { 
        $date = date('Y-m-d');
        $data = DB::table('services')
        ->join('service_client', 'service_client.service_id', '=', 'services.id')
        ->join('client', 'client.id', '=', 'service_client.client_id')
        ->join('users', 'users.id', '=', 'service_client.user_chack')
        ->join('cards', 'cards.card_number', '=', 'service_client.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('card_user', 'card_user.card_id', '=', 'cards.id')
        ->where('client.deleted_at', '=',  null )
        ->where('card_user.end_active', '>',   $date  )
        ->where('cards.card_type_id', '=', $type )
        ->where('cards.card_number', '=', $q )
        ->select(['client.id','services.title','service_client.date','service_client.number','users.name','cards.card_number'])
        ->get();      
       if ($request->ajax()) 
       {
        return Datatables::of($data)->make(true);
     }
        return view('check_card',compact('data'));
    }
    public function check_card_no($q,$type="")
    { 
        $date = date('Y-m-d');
        $data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        //->join('servicecardtype', 'servicecardtype.card_type_id', '=', 'card_type.id')
        //->join('services', 'services.id', '=', 'servicecardtype.services_id')
        ->where('card_user.end_active', '>',   $date  )
        ->where('cards.is_valid', '=', 1 )
        ->where('client.deleted_at', '=',  null )
        ->where('cards.card_number', '=', $q )
        ->where('card_type.id', '=', $type )
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
        ->where('cards.is_valid', '=', 1 )
        ->where('client.deleted_at', '=',  null )
        ->where('cards.card_number', '=', $q )
        ->select(['services.title','services.id as services_id','client.id as client_id'])
        ->get();
        if($data)      
        return response()->json($data);  
        else 
        return response()->json(0); 

    }
    public function submit_service($client,$services,$q)
    { 
        $date = date('Y-m-d h:i');
        $userId =  Auth::user()->id;
        $number=DB::table('service_client')->where('service_id', '=', $services )->where('client_id', '=', $client)->count();
        DB::table('service_client')->insert(array('service_id' => $services,'client_id' => $client,'date'=>$date,'number'=>$number+1,'user_chack'=>$userId ,'card_id'=>$q));
        return response()->json('Added Client Services');
    }
    public function card(Request $request,$q="")
    { 
        $data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('servicecardtype', 'servicecardtype.card_type_id', '=', 'card_type.id')
        ->join('services', 'services.id', '=', 'servicecardtype.services_id')
        ->where('cards.is_valid', '=', 1 )
        ->where('cards.card_number', '=', $q )
        ->select(['services.title','services.id as services_id','client.id as client_id'])
        ->get();
        //return response()->json($data);
        if ($request->ajax()) 
        {
         return Datatables::of($data)->make(true);
      }
        return view('report/card',compact('data'));
    }
    
    public function generatePDF_card_order($q="")
    {
        $new = date('Y-m-d');
        $customers = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->where('cards.is_valid', '=', 1 )
        ->where('client.deleted_at', '=',  null )
        ->where('cards.card_number', '=', $q )
        ->select('client.full_name','client.address','client.birth_date','cards.card_number','card_user.strat_active','card_user.end_active','client.phone','card_type.title as type')
        ->get();
        //return response()->json($customers);
        if(!empty($customers->first())){
        $pdf = PDF::loadView('report/card_info_pdf',compact('customers','new'));
        return View('report/card_info_pdf',compact('customers','new'));;
        }
        else 
        return response()->json("No Cards");

    }
    public function generatePDF_card($q="")
    {
        $new = date('Y-m-d');
        $customers =  DB::table('services')
        ->join('service_client', 'service_client.service_id', '=', 'services.id')
        ->join('client', 'client.id', '=', 'service_client.client_id')
        ->join('users', 'users.id', '=', 'service_client.user_chack')
        ->join('cards', 'cards.card_number', '=', 'service_client.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->where('cards.is_valid', '=', 1 )
        ->where('client.deleted_at', '=',  null )
        ->where('cards.card_number', '=', $q )
        ->select('client.full_name','client.birth_date','cards.card_number','card_user.strat_active','card_user.end_active','client.phone','services.title','card_type.title as type','users.name','service_client.date')
        ->get();
        //return response()->json($customers);
        if(!empty($customers->first())){
        $pdf = PDF::loadView('report/card_pdf',compact('customers','new'));
        return $pdf->download($q.' '.$new.'..pdf');
        }
        else 
        return response()->json("No Cards");

    }
    public function generatePDF_card_info($q="")
    {
        $new = date('Y-m-d');
        $customers = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->where('cards.is_valid', '=', 1 )
        ->where('client.deleted_at', '=',  null )
        ->where('cards.card_number', '=', $q )
        ->select('client.full_name','client.address','client.birth_date','cards.card_number','card_user.strat_active','card_user.end_active','client.phone','card_type.title as type')
        ->get();
        //return response()->json($customers);
        if(!empty($customers->first())){
          return view('report/card_info_pdf',compact('customers','new'));
        $pdf = PDF::loadView('report/card_info_pdf',compact('customers','new'));
        return $pdf->download($q.' '.$new.'..pdf');
        }
        else 
        return response()->json("No Cards");

    } 
    public function cards_from_to(Request $request,$from=0,$to=0,$type="",$pdf_download=false)
    {
        $new = date('Y-m-d');
        $type_ar="";
        if ($from == 0) $from ="2021-06-01";
        if ($to == 0) $to =date('Y-m-d');
        $customers_temp = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->where('cards.is_valid', '=', 1 )
        ->where('client.deleted_at', '=',  null );
        if($type=="started")
        {
        $customers_result=$customers_temp
        ->whereBetween('card_user.strat_active', [$from, $to]);
        $type_ar="  جميع البطاقات الفعالة من تاريخ ".$from." إلى تاريخ".$to;
        }
        if($type=="ended")
        {
        $customers_result=$customers_temp
        ->whereBetween('card_user.end_active', [$from, $to]);
        $type_ar="  جميع البطاقات الغير الفعالة من تاريخ ".$from." إلى تاريخ".$to;
        }
        if($type=="active")
        {
        $customers_result=$customers_temp
        ->where('card_user.end_active','>=',$new);
        $type_ar=" جميع البطاقات الفعالة لتاريخ ".$new;
        }
        if($type=="finished")
        {
        $customers_result=$customers_temp
        ->where('card_user.end_active','<',$new);
        $type_ar=" جميع البطاقات الغير الفعالة لتاريخ ".$new;
        }
        if($type=="all")
        {
        $customers_result=$customers_temp;
        $type_ar=" جميع البطاقات الفعالة وغير الفعالة لتاريخ".$new;
        }

        $customers=$customers_result->select('*')->get();
        if ($request->ajax()) 
        {
         return Datatables::of($customers)->make(true);
        }
        if($pdf_download){
        if(!empty($customers->first())){
        $pdf = PDF::loadView('report/card_from_to_pdf',compact('customers','new','type_ar'));
        return $pdf->download(' '.$new.'..pdf');
        }
        else 
        return response()->json("No Cards");
        }
        return response()->json($customers);
    }  
    public function service(Request $request,$q="")
    { 
        $data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('servicecardtype', 'servicecardtype.card_type_id', '=', 'card_type.id')
        ->join('services', 'services.id', '=', 'servicecardtype.services_id')
        ->where('cards.is_valid', '=', 1 )
        ->where('client.deleted_at', '=',  null )
        ->where('cards.card_number', '=', $q )
        ->select(['services.title','services.id as services_id','client.id as client_id'])
        ->get();
        //return response()->json($data);
        if ($request->ajax()) 
        {
         return Datatables::of($data)->make(true);
        }
        return view('report/service',compact('data'));
    }
    public function check_service(Request $request,$from=0,$to=0,$type=0,$pdf_download=false)
    { 
        //if ($from == 0) $from ="2021-06-01";
        //if ($to == 0) $to =date('Y-m-d');
        $new = date('Y-m-d');
        $type_ar="";
        $form_to_data="";
        $data_temp = DB::table('services')
        ->join('service_client', 'service_client.service_id', '=', 'services.id')
        ->join('client', 'client.id', '=', 'service_client.client_id')
        ->join('users', 'users.id', '=', 'service_client.user_chack')
        ->join('cards', 'cards.card_number', '=', 'service_client.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->where('cards.is_valid', '=', 1 )
        ->where('client.deleted_at', '=',  null );
        if($from !=0 && $to!=0)
        {
        $form_to_data= $data_temp->whereBetween('date', [$from, $to]);  
        }
        else
        {
        $form_to_data=$data_temp;
        }
        if($type==0 || $type=="undefined")
        {
            $data= $form_to_data;
            $type_ar=" جميع الخدمات  لتاريخ".$new;
            
        }
        else
        {
            $data= $form_to_data->where('services.id', '=', $type );
            if(!empty($data->first())){
                $type_ar=" جميع الخدمات  المقدمة من ".$data->select(['services.title'])->first()->title;
            }
           
        }

        $data_service=$data->select(['services.title','service_client.date','card_type.title as type','cards.card_number','service_client.number','users.name'])
     
        ->get();
        
        //return response()->json($data_service);  
       if ($request->ajax()) 
       {
        return Datatables::of($data_service)->make(true);
       }
       if($pdf_download){
        $customers=$data_service;
        if(!empty($customers->first())){
        $pdf = PDF::loadView('report/service_from_to_pdf',compact('customers','new','type_ar'));
        return $pdf->download(' '.$new.'..pdf');
        }
        else 
        return response()->json("No Services");
        }
        return view('check_card',compact('data'));
    }
     //report users
    public function user(Request $request,$q="")
    { 
        $data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('servicecardtype', 'servicecardtype.card_type_id', '=', 'card_type.id')
        ->join('services', 'services.id', '=', 'servicecardtype.services_id')
        ->where('cards.is_valid', '=', 1 )
        ->where('client.deleted_at', '=',  null )
        ->where('cards.card_number', '=', $q )
        ->select(['services.title','services.id as services_id','client.id as client_id'])
        ->get();
        //return response()->json($data);
        if ($request->ajax()) 
        {
         return Datatables::of($data)->make(true);
        }
        return view('report/user',compact('data'));
    }
    public function check_user(Request $request,$from=0,$to=0,$type=0,$pdf_download=false)
    { 
        //if ($from == 0) $from ="2021-06-01";
        //if ($to == 0) $to =date('Y-m-d');
        $date = date('Y-m-d h:i');
   
        $type_ar="";
        $form_to_data="";
        $data_temp = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('users', 'users.id', '=', 'cards.author_id')
        ->where('client.deleted_at', '=',  null )
        ->where('cards.is_valid', '=', 1 )
        ->where('card_user.end_active', '>=',   $date  );
    
        if($from !=0 && $to!=0)
        {
            $form_to_data= $data_temp->whereBetween('card_user.created_at', [$from, $to]);
        }
        if($type==0 || $type=="undefined")
        {
            $type_ar=" جميع الحسابات  لتاريخ".$date;
            $form_to_data= $data_temp;
        }
        else
        {
            $form_to_data= $data_temp->where('cards.author_id', '=',$type );
        }

        $data_service=$form_to_data->select(['client.id','cards.card_number','users.name','client.full_name','client.phone','card_user.strat_active','card_user.end_active', 'card_type.title as type',DB::raw('(card_type.price * users.rate)/100 As price' ) ])->get();
        $data_count=$form_to_data->select(['users.name', 'card_type.title'])->count();

        $data_temp_total = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('users', 'users.id', '=', 'cards.author_id')
        ->where('client.deleted_at', '=',  null )
        ->where('cards.is_valid', '=', 1 )
        ->where('card_user.end_active', '>=',   $date  )
        ->groupBy('card_type.title','users.name');
        if($from !=0 && $to!=0)
        {
            $form_to_data_total= $data_temp_total->whereBetween('card_user.created_at', [$from, $to]);
        }
        if($type==0 || $type=="undefined")
        {
            $type_ar=" جميع الخدمات  لتاريخ".$date;
            $form_to_data_total= $data_temp_total;
        }
        else
        {
            $form_to_data_total= $data_temp_total->where('cards.author_id', '=',$type );
        }
        $data_service_total=$form_to_data_total->select(['users.name','card_type.title',DB::raw('SUM((card_type.price * users.rate)/100) as total')])->get();
        //return response()->json($data_service_total);  
       if ($request->ajax()) 
       {
        return Datatables::of($data_service)->make(true);
       }
       if($pdf_download){
        $customers=$data_service;
        if(!empty($customers->first())){
        $pdf = PDF::loadView('report/user_from_to_pdf',compact('customers','date','type_ar','data_service_total'));
        return $pdf->download(' '.$date.'..pdf');
        }
        else 
        return response()->json("No Services");
        }
        return view('check_card',compact('data'));
    }

    public function check_user_total(Request $request,$from=0,$to=0,$type=0)
    { 
        //if ($from == 0) $from ="2021-06-01";
        //if ($to == 0) $to =date('Y-m-d');
        $date = date('Y-m-d h:i');
        $new = date('Y-m-d');
        $type_ar="";
        $form_to_data="";
        $data_temp = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('users', 'users.id', '=', 'cards.author_id')
        ->where('client.deleted_at', '=',  null )
        ->where('cards.is_valid', '=', 1 )
        ->where('card_user.end_active', '>=',   $date  )
        ->groupBy('card_type.title','users.name');
        if($from !=0 && $to!=0)
        {
            $form_to_data= $data_temp->whereBetween('card_user.created_at', [$from, $to]);
        }
        if($type==0 || $type=="undefined")
        {
            $type_ar=" جميع الخدمات  لتاريخ".$new;
            $form_to_data= $data_temp;
        }
        else
        {
            $form_to_data= $data_temp->where('cards.author_id', '=',$type );
        }
     
        $data_service=$form_to_data->select(['users.name','card_type.title',DB::raw('SUM((card_type.price * users.rate)/100) as total')])->get();
        $data_count=$form_to_data->select(['users.name', 'card_type.title'])->count();

       //return response()->json($data_service);
       if ($request->ajax())
       {
        return Datatables::of($data_service)->make(true);
       }
    
        return view('check_card',compact('data'));
    }
      //report hospital
    public function hospital(Request $request,$q="")
    { 
        $data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('servicecardtype', 'servicecardtype.card_type_id', '=', 'card_type.id')
        ->join('services', 'services.id', '=', 'servicecardtype.services_id')
        ->where('cards.is_valid', '=', 1 )
        ->where('client.deleted_at', '=',  null )
        ->where('cards.card_number', '=', $q )
        ->select(['services.title','services.id as services_id','client.id as client_id'])
        ->get();
        //return response()->json($data);
        if ($request->ajax()) 
        {
         return Datatables::of($data)->make(true);
        }
        return view('report/hospital',compact('data'));
    }
    public function check_hospital(Request $request,$from=0,$to=0,$type=0,$pdf_download=false)
    { 
        //if ($from == 0) $from ="2021-06-01";
        //if ($to == 0) $to =date('Y-m-d');
        $date = date('Y-m-d h:i');
   
        $type_ar="";
        $form_to_data="";
        $data_temp = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('users', 'users.id', '=', 'cards.author_id')
        ->where('client.deleted_at', '=',  null )
        ->where('cards.is_valid', '=', 1 )
        ->where('card_user.end_active', '>=',   $date  )
        ->orderBy('cards.card_number');
    
        if($from !=0 && $to!=0)
        {
            $form_to_data= $data_temp->whereBetween('card_user.created_at', [$from, $to]);
        }
        if($type==0 || $type=="undefined")
        {
            $type_ar=" جميع الحسابات  لتاريخ".$date;
            $form_to_data= $data_temp;
        }
        else
        {
            $form_to_data= $data_temp->where('cards.card_type_id', '=',$type );
        }

        $data_service=$form_to_data->select(['client.id','cards.card_number','users.name','client.full_name','client.phone','card_user.strat_active','card_user.names','card_user.end_active', 'card_type.title as type',DB::raw('(card_type.price)/12 As price' ) ])->get();
        $data_count=$form_to_data->select(['users.name', 'card_type.title'])->count();

        $data_temp_total = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('users', 'users.id', '=', 'cards.author_id')
        ->where('client.deleted_at', '=',  null )
        ->where('cards.is_valid', '=', 1 )
        ->where('card_user.end_active', '>=',   $date  )
        ->groupBy('card_type.title');
        if($from !=0 && $to!=0)
        {
            $form_to_data_total= $data_temp_total->whereBetween('card_user.created_at', [$from, $to]);
        }
        if($type==0 || $type=="undefined")
        {
            $type_ar=" جميع البطاقات  لتاريخ".$date;
            $form_to_data_total= $data_temp_total;
        }
        else
        {
            $form_to_data_total= $data_temp_total->where('cards.card_type_id', '=',$type );
        }
        $data_service_total=$form_to_data_total->select(['card_type.title',DB::raw('SUM((card_type.price)/12) as total')])->get();
        //return response()->json($data_service_total);  
       if ($request->ajax()) 
       {
        return Datatables::of($data_service)->make(true);
       }
       if($pdf_download){
        $customers=$data_service;
        if(!empty($customers->first())){
        $pdf = PDF::loadView('report/hospital_from_to_pdf',compact('customers','date','type_ar','data_service_total'));
        return $pdf->download(' '.$date.'..pdf');
        }
        else 
        return response()->json("No Services");
        }
        return view('check_card',compact('data'));
    }
    public function check_hospital_total(Request $request,$from=0,$to=0,$type=0)
    { 
        //if ($from == 0) $from ="2021-06-01";
        //if ($to == 0) $to =date('Y-m-d');
        $date = date('Y-m-d h:i');
        $new = date('Y-m-d');
        $type_ar="";
        $form_to_data="";
        $data_temp = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
        ->join('users', 'users.id', '=', 'cards.author_id')
        ->where('client.deleted_at', '=',  null )
        ->where('cards.is_valid', '=', 1 )
        ->where('card_user.end_active', '>=',   $date  )
        ->groupBy('card_type.title');
        if($from !=0 && $to!=0)
        {
            $form_to_data= $data_temp->whereBetween('card_user.created_at', [$from, $to]);
        }
        if($type==0 || $type=="undefined")
        {
            $type_ar=" جميع البطاقات  لتاريخ".$new;
            $form_to_data= $data_temp;
        }
        else
        {
            $form_to_data= $data_temp->where('cards.card_type_id', '=',$type );
        }
     
        $data_service=$form_to_data->select(['card_type.title',DB::raw('SUM((card_type.price)/12) as total')])->get();
        $data_count=$form_to_data->select(['card_type.title'])->count();

       //return response()->json($data_service);
       if ($request->ajax())
       {
        return Datatables::of($data_service)->make(true);
       }
    
        return view('check_card',compact('data'));
    }
      //report doctor
    public function doctor(Request $request,$q="")
    { 
          $data = DB::table('client')
          ->join('card_user', 'card_user.client_id', '=', 'client.id')
          ->join('cards', 'cards.id', '=', 'card_user.card_id')
          ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
          ->join('servicecardtype', 'servicecardtype.card_type_id', '=', 'card_type.id')
          ->join('services', 'services.id', '=', 'servicecardtype.services_id')
          ->where('cards.is_valid', '=', 1 )
          ->where('client.deleted_at', '=',  null )
          ->where('cards.card_number', '=', $q )
          ->select(['services.title','services.id as services_id','client.id as client_id'])
          ->get();
          //return response()->json($data);
          if ($request->ajax()) 
          {
           return Datatables::of($data)->make(true);
        }
          return view('report/doctor',compact('data'));
    }
    public function check_doctor(Request $request,$from=0,$to=0,$type=0,$pdf_download=false)
    { 
          //if ($from == 0) $from ="2021-06-01";
          //if ($to == 0) $to =date('Y-m-d');
          $date = date('Y-m-d h:i');
     
          $type_ar="";
          $form_to_data="";
          $data_temp = DB::table('client')
          ->join('card_user', 'card_user.client_id', '=', 'client.id')
          ->join('cards', 'cards.id', '=', 'card_user.card_id')
          ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
          ->join('users', 'users.id', '=', 'cards.author_id')
          ->where('client.deleted_at', '=',  null )
          ->where('cards.is_valid', '=', 1 )
          ->where('card_user.end_active', '>=',   $date  )
          ->orderBy('cards.card_number');
      
          if($from !=0 && $to!=0)
          {
              $form_to_data= $data_temp->whereBetween('card_user.created_at', [$from, $to]);
          }
          if($type==0 || $type=="undefined")
          {
              $type_ar=" جميع البطاقات  لتاريخ".$date;
              $form_to_data= $data_temp;
          }
          else
          {
              $form_to_data= $data_temp->where('cards.card_type_id', '=',$type );
          }
  
          $data_service=$form_to_data->select(['client.id','cards.card_number','card_user.names','users.name','client.full_name','client.phone','card_user.strat_active','card_user.end_active', 'card_type.title as type',DB::raw('3000 As price' ) ])->get();
          $data_count=$form_to_data->select(['users.name', 'card_type.title'])->count();
  
          $data_temp_total = DB::table('client')
          ->join('card_user', 'card_user.client_id', '=', 'client.id')
          ->join('cards', 'cards.id', '=', 'card_user.card_id')
          ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
          ->join('users', 'users.id', '=', 'cards.author_id')
          ->where('client.deleted_at', '=',  null )
          ->where('cards.is_valid', '=', 1 )
          ->where('card_user.end_active', '>=',   $date  )
          ->orderBy('cards.card_number')
          ->groupBy('card_type.title');
          if($from !=0 && $to!=0)
          {
              $form_to_data_total= $data_temp_total->whereBetween('card_user.created_at', [$from, $to]);
          }
          if($type==0 || $type=="undefined")
          {
              $type_ar=" جميع البطاقات  لتاريخ".$date;
              $form_to_data_total= $data_temp_total;
          }
          else
          {
              $form_to_data_total= $data_temp_total->where('cards.card_type_id', '=',$type );
          }
          $data_service_total=$form_to_data_total->select(['card_type.title',DB::raw('SUM(3000) as total')])->get();
          //return response()->json($data_service_total);  
         if ($request->ajax()) 
         {
          return Datatables::of($data_service)->make(true);
         }
         if($pdf_download){
          $customers=$data_service;
          if(!empty($customers->first())){
          $pdf = PDF::loadView('report/doctor_from_to_pdf',compact('customers','date','type_ar','data_service_total'));
          return $pdf->download(' '.$date.'..pdf');
          }
          else 
          return response()->json("No Services");
          }
          return view('check_card',compact('data'));
    }
    public function check_doctor_total(Request $request,$from=0,$to=0,$type=0)
    { 
          //if ($from == 0) $from ="2021-06-01";
          //if ($to == 0) $to =date('Y-m-d');
          $date = date('Y-m-d h:i');
          $new = date('Y-m-d');
          $type_ar="";
          $form_to_data="";
          $data_temp = DB::table('client')
          ->join('card_user', 'card_user.client_id', '=', 'client.id')
          ->join('cards', 'cards.id', '=', 'card_user.card_id')
          ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
          ->join('users', 'users.id', '=', 'cards.author_id')
          ->where('client.deleted_at', '=',  null )
          ->where('cards.is_valid', '=', 1 )
          ->where('card_user.end_active', '>=',   $date  )
          ->orderBy('cards.card_number')
          ->groupBy('card_type.title');
          if($from !=0 && $to!=0)
          {
              $form_to_data= $data_temp->whereBetween('card_user.created_at', [$from, $to]);
          }
          if($type==0 || $type=="undefined")
          {
              $type_ar=" جميع البطاقات  لتاريخ".$new;
              $form_to_data= $data_temp;
          }
          else
          {
              $form_to_data= $data_temp->where('cards.card_type_id', '=',$type );
          }
          $data_service=$form_to_data->select(['card_type.title',DB::raw('SUM((3000) as total')])->get();
          $data_count=$form_to_data->select(['card_type.title'])->count();
  
         //return response()->json($data_service);
         if ($request->ajax())
         {
          return Datatables::of($data_service)->make(true);
         }
      
          return view('doctor_card',compact('data'));
    }
}
