@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto space-y-6">
        
        @if($isAuth && $authUser)
            <!-- Tweet Creation Form -->
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"
                            placeholder="Share your thoughts with the world..."
                            oninput="updateCharCounter(this)"
                            required
                        >{{ old('content') }}</textarea>
                        
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <div class="flex justify-between items-center mt-2">
                            <span id="char-count" class="text-gray-500 text-sm">280</span>
                            <button 
                                type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-medium"
                            >
                                <i class="fas fa-paper-plane mr-2"></i>Post Tweet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <!-- Guest Welcome Message -->
            <div class="bg-white rounded-lg shadow p-10 text-center">
                <div class="mb-6">
                    <i class="fas fa-kiwi-bird text-blue-500 text-5xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Welcome to Munchkly!</h2>
                <p class="text-gray-600 text-lg mb-6">Join the conversation and share your thoughts with the world.</p>
                <div class="space-x-4">
                    <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-medium inline-block">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md font-medium inline-block">
                        Sign In
                    </a>
                </div>
            </div>
        @endif
        
    </div>
</div>

<script>
function updateCharCounter(textarea) {
    const remaining = 280 - textarea.value.length;
    const counter = document.getElementById('char-count');
    counter.textContent = remaining;
    
    if (remaining < 0) {
        counter.className = 'text-red-500 text-sm';
    } else {
        counter.className = 'text-gray-500 text-sm';
    }
}
</script>
@endsection