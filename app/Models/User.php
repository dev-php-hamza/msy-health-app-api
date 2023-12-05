<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country_id',
        'city',
        'address',
        'profile_image',
        'verification_id_image',
        'is_employee',
        'player_id',
        'is_verified',
        'google_id',
        'facebook_id',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];

    public function isAdmin()
    {
        $flag = false;
        if ($this->role_id === 1) {
            $flag = true;
        }

        return $flag;
    }

    // public function scopeName()
    // {
    //     return $this->first_name.' '.$this->last_name;
    // }

    public function userInfo()
    {
        return $this->hasOne('App\Models\UserProfile');
    }

    public function notifications()
    {
        return $this->belongsToMany('App\Models\Notification','notification_users');
    }

    public function readNotifications()
    {
       return $this->notifications()->where('read', 1);
    }

    public function unreadNotifications()
    {
       return $this->notifications()->where('read', 0);
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function checkins()
    {
        return $this->hasMany('App\Models\Checkin');
    }

    
    # MUTATORS - To modify the data 
    # before it saves in the 
    # database
     
    public function setNameAttribute($value)         // first_name as Martin
    {
        return $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function setEmailAttribute($value)             // email as martin@test.com
    {
        return $this->attributes['email'] = strtolower($value);
    }

    public function setAddressAttribute($value)          // address as Johar Town,Lahore
    {
        return $this->attributes['address'] = ucwords(strtolower($value));
    }
}