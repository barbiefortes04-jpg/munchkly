

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center text-blue-500 hover:text-blue-600 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Home
            </a>
        </div>

        <!-- Profile Card with Cover Photo -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
            <!-- Cover Photo Section -->
            <div class="relative h-48 bg-gradient-to-br from-orange-300 via-pink-300 to-purple-400" style="background-image: <?php echo e($user->cover_photo ? 'url(' . asset('storage/cover_photos/' . $user->cover_photo) . ')' : ''); ?>; background-size: cover; background-position: center;">
                <!-- Cover Photo Upload (only for own profile) -->
                <?php if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $user->id): ?>
                    <div class="absolute top-4 right-4">
                        <form action="<?php echo e(route('profile.upload-cover')); ?>" method="POST" enctype="multipart/form-data" class="inline">
                            <?php echo csrf_field(); ?>
                            <label for="cover_photo" class="cursor-pointer bg-black bg-opacity-50 hover:bg-opacity-70 text-white px-3 py-2 rounded-lg text-sm transition-all backdrop-blur-sm">
                                <i class="fas fa-camera mr-2"></i>Edit Cover
                            </label>
                            <input type="file" id="cover_photo" name="cover_photo" class="hidden" accept="image/*" onchange="this.form.submit()">
                        </form>
                    </div>
                <?php endif; ?>
                
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
                        <?php if($user->profile_picture): ?>
                            <img src="<?php echo e(asset('storage/profile_pictures/' . $user->profile_picture)); ?>" 
                                 alt="<?php echo e($user->name); ?>" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-xl">
                        <?php else: ?>
                            <div class="w-32 h-32 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center border-4 border-white shadow-xl">
                                <span class="text-white font-bold text-4xl">
                                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Profile Picture Upload (only for own profile) -->
                        <?php if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $user->id): ?>
                            <div class="absolute bottom-2 right-2">
                                <form action="<?php echo e(route('profile.upload-picture')); ?>" method="POST" enctype="multipart/form-data" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <label for="profile_picture" class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition-colors">
                                        <i class="fas fa-camera text-sm"></i>
                                    </label>
                                    <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*" onchange="this.form.submit()">
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Name and Actions -->
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4"><?php echo e($user->name); ?></h1>
                    
                    <!-- Follow Button (only if viewing another user's profile) -->
                    <?php if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) != $user->id): ?>
                        <div class="flex space-x-3 justify-center">
                            <button onclick="toggleFollow(<?php echo e($user->id); ?>)" 
                                    class="follow-btn px-8 py-3 rounded-full font-medium transition-all transform hover:scale-105 <?php echo e($isFollowing ? 'bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300' : 'bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white shadow-lg'); ?>"
                                    id="follow-btn-<?php echo e($user->id); ?>">
                                <i class="fas <?php echo e($isFollowing ? 'fa-user-check' : 'fa-user-plus'); ?> mr-2"></i>
                                <span id="follow-text-<?php echo e($user->id); ?>"><?php echo e($isFollowing ? 'Following' : 'Follow'); ?></span>
                            </button>
                            
                            <?php if($isFollowing): ?>
                                <button onclick="toggleCloseFriend(<?php echo e($user->id); ?>)" 
                                        class="close-friend-btn px-6 py-3 rounded-full font-medium transition-all transform hover:scale-105 <?php echo e($isCloseFriend ? 'bg-green-100 hover:bg-green-200 text-green-700 border border-green-300' : 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300'); ?>"
                                        id="close-friend-btn-<?php echo e($user->id); ?>">
                                    <i class="fas <?php echo e($isCloseFriend ? 'fa-heart' : 'fa-heart-o'); ?> mr-2" style="color: <?php echo e($isCloseFriend ? '#10B981' : '#6B7280'); ?>"></i>
                                    <span id="close-friend-text-<?php echo e($user->id); ?>"><?php echo e($isCloseFriend ? 'Close Friend' : 'Add Close Friend'); ?></span>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Profile Stats -->
                <div class="grid grid-cols-4 gap-6 p-6 bg-gray-50 rounded-xl">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600"><?php echo e($tweetCount); ?></div>
                        <div class="text-sm text-gray-600 font-medium"><?php echo e($tweetCount === 1 ? 'Tweet' : 'Tweets'); ?></div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-500"><?php echo e($totalLikesReceived); ?></div>
                        <div class="text-sm text-gray-600 font-medium"><?php echo e($totalLikesReceived === 1 ? 'Like' : 'Likes'); ?></div>
                    </div>
                    <div class="text-center cursor-pointer hover:bg-gray-100 rounded-lg p-2 transition-colors" onclick="toggleFollowersModal()">
                        <div class="text-2xl font-bold text-green-500" id="followers-count"><?php echo e($followersCount); ?></div>
                        <div class="text-sm text-gray-600 font-medium hover:text-blue-600"><?php echo e($followersCount === 1 ? 'Follower' : 'Followers'); ?></div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-500"><?php echo e($followingCount); ?></div>
                        <div class="text-sm text-gray-600 font-medium">Following</div>
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
                        <?php if($followers->count() > 0): ?>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $followers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $follower): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center space-x-3">
                                        <?php if($follower->profile_picture): ?>
                                            <img src="<?php echo e(asset('storage/profile_pictures/' . $follower->profile_picture)); ?>" 
                                                 alt="<?php echo e($follower->name); ?>" 
                                                 class="w-10 h-10 rounded-full object-cover">
                                        <?php else: ?>
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                <span class="text-white font-medium text-sm">
                                                    <?php echo e(strtoupper(substr($follower->name, 0, 1))); ?>

                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-1">
                                            <a href="<?php echo e(route('profile.show', $follower->id)); ?>" class="font-medium text-gray-900 hover:text-blue-600">
                                                <?php echo e($follower->name); ?>

                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-gray-500">
                                <i class="fas fa-users text-3xl mb-2"></i>
                                <p>No followers yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- User's Tweets -->
        <div class="space-y-4">
            <?php if($tweets->count() > 0): ?>
                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-stream text-gray-600 mr-2"></i><?php echo e($user->name); ?>'s Tweets
                </h2>
                
                <?php $__currentLoopData = $tweets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tweet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow duration-200">
                        <!-- Tweet Header -->
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <?php if($tweet->user->profile_picture ?? false): ?>
                                        <img src="<?php echo e(asset('storage/profile_pictures/' . $tweet->user->profile_picture)); ?>" 
                                             alt="<?php echo e($tweet->user->name); ?>" 
                                             class="w-10 h-10 rounded-full object-cover">
                                    <?php else: ?>
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">
                                                <?php echo e(strtoupper(substr($tweet->user->name, 0, 1))); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo e($tweet->user->name); ?></p>
                                    <p class="text-sm text-gray-500">
                                        <?php echo e($tweet->time_ago); ?>

                                        <?php if($tweet->is_edited && $isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $user->id): ?>
                                            <span class="text-orange-500">• edited</span>
                                        <?php endif; ?>
                                        
                                        <!-- Privacy Indicator -->
                                        <?php if(isset($tweet->privacy) && $tweet->privacy !== 'public'): ?>
                                            <span class="ml-2">
                                                <?php if($tweet->privacy === 'followers'): ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                        Followers
                                                    </span>
                                                <?php elseif($tweet->privacy === 'close_friends'): ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                                        </svg>
                                                        Close Friends
                                                    </span>
                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Tweet Actions (for tweet owner viewing their own profile) -->
                            <?php if($isAuth && $authUser && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $tweet->user->id && (is_array($authUser) ? $authUser['id'] : $authUser->id) == $user->id): ?>
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('tweets.edit', $tweet->id)); ?>" class="text-gray-400 hover:text-blue-500 transition-colors duration-200"
                                       title="Edit Tweet">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteTweet(<?php echo e($tweet->id); ?>)" class="text-gray-400 hover:text-red-500 transition-colors duration-200"
                                            title="Delete Tweet">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Tweet Content -->
                        <div class="mt-4">
                            <!-- Share indicator -->
                            <?php if(isset($tweet->is_share) && $tweet->is_share): ?>
                                <div class="flex items-center text-gray-500 text-sm mb-3">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.77 15.67c-.292-.293-.767-.293-1.06 0l-2.22 2.22V7.65c0-2.068-1.683-3.75-3.75-3.75h-5.85c-.414 0-.75.336-.75.75s.336.75.75.75h5.85c1.24 0 2.25 1.01 2.25 2.25v10.24l-2.22-2.22c-.293-.293-.768-.293-1.061 0s-.293.768 0 1.061l3.5 3.5c.145.147.337.22.53.22s.385-.073.53-.22l3.5-3.5c.294-.292.294-.767.001-1.06zM10.75 20.25H4.9c-1.24 0-2.25-1.01-2.25-2.25V7.65l2.22 2.22c.148.147.34.22.532.22s.384-.073.53-.22c.293-.293.293-.768 0-1.061l-3.5-3.5c-.293-.293-.768-.293-1.061 0l-3.5 3.5c-.294.292-.294.767 0 1.06s.767.294 1.06 0l2.22-2.22V18c0 2.068 1.683 3.75 3.75 3.75h5.85c.414 0 .75-.336.75-.75s-.336-.75-.75-.75z"/>
                                    </svg>
                                    <span><?php echo e($tweet->user->name); ?> shared</span>
                                </div>
                                
                                <!-- User's comment on share -->
                                <?php if(!empty($tweet->content)): ?>
                                    <p class="text-gray-900 text-base leading-relaxed mb-3"><?php echo e($tweet->content); ?></p>
                                <?php endif; ?>
                                
                                <!-- Original shared tweet -->
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <div class="flex items-start space-x-3 mb-2">
                                        <div class="flex-shrink-0">
                                            <?php if($tweet->shared_tweet['user']['profile_picture'] ?? false): ?>
                                                <img src="<?php echo e(asset('storage/profile_pictures/' . $tweet->shared_tweet['user']['profile_picture'])); ?>" 
                                                     alt="<?php echo e($tweet->shared_tweet['user']['name']); ?>" 
                                                     class="w-8 h-8 rounded-full object-cover">
                                            <?php else: ?>
                                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-medium text-xs">
                                                        <?php echo e(strtoupper(substr($tweet->shared_tweet['user']['name'], 0, 1))); ?>

                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 text-sm">
                                                <a href="<?php echo e(route('profile.show', $tweet->shared_tweet['user']['id'])); ?>" class="hover:text-blue-600">
                                                    <?php echo e($tweet->shared_tweet['user']['name']); ?>

                                                </a>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <?php echo e(\Carbon\Carbon::parse($tweet->shared_tweet['created_at'])->diffForHumans()); ?>

                                            </p>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-800 text-sm mb-2"><?php echo e($tweet->shared_tweet['content']); ?></p>
                                    
                                    <!-- Original tweet media -->
                                    <?php if(isset($tweet->shared_tweet['photos']) && count($tweet->shared_tweet['photos']) > 0): ?>
                                        <div class="mb-2">
                                            <?php if(count($tweet->shared_tweet['photos']) == 1): ?>
                                                <img src="<?php echo e(asset('storage/tweet_media/photos/' . $tweet->shared_tweet['photos'][0])); ?>" 
                                                     alt="Tweet photo" 
                                                     class="w-full max-w-sm rounded-lg object-cover cursor-pointer"
                                                     onclick="openImageModal('<?php echo e(asset('storage/tweet_media/photos/' . $tweet->shared_tweet['photos'][0])); ?>')">
                                            <?php else: ?>
                                                <div class="grid grid-cols-2 gap-1">
                                                    <?php $__currentLoopData = $tweet->shared_tweet['photos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($index < 4): ?>
                                                            <img src="<?php echo e(asset('storage/tweet_media/photos/' . $photo)); ?>" 
                                                                 alt="Tweet photo <?php echo e($index + 1); ?>" 
                                                                 class="w-full h-20 rounded object-cover cursor-pointer"
                                                                 onclick="openImageModal('<?php echo e(asset('storage/tweet_media/photos/' . $photo)); ?>')">
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($tweet->shared_tweet['video']) && $tweet->shared_tweet['video']): ?>
                                        <div class="mb-2">
                                            <video controls class="w-full max-w-sm rounded-lg">
                                                <source src="<?php echo e(asset('storage/tweet_media/videos/' . $tweet->shared_tweet['video'])); ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <!-- Regular tweet content -->
                                <p class="text-gray-900 text-base leading-relaxed"><?php echo e($tweet->content); ?></p>
                                
                                <!-- Media Content -->
                                <?php if(isset($tweet->photos) && count($tweet->photos) > 0): ?>
                                    <div class="mt-3">
                                        <?php if(count($tweet->photos) == 1): ?>
                                            <img src="<?php echo e(asset('storage/tweet_media/photos/' . $tweet->photos[0])); ?>" 
                                                 alt="Tweet photo" 
                                                 class="w-full max-w-lg rounded-lg object-cover cursor-pointer"
                                                 onclick="openImageModal('<?php echo e(asset('storage/tweet_media/photos/' . $tweet->photos[0])); ?>')">
                                        <?php else: ?>
                                            <div class="grid <?php echo e(count($tweet->photos) == 2 ? 'grid-cols-2' : 'grid-cols-2'); ?> gap-2">
                                                <?php $__currentLoopData = $tweet->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($index < 4): ?>
                                                        <div class="relative">
                                                            <img src="<?php echo e(asset('storage/tweet_media/photos/' . $photo)); ?>" 
                                                                 alt="Tweet photo <?php echo e($index + 1); ?>" 
                                                                 class="w-full h-40 rounded-lg object-cover cursor-pointer"
                                                                 onclick="openImageModal('<?php echo e(asset('storage/tweet_media/photos/' . $photo)); ?>')">
                                                            <?php if($index == 3 && count($tweet->photos) > 4): ?>
                                                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                                                                    <span class="text-white font-bold text-xl">+<?php echo e(count($tweet->photos) - 4); ?></span>
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
                                        <video controls class="w-full max-w-lg rounded-lg">
                                            <source src="<?php echo e(asset('storage/tweet_media/videos/' . $tweet->video)); ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Tweet Footer -->
                        <div class="mt-4 flex items-center justify-between pt-3 border-t border-gray-100">
                            <div class="flex items-center space-x-6">
                                <!-- Like Button -->
                                <?php if($isAuth): ?>
                                    <button onclick="toggleLike(<?php echo e($tweet->id); ?>)" 
                                            class="like-btn flex items-center space-x-1 text-gray-400 hover:text-red-500 transition-colors duration-200"
                                            id="like-btn-<?php echo e($tweet->id); ?>">
                                        <i class="far fa-heart" id="like-icon-<?php echo e($tweet->id); ?>"></i>
                                        <span class="text-sm font-medium" id="like-count-<?php echo e($tweet->id); ?>"><?php echo e($tweet->likes_count); ?></span>
                                    </button>
                                <?php else: ?>
                                    <div class="flex items-center space-x-1 text-gray-400">
                                        <i class="far fa-heart"></i>
                                        <span class="text-sm font-medium"><?php echo e($tweet->likes_count); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <span class="text-xs text-gray-400"><?php echo e($tweet->formatted_created_at); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="text-center py-12 bg-white rounded-lg shadow">
                    <i class="fas fa-feather-alt text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No tweets yet</h3>
                    <p class="text-gray-500">
                        <?php if($isAuth && $authUser && $user->id == (is_array($authUser) ? $authUser['id'] : $authUser->id)): ?>
                            <a href="<?php echo e(route('home')); ?>" class="text-blue-500 hover:text-blue-600">Start sharing your thoughts!</a>
                        <?php else: ?>
                            <?php echo e($user->name); ?> hasn't posted any tweets yet.
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($isAuth): ?>
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
                    followBtn.className = 'follow-btn px-8 py-3 rounded-full font-medium transition-all transform hover:scale-105 bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300';
                    followBtn.innerHTML = '<i class="fas fa-user-check mr-2"></i><span id="follow-text-' + userId + '">Following</span>';
                } else {
                    followBtn.className = 'follow-btn px-8 py-3 rounded-full font-medium transition-all transform hover:scale-105 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white shadow-lg';
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
                    closeFriendBtn.className = 'close-friend-btn px-6 py-3 rounded-full font-medium transition-all transform hover:scale-105 bg-green-100 hover:bg-green-200 text-green-700 border border-green-300';
                    icon.style.color = '#10B981';
                    icon.className = 'fas fa-heart mr-2';
                    closeFriendText.textContent = 'Close Friend';
                } else {
                    closeFriendBtn.className = 'close-friend-btn px-6 py-3 rounded-full font-medium transition-all transform hover:scale-105 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300';
                    icon.style.color = '#6B7280';
                    icon.className = 'fas fa-heart-o mr-2';
                    closeFriendText.textContent = 'Add Close Friend';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Jherilyn\MIDTERM_ FORTES\resources\views/profile/show.blade.php ENDPATH**/ ?>