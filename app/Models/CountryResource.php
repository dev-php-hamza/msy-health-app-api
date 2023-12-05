<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryResource extends Model
{
	protected $table = 'country_resource';
    protected $fillable = [
    	'resource_id',
    	'country_id'
    ];

    public function companyResources()
    {
    	return $this->hasMany('App\Models\CompanyResource');
    }
}
