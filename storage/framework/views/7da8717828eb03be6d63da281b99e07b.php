

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2" style="color: var(--text-primary);">Search Results</h1>
        <?php if($query): ?>
            <p style="color: var(--text-secondary);">Results for: "<span class="font-medium"><?php echo e($query); ?></span>"</p>
        <?php else: ?>
            <p style="color: var(--text-secondary);">Enter a search term to find tweets and users.</p>
        <?php endif; ?>
    </div>

    <!-- Search Form -->
    <div class="rounded-lg shadow p-4 mb-6 theme-transition" style="background-color: var(--bg-secondary);">
        <form action="<?php echo e(route('search')); ?>" method="GET" class="flex">
            <div class="relative flex-1">
                <input 
                    type="text" 
                    name="query" 
                    value="<?php echo e($query); ?>" 
                    placeholder="Search tweets and users..."
                    class="w-full pl-10 pr-4 py-2 border rounded-full focus:outline-none focus:ring-2 theme-transition"
                    style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color); --tw-ring-color: var(--accent-color);"
                >
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--text-secondary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"></path>
                </svg>
            </div>
            <button type="submit" class="ml-3 text-white px-6 py-2 rounded-full transition-colors" style="background-color: var(--accent-color);" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                Search
            </button>
        </form>
    </div>

    <?php if($query): ?>
        <!-- Results Tabs -->
        <div class="mb-6">
            <div class="border-b theme-transition" style="border-color: var(--border-color);">
                <nav class="-mb-px flex space-x-8">
                    <button id="tweets-tab" class="tab-btn active py-2 px-1 border-b-2 font-medium text-sm transition-colors" style="border-color: var(--accent-color); color: var(--accent-color);">
                        Tweets (<?php echo e($tweets->count()); ?>)
                    </button>
                    <button id="users-tab" class="tab-btn py-2 px-1 border-b-2 border-transparent font-medium text-sm transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--text-primary)'; this.style.borderColor='var(--border-color)'" onmouseout="this.style.color='var(--text-secondary)'; this.style.borderColor='transparent'">
                        Users (<?php echo e($users->count()); ?>)
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tweets Results -->
        <div id="tweets-content" class="tab-content">
            <?php if($tweets->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $tweets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tweet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-lg shadow p-4 theme-transition" style="background-color: var(--bg-secondary);">
                            <div class="flex items-start space-x-3">
                                <!-- Profile Picture -->
                                <div class="flex-shrink-0">
                                    <?php if(isset($tweet->user->profile_picture) && $tweet->user->profile_picture): ?>
                                        <img class="h-10 w-10 rounded-full object-cover" src="<?php echo e(Storage::url('profile_pictures/' . $tweet->user->profile_picture)); ?>" alt="<?php echo e($tweet->user->name); ?>">
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                            <span class="text-white font-medium text-sm"><?php echo e(strtoupper(substr($tweet->user->name, 0, 1))); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <!-- User Info -->
                                    <div class="flex items-center space-x-2 mb-2">
                                        <a href="<?php echo e(route('profile.show', $tweet->user->id)); ?>" class="font-medium hover:underline theme-transition" style="color: var(--text-primary);">
                                            <?php echo e($tweet->user->name); ?>

                                        </a>
                                        <span class="text-sm" style="color: var(--text-secondary);"><?php echo e($tweet->time_ago); ?></span>
                                        <?php if($tweet->is_edited): ?>
                                            <span class="text-xs" style="color: var(--text-secondary); opacity: 0.7;">(edited)</span>
                                        <?php endif; ?>
                                        
                                        <!-- Privacy Indicator -->
                                        <?php if(isset($tweet->privacy) && $tweet->privacy !== 'public'): ?>
                                            <?php if($tweet->privacy === 'followers'): ?>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">
                                                    <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    Followers
                                                </span>
                                            <?php elseif($tweet->privacy === 'close_friends'): ?>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs bg-green-100 text-green-800">
                                                    <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                                    </svg>
                                                    Close Friends
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Tweet Content -->
                                    <div class="mb-3 theme-transition" style="color: var(--text-primary);">
                                        <!-- Share indicator -->
                                        <?php if(isset($tweet->is_share) && $tweet->is_share): ?>
                                            <div class="flex items-center text-sm mb-3" style="color: var(--text-secondary);">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.77 15.67c-.292-.293-.767-.293-1.06 0l-2.22 2.22V7.65c0-2.068-1.683-3.75-3.75-3.75h-5.85c-.414 0-.75.336-.75.75s.336.75.75.75h5.85c1.24 0 2.25 1.01 2.25 2.25v10.24l-2.22-2.22c-.293-.293-.768-.293-1.061 0s-.293.768 0 1.061l3.5 3.5c.145.147.337.22.53.22s.385-.073.53-.22l3.5-3.5c.294-.292.294-.767.001-1.06zM10.75 20.25H4.9c-1.24 0-2.25-1.01-2.25-2.25V7.65l2.22 2.22c.148.147.34.22.532.22s.384-.073.53-.22c.293-.293.293-.768 0-1.061l-3.5-3.5c-.293-.293-.768-.293-1.061 0l-3.5 3.5c-.294.292-.294.767 0 1.06s.767.294 1.06 0l2.22-2.22V18c0 2.068 1.683 3.75 3.75 3.75h5.85c.414 0 .75-.336.75-.75s-.336-.75-.75-.75z"/>
                                                </svg>
                                                <span><?php echo e($tweet->user->name); ?> shared</span>
                                            </div>
                                            
                                            <!-- User's comment on share -->
                                            <?php if(!empty($tweet->content)): ?>
                                                <p class="text-base leading-relaxed mb-3" style="color: var(--text-primary);"><?php echo e($tweet->content); ?></p>
                                            <?php endif; ?>
                                            
                                            <!-- Original shared tweet -->
                                            <div class="border rounded-lg p-3 theme-transition" style="border-color: var(--border-color); background-color: var(--bg-primary);">
                                                <div class="flex items-start space-x-2 mb-2">
                                                    <div class="flex-shrink-0">
                                                        <?php if($tweet->shared_tweet['user']['profile_picture'] ?? false): ?>
                                                            <img src="<?php echo e(asset('storage/profile_pictures/' . $tweet->shared_tweet['user']['profile_picture'])); ?>" 
                                                                 alt="<?php echo e($tweet->shared_tweet['user']['name']); ?>" 
                                                                 class="w-6 h-6 rounded-full object-cover">
                                                        <?php else: ?>
                                                            <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                                                <span class="text-white font-medium text-xs">
                                                                    <?php echo e(strtoupper(substr($tweet->shared_tweet['user']['name'], 0, 1))); ?>

                                                                </span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-sm" style="color: var(--text-primary);"><?php echo e($tweet->shared_tweet['user']['name']); ?></p>
                                                        <p class="text-xs" style="color: var(--text-secondary);"><?php echo e(\Carbon\Carbon::parse($tweet->shared_tweet['created_at'])->diffForHumans()); ?></p>
                                                    </div>
                                                </div>
                                                <p class="text-sm" style="color: var(--text-primary);"><?php echo e($tweet->shared_tweet['content']); ?></p>
                                            </div>
                                        <?php else: ?>
                                            <?php echo e($tweet->content); ?>

                                            
                                            <!-- Media Content -->
                                            <?php if(isset($tweet->photos) && count($tweet->photos) > 0): ?>
                                                <div class="mt-3">
                                                    <?php if(count($tweet->photos) == 1): ?>
                                                        <img src="<?php echo e(asset('storage/tweet_media/photos/' . $tweet->photos[0])); ?>" 
                                                             alt="Tweet photo" 
                                                             class="w-full max-w-sm rounded-lg object-cover cursor-pointer"
                                                             onclick="openImageModal('<?php echo e(asset('storage/tweet_media/photos/' . $tweet->photos[0])); ?>')">
                                                    <?php else: ?>
                                                        <div class="grid grid-cols-2 gap-1">
                                                            <?php $__currentLoopData = $tweet->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if($index < 4): ?>
                                                                    <div class="relative">
                                                                        <img src="<?php echo e(asset('storage/tweet_media/photos/' . $photo)); ?>" 
                                                                             alt="Tweet photo <?php echo e($index + 1); ?>" 
                                                                             class="w-full h-20 rounded object-cover cursor-pointer"
                                                                             onclick="openImageModal('<?php echo e(asset('storage/tweet_media/photos/' . $photo)); ?>')">
                                                                        <?php if($index == 3 && count($tweet->photos) > 4): ?>
                                                                            <div class="absolute inset-0 bg-black bg-opacity-50 rounded flex items-center justify-center">
                                                                                <span class="text-white font-bold text-sm">+<?php echo e(count($tweet->photos) - 4); ?></span>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if(isset($tweet->video) && $tweet->video): ?>
                                                <div class="mt-3">
                                                    <video controls class="w-full max-w-sm rounded-lg">
                                                        <source src="<?php echo e(asset('storage/tweet_media/videos/' . $tweet->video)); ?>" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Tweet Actions -->
                                    <div class="flex items-center space-x-4" style="color: var(--text-secondary);">
                                        <!-- Like Button -->
                                        <?php if($isAuth): ?>
                                            <button 
                                                onclick="toggleLike(<?php echo e($tweet->id); ?>, this)"
                                                class="flex items-center space-x-1 hover:text-red-500 transition-colors like-btn"
                                            >
                                                <svg class="h-5 w-5 <?php echo e($tweet->user_has_liked ? 'text-red-500 fill-current' : ''); ?>" fill="<?php echo e($tweet->user_has_liked ? 'currentColor' : 'none'); ?>" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                <span class="likes-count"><?php echo e($tweet->likes_count); ?></span>
                                            </button>
                                        <?php else: ?>
                                            <div class="flex items-center space-x-1">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                <span><?php echo e($tweet->likes_count); ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Share Button (only for non-shared tweets and not own tweets) -->
                                        <?php if($isAuth && !isset($tweet->is_share) && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) != $tweet->user->id): ?>
                                            <button 
                                                onclick="shareTweet(<?php echo e($tweet->id); ?>)"
                                                class="flex items-center space-x-1 hover:text-green-500 transition-colors duration-200"
                                                title="Share Tweet"
                                            >
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.77 15.67c-.292-.293-.767-.293-1.06 0l-2.22 2.22V7.65c0-2.068-1.683-3.75-3.75-3.75h-5.85c-.414 0-.75.336-.75.75s.336.75.75.75h5.85c1.24 0 2.25 1.01 2.25 2.25v10.24l-2.22-2.22c-.293-.293-.768-.293-1.061 0s-.293.768 0 1.061l3.5 3.5c.145.147.337.22.53.22s.385-.073.53-.22l3.5-3.5c.294-.292.294-.767.001-1.06zM10.75 20.25H4.9c-1.24 0-2.25-1.01-2.25-2.25V7.65l2.22 2.22c.148.147.34.22.532.22s.384-.073.53-.22c.293-.293.293-.768 0-1.061l-3.5-3.5c-.293-.293-.768-.293-1.061 0l-3.5 3.5c-.294.292-.294.767 0 1.06s.767.294 1.06 0l2.22-2.22V18c0 2.068 1.683 3.75 3.75 3.75h5.85c.414 0 .75-.336.75-.75s-.336-.75-.75-.75z"/>
                                                </svg>
                                                <span class="text-sm">Share</span>
                                            </button>
                                        <?php endif; ?>

                                        <!-- Edit/Delete Actions for own tweets -->
                                        <?php if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $tweet->user->id): ?>
                                            <a href="<?php echo e(route('tweets.edit', $tweet->id)); ?>" class="text-blue-500 hover:text-blue-700 text-sm">
                                                Edit
                                            </a>
                                            <button 
                                                onclick="deleteTweet(<?php echo e($tweet->id); ?>, this)"
                                                class="text-red-500 hover:text-red-700 text-sm"
                                            >
                                                Delete
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--text-secondary);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium" style="color: var(--text-primary);">No tweets found</h3>
                    <p class="mt-1 text-sm" style="color: var(--text-secondary);">No tweets match your search query.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Users Results -->
        <div id="users-content" class="tab-content hidden">
            <?php if($users->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-lg shadow p-4 theme-transition" style="background-color: var(--bg-secondary);">
                            <div class="flex items-center space-x-4">
                                <!-- Profile Picture -->
                                <div class="flex-shrink-0">
                                    <?php if(isset($user->profile_picture) && $user->profile_picture): ?>
                                        <img class="h-12 w-12 rounded-full object-cover" src="<?php echo e(Storage::url('profile_pictures/' . $user->profile_picture)); ?>" alt="<?php echo e($user->name); ?>">
                                    <?php else: ?>
                                        <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                                            <span class="text-white font-medium"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="flex-1">
                                    <a href="<?php echo e(route('profile.show', $user->id)); ?>" class="block">
                                        <h3 class="font-medium hover:underline theme-transition" style="color: var(--text-primary);"><?php echo e($user->name); ?></h3>
                                        <p class="text-sm" style="color: var(--text-secondary);"><?php echo e($user->email); ?></p>
                                    </a>
                                </div>

                                <div>
                                    <a href="<?php echo e(route('profile.show', $user->id)); ?>" class="bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 transition-colors">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--text-secondary);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium" style="color: var(--text-primary);">No users found</h3>
                    <p class="mt-1 text-sm" style="color: var(--text-secondary);">No users match your search query.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--text-secondary);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"></path>
            </svg>
            <h3 class="mt-2 text-lg font-medium" style="color: var(--text-primary);">Start Searching</h3>
            <p class="mt-1" style="color: var(--text-secondary);">Enter a search term above to find tweets and users.</p>
        </div>
    <?php endif; ?>
