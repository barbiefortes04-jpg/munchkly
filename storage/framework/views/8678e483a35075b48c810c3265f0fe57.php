<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Munchkly')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Keyboard animations CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/keyboard.css')); ?>">
    
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
        
        /* Dark theme variables and styles */
        :root {
            --bg-primary: #f9fafb;
            --bg-secondary: #ffffff;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --accent-color: #3b82f6;
        }
        
        .dark {
            --bg-primary: #111827;
            --bg-secondary: #1f2937;
            --text-primary: #f9fafb;
            --text-secondary: #9ca3af;
            --border-color: #374151;
            --accent-color: #60a5fa;
        }
        
        .theme-transition {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        /* Theme toggle button styles */
        .theme-toggle {
            position: relative;
            width: 50px;
            height: 25px;
            background: var(--border-color);
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s ease;
            z-index: 10;
        }
        
        .theme-toggle.dark {
            background: #4b5563;
        }
        
        .theme-toggle::before {
            content: '';
            position: absolute;
            top: 2px;
            right: 2px;
            width: 21px;
            height: 21px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            z-index: 11;
        }
        
        .theme-toggle.dark::before {
            transform: translateX(-25px);
            background: #fbbf24;
        }
    </style>
</head>
<body class="font-sans antialiased theme-transition" style="background-color: var(--bg-primary); color: var(--text-primary);">
    <div class="page-container">
        <!-- Navigation Bar -->
        <nav class="shadow-sm border-b theme-transition" style="background-color: var(--bg-secondary); border-color: var(--border-color);">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="<?php echo e(route('home')); ?>" class="flex items-center">
                            <i class="fas fa-kiwi-bird text-2xl mr-2" style="color: var(--accent-color);"></i>
                            <span class="text-xl font-bold" style="color: var(--text-primary);">Munchkly</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4">
                        <!-- Theme Toggle -->
                        <div class="flex items-center mr-2">
                            <span class="text-xs mr-2" style="color: var(--text-secondary);">Dark</span>
                            <div class="theme-toggle" id="themeToggle" onclick="toggleTheme()"></div>
                            <span class="text-xs ml-2" style="color: var(--text-secondary);">Light</span>
                        </div>
                        
                        <?php if($isAuth && $authUser): ?>
                            <a href="<?php echo e(route('profile.show', $authUser->id)); ?>" class="px-3 py-2 rounded-md text-sm font-medium theme-transition" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                                <i class="fas fa-user mr-1"></i>Profile
                            </a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium theme-transition" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                                    <i class="fas fa-sign-out-alt mr-1"></i>Logout
                                </button>
                            </form>
                            <span class="text-sm" style="color: var(--text-secondary);">Hi, <?php echo e($authUser->name); ?>!</span>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="px-3 py-2 rounded-md text-sm font-medium theme-transition" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                                <i class="fas fa-sign-in-alt mr-1"></i>Login
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <div class="content-wrap">
            <!-- Flash Messages -->
            <?php if(session('success')): ?>
                <div class="max-w-4xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="max-w-4xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo e(session('error')); ?></span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('info')): ?>
                <div class="max-w-4xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo e(session('info')); ?></span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Main Content -->
            <main class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>

        <!-- Footer (only on login/register pages) -->
        <?php if(in_array(request()->route()->getName(), ['login', 'register'])): ?>
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
                            <li><a href="<?php echo e(route('home')); ?>" class="text-gray-300 hover:text-blue-400 transition-colors">Home</a></li>
                            <?php if($isAuth && $authUser): ?>
                                <li><a href="<?php echo e(route('profile.show', $authUser->id)); ?>" class="text-gray-300 hover:text-blue-400 transition-colors">Profile</a></li>
                            <?php endif; ?>
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
        <?php endif; ?>
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

        // Dark Theme Toggle Functionality
        function toggleTheme() {
            const html = document.documentElement;
            const toggle = document.getElementById('themeToggle');
            
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                toggle.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                toggle.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const html = document.documentElement;
            const toggle = document.getElementById('themeToggle');
            
            // Determine if we should use dark mode
            const shouldUseDark = savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            if (shouldUseDark) {
                html.classList.add('dark');
                if (toggle) toggle.classList.add('dark');
            } else {
                html.classList.remove('dark');
                if (toggle) toggle.classList.remove('dark');
            }
        });

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
</html><?php /**PATH C:\Users\Jherilyn\MIDTERM_ FORTES\resources\views/layouts/app.blade.php ENDPATH**/ ?>