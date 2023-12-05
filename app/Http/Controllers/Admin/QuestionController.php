<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page  = config('setting.pagination.per_page');
        $questions = Question::latest()->paginate($per_page);
        return view('admin.question.index',compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.question.create');
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
            'title' => 'required|string|max:255', 
            'lang'        => 'required|string', 
        );

        $this->validate($request, $rules);
        $questions = Question::create([
            'title' => $request->title,
            'lang' => $request->lang
        ]);
        
        return redirect()->route('questions.index')->with('message','Question has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return view('admin.question.detail',compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        return view('admin.question.edit',compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $rules = array(
            'lang'  => 'required|string',
            'title' => 'required|string|max:255|unique:questions,title,'.$question->id.',id', 
        );

        $this->validate($request, $rules);
        $question->lang = $request->lang;
        $question->title = $request->title;
        $question->save();
        
        return redirect()->route('questions.index')->with('message','Question has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('questions.index')->with('message', 'Question has been deleted successfully');
    }

    public function search(Request $request)
    {
        $rules = array(
            'term' => 'required|string|max:255',
        );

        $this->validate($request, $rules);

        $per_page  = config('setting.pagination.per_page');

        $term      = ucfirst(strtolower($request->term));
        $questions = Question::where('title', 'LIKE','%'.$term.'%')
        ->orderBy('title')
        ->paginate($per_page);

        return view('admin.question.index',compact('questions'));
    }
}
