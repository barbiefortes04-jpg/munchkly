@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center font-medium transition-colors" style="color: var(--accent-color);" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                <i class="fas fa-arrow-left mr-2"></i>Back to Home
            </a>
        </div>

        <!-- Edit Tweet Form -->
        <div class="rounded-lg shadow p-6 theme-transition" style="background-color: var(--bg-secondary);">
            <h2 class="text-xl font-semibold mb-6" style="color: var(--text-primary);">
                <i class="fas fa-edit mr-2" style="color: var(--accent-color);"></i>Edit Tweet
            </h2>

            <form method="POST" action="{{ route('tweets.update', $tweet->id) }}" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="content" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Tweet Content</label>
                    <textarea 
                        name="content" 
                        id="content"
                        rows="4" 
                        maxlength="280"
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 resize-none theme-transition @error('content') border-red-500 @enderror"
                        style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color); --tw-ring-color: var(--accent-color);"
                        placeholder="What's on your mind?"
                        oninput="updateCharCounter(this, document.getElementById('char-count'))"
                        required
                    >{{ old('content', $tweet->content) }}</textarea>
                    
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <div class="flex justify-between items-center mt-2">
                        <span id="char-count" class="text-sm" style="color: var(--text-secondary);">{{ 280 - strlen(old('content', $tweet->content)) }}</span>
                        <div class="space-x-3">
                            <a href="{{ route('home') }}" class="px-4 py-2 rounded-md font-medium transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                                Cancel
                            </a>
                            <button 
                                type="submit" 
                                class="text-white px-6 py-2 rounded-md font-medium transition-colors duration-200"
                                style="background-color: var(--accent-color);"
                                onmouseover="this.style.opacity='0.9'"
                                onmouseout="this.style.opacity='1'"
                            >
                                <i class="fas fa-save mr-2"></i>Update Tweet
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Original Tweet Preview -->
            <div class="mt-8 p-4 rounded-lg theme-transition" style="background-color: var(--bg-primary);">
                <h3 class="text-sm font-medium mb-2" style="color: var(--text-primary);">Original Tweet:</h3>
                <p style="color: var(--text-primary);">{{ $tweet->content }}</p>
                <p class="text-sm mt-2" style="color: var(--text-secondary);">Posted {{ $tweet->time_ago }}</p>
            </div>
        </div>
    </div>

    <script>
        // Initialize character counter on page load
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('content');
            const counter = document.getElementById('char-count');
            updateCharCounter(textarea, counter);
        });
    </script>
@endsection