<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use Validator;
use App\Models\Country;
use App\Models\Company;
use App\Models\Question;
use App\Models\S1Option;

class HomeController extends Controller
{
    public function countries()
    {
        // $allowedCountries = config('allowedcountries.codes');

    	$allcountries = Country::where('switch', 1)->orderBy('name')->get();
    	return response()->json(ApiResponse::success($allcountries));
    }

    public function companiesByCountry(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'country_id' => 'required|integer',
    	]);

    	if ($validator->fails()) {
    		return response()->json(ApiResponse::validation($validator->errors()));
    	}

    	$country = Country::find($request->country_id);
    	if (!empty($country) && !is_null($country) && $country != '') {
    		$data['companies'] = $country->companies()->OrderByName()->get();
    		return response()->json(ApiResponse::success($data));
    	}
    	return response()->json(ApiResponse::modelNotFound('UnprocessableEntity', 'Country'));
    }

    public function brancesByCompany(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'company_id' => 'required|integer',
    	]);

    	if ($validator->fails()) {
    		return response()->json(ApiResponse::validation($validator->errors()));
    	}

    	$company = Company::find($request->company_id);
    	if (!empty($company) && !is_null($company) && $company != '') {
    		$data['branches'] = $company->branches()->OrderByName()->get();
    		return response()->json(ApiResponse::success($data));
    	}
    	return response()->json(ApiResponse::modelNotFound('UnprocessableEntity', 'Company'));
    }

    public function departmentsByCompany(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'company_id' => 'required|integer',
    	]);

    	if ($validator->fails()) {
    		return response()->json(ApiResponse::validation($validator->errors()));
    	}

    	$company = Company::find($request->company_id);
    	if (!empty($company) && !is_null($company) && $company != '') {
    		$data['departments'] = $company->departments()->OrderByName()->get();
    		return response()->json(ApiResponse::success($data));
    	}
    	return response()->json(ApiResponse::modelNotFound('UnprocessableEntity', 'Company'));
    }

    public function questions()
    {
        $allQuestions['questions'] = Question::orderBy('title')->get();
        return response()->json(ApiResponse::success($allQuestions));
    }

    public function s1Options()
    {
        $s1Options['s1_options'] = S1Option::orderBy('option')->get();
        return response()->json(ApiResponse::success($s1Options));
    }

    public function locationsByCountry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($validator->errors()));
        }

        $country = Country::find($request->country_id);
        if (!empty($country) && !is_null($country) && $country != '') {
            $data['locations'] = $country->locations()->OrderByName()->get();
            return response()->json(ApiResponse::success($data));
        }
        return response()->json(ApiResponse::modelNotFound('UnprocessableEntity', 'Country'));
    }
}
