<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page  = config('setting.pagination.per_page');
        $notifications = Notification::with('users')->latest()->paginate($per_page);
        return view('admin.notification.index',compact('notifications'));
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
        return view('admin.notification.create', compact('countries'));
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
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'country_id'  => 'required|numeric'
        );

        $this->validate($request, $rules);

        $notification = Notification::create([
            'title'       => $request->title,
            'description' => $request->description,
            'country_id'  => $request->country_id
        ]);
        
        return redirect()->route('notifications.choose_user',$notification->id);
    }

    public function choose_user($id)
    {        
        $notification = Notification::find($id);
        $usersCount   = User::whereCountryId($notification->country_id)->count();
        return view('admin.notification.choose-user',compact('notification', 'usersCount'));
    }

    public function getUsersbyCountry($countryId)
    {
        $data = array();
        $data['status'] = false;
        $country = Country::find($countryId);
        if (!empty($country) && $country != '') {
            $data['status'] = true;
            $data['users'] = $country->users()->orderBy('name')->get();
           
        }

        return response()->json($data);
    }

    public function saveNotificationUsers(Request $request)
    {
        $rules = array(
            'notification_id' => 'required|numeric',
            // 'user.*'          => 'required',
        );

        $this->validate($request, $rules);

        if (isset($request->user) && !empty($request->user) && count($request->user) > 0) {
            $users           = $request->user;
            $notification_id = $request->notification_id;
            $notification    = Notification::whereId($notification_id)->first();

            if (isset($notification)) {
                if ($users[0] === 'all') {
                    /*Fetch all users against notification country*/
                    $countryId = $notification->country_id;
                    $userIds   = User::whereCountryId($countryId)->pluck('id'); 
                        
                }else{
                    $userIds = $request->user;
                }

                $notification->users()->attach($userIds);
                $notification->is_completed  = 1;
                $notification->save();

                $users = User::whereIn('id', $userIds)->get();

                $player_ids = array();
                foreach ($users as $key => $user) {
                    if(isset($user->player_id) && !empty($user->player_id) && $user->player_id != ''){
                        $player_ids[] = $user->player_id;
                    }
                }
                
                $title = $notification->title;
                $desc  = $notification->description;

                $totalRecepient  = 0;
                $chunkedPlayerIds = array_chunk($player_ids,1999);
                foreach ($chunkedPlayerIds as $key => $playerIds) {
                    $response = $this->sendNotification($player_ids, $title, $desc, $notification_id);
                    if ($response['status'] == true) {
                        $totalRecepient += $response['recipients'];
                    }
                }

                $notification->total_notification_users = count($users);
                $notification->total_push_notification_recipients = $totalRecepient;
                $notification->save();


                if (!$response['status']) {
                    return redirect()->route('notifications.index')->with('messege', 'There is some problem while sending notification!');
                }
                return redirect()->route('notifications.index')->with('message', 'Notification has been saved successfully and delivered successful!');
            }
            return redirect()->route('notifications.index')->with('messege','Notification not found');
        }
        return redirect()->route('notifications.index')->with('messege','Notification save without any user!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        $users      = $notification->users()->count();
        $country    = $notification->country->name;
        return view('admin.notification.detail',compact('notification','users','country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {   
        $userIds    = array();
        $notifUsers = $notification->users;
          foreach($notifUsers as $user){
            $userIds[] = $user->id;
        }

        $coutryuser     = $notifUsers->first();
        $country        = $coutryuser->country;
        $usersbycountry = $country->users;
        return view('admin.notification.edit',compact('notification','country','usersbycountry', 'userIds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        $rules = array(
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'user.*'      => 'required',
        );

        $this->validate($request, $rules);

        if (isset($request->user)) {
            $userIds = $request->user;
            if (isset($notification)) {
                $notification->title       = $request->title;
                $notification->description = $request->description;
                $notification->users()->sync($userIds);
                $notification->save();
              return redirect()->route('notifications.index');
            }
            return back()->with('messege','Notification not found');
        }
        return back()->with('messege','User not found');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('notifications.index');
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
        
        $notifications = Notification::where('title', 'LIKE','%'.$term.'%')
                            ->orWhere('description', 'LIKE','%'.$desc.'%')
                            ->orderBy('title')
                            ->paginate($per_page);

        return view('admin.notification.index',compact('notifications'));
    }

    public function sendNotification($player_ids, $title, $desc, $notification_id)
    {
        $oneSignalKey = config('services.oneSignal.app_key');

        $content = array("en" => $desc);
        $heading = array("en" => $title);
        $data    = array('notification_id' => $notification_id);
        /*Add comma seprated Player id into include_player_ids i.e: array("6392d91a-b206-4b7b-a620-cd68e32c3a76","76ece62b-bcfe-468c-8a78-839aeaa8c5fa","8e0f21fa-9a5a-4ae7-a9a6-ca1f24294b86")*/
        $fields = array(
                    'app_id'             => $oneSignalKey,
                    'include_player_ids' => $player_ids,
                    'data'     => $data,
                    'contents' => $content,
                    'headings' => $heading,
                    'ios_badgeType'  => 'Increase',
                    'ios_badgeCount' => 1
                );
                
        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $result = json_decode(curl_exec($ch), true);

        $data = array();
        $data['status'] = false;
        $data['recipients'] = 0;
        if (curl_errno($ch) || empty($result) || is_null($result) ) {
            $data['status'] = false;
        }

        if (isset($result['id']) && isset($result['recipients'])) {
            $data['status'] = true;
            $data['recipients'] = $result['recipients'];
        }
        curl_close($ch);

        return $data;
    }

    public function getUsersByCountryAndTerm(Request $request)
    {
        $notification = Notification::find($request->notification_id);
        $countryId = $notification->country_id;
        $input = $request->all();
        $sql = "SELECT u.id FROM users u INNER JOIN user_profiles ups ON u.id=ups.user_id where u.country_id='".$countryId."' and u.role_id = 2";  

        if (isset($input['email']) && !empty($input['email']) && $input['email'] != '') {
            $sql .= " and u.email='".$input['email']."'";
        }
        if (isset($input['phone']) && !empty($input['phone']) && $input['phone'] != '') {
            $sql .= " and ups.phone=".$input['phone'];
        }
        if (isset($input['name']) && !empty($input['name']) && $input['name'] != '') {
            $sql .= " and (u.name LIKE '%".$input['name']."%')";
        }

        $results = DB::select( DB::raw($sql) );
        $tempUserIds = array();
        
        // Extract all ids from $results
        foreach ($results as $key => $user) {
            array_push($tempUserIds, $user->id);
        }

        $userData = array();
        $userData['custom_search'] = true;
        $userData['users'] = User::whereIn('id', $tempUserIds)->latest()->get();

        $usersCount = count($tempUserIds);

        return view('admin.notification.choose-user', ['notification' => $notification, 'usersCount' => $usersCount, 'userData' => $userData]);
    }
}
