@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Search Section -->
    <div class="mb-8">
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('search') }}" method="GET" class="relative">
                <div class="relative">
                    <input type="text" 
                           name="query" 
                           value="{{ request('query') }}" 
                           placeholder="Search tweets, users, or topics..." 
                           class="w-full pl-12 pr-4 py-3 rounded-full shadow-sm focus:outline-none focus:ring-2 theme-transition" 
                           style="background-color: var(--bg-secondary); color: var(--text-primary); border: 1px solid var(--border-color); --tw-ring-color: var(--accent-color);">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fas fa-search" style="color: var(--text-secondary);"></i>
                    </div>
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-4">
                        <div class="text-white px-6 py-2 rounded-full transition-colors" style="background-color: var(--accent-color);" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                            Search
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <h1 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">Welcome to Munchkly</h1>
    
    @if($isAuth)
        <div class="p-6 rounded-lg shadow-sm border theme-transition" style="background-color: var(--bg-secondary); border-color: var(--border-color);">
            <h2 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Post a Tweet</h2>
            <form method="POST" action="{{ route('tweets.store') }}" enctype="multipart/form-data" id="tweetForm">
                @csrf
                <textarea name="content" class="w-full p-3 border rounded-lg focus:ring-2 focus:border-transparent theme-transition" rows="3" maxlength="280" placeholder="What's on your mind?" required style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color); --tw-ring-color: var(--accent-color);"></textarea>
                
                <!-- Media Upload Section -->
                <div class="mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-4">
                            <!-- Photo Upload -->
                            <label for="photos" class="flex items-center space-x-2 cursor-pointer text-blue-500 hover:text-blue-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 4h16v16H4V4zm2 2v12h12V6H6zm2 2h8v8H8V8zm2 2v4h4v-4h-4zm1 1h2v2h-2v-2z"/>
                                </svg>
                                <span class="text-sm font-medium">Photos</span>
                                <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="hidden" onchange="previewPhotos(this)">
                            </label>
                            
                            <!-- Video Upload -->
                            <label for="video" class="flex items-center space-x-2 cursor-pointer text-green-500 hover:text-green-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                                <span class="text-sm font-medium">Video</span>
                                <input type="file" id="video" name="video" accept="video/*" class="hidden" onchange="previewVideo(this)">
                            </label>
                        </div>
                        
                        <!-- Privacy Setting -->
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" style="color: var(--text-secondary);">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                            </svg>
                            <select name="privacy" class="text-sm border rounded-md px-2 py-1 focus:outline-none focus:ring-1 theme-transition" style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color); --tw-ring-color: var(--accent-color);">
                                <option value="public">Public</option>
                                <option value="followers">Followers</option>
                                <option value="close_friends">Close Friends</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Media Previews -->
                    <div id="photoPreview" class="grid grid-cols-2 gap-2 mb-3" style="display: none;"></div>
                    <div id="videoPreview" class="mb-3" style="display: none;"></div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="text-white px-6 py-2 rounded-full transition-colors" style="background-color: var(--accent-color);" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">Post Tweet</button>
                </div>
            </form>
        </div>
    @else
        <div class="p-6 rounded shadow text-center theme-transition" style="background-color: var(--bg-secondary);">
            <h2 style="color: var(--text-primary);">Welcome to Munchkly!</h2>
            <p style="color: var(--text-secondary);">Please login or register to start using the platform.</p>
        </div>
    @endif
    
    <!-- Display All Tweets -->
    @if(isset($tweets) && $tweets->count() > 0)
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-6" style="color: var(--text-primary);">
                <i class="fas fa-stream mr-2" style="color: var(--text-secondary);"></i>Recent Tweets
            </h2>
            
            @foreach($tweets as $tweet)
                <div class="rounded-lg shadow p-6 mb-4 hover:shadow-md transition-all duration-200 theme-transition" style="background-color: var(--bg-secondary);">
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
                                <p class="font-medium" style="color: var(--text-primary);">
                                    <a href="{{ route('profile.show', $tweet->user->id) }}" class="transition-colors" style="color: var(--text-primary);" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-primary)'">
                                        {{ $tweet->user->name }}
                                    </a>
                                </p>
                                <p class="text-sm" style="color: var(--text-secondary);">
                                    {{ $tweet->time_ago }}
                                    @if($tweet->is_edited)
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
                        
                        <!-- Tweet Actions (for tweet owner) -->
                        @if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $tweet->user->id)
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
                                                 class="w-full max-w-sm rounded-lg object-cover cursor-pointer shadow-md"
                                                 style="max-height: 250px;"
                                                 onclick="openImageModal('{{ asset('storage/tweet_media/photos/' . $tweet->shared_tweet['photos'][0]) }}')"
                                                 onerror="this.src='{{ asset('images/placeholder.png') }}'; this.onerror=null;">
                                        @else
                                            <div class="grid grid-cols-2 gap-1">
                                                @foreach($tweet->shared_tweet['photos'] as $index => $photo)
                                                    @if($index < 4)
                                                        <img src="{{ asset('storage/tweet_media/photos/' . $photo) }}" 
                                                             alt="Tweet photo {{ $index + 1 }}" 
                                                             class="w-full h-16 rounded object-cover cursor-pointer shadow-sm"
                                                             onclick="openImageModal('{{ asset('storage/tweet_media/photos/' . $photo) }}')"
                                                             onerror="this.src='{{ asset('images/placeholder.png') }}'; this.onerror=null;">
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                
                                @if(isset($tweet->shared_tweet['video']) && $tweet->shared_tweet['video'])
                                    <div class="mb-2">
                                        <video controls class="w-full max-w-sm rounded-lg shadow-md" style="max-height: 250px;" preload="metadata">
                                            <source src="{{ asset('storage/tweet_media/videos/' . $tweet->shared_tweet['video']) }}" type="video/mp4">
                                            <source src="{{ asset('storage/tweet_media/videos/' . $tweet->shared_tweet['video']) }}" type="video/webm">
                                            <p class="text-gray-500 p-2 bg-gray-100 rounded text-xs">
                                                Your browser does not support the video tag. 
                                                <a href="{{ asset('storage/tweet_media/videos/' . $tweet->shared_tweet['video']) }}" 
                                                   class="text-blue-500 hover:underline" download>Download</a>
                                            </p>
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
                                             class="w-full max-w-lg rounded-lg object-cover cursor-pointer shadow-md"
                                             style="max-height: 400px;"
                                             onclick="openImageModal('{{ asset('storage/tweet_media/photos/' . $tweet->photos[0]) }}')"
                                             onerror="this.src='{{ asset('images/placeholder.png') }}'; this.onerror=null;">
                                    @elseif(count($tweet->photos) == 2)
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($tweet->photos as $index => $photo)
                                                <img src="{{ asset('storage/tweet_media/photos/' . $photo) }}" 
                                                     alt="Tweet photo {{ $index + 1 }}" 
                                                     class="w-full h-48 rounded-lg object-cover cursor-pointer shadow-md"
                                                     onclick="openImageModal('{{ asset('storage/tweet_media/photos/' . $photo) }}')"
                                                     onerror="this.src='{{ asset('images/placeholder.png') }}'; this.onerror=null;">
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($tweet->photos as $index => $photo)
                                                @if($index < 4)
                                                    <div class="relative">
                                                        <img src="{{ asset('storage/tweet_media/photos/' . $photo) }}" 
                                                             alt="Tweet photo {{ $index + 1 }}" 
                                                             class="w-full h-32 rounded-lg object-cover cursor-pointer shadow-md"
                                                             onclick="openImageModal('{{ asset('storage/tweet_media/photos/' . $photo) }}')"
                                                             onerror="this.src='{{ asset('images/placeholder.png') }}'; this.onerror=null;">
                                                        @if($index == 3 && count($tweet->photos) > 4)
                                                            <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center cursor-pointer"
                                                                 onclick="showAllPhotos({{ json_encode($tweet->photos) }}, {{ $index }})">
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
                                    <video controls class="w-full max-w-lg rounded-lg shadow-md" style="max-height: 400px;" preload="metadata">
                                        <source src="{{ asset('storage/tweet_media/videos/' . $tweet->video) }}" type="video/mp4">
                                        <source src="{{ asset('storage/tweet_media/videos/' . $tweet->video) }}" type="video/webm">
                                        <p class="p-4 rounded theme-transition" style="color: var(--text-secondary); background-color: var(--bg-primary);">
                                            Your browser does not support the video tag. 
                                            <a href="{{ asset('storage/tweet_media/videos/' . $tweet->video) }}" 
                                               class="hover:underline" style="color: var(--accent-color);" download>Download video</a>
                                        </p>
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
                                        class="like-btn flex items-center space-x-1 transition-colors duration-200 {{ $tweet->user_has_liked ? 'text-red-500' : '' }}"
                                        style="{{ !$tweet->user_has_liked ? 'color: var(--text-secondary);' : '' }}"
                                        onmouseover="{{ !$tweet->user_has_liked ? 'this.style.color=\"#ef4444\"' : '' }}"
                                        onmouseout="{{ !$tweet->user_has_liked ? 'this.style.color=\"var(--text-secondary)\"' : '' }}"
                                        id="like-btn-{{ $tweet->id }}">
                                    <i class="{{ $tweet->user_has_liked ? 'fas' : 'far' }} fa-heart" id="like-icon-{{ $tweet->id }}"></i>
                                    <span class="text-sm font-medium" id="like-count-{{ $tweet->id }}">{{ $tweet->likes_count }}</span>
                                </button>
                                
                                <!-- Share Button (only for non-shared tweets and not own tweets) -->
                                @if(!isset($tweet->is_share) && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) != $tweet->user->id)
                                    <button onclick="shareTweet({{ $tweet->id }})" 
                                            class="flex items-center space-x-1 transition-colors duration-200"
                                            style="color: var(--text-secondary);"
                                            onmouseover="this.style.color='#10b981'"
                                            onmouseout="this.style.color='var(--text-secondary)'"
                                            title="Share Tweet">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.77 15.67c-.292-.293-.767-.293-1.06 0l-2.22 2.22V7.65c0-2.068-1.683-3.75-3.75-3.75h-5.85c-.414 0-.75.336-.75.75s.336.75.75.75h5.85c1.24 0 2.25 1.01 2.25 2.25v10.24l-2.22-2.22c-.293-.293-.768-.293-1.061 0s-.293.768 0 1.061l3.5 3.5c.145.147.337.22.53.22s.385-.073.53-.22l3.5-3.5c.294-.292.294-.767.001-1.06zM10.75 20.25H4.9c-1.24 0-2.25-1.01-2.25-2.25V7.65l2.22 2.22c.148.147.34.22.532.22s.384-.073.53-.22c.293-.293.293-.768 0-1.061l-3.5-3.5c-.293-.293-.768-.293-1.061 0l-3.5 3.5c-.294.292-.294.767 0 1.06s.767.294 1.06 0l2.22-2.22V18c0 2.068 1.683 3.75 3.75 3.75h5.85c.414 0 .75-.336.75-.75s-.336-.75-.75-.75z"/>
                                        </svg>
                                        <span class="text-sm font-medium">Share</span>
                                    </button>
                                @endif
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
        </div>
    @endif
