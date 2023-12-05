<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Country;
use App\Models\Location;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page  = config('setting.pagination.per_page');
        $branches = Branch::orderBy('name')->paginate($per_page);
        return view('admin.branch.index',compact('branches'));
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
        return view('admin.branch.create',compact('countries'));
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
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:branches,key_contact_email',
            'phone'      => ['required','regex:/(\+?( |-|\.)?\d{1,2}( |-|\.)?)?(\(?\d{3}\)?|\d{3})( |-|\.)?(\d{3}( |-|\.)?\d{2})/'],
            'company_id' => 'required|numeric',
            'location_id'=> 'required|numeric',
            'address'    => 'required|string',
        );

        $this->validate($request, $rules);

        $branch = Branch::create([
            'name'              => $request->name,
            'key_contact_email' => $request->email,
            'phone'             => $request->phone,
            'company_id'        => $request->company_id,
            'location_id'       => $request->location_id,
            'address'           => $request->address,
        ]);
        
        return redirect()->route('branches.index')->with('message','Branch has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        $company = $branch->company;
        $country = $company->country->name;
        return view('admin.branch.detail',compact('branch','country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        // $allowedCountries = config('allowedcountries.codes');
        $countries = Country::where('switch', 1)->orderBy('name')->get();
        $branchCountry   = $branch->country();
        $branchCountryId = $branchCountry->id;
        $locations = $branchCountry->locations;
        $companies = $branchCountry->companies;

        return view('admin.branch.edit',compact('branch','countries', 'branchCountryId', 'locations', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $branch)
    {
        $rules = array(
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:branches,key_contact_email,'.$branch->id,
            'phone'      => ['required','regex:/(\+?( |-|\.)?\d{1,2}( |-|\.)?)?(\(?\d{3}\)?|\d{3})( |-|\.)?(\d{3}( |-|\.)?\d{2})/'],
            'company_id' => 'required|numeric',
            'location_id'=> 'required|numeric',
            'address'    => 'required|string',
        );

        $this->validate($request, $rules);


        $branch->name              = $request->name;
        $branch->key_contact_email = $request->email;
        $branch->phone             = $request->phone;
        $branch->company_id        = $request->company_id;
        $branch->location_id       = $request->location_id;
        $branch->address           = $request->address;
        $branch->save();
        
        return redirect()->route('branches.index')->with('message','Branch has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branches.index')->with('message','Branch has been deleted successfully!');
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
        $email     = $termReq;
        $branches  = Branch::where('name', 'LIKE','%'.$term.'%')
                            ->orWhere('key_contact_email', 'LIKE','%'.$email.'%')
                            ->orWhere('phone', 'LIKE','%'.$termReq.'%')
                            ->orWhere('address', 'LIKE','%'.$term.'%')
                            ->orderBy('name')
                            ->paginate($per_page);

        if (count($branches) == 0) {
            $companyIds = Company::where('name', 'LIKE','%'.$term.'%')->pluck('id');
            $branches   = Branch::whereIn('company_id', $companyIds)
                                 ->orderBy('name')
                                 ->paginate($per_page);
        }
        return view('admin.branch.index',compact('branches'));
    }
}
