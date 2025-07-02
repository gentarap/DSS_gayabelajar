<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use App\Models\User; // <-- Pastikan ini sudah diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Result;
use App\Models\LearningStyle;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\Hash; // <-- Tambahkan ini untuk hashing password
use Illuminate\Validation\ValidationException; // <-- Tambahkan ini untuk exception validasi

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalQuestions = Question::count();
        $totalUsers = User::count();
        $totalTests = Result::count();

        $visualCount = Result::where('style_id', 1)->count();
        $auditoryCount = Result::where('style_id', 2)->count();
        $kinestheticCount = Result::where('style_id', 3)->count();

        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dbChartData = Result::select(
            DB::raw("DATE(created_at) as tanggal"),
            DB::raw("SUM(CASE WHEN style_id = 1 THEN 1 ELSE 0 END) as visual"),
            DB::raw("SUM(CASE WHEN style_id = 2 THEN 1 ELSE 0 END) as auditory"),
            DB::raw("SUM(CASE WHEN style_id = 3 THEN 1 ELSE 0 END) as kinestetik")
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $chartData = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $existing = $dbChartData->get($dateString);

            $chartData[] = [
                'tanggal' => $dateString,
                'visual' => $existing ? (int) $existing->visual : 0,
                'auditory' => $existing ? (int) $existing->auditory : 0,
                'kinestetik' => $existing ? (int) $existing->kinestetik : 0,
            ];
            $currentDate->addDay();
        }

        $recentResults = Result::with('style')->latest()->take(5)->get()
            ->map(function ($result) {
                return [
                    'id' => $result->id,
                    'style_name' => $result->style ? $result->style->name : 'Unknown Style',
                    'created_at' => $result->created_at->format('d M Y H:i'),
                    'visual_score' => $result->total_skor_visual ?? 0,
                    'auditory_score' => $result->total_skor_auditory ?? 0,
                    'kinesthetic_score' => $result->total_skor_kinestetik ?? 0
                ];
            });


        $weeklyStats = Result::select(
            DB::raw("WEEK(created_at, 1) as week"),
            DB::raw("YEAR(created_at) as year"),
            DB::raw("SUM(CASE WHEN style_id = 1 THEN 1 ELSE 0 END) as visual"),
            DB::raw("SUM(CASE WHEN style_id = 2 THEN 1 ELSE 0 END) as auditory"),
            DB::raw("SUM(CASE WHEN style_id = 3 THEN 1 ELSE 0 END) as kinestetik"),
            DB::raw("COUNT(*) as total")
        )
            ->where('created_at', '>=', Carbon::now()->subWeeks(5)->startOfWeek())
            ->groupBy('week', 'year')
            ->orderBy('year', 'desc')
            ->orderBy('week', 'desc')
            ->take(4)
            ->get();

        return view('admin.dashboard', compact(
            'totalQuestions',
            'totalUsers',
            'totalTests',
            'visualCount',
            'auditoryCount',
            'kinestheticCount',
            'recentResults',
            'weeklyStats',
            'chartData'
        ));
    }


    public function getDashboardData()
    {
        $visualCount = Result::where('style_id', 1)->count();
        $auditoryCount = Result::where('style_id', 2)->count();
        $kinestheticCount = Result::where('style_id', 3)->count();

        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dbChartData = Result::select(
            DB::raw("DATE(created_at) as tanggal"),
            DB::raw("SUM(CASE WHEN style_id = 1 THEN 1 ELSE 0 END) as visual"),
            DB::raw("SUM(CASE WHEN style_id = 2 THEN 1 ELSE 0 END) as auditory"),
            DB::raw("SUM(CASE WHEN style_id = 3 THEN 1 ELSE 0 END) as kinestetik")
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $chartData = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $existing = $dbChartData->get($dateString);

            $chartData[] = [
                'tanggal' => $dateString,
                'visual' => $existing ? (int) $existing->visual : 0,
                'auditory' => $existing ? (int) $existing->auditory : 0,
                'kinestetik' => $existing ? (int) $existing->kinestetik : 0,
            ];

            $currentDate->addDay();
        }

        $weeklyStats = Result::select(
            DB::raw("WEEK(created_at, 1) as week"),
            DB::raw("YEAR(created_at) as year"),
            DB::raw("SUM(CASE WHEN style_id = 1 THEN 1 ELSE 0 END) as visual"),
            DB::raw("SUM(CASE WHEN style_id = 2 THEN 1 ELSE 0 END) as auditory"),
            DB::raw("SUM(CASE WHEN style_id = 3 THEN 1 ELSE 0 END) as kinestetik"),
            DB::raw("COUNT(*) as total")
        )
            ->where('created_at', '>=', Carbon::now()->subWeeks(5)->startOfWeek())
            ->groupBy('week', 'year')
            ->orderBy('year', 'desc')
            ->orderBy('week', 'desc')
            ->take(4)
            ->get();

        $recentResults = Result::with(['style'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($result) {
                return [
                    'id' => $result->id,
                    'style_name' => $result->style ? $result->style->name : 'Unknown Style',
                    'created_at' => $result->created_at->format('d M Y H:i'),
                    'visual_score' => $result->total_skor_visual ?? 0,
                    'auditory_score' => $result->total_skor_auditory ?? 0,
                    'kinesthetic_score' => $result->total_skor_kinestetik ?? 0
                ];
            });

        $data = [
            'totalTests' => Result::count(),
            'visualCount' => $visualCount,
            'auditoryCount' => $auditoryCount,
            'kinestheticCount' => $kinestheticCount,
            'chartData' => $chartData,
            'weeklyStats' => $weeklyStats,
            'recentResults' => $recentResults
        ];

        return response()->json($data);
    }
    public function questionsIndex()
    {

        try {
            $questions = Question::with('answers')
                ->orderByDesc('question_id')
                ->get();
            return view('admin.questions.index', compact('questions'));
        } catch (\Exception $e) {
            Log::error('Error in AdminController@questionsIndex: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat pertanyaan. Silakan coba lagi nanti.');
        }
    }

    public function storeQuestion(Request $request)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
        ]);

        try {
            $question = Question::create($validated);
            return redirect()->route('admin.questions.index')->with('success', 'Question created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating question: ' . $e->getMessage());
            return back()->with('error', 'Failed to create question');
        }
    }

    public function editQuestion($id)
    {
        $question = Question::findOrFail($id);
        return view('admin.questions.edit', compact('question'));
    }

    public function updateQuestion(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        try {
            $question->update($validated);
            return redirect()->route('admin.questions.index')->with('success', 'Question updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating question: ' . $e->getMessage());
            return back()->with('error', 'Failed to update question');
        }
    }

    public function deleteQuestion($id)
    {
        $question = Question::findOrFail($id);

        try {
            $question->answers()->delete();
            $question->delete();
            return redirect()->route('admin.questions.index')->with('success', 'Question deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting question: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete question');
        }
    }

    public function createAnswer($questionId)
    {
        $question = Question::findOrFail($questionId);
        return view('admin.answers.create', compact('question'));
    }

    public function storeAnswer(Request $request, $questionId)
    {
        $validated = $request->validate([
            'answer_text' => ['required'],
            'learning_type' => ['required', Rule::in(['visual', 'auditory', 'kinesthetic'])],
        ]);

        try {
            $question = Question::findOrFail($questionId);

            $answer = new Answer();
            $answer->question_id = $questionId;
            $answer->answer_text = $validated['answer_text'];
            $answer->learning_type = $validated['learning_type'];
            $answer->save();

            return redirect()->route('admin.questions.index')
                ->with('success', 'Answer added successfully');
        } catch (\Exception $e) {
            Log::error('Error creating answer: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to add answer: ' . $e->getMessage());
        }
    }


    public function editAnswer($questionId, $answerId)
    {
        $question = Question::findOrFail($questionId);
        $answer = Answer::findOrFail($answerId);
        return view('admin.answers.edit', compact('question', 'answer'));
    }

    public function updateAnswer(Request $request, $questionId, $answerId)
    {
        $validated = $request->validate([
            'answer_text' => ['required'],
            'learning_type' => ['required', Rule::in(['visual', 'auditory', 'kinesthetic'])],
        ]);

        try {
            $answer = Answer::findOrFail($answerId);
            $answer->update($validated);
            return redirect()->route('admin.questions.index')->with('success', 'Answer updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating answer: ' . $e->getMessage());
            return back()->with('error', 'Failed to update answer');
        }
    }

    public function deleteAnswer($questionId, $answerId)
    {
        try {
            $answer = Answer::findOrFail($answerId);
            $answer->delete();
            return redirect()->route('admin.questions.index')->with('success', 'Answer deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting answer: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete answer');
        }
    }

    public function usersIndex()
    {
        // Ambil semua user, urutkan dari ID user terbaru ke terlama
        // Kecualikan admin agar admin tidak bisa menghapus dirinya sendiri dengan mudah
        $users = User::orderByDesc('user_id')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat user baru.
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function storeUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed', // 'confirmed' akan mencari password_confirmation
                'role' => ['required', Rule::in(['admin', 'user'])],
            ]);

            User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']), // Hash password
                'role' => $validated['role'],
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan user. ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan form untuk mengedit user.
     */
    public function editUser($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui detail user di database.
     */
    public function updateUser(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        try {
            $rules = [
                'name' => 'required|string|max:255',
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')],
                'role' => ['required', Rule::in(['admin', 'user'])],
            ];

            // Hanya validasi password jika ada input password baru
            if ($request->filled('password')) {
                $rules['password'] = 'nullable|string|min:8|confirmed';
            }

            $validated = $request->validate($rules);

            $user->name = $validated['name'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];

            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating user ' . $user_id . ': ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui user. ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus user dari database.
     */
    public function deleteUser($user_id)
    {
        // Pencegahan agar admin tidak menghapus dirinya sendiri
        if (auth()->user()->user_id == $user_id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }

        try {
            $user = User::findOrFail($user_id);
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting user ' . $user_id . ': ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus user. ' . $e->getMessage());
        }
    }
}
