<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\LearningStyle;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResultController extends Controller
{
    /**
     * Start new quiz session for user
     */
    public function startQuiz(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $userId = $request->user_id;

        // Generate unique session ID
        $sessionId = Str::uuid()->toString();

        return response()->json([
            'message' => 'New quiz session started',
            'session_id' => $sessionId,
            'user_id' => $userId,
            'instructions' => 'You can now answer all questions and submit when ready'
        ]);
    }

    /**
     * Submit quiz answers and generate result automatically
     */
    public function submitQuiz(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer',
            'answers.*.answer_id' => 'required|integer',
            'answers.*.skor_visual' => 'required|integer|min:0|max:1',
            'answers.*.skor_auditory' => 'required|integer|min:0|max:1',
            'answers.*.skor_kinestetik' => 'required|integer|min:0|max:1'
        ]);

        $userId = $request->user_id;
        $answers = $request->answers;

        // Generate unique session ID for this quiz attempt
        $sessionId = Str::uuid()->toString();

        try {
            DB::beginTransaction();

            // Save all answers with the new session ID
            $userAnswers = [];
            foreach ($answers as $answer) {
                $userAnswers[] = [
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'question_id' => $answer['question_id'],
                    'answer_id' => $answer['answer_id'],
                    'skor_visual' => $answer['skor_visual'],
                    'skor_auditory' => $answer['skor_auditory'],
                    'skor_kinestetik' => $answer['skor_kinestetik'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            UserAnswer::insert($userAnswers);

            // Calculate scores immediately
            $scores = UserAnswer::where('session_id', $sessionId)
                ->selectRaw('
                    SUM(skor_visual) as total_visual,
                    SUM(skor_auditory) as total_auditory,
                    SUM(skor_kinestetik) as total_kinestetik
                ')->first();

            // Determine dominant learning style
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

            if (!$styleData) {
                throw new \Exception('Learning style data not found');
            }

            // Calculate test number for this user
            $testNumber = Result::where('user_id', $userId)->count() + 1;

            // Create result record
            $result = Result::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'style_id' => $styleData->style_id,
                'test_number' => $testNumber,
                'total_skor_visual' => $scores->total_visual,
                'total_skor_auditory' => $scores->total_auditory,
                'total_skor_kinestetik' => $scores->total_kinestetik,
            ]);

            DB::commit();

            // Calculate percentages
            $totalScore = $scores->total_visual + $scores->total_auditory + $scores->total_kinestetik;
            $percentages = [
                'visual' => $totalScore > 0 ? round(($scores->total_visual / $totalScore) * 100, 1) : 0,
                'auditory' => $totalScore > 0 ? round(($scores->total_auditory / $totalScore) * 100, 1) : 0,
                'kinestetik' => $totalScore > 0 ? round(($scores->total_kinestetik / $totalScore) * 100, 1) : 0
            ];

            return response()->json([
                'message' => 'Quiz completed successfully!',
                'result_id' => $result->result_id,
                'session_id' => $sessionId,
                'test_number' => $testNumber,
                'result' => [
                    'learning_style' => $style,
                    'description' => $styleData->rekomendasi,
                    'scores' => [
                        'visual' => $scores->total_visual,
                        'auditory' => $scores->total_auditory,
                        'kinestetik' => $scores->total_kinestetik,
                        'total' => $totalScore
                    ],
                    'percentages' => $percentages,
                    'test_date' => $result->created_at->format('Y-m-d H:i:s')
                ],
                'can_retake' => true,
                'retake_message' => 'You can take the quiz again anytime to see if your learning style has changed!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to submit quiz',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick retake quiz for logged in user
     */
    public function retakeQuiz(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $userId = $request->user_id;

        // Check how many tests user has taken
        $totalTests = Result::where('user_id', $userId)->count();

        return response()->json([
            'message' => 'Ready to retake quiz',
            'user_id' => $userId,
            'previous_tests' => $totalTests,
            'next_test_number' => $totalTests + 1,
            'instructions' => 'Answer all questions and submit to get your updated learning style results'
        ]);
    }

    /**
     * Get user's quiz history with comparison
     */
    public function getHistory(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $userId = $request->user_id;

        $results = Result::where('user_id', $userId)
            ->with('style')
            ->orderBy('test_number', 'desc')
            ->get()
            ->map(function ($result, $index) {
                $totalScore = $result->total_skor_visual + $result->total_skor_auditory + $result->total_skor_kinestetik;

                return [
                    'result_id' => $result->result_id,
                    'test_number' => $result->test_number,
                    'learning_style' => $result->style->gaya_belajar,
                    'description' => $result->style->rekomendasi,
                    'scores' => [
                        'visual' => $result->total_skor_visual,
                        'auditory' => $result->total_skor_auditory,
                        'kinestetik' => $result->total_skor_kinestetik,
                        'total' => $totalScore
                    ],
                    'percentages' => [
                        'visual' => $totalScore > 0 ? round(($result->total_skor_visual / $totalScore) * 100, 1) : 0,
                        'auditory' => $totalScore > 0 ? round(($result->total_skor_auditory / $totalScore) * 100, 1) : 0,
                        'kinestetik' => $totalScore > 0 ? round(($result->total_skor_kinestetik / $totalScore) * 100, 1) : 0
                    ],
                    'test_date' => $result->created_at->format('Y-m-d H:i:s'),
                    'is_latest' => $index === 0
                ];
            });

        return response()->json([
            'message' => 'Quiz history retrieved successfully',
            'user_id' => $userId,
            'total_tests' => $results->count(),
            'can_retake' => true,
            'data' => $results
        ]);
    }

    /**
     * Get latest result only
     */
    public function getLatest(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $userId = $request->user_id;

        $latestResult = Result::where('user_id', $userId)
            ->with('style')
            ->orderBy('test_number', 'desc')
            ->first();

        if (!$latestResult) {
            return response()->json([
                'message' => 'No quiz results found. Take your first quiz!',
                'has_result' => false,
                'user_id' => $userId,
                'can_take_quiz' => true
            ]);
        }

        $totalScore = $latestResult->total_skor_visual + $latestResult->total_skor_auditory + $latestResult->total_skor_kinestetik;

        return response()->json([
            'message' => 'Latest quiz result',
            'has_result' => true,
            'can_retake' => true,
            'data' => [
                'result_id' => $latestResult->result_id,
                'test_number' => $latestResult->test_number,
                'learning_style' => $latestResult->style->gaya_belajar,
                'description' => $latestResult->style->rekomendasi,
                'scores' => [
                    'visual' => $latestResult->total_skor_visual,
                    'auditory' => $latestResult->total_skor_auditory,
                    'kinestetik' => $latestResult->total_skor_kinestetik,
                    'total' => $totalScore
                ],
                'percentages' => [
                    'visual' => $totalScore > 0 ? round(($latestResult->total_skor_visual / $totalScore) * 100, 1) : 0,
                    'auditory' => $totalScore > 0 ? round(($latestResult->total_skor_auditory / $totalScore) * 100, 1) : 0,
                    'kinestetik' => $totalScore > 0 ? round(($latestResult->total_skor_kinestetik / $totalScore) * 100, 1) : 0
                ],
                'test_date' => $latestResult->created_at->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Compare results - shows progression over time
     */
    public function compare(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $userId = $request->user_id;

        $results = Result::where('user_id', $userId)
            ->with('style')
            ->orderBy('test_number', 'desc')
            ->limit(2)
            ->get();

        if ($results->count() < 2) {
            return response()->json([
                'message' => 'Take at least 2 quizzes to see comparison',
                'can_compare' => false,
                'total_tests' => $results->count(),
                'suggestion' => 'Retake the quiz to track your learning style changes over time!'
            ]);
        }

        $latest = $results->first();
        $previous = $results->last();

        $comparison = [
            'latest' => [
                'test_number' => $latest->test_number,
                'learning_style' => $latest->style->gaya_belajar,
                'scores' => [
                    'visual' => $latest->total_skor_visual,
                    'auditory' => $latest->total_skor_auditory,
                    'kinestetik' => $latest->total_skor_kinestetik
                ],
                'date' => $latest->created_at->format('Y-m-d H:i:s')
            ],
            'previous' => [
                'test_number' => $previous->test_number,
                'learning_style' => $previous->style->gaya_belajar,
                'scores' => [
                    'visual' => $previous->total_skor_visual,
                    'auditory' => $previous->total_skor_auditory,
                    'kinestetik' => $previous->total_skor_kinestetik
                ],
                'date' => $previous->created_at->format('Y-m-d H:i:s')
            ],
            'changes' => [
                'style_changed' => $latest->style->gaya_belajar !== $previous->style->gaya_belajar,
                'score_differences' => [
                    'visual' => $latest->total_skor_visual - $previous->total_skor_visual,
                    'auditory' => $latest->total_skor_auditory - $previous->total_skor_auditory,
                    'kinestetik' => $latest->total_skor_kinestetik - $previous->total_skor_kinestetik
                ]
            ]
        ];

        return response()->json([
            'message' => 'Quiz comparison results',
            'can_compare' => true,
            'can_retake' => true,
            'data' => $comparison
        ]);
    }

    // Deprecated - keeping for backward compatibility
    public function store(Request $request)
    {
        return response()->json([
            'message' => 'This endpoint is deprecated. Use /submit-quiz instead.',
            'new_endpoint' => '/api/submit-quiz'
        ], 410);
    }
}
