    <?php

    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\AuthControllerAdmin;
    use Illuminate\Support\Facades\Route;


    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::prefix('auth')->group(function () {
        Route::get('/register', [AuthControllerAdmin::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthControllerAdmin::class, 'register'])->name('register.store');

        Route::get('/login', [AuthControllerAdmin::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthControllerAdmin::class, 'login'])->name('login.store');

        Route::post('/logout', [AuthControllerAdmin::class, 'logout'])->name('logout');
    });

    Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard-data', [AdminController::class, 'getDashboardData'])->name('dashboard.data');
Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'usersIndex'])->name('index'); // Menampilkan daftar user
        Route::get('/create', [AdminController::class, 'createUser'])->name('create'); // Menampilkan form tambah user
        Route::post('/store', [AdminController::class, 'storeUser'])->name('store'); // Menyimpan user baru
        Route::get('/{user_id}/edit', [AdminController::class, 'editUser'])->name('edit'); // Menampilkan form edit user
        Route::put('/{user_id}', [AdminController::class, 'updateUser'])->name('update'); // Memperbarui user
        Route::delete('/{user_id}', [AdminController::class, 'deleteUser'])->name('delete'); // Menghapus user
    });
        Route::prefix('questions')->name('questions.')->group(function () {
            Route::get('/', [AdminController::class, 'questionsIndex'])->name('index');
            Route::get('/create', [AdminController::class, 'createQuestion'])->name('create');
            Route::post('/store', [AdminController::class, 'storeQuestion'])->name('store');
            Route::get('/{id}/edit', [AdminController::class, 'editQuestion'])->name('edit');
            Route::put('/{id}', [AdminController::class, 'updateQuestion'])->name('update');
            Route::delete('/{id}', [AdminController::class, 'deleteQuestion'])->name('delete');

            Route::prefix('{questionId}/answers')->name('answers.')->group(function () {
                Route::get('/create', [AdminController::class, 'createAnswer'])->name('create');
                Route::post('/store', [AdminController::class, 'storeAnswer'])->name('store');
                Route::get('/{answerId}/edit', [AdminController::class, 'editAnswer'])->name('edit');
                Route::put('/{answerId}', [AdminController::class, 'updateAnswer'])->name('update');
                Route::delete('/{answerId}', [AdminController::class, 'deleteAnswer'])->name('delete');
            });
            
        });
    });

    Route::middleware(['auth'])->group(function () {});

    Route::middleware(['guest'])->group(function () {});
