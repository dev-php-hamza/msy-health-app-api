<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = [
    	'intro_text',
    	'allowed_domains',
    	'lang'
    ];

    public function AllowedDomains()
    {
    	return explode(',', $this->allowed_domains);
    }
}
