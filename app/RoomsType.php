<?php namespace App;
	
use Illuminate\Database\Eloquent\Model;

class RoomsType extends Model
{
	protected $table = 'room_types';
	protected $fillable = ['id','name','amount','hotel_id'];
	protected $hidden = ['created_at','updated_at'];


	public function reservations()
	{
		return $this->hasMany('App\Reservation','room_type_id','id');
	}

}