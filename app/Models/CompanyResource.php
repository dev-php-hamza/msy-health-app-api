<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyResource extends Model
{
	protected $table = 'company_resource';
	
     protected $fillable = [
       'country_resource_id',
       'company_id'
    ];

    public function countryResource()
    {
    	return $this->belongsTo('App\Models\CountryResource','country_resource_id')->withTimestamps();
    }
}
