<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'user_id',
        'style_id',
        'total_skor_visual',
        'total_skor_auditory',
        'total_skor_kinestetik'
    ];

    public function style()
    {
        return $this->belongsTo(LearningStyle::class, 'style_id', 'style_id');
    }
}
