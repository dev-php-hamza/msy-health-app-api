<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponse;
use App\Models\News;
use App\Models\CountryNews;
use App\Models\User;
use Validator;
use Response;
use Auth;

class NewsController extends Controller
{
    public function massyEmpNews(Request $request)
    {
        $user = Auth()->user();

        $countryId = $user->country_id;
        $user_id   = $user->id;
        
        $user     = User::find($user_id);
        $userInfo = $user->userInfo;
        $userCompanyId = $userInfo->company_id;

        $newsObjs = DB::table('news')
            ->join('country_news','news.id','=','country_news.news_id')
            ->join('company_news','country_news.id','=','company_news.country_news_id')
            ->where('country_news.country_id',$countryId)
            ->where('company_news.company_id',$userCompanyId)
            ->where(function ($query){
                return $query->where('for_employee', 'yes')->orWhere('for_employee', 'both');
        })
        ->select('news.*')
        ->latest()
        ->get();

        $data = array();
        foreach ($newsObjs as $key => $news) {
            $temp = array();
            $temp['id']           = $news->id;
            $temp['title']        = $news->title;
            $temp['external_url'] = $news->external_url;
            $temp['snapshot']     = $news->snapshot;
            $temp['description']  = $news->description;
            $temp['embeded_video']= $news->embeded_video;
            $temp['for_employee'] = $news->for_employee;
            $temp['image']        = url('/').'/assets/news/'.$news->image;
            $temp['banner_image'] = (isset($news->banner_image))?url('/').'/assets/news/'.$news->banner_image:Null;
            $temp['lang']         = $news->lang;
            $temp['created_at']   = date_format(date_create($news->created_at), "Y-m-d");

            $data[] = $temp;
        }
        return response()->json(ApiResponse::success($data));
    }

    public function generalNews(Request $request)
    {
        $user = Auth()->user();

        $countryId = $user->country_id;
        
        $newsObjs = DB::table('news')
            ->join('country_news','news.id','=','country_news.news_id')
            ->where('country_news.country_id',$countryId)
            ->where(function ($query){
                    return $query->where('for_employee', 'no')->orWhere('for_employee', 'both');
                })
            ->select('news.*')
            ->latest()
            ->get();

        $data = array();
        foreach ($newsObjs as $key => $news) {
            $temp = array();
            $temp['id']           = $news->id;
            $temp['title']        = $news->title;
            $temp['external_url'] = $news->external_url;
            $temp['snapshot']     = $news->snapshot;
            $temp['description']  = $news->description;
            $temp['embeded_video']= $news->embeded_video;
            $temp['for_employee'] = $news->for_employee;
            $temp['image']        = url('/').'/assets/news/'.$news->image;
            $temp['banner_image'] = (isset($news->banner_image))?url('/').'/assets/news/'.$news->banner_image:Null;
            $temp['lang']         = $news->lang;
            $temp['created_at']   = date_format(date_create($news->created_at), "Y-m-d");

            $data[] = $temp;
        }
        return response()->json(ApiResponse::success($data));
    }
}
