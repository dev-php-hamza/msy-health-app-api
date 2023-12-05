<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
    	'name',
    	'key_contact_email',
    	'phone',
    	'address',
    	'company_id',
        'location_id'
    ];
    protected $hidden = ['companies', 'created_at', 'updated_at'];

    public function company()
    {
    	return $this->belongsTo('App\Models\Company');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function country()
    {
        return $this->company->country;
    }

    public function scopeOrderByName($query)
    {
        return $query->orderBy('name');
    }

    # MUTATORS - To modify the data 
    # before it saves in the 
    # database
     
    public function setNameAttribute($value)         // first_name as Martin
    {
        return $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function setKeyContactEmailAttribute($value)             // email as martin@test.com
    {
        return $this->attributes['key_contact_email'] = strtolower($value);
    }

    public function setAddressAttribute($value)          // address as Johar Town,Lahore
    {
        return $this->attributes['address'] = ucwords(strtolower($value));
    }
}
