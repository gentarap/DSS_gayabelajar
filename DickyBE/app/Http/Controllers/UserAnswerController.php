<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAnswer;

class UserAnswerController extends Controller
{
    public function store(Request $request)
{
    try {
        $userAnswers = $request->all();

        foreach ($userAnswers as $answer) {
            // Ambil data answer dari database untuk mendapatkan learning type
            $answerData = Answer::find($answer['answer_id']);
            
            if (!$answerData) {
                throw new Exception("Answer not found for ID: " . $answer['answer_id']);
            }

            // Tentukan skor berdasarkan learning type
            $skor_visual = 0;
            $skor_auditory = 0;
            $skor_kinestetik = 0;

            // Asumsikan field 'learning_type' ada di table answers
            // atau Anda bisa menggunakan field lain yang menandakan tipe pembelajaran
            switch (strtolower($answerData->learning_type)) {
                case 'visual':
                    $skor_visual = 1;
                    break;
                case 'auditory':
                    $skor_auditory = 1;
                    break;
                case 'kinestetik':
                case 'kinesthetic':
                    $skor_kinestetik = 1;
                    break;
                default:
                    // Jika tidak ada learning type yang jelas, bisa set default atau error
                    break;
            }

            UserAnswer::create([
                'user_id' => $answer['user_id'],
                'question_id' => $answer['question_id'],
                'answer_id' => $answer['answer_id'],
                'skor_visual' => $skor_visual,
                'skor_auditory' => $skor_auditory,
                'skor_kinestetik' => $skor_kinestetik,
            ]);
        }

        return response()->json([
            'message' => 'User answers saved successfully',
            'status' => 'success'
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'message' => 'Failed to save user answers',
            'error' => $e->getMessage()
        ], 500);
    }
}

// ALTERNATIF: Jika Anda ingin mapping berdasarkan answer_id
public function storeAlternative(Request $request)
{
    try {
        $userAnswers = $request->all();

        // Mapping answer_id ke learning type (sesuaikan dengan database Anda)
        $learningTypeMap = [
            1 => 'visual',      // answer_id 1 = visual
            2 => 'auditory',    // answer_id 2 = auditory  
            3 => 'kinestetik',  // answer_id 3 = kinestetik
            4 => 'visual',      // answer_id 4 = visual
            5 => 'kinestetik',  // answer_id 5 = kinestetik
            // ... tambahkan mapping sesuai data Anda
        ];

        foreach ($userAnswers as $answer) {
            $answerId = $answer['answer_id'];
            $learningType = $learningTypeMap[$answerId] ?? null;

            if (!$learningType) {
                throw new Exception("Learning type not found for answer_id: " . $answerId);
            }

            // Set skor berdasarkan learning type
            $skor_visual = ($learningType === 'visual') ? 1 : 0;
            $skor_auditory = ($learningType === 'auditory') ? 1 : 0;
            $skor_kinestetik = ($learningType === 'kinestetik') ? 1 : 0;

            UserAnswer::create([
                'user_id' => $answer['user_id'],
                'question_id' => $answer['question_id'],
                'answer_id' => $answer['answer_id'],
                'skor_visual' => $skor_visual,
                'skor_auditory' => $skor_auditory,
                'skor_kinestetik' => $skor_kinestetik,
            ]);
        }

        return response()->json([
            'message' => 'User answers saved successfully',
            'status' => 'success'
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'message' => 'Failed to save user answers',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
