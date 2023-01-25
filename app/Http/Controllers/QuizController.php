<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Rules\OneCorrect;

class QuizController extends Controller
{

    public function __construct() {
    
        $this->middleware('role:edit')->only('store', 'edit', 'update', 'destroy');
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = Quiz::with('Category:id,title')->get();
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
      
        Validator::make($request->all(), [
            'title' => ['required'],
            'description' => ['required'],
            'category_id' => ['required'],
            'questions' =>['required'],
            'questions.*.question' => ['required'],
            'questions.*.answers.*.answer' => ['required'],
            'questions.*.answers' => ['required', new OneCorrect],
            // 'questions.*.answers.*.correct_answer' => ['required', new OneCorrect],
            // 'questions.*.answers.*.correct_answer' => ['required_if:questions.*.answers.*.correct_answer,true'],
        ],[
            'questions.*.answers.*.correct_answer' => 'At least one answer must be marked as true',
            'questions.*.question' => 'Question is required',
            'questions.*.answers.*.answer' => 'Answer is required',            
        ]
        )->validate();

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
            'quiz' => $quiz->formatQuiz() 
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

        Validator::make($request->all(), [
            'title' => ['required'],
            'description' => ['required'],
            'category_id' => ['required'],
            'questions.*.question' => ['required'],
            'questions.*.answers.*.answer' => ['required'],
            'questions.*.answers.*.correct_answer' => ['required'],
        ],[
            'questions.*.question' => 'Question is required',
            'questions.*.answers.*.answer' => 'Answer is required',            
        ]
        )->validate();

        $data = $request->all();

        $quiz = Quiz::updateOrCreate(
            [ 'id' => $id ],
            [
            'title' => $data['title'],
            'description' => $data['description'],
            'category_id' => $data['category_id'],
            ],
        );

        collect($data['questions'])->map(function ($question) use ($quiz) {
            $question_record = $quiz->questions()->create($question);
            // dd($question['answers']);
            $question_record->answers()->createMany($question['answers']);
        });
        // Quiz::find($id)->update($request->all());






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
