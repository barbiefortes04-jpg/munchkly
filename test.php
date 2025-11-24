<?php

// Test file to debug the Laravel application
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

// Test reading tweets data
$tweetsPath = storage_path('app/tweets.json');
if (file_exists($tweetsPath)) {
    $tweets = json_decode(file_get_contents($tweetsPath), true);
    echo "Tweets data loaded successfully:\n";
    print_r($tweets);
} else {
    echo "Tweets file not found\n";
}

echo "\n\nTesting object conversion:\n";

if (!empty($tweets)) {
    $tweet = (object) $tweets[0];
    echo "Tweet object created\n";
    
    $tweet->user = (object) $tweet->user;
    echo "User object created\n";
    
    if (isset($tweet->created_at)) {
        $createdAt = \Carbon\Carbon::parse($tweet->created_at);
        $tweet->time_ago = $createdAt->diffForHumans();
        echo "Time format: " . $tweet->time_ago . "\n";
    }
    
    echo "Tweet content: " . $tweet->content . "\n";
    echo "User name: " . $tweet->user->name . "\n";
}

echo "Test completed\n";