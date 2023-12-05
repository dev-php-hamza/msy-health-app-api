<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
    	'gender',
    	'phone',
    	'date_of_birth',
    	'user_id',
    	'company_id',
    	'department_id',
    	'employee_number'
    ];

    protected $hidden = [
    	'created_at',
    	'updated_at'
    ];

    # MUTATORS - To modify the data 
    # before it saves in the 
    # database

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
     
     public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function setGenderAttribute($value)         // gender as male|female
    {
        return $this->attributes['gender'] = strtolower($value);
    }
}
