<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Location;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use App\Models\UserProfile;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page  = config('setting.pagination.per_page');
        $users = User::with('userInfo')->latest()->paginate($per_page);
        return view('admin.user.index',compact('users'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $userInfo = $user->userInfo;
        $location = '';
        if (isset($user->city) && $user->city != '' && !is_null($user->city)) {
            $location   = Location::whereId($user->city)->first();
        }

        return view('admin.user.detail',compact('user','userInfo','location'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // $allowedCountries = config('allowedcountries.codes');
        $userInfo    = $user->userInfo;
        $countries   = Country::where('switch', 1)->orderBy('name')->get();
        $locations   = Location::whereCountryId($user->country_id)->orderBy('name')->get();
        $companies   = Company::whereCountryId($user->country_id)->orderBy('name')->get();
        $departments = Department::whereCompanyId($userInfo->company_id)->orderBy('name')->get();

        return view('admin.user.edit',compact('user', 'userInfo', 'countries', 'locations', 'companies', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        if ($request->is_employee) {
            $rules = array(
                'name'            => 'required|string',
                'phone'           => 'required',
                'date_of_birth'   => 'required|string',
                'gender'          => 'required|string',
                'is_employee'     => 'required|integer',
                'employee_number' => 'required|string',
                // 'country_id'      => 'required|integer',
                'company_id'      => 'required|integer',
                'department_id'   => 'required|integer',
            );

            $this->validate($request, $rules);

            $user = User::whereId($id)->first();
            if (isset($user) && !is_null($user) && $user != '') {
                if ($user->is_employee == $request->is_employee ) {
                    // Normal update
                    $user->name = $request->name;
                    $user->save();
                    $this->updateUserInfo($request, $user, 'massy');
                }else{
                    // put null into city and address and put values company_id, department_id and employee number in userInfo
                    $user->name        = $request->name;
                    $user->is_employee = 1;
                    $user->city        = Null;
                    $user->address     = Null;
                    $user->save();

                    $this->updateUserInfo($request, $user, 'massy');
                }
                return redirect()->route('users.index')->with('message','User Updated successfully!');
            }
        }else{
            $rules = array(
                'name'          => 'required|string',
                'phone'         => 'required',
                'date_of_birth' => 'required|string',
                'gender'        => 'required|string',
                'is_employee'   => 'required|integer',
                'city'          => 'required|integer',
                // 'country_id'    => 'required|integer',
                'address'       => 'required|string',
            );

            $this->validate($request, $rules);

            $user = User::whereId($id)->first();
            if (isset($user) && !is_null($user) && $user != '') {
                if ($user->is_employee == $request->is_employee ) {
                    // Normal update
                    $user->name    = $request->name;
                    $user->city    = $request->city;
                    $user->address = $request->address;
                    $user->save();

                    $this->updateUserInfo($request, $user, 'general');
                }else{
                   // Put null in company_id, department_id and employee_number
                    $user->name    = $request->name;
                    $user->city    = $request->city;
                    $user->address = $request->address;
                    $user->is_employee = 0;
                    $user->save();
                    
                    $this->updateUserInfo($request, $user, 'general');
                }

                return redirect()->route('users.index')->with('message','User Updated successfully!');
            }
        }

        return redirect()->route('users.index')->with('message','User Not Found!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (isset($user->profile_image)) {
            // remove profile_image too
            $dir = 'profile';
            $this->removeUserImage($user->profile_image, $dir);
        }

        if (isset($user->verification_id_image)) {
            // remove verification_id_image too
            $dir = 'verificationImage';
            $this->removeUserImage($user->verification_id_image, $dir);
        }
        $user->delete();
        return redirect()->route('users.index')->with('message','User has been deleted successfully!');
    }

    public function removeUserImage($filePath, $dirName)
    {
        $dir = '/assets/'.$dirName.'/';
        $fileChunks = explode($dir, $filePath);

        @unlink(public_path($dir.$fileChunks[1]));
        return true;
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

        $users = User::where('name', 'LIKE','%'.$term.'%')
                            ->orWhere('email', 'LIKE','%'.$email.'%')
                            ->orderBy('name')
                            ->paginate($per_page);

        if (count($users) == 0) {
            $userProfileIds = UserProfile::where('phone', 'LIKE','%'.$term.'%')->pluck('user_id');
            $users          = User::whereIn('id',$userProfileIds)->orderBy('name')->paginate($per_page);
        }
        return view('admin.user.index',compact('users'));
    }

    public function updateUserInfo($request, $user, $userType)
    {
        $userInfo = $user->userInfo;

        if ($userType == 'massy') {
            $userInfo->phone           = $request->phone;
            $userInfo->date_of_birth   = $request->date_of_birth;
            $userInfo->gender          = $request->gender;
            $userInfo->company_id      = $request->company_id;
            $userInfo->department_id   = $request->department_id;
            $userInfo->employee_number = $request->employee_number;
            $userInfo->save();
        }

        if ($userType == 'general') {
            $userInfo->phone           = $request->phone;
            $userInfo->date_of_birth   = $request->date_of_birth;
            $userInfo->gender          = $request->gender;
            $userInfo->company_id      = Null;
            $userInfo->department_id   = Null;
            $userInfo->employee_number = Null;
            $userInfo->save();
        }

        return true;
    }
}
