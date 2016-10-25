<?php namespace App\Functions;

use App\RoomsType;
class Calculations {

    public static function nights($check_in, $check_out) {
    	$diff = (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);
    	// $my_t=getdate($diff);
        return $diff;
    }

    public static function total_price($nights, $price) {
    	$total = $nights * $price;
    	return $total;
    }

	public static function chechAvailability($from, $to,$type) {
		$nights = Calculations::nights($from,$to);

	    $dates=array(); //all dates of current month
		$date = date('Y-m-d',strtotime($from));
		$dates[] = $date;
		while (strtotime($date) < strtotime($to)) {
        	$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
        	$dates[] = $date;
     	}
     	array_pop($dates); //remove last date 

	    $reservations = \DB::table('reservations')->where('status_id','1')
	    		->where('room_type_id',$type)
	            ->whereDate('check_in', '<=', $to) 
	            ->whereDate('check_out', '>', $from) 
	            ->get();

	    $results = array();
	    $overbook = array();
	    $room_type_amount = RoomsType::find($type)->amount;
	    foreach ($dates as $date) {
	        $sum = 0;
	        foreach ($reservations as $reservation) {
	            if(strtotime($reservation->check_in) <= strtotime($date) && strtotime($reservation->check_out) > strtotime($date)){
	                ++$sum;
	            }
	        }
	        if($sum >= $room_type_amount) {
				$overbook[] = (object) array('date' => date('Y/m/d', strtotime($date)), 'sum' => $sum);
	        } else{
	        	$results[] = (object) array('date' => date('Y/m/d', strtotime($date)), 'sum' => $room_type_amount-$sum);
	    	}
	    }
	    
	    if(!empty($overbook)) {
	    	return response()->json(['dates' => $overbook,'status' => 'error' ],200);
	    } 
			return response()->json(['dates' => $results,'status' => 'success'],200);


    }

}

