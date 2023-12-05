<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Helpers\ApiResponse;

class AppSettingController extends Controller
{
    public function index()
    {
    	$settings = AppSetting::get();
    	$response = $this->extractData($settings);
    	
    	return response()->json(ApiResponse::success($response));
    }

    public function extractData($settings)
    {
    	$data = array();
        foreach ($settings as $key => $setting) {
            $temp = array();
            $temp['intro_text']      = $setting->intro_text;
            $temp['allowed_domains'] = explode(",",$setting->allowed_domains);
            $temp['lang']            = $setting->lang;
            $data[] = $temp;
        }

    	return $data;
    }
}
