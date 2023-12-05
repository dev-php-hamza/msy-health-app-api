<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Twilio\Jwt\ClientToken;
use Twilio\Rest\Client;
use Validator;
use Helper;
use App\Models\User;
use App\Models\UserProfile;

class UserController extends Controller
{
	public function uploadImage(Request $request)
	{
		$validator = Validator::make($request->all(), [
	    	'image'       => 'required',
            'profileType' => 'required|string|max:255',
		]);

		if ($validator->fails()) {
	    	return response()->json(ApiResponse::validation($validator->errors()));
		}

		$user = Auth::user();
		$input = $request->all();
		$data = array();
		$filePath = $this->processImage($input['image'], $request->profileType);
		if ($filePath !== false) {

            if ($input['profileType'] === 'profile') {
                if (isset($user->profile_image)) {
                    $this->removeUserPrevImage($user->profile_image, $input['profileType']);
                }
                $user->profile_image = $filePath;
            }else{
                if (isset($user->verification_id_image)) {
                    $this->removeUserPrevImage($user->verification_id_image, $input['profileType']);
                }
                $user->verification_id_image = $filePath;
            }
			
			$user->save();
			// $user = $user->refresh();
			// $userInfo = $user->userInfo;
			$data['url'] = $filePath;
            return response()->json(ApiResponse::success($data));
		}
		return response()->json(ApiResponse::error('invalidImageFormat', 'Invalid image format, please select PNG,JPG,JPEG image format!'));
	}

    public function profile()
    {
    	$userId = Auth::id();
    	$user   = User::with('userInfo')->whereId($userId)->first();
    	return response()->json(ApiResponse::success($user));
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table' => 'required|string',
            // 'type'  => 'required|string',
            'key'   => 'required|string',
            'value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($validator->errors()));
        }

        $user = Auth::user();
        $userInfo = $user->userInfo;

        $allowed_tbls = ['users', 'user_profiles'];

        $table        = $request->table;
        // $type         = $request->type;
        $column       = $request->key;
        $value        = $request->value;

        if (!in_array($table, $allowed_tbls)) {
            return response()->json(ApiResponse::error('TableNotFound', 'Invalid data table requested', 921));
        }

        if ($table === 'users') {
            if (!Schema::hasColumn('users', $column)) {
                return response()->json(ApiResponse::error('ColumnNotFound', 'Invalid data Column requested', 922));
            }

            if ($column === 'email' || $column === 'password') {
                return response()->json(ApiResponse::error('updateRestricted', 'Sorry, The desired field is restricted to update!'));
            }

            if ($user->is_employee == 1 && $column === 'country_id') {
                $userInfo->company_id = Null;
                $userInfo->department_id = Null;
                $userInfo->save();
            }

            if ($user->is_employee == 0 && $column === 'country_id') {
                $user->city = Null;
                $user->save();
            }

            // if ($column === 'profile_image') {
            //     if (isset($user->profile_image) && $user->profile_image != '' && !empty($user->profile_image)) {
            //         $dir = 'profile';
            //         $this->removeUserPrevImage($user->profile_image, $dir);

            //     }
            // }

            // if ($column === 'verification_id_image') {
            //     if (isset($user->verification_id_image) && $user->verification_id_image != '' && !empty($user->verification_id_image)) {
            //         $dir = 'verificationImage';
            //         $this->removeUserPrevImage($user->verification_id_image, $dir);

            //     }
            // }

            $user->update([
                $column => $value
            ]);

            return response()->json(ApiResponse::success());
        }

