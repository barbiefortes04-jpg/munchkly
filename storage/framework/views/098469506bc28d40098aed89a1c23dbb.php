

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center px-4">
    <!-- Main Welcome Content -->
    <div class="text-center max-w-md mx-auto">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-5xl font-bold text-gray-800 mb-4">Welcome</h1>
            <h2 class="text-3xl font-semibold text-gray-600">to Munchkly</h2>
        </div>
        
        <!-- Description -->
        <div class="mb-8">
            <p class="text-gray-600 text-lg">
                Share your thoughts and connect with friends.
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo e(route('register')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                Join Now
            </a>
            <a href="<?php echo e(route('login')); ?>" class="bg-white hover:bg-gray-50 text-gray-800 font-semibold py-3 px-6 rounded-lg border border-gray-300 transition-colors duration-200">
                Sign In
            </a>
        </div>
        
        <!-- Footer -->
        <div class="mt-12">
            <p class="text-gray-400 text-sm">
                © 2025 Munchkly. Connect, Share, Inspire.
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Jherilyn\MIDTERM_ FORTES\resources\views/welcome.blade.php ENDPATH**/ ?>