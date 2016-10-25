<?php namespace App;
	
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
	protected $hidden = ['created_at','updated_at'];

	public function reservations()
	{
		return $this->hasMany('App\Reservation');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}
	
	public function room_types()
	{
		return $this->hasMany('App\RoomsType');
	}

}