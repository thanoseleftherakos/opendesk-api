<?php namespace App\Functions;


class ReservationsFilter {

    public static function perDay($from, $to) {
    	if(!$to){
    		$to = date('Y/m/t');
    	}
    	if(!$from){
    		$from = date('Y/m/01');
    	}
	    $dates=array(); //all dates of current month
	    for($d=1; $d<=31; $d++)
	    {
	        $time=mktime(12, 0, 0, date('m', strtotime($to)), $d, date('Y', strtotime($to)));
	        if (date('m', $time)==date('m', strtotime($to) ))
	            $dates[]=date('Y-m-d', $time);
	    }
	    
	    $reservations = \DB::table('reservations')->where('status_id','1')
	            ->whereDate('check_in', '<=', $to) //last day of the month
	            ->whereDate('check_out', '>', $from) //first day of the month
	            ->get();
	    $results = array();

	    foreach ($dates as $date) {
	        $total = 0;
	        $sum = 0;
	        foreach ($reservations as $reservation) {
	            if(strtotime($reservation->check_in) <= strtotime($date) && strtotime($reservation->check_out) > strtotime($date)){
	                ++$sum;
	                $total += $reservation->price;
	            }
	        }
	        $results[] = (object) array('day' => (int)date('d', strtotime($date)), 'total' => $total, 'sum' => $sum);
	    }
	    
	    
	    return $results;
    }


}

