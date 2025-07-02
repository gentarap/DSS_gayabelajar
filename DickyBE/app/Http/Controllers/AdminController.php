<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // Dashboard Admin
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Questions CRUD
    public function questionsIndex(Request $request)
    {
        // Ambil parameter sort dan direction dari query string, defaultnya:
        $sort = $request->query('sort', 'question_id'); // default sort by id
        $direction = $request->query('direction', 'asc'); // default ascending

        // Validasi input parameter supaya hanya bisa sorting yang diijinkan
        $allowedSorts = ['question_id', 'question_text', 'status', 'updated_at', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'question_id';
        }

        if (!in_array($direction, $allowedDirections)) {
            $direction = 'asc';
        }

        // Query dengan sorting dinamis
        $questions = Question::with('answers')
            ->orderBy($sort, $direction)
            ->get();
        return view('admin.questions.index', compact('questions', 'sort', 'direction'));
    }

    public function createQuestion()
    {
        return view('admin.questions.create');
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
            // Delete related answers first
            $question->answers()->delete();
            $question->delete();
            return redirect()->route('admin.questions.index')->with('success', 'Question deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting question: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete question');
        }
    }

    // Answers CRUD
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
}
