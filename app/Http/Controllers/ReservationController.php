<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Reservation;
use App\User;
use App\RoomsType;
use App\StatusType;
use App\Channel;
use App\Search\ReservationSearch;
use App\Classes\CurrentUser;
use \Auth;
use App\Functions\Calculations;

class ReservationController extends Controller
{
	public function __construct() {
		$this->hotel_id = Auth::user()->hotel->id;
	}
    
    public function index(){
    	$reservations = Reservation::where('hotel_id',$this->hotel_id)->with('room')->get();
    	return $this->createResponse($reservations,'',200);
    }
    
    public function show($id){
    	$reservation = Reservation::with('room')
                                    ->with('channel')
    								->with('statusType')
    								->find($id);
    	if($reservation){ //check if exists on Databse
    		if($reservation->hotel_id == $this->hotel_id){
                $channels = Channel::all();
                $room_types = $reservation->hotel->room_types;
                $status_types = StatusType::all();
                $reservation->channels = $channels;
                $reservation->room_types = $room_types;
                $reservation->status_types = $status_types;
    			return $this->createResponse($reservation,'',200);
    		}
    		return $this->createResponse('','You are not supposed to be here!',403);
    	}
    	return $this->createResponse('','Reservation not found',404);
    }

    public function store(Request $request){
    	//create a new reservation
    	$this->validateRequest($request);
    	$data = $request->all();
    	$reservation = new Reservation($data);
    	$reservation->hotel_id = Auth::user()->hotel->id;
        $nights = Calculations::nights($request['check_in'], $request['check_out']);
        $total_price = Calculations::total_price($nights,$request['price']);
        $reservation->nights = $nights;
        $reservation->total_price = $total_price;
    	$save = $reservation->save();
    	if($save) {
            return response()->json(['message' => 'Reservation has breen created successfully!', 'data' => $reservation->id ],200);
    	}
    	return $this->createResponse('','Reservation cannot be saved!',503);
    	
    }

    public function update($id,Request $request){
    	//update a reservation
    	$reservation = Reservation::find($id);
    	if($reservation){ //check if exists on Databse
    		if($reservation->hotel_id == $this->hotel_id){ 
    			$this->validateRequest($request);
                $nights = Calculations::nights($request['check_in'], $request['check_out']);
                $total_price = Calculations::total_price($nights,$request['price']);
                $reservation->nights = $nights;
                $reservation->total_price = $total_price;
    			$update = $reservation->update($request->all());
    			if($update) {
    				return $this->createResponse($id,"Reservation with id {$id} has breen updated successfully!",200);
    			}
    			return $this->createResponse('','Reservation cannot be updated! Please try again later..',503);

    		}
    		return $this->createResponse('','You are not supposed to be here!',403);
    	}
    	return $this->createResponse('','Reservation does not exists',401);
    }

    public function destroy($id){
    	//delete a reservation
    	$reservation = Reservation::find($id);
    	if($reservation){ //check if exists on Databse
    		if($reservation->hotel_id == $this->hotel_id){ 
    			$reservation->delete();
    			return $this->createResponse('',"Reservation has been removed successfully!",200);
    		}
    		return $this->createResponse('','You are not supposed to be here!',403);
    	}
    	return $this->createResponse('','Reservation does not exists',404);
    }


    function validateRequest($request){

    	$rules = [
    		'client_name' => 'required',
    		'check_in' => 'required|date',
    		'check_out' => 'required|date',
    		'room_type_id' => 'required|numeric',
    		'persons' => 'required|numeric',
    		'price' => 'required|numeric',
    		'deposit_amount' => ['required_if:deposit,1'],
    		'channel_id' => 'required|numeric',
    	];

    	$this->validate($request,$rules);
    }


    public function checkAvailability(Request $request) {
        return Calculations::chechAvailability($request['check_in'], $request['check_out'], $request['room_type_id']);
    }


    public function search(Request $request) {
        $query = Reservation::with('room','channel','statusType');
        $stay_from = date("Y/m/d");
        $stay_to = date("Y/m/d",  strtotime("+1 day"));
        if($request->input('type')){
            $type = $request->input('type');
        } else{
            $type = "arr_date";
        }


        if($request->input('stay_from')){
            $stay_from = $request->input('stay_from');
        }

        if($request->input('stay_to')){
            $stay_to = $request->input('stay_to');
        } 
        
        if($type == 'arr_date') {
            $query = $query->where('hotel_id',$this->hotel_id)->where('check_in', '>=', $stay_from)->where('check_in', '<=', $stay_to);
            
        }
        if($type == 'rs_date') {
            $query = $query->where('hotel_id',$this->hotel_id)->where('created_at', '>=', $stay_from)->where('created_at', '<=', $stay_to);

        }
        if($type == 'dp_date') {
            $query = $query->where('hotel_id',$this->hotel_id)->where('check_out', '>=', $stay_from)->where('check_out', '<=', $stay_to);
        }

        if($request->input('query')){
            $query->where('hotel_id',$this->hotel_id)->where('client_name', 'LIKE', '%' . $request->input('query') . '%' )
                          ->orWhere('client_email', 'LIKE', '%' . $request->input('query') . '%')
                          ->orWhere('ref_id', 'LIKE', '%' . $request->input('query') . '%');
        }
            
        return $query->get();
        // return ReservationSearch::apply($request);

    }

    public function formparams(){
        $reservation= new Reservation;
        $channels = Channel::all();
        $room_types = RoomsType::all();
        $status_types = StatusType::all();
        $reservation->channels = $channels;
        $reservation->room_types = $room_types;
        $reservation->status_types = $status_types;
        return $this->createResponse($reservation,'',200);
    }

}
