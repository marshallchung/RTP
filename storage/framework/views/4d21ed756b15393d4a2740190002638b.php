<?php $__env->startSection('title', $data->title); ?>

<?php $__env->startSection('inner_content'); ?>

<div class="flex flex-row items-start justify-start w-full" x-data="{
    showDeleteModel:false,
}" x-init="$nextTick(() => {
        const removeFiles=document.querySelectorAll('.js-remove-file');
        if(removeFiles && removeFiles.length>0){
            for(fileIdx in removeFiles){
                removeFiles[fileIdx].onclick = function(event){
                    event.preventDefault();
                    var removedFilesInput = document.getElementById('js-removed-files');
                    var removedFiles = JSON.parse(removedFilesInput.value);
                    var file = event.target.closest('.well');
                    var id = file.dataset.id;
                    removedFiles.push(id);
                    removedFilesInput.value=JSON.stringify(removedFiles);
                    return file.remove();
                };
            }
        }
     })">
    <div class="flex flex-row items-start justify-start w-full px-4">
        <div class="relative w-full max-w-4xl">
            <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
                <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                    編輯
                </div>
                <div class="flex flex-col w-full space-y-6 bg-white">
                    <?php echo Form::model($data, ['route' => ['admin.dp-students.update', $data->id], 'method' => 'put',
                    'files' =>
                    true,'class'=>'flex flex-col w-full p-5 m-0 space-y-6 bg-white']); ?>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('TID', '身份證字號（必填）'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <?php echo Form::text('TID', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']); ?>

                        <?php else: ?>
                        <?php echo Form::text('TID', null, ['class' => 'h-12 px-4 w-full border-none', 'readonly']); ?>

                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('name', '姓名（必填）'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <?php echo Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']); ?>

                        <?php else: ?>
                        <?php echo Form::text('name', null, ['class' => 'h-12 px-4 w-full border-none',
                        'readonly']); ?>

                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('certificate', '證書編號'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <?php echo Form::text('certificate', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                        <?php else: ?>
                        <?php echo Form::text('certificate', null, ['class' => 'h-12 px-4 w-full border-none',
                        'readonly']); ?>

                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('birth', '出生年月日（必填）'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <?php echo Form::text('birth', null, [
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                        'placeholder' => '範例：19880606',
                        'required',
                        ]); ?>

                        <?php else: ?>
                        <?php echo Form::text('birth', null, ['class' => 'h-12 px-4 w-full border-none',
                        'readonly']); ?>

                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('gender', '性別（必填）'); ?>

                        <div class="flex flex-row flex-wrap text-center">
                            <?php
                        $checked = $data->gender == '男'?true:false;
                        ?>
                            <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                            <label class="radio-inline"><?php echo Form::radio('gender', '男', $checked, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark']); ?>男</label>
                            <label class="radio-inline"><?php echo Form::radio('gender', '女', !$checked, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark']); ?>女</label>
                            <?php else: ?>
                            <?php echo Form::text('gender', null, ['class' => 'h-12 px-4 w-full border-none',
                            'readonly']); ?>

                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('field', '工作領域'); ?>

                        <?php echo Form::select('field', $fields, $data->field, [
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('education', '最高學歷（選填）'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <?php echo Form::text('education', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full','required']); ?>

                        <?php else: ?>
                        <?php echo Form::text('education', null, ['class' => 'h-12 px-4 w-full border-none',
                        'readonly']); ?>

                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('service', '服務單位（選填）'); ?>

                        <?php echo Form::text('service', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('title', '職稱（選填）'); ?>

                        <?php echo Form::text('title', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('Yesno', '參與防災意願&nbsp;&nbsp;&nbsp;'); ?>

                        <div class="flex flex-row flex-wrap text-center">
                            <?php
                    $checked = $data->willingness?true:false;
                        ?>
                            <label class="radio-inline"><?php echo Form::radio('willingness', '1', $checked, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark']); ?>有&nbsp;</label>
                            <label class="radio-inline"><?php echo Form::radio('willingness', '0', !$checked, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark']); ?>無</label>
                        </div>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('phone', '市內電話（選填）'); ?>

                        <?php echo Form::text('phone', null, [
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                        'placeholder' => '範例：02-81234567']); ?>

                    </div>


                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('mobile', '行動電話（選填）'); ?>

                        <?php echo Form::text('mobile', null, [
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                        'placeholder' => '範例：0912-345678']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('email', 'E-mail（選填）'); ?>

                        <?php echo Form::text('email', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                        <label><?php echo Form::checkbox('resend_email', 1, null, ['class' => 'border-gray-300 rounded-sm
                            bg-white
                            text-mainCyanDark']); ?> 重新發送</label>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('address', '居住地址（必填）'); ?>

                        <?php echo Form::text('address', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('addressId', '地址識別碼（選填）'); ?>

                        <?php echo Form::text('addressId', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('community', '所屬村里或社區（選填）'); ?>

                        <?php echo Form::text('community', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('county_id', '所屬縣市（必填）'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <?php echo Form::select('county_id', $counties, request('county_id'), ['class' => 'h-12 px-4
                        border-gray-300
                        rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                        w-full','required']); ?>

                        <?php else: ?>
                        <p class="w-full pt-4 pl-2 text-left"><?php echo e($counties[$data->county_id]); ?></p>
                        <input type="hidden" name="county_id" value="<?php echo e($data->county_id); ?>">
                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('unit_first_course', '受訓單位（必填）'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <?php echo Form::text('unit_first_course', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md
                        shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full','required']); ?>

                        <?php else: ?>
                        <?php echo Form::text('unit_first_course', null, ['class' => 'h-12 px-4 w-full',
                        'readonly']); ?>

                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('date_first_finish', '證書發放日期（必填）'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <?php echo Form::text('date_first_finish', date('Y-m-d', strtotime($data->date_first_finish)), [
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                        'placeholder' => '格式範例：2018-06-01','required']); ?>

                        <?php else: ?>
                        <?php echo Form::text('date_first_finish', date('Y-m-d', strtotime($data->date_first_finish)), [
                        'class' => 'h-12 px-4 border-gray-300 w-full border-none', 'readonly']); ?>

                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center" style="display:none">
                        <?php echo Form::label('unit_second_course', '證書有效期限'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <?php echo Form::text('unit_second_course', date('Y-m-d', strtotime($data->unit_second_course)), [
                        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                        'placeholder' => '格式範例：2018-06-01']); ?>

                        <?php else: ?>
                        <?php echo Form::text('unit_second_course', date('Y-m-d', strtotime($data->unit_second_course)), [
                        'class' => 'h-12 px-4 w-full border-none', 'readonly']); ?>

                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('files[]', '證書'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <?php echo Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']); ?>

                        <div class="mb-6 xl:w-full">
                            <?php $__currentLoopData = $data->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight well"
                                data-id="<?php echo e($file->id); ?>">
                                <a href="/<?php echo e($file->path); ?>" target="_blank"
                                    class="flex-1 text-left text-mainBlueDark"><?php echo e($file->name); ?></a>
                                <span
                                    class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php echo Form::file('files[]', ['multiple' => true,'accept'=>'.pdf, .doc, .docx,
                        .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']); ?>

                        <?php else: ?>
                        <div class="mb-6 xl:w-full">
                            <?php $__currentLoopData = $data->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight well"
                                data-id="<?php echo e($file->id); ?>">
                                <a href="/<?php echo e($file->path); ?>" target="_blank"
                                    class="flex-1 text-left text-mainBlueDark"><?php echo e($file->name); ?></a>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('plan', '培訓計畫名稱（必填）'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <?php echo Form::text('plan', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']); ?>

                        <?php else: ?>
                        <?php echo Form::text('plan', null, ['class' => 'h-12 px-4 w-full border-none',
                        'readonly']); ?>

                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('score_academic', '學科測驗成績（必填）'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <?php echo Form::input('number', 'score_academic', null, ['class' => 'h-12 px-4 border-gray-300
                        rounded-md
                        shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                        w-full','required']); ?>

                        <?php else: ?>
                        <?php echo Form::input('number', 'score_academic', null, ['class' => 'h-12 px-4 w-full',
                        'readonly']); ?>

                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('physical_pass', '術科測驗成績是否合格&nbsp;&nbsp;&nbsp;'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <div class="flex flex-row flex-wrap text-center">
                            <label class="radio-inline"><?php echo Form::radio('physical_pass', 1, null, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark']); ?>合格&nbsp;</label>
                            <label class="radio-inline"><?php echo Form::radio('physical_pass', 0, null, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark']); ?>不合格</label>
                        </div>
                        <?php else: ?>
                        <p class="w-full pt-4 pl-2 text-left"><?php echo e($data->physical_pass==1?'合格':'不合格'); ?></p>
                        <input type="hidden" name="physical_pass" value="<?php echo e($data->physical_pass); ?>">
                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('pass', '認證結果是否合格&nbsp;&nbsp;&nbsp;'); ?>

                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                        <div class="flex flex-row flex-wrap text-center">
                            <label class="radio-inline"><?php echo Form::radio('pass', 1, null, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark']); ?>合格&nbsp;</label>
                            <label class="radio-inline"><?php echo Form::radio('pass', 0, null, ['class' =>
                                'border-gray-300
                                rounded-full bg-white text-mainCyanDark']); ?>不合格</label>
                        </div>
                        <?php else: ?>
                        <p class="w-full pt-4 pl-2 text-left"><?php echo e($data->pass==1?'合格':'不合格'); ?></p>
                        <input type="hidden" name="pass" value="<?php echo e($data->pass); ?>">
                        <?php endif; ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('dp_subjects[]', '參訓情形'); ?>

                        <table>
                            <thead>
                                <tr>
                                    <th class="p-2 font-normal text-left border-r last:border-r-0">科目</th>
                                    <th class="p-2 font-normal text-left border-r last:border-r-0">參訓</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dpSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dpSubject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="p-2 border-r last:border-r-0"><?php echo e($dpSubject->name); ?>&nbsp;&nbsp;</td>
                                    <td class="p-2 border-r last:border-r-0">
                                        <?php if($origin_role==1 || $origin_role==2 || $origin_role==6): ?>
                                        <?php echo Form::checkbox('dp_subjects[]',
                                        $dpSubject->id,
                                        $data->dpStudentSubjects->keyBy('dp_subject_id')->has($dpSubject->id), ['class'
                                        =>
                                        'border-gray-300 rounded-sm bg-white
                                        text-mainCyanDark']); ?>

                                        <?php else: ?>
                                        <?php echo Form::checkbox('dp_subjects[]',
                                        $dpSubject->id,
                                        $data->dpStudentSubjects->keyBy('dp_subject_id')->has($dpSubject->id), [
                                        'class' => 'border-gray-300 rounded-sm bg-white text-mainCyanDark']); ?>

                                        <?php endif; ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <?php echo Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex
                    justify-center
                    items-center h-10 bg-mainCyanDark rounded']); ?>

                    <?php if(Auth::user()->origin_role < 4): ?> <a @click="showDeleteModel=true" data-toggle="modal"
                        data-target="#js-delete-modal"
                        class="flex items-center justify-center w-full h-10 text-white bg-orange-500 rounded cursor-pointer hover:bg-orange-400">
                        刪除</a>
                        <?php endif; ?>
                        <?php echo Form::close(); ?>

                        <?php if(Auth::user()->origin_role < 4): ?> <?php echo Form::model($data, ['method'=> 'DELETE', 'route' =>
                            ['admin.dp-students.destroy', $data->id]]); ?>

                            <?php echo $__env->make('admin.layouts.partials.genericDeleteForm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::close(); ?>

                            <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 防災士資料管理',
'breadcrumbs' => [
['防災士培訓', route('admin.dp-students.index')],
'編輯受訓者與防災士資料'
]
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/Marshall/Downloads/RTP-main/resources/views/admin/dp-students/edit.blade.php ENDPATH**/ ?>