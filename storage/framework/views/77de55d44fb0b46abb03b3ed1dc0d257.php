<footer class="flex flex-col items-center justify-center w-full bg-mainYellow">
    <div class="flex flex-row flex-wrap items-start justify-around w-full max-w-5xl px-4 py-3 text-[rgb(136,136,140)]">
        <?php $__currentLoopData = Menu::get('right')->roots(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($menu->title!=='English' && $menu->title!=='業務人員版'&& $menu->title!=='登入'&& $menu->title!=='登出'): ?>
        <div class="py-2">
            <h5 class="pb-2 mr-2 text-xl whitespace-nowrap">
                <?php if($menu->link && $menu->url() != 'javascript:void(0)'): ?>
                <a href="<?php echo e($menu->url()); ?>" class=" hover:no-underline hover:text-mainBlue"><?php echo e($menu->title); ?></a>
                <?php else: ?>
                <span><?php echo e($menu->title); ?></span>
                <?php endif; ?>
            </h5>
            <ul class="list-none">
                <?php $__currentLoopData = $menu->children(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($item->link): ?>
                <li><a href="<?php echo e($item->url()); ?>" class=" hover:no-underline hover:text-mainBlue"><?php echo e($item->title); ?></a>
                </li>
                <?php else: ?>
                <li><?php echo e($item->title); ?></li>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <img src="/image/footer1.jpg" class="object-cover w-full h-auto">
    <div class="flex flex-col items-center justify-center w-full text-sm text-white bg-mainBlue">
        <div class="w-full px-6 py-4 max-w-[1920px]">
            <div class="flex-row items-start justify-start flex-1 hidden w-full pb-3 space-x-2 sm:flex">
                <a href="<?php echo e(route('static-page', 'privacy')); ?>"
                    class=" underline-offset-4 hover:text-mainYellow">隱私權政策</a>
                <a href="<?php echo e(route('static-page', 'security')); ?>"
                    class=" underline-offset-4 hover:text-mainYellow">網站安全政策</a>
                <a href="<?php echo e(route('static-page', 'opendata')); ?>"
                    class=" underline-offset-4 hover:text-mainYellow">政府網站資料開放宣告</a>
                <a href="<?php echo e(route('static-page', 'navigation')); ?>"
                    class="text-left sm:text-center underline-offset-4 hover:text-mainYellow">網站導覽</a>
                <a href="https://www.webcheck.nat.gov.tw/dashboard.aspx"
                    class="text-left text-white sm:text-right hover:text-mainYellow">政府網站流量儀表板</a>
            </div>
            <div class="flex flex-col pb-3 sm:hidden sm:flex-row">
                <div class="flex flex-row items-start justify-around flex-1 w-full">
                    <a href="<?php echo e(route('static-page', 'privacy')); ?>"
                        class="flex-1 underline-offset-4 hover:text-mainYellow">隱私權政策</a>
                    <a href="<?php echo e(route('static-page', 'security')); ?>"
                        class="flex-1 underline-offset-4 hover:text-mainYellow">網站安全政策</a>
                    <a href="<?php echo e(route('static-page', 'navigation')); ?>"
                        class="flex-1 text-left sm:text-center underline-offset-4 hover:text-mainYellow">網站導覽</a>
                </div>
                <div class="flex flex-row items-start justify-around flex-1 w-full">
                    <a href="<?php echo e(route('static-page', 'opendata')); ?>"
                        class="flex-1 underline-offset-4 hover:text-mainYellow">政府網站資料開放宣告</a>
                    <a href="https://www.webcheck.nat.gov.tw/dashboard.aspx"
                        class="flex-1 text-left text-white sm:text-right hover:text-mainYellow ">政府網站流量儀表板</a>
                    <div class="flex-1"></div>
                </div>
            </div>
            <?php
            $totalVisitorCounter = App\Counter::firstOrCreate(['name' => 'total_visitor'], ['count' => 37600]);
            $totalVisitorCounter->count += 1;
            $totalVisitorCounter->save();
            ?>
            <div class="flex flex-col items-center justify-start sm:flex-row sm:justify-between">
                <div class="flex flex-col items-center justify-center sm:justify-start sm:flex-row">
                    <span class="whitespace-nowrap">地址：231007 新北市新店區北新路3段200號8樓</span>
                    <span class="hidden whitespace-nowrap sm:block">&nbsp;|&nbsp;</span>
                    <span class="whitespace-nowrap">防災士客服專線：02-81966118</span>
                    <span class="hidden whitespace-nowrap sm:block">&nbsp;|&nbsp;</span>
                    <span class="whitespace-nowrap">客服專線：02-81966123、02-81966122</span>
                </div>
            </div>
            <div class="flex flex-col items-center justify-start sm:flex-row sm:justify-between">
                <div class="flex flex-col-reverse items-center justify-center flex-1 sm:justify-around sm:flex-row">
                    <div class="flex flex-row items-center justify-center sm:justify-start">
                        <a href="https://www.facebook.com/groups/bousaiTW" target="_blank"
                            class=" hover:no-underline hover:text-transparent">
                            <img src="/image/icon_footer_fb.svg" class="w-7 h-7" title="防災士。防災事">
                        </a>
                        <a href="mailto:hsuyaya@nfa.gov.tw" class=" hover:no-underline hover:text-transparent">
                            <img src="/image/icon_footer_mail.svg" class="w-7 h-7" title="客服信箱">
                        </a>
                        <a href="mailto:tdrvtiedp@gmail.com" class=" hover:no-underline hover:text-transparent">
                            <img src="/image/icon_footer_mail.svg" class="w-7 h-7" title="防災士客服信箱">
                        </a>
                    </div>
                    <span class="mx-4 text-center whitespace-nowrap sm:flex-1">©2023 All rights reserved.</span>
                    <div
                        class="flex flex-row items-center justify-start pt-1 space-x-2 whitespace-nowrap sm:pt-0 sm:text-right sm:flex-1">
                        <span>
                            更新日期：<?php echo e(date('Y-m-d')); ?>&nbsp;|&nbsp;瀏覽人次：
                            <?php echo e($totalVisitorCounter->count); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer><?php /**PATH /Users/Marshall/Downloads/RTP-main/resources/views/components/footer.blade.php ENDPATH**/ ?>