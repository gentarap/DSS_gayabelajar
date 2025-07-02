<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Panel - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Optional: If you want to use Vite later, uncomment this -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h2 class="text-xl font-bold">Admin Panel</h2>
            </div>

            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('admin.questions.index') }}"
                    class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.questions.*') ? 'bg-gray-700' : '' }}">
                    Questions Management
                </a>

                <a href="{{ route('admin.questions.index') }}"
                    class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.questions.*') ? 'bg-gray-700' : '' }}">
                    User Management
                </a>

                <div class="mt-8 pt-4 border-t border-gray-700">
                    <a href="{{ route('home') }}"
                        class="block px-4 py-2 hover:bg-gray-700">
                        Back to Site
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline w-full">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 hover:bg-gray-700">
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <div class="flex justify-between items-center">
                        <h1 class="text-lg font-semibold text-gray-800">
                            @yield('page-title', 'Admin Dashboard')
                        </h1>

                        <div class="flex items-center space-x-4">
                            <span class="text-gray-600">Welcome, {{ Auth::user()->name }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-6 mt-4 rounded" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-6 mt-4 rounded" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-6 mt-4 rounded" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>