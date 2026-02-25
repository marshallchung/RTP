<?php $__env->startSection('title', '新增&重設帳號'); ?>

<?php $__env->startSection('inner_content'); ?>
<div x-data="{
    showRole:'',
}" class="relative w-full max-w-5xl p-4 text-content text-mainAdminTextGrayDark">
    <?php if(in_array(auth()->user()->origin_role, [1, 2, 6])): ?>
    <div class="p-5 mb-4 border-l-2 border-l-lime-400 bg-lime-50/70">
        <a href="<?php echo e(route('admin.users.create-user')); ?>" class="btn btn-lg btn-success">新增帳號</a>
    </div>
    <?php endif; ?>
    <div class="p-5 mb-4 border-l-2 border-l-sky-400 bg-sky-50/70">
        <?php
            $roles = [
                '' => '全選',
                2  => '消防署',
                4  => '縣市政府',
                6  => '社團法人臺灣防災教育訓練學會',
                7  => '防災士培訓機構'
            ];
            ?>
        <?php echo Form::select('filt_role_id', $roles, '', [
        'id' => 'filt_role_id',
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200
        focus:ring-opacity-50 w-full','x-model'=>'showRole'
        ]); ?>

    </div>
    <div class="p-5 mb-4 border-l-2 border-l-sky-400 bg-sky-50/70">
        <p class="m-0"><?php echo e(trans('app.users.reset.info')); ?></p>
        <p class="m-0"><?php echo e(trans('app.users.reset.hint')); ?></p>
    </div>
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b rounded-t bg-mainGray">
                <th class="p-2 font-bold border-r last:border-r-0">帳號</th>
                <th class="p-2 font-bold border-r last:border-r-0">對應帳號</th>
                <th class="w-32 p-2 font-bold border-r last:border-r-0">子帳號</th>
                <th class="p-2 font-bold border-r w-28 last:border-r-0">密碼</th>
                <th class="w-24 p-2 font-bold border-r last:border-r-0">刪除子帳號</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr x-show="showRole=='' || showRole=='<?php echo e($user->origin_role); ?>'"
                class="text-left border-r last:border-r-0 odd:bg-white even:bg-mainLight">
                <td class="p-2 border-r last:border-r-0"><?php echo e($user->username); ?>（<?php echo e($user->name); ?>）</td>
                <td class="p-2 border-r last:border-r-0"></td>
                <td class="p-2 border-r last:border-r-0">
                    <a href="<?php echo e(route('admin.users.create-alias-user', $user)); ?>" class="px-4 text-sm rounded cursor-pointer py-1.5 bg-gray-100
                    hover:bg-gray-50 border">新增子帳號</a>
                </td>
                <td x-data="{showConfirm:false}" class="p-2 border-r last:border-r-0">
                    <?php echo Form::open(['method' => 'PUT', 'route' => ['admin.users.reset.update', $user->id]]); ?>

                    <button
                        class="w-20 flex justify-center items-center text-sm rounded cursor-pointer py-1.5 bg-gray-100  hover:bg-gray-50 border  border-gray-300"
                        x-show="!showConfirm" @click="showConfirm=!showConfirm" type="button">重設密碼</button>
                    <button class="w-20 flex justify-center items-center text-sm text-white rounded cursor-pointer py-1.5
                    bg-rose-500 hover:bg-rose-400" x-show="showConfirm" type="submit">確認</button>
                    <?php echo Form::close(); ?>

                </td>
                <td class="p-2 border-r last:border-r-0"></td>
            </tr>
            <?php if($user->userAliases): ?>
            <?php $__currentLoopData = $user->userAliases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aliasUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr x-show="showRole=='' || showRole=='<?php echo e($user->origin_role); ?>'"
                class="text-left border-r last:border-r-0 odd:bg-white even:bg-mainLight">
                <td class="p-2 border-r last:border-r-0">
                    <i class="fa fa-angle-right"></i> <?php echo e($aliasUser->username); ?>

                </td>
                <td class="p-2 border-r last:border-r-0"><?php echo e($user->username); ?>（<?php echo e($user->name); ?>）</td>
                <td class="p-2 border-r last:border-r-0"></td>
                <td x-data="{showConfirm:false}" class="p-2 border-r last:border-r-0">
                    <?php echo Form::open(['method' => 'PUT', 'route' => ['admin.users.reset.update', $aliasUser->id]]); ?>

                    <?php echo Form::hidden('is_alias', 1); ?>

                    <button
                        class="w-20 flex justify-center items-center text-sm rounded cursor-pointer py-1.5 bg-gray-100  hover:bg-gray-50 border  border-gray-300"
                        x-show="!showConfirm" @click="showConfirm=!showConfirm" type="button">重設密碼</button>
                    <button class="w-20 flex justify-center items-center text-sm text-white rounded cursor-pointer py-1.5
                                        bg-rose-500 hover:bg-rose-400" x-show="showConfirm" type="submit">確認</button>
                    <?php echo Form::close(); ?>

                </td>
                <td x-data="{showConfirm:false}" class="p-2 border-r last:border-r-0">
                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['admin.users.delete-alias-user', $aliasUser]]); ?>

                    <button
                        class="w-20 flex justify-center items-center text-sm rounded cursor-pointer py-1.5 bg-gray-100  hover:bg-gray-50 border  border-gray-300"
                        x-show="!showConfirm" @click="showConfirm=!showConfirm" type="button">刪除子帳號</button>
                    <button class="w-20 flex justify-center items-center text-sm text-white rounded cursor-pointer py-1.5
                                        bg-rose-500 hover:bg-rose-400" x-show="showConfirm" type="submit">確認</button>
                    <?php echo Form::close(); ?>

                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.dashboard', [
'heading' => '新增&重設帳號',
'breadcrumbs' => ['新增&重設帳號']
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/Marshall/Desktop/RTP-main/resources/views/admin/users/reset/index.blade.php ENDPATH**/ ?>