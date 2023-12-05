<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Country;
use App\Models\Company;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page  = config('setting.pagination.per_page');
        $locations = Location::with('country','healthCenters')->latest()->paginate($per_page);
        return view('admin.location.index',compact('locations'));
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
        return view('admin.location.create',compact('countries'));
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
            'lName'      => 'required|string|max:255',
            'country_id' => 'required|numeric',  
        );

        $this->validate($request, $rules);

        $company = Location::create([
            'name'       => $request->lName,
            'country_id' => $request->country_id
        ]);
        
        return redirect()->route('locations.index')->with('message','Location has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        $healthCenters = $location->healthCenters;
        return view('admin.location.detail',compact('location','healthCenters'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        // $allowedCountries = config('allowedcountries.codes');
        $countries = Country::where('switch', 1)->orderBy('name')->get();
        return view('admin.location.edit',compact('location','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $rules = array(
            'lName'      => 'required|string|max:255',
            'country_id' => 'required|numeric',
        );

        $this->validate($request, $rules);
        
        $location->name       = $request->lName;
        $location->country_id = $request->country_id;
        $location->save();
        return redirect()->route('locations.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')->with('message','Location has been deleted successfully!');
    }

    /**
     * Get locations by specified country resource.
     *
     * @param  \App\Country  $countryId
     * @return json 
     */
    public function locationByCountry($countryId)
    {
        $data = array();
        $data['status'] = false;
        $country = Country::find($countryId);
        if (!empty($country) && $country != '') {
            $data['status'] = true;
            $data['locations'] = $country->locations()->orderBy('name')->get();
        }

        return response()->json($data);
    }

    public function getCompAndLoc($countryId)
    {
        $data = array();
        $data['status'] = false;
        $country = Country::find($countryId);
        if (!empty($country) && $country != '') {
            $data['status'] = true;
            $data['companies'] = $country->companies()->orderBy('name')->get();
            $data['locations'] = $country->locations()->orderBy('name')->get();
        }

        return response()->json($data);
    }

    public function search(Request $request)
    {
        $rules = array(
            'term' => 'required|string|max:255',
        );

        $this->validate($request, $rules);

        $per_page  = config('setting.pagination.per_page');

        $term      = ucwords(strtolower($request->term));

        $locations = Location::where('name', 'LIKE','%'.$term.'%')
                             ->orderBy('name')
                             ->paginate($per_page);
        if (count($locations) == 0 ) {
            $countryIds = Country::where('name', 'LIKE','%'.$term.'%')->pluck('id');
            $locations  = Location::whereIn('country_id', $countryIds)
                                    ->orderBy('name')
                                    ->paginate($per_page);
        }

        return view('admin.location.index',compact('locations'));
    }
}
