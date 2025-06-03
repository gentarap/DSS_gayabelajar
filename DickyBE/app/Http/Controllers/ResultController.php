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
            ->get();

        return response()->json($results);
    }
}
