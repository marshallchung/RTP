<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="referrer" content="strict-origin-when-cross-origin" />
    <meta property="og:title" content="<?php echo $__env->yieldContent('title'); ?> - <?php echo e(config('app.cht_name')); ?>">
    <meta property="og:url" content="<?php echo e(URL::current()); ?>">
    <meta property="og:image" content="<?php echo e(asset('image/logo.jpg')); ?>">
    <meta property="og:description" content="<?php echo e(config('app.cht_name')); ?>">

    <link rel="icon" type="image/ico" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('apple-touch-icon.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('favicon-16x16.png')); ?>">
    <link rel="manifest" href="<?php echo e(asset('site.webmanifest')); ?>">

    <title><?php echo $__env->yieldContent('title'); ?> - <?php echo e(config('app.cht_name')); ?></title>

    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <?php echo \Livewire\Livewire::styles(['nonce' => csp_nonce()]); ?>

    <?php echo $__env->yieldContent('css'); ?>
</head>

<body x-data="{scrollTop:0}" @scroll.window="scrollTop=window.pageYOffset" x-init="$nextTick(() => {
    window.Laravel = {'csrfToken':'<?php echo e(csrf_token()); ?>'};
    scrollTop=window.pageYOffset;
     })">
    <div class="flex flex-col items-center justify-start w-screen min-h-screen mx-auto" id="app">
        
        <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->yieldContent('beforeContainer'); ?>

        
        <div class="<?php echo $__env->yieldContent('container_class', 'container'); ?> block flex-grow px-4 lg:px-0" id="app1">
            <button type="button" @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="scrollTop>300"
                x-transition.duration.500ms
                class="fixed z-50 items-center justify-center hidden w-20 h-20 text-white border border-white rounded-full bg-mainBlue hover:bg-mainBlueDark bottom-44 right-8"
                :class="{'flex':scrollTop>300,'hidden':scrollTop<=300}">
                <span class="text-2xl ">Top</span>
            </button>
            <a href="https://www.facebook.com/groups/bousaiTW" x-transition.duration.500ms target="_blank"
                class="fixed z-50 flex items-center justify-center w-20 h-20 bg-white rounded-full text-mainBlue hover:text-mainBlueDark bottom-20 right-8">
                <i class="w-full h-full i-fa6-brands-facebook"></i>
            </a>
            <?php echo $__env->make('flash::message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->yieldContent('content'); ?>
        </div>

        
        <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        // Google分析
            <?php if(env('GOOGLE_ANALYSIS')): ?>
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
                ga('create', '<?php echo e(env('GOOGLE_ANALYSIS')); ?>', 'auto');
                ga('send', 'pageview');
            <?php endif; ?>
        }, false);
    </script>
    <?php echo $__env->yieldContent('js'); ?>
    <?php echo \Livewire\Livewire::scripts(['nonce'=> csp_nonce()]); ?>

    <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer>
    </script>
</body>

</html><?php /**PATH /Users/Marshall/Desktop/RTP-main/resources/views/layouts/app.blade.php ENDPATH**/ ?>