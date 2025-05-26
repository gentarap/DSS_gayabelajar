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
}
