<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title') - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    {{-- Anda bisa menambahkan link CSS khusus lainnya di sini jika diperlukan --}}
    @stack('styles') {{-- Untuk CSS tambahan per halaman --}}
</head>
<body class="font-sans antialiased bg-gray-100">

    {{-- Container utama yang mengatur layout sidebar dan konten --}}
    <div class="min-h-screen flex">
        {{-- Sidebar Navigasi - Isi dari admin.partials.navigation --}}
        <div class="w-64 bg-gray-800 text-white flex-shrink-0">
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

<a href="{{ route('admin.users.index') }}"
    class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">
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

        {{-- Area konten utama (header + main content) --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Header/Navbar - Isi dari admin.partials.navbar --}}
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <div class="flex justify-between items-center">
                        <h1 class="text-lg font-semibold text-gray-800">
                            @yield('page-title', 'Admin Dashboard')
                        </h1>
                        
                        <div class="flex items-center space-x-4">
                            @auth
                                <span class="text-gray-600">Welcome, {{ Auth::user()->name }}</span>
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            {{-- Bagian untuk menampilkan Flash Messages --}}
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

            {{-- Area konten utama tempat halaman akan di-inject --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 bg-gray-200">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- JavaScript files (termasuk Chart.js dan script kustom Anda) --}}
    @stack('scripts')
</body>
</html>
