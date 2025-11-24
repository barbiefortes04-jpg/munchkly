<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tweet;
use App\Models\Like;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@munchkly.test',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@munchkly.test',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@munchkly.test',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah@munchkly.test',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Alex Brown',
                'email' => 'alex@munchkly.test',
                'password' => Hash::make('password'),
            ],
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $createdUsers[] = User::create($userData);
        }

        // Sample tweets
        $sampleTweets = [
            "Welcome to Munchkly! 🐦 This is my first tweet on this amazing platform!",
            "Just had the most amazing coffee ☕ What's everyone else up to today?",
            "Working on a new project and feeling excited! 💻 Anyone else coding today?",
            "Beautiful sunset today 🌅 Nature never fails to amaze me",
            "Reading a fantastic book 📚 Any recommendations for my next read?",
            "Friday feeling! 🎉 Weekend plans anyone?",
            "Learning something new every day keeps life interesting ✨",
            "Pizza or pasta? The eternal dilemma 🍕🍝 What's your choice?",
            "Grateful for good friends and family ❤️ What are you grateful for?",
            "Monday motivation: You got this! 💪 Let's make this week amazing!",
            "Technology is advancing so fast! 🚀 What tech trend excites you most?",
            "Just finished a great workout 🏃‍♂️ Exercise really does boost mood!",
            "Rainy day calls for hot chocolate and a good movie 🌧️☕🎬",
            "Celebrating small wins today! 🎊 What's a recent achievement you're proud of?",
            "Music has the power to change your entire day 🎵 What's on your playlist?",
            "Cooking experiment: success! 👨‍🍳 Anyone have favorite recipes to share?",
            "Sometimes the best conversations happen over a simple cup of tea 🫖",
            "Inspiration can strike at the most unexpected moments ⚡",
            "Teamwork makes the dream work! 🤝 Shout out to amazing colleagues",
            "Life is too short for boring food 🌮 Trying something new today!",
        ];

        // Create tweets for each user
        foreach ($createdUsers as $user) {
            // Each user gets 3-5 random tweets
            $tweetCount = rand(3, 5);
            $userTweets = array_rand(array_flip($sampleTweets), $tweetCount);
            
            if (!is_array($userTweets)) {
                $userTweets = [$userTweets];
            }

            foreach ($userTweets as $index => $tweetContent) {
                $tweet = $user->tweets()->create([
                    'content' => $tweetContent,
                    'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                ]);

                // Some tweets get edited
                if (rand(1, 10) <= 2) { // 20% chance
                    $tweet->update([
                        'content' => $tweetContent . ' (Updated with more thoughts!)',
                        'is_edited' => true,
                    ]);
                }
            }
        }

        // Create random likes
        $allTweets = Tweet::all();
        foreach ($createdUsers as $user) {
            // Each user likes 5-15 random tweets
            $tweetsToLike = $allTweets->where('user_id', '!=', $user->id)
                                   ->random(rand(5, min(15, $allTweets->count() - 1)));
            
            foreach ($tweetsToLike as $tweet) {
                // Avoid duplicate likes
                if (!$user->hasLiked($tweet)) {
                    Like::create([
                        'user_id' => $user->id,
                        'tweet_id' => $tweet->id,
                        'created_at' => now()->subDays(rand(0, 30)),
                    ]);
                }
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Sample users created with email/password:');
        foreach ($users as $user) {
            $this->command->info("- {$user['email']} / password");
        }
    }
}