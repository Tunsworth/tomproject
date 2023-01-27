<?php

namespace App\Http\Requests;

use App\Rules\BetweenThreeAndFive;
use App\Rules\OneCorrect;
use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required'],
            'description' => ['required'],
            'category_id' => ['required'],
            'questions.*.answers' =>['required','array', 'between:2,4'],
            'questions.*.question' => ['required'],
            'questions.*.answers.*.answer' => ['required'],
            'questions.*.answers' => ['required', new BetweenThreeAndFive, new OneCorrect],
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'questions.*.answers.*.correct_answer' => 'At least one answer must be marked as true',
            'questions.*.question' => 'Question is required',
            'questions.*.answers.*.answer' => 'Answer is required', 
        ];
    }
}
