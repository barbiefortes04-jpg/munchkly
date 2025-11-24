<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'content',
        'is_edited',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_edited' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the tweet
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all likes for this tweet
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get users who liked this tweet
     */
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    /**
     * Get the like count for this tweet
     */
    public function likesCount()
    {
        return $this->likes()->count();
    }

    /**
     * Scope to order tweets by newest first
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Check if tweet is owned by specific user
     */
    public function isOwnedBy(User $user)
    {
        return $this->user_id === $user->id;
    }

    /**
     * Get formatted created date
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('M j, Y \a\t g:i A');
    }

    /**
     * Get time ago format
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}