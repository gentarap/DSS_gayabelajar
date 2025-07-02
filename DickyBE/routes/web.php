<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthControllerAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::prefix('auth')->group(function () {

    // Login routes
    Route::get('/login', [AuthControllerAdmin::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthControllerAdmin::class, 'login'])->name('login.store');

    // Logout route
    Route::post('/logout', [AuthControllerAdmin::class, 'logout'])->name('logout');
});

// Admin Routes - Protected by auth and admin middleware
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Register routes
    Route::get('/register', [AuthControllerAdmin::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthControllerAdmin::class, 'register'])->name('register.store');

    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Questions Management
    Route::prefix('questions')->name('questions.')->group(function () {
        Route::get('/', [AdminController::class, 'questionsIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'createQuestion'])->name('create');
        Route::post('/store', [AdminController::class, 'storeQuestion'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editQuestion'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateQuestion'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'deleteQuestion'])->name('delete');

        // Answers Management (nested under questions)
        Route::prefix('{questionId}/answers')->name('answers.')->group(function () {
            Route::get('/create', [AdminController::class, 'createAnswer'])->name('create');
            Route::post('/store', [AdminController::class, 'storeAnswer'])->name('store');
            Route::get('/{answerId}/edit', [AdminController::class, 'editAnswer'])->name('edit');
            Route::put('/{answerId}', [AdminController::class, 'updateAnswer'])->name('update');
            Route::delete('/{answerId}', [AdminController::class, 'deleteAnswer'])->name('delete');
        });
    });
});

// User Routes - Protected by auth middleware (for regular users)
Route::middleware(['auth'])->group(function () {
    // Add user-specific routes here if needed
    // Example: Route::get('/profile', [UserController::class, 'profile'])->name('profile');
});

// Guest only routes (redirect if authenticated)
Route::middleware(['guest'])->group(function () {
    // These routes are only accessible to non-authenticated users
    // Login and register forms are already handled above
});
