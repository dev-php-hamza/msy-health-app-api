<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Country;
use App\Models\Department;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page  = config('setting.pagination.per_page');
        $companies = Company::with('country')->latest()->paginate($per_page);
        return view('admin.company.index',compact('companies'));
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
        return view('admin.company.create',compact('countries'));
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
            'cName'      => 'required|string|max:255',
            'phone'      => ['required','regex:/(\+?( |-|\.)?\d{1,2}( |-|\.)?)?(\(?\d{3}\)?|\d{3})( |-|\.)?(\d{3}( |-|\.)?\d{2})/'],
            'email'      => 'required|string|email|max:255',
            'country_id' => 'required|numeric',
            'address'    => 'required|string',
        );

        $this->validate($request, $rules);

        $company = Company::create([
            'name'               => $request->cName,
            'key_contact_email'  => $request->email,
            'cc_email_addresses' => $request->cc_email_addresses,
            'phone'              => $request->phone,
            'address'            => $request->address,
            'country_id'         => $request->country_id
        ]);
        
        return redirect()->route('companies.index')->with('message','Company has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return view('admin.company.detail',compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        // $allowedCountries = config('allowedcountries.codes');
        $countries = Country::where('switch', 1)->orderBy('name')->get();
        return view('admin.company.edit',compact('company','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {

        $rules = array(
            'cName'       => 'required|string|max:255',
            'phone'       => ['required','regex:/(\+?( |-|\.)?\d{1,2}( |-|\.)?)?(\(?\d{3}\)?|\d{3})( |-|\.)?(\d{3}( |-|\.)?\d{2})/'],
            'email'       => 'required|string|email|max:255',
            'country_id'  => 'required|numeric',
            'address'     => 'required|string',
        );

        $this->validate($request, $rules);
        
        $company->name               = $request->cName;
        $company->phone              = $request->phone;
        $company->key_contact_email  = $request->email;
        $company->cc_email_addresses = $request->cc_email_addresses;
        $company->country_id         = $request->country_id;
        $company->address            = $request->address;
        $company->save();
        return redirect()->route('companies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('message','Company has been deleted successfully!');
    }

    public function companiesByCountry($countryId)
    {
        $data = array();
        $data['status'] = false;

        $country = Country::whereId($countryId)->first();
        if (!empty($country) && !is_null($country) && $country != '' ) {
            $data['status']    = true;
            $data['companies'] = $country->companies()->orderBy('name')->get(['id','name']);
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

        $termReq   = strtolower($request->term);
        $term      = ucwords($termReq);
        $email     = $termReq;

        $companies = Company::where('name', 'LIKE','%'.$term.'%')
                            ->orWhere('phone', 'LIKE','%'.$term.'%')
                            ->orWhere('key_contact_email', 'LIKE','%'.$email.'%')
                            ->orWhere('address', 'LIKE','%'.$term.'%')
                            ->orderBy('name')
                            ->paginate($per_page);

        if (count($companies) == 0) {
            $countryIds = Country::where('name', 'LIKE','%'.$term.'%')->pluck('id');
            $companies  = Company::whereIn('country_id',$countryIds)->orderBy('name')->paginate($per_page);
        }
        return view('admin.company.index',compact('companies'));
    }

    public function saveImport(Request $request)
    {
        set_time_limit(0);
        $csvData = array_map('str_getcsv', file($request->importFile));
        $arr = array();
        $arr2 = array();
        $arr['header']     = $csvData[0];
        $arr['companies']  = array_splice($csvData, 1);
        // $arr2['departments']  = array_unique(array_column($arr['companies'], 4));
        // dd($arr2);
        // construct an array for country ids
        $countryIds = array();
        if ( count($arr['companies']) > 0) {
            $countryId = Country::whereName($arr['companies'][0][2])->pluck('id')[0];
            $countryIds[$countryId] = $arr['companies'][0][2];
        }

        foreach ($arr['companies'] as $key => $company) {
            $countryName = ucwords(strtolower(trim($company[2])));
            $countryId   = array_search($countryName, $countryIds);
            if ($countryId) {
                $this->storeCompanyAndDepartment($countryId, $company);
            }else{
                // check in db
                $flag = false;
                if ($countryName === 'St. Lucia') {
                    $countryId = 188;
                    $flag = true;
                }elseif ($countryName === 'Trinidad & Tobago' || $countryName === 'Trinidad' || $countryName === 'Tobago') {
                    $countryId = 226;
                    $flag = true;
                }elseif ($countryName === 'St. Kitts') {
                    $countryId = 187;
                    $flag = true;
                }elseif ($countryName === 'Usa') {
                    $countryId = 240;
                    $flag = true;
                }elseif ($countryName === 'Svg') {
                    $countryId = 191;
                    $flag = true;
                }

                if ($flag) {
                    $countryIds[$countryId] = $company[2];
                    $this->storeCompanyAndDepartment($countryId, $company);
                }else{
                    $countryId = Country::whereName($countryName)->pluck('id');
                    if (count($countryId) > 0) {
                        $countryIds[$countryId[0]] = $company[2];
                        $this->storeCompanyAndDepartment($countryId[0], $company);
                    }
                }
            }
        }

        return redirect()->route('companies.index')->with('message','File has been imported successfully!');
    }

    public function storeCompanyAndDepartment($countryId, $companyFile)
    {
        $company = Company::updateOrCreate([
            'name' => trim($companyFile[3]),
            'country_id' => $countryId
        ],[
            'key_contact_email' => 'company@domain.com',
            'phone'      => '090078601000',
            'address'    => 'Your Company address',
            'country_id' => $countryId
        ]);

        if ($companyFile[4] != '' && !is_null($companyFile[4]) && !empty($companyFile[4])) {
            $department = Department::updateOrCreate([
                'name'       => utf8_encode($companyFile[4]),
                'company_id' => $company->id
            ],[
                'name'       => utf8_encode($companyFile[4]),
                'company_id' => $company->id
            ]);
        }
    }
}