        if ($table === 'user_profiles') {
            if (!Schema::hasColumn('user_profiles', $column)) {
                return response()->json(ApiResponse::error('ColumnNotFound', 'Invalid data Column requested', 922));
            }
            
            if (count($userInfo) > 0) {
                if ($column === 'company_id') {
                    $userInfo->department_id = Null;
                    $userInfo->save();
                }

                $userInfo->update([
                    $column => $value
                ]);
            }else{
                $userInfo = UserProfile::create([
                    'user_id' => $user->id,
                    $column   => $value
                ]);
            }

            return response()->json(ApiResponse::success());
        }
    }

    public function removeUserPrevImage($filePath, $dirName)
    {
        $dir = '/assets/'.$dirName.'/';
        $fileChunks = explode($dir, $filePath);

        @unlink(public_path($dir.$fileChunks[1]));
        return true;
    }

    public function updatePassword(Request $request)
    {
      try {
	        $validator = Validator::make($request->all(), [
	        	'current_password' => 'required',
	            'new_password'     => 'required',
	        ]);

	        if ($validator->fails()) {
	        	return response()->json(ApiResponse::validation($validator->errors()));
	        }

	        $user = Auth::user();
	        if (Hash::check($request->get('current_password'), $user->password)) {
	        	$user->password = Hash::make($request->get('new_password'));
	        	$user->save();
	        	return response()->json(ApiResponse::success());
	        }else{
	        	return response()->json(ApiResponse::error('UnprocessableEntity', 'Current password is not correct!'));
	        }
        } catch (\Exception $e) {
        	return response()->json(['errors'=>$e->getInfo(), 'message' => 'Please try again', 'status' => false],422);
        }
    }

  //   public function updateProfileImage(Request $request)
  //   {
  //   	$validator = Validator::make($request->all(), [
		//     'image' => 'required',
		// ]);

		// if ($validator->fails()) {
  //   		return response()->json(ApiResponse::validation($validator->errors()));
		// }

		// $user = Auth::user();
		// if (isset($user->profile_image)) {
		// 	$dir = '/assets/profile/';
		// 	$file = $user->profile_image;
		// 	$fileChunks = explode($dir, $file);

		// 	@unlink(public_path($dir.$fileChunks[1]));
		// }

		// $input = $request->all();
		// $data  = array();
		// $filePath = $this->processImage($input['image']);
		// if ($filePath !== false) {
		//   	$user->profile_image = $filePath;
		//   	$user->save();

		//   	$user = $user->refresh();

		//   	$userInfo = $user->userInfo;
		//   	$data['user'] = $user;
		// }
		// return response()->json(ApiResponse::success($data));
  //   }

    // public function uploadVerificationImage(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'image' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(ApiResponse::validation($validator->errors()));
    //     }

    //     $user = Auth::user();
    //     $input = $request->all();
    //     $data = array();
    //     $filePath = $this->processImage($input['image']);
    //     if ($filePath !== false) {
    //         $user->verification_id_image = $filePath;
    //         $user->save();
    //         $user = $user->refresh();
    //         $userInfo = $user->userInfo;
    //         $data['user'] = $user;
    //     }
    //     return response()->json(ApiResponse::success($data));
    // }

    // public function updateVerificationImage(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'image' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(ApiResponse::validation($validator->errors()));
    //     }

    //     $user = Auth::user();
    //     if (isset($user->verification_id_image)) {
    //         $dir = '/assets/profile/';
    //         $file = $user->verification_id_image;
    //         $fileChunks = explode($dir, $file);

    //         @unlink(public_path($dir.$fileChunks[1]));
    //     }

    //     $input = $request->all();
    //     $data  = array();
    //     $filePath = $this->processImage($input['image']);
    //     if ($filePath !== false) {
    //         $user->verification_id_image = $filePath;
    //         $user->save();

    //         $user = $user->refresh();

    //         $userInfo     = $user->userInfo;
    //         $data['user'] = $user;
    //         // $data['user']['user_info'] = $userInfo;
    //     }
    //     return response()->json(ApiResponse::success($data));
    // }

    public function processImage($base64String, $dirName)
    {
      /*imgArr temp array for checking allowed images type*/
      $imgArr = array('jpg','png','jpeg');
      foreach ($imgArr as $key => $value) {
        $strpos = strpos($base64String,$value);
        if ($strpos !== false && $strpos == 11) {
        	$dir = 'assets/'.$dirName.'/';
        	if (! File::isDirectory(public_path($dir))) {
        	    Helper::make_dir($dir);
        	}

          $image_parts = explode(";base64,", $base64String);
          $image_type_exten = explode("image/", $image_parts[0]);
          $image_ext = $image_type_exten[1];
          $image_base64 = base64_decode($image_parts[1]);
          $filename = Helper::make_file_name($image_ext);
          $file = $dir . $filename;
          file_put_contents($file, $image_base64);
          $filePath = url('/').'/'.$dir.$filename;
          return $filePath;
        }
      }
      return false;
    }

    public function verifyUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($validator->errors()));
        }

        $user = Auth::user();
        if ($user->verification_code === $request->verification_code) {
            $user->is_verified = 1;
            $user->verified_at = now();
            $user->save();
            return response()->json(ApiResponse::success());
        }
        return response()->json(ApiResponse::error('UnprocessableEntity', 'Verification Code is Invalid!'));
    }

    public function resendVerificationCode(Request $request)
    {
        $user = Auth::user();
        $userInfo = $user->userInfo;
        $userPhone  = $userInfo->phone;

        $verifyCode = $this->generateVerificationCode();
        $user->verification_code = $verifyCode;
        $user->is_verified = 0;
        $user->verified_at = Null;
        $user->save();

        if (isset($user->email) && !empty($user->email) && $user->email != '') {
            $mailSent = $this->sendVerificationViaMail($verifyCode, $user, $userInfo);
        }else{
            $smsSent  = $this->sendSMSVerification($verifyCode, $user, $userPhone);
        }

        if ((isset($smsSent['status']) && $smsSent['status'] == false) || (isset($mailSent['status']) && $mailSent['status'] == false)) {
            return response()->json(ApiResponse::error('verificationCodeError', 'There is an error occured while sending Verification Code, Please try again'));

        }
        return response()->json(ApiResponse::success());
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

        $data = array();
        $data['status'] = false;
        if (isset($userPhone) && $userPhone !='' && $userPhone != Null) {
            try {
              $twilio  = new Client($accountSid, $authToken);
              $message = $twilio->messages->create($userPhone,
                                    array(
                                        "body" => "Verification code is re-generated for you. Your Massy Connect App Verification Code is ".$verifyCode,
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

        $htmlBody = 'Congrats '.$user->name.',<br><p>Welcome Back to Massy Connect App.</p><br>';
        // $htmlBody .= '<strong>Massy Loyalty Card Info:-</strong><br>';
        $htmlBody .= '<ul><li>Name: '.$user->name.'</li>';
        $htmlBody .= '<li>Email: '.$user->email.'</li>';
        $htmlBody .= '<li>Phone: '.$userInfo->phone.'</li>';
        if ($user->is_employee == 1) {
            $htmlBody .= '<li>Employee: Yes</li>';
            $htmlBody .= '<li>Employee Number: '.$userInfo->employee_number.'</li>';
            $htmlBody .= '<li>Company Name: '.$userInfo->company->name.'</li>';
            $htmlBody .= '<li>Department Name: '.$userInfo->department->name.'</li>';
        }else{
            $htmlBody .= '<li>Employee: No</li>';
            $htmlBody .= '<li>Address: '.$user->address.'</li>';
        }
        $htmlBody .= '</ul><br>';  

        $htmlBody .= 'Verification code is re-generated for you. Please use this <strong>Verification Code</strong> to activate your <i>Massy Connect App</i>';
        $htmlBody .= '<br><ul><li style="list-style: none;"><strong>'.$verifyCode.'</strong></li></ul><br>';

        $email = ($user->email != null) ? $user->email: "devapp@mailinator.com";
    
        $body = array(
                "From"=> "connect@massygroup.com",
                "To"=> $email,
                "Subject"=> "Massy Connect New Verification Code",
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

    public function checkEmail(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'email'  => 'required|string|email|unique:users',
      ]);

      if ($validator->fails()) {
        return response()->json(ApiResponse::validation($validator->errors()));
      }
      return response()->json(ApiResponse::success());
    }

    public function checkPhone(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'phone'  => 'required|unique:user_profiles,phone',
      ]);

      if ($validator->fails()) {
        return response()->json(ApiResponse::validation($validator->errors()));
      }

      $userInfo = UserProfile::wherePhone($request->phone)->count();
      if ($userInfo) {
        $errors = array(
            'phone' => array('The phone has already been taken.')
        );
        
        return response()->json(ApiResponse::validation($errors));
      }
      return response()->json(ApiResponse::success());
    }

    public function sendVerificationCodebyPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'phone'  => 'required|string|exists:user_profiles,phone',
        ],[
            'exists' => 'Requested phone number does not exist.'
        ]);

        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($validator->errors()));
        }

        $userPhone = $request->phone;

        $userInfo  = UserProfile::wherePhone($userPhone)->first();
        $user      = User::find($userInfo->user_id);

        $verifyCode = $this->generateVerificationCode();
        $user->verification_code = $verifyCode;
        $user->is_verified = 0;
        $user->verified_at = Null;
        $user->save();

        $data['user'] = $user;

        $smsSent = $this->sendSMSVerificationByPhone($userPhone, $verifyCode);
        
        if (isset($smsSent['status']) && $smsSent['status'] == false) {
            return response()->json(ApiResponse::error('verificationCodeError', 'There is an error occured while sending Verification Code, Please try again'));

        }


        return response()->json(ApiResponse::success($data));

    }

    public function sendSMSVerificationByPhone($userPhone, $verifyCode)
    {
        $accountSid = config('services.twilio')['TWILIO_ACCOUNT_SID'];
        $authToken  = config('services.twilio')['TWILIO_AUTH_TOKEN'];
        $from       = config('services.twilio')['TWILIO_NUMBER'];

        $data = array();
        $data['status'] = false;
        if (isset($userPhone) && $userPhone !='' && $userPhone != Null) {
            try {
              $twilio  = new Client($accountSid, $authToken);
              $message = $twilio->messages->create($userPhone,
                                    array(
                                        "body" => "Verification code is re-generated for you. Your Massy Connect App Verification Code is ".$verifyCode,
                                        "from" => $from
                                    )
                                );
              $data['status'] = true;
            }
            catch (\Exception $e) {
                $data['status']  = false;
                $data['expCode'] = $e->getCode();
                $data['expMsg']  = $e->getMessage();
            }
        }
        return $data;
    }

    public function verifyByUserId(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'user_id'            => 'required|numeric|exists:users,id',
          'verification_code'  => 'required|numeric',
        ],[
            'exists' => 'Requested user id does not exist.'
        ]);

        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($validator->errors()));
        }

        $user = User::find($request->user_id);
        $verificationCode = $request->verification_code;
        if ($user->verification_code == $verificationCode) {
            $user->is_verified = 1;
            $user->verified_at = now();
            $user->save();
            return response()->json(ApiResponse::success());
        }

        return response()->json(ApiResponse::error('invalidVerificationCode', 'Your provided verification code is invalid!'));
    }

    public function restPasswordByUserId(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'user_id'       => 'required|numeric|exists:users,id',
          'new_password'  => 'required',
        ],[
            'exists' => 'Requested user id does not exist.'
        ]);

        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($validator->errors()));
        }

        $user = User::find($request->user_id);

        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json(ApiResponse::success());
    }

    public function delete()
    {
        $user = Auth::user();
        $profile_image_fileName = $this->checkImageInSystem($user->profile_image);
        $vid_image_fileName = $this->checkImageInSystem($user->verification_id_image);
        if ($profile_image_fileName != false) {
          $fileToBeDeleted =  public_path().'/assets/'.$profile_image_fileName;
          if (file_exists($fileToBeDeleted)) {
            @unlink($fileToBeDeleted);
          }
        }
        if ($vid_image_fileName != false) {
          $fileToBeDeleted =  public_path().'/assets/'.$vid_image_fileName;
          if (file_exists($fileToBeDeleted)) {
            @unlink($fileToBeDeleted);
          }
        }

        $user->delete();
        return response()->json(ApiResponse::success());
    }

    /**
   * Get image-name 
   *
   * @param   string - $urlStirng
   * @return  name: 5d820c85efcee.jpeg |false
   **/
    public function checkImageInSystem($urlStirng)
    {
        if (strpos($urlStirng, 'assets/') !== false) {
          $imageParts = explode('assets/', $urlStirng);
          return $imageParts[1];
        }
        return false;
    }
}