</div>

<!-- JavaScript for tabs and AJAX functionality -->
<script>
// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.id.replace('-tab', '-content');
            
            // Remove active class from all tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Add active class to clicked tab
            this.classList.add('active', 'border-blue-500', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show selected tab content
            document.getElementById(tabId).classList.remove('hidden');
        });
    });
});

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

// Like functionality
async function toggleLike(tweetId, button) {
    try {
        const response = await fetch(`/tweets/${tweetId}/toggle-like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            const data = await response.json();
            const likesCountElement = button.querySelector('.likes-count');
            const heartIcon = button.querySelector('svg');
            
            likesCountElement.textContent = data.likes_count;
            
            if (data.liked) {
                heartIcon.classList.add('text-red-500', 'fill-current');
                heartIcon.setAttribute('fill', 'currentColor');
            } else {
                heartIcon.classList.remove('text-red-500', 'fill-current');
                heartIcon.setAttribute('fill', 'none');
            }
        } else {
            console.error('Error toggling like');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Delete tweet functionality
async function deleteTweet(tweetId, button) {
    if (confirm('Are you sure you want to delete this tweet?')) {
        try {
            const response = await fetch(`/tweets/${tweetId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    button.closest('.bg-white').remove();
                } else {
                    alert(data.message || 'Error deleting tweet');
                }
            } else {
                alert('Error deleting tweet');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error deleting tweet');
        }
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Jherilyn\MIDTERM_ FORTES\resources\views/search.blade.php ENDPATH**/ ?>