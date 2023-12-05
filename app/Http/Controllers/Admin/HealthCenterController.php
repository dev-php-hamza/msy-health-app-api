<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\HealthCenter;
use App\Models\Country;
use App\Models\Location;
use Helper;


class HealthCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = config('setting.pagination.per_page');
        $healthCenters = HealthCenter::with('location')->latest()->paginate($per_page);
        return view('admin.healthcenter.index',compact('healthCenters'));
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
        return view('admin.healthcenter.create',compact('countries'));
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
            'name'        => 'required|string|max:255',
            // 'location_id' => 'required|numeric',
            // 'email'       => 'nullable|email|unique:health_centers,email',
            // 'phone'       => ['required','regex:/(\+?( |-|\.)?\d{1,2}( |-|\.)?)?(\(?\d{3}\)?|\d{3})( |-|\.)?(\d{3}( |-|\.)?\d{2})/'],
            // 'address'     => 'required|string',
            // 'centerFile'  => 'required|image|mimes:jpeg,png,jpg',
        );

        $this->validate($request, $rules);
        if ($request->latitude != 0 && $request->longitude != 0) {
            $address = '';
            $addr = explode(',', $request->address);
            if (count($addr) > 3) {
                $address = $addr[0].', '.$addr[1];
            }elseif(count($addr) == 2 || count($addr) == 3){
                $address = $addr[0];
            }else{
                $address = 'No Address Available';
            }

            $healthCenter = HealthCenter::create([
                'name'        => $request->name,
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'location_id' => $request->location_id,
                'address'     => $address
            ]);

            if ($request->hasFile('centerFile')) {
                $dir = "assets/healthcenter";
                if (! File::isDirectory(public_path($dir))) {
                    Helper::make_dir($dir);
                }

                $file = $request->centerFile;
                $file_ext = $file->extension();
                $filename = Helper::make_file_name($file_ext);
                $file->move($dir, $filename);
                $healthCenter->image = $filename;
                $healthCenter->save();
            }
            
            return redirect()->route('health-centers.index')->with('message','Health Centers has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors(['address' => 'Invalid Address']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HealthCenter  $healthCenter
     * @return \Illuminate\Http\Response
     */
    public function show(HealthCenter $healthCenter)
    {
        return view('admin.healthcenter.detail',compact('healthCenter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HealthCenter  $healthCenter
     * @return \Illuminate\Http\Response
     */
    public function edit(HealthCenter $healthCenter)
    {
        // $allowedCountries  = config('allowedcountries.codes');

        $countries = '';
        $hlthCountryId = '';
        $hlthCenterCountry = '';
        $locations = '';

        if (isset($healthCenter->location_id) && !empty($healthCenter->location_id) && $healthCenter->location_id != '') {
            $hlthCenterCountry = $healthCenter->country();
            $hlthCountryId = $hlthCenterCountry->id;
            $countries = Country::where('switch', 1)->orderBy('name')->get();
            $locations = $hlthCenterCountry->locations;
        }

        return view('admin.healthcenter.edit',compact('healthCenter','countries', 'hlthCountryId', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HealthCenter  $healthCenter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HealthCenter $healthCenter)
    {
        $rules = array(
            'name'        => 'required|string|max:255',
            // 'location_id' => 'required|numeric',
            // 'email'       => 'nullable|email|unique:health_centers,email,'.$healthCenter->id.',id',
            // 'phone'       => ['required','regex:/(\+?( |-|\.)?\d{1,2}( |-|\.)?)?(\(?\d{3}\)?|\d{3})( |-|\.)?(\d{3}( |-|\.)?\d{2})/'],
            // 'address'     => 'required|string',
            // 'centerFile'  => 'image|mimes:jpeg,png,jpg',
        );

        $this->validate($request, $rules);

        if ($request->latitude != 0 && $request->longitude != 0) {
            $address = $healthCenter->address;
            if ($request->address != $healthCenter->address) {
                $addr = explode(',', $request->address);
                if (count($addr) > 3) {
                    $address = $addr[0].', '.$addr[1];
                }elseif(count($addr) == 2 || count($addr) == 3){
                    $address = $addr[0];
                }
            }

            $healthCenter->name        = $request->name;
            $healthCenter->latitude    = $request->latitude;
            $healthCenter->longitude   = $request->longitude;
            $healthCenter->email       = $request->email;
            $healthCenter->phone       = $request->phone;
            $healthCenter->address     = $address;
            $healthCenter->location_id = $request->location_id;
            $healthCenter->save();

            if ($request->hasFile('centerFile')) {
                $dir = "assets/healthcenter";
                if (! File::isDirectory(public_path($dir))) {
                    Helper::make_dir($dir);
                }

                $filename  = $healthCenter->image;
                $fileCheck = Helper::file_exists_sys($dir, $filename);
                if ($fileCheck) {
                    Helper::unlink_file($dir, $filename);
                }

                $file = $request->centerFile;
                $file_ext = $file->extension();
                $filename = Helper::make_file_name($file_ext);
                $file->move($dir, $filename);
                $healthCenter->image = $filename;
                $healthCenter->save();
            }
            
            return redirect()->route('health-centers.index')->with('message','Health Centers has been updated successfully!');
        }
        return redirect()->back()->withInput()->withErrors(['address' => 'Invalid Address']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HealthCenter  $healthCenter
     * @return \Illuminate\Http\Response
     */
    public function destroy(HealthCenter $healthCenter)
    {
        $filename = $healthCenter->image;
        $dir = 'assets/healthcenter';
        $fileExists = Helper::file_exists_sys($dir, $filename);

        if ($fileExists) {
            Helper::unlink_file($dir, $filename);
        }

        $healthCenter->delete();

        return redirect()->route('health-centers.index',compact('healthCenter'))->with('message', 'Health Center has been deleted successfully');;
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
        $healthCenters  = HealthCenter::where('name', 'LIKE','%'.$term.'%')
                            ->orWhere('email', 'LIKE','%'.$email.'%')
                            ->orWhere('phone', 'LIKE','%'.$termReq.'%')
                            ->orWhere('address', 'LIKE','%'.$term.'%')
                            ->orderBy('name')
                            ->paginate($per_page);

        if (count($healthCenters) == 0) {
            $locationIds   = Location::where('name', 'LIKE','%'.$term.'%')->pluck('id');
            $healthCenters = HealthCenter::whereIn('location_id', $locationIds)
                                 ->orderBy('name')
                                 ->paginate($per_page);
        }
        return view('admin.healthcenter.index',compact('healthCenters'));
    }
}
