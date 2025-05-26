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
            // Debug: cek data questions
            $questions = Question::with('answers')->get();

            // Debug: log atau dump data untuk melihat struktur
            Log::info('Questions data:', $questions->toArray());

            // Pastikan answers ter-load
            foreach ($questions as $question) {
                Log::info("Question {$question->question_id} has " . $question->answers->count() . " answers");
            }

            return response()->json($questions);
        } catch (\Exception $e) {
            Log::error('Error in QuestionController: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Failed to load questions'
            ], 500);
        }
    }
}
