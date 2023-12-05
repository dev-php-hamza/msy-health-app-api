<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Admin\AdminController@index');

Auth::routes();

Route::namespace('Admin')
        ->prefix('admin')
        ->group(function() {
	Route::get('/', 'AdminController@index')->name('home');

    Route::get('users/search', 'UserController@search')->name('users.search');
    Route::resource('users', 'UserController');
    
    Route::get('checkins/search', 'CheckinController@search')->name('checkins.search');
    Route::resource('checkins', 'CheckinController');

    Route::get('countries/search', 'CountryController@search')->name('countries.search');
    Route::post('countries/status', 'CountryController@updateCountrySwitch');
    Route::resource('countries', 'CountryController');

    Route::post('companies/import', 'CompanyController@saveImport')->name('companies.import.save');
    Route::get('companies/country/{id}', 'CompanyController@companiesByCountry')->name('companies.by-country');
    Route::get('companies/search', 'CompanyController@search')->name('companies.search');
    Route::resource('companies', 'CompanyController');

    Route::get('branches/search', 'BranchController@search')->name('branches.search');
    Route::resource('branches', 'BranchController');

    Route::get('departments/search', 'DepartmentController@search')->name('departments.search');
    Route::get('departments/company/{id}', 'DepartmentController@departmentsByCompany')->name('departments.by-company');
    Route::resource('departments', 'DepartmentController');

    Route::get('news/search', 'NewsController@search')->name('news.search');
    Route::resource('news', 'NewsController');

    Route::get('resources/search', 'ResourceController@search')->name('resources.search');
    Route::resource('resources', 'ResourceController');
    

    Route::get('notifications/search', 'NotificationController@search')->name('notifications.search');
    Route::get('notifications/choose-user/{id}','NotificationController@choose_user')->name('notifications.choose_user');
    Route::get('notifications/country-users/{id}','NotificationController@getUsersbyCountry')->name('notifications.country-users');
    Route::post('notifications/save', 'NotificationController@saveNotificationUsers')->name('notifications.save.users');
     Route::post('notifications/users-search/', 'NotificationController@getUsersByCountryAndTerm')->name('notifications_users_country_term');
    Route::resource('notifications', 'NotificationController');


    Route::get('locations/search', 'LocationController@search')->name('locations.search');
    Route::resource('locations', 'LocationController');
    Route::prefix('locations')->group(function() {
    	Route::get('/country/{id}', 'LocationController@locationByCountry');
    	Route::get('/country-companies/{id}', 'LocationController@getCompAndLoc');
    });

    Route::get('health-centers/search', 'HealthCenterController@search')->name('health-centers.search');
    Route::resource('health-centers', 'HealthCenterController');

    Route::get('questions/search', 'QuestionController@search')->name('questions.search');
    Route::resource('questions', 'QuestionController');

    Route::get('s1-options/search', 'S1OptionController@search')->name('s1-options.search');
    Route::resource('s1-options', 'S1OptionController');

    // Route::get('settings/edit', 'AppSettingController@edit')->name('settings.edit');
    // Route::post('settings/update', 'AppSettingController@update')->name('settings.update');
    Route::resource('settings', 'AppSettingController');
});