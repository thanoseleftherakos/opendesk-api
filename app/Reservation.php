<?php namespace App;
	
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
	protected $fillable = ['client_name','client_email','client_phone','check_in','check_out','nights','room_type_id','persons','price','breakfast','deposit','deposit_amount','status_id','channel_id', 'total_price', 'notes', 'room_number', 'ref_id'];
	protected $hidden = ['hotel_id'];

	public function hotel()
	{
		return $this->belongsTo('App\Hotel');
	}	
	public function room()
	{
		return $this->hasOne('App\RoomsType','id','room_type_id');
	}
	public function statusType()
	{
		return $this->hasOne('App\StatusType','id','status_id');
	}
	public function channel()
	{
		return $this->hasOne('App\Channel','id','channel_id');
	}

}

