<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use App\Functions\Calculations;
use App\User;
use App\Reservation;

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['middleware' => ['throttle']], function($app)
{
    $app->post('/auth/login', 'App\Http\Controllers\AuthController@postLogin');
});

$app->group(['middleware' => 'auth:api'], function($app)
{   
	////Hotel Routes
    $app->get('/init','App\Http\Controllers\HotelController@init');
	$app->post('/hotelinfo','App\Http\Controllers\HotelController@dashboard');
    $app->get('/settings','App\Http\Controllers\HotelController@settings');
    $app->post('/settings','App\Http\Controllers\HotelController@updateSettings');
    $app->delete('/settings/remove_room_type/{id}','App\Http\Controllers\HotelController@removeRoomType');


    ////User Routes
    $app->get('/user','App\Http\Controllers\UserController@index');
    $app->put('/user','App\Http\Controllers\UserController@update');

    ////Helpers
    $app->get('/room_types','App\Http\Controllers\HotelController@getRoomTypes');

    ////Reservations Routes
    $app->get('/reservations','App\Http\Controllers\ReservationController@index');
    $app->get('/reservations/form','App\Http\Controllers\ReservationController@formparams');
    $app->post('/reservations','App\Http\Controllers\ReservationController@store');
    $app->post('/reservations/check_availability','App\Http\Controllers\ReservationController@checkAvailability');
    $app->get('/reservations/{id}','App\Http\Controllers\ReservationController@show');
    $app->put('/reservations/{id}','App\Http\Controllers\ReservationController@update');
    $app->delete('/reservations/{id}','App\Http\Controllers\ReservationController@destroy');
    $app->post('/reservations/search','App\Http\Controllers\ReservationController@search');

    ////Statistics Routes
    $app->post('/statistics','App\Http\Controllers\HotelController@stats');
    


});