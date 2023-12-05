<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([ 'prefix' => 'auth'], function (){ 
    Route::group(['namespace' => 'API','middleware' => ['guest:api']], function () {
        Route::post('login', 'AuthController@login');
        Route::post('registartion', 'AuthController@registration');
        Route::post('password/forgot', 'AuthController@forgotPassword');
    });
});

Route::group([ 'prefix' => 'guest'], function (){ 
    Route::group(['namespace' => 'API', 'middleware' => ['guest:api']], function () {
        Route::post('check-email', 'UserController@checkEmail');
        Route::post('check-phone', 'UserController@checkPhone');

        /*Reset password*/
        Route::post('user/send-verification-code-by-phone', 'UserController@sendVerificationCodebyPhone');
        Route::post('user/verify-by-user-id', 'UserController@verifyByUserId');
        Route::post('user/reset-password-by-user-id', 'UserController@restPasswordByUserId');
        
        Route::get('countries', 'HomeController@countries');
        Route::get('locations', 'HomeController@locationsByCountry');
        Route::get('companies', 'HomeController@companiesByCountry');
        Route::get('departments', 'HomeController@departmentsByCompany');
        Route::get('branches', 'HomeController@brancesByCompany');
        Route::get('questions', 'HomeController@questions');
        Route::get('s1-options', 'HomeController@s1Options');

        Route::get('settings', 'AppSettingController@index');
    });
});

Route::group(['middleware' => 'auth:api', 'namespace'=> 'API'], function() {
    
    Route::post('user/verification', 'UserController@verifyUser');
    Route::post('user/resend/verification', 'UserController@resendVerificationCode');
    Route::post('user/delete', 'UserController@delete');

    Route::group(['prefix' => 'user/profile'], function(){
        Route::get('/', 'UserController@profile');
        Route::post('image/upload', 'UserController@uploadImage');
        Route::post('update', 'UserController@updateProfile');
        // Route::post('image/update', 'UserController@updateProfileImage');
        Route::post('password/update', 'UserController@updatePassword');
    });
    
    Route::get('points', 'HealthCenterController@healthCenters');

    Route::group(['prefix' => 'checkin'], function(){
        Route::get('/', 'CheckinController@index');
        Route::post('/create', 'CheckinController@store');
    });

    Route::group(['prefix' => 'notifications'], function(){
        Route::get('/', 'NotificationController@index');
        Route::post('update', 'NotificationController@update');
        Route::get('unreadcount', 'NotificationController@unreadCount');
        Route::post('delete', 'NotificationController@delete');
        Route::post('delete-all', 'NotificationController@deleteAll');
    });

    Route::get('news/massy-employee', 'NewsController@massyEmpNews');
    Route::get('news/general', 'NewsController@generalNews');

    Route::get('resources/massy-employee', 'ResourceController@massyEmpResources');
    Route::get('resources/general', 'ResourceController@generalUserResources');
});

Route::fallback(function(){
  return response()->json(['errors'=> ['message'=>'Page Not Found. If error persists, contact info@website.com']],404);
});
