<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Reservation::class, function ($faker) {
    $days = rand ( 2, 10 );
	$startingDate = $faker->dateTimeBetween(date('Y/m/d',strtotime('01/01/2016')), date('Y/m/d',strtotime('11/01/2016')), $timezone = date_default_timezone_get());
    $endingDate   = strtotime('+'.$days.' days', $startingDate->getTimestamp());
    $checkin = date("Y/m/d",$startingDate->getTimestamp());
    $checkout = date("Y/m/d",$endingDate);
    $deposit = rand(0,1);
    $price = $faker->randomElement($array = array (40,50,60,70,80));
    $total_price = $days * $price;
    $deposit_amount = 0.0;
    if($deposit){
    	$deposit_amount = $faker->randomElement($array = array (100,50,10,200));
    }
    $price = $faker->randomElement($array = array (40,50,60,70,80));
    $total_price = $days * $price;
    $channel_id = $faker->randomElement($array = array (1,2,3,4,5,5,5,5,5,5));
    $ref_id = "";
    if($channel_id != 4 && $channel_id != 5) {
        $ref_id = rand(100000000,500000000);
    }
    $country = $faker->randomElement($array = array("Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua &amp; Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas"
        ,"Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia &amp; Herzegovina","Botswana","Brazil","British Virgin Islands"
        ,"Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Cape Verde","Cayman Islands","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica"
        ,"Cote D Ivoire","Croatia","Cruise Ship","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea"
        ,"Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana"
        ,"Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India"
        ,"Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kuwait","Kyrgyz Republic","Laos","Latvia"
        ,"Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Mauritania"
        ,"Mauritius","Mexico","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Namibia","Nepal","Netherlands","Netherlands Antilles","New Caledonia"
        ,"New Zealand","Nicaragua","Niger","Nigeria","Norway","Oman","Pakistan","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal"
        ,"Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre &amp; Miquelon","Samoa","San Marino","Satellite","Saudi Arabia","Senegal","Serbia","Seychelles"
        ,"Sierra Leone","Singapore","Slovakia","Slovenia","South Africa","South Korea","Spain","Sri Lanka","St Kitts &amp; Nevis","St Lucia","St Vincent","St. Lucia","Sudan"
        ,"Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad &amp; Tobago","Tunisia"
        ,"Turkey","Turkmenistan","Turks &amp; Caicos","Uganda","Ukraine","United Arab Emirates","United Kingdom","Uruguay","Uzbekistan","Venezuela","Vietnam","Virgin Islands (US)"
        ,"Yemen","Zambia","Zimbabwe"));
    return [
    	'hotel_id' => 1,
        'client_name' => $faker->name,
        'client_email' => $faker->email,
        'client_phone' => $faker->phoneNumber,
        'country' => $country,
        'check_in' => $checkin,
        'check_out' => $checkout,
        'nights' => $days,
        'room_type_id' => $faker->randomElement($array = array (1,1,1,1,1,1,1,1,1,1,1,1,1,1,2)),
        'persons' => rand(2,3),
        'price' => $price,
        'total_price' => $total_price,
        'room_number' => rand(11,22),
        'breakfast' => rand(0,1),
        'deposit' => $deposit,
        'deposit_amount' => $deposit_amount,
        'status_id' => $faker->randomElement($array = array (1,1,1,1,1,1,1,1,1,1,2,3)),
        'channel_id' => $channel_id,
        'ref_id' => $ref_id,
        'notes' => $faker->realText(rand(10,200))
    ];
});



