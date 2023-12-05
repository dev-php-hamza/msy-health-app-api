<?php

namespace App\Models;
use App\Models\Country;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
    	'name',
    	'country_id'
    ];

    protected $hidden = ['countries'];

    public function country()
    {
    	return $this->belongsTo('App\Models\Country');
    }

    public function healthCenters()
    {
    	return $this->hasMany('App\Models\HealthCenter');
    }

    # MUTATORS - To modify the data 
    # before it saves in the 
    # database
     
    public function setNameAttribute($value)         // first_name as Martin
    {
        return $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function scopeOrderByName($query)
    {
        return $query->orderBy('name');
    }
}
