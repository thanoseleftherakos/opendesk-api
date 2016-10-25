<?php namespace App;
	
use Illuminate\Database\Eloquent\Model;

class StatusType extends Model
{
	protected $fillable = ['id','type'];
	protected $table = 'status_types';
	protected $hidden = ['created_at','updated_at'];

}