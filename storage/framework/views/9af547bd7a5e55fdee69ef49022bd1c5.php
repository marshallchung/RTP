<div class="bg-white border-[#e9e9e9] border-b py-6 px-[18px] flex flex-row justify-between items-center w-full">
    <?php if(isset($heading)): ?>
    <h3 class="text-[#666] font-light text-header whitespace-nowrap"><?php echo e($heading); ?></h3>
    <?php endif; ?>
    <?php if(isset($header_btn)): ?>
    <div class="flex flex-row flex-wrap items-center justify-end">
        <?php for($i = 0; $i < count($header_btn); $i+=2): ?> <a href="<?php echo e($header_btn[$i+1]); ?>"
            class="flex items-center justify-center h-10 px-4 m-1 text-sm text-white rounded bg-mainCyanDark hover:bg-teal-400">
            <?php echo e($header_btn[$i]); ?></a>
            <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
<?php if(isset($breadcrumbs)): ?>
<ul class="mt-[18px] py-2 px-4 list-none rounded-sm text-sm text-mainTextGray flex flex-row items-center space-x-8">
    <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li
        class="before:text-xs before:content-['>'] before:font-extrabold before:text-mainTextGray before:w-4 before:h-4 relative before:absolute before:-left-[1.125rem] before:top-1.5 first:before:content-['']">
        <?php if(is_array($breadcrumb)): ?>
        <a href="<?php echo e($breadcrumb[1]); ?>"><?php echo e($breadcrumb[0]); ?></a>
        <?php else: ?>
        <?php echo e($breadcrumb); ?>

        <?php endif; ?>
    </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php endif; ?><?php /**PATH /Users/Marshall/Downloads/RTP-main/resources/views/admin/layouts/partials/header.blade.php ENDPATH**/ ?>