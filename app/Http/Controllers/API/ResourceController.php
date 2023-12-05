<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponse;
use App\Models\Resource;
use App\Models\Country;
use App\Models\CompanyResource;
use App\Models\CountryResource;
use App\Models\User;
use App\Models\UserProfile;
use Validator;
use Response;
use Auth;

class ResourceController extends Controller
{
    public function massyEmpResources(Request $request)
    {
      $user = Auth()->user();

      $countryId = $user->country_id;
      $user_id   = $user->id;

      $user     = User::findOrFail($user_id);
      $userInfo = $user->userInfo;
      $userCompanyId = $userInfo->company_id;

      $resourcesObjs = DB::table('resources')
        ->join('country_resource','resources.id','=','country_resource.resource_id')
        ->join('company_resource','country_resource.id','=','company_resource.country_resource_id')
        ->where('country_resource.country_id',$countryId)
        ->where('company_resource.company_id',$userCompanyId)
        ->where(function ($query) {
          return $query->where('for_employee', 'yes')->orWhere('for_employee', 'both');
        })
        ->select('resources.*')
        ->get();

      $data = array();
      foreach ($resourcesObjs as $key => $resource) {
        $temp = array();
        $temp['id']           = $resource->id;
        $temp['title']        = $resource->title;
        $temp['description']  = $resource->description;
        $temp['for_employee'] = $resource->for_employee;
        $temp['url']          = $resource->url;
        $temp['icon']         = (isset($resource->icon))?url('/').'/assets/resources/'.$resource->icon:Null;
        $temp['lang']         = $resource->lang;
        $temp['created_at']   = $resource->created_at;

        $data[] = $temp;
      }
    return response()->json(ApiResponse::success($data)); 
  }

    public function generalUserResources(Request $request)
    {
      $user = Auth()->user();

      $countryId = $user->country_id;

      $resourcesObjs = DB::table('resources')
        ->join('country_resource','resources.id','=','country_resource.resource_id')
        ->where('country_resource.country_id',$countryId)
        ->where(function ($query) {
          return $query->where('for_employee', 'no')->orWhere('for_employee', 'both');
        })
        ->select('resources.*')
      ->get();

      $data = array();
      
      foreach ($resourcesObjs as $key => $resource) {
        $temp = array();
        $temp['id']           = $resource->id;
        $temp['title']        = $resource->title;
        $temp['description']  = $resource->description;
        $temp['for_employee'] = $resource->for_employee;
        $temp['url']          = $resource->url;
        $temp['icon']         = (isset($resource->icon))?url('/').'/assets/resources/'.$resource->icon:Null;
        $temp['lang']         = $resource->lang;
        $temp['created_at']   = $resource->created_at;

        $data[] = $temp;
      }
    return response()->json(ApiResponse::success($data));
  }
}
