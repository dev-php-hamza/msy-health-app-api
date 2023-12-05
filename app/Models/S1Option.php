<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class S1Option extends Model
{
    protected $fillable = [
    	'option',
    	'lang'
    ];

    protected $hidden = [
    	'created_at',
    	'updated_at',
    ];

    public function scopegetOptByType($query, $type)
    {
    	return $query->whereLang($type);
    }
}
