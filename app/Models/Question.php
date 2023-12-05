<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
	protected $fillable = [
		'title',
		'lang'
	];

	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public function checkins()
	{
		return $this->belongsToMany('App\Models\Checkin', 'checkin_questions');
	}


	# MUTATORS - To modify the data 
    # before it saves in the 
    # database
     
    // public function setTitleAttribute($value)         // title as Martin
    // {
    //     return $this->attributes['title'] = ucfirst(strtolower($value));
    // }
}
