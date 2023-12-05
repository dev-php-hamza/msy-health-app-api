<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class HealthCenterController extends Controller
{
    public function healthCenters(Request $request)
    {
    	$user = Auth::user();
    	$healthCenters = $user->country->healthCenters()->orderBy('name', 'asc')->get();
    	$data['points'] = $this->extractData($healthCenters);
    	return response()->json(ApiResponse::success($data));
    }

    public function extractData($healthCenters)
    {
    	$data = array();
    	foreach ($healthCenters as $key => $healthCenter) {
    		$temp = array();
    		$temp["id"]          = $healthCenter->id;
    		$temp["name"] 		 = $healthCenter->name;
    		$temp["latitude"] 	 = $healthCenter->latitude;
    		$temp["longitude"] 	 = $healthCenter->longitude;
            $temp["phone"]       = $healthCenter->phone;
    		$temp["image"] 		 = url('/').'/assets/healthcenter/'.$healthCenter->image;
    		$temp["address"] 	 = $healthCenter->address;
    		$temp["location"]    = $healthCenter->location;
    		$data[]              = $temp;
    	}

    	return $data;
    }
}
