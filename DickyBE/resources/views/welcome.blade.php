<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Studyfy') }} - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-indigo-600">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            {{ config('app.name', 'QuizApp') }}
                        </h1>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-700">Welcome, {{ Auth::user()->name }}!</span>
                            
                            @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-200">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                                </a>
                            @endif
                            
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('login') }}" 
                               class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-200">
                                <i class="fas fa-sign-in-alt mr-1"></i>Login
                            </a>
                            <a href="{{ route('register') }}" 
                               class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-200">
                                <i class="fas fa-user-plus mr-1"></i>Register
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                    Welcome to 
                    <span class="text-indigo-600">Quiz Platform</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Test your knowledge, challenge yourself, and learn something new every day. 
                    Join our community of learners and start your quiz journey today!
                </p>

                @guest
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('register') }}" 
                           class="bg-indigo-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-indigo-700 transform hover:scale-105 transition duration-200 shadow-lg">
                            <i class="fas fa-rocket mr-2"></i>Get Started
                        </a>
                        <a href="{{ route('login') }}" 
                           class="border-2 border-indigo-600 text-indigo-600 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-indigo-600 hover:text-white transition duration-200">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </a>
                    </div>
                @else
                    <div class="flex justify-center">
                        @if(Auth::user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" 
                               class="bg-indigo-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-indigo-700 transform hover:scale-105 transition duration-200 shadow-lg">
                                <i class="fas fa-tachometer-alt mr-2"></i>Go to Dashboard
                            </a>
                        @else
                            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl">
                                <i class="fas fa-check-circle mr-2"></i>
                                You're logged in! Explore the available features.
                            </div>
                        @endif
                    </div>
                @endguest
            </div>
        </div>

        <!-- Background decoration -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Why Choose Our Platform?
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Discover the features that make our quiz platform the perfect choice for learners everywhere.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 hover:shadow-lg transition duration-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-500 text-white rounded-full mb-6">
                        <i class="fas fa-brain text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Smart Questions</h3>
                    <p class="text-gray-600">
                        Carefully crafted questions designed to challenge your mind and enhance your learning experience.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-50 hover:shadow-lg transition duration-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-500 text-white rounded-full mb-6">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Track Progress</h3>
                    <p class="text-gray-600">
                        Monitor your learning journey with detailed analytics and progress tracking features.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 hover:shadow-lg transition duration-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-500 text-white rounded-full mb-6">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Community</h3>
                    <p class="text-gray-600">
                        Join a vibrant community of learners and share your knowledge with others.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    @guest
    <div class="bg-indigo-600 py-20">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Ready to Start Learning?
            </h2>
            <p class="text-xl text-indigo-100 mb-8">
                Join thousands of learners who have already started their quiz journey with us.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" 
                   class="bg-white text-indigo-600 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-gray-100 transform hover:scale-105 transition duration-200 shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i>Create Account
                </a>
                <a href="{{ route('login') }}" 
                   class="border-2 border-white text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white hover:text-indigo-600 transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In Now
                </a>
            </div>
        </div>
    </div>
    @endguest

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h3 class="text-2xl font-bold mb-4">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    {{ config('app.name', 'QuizApp') }}
                </h3>
                <p class="text-gray-400 mb-6">
                    Empowering learners through interactive quizzes and knowledge sharing.
                </p>
                <div class="flex justify-center space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fab fa-facebook-f text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fab fa-linkedin-in text-xl"></i>
                    </a>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-800 text-gray-400">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'QuizApp') }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>
</html>