<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $fillable = [
        'user_id',
        'question_id',
        'answer_id',
        'skor_visual',
        'skor_auditory',
        'skor_kinestetik'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answer_id', 'answer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
