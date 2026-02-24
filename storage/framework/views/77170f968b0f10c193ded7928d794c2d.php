<div class="flex flex-row items-center justify-between w-full mb-1 ml-auto mr-auto flex-nowrap" id="search-form">
    <?php echo Form::open(['method'=>'get', 'class' => 'flex flex-row items-center justify-between w-full mb-1
    ml-auto mr-auto flex-nowrap space-x-4']); ?>

    <?php echo Form::text('filter_name', request('filter_name'), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => '名稱']); ?>

    <?php echo Form::select('filter_county_id', $counties, request('filter_county_id'), ['class' => 'h-12 px-4 border-gray-300
    rounded-md
    shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1']); ?>

    <?php echo Form::text('filter_phone', request('filter_phone'), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => '連絡電話']); ?>

    <?php echo Form::text('filter_address', request('filter_address'), ['class' => 'h-12 px-4 border-gray-300 rounded-md
    shadow-sm
    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => '訓練地址']); ?>

    <button type="submit" class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark">搜尋</button>
    <a href="<?php echo e(request()->url()); ?>"
        class="flex items-center justify-center w-20 h-10 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-mainAdminTextGrayDark">清空</a>
    <?php echo Form::close(); ?>

</div>

<?php $__env->startSection('scripts'); ?>
<?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?>
<?php $__env->stopSection(); ?><?php /**PATH /Users/Marshall/Downloads/RTP-main/resources/views/admin/dp-training-institution/partials/filter.blade.php ENDPATH**/ ?>