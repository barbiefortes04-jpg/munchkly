<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display the specified user's profile.
     */
    public function show($id)
    {
        // Get current authenticated user (optional)
        $authUser = session('auth_user');

        // Get user data from file storage
        $users = $this->getUsers();
        $user = collect($users)->where('id', (int) $id)->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found.');
        }

        // Convert to object for template compatibility
        $user = (object) [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'profile_picture' => $user['profile_picture'] ?? null,
            'cover_photo' => $user['cover_photo'] ?? null,
            'created_at' => \Carbon\Carbon::parse($user['created_at'])
        ];

        // Get user's tweets
        $tweets = collect($this->getTweets())
            ->where('user.id', (int) $id)
            ->sortByDesc('created_at')
            ->map(function ($tweet) {
                $tweet = (object) $tweet;
                $tweet->user = (object) $tweet->user;
                $tweet->likes_count = count($tweet->likes ?? []);
                
                // Set time formatting
                if (isset($tweet->created_at)) {
                    $createdAt = \Carbon\Carbon::parse($tweet->created_at);
                    $tweet->time_ago = $createdAt->diffForHumans();
                    $tweet->formatted_created_at = $createdAt->format('M d, Y');
                } else {
                    $tweet->time_ago = 'Just now';
                    $tweet->formatted_created_at = now()->format('M d, Y');
                }
                
                $tweet->is_edited = $tweet->is_edited ?? false;
                return $tweet;
            });

        $tweetCount = $tweets->count();
        $totalLikesReceived = $tweets->sum('likes_count');
        
        // Get follow stats
        $follows = $this->getFollows();
        $followersCount = collect($follows)->where('following_id', (int) $id)->count();
        $followingCount = collect($follows)->where('follower_id', (int) $id)->count();
        
        // Check if current user is following this profile
        $isFollowing = false;
        $isCloseFriend = false;
        if ($authUser) {
            $currentUserId = is_array($authUser) ? $authUser['id'] : $authUser->id;
            $isFollowing = collect($follows)->where('follower_id', $currentUserId)
                                          ->where('following_id', (int) $id)
                                          ->isNotEmpty();
            
            // Check close friend status
            $closeFriends = [];
            if (Storage::exists('close_friends.json')) {
                $closeFriends = json_decode(Storage::get('close_friends.json'), true) ?: [];
            }
            $isCloseFriend = collect($closeFriends)->where('user_id', $currentUserId)
                                                  ->where('close_friend_id', (int) $id)
                                                  ->isNotEmpty();
        }
        
        // Get followers list
        $followerIds = collect($follows)->where('following_id', (int) $id)->pluck('follower_id');
        $followers = collect($users)->whereIn('id', $followerIds)->map(function($follower) {
            return (object) [
                'id' => $follower['id'],
                'name' => $follower['name'],
                'profile_picture' => $follower['profile_picture'] ?? null
            ];
        });

        // Pass authentication state to view
        $isAuth = $authUser !== null;

        return view('profile.show', compact('user', 'tweets', 'tweetCount', 'totalLikesReceived', 'isAuth', 'authUser', 'followersCount', 'followingCount', 'isFollowing', 'isCloseFriend', 'followers'));
    }

    /**
     * Upload cover photo
     */
    public function uploadCoverPhoto(Request $request)
    {
        if (!session('auth_user')) {
            return redirect()->route('login')->with('error', 'Please log in to upload a cover photo.');
        }

        $request->validate([
            'cover_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $authUser = session('auth_user');
        $userId = is_array($authUser) ? $authUser['id'] : $authUser->id;

        if ($request->hasFile('cover_photo')) {
            $image = $request->file('cover_photo');
            $imageName = 'cover_' . time() . '_' . $userId . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path('storage/cover_photos'))) {
                mkdir(public_path('storage/cover_photos'), 0755, true);
            }
            
            // Move the file to public storage
            $image->move(public_path('storage/cover_photos'), $imageName);

            // Update user data in file storage
            $users = $this->getUsers();
            $userIndex = collect($users)->search(function ($user) use ($userId) {
                return $user['id'] == $userId;
            });

            if ($userIndex !== false) {
                // Delete old cover photo if exists
                if (isset($users[$userIndex]['cover_photo']) && $users[$userIndex]['cover_photo']) {
                    $oldImagePath = public_path('storage/cover_photos/' . $users[$userIndex]['cover_photo']);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $users[$userIndex]['cover_photo'] = $imageName;
                Storage::put('users.json', json_encode($users, JSON_PRETTY_PRINT));
                
                // Update session data
                $authUser['cover_photo'] = $imageName;
                session(['auth_user' => $authUser]);
            }
        }

        return redirect()->route('profile.show', $userId)->with('success', 'Cover photo updated successfully!');
    }

    /**
     * Upload profile picture
     */
    public function uploadProfilePicture(Request $request)
    {
        if (!session('auth_user')) {
            return redirect()->route('login')->with('error', 'Please log in to upload a profile picture.');
        }

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $authUser = session('auth_user');
        $userId = is_array($authUser) ? $authUser['id'] : $authUser->id;

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = time() . '_' . $userId . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path('storage/profile_pictures'))) {
                mkdir(public_path('storage/profile_pictures'), 0755, true);
            }
            
            // Move the file to public storage
            $image->move(public_path('storage/profile_pictures'), $imageName);

            // Update user data in file storage
            $users = $this->getUsers();
            $userIndex = collect($users)->search(function ($user) use ($userId) {
                return $user['id'] == $userId;
            });

            if ($userIndex !== false) {
                // Delete old profile picture if exists
                if (isset($users[$userIndex]['profile_picture']) && $users[$userIndex]['profile_picture']) {
                    $oldImagePath = public_path('storage/profile_pictures/' . $users[$userIndex]['profile_picture']);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $users[$userIndex]['profile_picture'] = $imageName;
                Storage::put('users.json', json_encode($users, JSON_PRETTY_PRINT));
                
                // Update session data
                $authUser['profile_picture'] = $imageName;
                session(['auth_user' => $authUser]);
            }
        }

        return redirect()->route('profile.show', $userId)->with('success', 'Profile picture updated successfully!');
    }

    /**     * Toggle close friend status
     */
    public function toggleCloseFriend(Request $request, $id)
    {
        if (!session('auth_user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $authUser = session('auth_user');
        $currentUserId = is_array($authUser) ? $authUser['id'] : $authUser->id;
        
        // Can't add yourself as close friend
        if ($currentUserId == $id) {
            return response()->json(['error' => 'Cannot add yourself as close friend'], 400);
        }

        // Get close friends data
        $closeFriendsFile = 'close_friends.json';
        $closeFriends = [];
        if (Storage::exists($closeFriendsFile)) {
            $closeFriends = json_decode(Storage::get($closeFriendsFile), true) ?: [];
        }

        // Check if already close friends
        $existingIndex = collect($closeFriends)->search(function ($friendship) use ($currentUserId, $id) {
            return $friendship['user_id'] == $currentUserId && $friendship['close_friend_id'] == $id;
        });

        if ($existingIndex !== false) {
            // Remove from close friends
            array_splice($closeFriends, $existingIndex, 1);
            $isCloseFriend = false;
        } else {
            // Add to close friends
            $closeFriends[] = [
                'user_id' => $currentUserId,
                'close_friend_id' => (int) $id,
                'created_at' => now()->toISOString()
            ];
            $isCloseFriend = true;
        }

        Storage::put($closeFriendsFile, json_encode($closeFriends, JSON_PRETTY_PRINT));

        return response()->json([
            'is_close_friend' => $isCloseFriend,
            'message' => $isCloseFriend ? 'Added to close friends' : 'Removed from close friends'
        ]);
    }

    /**     * Get users from file storage
     */
    private function getUsers()
    {
        if (!Storage::exists('users.json')) {
            return [];
        }
        
        return json_decode(Storage::get('users.json'), true) ?: [];
    }

    /**
     * Get follows from file storage
     */
    private function getFollows()
    {
        if (!Storage::exists('follows.json')) {
            return [];
        }
        
        return json_decode(Storage::get('follows.json'), true) ?: [];
    }

    /**
     * Get tweets from file storage
     */
    private function getTweets()
    {
        if (!Storage::exists('tweets.json')) {
            return [];
        }
        
        return json_decode(Storage::get('tweets.json'), true) ?: [];
    }
}