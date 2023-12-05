<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\Resource;
use App\Models\Country;
use App\Models\Company;
use App\Models\CompanyResource;
use App\Models\CountryResource;
use Illuminate\Http\Request;
use Helper;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page  = config('setting.pagination.per_page');
        $resources = Resource::latest()->paginate($per_page);
        return view('admin.resource.index',compact('resources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $allowedCountries = config('allowedcountries.codes');
        $countriesAll = Country::with('companies')->where('switch', 1)->orderBy('name')->get();

        return view('admin.resource.create',compact('countriesAll'));
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
            'title'        => 'required|string|max:255',
            'url'          => 'required|url',
            'for_employee' => 'required|string',
            'description'  => 'required|string|max:240',
            'icon_file'    => 'nullable|image|mimes:jpeg,png,jpg',
            'lang'         => 'required|string',
        );

        $this->validate($request, $rules);

        $resource = Resource::create([
            'title'        => $request->title,
            'url'          => $request->url,
            'for_employee' => $request->for_employee,
            'description'  => $request->description,
            'lang'         => $request->lang,
            'check_all'    => $request->check_all
        ]);
        
        if ($request->hasFile('icon_file')) {
            $dir = "assets/resources";
            if (! File::isDirectory(public_path($dir))) {
                Helper::make_dir($dir);
            }

            $file = $request->icon_file;
            $file_ext = $file->extension();
            $filename = Helper::make_file_name($file_ext);
            $file->move($dir, $filename);
            $resource->icon = $filename;
            $resource->save();
        }
        
        if (isset($request->companyIds) && !empty($request->companyIds) && $request->companyIds != '') {

            $resourceId = $resource->id;
            $companyIds = $request->companyIds;

            foreach ($companyIds as $key => $compIds) {
                $exp = explode(':', $compIds);
                $countryId = $exp[0];
                $companyId = $exp[1];
                $countryResource = CountryResource::where('country_id',$countryId)->where('resource_id',$resourceId)->first();
                if ($countryResource) {
                    $pivotId = $countryResource->id;
                }else {
                    $countryResource = CountryResource::create([
                        'resource_id' => $resourceId,
                        'country_id'  => $countryId
                    ]);

                    $pivotId = $countryResource->id;
                }


                if ($companyId != 0) {
                    $companyResource = CompanyResource::create([
                        'country_resource_id' => $pivotId, 
                        'company_id'          => $companyId
                    ]);
                }
            }       
        }

        return redirect()->route('resources.index')->with('message','Resource has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        $countries = $resource->countries;
        $data = array();
        foreach ($countries as $key => $country) {
            $tempArr = array();
            $tempArr['country']    = $country;
            $tempArr['country']['pivot_id'] = $country->pivot->id;
            $data[] = $tempArr; 
        }
        return view('admin.resource.detail',compact('resource','data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        // $allowedCountries = config('allowedcountries.codes');
        $countriesAll = Country::with('companies')->where('switch', 1)->orderBy('name')->get();

        $countries          = $resource->countries;
        $countryResourceIds = array();
        $cIds = array();

        foreach ($countries as $key => $country) {
            $countryResourceIds[] = $country->pivot->id;
            $cIds[] = $country->id;
        }

        $companyRes = CompanyResource::whereIn('country_resource_id',$countryResourceIds)->pluck('company_id')->toArray();
        $countryRes = CountryResource::whereIn('country_id',$cIds)->pluck('country_id')->toArray();

        return view('admin.resource.edit',compact('resource', 'countriesAll', 'companyRes', 'countryRes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resource $resource)
    {
        $rules = array(
            'title'        => 'required|string|max:255',
            'url'          => 'required|url',
            'for_employee' => 'required|string',
            'description'  => 'required|string|max:240',
            'icon_file'    => 'nullable|image|mimes:jpeg,png,jpg',
            'lang'         => 'required|string'
        );

        $this->validate($request, $rules);

        $resource->title        = $request->title;
        $resource->url          = $request->url;
        $resource->for_employee = $request->for_employee;
        $resource->description  = $request->description;
        $resource->lang         = $request->lang;
        $resource->save();

        if ($request->hasFile('icon_file')) {
            $dir = "assets/resources";
            if (! File::isDirectory(public_path($dir))) {
                Helper::make_dir($dir);
            }

            $filename  = $resource->icon;
            $fileCheck = Helper::file_exists_sys($dir, $filename);
            if ($fileCheck) {
                Helper::unlink_file($dir, $filename);
            }

            $file = $request->icon_file;
            $file_ext = $file->extension();
            $filename = Helper::make_file_name($file_ext);
            $file->move($dir, $filename);
            $resource->icon = $filename;
        }
        $resource->save();

        // Delete country not comming from frontend

        if(isset($request->companyIds) && !empty($request->companyIds) && $request->companyIds != ''){
            $resourceId   = $resource->id;
            $requestedArr = $request->companyIds;

            $countryResourceIds = CountryResource::where('resource_id',$resourceId)->pluck('country_id')->toArray();
            $countIds = array();

            foreach ($requestedArr as $key => $countryIds) {
                $expId = explode(':', $countryIds);
                $countIds[] = $expId[0];
            }

            $fullDiff = array_diff(array_unique($countryResourceIds), array_unique($countIds));
            foreach ($fullDiff as $key => $countryId) {
                $countryReso = CountryResource::where('country_id',$countryId)->where('resource_id',$resourceId)->delete();
            }

            // Add company association
            foreach ($requestedArr as $key => $countryCompany) {
                $explodeIds = explode(':', $countryCompany);
                $countryId  = $explodeIds[0];
                $companyId  = $explodeIds[1];
                $countryResource  = CountryResource::firstOrCreate([
                    'country_id'  => $countryId,
                    'resource_id' => $resourceId
                ],[
                    'resource_id' => $resourceId
                ]);

                if ($companyId == 0) {
                    $countryResource->companyResources()->delete();
                }else{
                    $companyResource = CompanyResource::create([
                        'country_resource_id' => $countryResource->id,
                        'company_id'          => $companyId
                    ]);
                }
            }
        }elseif (!isset($request->companyIds) && empty($request->companyIds) && $request->companyIds == '') {
            $resourceId   = $resource->id;
            $countryReso = CountryResource::where('resource_id',$resourceId)->delete(); 
        }

        return redirect()->route('resources.index')->with('message','Resource has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {
        $filename = $resource->icon;
        $dir = '/assets/resources';
        $fileExists = Helper::file_exists_sys($dir, $filename);

        if ($fileExists) {
            @unlink(public_path($dir.'/'.$filename));
        }
        
        $resource->delete();
        return redirect()->route('resources.index')->with('message', 'Resource has been deleted successfully');
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
        
        $resources = Resource::where('title', 'LIKE','%'.$term.'%')
                            ->orWhere('description', 'LIKE','%'.$desc.'%')
                            ->orderBy('title')
                            ->paginate($per_page);

        return view('admin.resource.index',compact('resources'));
    }
}
