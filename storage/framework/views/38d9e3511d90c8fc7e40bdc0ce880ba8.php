<?php $__env->startSection('title', $data->title); ?>

<?php $__env->startSection('inner_content'); ?>
<div class="md:w-67/100 xl:w-75/100">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <?php echo Form::model($data, ['route' => ['admin.dp-training-institution.update', $data->id], 'method' => 'put',
        'files' => true]); ?>

        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">編輯</div>
        <div class="flex flex-col w-full p-5 m-0 space-y-4 bg-white">

            <div class="flex flex-row flex-wrap text-center">
                <?php echo Form::label('name', '名稱（必填）'); ?>

                <?php echo Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']); ?>

            </div>

            <div class="flex flex-row flex-wrap text-center">
                <?php echo Form::label('county_id', '縣市（必填）'); ?>

                <?php echo Form::select('county_id', $counties, request('county_id'), ['class' => 'h-12 px-4 border-gray-300
                rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                'required']); ?>

            </div>

            <div class="flex flex-row flex-wrap text-center">
                <?php echo Form::label('phone', '連絡電話（必填）'); ?>

                <?php echo Form::text('phone', null, [
                'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                'placeholder' => '範例：02-81234567',
                'required',
                ]); ?>

            </div>

            <div class="flex flex-row flex-wrap text-center">
                <?php echo Form::label('address', '訓練地址（選填）'); ?>

                <?php echo Form::text('address', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

            </div>

            <div class="flex flex-row flex-wrap text-center">
                <?php echo Form::label('addressId', '地址編碼（選填）'); ?>

                <?php echo Form::text('addressId', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

            </div>

            <div class="flex flex-row flex-wrap text-center">
                <?php echo Form::label('url', '官方網址（選填）'); ?>

                <?php echo Form::url('url', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

            </div>

            <?php echo Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center
            items-center h-10 bg-mainCyanDark
            rounded']); ?>

            <?php echo Form::close(); ?>


            <a @click="showDeleteModel=true" data-toggle="modal" data-target="#js-delete-modal"
                class="flex items-center justify-center w-full h-10 text-white bg-orange-500 rounded cursor-pointer hover:bg-orange-400">刪除</a>

        </div>

        <?php echo Form::model($data, ['method' => 'DELETE', 'route' => ['admin.dp-training-institution.destroy', $data->id]]); ?>

        <?php echo $__env->make('admin.layouts.partials.genericDeleteForm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo Form::close(); ?>

    </div>
</div>

<script src="<?php echo e(asset('scripts/genericPostForm.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 防災士培訓機構',
'breadcrumbs' => [
['防災士培訓機構', route('admin.dp-training-institution.index')],
'編輯'
]
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/Marshall/Downloads/RTP-main/resources/views/admin/dp-training-institution/edit.blade.php ENDPATH**/ ?>