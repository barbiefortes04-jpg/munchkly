<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Store a like for a tweet.
     */
    public function store($id)
    {
        // For demo purposes, simulate like action
        return redirect()->back()->with('success', 'Tweet liked successfully! (Demo mode - no database)');
    }

    /**
     * Remove a like from a tweet.
     */
    public function destroy($id)
    {
        // For demo purposes, simulate unlike action
        return redirect()->back()->with('success', 'Tweet unliked successfully! (Demo mode - no database)');
    }
}