<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TweetController extends Controller
{
    /**
     * Display homepage (without tweets display).
     */
    public function index()
    {
        // Pass authentication data for the view
        $isAuth = session('auth_user') !== null;
        $authUser = session('auth_user');
        
        // Get all tweets for display
        $tweets = collect($this->getTweets())
            ->filter(function ($tweet) use ($authUser) {
                // Always show public tweets
                if (!isset($tweet['privacy']) || $tweet['privacy'] === 'public') {
                    return true;
                }
                
                // Hide private tweets if user is not logged in
                if (!$authUser) {
                    return false;
                }
                
                $currentUserId = is_array($authUser) ? $authUser['id'] : $authUser->id;
                
                // Always show own tweets
                if ($tweet['user']['id'] == $currentUserId) {
                    return true;
                }
                
                // Handle followers-only tweets
                if ($tweet['privacy'] === 'followers') {
                    return $this->isUserFollowing($currentUserId, $tweet['user']['id']);
                }
                
                // Handle close friends tweets
                if ($tweet['privacy'] === 'close_friends') {
                    return $this->isCloseFriend($currentUserId, $tweet['user']['id']);
                }
                
                return false;
            })
            ->sortByDesc('created_at')
            ->map(function ($tweet) use ($authUser) {
                $tweet = (object) $tweet;
                $tweet->user = (object) $tweet->user;
                $tweet->likes_count = count($tweet->likes ?? []);
                
                // Set time formatting
                if (isset($tweet->created_at)) {
                    $createdAt = \Carbon\Carbon::parse($tweet->created_at);
                    $tweet->time_ago = $createdAt->diffForHumans();
                    $tweet->formatted_created_at = $createdAt->format('M d, Y \a\t g:i A');
                } else {
                    $tweet->time_ago = 'Just now';
                    $tweet->formatted_created_at = now()->format('M d, Y \a\t g:i A');
                }
                
                // Check if current user has liked this tweet
                $tweet->user_has_liked = false;
                if ($authUser && isset($tweet->likes)) {
                    foreach ($tweet->likes as $like) {
                        if ((is_array($authUser) ? $authUser['id'] : $authUser->id) == $like['user_id']) {
                            $tweet->user_has_liked = true;
                            break;
                        }
                    }
                }
                
                $tweet->is_edited = $tweet->is_edited ?? false;
                return $tweet;
            });
        
        return view('home_basic', compact('isAuth', 'authUser', 'tweets'));
    }

    /**
     * Store a new tweet.
     */
    public function store(Request $request)
    {
        if (!session('auth_user')) {
            return redirect()->route('login')->with('error', 'Please log in to post tweets.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:280',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max per image
            'video' => 'nullable|mimes:mp4,avi,mov,wmv,flv|max:51200', // 50MB max for video
            'privacy' => 'required|in:public,followers,close_friends'
        ]);

        $user = session('auth_user');
        $tweets = $this->getTweets();
        $tweetId = count($tweets) + 1;

        // Handle photo uploads
        $photoUrls = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $fileName = 'tweet_' . $tweetId . '_photo_' . ($index + 1) . '_' . time() . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('tweet_media/photos', $fileName, 'public');
                $photoUrls[] = $fileName;
            }
        }

        // Handle video upload
        $videoUrl = null;
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $fileName = 'tweet_' . $tweetId . '_video_' . time() . '.' . $video->getClientOriginalExtension();
            $video->storeAs('tweet_media/videos', $fileName, 'public');
            $videoUrl = $fileName;
        }

        $tweet = [
            'id' => $tweetId,
            'content' => $validated['content'],
            'user' => $user,
            'photos' => $photoUrls,
            'video' => $videoUrl,
            'privacy' => $validated['privacy'],
            'likes' => [],
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString(),
        ];

        $tweets[] = $tweet;
        Storage::put('tweets.json', json_encode($tweets, JSON_PRETTY_PRINT));

        return redirect()->route('home')->with('success', 'Tweet posted successfully!');
    }

    /**
     * Show the form for editing the specified tweet.
     */
    public function edit($id)
    {
        if (!session('auth_user')) {
            return redirect()->route('login')->with('error', 'Please log in to edit tweets.');
        }

        $user = session('auth_user');
        $tweets = $this->getTweets();
        $tweetIndex = collect($tweets)->search(function ($tweet) use ($id) {
            return $tweet['id'] == $id;
        });

        if ($tweetIndex === false) {
            return redirect()->route('home')->with('error', 'Tweet not found.');
        }

        $tweet = (object) $tweets[$tweetIndex];
        
        // Add time_ago property for the edit view
        if (isset($tweet->created_at)) {
            $createdAt = \Carbon\Carbon::parse($tweet->created_at);
            $tweet->time_ago = $createdAt->diffForHumans();
        } else {
            $tweet->time_ago = 'Just now';
        }
        
        if ($tweet->user['id'] != $user['id']) {
            return redirect()->route('home')->with('error', 'You can only edit your own tweets.');
        }

        return view('tweets.edit', compact('tweet'));
    }

    /**
     * Update the specified tweet in storage.
     */
    public function update(Request $request, $id)
    {
        if (!session('auth_user')) {
            return redirect()->route('login')->with('error', 'Please log in to update tweets.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $user = session('auth_user');
        $tweets = $this->getTweets();
        $tweetIndex = collect($tweets)->search(function ($tweet) use ($id) {
            return $tweet['id'] == $id;
        });

        if ($tweetIndex === false) {
            return redirect()->route('home')->with('error', 'Tweet not found.');
        }

        if ($tweets[$tweetIndex]['user']['id'] != $user['id']) {
            return redirect()->route('home')->with('error', 'You can only edit your own tweets.');
        }

        $tweets[$tweetIndex]['content'] = $validated['content'];
        $tweets[$tweetIndex]['is_edited'] = true;
        $tweets[$tweetIndex]['updated_at'] = now()->toISOString();

        Storage::put('tweets.json', json_encode($tweets, JSON_PRETTY_PRINT));

        return redirect()->route('home')->with('success', 'Tweet updated successfully!');
    }

    /**
     * Remove the specified tweet from storage.
     */
    public function destroy($id)
    {
        if (!session('auth_user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = session('auth_user');
        $tweets = $this->getTweets();
        $tweetIndex = collect($tweets)->search(function ($tweet) use ($id) {
            return $tweet['id'] == $id;
        });

        if ($tweetIndex === false) {
            return response()->json(['error' => 'Tweet not found'], 404);
        }

        if ($tweets[$tweetIndex]['user']['id'] != $user['id']) {
            return response()->json(['error' => 'You can only delete your own tweets'], 403);
        }

        array_splice($tweets, $tweetIndex, 1);
        Storage::put('tweets.json', json_encode($tweets, JSON_PRETTY_PRINT));

        return response()->json(['success' => true, 'message' => 'Tweet deleted successfully']);
    }

    /**
     * Toggle like on a tweet
     */
    public function toggleLike(Request $request, $id)
    {
        if (!session('auth_user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = session('auth_user');
        $tweets = $this->getTweets();
        $tweetIndex = collect($tweets)->search(function ($tweet) use ($id) {
            return $tweet['id'] == $id;
        });

        if ($tweetIndex === false) {
            return response()->json(['error' => 'Tweet not found'], 404);
        }

        $likes = $tweets[$tweetIndex]['likes'] ?? [];
        $userLikeIndex = collect($likes)->search(function ($like) use ($user) {
            return $like['user_id'] == $user['id'];
        });

        if ($userLikeIndex !== false) {
            // Unlike
            array_splice($likes, $userLikeIndex, 1);
            $liked = false;
        } else {
            // Like
            $likes[] = [
                'user_id' => $user['id'],
                'created_at' => now()->toISOString(),
            ];
            $liked = true;
        }

        $tweets[$tweetIndex]['likes'] = $likes;
        Storage::put('tweets.json', json_encode($tweets, JSON_PRETTY_PRINT));

        return response()->json([
            'liked' => $liked,
            'likes_count' => count($likes)
        ]);
    }

    /**
     * Share/retweet a tweet
     */
    public function share(Request $request, $id)
    {
        if (!session('auth_user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = session('auth_user');
        $tweets = $this->getTweets();
        $originalTweetIndex = collect($tweets)->search(function ($tweet) use ($id) {
            return $tweet['id'] == $id;
        });

        if ($originalTweetIndex === false) {
            return response()->json(['error' => 'Tweet not found'], 404);
        }

        $originalTweet = $tweets[$originalTweetIndex];
        
        // Check if user already shared this tweet
        $existingShare = collect($tweets)->first(function ($tweet) use ($user, $id) {
            return isset($tweet['shared_tweet_id']) && 
                   $tweet['shared_tweet_id'] == $id && 
                   $tweet['user']['id'] == $user['id'];
        });

        if ($existingShare) {
            return response()->json(['error' => 'You have already shared this tweet'], 409);
        }

        // Create share tweet
        $shareTweet = [
            'id' => count($tweets) + 1,
            'content' => $request->input('comment', ''), // Optional comment when sharing
            'user' => $user,
            'shared_tweet_id' => $id,
            'shared_tweet' => $originalTweet,
            'photos' => [],
            'video' => null,
            'likes' => [],
            'is_share' => true,
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString(),
        ];

        $tweets[] = $shareTweet;
        Storage::put('tweets.json', json_encode($tweets, JSON_PRETTY_PRINT));

        return response()->json(['success' => true, 'message' => 'Tweet shared successfully']);
    }

    /**
     * Search for tweets and users
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');
        $isAuth = session('auth_user') !== null;
        $authUser = session('auth_user');
        
        $tweets = collect();
        $users = collect();
        
        if (!empty($query)) {
            // Search tweets
            $allTweets = collect($this->getTweets())
                ->filter(function ($tweet) use ($query, $authUser) {
                    // Check if tweet matches search query
                    $matchesQuery = stripos($tweet['content'], $query) !== false ||
                                   stripos($tweet['user']['name'], $query) !== false;
                    
                    if (!$matchesQuery) {
                        return false;
                    }
                    
                    // Apply privacy filtering
                    if (!isset($tweet['privacy']) || $tweet['privacy'] === 'public') {
                        return true;
                    }
                    
                    if (!$authUser) {
                        return false;
                    }
                    
                    $currentUserId = is_array($authUser) ? $authUser['id'] : $authUser->id;
                    
                    if ($tweet['user']['id'] == $currentUserId) {
                        return true;
                    }
                    
                    if ($tweet['privacy'] === 'followers') {
                        return $this->isUserFollowing($currentUserId, $tweet['user']['id']);
                    }
                    
                    if ($tweet['privacy'] === 'close_friends') {
                        return $this->isCloseFriend($currentUserId, $tweet['user']['id']);
                    }
                    
                    return false;
                })
                ->sortByDesc('created_at')
                ->map(function ($tweet) use ($authUser) {
                    $tweet = (object) $tweet;
                    $tweet->user = (object) $tweet->user;
                    $tweet->likes_count = count($tweet->likes ?? []);
                    
                    // Set time formatting
                    if (isset($tweet->created_at)) {
                        $createdAt = \Carbon\Carbon::parse($tweet->created_at);
                        $tweet->time_ago = $createdAt->diffForHumans();
                        $tweet->formatted_created_at = $createdAt->format('M d, Y \a\t g:i A');
                    } else {
                        $tweet->time_ago = 'Just now';
                        $tweet->formatted_created_at = now()->format('M d, Y \a\t g:i A');
                    }
                    
                    // Check if current user has liked this tweet
                    $tweet->user_has_liked = false;
                    if ($authUser && isset($tweet->likes)) {
                        foreach ($tweet->likes as $like) {
                            if ((is_array($authUser) ? $authUser['id'] : $authUser->id) == $like['user_id']) {
                                $tweet->user_has_liked = true;
                                break;
                            }
                        }
                    }
                    
                    $tweet->is_edited = $tweet->is_edited ?? false;
                    return $tweet;
                });
            
            $tweets = $allTweets;
            
            // Search users
            $allUsers = $this->getUsers();
            $users = collect($allUsers)
                ->filter(function ($user) use ($query) {
                    return stripos($user['name'], $query) !== false ||
                           stripos($user['email'], $query) !== false;
                })
                ->map(function ($user) {
                    return (object) $user;
                });
        }
        
        return view('search', compact('isAuth', 'authUser', 'tweets', 'users', 'query'));
    }

    /**
     * Check if user is following another user
     */
    private function isUserFollowing($followerId, $followeeId)
    {
        if (!Storage::exists('follows.json')) {
            return false;
        }
        
        $follows = json_decode(Storage::get('follows.json'), true) ?: [];
        
        return collect($follows)->contains(function ($follow) use ($followerId, $followeeId) {
            return $follow['follower_id'] == $followerId && $follow['followee_id'] == $followeeId;
        });
    }
    
    /**
     * Check if user is in close friends list
     */
    private function isCloseFriend($userId, $tweetAuthorId)
    {
        if (!Storage::exists('close_friends.json')) {
            return false;
        }
        
        $closeFriends = json_decode(Storage::get('close_friends.json'), true) ?: [];
        
        return collect($closeFriends)->contains(function ($friendship) use ($userId, $tweetAuthorId) {
            return $friendship['user_id'] == $tweetAuthorId && $friendship['close_friend_id'] == $userId;
        });
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

    /**
     * Get tweets from file storage
     */
    private function getTweets()
    {
        if (!Storage::exists('tweets.json')) {
            // Create initial demo tweets
            $demoTweets = [
                [
                    'id' => 1,
                    'content' => 'Welcome to Munchkly! 🎉 Your Twitter-like social media platform is now ready!',
                    'user' => ['id' => 0, 'name' => 'Munchkly Admin', 'email' => 'admin@munchkly.app'],
                    'likes' => [],
                    'created_at' => now()->toISOString(),
                    'updated_at' => now()->toISOString(),
                ],
                [
                    'id' => 2,
                    'content' => 'This is a demo tweet showing how the platform works. Feel free to create your own!',
                    'user' => ['id' => 0, 'name' => 'Munchkly Admin', 'email' => 'admin@munchkly.app'],
                    'likes' => [],
                    'created_at' => now()->subMinutes(30)->toISOString(),
                    'updated_at' => now()->subMinutes(30)->toISOString(),
                ]
            ];
            Storage::put('tweets.json', json_encode($demoTweets, JSON_PRETTY_PRINT));
            return $demoTweets;
        }
        
        return json_decode(Storage::get('tweets.json'), true) ?: [];
    }
}