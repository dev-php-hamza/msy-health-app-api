<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;
use Twilio\Jwt\ClientToken;
use Twilio\Rest\Client;
use Validator;
use App\Models\User;
use App\Models\UserProfile;

class AuthController extends Controller
{
    public function login_Old(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'email'    => 'required|string|email|max:255',
    	    'password' => 'required|string',
    	]);

    	if ($validator->fails()) {
    		return response()->json(ApiResponse::validation($validator->errors()));
    	}

    	if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

    		$user = Auth::user();

	    	if(isset($request->player_id) && !empty($request->player_id) && $request->player_id != ''){
		    	$user->player_id = $request->player_id;
		    	$user->save();
	    	}

	    	$data['user'] = $user;
	    	$data['user']['user_info'] = $user->userInfo;
	    	$data['user_access_token'] = $user->createToken('MyApp')->accessToken;    	 
	    	 
	    	return response()->json(ApiResponse::success($data));
    	}
    	return response()->json(ApiResponse::error('Unauthorized', 'Email or Password is incorrect', 401));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($validator->errors()));
        }

        $auth = false;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();
            $auth = true;

        }else{
            $userIds = UserProfile::where('phone','LIKE','%'.$request->email)->pluck('user_id');
            if (count($userIds) > 0) {
                foreach ($userIds as $key => $userId) {
                    if (Auth::attempt(['id' => $userId, 'password' => $request->password])) {
                        $user = Auth::user();
                        $auth = true;
                        break;
                    }
                }
            }
        }

        if ($auth) {
            if(isset($request->player_id) && !empty($request->player_id) && $request->player_id != '') {
                $user->player_id = $request->player_id;
                $user->save();
            }

            $data['user'] = $user;
            $data['user']['user_info'] = $user->userInfo;
            $data['user_access_token'] = $user->createToken('MyApp')->accessToken;       
             
            return response()->json(ApiResponse::success($data));
        }
        return response()->json(ApiResponse::error('Unauthorized', 'Email/Phone or Password is incorrect', 401));
    }

    public function registration(Request $request)
    {
        if ($request->is_employee === "true") {
            $validator = Validator::make($request->all(), [
                'name'            => 'required|string',
                'email'           => 'nullable|string|email|max:255|unique:users,email',
                'password'        => 'required',
                // 'phone'           => 'required|unique:user_profiles,phone',
                'phone'           => 'nullable|string',
                'date_of_birth'   => 'required|string',
                'gender'          => 'required|string',
                'employee_number' => 'required|string',
                'country_id'      => 'nullable|integer',
                'company_id'      => 'nullable|integer',
                'department_id'   => 'nullable|integer',
                'player_id'       => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(ApiResponse::validation($validator->errors()));
            }
            $userId = $this->massyEmployee($request);

        }else{
            $validator = Validator::make($request->all(), [
                'name'            => 'required|string',
                'email'           => 'required|string|email|max:255|unique:users,email',
                'password'        => 'required',
                // 'phone'           => 'required|unique:user_profiles,phone',
                'phone'           => 'nullable|string',
                'date_of_birth'   => 'required|string',
                'gender'          => 'required|string',
                'city'            => 'nullable|integer',
                'country_id'      => 'nullable|integer',
                'address'         => 'nullable|string',
                'player_id'       => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(ApiResponse::validation($validator->errors()));
            }

            $userId = $this->generalUser($request);
        }

        if (isset($userId) && !empty($userId)) {
            $user       = User::where('id',$userId)->first();
            $userInfo   = $user->userInfo;
            $userPhone  = $userInfo->phone;

            if ($user->is_employee) {

                $verifyCode = $this->generateVerificationCode();
                $user->verification_code = $verifyCode;
                $user->save();

                if (isset($user->email) && !empty($user->email) && $user->email != '') {
                    $mailSent = $this->sendVerificationViaMail($verifyCode, $user, $userInfo);
                }else{
                    $smsSent  = $this->sendSMSVerification($verifyCode, $user, $userPhone);
                }

                // if ((isset($smsSent['status']) && $smsSent['status'] == false) || (isset($mailSent['status']) && $mailSent['status'] == false)) {
                //     return response()->json(ApiResponse::error('verificationCodeError', 'There is an error occured while sending Verification Code, Please try again'));

                // }
            }

            $data['user'] = $user;
            $data['user']['user_info'] = 
            $data['user_access_token'] = $user->createToken('MyApp')->accessToken;

            return response()->json(ApiResponse::success($data));  
        }
        return response()->json(ApiResponse::error('exceptionOccurred', 'Please try again.'));
    }

    public function massyEmployee($request)
    {
        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => bcrypt($request->password),
            'is_employee'     => 1,
            'country_id'      => $request->country_id,
            'player_id'       => $request->player_id,
        ]);

        $userInfo = UserProfile::create([
            'gender'          => $request->gender,
            'phone'           => $request->phone,
            'date_of_birth'   => $request->date_of_birth,
            'user_id'         => $user->id,
            'employee_number' => $request->employee_number,
            'company_id'      => $request->company_id,
            'department_id'   => $request->department_id,
        ]);
        return $user->id;
    }

    public function generalUser($request)
    {
        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => bcrypt($request->password),
            'country_id'      => $request->country_id,
            'city'            => $request->city,
            'address'         => $request->address,
            'player_id'       => $request->player_id,
            'is_verified'     => 1
        ]);

        $userInfo = UserProfile::create([
            'gender'          => $request->gender,
            'phone'           => $request->phone,
            'date_of_birth'   => $request->date_of_birth,
            'user_id'         => $user->id,
        ]);
        return $user->id;
    }


    public function forgotPassword(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($validator->errors()));
        }

        $user = User::where('email',$request->email)->first();
        if(count($user) < 1){
           return response()->json(ApiResponse::error('invalidEmail', 'Sorry, This email is not match with your profile!'));
        }

        $new_password = $this->randomPassword();
        $user->password = Hash::make($new_password);
        $user->save();

        $isSent = $this->sendNewPasswordToMail($new_password, $user);

        return response()->json(ApiResponse::success());
        // if ($isSent['status']) {
        //     return response()->json(ApiResponse::success());
        // }

        // $prevPass = $user->password;
        // $user->password = $prevPass;
        // $user->save();
        // return response()->json(ApiResponse::error('emailNotSent', 'Sorry, There is some issue with email server, please try again!'));
      } catch (\Exception $e) {
        return response()->json(ApiResponse::error('exceptionOccurred', 'Please try again.'));
      }
    }

    protected function randomPassword() {
        $alphabet = 'abcdefghjkmnopqrstuvwxyzABCDEFGHJKMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    protected function sendNewPasswordToMail($new_password, $user)
    {
        $authToken  = config('services.postmark')['token'];

        $htmlBody = 'Hi '.$user->name.',<br><br>';
        $htmlBody .= 'Please use this <strong>Password</strong> to login into your <i>Account</i>.';
        $htmlBody .= '<br><br><strong>'.$new_password.'</strong><br>';

        $body = array(
                  "From"=> "connect@massygroup.com",
                  "To"=> $user->email,
                  "Subject"=> "Massy Connect New Password",
                  "HtmlBody"=> $htmlBody,
                  "TrackOpens"=> true,
                  "TrackLinks"=> "None",
                  "Headers" => array(
                    array(
                      "Name"=> "string",
                      "Value"=> "string"
                    )
                  )
                );

        $body = json_encode($body);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.postmarkapp.com/email");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Accept: application/json','X-Postmark-Server-Token: '.$authToken.''));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = json_decode(curl_exec($ch), true);
        $data = array();
        $data['status'] = false;

        if (curl_errno($ch) || empty($response) || is_null($response) ) {
            $data['status'] = false;
            $data['error']  = curl_error($ch);
        }

        if (isset($response['ErrorCode'])) {
            if ( $response['ErrorCode'] == 0 && $response['Message'] == 'OK' ) {
                $data['status'] = true;
                // $data['mailResponse'] = $response['Message'];
            }else{
              $data['status'] = false;
              $data['error']  = $response['Message'];
            }
        }
        curl_close($ch);

        return $data;
    }

    public function generateVerificationCode()
    {
        return str_pad(mt_rand(1001, 9999),4,'0',STR_PAD_RIGHT);
    }

    public function sendSMSVerification($verifyCode, $user, $userPhone )
    {
        $accountSid = config('services.twilio')['TWILIO_ACCOUNT_SID'];
        $authToken  = config('services.twilio')['TWILIO_AUTH_TOKEN'];
        $from       = config('services.twilio')['TWILIO_NUMBER'];

        // $userPhone  = $this->makePhoneNumberTiwilio($user, $userPhone);

        $data = array();
        $data['status'] = false;
        if (isset($userPhone) && $userPhone !='' && $userPhone != Null) {
            try {
              $twilio  = new Client($accountSid, $authToken);
              $message = $twilio->messages->create($userPhone,
                                    array(
                                        "body" => "Verification code for Massy Connect App is ".$verifyCode,
                                        "from" => $from
                                    )
                                );
              $data['status'] = true;
            }
            catch (\Exception $e) {
                $data['status']  = false;
                $data['expCode'] = $e->getStatusCode();
                $data['expMsg']  = $e->getMessage();
            }
        }
        return $data;
    }

    public function sendVerificationViaMail($verifyCode, $user, $userInfo)
    {
        $authToken  = config('services.postmark')['token'];

        $htmlBody = 'Congrats '.$user->name.',<br><p>Welcome to Massy Connect App.</p><br>';
        // $htmlBody .= '<strong>Massy Loyalty Card Info:-</strong><br>';
        $htmlBody .= '<ul><li>Name: '.$user->name.'</li>';
        $htmlBody .= '<li>Email: '.$user->email.'</li>';
        $htmlBody .= '<li>Phone: '.$userInfo->phone.'</li>';
        if ($user->is_employee == 1) {
            $htmlBody .= '<li>Employee: Yes</li>';
            $htmlBody .= '<li>Employee Number: '.$userInfo->employee_number.'</li>';

            if (isset($userInfo->company_id) && !is_null($userInfo->company_id) && !empty($userInfo->company_id) && $userInfo->company_id != '') {
                $htmlBody .= '<li>Company Name: '.$userInfo->company->name.'</li>';
            }else{
                $htmlBody .= '<li>Company Name: </li>';
            }
            
            if (isset($userInfo->department_id) && !is_null($userInfo->department_id) && !empty($userInfo->department_id) && $userInfo->department_id != '') {
                $htmlBody .= '<li>Department Name: '.$userInfo->department->name.'</li>';
            }else{
                $htmlBody .= '<li>Department Name: </li>';
            }
            
        }else{
            $htmlBody .= '<li>Employee: No</li>';
            $htmlBody .= '<li>Address: '.$user->address.'</li>';
        }
        $htmlBody .= '</ul><br>';  

        $htmlBody .= 'Please use this <strong>Verification Code</strong> to activate your <i>Massy Connect App</i>';
        $htmlBody .= '<br><ul><li style="list-style: none;"><strong>'.$verifyCode.'</strong></li></ul><br>';

        $email = ($user->email != null) ? $user->email: "devapp@mailinator.com";
    
        $body = array(
                "From"=> "connect@massygroup.com",
                "To"=> $email,
                "Subject"=> "Massy Connect Sign Up",
                "HtmlBody"=> $htmlBody,
                "TrackOpens"=> true,
                "TrackLinks"=> "None",
                "Headers" => array(
                  array(
                    "Name"=> "string",
                    "Value"=> "string"
                  )
                )
              );

        $body = json_encode($body);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.postmarkapp.com/email");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Accept: application/json','X-Postmark-Server-Token: '.$authToken.''));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = json_decode(curl_exec($ch), true);
        $data = array();
        $data['status'] = false;

        if (curl_errno($ch) || empty($response) || is_null($response) ) {
            $data['status'] = false;
            $data['error']  = curl_error($ch);
        }

        if (isset($response['ErrorCode'])) {
            if ( $response['ErrorCode'] == 0 && $response['Message'] == 'OK' ) {
                $data['status'] = true;
                // $data['mailResponse'] = $response['Message'];
            }else{
              $data['status'] = false;
              $data['error']  = $response['Message'];
            }
        }
        curl_close($ch);

        return $data;
    }

    public function makePhoneNumberTiwilio($user, $userPhone)
    {
        return '+'.$userPhone;
    }
}
