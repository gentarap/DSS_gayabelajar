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
                UserAnswer::create([
                    'user_id' => $answer['user_id'],
                    'question_id' => $answer['question_id'],
                    'answer_id' => $answer['answer_id'],
                    'skor_visual' => $answer['skor_visual'],
                    'skor_auditory' => $answer['skor_auditory'],
                    'skor_kinestetik' => $answer['skor_kinestetik'],
                ]);
            }

            return response()->json([
                'message' => 'User answers saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save user answers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
