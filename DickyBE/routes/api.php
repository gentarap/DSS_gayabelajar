<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\UserAnswerController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/questions', QuestionController::class);
    Route::apiResource('/results', ResultController::class);
    Route::apiResource('/user-answers', UserAnswerController::class);

    //logout 
    Route::post('/logout', [AuthController::class, 'logout']);
});
