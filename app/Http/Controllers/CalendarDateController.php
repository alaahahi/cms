<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Services;
use App\Models\Cards;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Models\Event;

class CalendarDateController extends Controller
{
  
    public function calendar(Request $request)
    {
		if($request->ajax())
    	{
    		$data = Event::all()
                       ->get(['id', 'title', 'start', 'end','color']);
            return response()->json($data);
    	}
    	return view('calendar');
    }
    public function all_calendar(Request $request)
    {
    	if($request->ajax())
    	{
    		$data =  Event::whereDate('start', '>=', $request->start)
			->whereDate('end',   '<=', $request->end)
			->get(['id', 'title', 'start', 'end','color']);
            return response()->json($data);
    	}
    }
    public function action(Request $request)
    {
    	if($request->ajax())
    	{
    		if($request->type == 'add')
    		{
    			$event = Event::create([
    				'title'		=>	$request->title,
    				'start'		=>	$request->start,
    				'end'		=>	$request->end,
					'service_id'=>	$request->service,
					'client_id'	=>	$request->clinet,
					'color'		=>	$request->color
    			]);
				$date = date('Y-m-d h:i');
				$number=DB::table('service_client')->where('service_id', '=',	$request->service)->where('client_id', '=', $request->clinet)->count();
				DB::table('service_client')->insert(array('service_id' => $request->service,'client_id' => $request->clinet,'date'=>$date,'number'=>$number+1));
    			return response()->json($event);
    		}

    		if($request->type == 'update')
    		{
    			$event = Event::find($request->id)->update([
    				'title'		=>	$request->title,
					'service_id'=>	$request->service,
					'client_id'	=>	$request->clinet,
					'color'		=>	$request->color
    			]);

    			return response()->json($event);
    		}

    		if($request->type == 'delete')
    		{
    			$event = Event::find($request->id)->delete();

    			return response()->json($event);
    		}
    	}
    }
	public function all_clinet(Request $request,$card_type)
    {
		$data = DB::table('client')
        ->join('card_user', 'card_user.client_id', '=', 'client.id')
        ->join('cards', 'cards.id', '=', 'card_user.card_id')
        ->join('card_type', 'card_type.id', '=', 'cards.card_type_id')
		->where('card_type.id', '=',$card_type)
		->select('client.id','client.full_name')
		->get();
		return response()->json($data);
	}
	public function all_card(Request $request)
    {
		$data = Cards::all();
		return response()->json($data);
	}
	public function all_services(Request $request,$id)
    {
		$user_services = DB::table('services')
		->join('service_client', 'service_client.service_id', '=', 'services.id')
		->where('service_client.client_id', '=',$id )
		->select("services.id")
		->get();
		$data = Services::whereNotIn('services.id', DB::table('services')
		->join('service_client', 'service_client.service_id', '=', 'services.id')
		->where('service_client.client_id', '=',$id )
		->select("services.id"))->select('services.id','services.title','color')->get();
		return response()->json($data);
	}
}
