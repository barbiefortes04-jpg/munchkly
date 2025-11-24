<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Munchkly')); ?> - Welcome</title>

    <!-- Google Fonts for Kawaii Style -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            extend: {
                colors: {
                    kawaii: {
                        pink: '#ec4899',
                        lightPink: '#f472b6',
                        superLight: '#fdf2f8'
                    }
                },
                fontFamily: {
                    kawaii: ['Nunito', 'Comic Sans MS', 'cursive', 'sans-serif']
                }
            }
        }
    </script>
</head>
<body class="font-kawaii antialiased">
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html><?php /**PATH C:\Users\Jherilyn\MIDTERM_ FORTES\resources\views/layouts/welcome.blade.php ENDPATH**/ ?>