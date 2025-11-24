@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto rounded-lg shadow p-6 theme-transition" style="background-color: var(--bg-secondary);">
        <div class="text-center mb-6">
            <i class="fas fa-kiwi-bird text-4xl mb-4" style="color: var(--accent-color);"></i>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Welcome Back</h2>
            <p class="mt-2" style="color: var(--text-secondary);">Sign in to your Munchkly account</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

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
                    autofocus
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
                    autocomplete="current-password"
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 theme-transition @error('password') border-red-500 @enderror"
                    style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color); --tw-ring-color: var(--accent-color);"
                    placeholder="Enter your password"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input 
                    id="remember" 
                    name="remember" 
                    type="checkbox" 
                    class="h-4 w-4 rounded focus:ring-2" 
                    style="accent-color: var(--accent-color); --tw-ring-color: var(--accent-color);"
                >
                <label for="remember" class="ml-2 block text-sm" style="color: var(--text-primary);">
                    Remember me
                </label>
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
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
            </div>
        </form>

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <p class="text-sm" style="color: var(--text-secondary);">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-medium transition-colors" style="color: var(--accent-color);" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">Create one here</a>
            </p>
        </div>
    </div>
@endsection