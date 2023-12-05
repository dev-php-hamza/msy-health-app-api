<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = 
    [
        'name',
        'calling_code',
        'territory_code',
        'switch',
        'master_email',
        'cc_email_addresses'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function users()
    {
    	return $this->hasMany('App\Models\User');
    }

    public function companies()
    {
        return $this->hasMany('App\Models\Company');
    }

    public function locations()
    {
        return $this->hasMany('App\Models\Location');
    }

    public function healthCenters()
    {
        return $this->hasManyThrough('App\Models\HealthCenter', 'App\Models\Location');
    }
    
    public function news()
    {
        return $this->belongsToMany('App\Models\News')->withTimestamps();
    }
    public function resources()
    {
        return $this->belongsToMany('App\Models\Resource')->withTimestamps()->withPivot('id');
    }
    
    # MUTATORS - To modify the data 
    # before it saves in the 
    # database
     
    public function setNameAttribute($value)         // first_name as Martin
    {
        return $this->attributes['name'] = ucwords(strtolower($value));
    }
}
