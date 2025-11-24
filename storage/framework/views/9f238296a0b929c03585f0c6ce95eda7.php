<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Munchkly')); ?> - Welcome</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'system-ui', 'sans-serif']
                },
                animation: {
                    'fade-in-up': 'fade-in-up 0.6s ease-out'
                },
                keyframes: {
                    'fade-in-up': {
                        '0%': {
                            opacity: '0',
                            transform: 'translateY(20px)'
                        },
                        '100%': {
                            opacity: '1',
                            transform: 'translateY(0)'
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .animation-delay-200 {
            animation-delay: 200ms;
        }
        .animation-delay-400 {
            animation-delay: 400ms;
        }
        .animation-delay-600 {
            animation-delay: 600ms;
        }
        .animation-delay-800 {
            animation-delay: 800ms;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html><?php /**PATH C:\Users\Jherilyn\MIDTERM_ FORTES\resources\views/layouts/welcome.blade.php ENDPATH**/ ?>