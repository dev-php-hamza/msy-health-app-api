<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;
use Validator;
use App\Models\Company;
use App\Models\Department;
use App\Models\CheckinQuestion;
use App\Models\Question;
use App\Models\Checkin;

class CheckinController extends Controller
{
	public function index()
	{
		$userId = Auth::id();
		$userCheckIns = Checkin::with('questions')->whereUserId($userId)->latest()->get();
		$data = $this->extractData($userCheckIns);
		return response()->json(ApiResponse::success($data));
	}

	public function extractData($userCheckIns)
	{
		$data = array();
		foreach ($userCheckIns as $key => $checkin) {
			$tempC = array();
			$tempC['id'] = $checkin->id;
			$tempC['s1_question']       = $checkin->s1_question;
			$tempC['user_checkin_lat']  = $checkin->user_checkin_lat;
			$tempC['user_checkin_long'] = $checkin->user_checkin_long;
			$tempC['additional_help']   = $checkin->additional_help;
			$tempC['additional_feedback'] = $checkin->additional_feedback;

			foreach ($checkin->questions as $key => $question) {
				$tempQ = array();
				$tempQ['id']     = $question->id;
				$tempQ['title']  = $question->title;
				$tempQ['option'] = (bool)$question->pivot->option;
				$tempC['questions'][] = $tempQ;
			}

			$data['checkins'][] = $tempC;
		}

		return $data;
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			's1_question'         => 'required|string',
			'additional_help'     => 'nullable|integer',
			'additional_feedback' => 'nullable|string',
			'checkin_lat'    => 'nullable|numeric',
			'checkin_long'   => 'nullable|numeric',
			'questions'      => 'required|array',
			'questions.*.id' => 'required|integer|exists:questions,id'
		]);

		if ($validator->fails()) {
			return response()->json(ApiResponse::validation($validator->errors()));
		}

		$user       = Auth::user();
		$userInfo   = $user->userInfo;


		$masterEmails   = '';
		$CcEmails       = '';
		$companyName    = '';
		$departmentName = '';

		if ($user->is_employee) {
			
			// get company and department names
			if (isset($user->country_id) && !is_null($user->country_id) && !empty($user->country_id) && $user->country_id != '') {
				$country    = $user->country;
			}

			if (isset($userInfo->company_id) && !is_null($userInfo->company_id) && !empty($userInfo->company_id) && $userInfo->company_id != '') {
				$company    = Company::find($userInfo->company_id);
				$companyName = $company->name;
			}

			if (isset($userInfo->department_id) && !is_null($userInfo->department_id) && !empty($userInfo->department_id) && $userInfo->department_id != '') {
				$department = Department::find($userInfo->department_id);
				$departmentName  = $department->name;
			}
			
			
			
			
			

			/*Get All master emails from country, company and department and store them into $masterEmails variable with comma separated*/
				if (isset($country->master_email) && !empty($country->master_email) && !is_null($country->master_email) && ($country->master_email != '')) {
					$masterEmails .= $country->master_email.',';
				}
				if (isset($company->key_contact_email) && !empty($company->key_contact_email) && !is_null($company->key_contact_email) && ($company->key_contact_email != '')) {
					$masterEmails .= $company->key_contact_email.',';
				}
				if (isset($department->master_email) && !empty($department->master_email) && !is_null($department->master_email) && ($department->master_email != '')) {
					$masterEmails .= $department->master_email;
				}
				$masterEmails    = rtrim($masterEmails, ',');
			// end of masterEmails

			// Get Cc Emails
			if (isset($country->cc_email_addresses) && !empty($country->cc_email_addresses) && !is_null($country->cc_email_addresses) && ($country->cc_email_addresses != '')) {
				$CcEmails .= rtrim($country->cc_email_addresses, ',').',';
			}
			if (isset($company->cc_email_addresses) && !empty($company->cc_email_addresses) && !is_null($company->cc_email_addresses) && $company->cc_email_addresses != '') {
				$CcEmails .= rtrim($company->cc_email_addresses, ',').',';
			}
			if (isset($department->cc_email_addresses) && !empty($department->cc_email_addresses) && !is_null($department->cc_email_addresses) && $department->cc_email_addresses != '') {
				$CcEmails .= rtrim($department->cc_email_addresses, ',');
			}

			$CcEmails = rtrim($CcEmails, ',');
		}

		$checkIn = Checkin::create([
			'user_id'             => $user->id,
			's1_question'         => $request->s1_question,
			'additional_help'     => $request->additional_help,
			'additional_feedback' => $request->additional_feedback
		]);

		$questions = $request->questions;
		foreach ($questions as $key => $question) {
			$checkIn->questions()->attach(
				array(
					$question['id'] => array('option' => $question['option'])
				)
			);
		}

		$checkIn = $checkIn->refresh();
		$questions = $checkIn->questions;

		$isEmailSent = $this->sendEmailToCompany($masterEmails, $CcEmails, $user, $questions, $companyName ,$departmentName, $checkIn);
		if ($isEmailSent['status']) {
			return response()->json(ApiResponse::success());
		}

		$checkIn->delete();
		return response()->json(ApiResponse::error('emailServerError', 'Sorry! There is an issue while sending your request. Please try again later.'));
	}

	public function sendEmailToCompany($masterEmails, $CcEmails, $userObj, $questions, $companyName, $departmentName, $checkInObj)
	{
		$postmarkToken = config('services.postmark.token');

	    $htmlBody = self::makeEmailHtml($userObj, $questions, $companyName, $departmentName, $checkInObj);
	    $body = array(
	    "From"       => "connect@massygroup.com",
	    // "To"      => "khubaibellahi@gmail.com",
	    // "To"      => $storeObj->email,
	    "To"         => ($masterEmails == '')?"storex@mailinator.com":$masterEmails,
	    "Cc"         => $CcEmails,
	    "Subject"    => "User Check In Request",
	    "HtmlBody"   => $htmlBody,
	    "TrackOpens" => true,
	    "TrackLinks" => "None",
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
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Accept: application/json','X-Postmark-Server-Token: '.$postmarkToken.''));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);
	    curl_setopt($ch, CURLOPT_POST, TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	    $result = curl_exec($ch);
	    $data = array();
	    $data['status'] = true;

		if (curl_errno($ch) || empty($result) || is_null($result) || !isset($result)) {
		    $error_msg = curl_error($ch);
		    $data['status'] = false;
		    $data['error'] = json_decode($error_msg, true);
		}elseif ( isset($result['HttpStatusCode']) && $result['HttpStatusCode'] != 200 ){
			$data['status'] = false;
		}

		curl_close($ch);
		return $data;
	}

	public static function makeEmailHtml($userObj, $questions, $companyName, $departmentName, $checkInObj)
	{
		$userInfo = $userObj->userInfo;
		$htmlBody = '<!DOCTYPE html>
					<html>
					<head>
						<meta name="viewport" content="width=device-width, initial-scale=1.0" />
						<meta name="x-apple-disable-message-reformatting" />
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
						<style>
						  @font-face {
						    font-family: Suisse;
						    src: url("assets/fonts/Suisse Light.otf");
						    font-weight: 300;
						  }
						  @font-face {
						    font-family: Suisse;
						    src: url("assets/fonts/Suisse Medium.ttf");
						    font-weight: 500;
						  }
						  @font-face {
						    font-family: Suisse;
						    src: url("assets/fonts/Suisse SemiBold.ttf");
						    font-weight: 600;
						  }

						  [style*="Suisse"] {
						    font-family: "Suisse", Arial, sans-serif !important;
						  }
						</style>
						<link
						  href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;500;700&display=swap"
						  rel="stylesheet"
						/>
					</head>
					<body>
					<div style="width: 100%; height: auto; background-color:#f6f6f6;font-size: 16px;">
						<div style="max-width: 600px; margin: 0 auto; background-color:white;">
						  <div style=" padding: 25px 0;background: rgb(236, 236, 236);text-align: center;">
						    <div style="margin: 0 auto; display: inline-block;">
						      <img src="https://beta-massyhealth.simplyintense.com/assets/images/logo.png" alt="logo" style="max-width: 50px; float: left;"/>
						      <h2 style="font-family: Arial, sans-serif, Suisse; color: #333; font-size: 48px; margin: 0; text-transform: uppercase; font-weight: 600; line-height: 1; color: #003660; margin-top: 6px; ">
						        Massy <br />
						        Connect
						      </h2>
						    </div>
						  </div>
						  <div style="max-width: 90%; margin: 60px auto 0;">
						    <h3 style="font-size: 18px; font-family: Arial, sans-serif, Suisse;margin-bottom: 25px;">
						      User Information
						    </h3>
						    <div style="margin-bottom: 10px;">
						      <p style="float: left;margin: 0;min-width: 230px;font-family: Arial, sans-serif, Suisse;color: #333;" >
						        Name
						      </p>
						      <p style="margin: 0;min-width: 230px;font-family: Arial, sans-serif, Suisse;color: #333;">
						        '.$userObj->name.'
						      </p>
						    </div>';
						    if ($userObj->is_employee == 1) {
						    	$htmlBody .= '<div style="margin-bottom: 10px;">
						    	  <p style="float: left;margin: 0;min-width: 230px;font-family: Arial, sans-serif, Suisse;color: #333;">
						    	    Employee No #
						    	  </p>
						    	  <p style="margin: 0;min-width: 230px;font-family: Arial, sans-serif, Suisse;color: #333;">
						    	    '.$userInfo->employee_number.'
						    	  </p>
						    	</div>';
							}

							if(isset($userObj->email) && $userObj->email != ''){
								$htmlBody .= '<div style="margin-bottom: 10px;">
							      <p style=" float: left;margin: 0;min-width: 230px;font-family: Arial, sans-serif, Suisse;color: #333;">
							        Email
							      </p>
							      <p style="margin: 0;min-width: 230px;font-family: Arial, sans-serif, Suisse;color: #333;">
							        '.$userObj->email.'
							      </p>
							    </div>';
							}else{
								$htmlBody .= '<div style="margin-bottom: 10px;">
								  <p style=" float: left;margin: 0;min-width: 230px;font-family: Arial, sans-serif, Suisse;color: #333;">
								    Phone
								  </p>
								  <p style="margin: 0;min-width: 230px;font-family: Arial, sans-serif, Suisse;color: #333;">
								    '.$userInfo->phone.'
								  </p>
								</div>';
							}

						    if (isset($companyName) && $companyName != '') {
						    	$htmlBody .= '<div style="margin-bottom: 10px;">
							      <p style="float: left;margin: 0;min-width: 230px;font-family: Arial, sans-serif, Suisse;color: #333;">
							        Company
							      </p>
							      <p style="margin: 0;min-width: 230px;font-family: Arial, sans-serif, Suisse;color: #333;">
							        '.$companyName.'
							      </p>
							    </div>';
							}

							if(isset($departmentName) && $departmentName != ''){
								$htmlBody .= '<div style="margin-bottom: 10px;">
							      <p style="float: left;margin: 0;min-width: 230px;font-family: Arial, sans-serif, Suisse;color: #333;">
							        Department
							      </p>
							      <p style="margin: 0;min-width: 230px;font-family: Arial, sans-serif, Suisse;color: #333;">
							        '.$departmentName.'
							      </p>
							    </div>';
							}
						  $htmlBody .= '</div>
						  <div style="max-width: 90%; margin: 60px auto 0;">
						    <h3 style="font-size: 18px;font-family: Arial, sans-serif, Suisse;margin-bottom: 25px;">
						      Questions
						    </h3>';

						    $htmlBody .= '<div style="margin-bottom: 10px;">
											<p style="float: left;margin: 0;min-width: 240px;font-family: Arial, sans-serif, Suisse;color: #333; margin-right: 10px;">
												What can we help you with today?
											</p>
											<p style="margin: 0;min-width: 240px;font-family: Arial, sans-serif, Suisse;color: #333;">';
										  		if(!empty($checkInObj->s1_question)){
											      	$htmlBody .= $checkInObj->s1_question;
												}else{
												   	$htmlBody .= '';
												}
							$htmlBody .='</p></div>';

						    foreach ($questions as $key => $question) {
						    	$htmlBody .='<div style="margin-bottom: 10px;">
							      <p style="float: left;margin: 0;min-width: 240px;font-family: Arial, sans-serif, Suisse;color: #333;">
							        '.$question->title.'
							      </p>
							      <p style="margin: 0;min-width: 240px;font-family: Arial, sans-serif, Suisse;color: #333;">';
							      	if($question->pivot->option == 1){
							      		$htmlBody .='Yes';
								    }else{
								    	$htmlBody .='No';
								    }
							    $htmlBody .=' </p>
							    </div>';
							}

							if (isset($checkInObj->additional_help) && !is_null($checkInObj->additional_help)) {
								$htmlBody .= '<div style="margin-bottom: 10px;">
											<p style="float: left;margin: 0;min-width: 240px;font-family: Arial, sans-serif, Suisse;color: #333; margin-right: 10px;">
												Do you want someone to contact you?
											</p>
											<p style="margin: 0;min-width: 240px;font-family: Arial, sans-serif, Suisse;color: #333;">';
										  		if($checkInObj->additional_help == 1){
											      	$htmlBody .='Yes';
												}else{
												   	$htmlBody .='No';
												}
								$htmlBody .='</p>
									</div>';
							}

						  if (isset($checkInObj->additional_feedback) && $checkInObj->additional_feedback != '') {
						  	$htmlBody .= '<div style="max-width: 90%;">
							    <h3 style="font-size: 18px;font-family: Arial, sans-serif, Suisse;margin-bottom: 25px;">
							      Additional feedback
							    </h3>
							    <div style="margin-bottom: 10px;">
							      <p style="margin: 0;min-width: 240px;font-family: Arial, sans-serif, Suisse;color: #333;color: #333;">
							        '.$checkInObj->additional_feedback.'
							      </p>
							    </div>
							  </div>';
						  }
						$htmlBody .= '</div>
							<div style="width:100%; height: 40px;background: #015289; margin-top: 50px;">
							</div>
					  </div>
					</body>
				</html>';

		return $htmlBody;
	}
}
