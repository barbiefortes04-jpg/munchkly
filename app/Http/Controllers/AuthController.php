<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Check if user already exists in file storage
        $users = $this->getUsers();
        if (collect($users)->where('email', $validated['email'])->first()) {
            return back()->withErrors(['email' => 'This email is already registered.']);
        }

        // Create new user
        $user = [
            'id' => count($users) + 1,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'created_at' => now()->toISOString(),
        ];

        // Save to file
        $users[] = $user;
        Storage::put('users.json', json_encode($users, JSON_PRETTY_PRINT));

        // Create session for the user
        session([
            'auth_user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
            ]
        ]);

        return redirect()->route('home')->with('success', 'Welcome to Munchkly! Your account has been created successfully!');
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Find user in file storage
        $users = $this->getUsers();
        $user = collect($users)->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user['password'])) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        // Create session for the user
        session([
            'auth_user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
            ]
        ]);

        $request->session()->regenerate();
        
        return redirect()->route('home')->with('success', 'Welcome back to Munchkly!');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        session()->forget('auth_user');
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('welcome')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Get users from file storage
     */
    private function getUsers()
    {
        if (!Storage::exists('users.json')) {
            return [];
        }
        
        return json_decode(Storage::get('users.json'), true) ?: [];
    }
}