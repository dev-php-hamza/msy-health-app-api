<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
    	'title',
    	'url',
        'icon',
    	'description',
    	'for_employee',
        'lang',
        'check_all'
    ];

    protected $hidden = [
    	'created_at',
    	'updated_at'
    ];

    public function countries()
    {
        return $this->belongsToMany('App\Models\Country')->withTimestamps()->withPivot('id');
    }
    public function companies()
    {
        return $this->belongsToMany('App\Models\Company')->withTimestamps();
    }

    public function scopeforMassyEmployee($query)
    {
        return $query->whereForEmployee('yes')->orWhere('for_employee', 'both');
    }

    public function scopeforGeneralUser($query)
    {
        return $query->whereForEmployee('no')->orWhere('for_employee', 'both');
    }
    

    # MUTATORS - To modify the data 
    # before it saves in the 
    # database
     
    public function setTitleAttribute($value)         // first_name as Martin
    {
        return $this->attributes['title'] = ucwords(strtolower($value));
    }

    public function setDescriptionAttribute($value)             // 
    {
        return $this->attributes['description'] = ucfirst(strtolower($value));
    }
}
