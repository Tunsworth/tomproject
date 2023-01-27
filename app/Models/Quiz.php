<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'title' , 'description', 'category_id'
    // ];
    protected $guarded = [];

    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function answers(){
        return $this->hasManyThrough(Answer::class ,Question::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function formatQuiz(){
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category()->first(),
            'questions' => $this->questions->map(function ($item){
                 return [ 'id' => $item->id, 'answers' => $item->answers, 'question' => $item->question];
            }),
        ];
    }

}
