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
            <div class="relative bg-white rounded-2xl p-10 text-center shadow-2xl">
                <div class="mb-8">
                    <i class="fas fa-kiwi-bird text-blue-500 text-6xl"></i>
                </div>
                <h2 class="text-4xl font-bold text-blue-600 mb-6">Welcome to Munchkly!</h2>
                <p class="text-gray-600 text-xl leading-relaxed">Join the conversation and share your thoughts with the world.</p>
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
@endsection