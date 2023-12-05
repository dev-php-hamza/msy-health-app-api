<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\News;
use App\Models\Country;
use App\Models\CountryNews;
use App\Models\CompanyNews;
use Illuminate\Http\Request;
use App\Models\HealthCenter;
use Helper;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = config('setting.pagination.per_page');
        $newsF = News::latest()->paginate($per_page);
        return view('admin.news.index',compact('newsF'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $allowedCountries = config('allowedcountries.codes');
        $countries = Country::where('switch', 1)->orderBy('name')->get();
        return view('admin.news.create',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'lang'         => 'required|string|max:2',
            'title'        => 'required|string|max:255',
            'external_url' => 'nullable|url',
            'snapshot'     => 'required|string|max:255',
            'description'  => 'required|string',
            'embeded_video'=> 'nullable|string',
            'for_employee' => 'required|string',
            // 'newsFile'     => 'required|image|mimes:jpeg,png,jpg',
            // 'banner_file'  => 'required|image|mimes:jpeg,png,jpg',
        );

        $this->validate($request, $rules);

        $news = News::create([
            'title'        => $request->title,
            'external_url' => $request->external_url,
            'snapshot'     => $request->snapshot,
            'description'  => $request->description,
            'embeded_video'=> $request->embeded_video,
            'for_employee' => $request->for_employee,
            'lang'         => $request->lang,
            'check_all'    => $request->check_all
        ]);

        if ($request->hasFile('newsFile')) {
            $dir = "assets/news";
            if (! File::isDirectory(public_path($dir))) {
                Helper::make_dir($dir);
            }

            $file = $request->newsFile;
            $file_ext = $file->extension();
            $filename = Helper::make_file_name($file_ext);
            $file->move($dir, $filename);
            $news->image = $filename;
        }
        
        if ($request->hasFile('banner_file')) {
            $dir = "assets/news";
            if (! File::isDirectory(public_path($dir))) {
                Helper::make_dir($dir);
            }

            $file = $request->banner_file;
            $file_ext = $file->extension();
            $filename = Helper::make_file_name($file_ext);
            $file->move($dir, $filename);
            $news->banner_image = $filename;
        }

        $news->save();
        $newsId = $news->id;
            $companyIds = $request->companyIds;
        foreach ($companyIds as $key => $compIds) {
            $exp       = explode(':', $compIds);
            $countryId = $exp[0];
            $companyId = $exp[1];

            $countryNews = CountryNews::where('country_id',$countryId)->where('news_id',$newsId)->first();
            if ($countryNews) {
                $pivotId = $countryNews->id;
            }else {
                $countryNews = CountryNews::create([
                    'news_id'    => $newsId,
                    'country_id' => $countryId
                ]);

                $pivotId = $countryNews->id;
            }
            if ($companyId != 0) {
                $companyNews = CompanyNews::create([
                'country_news_id' => $pivotId, 
                'company_id'      => $companyId
                ]);
            }
        }
        return redirect()->route('news.index')->with('success', 'News has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        $newsCountries = $news->countries;
        $data          = array();
        foreach ($newsCountries as $key => $country) {
            $temp            = array();
            $temp['country'] = $country;
            $temp['country']['pivot_id'] = $country->pivot->id;
            $data[] = $temp;
        }
        return view('admin.news.detail',compact('news','data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        // $allowedCountries = config('allowedcountries.codes');
        $countriesAll   = Country::with('companies')->where('switch', 1)->orderBy('name')->get();

        $countries      = $news->countries;
        $countryNewsIds = array();
        $countryIds     = array();
        foreach ($countries as $key => $country) {
            $countryNewsIds[] = $country->pivot->id;
            $countryIds[]     = $country->id;
        }
        $companyNewsIds = CompanyNews::whereIn('country_news_id',$countryNewsIds)->pluck('company_id')->toArray();
        $countryNewsIds = CountryNews::whereIn('country_id',$countryIds)->pluck('country_id')->toArray();
        return view('admin.news.edit',compact('news', 'countriesAll', 'companyNewsIds', 'countryNewsIds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        // dd($request->all());
        $rules = array(
            'lang'            => 'required|string|max:2',
            'title'           => 'required|string|max:255',
            'external_url'    => 'nullable|url',
            'snapshot'        => 'required|string|max:255',
            'description'     => 'required|string',
            'embeded_video'   => 'nullable|string',
            'for_employee'    => 'required|string',
            'newsFile'        => 'image|mimes:jpeg,png,jpg',
            'banner_file'     => 'image|mimes:jpeg,png,jpg',
        );

        $this->validate($request, $rules);
        
        $news->title          = $request->title;
        $news->external_url   = $request->external_url;
        $news->snapshot       = $request->snapshot;
        $news->description    = $request->description;
        $news->embeded_video  = $request->embeded_video;
        $news->for_employee   = $request->for_employee;
        $news->lang           = $request->lang;

        if ($request->hasFile('newsFile')) {
            $dir = "assets/news";
            if (! File::isDirectory(public_path($dir))) {
                Helper::make_dir($dir);
            }

            $filename   = $news->image;
            $fileCheck = Helper::file_exists_sys($dir, $filename);
            if ($fileCheck) {
                Helper::unlink_file($dir, $filename);
            }

            $file        = $request->newsFile;
            $file_ext    = $file->extension();
            $filename    = Helper::make_file_name($file_ext);
            $file->move($dir, $filename);
            $news->image = $filename;
        }

        if ($request->hasFile('banner_file')) {
            $dir = "assets/news";
            if (! File::isDirectory(public_path($dir))) {
                Helper::make_dir($dir);
            }

            $filename   = $news->banner_image;
            $fileCheck = Helper::file_exists_sys($dir, $filename);
            if ($fileCheck) {
                Helper::unlink_file($dir, $filename);
            }

            $file        = $request->banner_file;
            $file_ext    = $file->extension();
            $filename    = Helper::make_file_name($file_ext);
            $file->move($dir, $filename);
            $news->banner_image = $filename;
        }
        $news->save();
        if(isset($request->companyIds) && !empty($request->companyIds) && $request->companyIds != ''){
            $newsId = $news->id;
            $frontEndArr  = $request->companyIds;
            // get countryIds from db
            $dbCountryIds = CountryNews::where('news_id', $newsId)->pluck('country_id')->toArray();

            $countryId = array();

            foreach ($frontEndArr as $key => $countryIds) {
                $explode = explode(':', $countryIds);
                $countryId[] = $explode[0];
            }

            // delete existing country
            $diffArr = array_diff(array_unique($dbCountryIds), array_unique($countryId));
            foreach ($diffArr as $key => $countryId) {
                $countryNews = CountryNews::where('news_id', $newsId)->where('country_id', $countryId)->delete();
            }
            // countrynews and companynews association
            foreach ($frontEndArr as $key => $countryIds) {
                $explode   = explode(':', $countryIds);
                $countryId = $explode[0];
                $companyId = $explode[1];

                $countryNews = CountryNews::firstOrCreate([
                 'news_id'    =>  $newsId,
                 'country_id' =>  $countryId
                ],[
                    'news_id' => $newsId
                ]);

                if ($companyId == 0) {
                    $countryNews->companyNews()->delete();
                }else{
                    $companyNews = CompanyNews::create([
                    'country_news_id' => $countryNews->id,  
                    'company_id'      => $companyId 
                    ]);
                }
            }
        }elseif (!isset($request->companyIds) && empty($request->companyIds) && $request->companyIds == '') {
            $newsId = $news->id;
            $countryNews = CountryNews::where('news_id',$newsId)->delete(); 
        }

        return redirect()->route('news.index')->with('success', 'News has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {

        $image = $news->image;
        $banner_image = $news->banner_image;
        $dir = '/assets/news';
        $imageExists = Helper::file_exists_sys($dir, $image);
        $bannerImageExists = Helper::file_exists_sys($dir, $banner_image);

        if ($imageExists) {
            @unlink(public_path($dir.'/'.$image));
        }

        if ($bannerImageExists) {
            @unlink(public_path($dir.'/'.$banner_image));
        }

        $news->delete();

        return redirect()->route('news.index',compact('news'))->with('success', 'News has been deleted successfully');; 
    }

    public function search(Request $request)
    {
        $rules = array(
            'term' => 'required|string|max:255',
        );

        $this->validate($request, $rules);

        $per_page  = config('setting.pagination.per_page');

        $termReq   = strtolower($request->term);
        $term      = ucwords($termReq);
        $desc      = ucfirst($termReq);
        $newsF     = News::where('title', 'LIKE','%'.$term.'%')
                            ->orWhere('snapshot', 'LIKE','%'.$desc.'%')
                            ->orWhere('description', 'LIKE','%'.$desc.'%')
                            ->orderBy('title')
                            ->paginate($per_page);

        return view('admin.news.index',compact('newsF'));
    }
}
