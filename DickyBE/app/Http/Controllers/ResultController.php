<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAnswer;
use App\Models\Result;
use App\Models\LearningStyle;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function userHistory($userId)
    {
        $results = Result::with('style')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($result) {
                return [
                    'id' => $result->id,
                    'created_at' => $result->created_at,
                    'style' => [
                        'gaya_belajar' => $result->style->gaya_belajar,
                        'rekomendasi' => $result->style->rekomendasi,
                    ],
                    'persentase' => [
                        'visual' => number_format(($result->total_skor_visual / 20) * 100, 0),
                        'auditory' => number_format(($result->total_skor_auditory / 20) * 100, 0),
                        'kinestetik' => number_format(($result->total_skor_kinestetik / 20) * 100, 0),
                    ]
                ];
            });

        return response()->json($results);
    }
}
