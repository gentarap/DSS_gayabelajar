<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningStyle extends Model
{
    protected $primaryKey = 'style_id';
    protected $fillable = [
        'gaya_belajar',
        'deskripsi',
        'rekomendasi'
    ];

    public function results()
    {
        return $this->hasMany(Result::class, 'style_id', 'style_id');
    }
}
