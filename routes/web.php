<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// Welcome/Homepage routes
Route::get('/', function () {
    if (session('user_id')) {
        return redirect()->route('home');
    }
    return view('welcome');
})->name('welcome');

Route::get('/home', [TweetController::class, 'index'])->name('home');
Route::get('/search', [TweetController::class, 'search'])->name('search');

// Authentication routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Tweet routes  
Route::post('/tweets', [TweetController::class, 'store'])->name('tweets.store');
Route::get('/tweets/{tweet}/edit', [TweetController::class, 'edit'])->name('tweets.edit');
Route::put('/tweets/{tweet}', [TweetController::class, 'update'])->name('tweets.update');
Route::delete('/tweets/{tweet}', [TweetController::class, 'destroy'])->name('tweets.destroy');

// Like routes
Route::post('/tweets/{id}/like', [TweetController::class, 'toggleLike'])->name('tweets.like');
Route::post('/tweets/{id}/share', [TweetController::class, 'share'])->name('tweets.share');

// User profile routes
Route::get('/profile/{id}', [UserController::class, 'show'])->name('profile.show');
Route::post('/profile/upload-picture', [UserController::class, 'uploadProfilePicture'])->name('profile.upload-picture');
Route::post('/profile/upload-cover', [UserController::class, 'uploadCoverPhoto'])->name('profile.upload-cover');

// Follow routes
Route::post('/follow/{id}', [FollowController::class, 'toggle'])->name('follow.toggle');
Route::post('/close-friend/{id}', [UserController::class, 'toggleCloseFriend'])->name('close-friend.toggle');

// Temporary debug route to view live database data
Route::get('/debug-db', function () {
    if (!session('user_id')) {
        return 'Access denied. Please login first.';
    }
    
    $data = [
        'users_count' => \App\Models\User::count(),
        'tweets_count' => \App\Models\Tweet::count(),
        'likes_count' => \App\Models\Like::count(),
        'follows_count' => \App\Models\Follow::count(),
        'latest_users' => \App\Models\User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']),
        'latest_tweets' => \App\Models\Tweet::latest()->take(5)->with('user')->get(['id', 'content', 'user_id', 'created_at']),
    ];
    
    return '<pre>' . json_encode($data, JSON_PRETTY_PRINT) . '</pre>';
})->name('debug.db');