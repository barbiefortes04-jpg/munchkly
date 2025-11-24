@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center font-medium transition-colors" style="color: var(--accent-color);" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                <i class="fas fa-arrow-left mr-2"></i>Back to Home
            </a>
        </div>

        <!-- Profile Card with Cover Photo -->
        <div class="rounded-2xl shadow-xl overflow-hidden mb-6 theme-transition" style="background-color: var(--bg-secondary);">
            <!-- Cover Photo Section -->
            <div class="relative h-48 bg-gradient-to-br from-orange-300 via-pink-300 to-purple-400" style="background-image: {{ $user->cover_photo ? 'url(' . asset('storage/cover_photos/' . $user->cover_photo) . ')' : '' }}; background-size: cover; background-position: center;">
                <!-- Cover Photo Upload (only for own profile) -->
                @if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $user->id)
                    <div class="absolute top-4 right-4">
                        <form action="{{ route('profile.upload-cover') }}" method="POST" enctype="multipart/form-data" class="inline">
                            @csrf
                            <label for="cover_photo" class="cursor-pointer bg-black bg-opacity-50 hover:bg-opacity-70 text-white px-3 py-2 rounded-lg text-sm transition-all backdrop-blur-sm">
                                <i class="fas fa-camera mr-2"></i>Edit Cover
                            </label>
                            <input type="file" id="cover_photo" name="cover_photo" class="hidden" accept="image/*" onchange="this.form.submit()">
                        </form>
                    </div>
                @endif
                
                <!-- Decorative elements like in the design -->
                <div class="absolute top-4 left-8 opacity-20">
                    <div class="w-16 h-8 bg-white rounded-full"></div>
                </div>
                <div class="absolute top-8 left-20 opacity-15">
                    <div class="w-12 h-6 bg-white rounded-full"></div>
                </div>
                <div class="absolute bottom-8 right-20 opacity-10">
                    <div class="w-20 h-10 bg-white rounded-full"></div>
                </div>
            </div>
            
            <!-- Profile Info Section -->
            <div class="relative px-8 pb-8">
                <!-- Profile Picture -->
                <div class="flex justify-center -mt-16 mb-4">
                    <div class="relative">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/profile_pictures/' . $user->profile_picture) }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-xl">
                        @else
                            <div class="w-32 h-32 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center border-4 border-white shadow-xl">
                                <span class="text-white font-bold text-4xl">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        
                        <!-- Profile Picture Upload (only for own profile) -->
                        @if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $user->id)
                            <div class="absolute bottom-2 right-2">
                                <form action="{{ route('profile.upload-picture') }}" method="POST" enctype="multipart/form-data" class="inline">
                                    @csrf
                                    <label for="profile_picture" class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition-colors">
                                        <i class="fas fa-camera text-sm"></i>
                                    </label>
                                    <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*" onchange="this.form.submit()">
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Name and Actions -->
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-bold mb-4" style="color: var(--text-primary);">{{ $user->name }}</h1>
                    
                    <!-- Follow Button (only if viewing another user's profile) -->
                    @if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) != $user->id)
                        <div class="flex space-x-3 justify-center">
                            <button onclick="toggleFollow({{ $user->id }})" 
                                    class="follow-btn px-8 py-3 rounded-full font-medium transition-all transform hover:scale-105 {{ $isFollowing ? 'border' : 'text-white shadow-lg' }}"
                                    style="{{ $isFollowing ? 'background-color: var(--bg-primary); color: var(--text-secondary); border-color: var(--border-color);' : 'background: linear-gradient(to right, var(--accent-color), #8b5cf6);' }}"
                                    id="follow-btn-{{ $user->id }}">
                                <i class="fas {{ $isFollowing ? 'fa-user-check' : 'fa-user-plus' }} mr-2"></i>
                                <span id="follow-text-{{ $user->id }}">{{ $isFollowing ? 'Following' : 'Follow' }}</span>
                            </button>
                            
                            @if($isFollowing)
                                <button onclick="toggleCloseFriend({{ $user->id }})" 
                                        class="close-friend-btn px-6 py-3 rounded-full font-medium transition-all transform hover:scale-105 border"
                                        style="{{ $isCloseFriend ? 'background-color: #dcfce7; color: #166534; border-color: #22c55e;' : 'background-color: var(--bg-primary); color: var(--text-secondary); border-color: var(--border-color);' }}"
                                        id="close-friend-btn-{{ $user->id }}">
                                    <i class="fas {{ $isCloseFriend ? 'fa-heart' : 'fa-heart-o' }} mr-2" style="color: {{ $isCloseFriend ? '#10B981' : 'var(--text-secondary)' }}"></i>
                                    <span id="close-friend-text-{{ $user->id }}">{{ $isCloseFriend ? 'Close Friend' : 'Add Close Friend' }}</span>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Profile Stats -->
                <div class="grid grid-cols-4 gap-6 p-6 rounded-xl theme-transition" style="background-color: var(--bg-primary);">
                    <div class="text-center">
                        <div class="text-2xl font-bold" style="color: var(--accent-color);">{{ $tweetCount }}</div>
                        <div class="text-sm font-medium" style="color: var(--text-secondary);">{{ $tweetCount === 1 ? 'Tweet' : 'Tweets' }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-500">{{ $totalLikesReceived }}</div>
                        <div class="text-sm font-medium" style="color: var(--text-secondary);">{{ $totalLikesReceived === 1 ? 'Like' : 'Likes' }}</div>
                    </div>
                    <div class="text-center cursor-pointer rounded-lg p-2 transition-colors theme-transition" style="background-color: var(--bg-primary);" onmouseover="this.style.backgroundColor='var(--border-color)'" onmouseout="this.style.backgroundColor='var(--bg-primary)'" onclick="toggleFollowersModal()">
                        <div class="text-2xl font-bold text-green-500" id="followers-count">{{ $followersCount }}</div>
                        <div class="text-sm font-medium transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-secondary)'">{{ $followersCount === 1 ? 'Follower' : 'Followers' }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-500">{{ $followingCount }}</div>
                        <div class="text-sm font-medium" style="color: var(--text-secondary);">Following</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Followers Modal -->
        <div id="followers-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="toggleFollowersModal()">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="rounded-lg shadow-xl max-w-md w-full max-h-96 overflow-hidden theme-transition" style="background-color: var(--bg-secondary);" onclick="event.stopPropagation()">
                    <div class="p-4 border-b theme-transition" style="border-color: var(--border-color);">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Followers</h3>
                            <button onclick="toggleFollowersModal()" class="transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4 overflow-y-auto max-h-64">
                        @if($followers->count() > 0)
                            <div class="space-y-3">
                                @foreach($followers as $follower)
                                    <div class="flex items-center space-x-3">
                                        @if($follower->profile_picture)
                                            <img src="{{ asset('storage/profile_pictures/' . $follower->profile_picture) }}" 
                                                 alt="{{ $follower->name }}" 
                                                 class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                <span class="text-white font-medium text-sm">
                                                    {{ strtoupper(substr($follower->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <a href="{{ route('profile.show', $follower->id) }}" class="font-medium transition-colors" style="color: var(--text-primary);" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-primary)'">
                                                {{ $follower->name }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center" style="color: var(--text-secondary);">
                                <i class="fas fa-users text-3xl mb-2"></i>
                                <p>No followers yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- User's Tweets -->
        <div class="space-y-4">
            @if($tweets->count() > 0)
                <h2 class="text-xl font-semibold" style="color: var(--text-primary);">
                    <i class="fas fa-stream mr-2" style="color: var(--text-secondary);"></i>{{ $user->name }}'s Tweets
                </h2>
                
                @foreach($tweets as $tweet)
                    <div class="rounded-lg shadow p-6 hover:shadow-md transition-all duration-200 theme-transition" style="background-color: var(--bg-secondary);">
                        <!-- Tweet Header -->
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($tweet->user->profile_picture ?? false)
                                        <img src="{{ asset('storage/profile_pictures/' . $tweet->user->profile_picture) }}" 
                                             alt="{{ $tweet->user->name }}" 
                                             class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">
                                                {{ strtoupper(substr($tweet->user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium" style="color: var(--text-primary);">{{ $tweet->user->name }}</p>
                                    <p class="text-sm" style="color: var(--text-secondary);">
                                        {{ $tweet->time_ago }}
                                        @if($tweet->is_edited && $isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $user->id)
                                            <span class="text-orange-500">• edited</span>
                                        @endif
                                        
                                        <!-- Privacy Indicator -->
                                        @if(isset($tweet->privacy) && $tweet->privacy !== 'public')
                                            <span class="ml-2">
                                                @if($tweet->privacy === 'followers')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                        Followers
                                                    </span>
                                                @elseif($tweet->privacy === 'close_friends')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                                        </svg>
                                                        Close Friends
                                                    </span>
                                                @endif
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Tweet Actions (for tweet owner viewing their own profile) -->
                            @if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $tweet->user->id && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $user->id)
                                <div class="flex space-x-2">
                                    <a href="{{ route('tweets.edit', $tweet->id) }}" class="transition-colors duration-200" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-secondary)'"
                                       title="Edit Tweet">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteTweet({{ $tweet->id }})" class="transition-colors duration-200" style="color: var(--text-secondary);" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='var(--text-secondary)'"
                                            title="Delete Tweet">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Tweet Content -->
                        <div class="mt-4">
                            <!-- Share indicator -->
                            @if(isset($tweet->is_share) && $tweet->is_share)
                                <div class="flex items-center text-sm mb-3" style="color: var(--text-secondary);">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.77 15.67c-.292-.293-.767-.293-1.06 0l-2.22 2.22V7.65c0-2.068-1.683-3.75-3.75-3.75h-5.85c-.414 0-.75.336-.75.75s.336.75.75.75h5.85c1.24 0 2.25 1.01 2.25 2.25v10.24l-2.22-2.22c-.293-.293-.768-.293-1.061 0s-.293.768 0 1.061l3.5 3.5c.145.147.337.22.53.22s.385-.073.53-.22l3.5-3.5c.294-.292.294-.767.001-1.06zM10.75 20.25H4.9c-1.24 0-2.25-1.01-2.25-2.25V7.65l2.22 2.22c.148.147.34.22.532.22s.384-.073.53-.22c.293-.293.293-.768 0-1.061l-3.5-3.5c-.293-.293-.768-.293-1.061 0l-3.5 3.5c-.294.292-.294.767 0 1.06s.767.294 1.06 0l2.22-2.22V18c0 2.068 1.683 3.75 3.75 3.75h5.85c.414 0 .75-.336.75-.75s-.336-.75-.75-.75z"/>
                                    </svg>
                                    <span>{{ $tweet->user->name }} shared</span>
                                </div>
                                
                                <!-- User's comment on share -->
                                @if(!empty($tweet->content))
                                    <p class="text-base leading-relaxed mb-3" style="color: var(--text-primary);">{{ $tweet->content }}</p>
                                @endif
                                
                                <!-- Original shared tweet -->
                                <div class="border rounded-lg p-4 theme-transition" style="border-color: var(--border-color); background-color: var(--bg-primary);">
                                    <div class="flex items-start space-x-3 mb-2">
                                        <div class="flex-shrink-0">
                                            @if($tweet->shared_tweet['user']['profile_picture'] ?? false)
                                                <img src="{{ asset('storage/profile_pictures/' . $tweet->shared_tweet['user']['profile_picture']) }}" 
                                                     alt="{{ $tweet->shared_tweet['user']['name'] }}" 
                                                     class="w-8 h-8 rounded-full object-cover">
                                            @else
                                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-medium text-xs">
                                                        {{ strtoupper(substr($tweet->shared_tweet['user']['name'], 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-sm" style="color: var(--text-primary);">
                                                <a href="{{ route('profile.show', $tweet->shared_tweet['user']['id']) }}" class="transition-colors" style="color: var(--text-primary);" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-primary)'">
                                                    {{ $tweet->shared_tweet['user']['name'] }}
                                                </a>
                                            </p>
                                            <p class="text-xs" style="color: var(--text-secondary);">
                                                {{ \Carbon\Carbon::parse($tweet->shared_tweet['created_at'])->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <p class="text-sm mb-2" style="color: var(--text-primary);">{{ $tweet->shared_tweet['content'] }}</p>
                                    
                                    <!-- Original tweet media -->
                                    @if(isset($tweet->shared_tweet['photos']) && count($tweet->shared_tweet['photos']) > 0)
                                        <div class="mb-2">
                                            @if(count($tweet->shared_tweet['photos']) == 1)
                                                <img src="{{ asset('storage/tweet_media/photos/' . $tweet->shared_tweet['photos'][0]) }}" 
                                                     alt="Tweet photo" 
                                                     class="w-full max-w-sm rounded-lg object-cover cursor-pointer"
                                                     onclick="openImageModal('{{ asset('storage/tweet_media/photos/' . $tweet->shared_tweet['photos'][0]) }}')">
                                            @else
                                                <div class="grid grid-cols-2 gap-1">
                                                    @foreach($tweet->shared_tweet['photos'] as $index => $photo)
                                                        @if($index < 4)
                                                            <img src="{{ asset('storage/tweet_media/photos/' . $photo) }}" 
                                                                 alt="Tweet photo {{ $index + 1 }}" 
                                                                 class="w-full h-20 rounded object-cover cursor-pointer"
                                                                 onclick="openImageModal('{{ asset('storage/tweet_media/photos/' . $photo) }}')">
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if(isset($tweet->shared_tweet['video']) && $tweet->shared_tweet['video'])
                                        <div class="mb-2">
                                            <video controls class="w-full max-w-sm rounded-lg">
                                                <source src="{{ asset('storage/tweet_media/videos/' . $tweet->shared_tweet['video']) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <!-- Regular tweet content -->
                                <p class="text-base leading-relaxed" style="color: var(--text-primary);">{{ $tweet->content }}</p>
                                
                                <!-- Media Content -->
                                @if(isset($tweet->photos) && count($tweet->photos) > 0)
                                    <div class="mt-3">
                                        @if(count($tweet->photos) == 1)
                                            <img src="{{ asset('storage/tweet_media/photos/' . $tweet->photos[0]) }}" 
                                                 alt="Tweet photo" 
                                                 class="w-full max-w-lg rounded-lg object-cover cursor-pointer"
                                                 onclick="openImageModal('{{ asset('storage/tweet_media/photos/' . $tweet->photos[0]) }}')">
                                        @else
                                            <div class="grid {{ count($tweet->photos) == 2 ? 'grid-cols-2' : 'grid-cols-2' }} gap-2">
                                                @foreach($tweet->photos as $index => $photo)
                                                    @if($index < 4)
                                                        <div class="relative">
                                                            <img src="{{ asset('storage/tweet_media/photos/' . $photo) }}" 
                                                                 alt="Tweet photo {{ $index + 1 }}" 
                                                                 class="w-full h-40 rounded-lg object-cover cursor-pointer"
                                                                 onclick="openImageModal('{{ asset('storage/tweet_media/photos/' . $photo) }}')">
                                                            @if($index == 3 && count($tweet->photos) > 4)
                                                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                                                                    <span class="text-white font-bold text-xl">+{{ count($tweet->photos) - 4 }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                
                                @if(isset($tweet->video) && $tweet->video)
                                    <div class="mt-3">
                                        <video controls class="w-full max-w-lg rounded-lg">
                                            <source src="{{ asset('storage/tweet_media/videos/' . $tweet->video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Tweet Footer -->
                        <div class="mt-4 flex items-center justify-between pt-3 border-t theme-transition" style="border-color: var(--border-color);">
                            <div class="flex items-center space-x-6">
                                <!-- Like Button -->
                                @if($isAuth)
                                    <button onclick="toggleLike({{ $tweet->id }})" 
                                            class="like-btn flex items-center space-x-1 transition-colors duration-200" 
                                            style="color: var(--text-secondary);"
                                            onmouseover="this.style.color='#ef4444'"
                                            onmouseout="this.style.color='var(--text-secondary)'"
                                            id="like-btn-{{ $tweet->id }}">
                                        <i class="far fa-heart" id="like-icon-{{ $tweet->id }}"></i>
                                        <span class="text-sm font-medium" id="like-count-{{ $tweet->id }}">{{ $tweet->likes_count }}</span>
                                    </button>
                                @else
                                    <div class="flex items-center space-x-1" style="color: var(--text-secondary);">
                                        <i class="far fa-heart"></i>
                                        <span class="text-sm font-medium">{{ $tweet->likes_count }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <span class="text-xs" style="color: var(--text-secondary);">{{ $tweet->formatted_created_at }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-12 rounded-lg shadow theme-transition" style="background-color: var(--bg-secondary);">
                    <i class="fas fa-feather-alt text-6xl mb-4" style="color: var(--border-color);"></i>
                    <h3 class="text-lg font-medium mb-2" style="color: var(--text-primary);">No tweets yet</h3>
                    <p style="color: var(--text-secondary);">
                        @if($isAuth && $authUser && $user->id == (is_array($authUser) ? $authUser['id'] : $authUser->id))
                            <a href="{{ route('home') }}" class="transition-colors" style="color: var(--accent-color);" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">Start sharing your thoughts!</a>
                        @else
                            {{ $user->name }} hasn't posted any tweets yet.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    @if($isAuth)
    <script>
        // Image modal functions
        function openImageModal(src) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="relative max-w-4xl max-h-4xl p-4">
                    <img src="${src}" class="max-w-full max-h-full object-contain">
                    <button onclick="this.closest('.fixed').remove()" class="absolute -top-10 right-0 text-white text-2xl hover:text-gray-300">
                        ×
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
            
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        }

        function toggleLike(tweetId) {
            fetch(`/tweets/${tweetId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.liked !== undefined) {
                    const likeBtn = document.getElementById(`like-btn-${tweetId}`);
                    const likeIcon = document.getElementById(`like-icon-${tweetId}`);
                    const likeCount = document.getElementById(`like-count-${tweetId}`);
                    
                    if (data.liked) {
                        likeIcon.classList.remove('far');
                        likeIcon.classList.add('fas');
                        likeBtn.classList.remove('text-gray-400');
                        likeBtn.classList.add('text-red-500');
                    } else {
                        likeIcon.classList.remove('fas');
                        likeIcon.classList.add('far');
                        likeBtn.classList.remove('text-red-500');
                        likeBtn.classList.add('text-gray-400');
                    }
                    
                    likeCount.textContent = data.likes_count;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function deleteTweet(tweetId) {
            if (confirm('Are you sure you want to delete this tweet? This action cannot be undone.')) {
                fetch(`/tweets/${tweetId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.error || 'Failed to delete tweet');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the tweet');
                });
            }
        }

        function toggleFollow(userId) {
            fetch(`/follow/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                const followBtn = document.getElementById(`follow-btn-${userId}`);
                const followText = document.getElementById(`follow-text-${userId}`);
                const followersCount = document.getElementById('followers-count');
                
                if (data.following) {
                    followBtn.style.backgroundColor = 'var(--bg-primary)';
                    followBtn.style.color = 'var(--text-secondary)';
                    followBtn.style.borderColor = 'var(--border-color)';
                    followBtn.className = 'follow-btn px-8 py-3 rounded-full font-medium transition-all transform hover:scale-105 border';
                    followBtn.innerHTML = '<i class="fas fa-user-check mr-2"></i><span id="follow-text-' + userId + '">Following</span>';
                } else {
                    followBtn.style.background = 'linear-gradient(to right, var(--accent-color), #8b5cf6)';
                    followBtn.style.color = 'white';
                    followBtn.style.borderColor = 'transparent';
                    followBtn.className = 'follow-btn px-8 py-3 rounded-full font-medium transition-all transform hover:scale-105 text-white shadow-lg';
                    followBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i><span id="follow-text-' + userId + '">Follow</span>';
                }
                
                followersCount.textContent = data.followers_count;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function toggleFollowersModal() {
            const modal = document.getElementById('followers-modal');
            modal.classList.toggle('hidden');
        }
        
        function toggleCloseFriend(userId) {
            fetch(`/close-friend/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                const closeFriendBtn = document.getElementById(`close-friend-btn-${userId}`);
                const closeFriendText = document.getElementById(`close-friend-text-${userId}`);
                const icon = closeFriendBtn.querySelector('i');
                
                if (data.is_close_friend) {
                    closeFriendBtn.style.backgroundColor = '#dcfce7';
                    closeFriendBtn.style.color = '#166534';
                    closeFriendBtn.style.borderColor = '#22c55e';
                    closeFriendBtn.className = 'close-friend-btn px-6 py-3 rounded-full font-medium transition-all transform hover:scale-105 border';
                    icon.style.color = '#10B981';
                    icon.className = 'fas fa-heart mr-2';
                    closeFriendText.textContent = 'Close Friend';
                } else {
                    closeFriendBtn.style.backgroundColor = 'var(--bg-primary)';
                    closeFriendBtn.style.color = 'var(--text-secondary)';
                    closeFriendBtn.style.borderColor = 'var(--border-color)';
                    closeFriendBtn.className = 'close-friend-btn px-6 py-3 rounded-full font-medium transition-all transform hover:scale-105 border';
                    icon.style.color = 'var(--text-secondary)';
                    icon.className = 'fas fa-heart-o mr-2';
                    closeFriendText.textContent = 'Add Close Friend';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
    @endif
@endsection