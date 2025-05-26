<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\UserAnswerController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Question routes
Route::get('/questions', [QuestionController::class, 'index']);

// === User Answer Routes ===
// Generate session ID untuk test baru
Route::post('/generate-session', [UserAnswerController::class, 'generateSession']);

// Simpan jawaban user (batch)
Route::post('/user-answers', [UserAnswerController::class, 'store']);

// Cek progress test
Route::get('/test-progress', [UserAnswerController::class, 'getProgress']);

// Ambil jawaban untuk session tertentu
Route::get('/session-answers', [UserAnswerController::class, 'getSessionAnswers']);

// Cleanup session tidak lengkap
Route::delete('/cleanup-sessions', [UserAnswerController::class, 'cleanupSessions']);

// === Result Routes ===
// Simpan hasil test (membuat histori baru)
Route::post('/results', [ResultController::class, 'store']);

// Ambil hasil test terbaru
Route::get('/latest-result', [ResultController::class, 'getLatest']);

// Ambil histori semua test user
Route::get('/test-history', [ResultController::class, 'getHistory']);

// Bandingkan 2 test terakhir
Route::get('/compare-results', [ResultController::class, 'compare']);
