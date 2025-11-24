@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center text-blue-500 hover:text-blue-600 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Home
            </a>
        </div>

        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-start space-x-6">
                <!-- Profile Picture Section -->
                <div class="flex-shrink-0">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/profile_pictures/' . $user->profile_picture) }}" 
                             alt="{{ $user->name }}" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                    @else
                        <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center border-4 border-gray-200">
                            <span class="text-white font-bold text-3xl">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    
                    <!-- Upload Profile Picture (only for own profile) -->
                    @if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $user->id)
                        <div class="mt-3">
                            <form action="{{ route('profile.upload-picture') }}" method="POST" enctype="multipart/form-data" class="text-center">
                                @csrf
                                <label for="profile_picture" class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                    <i class="fas fa-camera mr-1"></i>Change Photo
                                </label>
                                <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*" onchange="this.form.submit()">
                            </form>
                        </div>
                    @endif
                </div>
                
                <!-- Profile Info -->
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-gray-600 mt-1">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Joined {{ $user->created_at->format('F Y') }}
                            </p>
                        </div>
                        
                        <!-- Follow Button (only if viewing another user's profile) -->
                        @if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) != $user->id)
                            <button onclick="toggleFollow({{ $user->id }})" 
                                    class="follow-btn px-6 py-2 rounded-md font-medium transition-colors {{ $isFollowing ? 'bg-gray-500 hover:bg-gray-600 text-white' : 'bg-blue-500 hover:bg-blue-600 text-white' }}"
                                    id="follow-btn-{{ $user->id }}">
                                <i class="fas {{ $isFollowing ? 'fa-user-minus' : 'fa-user-plus' }} mr-1"></i>
                                <span id="follow-text-{{ $user->id }}">{{ $isFollowing ? 'Unfollow' : 'Follow' }}</span>
                            </button>
                        @endif
                    </div>

                    <!-- Profile Stats -->
                    <div class="mt-4 grid grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-xl font-bold text-blue-600">{{ $tweetCount }}</div>
                            <div class="text-sm text-gray-600">{{ $tweetCount === 1 ? 'Tweet' : 'Tweets' }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-red-500">{{ $totalLikesReceived }}</div>
                            <div class="text-sm text-gray-600">{{ $totalLikesReceived === 1 ? 'Like' : 'Likes' }} Received</div>
                        </div>
                        <div class="text-center cursor-pointer" onclick="toggleFollowersModal()">
                            <div class="text-xl font-bold text-green-500" id="followers-count">{{ $followersCount }}</div>
                            <div class="text-sm text-gray-600 hover:text-blue-600">{{ $followersCount === 1 ? 'Follower' : 'Followers' }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-purple-500">{{ $followingCount }}</div>
                            <div class="text-sm text-gray-600">Following</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Followers Modal -->
        <div id="followers-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="toggleFollowersModal()">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-96 overflow-hidden" onclick="event.stopPropagation()">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Followers</h3>
                            <button onclick="toggleFollowersModal()" class="text-gray-400 hover:text-gray-600">
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
                                            <a href="{{ route('profile.show', $follower->id) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                                {{ $follower->name }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-gray-500">
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
                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-stream text-gray-600 mr-2"></i>{{ $user->name }}'s Tweets
                </h2>
                
                @foreach($tweets as $tweet)
                    <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow duration-200">
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
                                    <p class="font-medium text-gray-900">{{ $tweet->user->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $tweet->time_ago }}
                                        @if($tweet->is_edited && $isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $user->id)
                                            <span class="text-orange-500">• edited</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Tweet Actions (for tweet owner viewing their own profile) -->
                            @if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $tweet->user->id && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $user->id)
                                <div class="flex space-x-2">
                                    <a href="{{ route('tweets.edit', $tweet->id) }}" class="text-gray-400 hover:text-blue-500 transition-colors duration-200"
                                       title="Edit Tweet">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteTweet({{ $tweet->id }})" class="text-gray-400 hover:text-red-500 transition-colors duration-200"
                                            title="Delete Tweet">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Tweet Content -->
                        <div class="mt-4">
                            <p class="text-gray-900 text-base leading-relaxed">{{ $tweet->content }}</p>
                        </div>

                        <!-- Tweet Footer -->
                        <div class="mt-4 flex items-center justify-between pt-3 border-t border-gray-100">
                            <div class="flex items-center space-x-6">
                                <!-- Like Button -->
                                @if($isAuth)
                                    <button onclick="toggleLike({{ $tweet->id }})" 
                                            class="like-btn flex items-center space-x-1 text-gray-400 hover:text-red-500 transition-colors duration-200"
                                            id="like-btn-{{ $tweet->id }}">
                                        <i class="far fa-heart" id="like-icon-{{ $tweet->id }}"></i>
                                        <span class="text-sm font-medium" id="like-count-{{ $tweet->id }}">{{ $tweet->likes_count }}</span>
                                    </button>
                                @else
                                    <div class="flex items-center space-x-1 text-gray-400">
                                        <i class="far fa-heart"></i>
                                        <span class="text-sm font-medium">{{ $tweet->likes_count }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <span class="text-xs text-gray-400">{{ $tweet->formatted_created_at }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-12 bg-white rounded-lg shadow">
                    <i class="fas fa-feather-alt text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No tweets yet</h3>
                    <p class="text-gray-500">
                        @if($isAuth && $authUser && $user->id == (is_array($authUser) ? $authUser['id'] : $authUser->id))
                            <a href="{{ route('home') }}" class="text-blue-500 hover:text-blue-600">Start sharing your thoughts!</a>
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
                    followBtn.className = 'follow-btn px-6 py-2 rounded-md font-medium transition-colors bg-gray-500 hover:bg-gray-600 text-white';
                    followBtn.innerHTML = '<i class="fas fa-user-minus mr-1"></i><span id="follow-text-' + userId + '">Unfollow</span>';
                } else {
                    followBtn.className = 'follow-btn px-6 py-2 rounded-md font-medium transition-colors bg-blue-500 hover:bg-blue-600 text-white';
                    followBtn.innerHTML = '<i class="fas fa-user-plus mr-1"></i><span id="follow-text-' + userId + '">Follow</span>';
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
    </script>
    @endif
@endsection