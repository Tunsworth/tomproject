<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use App\Models\Quiz;
use App\Rules\BetweenThreeAndFive;
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
    public function store(QuizRequest $request)
    {
        $data = $request->all();

        $quiz = Quiz::firstOrCreate([
            'title' => ucfirst($data['title']),
            'description' => ucfirst($data['description']),
            'category_id' => $data['category_id']],
        );
    
        collect($data['questions'])->map(function ($question) use ($quiz) {
            $question_record = $quiz->questions()->create($question);
            $question_record->answers()->createMany($question['answers']);
        });   

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
    public function update(QuizRequest $request, $id)
    {
        $data = $request->all();

        $quiz = Quiz::updateOrCreate(
            [ 'id' => $id ],
            ['title' => $data['title'],'description' => $data['description'],'category_id' => $data['category_id']],
        );

        $questions = collect($data['questions'])->map(function ($question) use ($quiz){
            return['id' => $question['id'], 'quiz_id' => $quiz->id ,'question' => $question['question']];
        })->toArray();


        $answers = collect($data['questions'])->flatMap(function ($question){

            // $currentAnswers = Answer::where('question_id', $question['id'])->get();
           $newAnwsers = collect($question['answers'])->map(function ($answer) use ($question){
            // get the awnsers for that question 
            // if there is no array key we need to create a new record
            if(array_key_exists('id', $answer) == false){
                // dd($answer);
               $newInsert = Answer::updateOrCreate(
                    ['answer' => $answer['answer'],
                    'question_id' => $question['id']]
                );
            } else {
                return ['id' => $answer['id'], 'question_id' => $answer['question_id'], 'answer' => $answer['answer'], 'correct_answer' => $answer['correct_answer']];
            }
            });
            return $newAnwsers; 




        })->filter()->toArray();
        // dd($answers);
        $quiz->questions()->upsert($questions, ['id'],['question']);
        Answer::upsert($answers, ['id'],['answer', 'correct_answer']);

        //pass quiz updated allert back
        // return redirect()->route('quizzes.index');
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
