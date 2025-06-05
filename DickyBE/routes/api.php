<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\UserAnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/questions', QuestionController::class);
    Route::apiResource('/results', ResultController::class);
    Route::apiResource('/user-answers', UserAnswerController::class);

    Route::put('/user-answers/update', [UserAnswerController::class, 'updateAnswers']);
    Route::get('/results/history/{userId}', [ResultController::class, 'userHistory']);

    //logout 
    Route::post('/logout', [AuthController::class, 'logout']);
});
