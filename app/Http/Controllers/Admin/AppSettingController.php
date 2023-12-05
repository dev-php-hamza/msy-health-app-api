<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page  = config('setting.pagination.per_page');
        $settings = AppSetting::orderBy('intro_text')->paginate($per_page);
        return view('admin.setting.index',compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.setting.create');
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
            'intro_text'       => 'required|string',
            'allowed_domains'  => 'required|string',
            'lang'             => 'required|string|max:2'
        );

        $this->validate($request, $rules);

        $setting = AppSetting::create([
            'intro_text'      => $request->intro_text,
            'allowed_domains' => $request->allowed_domains,
            'lang'            => $request->lang
        ]);
        
        return redirect()->route('settings.index')->with('message','App Setting has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AppSetting  $appSetting
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $appSetting = AppSetting::whereId($id)->first();
        return view('admin.setting.detail',compact('appSetting'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AppSetting  $appSetting
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $setting = AppSetting::whereId($id)->first();
        return view('admin.setting.edit',compact('setting')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AppSetting  $appSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'intro_text'  => 'required|string',
            'allowed_domains'  => 'required|string',
            'lang' => 'required|string|max:2'
        );

        $this->validate($request, $rules);

        $appSetting = AppSetting::whereId($id)->first();
        $appSetting->lang = $request->lang;
        $appSetting->intro_text = $request->intro_text;
        $appSetting->allowed_domains = $request->allowed_domains;
        $appSetting->save();
        
        return redirect()->route('settings.index')->with('message','App Setting has been saved successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AppSetting  $appSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appSetting = AppSetting::whereId($id)->first();
        $appSetting->delete();
        return redirect()->route('settings.index')->with('message', 'App Setting has been deleted successfully');
    }
}
