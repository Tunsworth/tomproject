<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = Quiz::with('Category:id,title')->get();
        // $categories = Category::all();
        // dd($categories);
        return Inertia::render('Quizzes/Index', ['quizzes' => $quizzes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Quizzes/Create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // add validation here
        $data = $request->all();

        $quiz = Quiz::firstOrCreate([
            'title' => $data['title'],
            'description' => $data['description'],
            'category_id' => $data['category_id']],
        );
        
        collect($data['questions'])->map(function ($question) use ($quiz) {
            $question_record = $quiz->questions()->create($question);
            // dd($question['answers']);
            $question_record->answers()->createMany($question['answers']);
        });

        // $questions = $quiz->questions()->createMany($data['questions']);

        // $answers = $questions;
    
        return redirect()->route('quizzes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Quiz $quiz)
    {
        return Inertia::render('Quizzes/Show', [
            'quiz' => $quiz 
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz)
    {
        $categories = Category::all();
        return Inertia::render('Quizzes/Edit', [
            'quiz' => $quiz->formatQuiz(),
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Quiz::find($id)->update($request->all());
        return redirect()->route('quizzes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Quiz::find($id)->delete();
        return redirect()->route('quizzes.index');
    }
}
