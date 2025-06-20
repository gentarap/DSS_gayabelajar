<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    public function index()
    {
        try {
            $questions = Question::with(['answers' => function ($query) {
                $query->inRandomOrder();
            }])
                ->inRandomOrder()
                ->limit(20)
                ->get();

            $questions->transform(function ($question) {
                $question->answers = $question->answers->shuffle();
                return $question;
            });

            return response()->json($questions);
        } catch (\Exception $e) {
            Log::error('Error in QuestionController@index: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Failed to load shuffled questions and answers'
            ], 500);
        }
    }
}