</div>

@if($isAuth)
<script>
// Photo preview function
function previewPhotos(input) {
    const preview = document.getElementById('photoPreview');
    preview.innerHTML = '';
    preview.style.display = 'none';
    
    if (input.files) {
        const files = Array.from(input.files);
        if (files.length > 0) {
            preview.style.display = 'grid';
            
            files.forEach((file, index) => {
                if (index < 4) { // Limit to 4 photos
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                            <button type="button" onclick="removePhoto(${index})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                ×
                            </button>
                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            if (files.length > 4) {
                alert('Maximum 4 photos allowed. Only the first 4 will be uploaded.');
            }
        }
    }
}

// Video preview function
function previewVideo(input) {
    const preview = document.getElementById('videoPreview');
    preview.innerHTML = '';
    preview.style.display = 'none';
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.style.display = 'block';
            preview.innerHTML = `
                <div class="relative">
                    <video controls class="w-full max-w-sm rounded-lg">
                        <source src="${e.target.result}" type="${file.type}">
                        Your browser does not support the video tag.
                    </video>
                    <button type="button" onclick="removeVideo()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                        ×
                    </button>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }
}

// Remove photo function
function removePhoto(index) {
    const input = document.getElementById('photos');
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    previewPhotos(input);
}

// Remove video function
function removeVideo() {
    const input = document.getElementById('video');
    input.value = '';
    const preview = document.getElementById('videoPreview');
    preview.innerHTML = '';
    preview.style.display = 'none';
}

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

// Share tweet function
async function shareTweet(tweetId) {
    const comment = prompt('Add a comment (optional):');
    if (comment === null) return; // User cancelled
    
    try {
        const response = await fetch(`/tweets/${tweetId}/share`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                comment: comment || ''
            })
        });

        const data = await response.json();
        
        if (response.ok) {
            alert('Tweet shared successfully!');
            location.reload(); // Refresh to show the shared tweet
        } else {
            alert(data.error || 'Error sharing tweet');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error sharing tweet');
    }
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
                // Reload the page to show updated tweet list
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
</script>
@endif
@endsection