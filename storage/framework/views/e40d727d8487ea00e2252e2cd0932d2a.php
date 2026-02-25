<!DOCTYPE html>
<!--[if IE 8]>
<html class="ie8"> <![endif]-->
<!--[if IE 9]>
<html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="gt-ie8 gt-ie9 not-ie" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="strict-origin-when-cross-origin" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>內政部消防署 &ndash; <?php echo $__env->yieldContent('title'); ?></title>

    <link rel="icon" type="image/ico" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('apple-touch-icon.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('favicon-16x16.png')); ?>">
    <link rel="manifest" href="<?php echo e(asset('site.webmanifest')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <?php echo \Livewire\Livewire::styles(['nonce' => csp_nonce()]); ?>


    <?php echo $__env->yieldContent('styles'); ?>
</head>

<body class="theme-admin">
    <div x-data="{
    openMMC:true,
    toggleMMC(){
        this.openMMC=!this.openMMC;
    },
}" class="relative w-screen min-h-screen bg-mainLight text-mainAdminTextGrayDark" x-init="$nextTick(() => {
    <?php if(Auth::user() && Auth::user()->change_default): ?>
    document.addEventListener('click', function(event) {
        console.log(event.target.tagName);
        if((event.target.tagName=='A' || event.target.tagName=='SPAN') && event.target.innerText!=='改密碼'){
            event.preventDefault();
            event.stopPropagation();
            alert('請先變更預設密碼');
        }
    }, true);
    <?php endif; ?>
    })">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <?php echo $__env->yieldContent('scripts'); ?>
    <?php echo \Livewire\Livewire::scripts(['nonce'=> csp_nonce()]); ?>

    <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer>
    </script>
</body>

</html><?php /**PATH /Users/Marshall/Desktop/RTP-main/resources/views/admin/layouts/pixeladmin.blade.php ENDPATH**/ ?>