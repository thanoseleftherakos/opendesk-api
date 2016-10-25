<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Auth;
use App\Hotel;
use App\Reservation;
use App\StatusType;
use App\RoomsType;
use App\Functions\ReservationsFilter;
use DB;

use Illuminate\Support\Facades\File;

class HotelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->hotel_id = Auth::user()->hotel->id;
    }
    public function init(){
        $hotel = Hotel::find(Auth::user()->hotel->id);
        $user = Auth::user();
        $response = array(
            'hotel' => $hotel,
            'user' => $user
        );
        return $this->createResponse($response,'',200);

    }

    public function dashboard(Request $request){
        $hotel = Hotel::find(Auth::user()->hotel->id);
         
        $today = ($request['date'] ? $request['date'] : date('Y/m/d'));    
        $first_day = date('Y/m/01', strtotime($today));
        $last_day = date('Y/m/t', strtotime($today));

        if($hotel){
            $total_reservations = Reservation::where('hotel_id',$hotel->id)->with('room')->count();
            $arivals_today = Reservation::where('status_id','1')->whereDate('check_in','=',date($today))->with('room')->get();
            $arivals_today_count = Reservation::where('status_id','1')->whereDate('check_in','=',date($today))->count();
            // $arivals_tomorrow = Reservation::where('status_id','1')->whereDate('check_in','=',date($today,  strtotime("+1 day")))->with('room')->get();
            $departures_today = Reservation::where('status_id','1')->whereDate('check_out','=',date($today))->with('room')->get();
            $departures_today_count = Reservation::where('status_id','1')->whereDate('check_out','=',date($today))->count();
            // $departures_tomorrow = Reservation::where('status_id','1')->whereDate('check_out','=',date($today,  strtotime("+1 day")))->with('room')->get();
            $total_earnings = Reservation::where('status_id','1')->sum('total_price');
            $current_rooms_query = Reservation::where('status_id','1')
                                        ->whereDate('check_in', '<=', date($today))
                                        ->whereDate('check_out', '>', date($today));
            $available_rooms_today = $hotel->total_rooms - $current_rooms_query->count();
            $status_types = StatusType::all();
            $current_rooms = $current_rooms_query->with('room')->with('channel')->with('statusType')->get();
            $chart_old = \DB::table('reservations')->where('status_id','1')
                ->whereYear('check_out', '=', date("Y"))
                ->selectRaw('month(check_out) as month, sum(total_price) as sum, count(*) as total')
                ->groupBy(DB::raw('month desc'))
                ->orderBy('month', 'asc')
                ->get();
            $chart = ReservationsFilter::perDay($first_day,$last_day);
            $response = array(
                'hotel' => $hotel, 
                'total_reservations' => $total_reservations,
                'arivals_today_count' => $arivals_today_count,
                'arivals_today' => $arivals_today,
                // 'arivals_tomorrow' => $arivals_tomorrow,
                'departures_today_count' => $departures_today_count,
                'departures_today' => $departures_today,
                // 'departures_tomorrow' => $departures_tomorrow,
                'total_earnings' => $total_earnings,
                'available_rooms_today' => $available_rooms_today,
                'current_rooms' => $current_rooms,
                'chart' => $chart
                );
            return $this->createResponse($response,'',200);
        }
        else{
            return $this->createResponse('','User is not associated with a hotel',404);
        }
    }

    public function getRoomTypes(){
        $hotel = Hotel::find(Auth::user()->hotel->id);
        $room_types = $hotel->room_types;
        return $this->createResponse($room_types,'',200);
    }

    public function settings() {
        $hotel = Hotel::find(Auth::user()->hotel->id)->with('room_types')->first();
        return $this->createResponse($hotel,'',200);
    }
    public function updateSettings(Request $request) {
        $hotel = Hotel::find(Auth::user()->hotel->id)->with('room_types')->first();

        $file = $request->file('logo');
        if($file){
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $f_fileName = preg_replace("/[^-_a-z0-9]+/i", "_", $filename) . '.' . $extension;
            $destinationPath = base_path() . '/public/uploads/'. $hotel->id .'/logo/';    
            $file->move($destinationPath, $f_fileName);
            $hotel->logo = 'http://dev.webf8.net/hotelapi/public/uploads/' . $hotel->id . '/logo/' . $f_fileName;
        }
        
        
        $room_types = json_decode($request['room_types'],true);
        $deleted = json_decode($request['deleted'],true);
        $total_rooms = 0;

        foreach($room_types as $room_type) {
            $total_rooms += $room_type['amount'];
        }
        if($total_rooms > $hotel->total_rooms){ //validate total rooms
            return $this->createResponse('','Error!',403);
        }
        
        foreach($room_types as $input) {
            if(isset($input['id'])) { //if existing room type -> update
                $room = RoomsType::where('id',$input['id'])->where('hotel_id',$hotel->id)->first();
                if(!$room){
                    return $this->createResponse('','Error!',403);
                }
                $room->name = $input['name'];
                $room->amount = $input['amount'];
                $room->update();
            } else { //if new room type -> create
                $room = new RoomsType;
                $room->name = $input['name'];
                $room->amount = $input['amount'];
                $room->hotel_id = $hotel->id;
                $room->save();
            }
        }
        if(!empty($deleted)) {
            foreach($deleted as $type) {    
                $room = RoomsType::where('id',$type['id'])->where('hotel_id',$hotel->id)->first();
                if(!$room){
                    return $this->createResponse('','Error!',403);
                } 
                if(count($room->reservations)){
                    return $this->createResponse($hotel,"You can't delete this type of room because you have reservations associated with this type of room. Please try to rename it instead!",422);
                }
                $room->delete();
                
            }
        }

        $hotel->name = $request['name'];
        $hotel->email = $request['email'];

        $hotel->update();
        
        return $this->createResponse($hotel,'Settings updated successfully',200);
    }

    

    
}
