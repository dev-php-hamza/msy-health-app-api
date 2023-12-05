<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckinQuestion extends Model
{
    protected $fillable = [
    	'checkin_id',
    	'question_id',
    	'option'
    ];

    protected $hidden = [
    	'created_at',
    	'updated_at'
    ];
}
