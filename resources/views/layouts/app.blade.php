<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Munchkly') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Keyboard animations CSS -->
    <link rel="stylesheet" href="{{ asset('css/keyboard.css') }}">
    
    <style>
        .char-counter {
            transition: color 0.2s ease;
        }
        .char-limit {
            color: #ef4444;
        }
        .char-warning {
            color: #f59e0b;
        }
        
        /* Footer styles */
        html, body {
            height: 100%;
        }
        
        .page-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .content-wrap {
            flex: 1;
        }
        
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="page-container">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <i class="fas fa-kiwi-bird text-blue-500 text-2xl mr-2"></i>
                            <span class="text-xl font-bold text-gray-900">Munchkly</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4">
                        @if($isAuth && $authUser)
                            <a href="{{ route('profile.show', $authUser->id) }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-user mr-1"></i>Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-sign-out-alt mr-1"></i>Logout
                                </button>
                            </form>
                            <span class="text-sm text-gray-500">Hi, {{ $authUser->name }}!</span>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-sign-in-alt mr-1"></i>Login
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <div class="content-wrap">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="max-w-4xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-4xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="max-w-4xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('info') }}</span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <main class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>

        <!-- Footer (only on login/register pages) -->
        @if(in_array(request()->route()->getName(), ['login', 'register']))
        <footer class="bg-gray-800 text-white mt-12">
            <div class="max-w-4xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <!-- Main Footer Content -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-4">
                    <!-- Brand Section -->
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start mb-3">
                            <i class="fas fa-kiwi-bird text-blue-400 text-2xl mr-2"></i>
                            <span class="text-xl font-bold">Munchkly</span>
                        </div>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            A Twitter-like social media platform where you can share your thoughts, connect with friends, and stay updated with the latest trends.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div class="text-center">
                        <h4 class="font-semibold mb-3 text-white">Quick Links</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-blue-400 transition-colors">Home</a></li>
                            @if($isAuth && $authUser)
                                <li><a href="{{ route('profile.show', $authUser->id) }}" class="text-gray-300 hover:text-blue-400 transition-colors">Profile</a></li>
                            @endif
                            <li><a href="#" class="text-gray-300 hover:text-blue-400 transition-colors">About</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-blue-400 transition-colors">Privacy Policy</a></li>
                        </ul>
                    </div>

                    <!-- Connect Section -->
                    <div class="text-center md:text-right">
                        <h4 class="font-semibold mb-3 text-white">Connect With Us</h4>
                        <div class="flex justify-center md:justify-end space-x-4 mb-3">
                            <a href="#" class="text-gray-300 hover:text-blue-400 transition-colors text-lg">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-blue-400 transition-colors text-lg">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-blue-400 transition-colors text-lg">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-blue-400 transition-colors text-lg">
                                <i class="fab fa-github"></i>
                            </a>
                        </div>
                        <p class="text-gray-400 text-xs">
                            Built with Laravel & Tailwind CSS
                        </p>
                    </div>
                </div>

                <!-- Bottom Footer -->
                <div class="border-t border-gray-700 pt-3">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-gray-400 text-sm mb-2 md:mb-0">
                            © 2025 Munchkly. All rights reserved.
                        </p>
                        <div class="flex space-x-4 text-xs text-gray-400">
                            <a href="#" class="hover:text-blue-400 transition-colors">Terms of Service</a>
                            <a href="#" class="hover:text-blue-400 transition-colors">Privacy Policy</a>
                            <a href="#" class="hover:text-blue-400 transition-colors">Contact</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        @endif
    </div>

    <!-- Character Counter Script -->
    <script>
        function updateCharCounter(textarea, counter) {
            const maxLength = 280;
            const currentLength = textarea.value.length;
            const remaining = maxLength - currentLength;
            
            counter.textContent = remaining;
            
            if (remaining < 0) {
                counter.className = 'char-counter char-limit text-red-500 text-sm font-medium';
            } else if (remaining < 20) {
                counter.className = 'char-counter char-warning text-orange-500 text-sm font-medium';
            } else {
                counter.className = 'char-counter text-gray-500 text-sm';
            }
        }

        // Auto-remove flash messages after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>