<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $question = new Question;
        $edit = FALSE;
        return view('questionForm', ['question' => $question,'edit' => $edit  ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'title' => 'required|max:40',
            'body' => 'required|min:5',
            'image' => 'image|nullable|max:2084'
        ], [
            'title.required' => 'Title is required to make a question.',
            'title.min' => 'Titles length can only be 40 characters',
            'body.required' => 'Body is required',
            'body.min' => 'Body must be at least 5 characters',
        ]);

        if($request->hasFile('image')){
            $fileNameExtension = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($fileNameExtension, PATHINFO_FILENAME);
            $fileExtension = $request->file('image')->getClientOriginalExtension();
            $fileNameStore = $fileName.'_'.time().'.'.$fileExtension;
            $path = $request->file('image')->storeAs('public/user_images', $fileNameStore);
        } else{
            $fileNameStore = 'noimg.jpg';
        }



        $input = request()->all();
        $question = new Question($input);
        if($path){
            $question->image = $path;
        }
        $question->user()->associate(Auth::user());
        $question->save();
        return redirect()->route('home')->with('message', 'IT WORKS!');
        // return redirect()->route('questions.show', ['id' => $question->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return view('question')->with('question', $question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        $edit = TRUE;
        return view('questionForm', ['question' => $question, 'edit' => $edit ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $input = $request->validate([
            'title' => 'required|max:40',
            'body' => 'required|min:5',
            'image' => 'image|nullable|max:2084'
        ], [
            'title.required' => 'Title is required to make a question.',
            'title.min' => 'Titles length can only be 40 characters',
            'body.required' => 'Body is required',
            'body.min' => 'Body must be at least 5 characters',
        ]);
        $question->body = $request->body;
        $question->save();
        return redirect()->route('questions.show',['question_id' => $question->id])->with('message', 'Saved');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('home')->with('message', 'Deleted');
    }
}
