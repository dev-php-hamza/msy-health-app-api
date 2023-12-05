<?php

namespace App\Http\Controllers\Admin;

use App\Models\S1Option;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class S1OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page  = config('setting.pagination.per_page');
        $s1Options = S1Option::latest()->paginate($per_page);
        return view('admin.s1Options.index',compact('s1Options'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.s1Options.create');
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
            'lang'   => 'required|string|max:2',
            'option' => 'required|string|max:255', 
        );

        $this->validate($request, $rules);

        S1Option::create([
            'option' => $request->option,
            'lang'   => $request->lang
        ]);
        
        return redirect()->route('s1-options.index')->with('message','Option has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\S1Option  $s1Option
     * @return \Illuminate\Http\Response
     */
    public function show(S1Option $s1Option)
    {
        return view('admin.s1Options.detail',compact('s1Option'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\S1Option  $s1Option
     * @return \Illuminate\Http\Response
     */
    public function edit(S1Option $s1Option)
    {
        return view('admin.s1Options.edit',compact('s1Option'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\S1Option  $s1Option
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, S1Option $s1Option)
    {
        $rules = array(
            'lang'   => 'required|string|max:2',
            'option' => 'required|string|max:255|unique:s1_options,option,'.$s1Option->id.',id', 
        );

        $this->validate($request, $rules);
        $s1Option->lang   = $request->lang;
        $s1Option->option = $request->option;
        $s1Option->save();
        
        return redirect()->route('s1-options.index')->with('message','Option has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\S1Option  $s1Option
     * @return \Illuminate\Http\Response
     */
    public function destroy(S1Option $s1Option)
    {
        $s1Option->delete();
        return redirect()->route('s1-options.index')->with('message', 'Option has been deleted successfully');
    }

    public function search(Request $request)
    {
        $rules = array(
            'term' => 'required|string|max:255',
        );

        $this->validate($request, $rules);

        $per_page  = config('setting.pagination.per_page');

        $term      = ucfirst(strtolower($request->term));
        $s1Options = S1Option::where('option', 'LIKE','%'.$term.'%')
                            ->orderBy('option')
                            ->paginate($per_page);

        return view('admin.s1Options.index',compact('s1Options'));
    }
}
