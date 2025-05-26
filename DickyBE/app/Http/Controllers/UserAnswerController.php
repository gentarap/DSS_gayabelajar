<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\DB;

class UserAnswerController extends Controller
{
    /**
     * Generate session ID untuk test baru
     */
    public function generateSession(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $sessionId = 'test_' . $request->user_id . '_' . time() . '_' . uniqid();

        return response()->json([
            'message' => 'Session ID generated successfully',
            'session_id' => $sessionId,
            'user_id' => $request->user_id,
            'started_at' => now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Simpan jawaban user untuk sesi test tertentu
     */
    public function store(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'answers' => 'required|array',
            'answers.*.user_id' => 'required|integer',
            'answers.*.question_id' => 'required|integer',
            'answers.*.answer_id' => 'required|integer',
            'answers.*.skor_visual' => 'required|integer|min:0',
            'answers.*.skor_auditory' => 'required|integer|min:0',
            'answers.*.skor_kinestetik' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $sessionId = $request->session_id;
            $userAnswers = $request->answers;

            // Cek apakah ada jawaban untuk session ini sebelumnya
            $existingAnswers = UserAnswer::where('session_id', $sessionId)->count();

            if ($existingAnswers > 0) {
                return response()->json([
                    'message' => 'Session sudah memiliki jawaban. Gunakan session ID baru untuk test ulang.',
                    'existing_answers' => $existingAnswers
                ], 400);
            }

            // Simpan semua jawaban untuk session ini
            foreach ($userAnswers as $answer) {
                UserAnswer::create([
                    'user_id' => $answer['user_id'],
                    'session_id' => $sessionId,
                    'question_id' => $answer['question_id'],
                    'answer_id' => $answer['answer_id'],
                    'skor_visual' => $answer['skor_visual'],
                    'skor_auditory' => $answer['skor_auditory'],
                    'skor_kinestetik' => $answer['skor_kinestetik'],
                ]);
            }

            DB::commit();

            // Hitung total jawaban untuk validasi
            $totalAnswers = count($userAnswers);
            $userId = $userAnswers[0]['user_id'];

            return response()->json([
                'message' => 'User answers saved successfully',
                'session_id' => $sessionId,
                'total_answers' => $totalAnswers,
                'user_id' => $userId,
                'next_step' => 'Call /api/results to calculate learning style'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to save user answers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cek progress test untuk session tertentu
     */
    public function getProgress(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'total_questions' => 'integer|min:1'
        ]);

        $sessionId = $request->session_id;
        $totalQuestions = $request->get('total_questions', 20);

        $answeredCount = UserAnswer::where('session_id', $sessionId)->count();
        $isComplete = $answeredCount >= $totalQuestions;

        return response()->json([
            'session_id' => $sessionId,
            'answered_questions' => $answeredCount,
            'total_questions' => $totalQuestions,
            'progress_percentage' => round(($answeredCount / $totalQuestions) * 100, 1),
            'is_complete' => $isComplete,
            'remaining_questions' => max(0, $totalQuestions - $answeredCount)
        ]);
    }

    /**
     * Ambil jawaban untuk session tertentu
     */
    public function getSessionAnswers(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string'
        ]);

        $answers = UserAnswer::where('session_id', $request->session_id)
            ->with(['question', 'selectedAnswer'])
            ->orderBy('question_id')
            ->get();

        return response()->json([
            'session_id' => $request->session_id,
            'total_answers' => $answers->count(),
            'answers' => $answers->map(function ($answer) {
                return [
                    'question_id' => $answer->question_id,
                    'question_text' => $answer->question->question_text ?? null,
                    'answer_id' => $answer->answer_id,
                    'answer_text' => $answer->selectedAnswer->answer_text ?? null,
                    'scores' => [
                        'visual' => $answer->skor_visual,
                        'auditory' => $answer->skor_auditory,
                        'kinestetik' => $answer->skor_kinestetik
                    ]
                ];
            })
        ]);
    }

    /**
     * Hapus session yang tidak lengkap (cleanup)
     */
    public function cleanupSessions(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'hours_old' => 'integer|min:1'
        ]);

        $userId = $request->user_id;
        $hoursOld = $request->get('hours_old', 24);
        $cutoffTime = now()->subHours($hoursOld);

        // Cari session yang tidak lengkap (< 20 jawaban) dan sudah lama
        $incompleteSessions = UserAnswer::select('session_id')
            ->selectRaw('COUNT(*) as answer_count')
            ->where('user_id', $userId)
            ->where('created_at', '<', $cutoffTime)
            ->groupBy('session_id')
            ->having('answer_count', '<', 20)
            ->pluck('session_id');

        if ($incompleteSessions->isEmpty()) {
            return response()->json([
                'message' => 'No incomplete sessions to clean up',
                'deleted_sessions' => 0
            ]);
        }

        // Hapus jawaban dari session yang tidak lengkap
        $deletedCount = UserAnswer::whereIn('session_id', $incompleteSessions)->delete();

        return response()->json([
            'message' => 'Incomplete sessions cleaned up successfully',
            'deleted_sessions' => $incompleteSessions->count(),
            'deleted_answers' => $deletedCount
        ]);
    }
}
