<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyNews extends Model
{
    protected $fillable = [
       'country_news_id',
       'company_id'
    ];

    public function countryNews()
    {
    	return $this->belongsTo('App\Models\CountryNews');
    }
}
