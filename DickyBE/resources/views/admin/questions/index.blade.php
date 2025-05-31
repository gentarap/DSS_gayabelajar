@extends('admin.layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Manage Questions</h2>
        <a href="{{ route('admin.questions.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Question
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($questions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question Text</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Answers</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($questions as $question)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="py-4 px-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $question->question_id }}
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-900">
                            <div class="max-w-xs">
                                <p class="truncate" title="{{ $question->question_text }}">
                                    {{ Str::limit($question->question_text, 100) }}
                                </p>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-900">
                            @if($question->answers->count() > 0)
                                <div class="space-y-2">
                                    @foreach($question->answers as $answer)
                                        <div class="flex items-center justify-between bg-gray-100 rounded-lg px-3 py-2">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-900 truncate" title="{{ $answer->answer_text }}">
                                                    {{ Str::limit($answer->answer_text, 40) }}
                                                </p>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                    @if($answer->learning_type === 'visual') bg-blue-100 text-blue-800
                                                    @elseif($answer->learning_type === 'auditory') bg-green-100 text-green-800
                                                    @else bg-purple-100 text-purple-800 @endif">
                                                    {{ ucfirst($answer->learning_type) }}
                                                </span>
                                            </div>
                                            <div class="flex space-x-1 ml-2">
                                                <a href="{{ route('admin.questions.answers.edit', [$question->question_id, $answer->answer_id]) }}" 
                                                   class="inline-flex items-center px-2 py-1 text-xs text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded border border-blue-200" 
                                                   title="Edit Answer">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.questions.answers.delete', [$question->question_id, $answer->answer_id]) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this answer?')"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-2 py-1 text-xs text-red-600 hover:text-red-800 hover:bg-red-50 rounded border border-red-200" 
                                                            title="Delete Answer">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-2 text-xs text-gray-500">
                                    {{ $question->answers->count() }} answer(s)
                                </div>
                            @else
                                <div class="text-center py-2">
                                    <span class="text-gray-500 text-sm">No answers</span>
                                    <div class="mt-1">
                                        <a href="{{ route('admin.questions.answers.create', $question->question_id) }}" 
                                           class="text-indigo-600 hover:text-indigo-800 text-xs">
                                            Add first answer
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-col space-y-1 sm:flex-row sm:space-y-0 sm:space-x-2">
                                <!-- Edit Question Button -->
                                <a href="{{ route('admin.questions.edit', $question->question_id) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" 
                                   title="Edit Question">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                
                                <!-- Add Answer Button -->
                                <a href="{{ route('admin.questions.answers.create', $question->question_id) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" 
                                   title="Add Answer">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Answer
                                </a>
                                
                                <!-- Delete Question Button -->
                                <form action="{{ route('admin.questions.delete', $question->question_id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure? This will delete the question and all its answers!')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" 
                                            title="Delete Question">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No questions found</h3>
            <p class="text-gray-500 mb-6">Get started by creating your first question.</p>
            <a href="{{ route('admin.questions.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Your First Question
            </a>
        </div>
    @endif
</div>
@endsection