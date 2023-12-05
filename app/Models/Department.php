<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
    	'name',
    	'company_id',
        'master_email',
        'cc_email_addresses'
    ];

    protected $hidden = [
    	'created_at',
    	'updated_at'
    ];

    public function company()
    {
    	return $this->belongsTo('App\Models\Company');
    }

    public function scopeOrderByName($query)
    {
        return $query->orderBy('name');
    }

}