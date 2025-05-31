@extends('admin.layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.questions.index') }}" class="text-gray-600 hover:text-gray-800 mr-4 p-2 rounded hover:bg-gray-100 transition duration-200">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-semibold text-gray-800">Edit Answer</h2>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg mb-6 border-l-4 border-indigo-400">
        <h3 class="font-medium text-gray-700 mb-2 flex items-center">
            <i class="fas fa-question-circle mr-2 text-indigo-600"></i>
            Question:
        </h3>
        <p class="text-gray-800 leading-relaxed">{{ $question->question_text }}</p>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    
    <form action="{{ route('admin.questions.answers.update', [$question->question_id, $answer->answer_id]) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="answer_type" class="block text-sm font-medium text-gray-700 mb-2">
                Answer Type <span class="text-red-500">*</span>
            </label>
            <select name="answer_type" 
                    id="answer_type" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('answer_type') border-red-500 focus:ring-red-500 @enderror"
                    required>
                <option value="">Select Answer Type</option>
                <option value="setuju" {{ old('answer_type', $answer->answer_type) == 'setuju' ? 'selected' : '' }}>
                    ✓ Setuju (Agree)
                </option>
                <option value="tidak_setuju" {{ old('answer_type', $answer->answer_type) == 'tidak_setuju' ? 'selected' : '' }}>
                    ✗ Tidak Setuju (Disagree)
                </option>
            </select>
            @error('answer_type')
                <p class="text-red-500 text-sm mt-2 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>
        
        <div>
            <label for="learning_type" class="block text-sm font-medium text-gray-700 mb-2">
                Learning Type <span class="text-red-500">*</span>
            </label>
            <select name="learning_type" 
                    id="learning_type" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('learning_type') border-red-500 focus:ring-red-500 @enderror"
                    required>
                <option value="">Select Learning Type</option>
                <option value="visual" {{ old('learning_type', $answer->learning_type) == 'visual' ? 'selected' : '' }}>
                    👁️ Visual - Learn through seeing and visual aids
                </option>
                <option value="auditory" {{ old('learning_type', $answer->learning_type) == 'auditory' ? 'selected' : '' }}>
                    👂 Auditory - Learn through hearing and listening
                </option>
                <option value="kinesthetic" {{ old('learning_type', $answer->learning_type) == 'kinesthetic' ? 'selected' : '' }}>
                    ✋ Kinesthetic - Learn through hands-on activities
                </option>
            </select>
            @error('learning_type')
                <p class="text-red-500 text-sm mt-2 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400">
            <h3 class="font-medium text-yellow-800 mb-2 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Current Answer Details
            </h3>
            <div class="text-sm text-yellow-700">
                <p><strong>Answer Type:</strong> 
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-1
                        @if($answer->answer_type === 'setuju') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ $answer->answer_type === 'setuju' ? '✓ Setuju' : '✗ Tidak Setuju' }}
                    </span>
                </p>
                <p class="mt-1"><strong>Learning Type:</strong> 
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-1
                        @if($answer->learning_type === 'visual') bg-blue-100 text-blue-800
                        @elseif($answer->learning_type === 'auditory') bg-green-100 text-green-800
                        @else bg-purple-100 text-purple-800 @endif">
                        {{ ucfirst($answer->learning_type) }}
                    </span>
                </p>
                <p class="mt-1"><strong>Created:</strong> {{ $answer->created_at->format('M d, Y H:i') }}</p>
                @if($answer->updated_at != $answer->created_at)
                    <p><strong>Last Updated:</strong> {{ $answer->updated_at->format('M d, Y H:i') }}</p>
                @endif
            </div>
        </div>

        <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="font-medium text-blue-800 mb-2 flex items-center">
                <i class="fas fa-lightbulb mr-2"></i>
                Learning Type Guide
            </h3>
            <div class="space-y-2 text-sm text-blue-700">
                <p><strong>Visual:</strong> Charts, diagrams, images, written instructions</p>
                <p><strong>Auditory:</strong> Spoken explanations, discussions, audio materials</p>
                <p><strong>Kinesthetic:</strong> Hands-on practice, physical activities, experiments</p>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('admin.questions.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                Cancel
            </a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                Update Answer
            </button>
        </div>
    </form>
</div>
@endsection