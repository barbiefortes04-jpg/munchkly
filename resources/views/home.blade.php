@extends('layouts.app')

@section('content')
    <!-- Interactive Keyboard Background -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900/10 via-slate-800/5 to-gray-900/10"></div>
        
        <!-- Keyboard Layout -->
        <div class="keyboard-container">
            <!-- Row 1 -->
            <div class="keyboard-row row-1">
                <div class="key key-php" title="PHP 8.2">
                    <div class="key-logo">PHP</div>
                </div>
                <div class="key key-laravel" title="Laravel 10">
                    <div class="key-logo">🔥</div>
                </div>
                <div class="key key-blade" title="Blade Templates">
                    <div class="key-logo">@</div>
                </div>
                <div class="key key-composer" title="Composer">
                    <div class="key-logo">🎼</div>
                </div>
                <div class="key key-artisan" title="Artisan CLI">
                    <div class="key-logo">⚡</div>
                </div>
            </div>
            
            <!-- Row 2 -->
            <div class="keyboard-row row-2">
                <div class="key key-html" title="HTML5">
                    <div class="key-logo">HTML</div>
                </div>
                <div class="key key-css" title="CSS3">
                    <div class="key-logo">CSS</div>
                </div>
                <div class="key key-js" title="JavaScript">
                    <div class="key-logo">JS</div>
                </div>
                <div class="key key-tailwind" title="Tailwind CSS">
                    <div class="key-logo">🎨</div>
                </div>
                <div class="key key-fontawesome" title="FontAwesome">
                    <div class="key-logo">📱</div>
                </div>
            </div>
            
            <!-- Row 3 -->
            <div class="keyboard-row row-3">
                <div class="key key-ajax" title="AJAX">
                    <div class="key-logo">⚡</div>
                </div>
                <div class="key key-json" title="JSON">
                    <div class="key-logo">{}</div>
                </div>
                <div class="key key-session" title="Sessions">
                    <div class="key-logo">🔐</div>
                </div>
                <div class="key key-carbon" title="Carbon">
                    <div class="key-logo">📅</div>
                </div>
                <div class="key key-bcrypt" title="Bcrypt">
                    <div class="key-logo">🔒</div>
                </div>
            </div>
            
            <!-- Row 4 (Spacebar Row) -->
            <div class="keyboard-row row-4">
                <div class="key key-mvc" title="MVC Pattern">
                    <div class="key-logo">MVC</div>
                </div>
                <div class="key key-spacebar" title="Munchkly - Twitter Clone">
                    <div class="key-logo">🐦 MUNCHKLY</div>
                </div>
                <div class="key key-crud" title="CRUD Operations">
                    <div class="key-logo">CRUD</div>
                </div>
            </div>
        </div>
        
        <!-- Floating Code Snippets -->
        <div class="code-snippet snippet-1">&lt;?php</div>
        <div class="code-snippet snippet-2">@extends</div>
        <div class="code-snippet snippet-3">Route::</div>
        <div class="code-snippet snippet-4">.blade.php</div>
        <div class="code-snippet snippet-5">artisan</div>
    </div>

    <div class="relative z-10 space-y-6">
        <!-- Tweet Creation Form (only for authenticated users) -->
        @if($isAuth && $authUser)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-feather-alt text-blue-500 mr-2"></i>What's on your mind?
                </h2>
                
                <form method="POST" action="{{ route('tweets.store') }}" class="space-y-4">
                    @csrf
                    
                    <div>
                        <textarea 
                            name="content" 
                            id="tweet-content"
                            rows="4" 
                            maxlength="280"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"
                            placeholder="Share your thoughts with the world..."
                            oninput="updateCharCounter(this, document.getElementById('char-count'))"
                            required
                        >{{ old('content') }}</textarea>
                        
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <div class="flex justify-between items-center mt-2">
                            <span id="char-count" class="text-gray-500 text-sm">280</span>
                            <button 
                                type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white px-6 py-2 rounded-md font-medium transition-colors duration-200"
                            >
                                <i class="fas fa-paper-plane mr-2"></i>Post Tweet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <!-- Guest Welcome Message -->
            <div class="relative bg-white/90 backdrop-blur-md border border-blue-200/30 rounded-2xl p-10 text-center shadow-2xl">
                <div class="animate-bounce mb-8">
                    <i class="fas fa-kiwi-bird text-blue-500 text-6xl"></i>
                </div>
                <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-6 animate-fade-in">Welcome to Munchkly!</h2>
                <p class="text-gray-600 text-xl leading-relaxed animate-fade-in-delay">Join the conversation and share your thoughts with the world.</p>
            </div>
        @endif
    </div>

    <script>
        function updateCharCounter(textarea, counter) {
            const remaining = 280 - textarea.value.length;
            counter.textContent = remaining;
            
            if (remaining < 0) {
                counter.classList.add('text-red-500');
                counter.classList.remove('text-gray-500');
            } else {
                counter.classList.add('text-gray-500');
                counter.classList.remove('text-red-500');
            }
        }
    </script>

    <style>
        /* Keyboard Background Animations */
        @keyframes key-press {
            0%, 100% {
                transform: translateY(0) scale(1);
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            50% {
                transform: translateY(-2px) scale(1.05);
                box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            }
        }

        @keyframes float-code {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.3;
            }
            50% {
                transform: translateY(-30px) rotate(5deg);
                opacity: 0.7;
            }
        }

        @keyframes glow {
            0%, 100% {
                text-shadow: 0 0 5px currentColor;
            }
            50% {
                text-shadow: 0 0 15px currentColor, 0 0 25px currentColor;
            }
        }

        @keyframes fade-in {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in-delay {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            50% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Keyboard Container */
        .keyboard-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.15;
            scale: 1.5;
        }

        .keyboard-row {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        /* Key Styles */
        .key {
            width: 60px;
            height: 60px;
            background: linear-gradient(145deg, #f8f9fa, #e9ecef);
            border: 2px solid #dee2e6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            color: #495057;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            animation: key-press 4s infinite ease-in-out;
        }

        .key-logo {
            text-align: center;
            line-height: 1;
        }

        /* Technology-specific colors */
        .key-php {
            background: linear-gradient(145deg, #8993be, #4f5b93);
            color: white;
            animation-delay: 0s;
        }

        .key-laravel {
            background: linear-gradient(145deg, #ff2d20, #e3342f);
            color: white;
            animation-delay: 0.5s;
        }

        .key-blade {
            background: linear-gradient(145deg, #ff6b35, #e55d2b);
            color: white;
            animation-delay: 1s;
        }

        .key-composer {
            background: linear-gradient(145deg, #885630, #6d4423);
            color: white;
            animation-delay: 1.5s;
        }

        .key-artisan {
            background: linear-gradient(145deg, #f39c12, #e67e22);
            color: white;
            animation-delay: 2s;
        }

        .key-html {
            background: linear-gradient(145deg, #e34c26, #c7341a);
            color: white;
            animation-delay: 0.2s;
        }

        .key-css {
            background: linear-gradient(145deg, #1572b6, #0f5788);
            color: white;
            animation-delay: 0.7s;
        }

        .key-js {
            background: linear-gradient(145deg, #f7df1e, #dcc218);
            color: #323330;
            animation-delay: 1.2s;
        }

        .key-tailwind {
            background: linear-gradient(145deg, #06b6d4, #0891b2);
            color: white;
            animation-delay: 1.7s;
        }

        .key-fontawesome {
            background: linear-gradient(145deg, #339af0, #228be6);
            color: white;
            animation-delay: 2.2s;
        }

        .key-ajax {
            background: linear-gradient(145deg, #61dafb, #21d4fd);
            color: #20232a;
            animation-delay: 0.4s;
        }

        .key-json {
            background: linear-gradient(145deg, #000000, #2d2d2d);
            color: #f8f9fa;
            animation-delay: 0.9s;
        }

        .key-session {
            background: linear-gradient(145deg, #28a745, #1e7e34);
            color: white;
            animation-delay: 1.4s;
        }

        .key-carbon {
            background: linear-gradient(145deg, #6f42c1, #5a2d91);
            color: white;
            animation-delay: 1.9s;
        }

        .key-bcrypt {
            background: linear-gradient(145deg, #dc3545, #bd2130);
            color: white;
            animation-delay: 2.4s;
        }

        .key-mvc {
            background: linear-gradient(145deg, #17a2b8, #138496);
            color: white;
            animation-delay: 0.6s;
        }

        .key-crud {
            background: linear-gradient(145deg, #6610f2, #520dc2);
            color: white;
            animation-delay: 2.6s;
        }

        .key-spacebar {
            width: 200px;
            background: linear-gradient(145deg, #007bff, #0056b3);
            color: white;
            font-size: 14px;
            font-weight: 900;
            animation-delay: 1.1s;
        }

        /* Floating Code Snippets */
        .code-snippet {
            position: absolute;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: bold;
            color: rgba(55, 65, 81, 0.3);
            animation: float-code 8s infinite ease-in-out;
        }

        .snippet-1 {
            top: 20%;
            left: 10%;
            color: rgba(139, 147, 190, 0.4);
            animation-delay: 0s;
        }

        .snippet-2 {
            top: 30%;
            right: 15%;
            color: rgba(255, 107, 53, 0.4);
            animation-delay: 2s;
        }

        .snippet-3 {
            bottom: 35%;
            left: 15%;
            color: rgba(255, 45, 32, 0.4);
            animation-delay: 4s;
        }

        .snippet-4 {
            bottom: 25%;
            right: 10%;
            color: rgba(255, 107, 53, 0.4);
            animation-delay: 6s;
        }

        .snippet-5 {
            top: 60%;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(243, 156, 18, 0.4);
            animation-delay: 1s;
        }

        /* Text Animations */
        .animate-fade-in {
            animation: fade-in 1.5s ease-out forwards;
        }

        .animate-fade-in-delay {
            animation: fade-in-delay 2s ease-out forwards;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .keyboard-container {
                scale: 0.8;
                opacity: 0.1;
            }
            
            .key {
                width: 40px;
                height: 40px;
                font-size: 10px;
            }
            
            .key-spacebar {
                width: 120px;
                font-size: 10px;
            }
        }
    </style>
@endsection