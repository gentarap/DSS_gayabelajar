<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $primaryKey = 'question_id';
    protected $fillable = ['question_text', 'status'];

    //untuk debugging
    protected $with = ['answers'];

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'question_id');
    }
}
