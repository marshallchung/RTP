<?php $__env->startSection('title', '登入'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-center w-screen h-screen bg-gradient-to-t from-gray-200 to-white">
    <div class="flex flex-col items-center justify-start w-full max-w-[34rem] border rounded-3xl bg-white">
        <div class="flex flex-col w-full p-8 space-y-4 text-center text-gray-500">
            <span class="text-4xl">內政部消防署</span>
            <div class="">請輸入 Google 驗證器產生的驗證碼，以完成登入</div>
            <a href="/雙因子驗證登入方法.pdf" target="_blank" style="background: #000; color: #fff; padding: 9px; border-radius: 10px;">雙因子驗證操作方法</a>
        </div>
        <div class="relative flex items-start justify-start w-full p-8 text-mainAdminTextGrayDark">
            <form method="POST" action="<?php echo e(route('auth.2fa.verify')); ?>" class="flex flex-col items-center justify-start w-full">
                <?php echo csrf_field(); ?>
                <label class="relative flex flex-col items-start justify-start w-full space-y-2">
                    <span class="text-sm text-gray-500">驗證碼</span>
                    <div class="relative flex flex-row w-full">
                        <div class="absolute top-0 left-0 flex items-center justify-center w-10 h-12">
                            <i class="w-5 h-5 text-gray-400 i-lucide-shield-check"></i>
                        </div>
                        <input type="text" name="one_time_password" placeholder="請輸入 6 位數驗證碼"
                            class="w-full h-12 pl-10 pr-4 placeholder-gray-400 rounded-md shadow-sm border border-gray-300 focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                    </div>
                </label>

                <?php if(session('error')): ?>
                    <div class="border bg-rose-50 border-rose-100 w-full text-rose-400 py-1.5 rounded-sm relative mt-4 px-3">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="border help-block bg-rose-50 border-rose-100 w-full text-rose-400 py-1.5 rounded-sm relative mt-4 px-3">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if(isset($subuser)): ?>
                    <input type="hidden" name="sub_user_id" value="<?php echo e($subuser->id); ?>">
                <?php endif; ?>

                <button type="submit"
                    class="p-2.5 px-10 rounded-md text-center transition-all duration-300 h-12 w-full bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-500/70 mt-8">
                    驗證
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.pixeladmin', ['bodyClass' => 'page-signin'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/Marshall/Downloads/RTP-main/resources/views/admin/auth/2fa.blade.php ENDPATH**/ ?>