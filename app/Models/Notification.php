<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
    	'title',
    	'description',
    	'is_completed',
        'country_id'
    ];

    protected $casts = [
		'is_completed' => 'boolean',
	];
	
    public function users()
    {
    	return $this->belongsToMany('App\Models\User','notification_users')->withPivot('read')->withTimestamps();
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    # MUTATORS - To modify the data 
    # before it saves in the 
    # database
     
    // public function setTitleAttribute($value)         // first_name as Martin
    // {
    //     return $this->attributes['title'] = ucwords(strtolower($value));
    // }

    // public function setDescriptionAttribute($value)             // 
    // {
    //     return $this->attributes['description'] = ucfirst(strtolower($value));
    // }
}
