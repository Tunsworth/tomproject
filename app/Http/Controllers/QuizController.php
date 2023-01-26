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
            ['title' => ucfirst($data['title']),'description' => ucfirst($data['description']),'category_id' => $data['category_id']],
        );
         $questions = collect($data['questions'])->map(function ($question) use ($quiz){
            if(array_key_exists('id', $question)){
                return['id' => $question['id'], 'quiz_id' => $quiz->id ,'question' => $question['question']];
            }else{
                $newInsert = Question::updateOrCreate(
                    ['question' => $question['question'],
                    'quiz_id' => $quiz['id']]
                );
                $newInsert->answers()->createMany($question['answers']);;
                return ['id' => $newInsert->id, 'quiz_id' => $quiz['id'] ,'question' => $newInsert->question];
            }
        })->toArray();

        $removeQuestion = collect($questions)->map(function ($item){
            if(array_key_exists('id', $item)){
                return  $item['id'];
            }
        })->filter()->toArray();

        $questionsToRemove = Question::where('quiz_id', $quiz->id)->whereNotIn('id',$removeQuestion)->get();
        if($questionsToRemove != null){
            $questionsToRemove->map(function ($question) {
                Answer::whereIn('id', $question->answers->pluck('id'))->delete();
            });
                Question::whereIn('id', $questionsToRemove->pluck('id'))->delete();
        }

        $answers = collect($data['questions'])->flatMap(function ($question){
            if(array_key_exists('id', $question)){
            //remove answers if they have not been passed    
            $remove = collect($question['answers'])->map(function ($answer){
                if(array_key_exists('id', $answer)){
                    return $answer['id'];
                }
            })->filter()->toArray();
           if($remove != null){
                Answer::where('question_id', $question['id'])->whereNotIn('id', $remove)->delete();
           }
          
           $newAnwsers = collect($question['answers'])->map(function ($answer) use ($question){
            if(array_key_exists('id', $answer) == false){
               $newInsert = Answer::updateOrCreate(
                    ['answer' => $answer['answer'],
                    'question_id' => $question['id']]
                );
            } else {
                return ['id' => $answer['id'], 'question_id' => $answer['question_id'], 'answer' => $answer['answer'], 'correct_answer' => $answer['correct_answer']];
            }
            });
            return $newAnwsers; 
        }
        })->filter()->toArray();
        $quiz->questions()->upsert($questions, ['id'],['question']);
        Answer::upsert($answers, ['id'],['answer', 'correct_answer']);
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
