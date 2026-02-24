<?php $__env->startSection('title', $data->title); ?>

<?php $__env->startSection('inner_content'); ?>
<div class="flex flex-row items-start justify-start w-full" x-data="{
    showDeleteModel:false,
    updatePassDateInput(e){
        let is_seed = e.target.value=='種子師資';
        if (is_seed) {
            e.target.closest('tr').querySelector('.pass-date-input').removeAttribute('disabled');
        }else{
            e.target.closest('tr').querySelector('.pass-date-input').setAttribute('disabled', '');
        }
    }
}" x-init="$nextTick(() => {
    tinymce.init({
        content_css: '/css/tinymce.css',
        selector: '.js-wysiwyg',
        language: 'zh_TW',
        branding: false,
        plugins: 'autolink image link media table hr advlist lists  help anchor wordcount searchreplace visualblocks visualchars charmap emoticons code paste',
        toolbar1: 'formatselect fontselect | bold italic underline strikethrough forecolor backcolor | link image media | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat',
        image_advtab: true,
        height: 500,
        font_formats: '新細明體=PMingLiU; 標楷體=DFKai-sb; 微軟正黑體=Microsoft JhengHei; Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats',
        paste_data_images: true,
        paste_as_text: true,
    });
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
    document.querySelectorAll('.dp-subjects-selector').forEach(function(selecter) {
        selecter.addEventListener('change',updatePassDateInput);
        selecter.dispatchEvent(new Event('change', { 'bubbles': true }));
    });
 })">
    <div class="flex flex-row items-start justify-start w-full px-4">
        <div class="relative w-full max-w-4xl">
            <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
                <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                    編輯
                </div>
                <div class="flex flex-col w-full space-y-6 bg-white">
                    <?php echo Form::model($data, ['route' => ['admin.dp-teachers.update', $data->id], 'method' => 'put',
                    'files'
                    =>
                    true,'class'=>'flex flex-col w-full p-5 m-0 space-y-6 bg-white']); ?>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('location', '居住縣市（選填）'); ?>

                        <?php echo Form::select('location', $counties, null, ['class' => 'h-12 px-4 border-gray-300 rounded-md
                        shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('address', '現居地址（選填）'); ?>

                        <?php echo Form::text('address', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('addressId', '地址編碼（選填）'); ?>

                        <?php echo Form::text('addressId', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('belongsTo', '服務單位（選填）'); ?>

                        <?php echo Form::text('belongsTo', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('title', '職別（選填）'); ?>

                        <?php echo Form::text('title', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('name', '姓名（必填）'); ?>

                        <?php echo Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('tid', '身分證（必填）'); ?>

                        <?php echo Form::text('tid', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('phone', '市內電話（選填）'); ?>

                        <?php echo Form::text('phone', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('mobile', '行動電話（選填）'); ?>

                        <?php echo Form::text('mobile', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('email', '電子郵件（選填）'); ?>

                        <?php echo Form::text('email', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']); ?>

                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('dp_subjects[]', '教授科目（選填）'); ?>

                        <table>
                            <thead>
                                <tr>
                                    <th class="p-2 font-normal text-left border-r last:border-r-0">科目</th>
                                    <th class="p-2 font-normal text-left border-r last:border-r-0">師資類型</th>
                                    <th class="p-2 font-normal text-left border-r last:border-r-0">通過日期</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dpSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dpSubject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="p-2 border-r last:border-r-0"><?php echo e($dpSubject->name); ?></td>
                                    <td class="p-2 border-r last:border-r-0"><?php echo Form::select("dp_subjects[{$dpSubject->id}]",
                                        [null => '', '基本師資' => '基本師資', '種子師資'
                                        => '種子師資'],
                                        $data->dpTeacherSubjects->keyBy('dp_subject_id')->has($dpSubject->id) ?
                                        $data->dpTeacherSubjects->keyBy('dp_subject_id')->get($dpSubject->id)->type :
                                        null,
                                        ['class' => 'dp-subjects-selector h-12 px-4 w-36 border-gray-300 rounded-md
                                        shadow-sm focus:border-cyan-300 focus:ring
                                        focus:ring-cyan-200 focus:ring-opacity-50']); ?></td>
                                    <td class="p-2 border-r last:border-r-0"><?php echo Form::date("pass_date[{$dpSubject->id}]",
                                        $data->dpTeacherSubjects->keyBy('dp_subject_id')->has($dpSubject->id) ?
                                        $data->dpTeacherSubjects->keyBy('dp_subject_id')->get($dpSubject->id)->pass_date
                                        :
                                        null,
                                        ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                                        w-full
                                        pass-date-input']); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        <?php echo Form::label('content', '學經歷專長（選填）'); ?>

                        <?php echo Form::hidden('content-filter', '<p><br></p>'); ?>

                        <?php echo Form::textarea('content', null, ['class' => 'js-wysiwyg p-4 border-gray-300 rounded-md
                        shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'rows' =>
                        10]); ?>

                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        <?php if($data->files): ?>
                        <?php echo Form::label('files[]', '附件（選填）'); ?>

                        <?php if(class_basename($data->files) === 'Collection'): ?>
                        <?php echo Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']); ?>

                        <div class="mb-6 xl:w-full">
                            <?php $__currentLoopData = $data->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight well"
                                data-id="<?php echo e($file->id); ?>">
                                <a href="<?php echo e(url($file->path)); ?>" target="_blank"
                                    class="flex-1 text-left text-mainBlueDark"><?php echo e($file->name); ?></a>
                                <span
                                    class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php echo Form::file('files[]', ['multiple' => true,'accept'=>'.pdf, .doc, .docx,
                        .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']); ?>

                    </div>

                    <?php echo Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex
                    justify-center
                    items-center h-10 bg-mainCyanDark
                    rounded']); ?>


                    <a @click="showDeleteModel=true" data-toggle="modal" data-target="#js-delete-modal"
                        class="flex items-center justify-center w-full h-10 text-white bg-orange-500 rounded cursor-pointer hover:bg-orange-400">刪除</a>
                    <?php echo Form::close(); ?>


                </div>

                <?php echo Form::model($data, ['method' => 'DELETE', 'route' => ['admin.dp-teachers.destroy', $data->id]]); ?>

                <?php echo $__env->make('admin.layouts.partials.genericDeleteForm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 師資資料庫管理',
'breadcrumbs' => [
['師資資料庫管理', route('admin.dp-teachers.index')],
'編輯'
]
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/Marshall/Downloads/RTP-main/resources/views/admin/dp-teachers/edit.blade.php ENDPATH**/ ?>