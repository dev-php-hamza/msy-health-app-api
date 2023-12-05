<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\NotificationUser;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
    	$user = auth()->user();

    	$unreadNotifications = $user->unreadNotifications()->latest()->get();
    	$readNotifications   = $user->readNotifications()->latest()->get();

    	$unread = $this->extratNotifyData($unreadNotifications);
    	$data['new_count'] = count($unread);
    	$data['unread']    = $unread;
    	$data['read']      = $this->extratNotifyData($readNotifications);

    	return response()->json(ApiResponse::success($data));
    }

    public function update(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    	  'id' => 'required|numeric',
    	]);

    	if ($validator->fails()) {
    		return response()->json(ApiResponse::validation($validator->errors()));
    	}

    	$user = auth()->user();
    	$userNotification = NotificationUser::whereNotificationId($request->id)->whereUserId($user->id)->first();
    	if (count($userNotification)>0) {
    		$userNotification->read = 1;
    		$userNotification->save();
    		return response()->json(ApiResponse::success());			
    	}
    	return response()->json(ApiResponse::error('UnprocessableEntity', 'Notification is not found!'));
    }

    public function unreadCount(Request $request)
    {
    	$data = array();
    	$user = auth()->user();

    	$unreadNotifications = $user->unreadNotifications()->get();
    	$data['new_count']   = count($unreadNotifications)?:0;
    	return response()->json(ApiResponse::success($data));
    }

    public function extratNotifyData($notifications)
    {
    	$tempData = array();
    	foreach ($notifications as $key => $notification) {
    		$temp['id'] 		 = $notification->id;
    		$temp['title'] 	     = $notification->title;
    		$temp['description'] = $notification->description;
    		$temp['created_at']  = $notification->created_at->toDateTimeString();
    		array_push($tempData, $temp);
    	}

    	return $tempData;
    }

    public function delete(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    	  'id' => 'required|numeric',
    	]);

    	if ($validator->fails()) {
    		return response()->json(ApiResponse::validation($validator->errors()));
    	}

    	$user = auth()->user();
    	$userNotification = NotificationUser::whereUserId($user->id)->whereNotificationId($request->id)->first();
    	if (count($userNotification)>0) {
    		$userNotification->delete();
    		return response()->json(ApiResponse::success());			
    	}
    	return response()->json(ApiResponse::error('UnprocessableEntity', 'Notification is not found!'));
    }

    public function deleteAll()
    {
    	$user = auth()->user();
    	$userNotification = NotificationUser::whereUserId($user->id)->delete();
    	return response()->json(ApiResponse::success());
    }

}
