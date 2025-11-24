@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto rounded-lg shadow p-6 theme-transition" style="background-color: var(--bg-secondary);">
        <div class="text-center mb-6">
            <i class="fas fa-kiwi-bird text-4xl mb-4" style="color: var(--accent-color);"></i>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Join Munchkly</h2>
            <p class="mt-2" style="color: var(--text-secondary);">Create your account and start sharing your thoughts</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Full Name</label>
                <input 
                    id="name" 
                    name="name" 
                    type="text" 
                    value="{{ old('name') }}"
                    required 
                    autocomplete="name"
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 theme-transition @error('name') border-red-500 @enderror"
                    style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color); --tw-ring-color: var(--accent-color);"
                    placeholder="Enter your full name"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Email Address</label>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    value="{{ old('email') }}"
                    required 
                    autocomplete="email"
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 theme-transition @error('email') border-red-500 @enderror"
                    style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color); --tw-ring-color: var(--accent-color);"
                    placeholder="Enter your email address"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Password</label>
                <input 
                    id="password" 
                    name="password" 
                    type="password" 
                    required 
                    autocomplete="new-password"
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 theme-transition @error('password') border-red-500 @enderror"
                    style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color); --tw-ring-color: var(--accent-color);"
                    placeholder="Enter your password (min. 8 characters)"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Confirm Password</label>
                <input 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    required 
                    autocomplete="new-password"
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 theme-transition"
                    style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color); --tw-ring-color: var(--accent-color);"
                    placeholder="Confirm your password"
                >
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button 
                    type="submit" 
                    class="w-full text-white font-medium py-2 px-4 rounded-md transition-colors duration-200"
                    style="background-color: var(--accent-color);"
                    onmouseover="this.style.opacity='0.9'"
                    onmouseout="this.style.opacity='1'"
                >
                    <i class="fas fa-user-plus mr-2"></i>Create Account
                </button>
            </div>
        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <p class="text-sm" style="color: var(--text-secondary);">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-medium transition-colors" style="color: var(--accent-color);" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">Sign in here</a>
            </p>
        </div>
    </div>
@endsection