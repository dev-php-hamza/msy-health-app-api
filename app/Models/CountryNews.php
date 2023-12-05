<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryNews extends Model
{
    protected $fillable = [
      'news_id',
      'country_id'
    ];

    public function companyNews()
    {
    	return $this->hasMany('App\Models\CompanyNews');
    }
}
