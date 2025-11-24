@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center text-blue-500 hover:text-blue-600 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Home
            </a>
        </div>

        <!-- Edit Tweet Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">
                <i class="fas fa-edit text-blue-500 mr-2"></i>Edit Tweet
            </h2>

            <form method="POST" action="{{ route('tweets.update', $tweet->id) }}" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Tweet Content</label>
                    <textarea 
                        name="content" 
                        id="content"
                        rows="4" 
                        maxlength="280"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none @error('content') border-red-500 @enderror"
                        placeholder="What's on your mind?"
                        oninput="updateCharCounter(this, document.getElementById('char-count'))"
                        required
                    >{{ old('content', $tweet->content) }}</textarea>
                    
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <div class="flex justify-between items-center mt-2">
                        <span id="char-count" class="text-gray-500 text-sm">{{ 280 - strlen(old('content', $tweet->content)) }}</span>
                        <div class="space-x-3">
                            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md font-medium">
                                Cancel
                            </a>
                            <button 
                                type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-medium transition-colors duration-200"
                            >
                                <i class="fas fa-save mr-2"></i>Update Tweet
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Original Tweet Preview -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Original Tweet:</h3>
                <p class="text-gray-900">{{ $tweet->content }}</p>
                <p class="text-sm text-gray-500 mt-2">Posted {{ $tweet->time_ago }}</p>
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