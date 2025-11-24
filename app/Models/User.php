<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get all tweets by this user
     */
    public function tweets()
    {
        return $this->hasMany(Tweet::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get all likes by this user
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get tweets liked by this user
     */
    public function likedTweets()
    {
        return $this->belongsToMany(Tweet::class, 'likes');
    }

    /**
     * Get total number of likes received on all user's tweets
     */
    public function totalLikesReceived()
    {
        return $this->tweets()->withCount('likes')->get()->sum('likes_count');
    }

    /**
     * Check if user has liked a specific tweet
     */
    public function hasLiked(Tweet $tweet)
    {
        return $this->likes()->where('tweet_id', $tweet->id)->exists();
    }

    /**
     * Users this user is following
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * Users following this user
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    /**
     * Check if this user is following another user
     */
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Follow a user
     */
    public function follow(User $user)
    {
        if (!$this->isFollowing($user) && $this->id !== $user->id) {
            $this->following()->attach($user->id);
        }
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $user)
    {
        $this->following()->detach($user->id);
    }

    /**
     * Get profile picture URL
     */
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/profile_pictures/' . $this->profile_picture);
        }
        return null;
    }
}