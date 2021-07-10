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

    			return response()->json($event);
    		}

    		if($request->type == 'update')
    		{
    			$event = Event::find($request->id)->update([
    				'title'		=>	$request->title,
    				'start'		=>	$request->start,
    				'end'		=>	$request->end
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
	public function all_clinet(Request $request)
    {
		$data = Client::all();
		return response()->json($data);
	}
	public function all_card(Request $request)
    {
		$data = Cards::all();
		return response()->json($data);
	}
	public function all_services(Request $request)
    {
		$data = Services::all();
		return response()->json($data);
	}
}
