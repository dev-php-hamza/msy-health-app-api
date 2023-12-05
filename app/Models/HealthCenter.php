<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCenter extends Model
{
    protected $fillable = [
    	'name',
    	'latitude',
    	'longitude',
        'image',
        'phone',
        'email',
    	'address',
        'type',
    	'location_id',
    ];

    protected $hidden = [
    	'locations',
    ];

    public function location()
    {
    	return $this->belongsTo('App\Models\Location');
    }

    public function country()
    {
    	return $this->location->country;
    }

    # MUTATORS - To modify the data 
    # before it saves in the 
    # database
     
    public function setNameAttribute($value)         // first_name as Martin
    {
        return $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function setEmailAttribute($value)             // email as martin@test.com
    {
        return $this->attributes['email'] = strtolower($value);
    }

    public function setAddressAttribute($value)          // address as Johar Town,Lahore
    {
        return $this->attributes['address'] = ucwords(strtolower($value));
    }
}
