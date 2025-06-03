<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAnswer;
use App\Models\Answer;
use App\Models\Result;
use App\Models\LearningStyle;

class UserAnswerController extends Controller
{
    public function store(Request $request)
    {
        try {
            $userAnswers = $request->all();
            $userId = $userAnswers[0]['user_id'];

            // Hapus dulu semua jawaban sebelumnya
            UserAnswer::where('user_id', $userId)->delete();

            // Insert/update jawaban baru
            foreach ($userAnswers as $answer) {
                $answerModel = Answer::find($answer['answer_id']);

                $skorVisual = 0;
                $skorAuditory = 0;
                $skorKinestetik = 0;

                if ($answerModel) {
                    if ($answerModel->learning_type === 'visual') $skorVisual = 1;
                    if ($answerModel->learning_type === 'auditory') $skorAuditory = 1;
                    if ($answerModel->learning_type === 'kinesthetic') $skorKinestetik = 1;
                }

                UserAnswer::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'question_id' => $answer['question_id'],
                    ],
                    [
                        'answer_id' => $answer['answer_id'],
                        'skor_visual' => $skorVisual,
                        'skor_auditory' => $skorAuditory,
                        'skor_kinestetik' => $skorKinestetik,
                    ]
                );
            }

            // Hitung total skor setelah input jawaban
            $totals = UserAnswer::where('user_id', $userId)
                ->selectRaw('SUM(skor_visual) as total_visual, SUM(skor_auditory) as total_auditory, SUM(skor_kinestetik) as total_kinestetik')
                ->first();

            // Tentukan gaya belajar
            $style = 'Visual';
            $max = $totals->total_visual;

            if ($totals->total_auditory > $max) {
                $style = 'Auditory';
                $max = $totals->total_auditory;
            }

            if ($totals->total_kinestetik > $max) {
                $style = 'Kinestetik';
            }

            $styleData = LearningStyle::where('gaya_belajar', $style)->first();

            Result::create([
                'user_id' => $userId,
                'style_id' => $styleData->style_id,
                'total_skor_visual' => $totals->total_visual,
                'total_skor_auditory' => $totals->total_auditory,
                'total_skor_kinestetik' => $totals->total_kinestetik,
            ]);

            return response()->json([
                'message' => 'Jawaban dan hasil berhasil disimpan',
                'result' => $style,
                'rekomendasi' => $styleData->rekomendasi,
                'skor' => $totals,
                'persentase' => [
                    'visual' => number_format(($totals->total_visual / 20) * 100, 0) . '%', // bulatkan
                    'auditory' => number_format(($totals->total_auditory / 20) * 100, 0) . '%',
                    'kinestetik' => number_format(($totals->total_kinestetik / 20) * 100, 0) . '%',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
