<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Company;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = config('setting.pagination.per_page');
        $departments = Department::latest()->paginate($per_page);
        return view('admin.department.index',compact('departments'));
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
        return view('admin.department.create',compact('countries'));
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
            'company_id' => 'required|numeric',
            'email'      => 'nullable|string|email|max:255'
        );

        $this->validate($request, $rules);

        $company = Department::create([
            'name'               => $request->name,
            'company_id'         => $request->company_id,
            'master_email'       => $request->email,
            'cc_email_addresses' => $request->cc_email_addresses
        ]);
        
        return redirect()->route('departments.index')->with('message','Department has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        return view('admin.department.detail',compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        // $allowedCountries = config('allowedcountries.codes');
        $countries = Country::where('switch', 1)->orderBy('name')->get();
        $departCountry   = $department->company->country;
        $departCountryId = $departCountry->id;
        $companies       = $departCountry->companies;
        return view('admin.department.edit',compact('department','companies','countries', 'departCountryId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $rules = array(
            'name'         => 'required|string|max:255',
            'company_id'   => 'required|numeric',
            'email'        => 'nullable|string|email|max:255'
        );

        $this->validate($request, $rules);
        
        $department->name               = $request->name;
        $department->company_id         = $request->company_id;
        $department->master_email       = $request->email;
        $department->cc_email_addresses = $request->cc_email_addresses;
        $department->save();

        return redirect()->route('departments.index')->with('message','Department has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('message','Department has been deleted successfully!');
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

        $departments = Department::where('name', 'LIKE','%'.$term.'%')
                            ->orderBy('name')
                            ->paginate($per_page);

        if (count($departments) == 0) {
            $companyIds  = Company::where('name', 'LIKE','%'.$term.'%')->pluck('id');
            $departments = Department::whereIn('company_id', $companyIds)
                                       ->orderBy('name')
                                       ->paginate($per_page);
        }
        
        return view('admin.department.index',compact('departments'));
    }

    public function departmentsByCompany($companyId)
    {
        // dd($companyId);
        $data = array();
        $data['status'] = false;

        $departments = Department::whereCompanyId($companyId)->orderBy('name')->get();
        if (!empty($departments) && !is_null($departments) && $departments != '' ) {
            $data['status']    = true;
            $data['departments'] = $departments;
        }
        return response()->json($data);
    }
}
