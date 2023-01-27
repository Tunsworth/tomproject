<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question' , 'quiz_id', 'answer_id'
    ];

    public function quizzes(){
        return $this->belongsTo(Quiz::class);
    }

    public function answers(){
        return $this->hasMany(Answer::class);
    }

    public function correctAnswer(){
        return $this->hasOne(Answer::class)->where('id' == $this->answer_id);
    }

    public function formatQuestion(){
        return [ 'answers' => $this->answers, 'question' => $this->question];
    }

}
