<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo $__env->yieldContent('title', 'HC COM-SYS Říčany'); ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:ital,wght@0,300;0,400;0,600;0,700;1,400&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="icon" type="image/png" href="<?php echo e(asset('assets/img/HC_Ricany_logo.png')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
<?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<?php echo $__env->yieldContent('content'); ?>
<script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\MSI\Documents\GitRepos\_personal_\hc_ricany\resources\views/layouts/hc.blade.php ENDPATH**/ ?>