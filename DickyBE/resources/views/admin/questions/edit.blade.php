@extends('admin.layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.questions.index') }}" class="text-gray-600 hover:text-gray-800 mr-4 p-2 rounded hover:bg-gray-100 transition duration-200">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-semibold text-gray-800">Edit Question</h2>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <form action="{{ route('admin.questions.update', $question->question_id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">
                Question Text <span class="text-red-500">*</span>
            </label>
            <textarea name="question_text"
                id="question_text"
                rows="5"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('question_text') border-red-500 focus:ring-red-500 @enderror"
                required>{{ old('question_text', $question->question_text) }}</textarea>
            <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200">
                <option value="active" {{ old('status', $question->status) === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $question->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('question_text')
            <p class="text-red-500 text-sm mt-2 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $message }}
            </p>
            @enderror
            <p class="text-gray-500 text-sm mt-2">Maximum 1000 characters</p>
        </div>

        @if($question->answers->count() > 0)
        <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="font-medium text-blue-800 mb-2 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Current Answers ({{ $question->answers->count() }})
            </h3>
            <div class="space-y-2">
                @foreach($question->answers as $answer)
                <div class="bg-white p-3 rounded border-l-4 border-blue-400">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="text-gray-800">{{ $answer->answer_text }}</p>
                            <span class="inline-block mt-1 px-2 py-1 rounded-full text-xs font-medium
                                        @if($answer->learning_type === 'visual') bg-blue-100 text-blue-800
                                        @elseif($answer->learning_type === 'auditory') bg-green-100 text-green-800
                                        @else bg-purple-100 text-purple-800 @endif">
                                {{ ucfirst($answer->learning_type) }}
                            </span>
                        </div>
                        <a href="{{ route('admin.questions.answers.edit', [$question->question_id, $answer->answer_id]) }}"
                            class="text-blue-600 hover:text-blue-800 ml-2">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('admin.questions.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                Cancel
            </a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                Update Question
            </button>
        </div>
    </form>
</div>
@endsection