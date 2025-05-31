@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-semibold mb-6">Admin Dashboard</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-indigo-100 p-6 rounded-lg">
            <h3 class="text-lg font-medium mb-2">Total Questions</h3>
            <p class="text-3xl font-bold">{{ \App\Models\Question::count() }}</p>
        </div>
        
        <div class="bg-green-100 p-6 rounded-lg">
            <h3 class="text-lg font-medium mb-2">Total Answers</h3>
            <p class="text-3xl font-bold">{{ \App\Models\Answer::count() }}</p>
        </div>
        
        <div class="bg-blue-100 p-6 rounded-lg">
            <h3 class="text-lg font-medium mb-2">Total Users</h3>
            <p class="text-3xl font-bold">{{ \App\Models\User::count() }}</p>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.questions.create') }}" 
               class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition duration-200">
                Add New Question
            </a>
            <a href="{{ route('admin.questions.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-200">
                Manage Questions
            </a>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-gray-600">Dashboard statistics and recent activities will be displayed here.</p>
        </div>
    </div>
</div>
@endsection