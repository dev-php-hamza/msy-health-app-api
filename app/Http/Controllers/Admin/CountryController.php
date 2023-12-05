<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $allowedCountries = config('allowedcountries.codes');
        $per_page  = config('setting.pagination.per_page');
        $countries = Country::orderBy('name')->paginate($per_page);
        // dd($countries);
        return view('admin.country.index',compact('countries'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        return view('admin.country.detail',compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        return view('admin.country.edit',compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $rules = array(
            'cName'          => 'required|string|max:255',
            'calling_code'   => 'required|numeric',
            'territory_code' => 'required|string',
            'email'          => 'required|string|email|max:255'
        );

        $this->validate($request, $rules);
        
        $country->name               = $request->cName;
        $country->calling_code       = $request->calling_code;
        $country->territory_code     = $request->territory_code;
        $country->master_email       = $request->email;
        $country->cc_email_addresses = $request->cc_email_addresses;
        $country->save();

        return redirect()->route('countries.index')->with('message','Country has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        //
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

        $countries = Country::where('name', 'LIKE','%'.$term.'%')
                            ->orWhere('calling_code', 'LIKE','%'.$term.'%')
                            ->orderBy('name')
                            ->paginate($per_page);

        return view('admin.country.index',compact('countries'));
    }

    public function updateCountrySwitch(Request $request)
    {
        $country = Country::where('territory_code',$request->territory_code)->first();
        
        if (isset($country) ) {
            $country->switch = !$country->switch;
            $country->save();
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }
}
