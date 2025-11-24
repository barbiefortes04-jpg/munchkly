

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex flex-col items-center justify-center px-4 relative">
    <!-- Main Welcome Content -->
    <div class="relative z-10 text-center max-w-4xl mx-auto">
        <!-- Logo and Brand -->
        <div class="mb-12 animate-fade-in-up">
            <div class="flex items-center justify-center mb-8">
                <div class="bg-white rounded-full p-3 shadow-lg mr-4">
                    <img src="<?php echo e(asset('images/logo.jpg')); ?>" alt="Munchkly Logo" class="w-16 h-16 rounded-full object-cover">
                </div>
                <div>
                    <h1 class="text-5xl font-bold text-gray-900">Munchkly</h1>
                    <p class="text-xl text-gray-600 mt-2">Social Media Platform</p>
                </div>
            </div>
        </div>
        
        <!-- Description -->
        <div class="mb-16 animate-fade-in-up animation-delay-200">
            <h2 class="text-3xl font-semibold text-gray-800 mb-4">
                Share your thoughts and connect with friends
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Join our community to share ideas, discover new perspectives, and build meaningful connections with people who matter.
            </p>
        </div>
        
        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16 animate-fade-in-up animation-delay-400">
            <div class="bg-white rounded-lg p-8 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4 mx-auto">
                    <i class="fas fa-edit text-blue-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Share Content</h3>
                <p class="text-gray-600 text-sm">Express yourself with posts, images, and multimedia content</p>
            </div>
            <div class="bg-white rounded-lg p-8 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4 mx-auto">
                    <i class="fas fa-users text-green-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Build Network</h3>
                <p class="text-gray-600 text-sm">Connect and engage with like-minded individuals</p>
            </div>
            <div class="bg-white rounded-lg p-8 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4 mx-auto">
                    <i class="fas fa-search text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Discover</h3>
                <p class="text-gray-600 text-sm">Explore trending topics and fresh perspectives</p>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12 animate-fade-in-up animation-delay-600">
            <a href="<?php echo e(route('register')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-lg transition-colors duration-200">
                Get Started
            </a>
            <a href="<?php echo e(route('login')); ?>" class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-8 rounded-lg border border-gray-300 transition-colors duration-200">
                Sign In
            </a>
        </div>
        
        <!-- Footer -->
        <div class="animate-fade-in-up animation-delay-800">
            <p class="text-gray-500 text-sm">
                © 2025 Munchkly. All rights reserved.
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Jherilyn\MIDTERM_ FORTES\resources\views/welcome.blade.php ENDPATH**/ ?>