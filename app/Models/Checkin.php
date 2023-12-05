<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $fillable = [
    	'user_id',
    	'user_checkin_lat',
    	'user_checkin_long',
        's1_question',
        'additional_help',
        'additional_feedback'
    ];

    protected $hidden = [
    	'created_at',
    	'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function questions()
    {
    	return $this->belongsToMany('App\Models\Question', 'checkin_questions')->withPivot('option')->withTimestamps();
    }
}
