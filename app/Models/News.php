<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
    	'title',
        'external_url',
        'snapshot',
    	'description',
        'embeded_video',
    	'for_employee',
    	'image',
        'banner_image',
        'lang',
        'check_all'
    ];

    public function scopeforMassyEmployee($query)
    {
    	return $query->whereForEmployee('yes')->orWhere('for_employee', 'both');
    }

    public function scopeforGeneralUser($query)
    {
    	return $query->whereForEmployee('no')->orWhere('for_employee', 'both');
    }

    public function countries()
    {
        return $this->belongsToMany('App\Models\Country')->withTimestamps()->withPivot('id');
    }

     public function companies()
    {
        return $this->belongsToMany('App\Models\Company')->withTimestamps();
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
