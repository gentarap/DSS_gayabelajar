<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = 'results';
    protected $primaryKey = 'result_id';

    protected $fillable = [
        'user_id',
        'session_id',
        'style_id',
        'test_number',
        'total_skor_visual',
        'total_skor_auditory',
        'total_skor_kinestetik'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function style()
    {
        return $this->belongsTo(LearningStyle::class, 'style_id', 'style_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'session_id', 'session_id');
    }

    // Accessors
    public function getTotalScoreAttribute()
    {
        return $this->total_skor_visual + $this->total_skor_auditory + $this->total_skor_kinestetik;
    }

    public function getScorePercentagesAttribute()
    {
        $total = $this->total_score;

        if ($total == 0) return ['visual' => 0, 'auditory' => 0, 'kinestetik' => 0];

        return [
            'visual' => round(($this->total_skor_visual / $total) * 100, 1),
            'auditory' => round(($this->total_skor_auditory / $total) * 100, 1),
            'kinestetik' => round(($this->total_skor_kinestetik / $total) * 100, 1)
        ];
    }

    public function getDominantStyleAttribute()
    {
        $scores = [
            'visual' => $this->total_skor_visual,
            'auditory' => $this->total_skor_auditory,
            'kinestetik' => $this->total_skor_kinestetik
        ];

        return array_search(max($scores), $scores);
    }

    // Scopes
    public function scopeLatestForUser($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->orderBy('test_number', 'desc');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
