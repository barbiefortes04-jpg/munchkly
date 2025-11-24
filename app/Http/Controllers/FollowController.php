<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FollowController extends Controller
{
    /**
     * Follow or unfollow a user
     */
    public function toggle(Request $request, $userId)
    {
        if (!session('auth_user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $authUser = session('auth_user');
        $currentUserId = is_array($authUser) ? $authUser['id'] : $authUser->id;

        // Can't follow yourself
        if ($currentUserId == $userId) {
            return response()->json(['error' => 'You cannot follow yourself'], 400);
        }

        // Get follows from file storage
        $follows = $this->getFollows();
        
        // Check if already following
        $followIndex = collect($follows)->search(function ($follow) use ($currentUserId, $userId) {
            return $follow['follower_id'] == $currentUserId && $follow['following_id'] == $userId;
        });

        if ($followIndex !== false) {
            // Unfollow
            array_splice($follows, $followIndex, 1);
            $isFollowing = false;
        } else {
            // Follow
            $follows[] = [
                'id' => count($follows) + 1,
                'follower_id' => $currentUserId,
                'following_id' => (int) $userId,
                'created_at' => now()->toISOString(),
            ];
            $isFollowing = true;
        }

        Storage::put('follows.json', json_encode($follows, JSON_PRETTY_PRINT));

        return response()->json([
            'following' => $isFollowing,
            'followers_count' => $this->getFollowersCount($userId)
        ]);
    }

    /**
     * Get followers count for a user
     */
    private function getFollowersCount($userId)
    {
        $follows = $this->getFollows();
        return collect($follows)->where('following_id', (int) $userId)->count();
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
}