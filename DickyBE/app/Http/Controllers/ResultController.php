<?php

namespace App\Http\Controllers;

use App\Models\UserAnswer;
use App\Models\Result;
use App\Models\LearningStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function store(Request $request)
    {
        $userId = $request->user_id;

        $scores = DB::table('user_answers')
            ->where('user_id', $userId)
            ->select(
                DB::raw('SUM(skor_visual) as total_visual'),
                DB::raw('SUM(skor_auditory) as total_auditory'),
                DB::raw('SUM(skor_kinestetik) as total_kinestetik')
            )->first();

        $style = 'Visual';
        $maxScore = $scores->total_visual;

        if ($scores->total_auditory > $maxScore) {
            $style = 'Auditory';
            $maxScore = $scores->total_auditory;
        }

        if ($scores->total_kinestetik > $maxScore) {
            $style = 'Kinestetik';
        }

        $styleData = LearningStyle::where('gaya_belajar', $style)->first();

        $result = Result::updateOrCreate(
            ['user_id' => $userId],
            [
                'style_id' => $styleData->style_id,
                'total_skor_visual' => $scores->total_visual,
                'total_skor_auditory' => $scores->total_auditory,
                'total_skor_kinestetik' => $scores->total_kinestetik,
            ]
        );

        return response()->json([
            'message' => 'Hasil disimpan',
            'result' => $style,
            'rekomendasi' => $styleData->rekomendasi,
        ]);
    }
}
