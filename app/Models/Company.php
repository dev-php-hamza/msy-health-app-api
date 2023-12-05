<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
    	'name',
    	'key_contact_email',
        'cc_email_addresses',
    	'address',
    	'phone',
    	'country_id'
    ];

    protected $hidden = ['countries', 'created_at', 'updated_at'];

    public function country()
    {
    	return $this->belongsTo('App\Models\Country');
    }

    public function departments()
    {
        return $this->hasMany('App\Models\Department');
    }

    public function branches()
    {
        return $this->hasMany('App\Models\Branch');
    }

    public function news()
    {
        return $this->belongsToMany('App\Models\News')->withTimestamps();
    }

    public function resources()
    {
        return $this->belongsToMany('App\Models\Resource')->withTimestamps();
    }
    
    public function scopeOrderByName($query)
    {
        return $query->orderBy('name');
    }

    # MUTATORS - To modify the data 
    # before it saves in the 
    # database

    public function setKeyContactEmailAttribute($value)             // email as martin@test.com
    {
        return $this->attributes['key_contact_email'] = strtolower($value);
    }

    public function setAddressAttribute($value)          // address as Johar Town,Lahore
    {
        return $this->attributes['address'] = ucwords(strtolower($value));
    }
    
}
