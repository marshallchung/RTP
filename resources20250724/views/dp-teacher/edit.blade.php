@extends('layouts.app')

@section('title', '師資資料')
@section('subtitle', '師資基本資料更新')

@section('content')
<div class="mt-1 card" x-data="" x-init="$nextTick(() => {
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
 })">
    <div class="flex-auto p-5">
        {!! Form::model($data, ['url' => $formActionUrl, 'method' => 'put', 'files' => true]) !!}

        <div class="flex flex-row flex-wrap text-center">
            {!! Form::label('location', '居住縣市（選填）') !!}
            {!! Form::select('location', $counties, null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
            focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
        </div>

        <div class="flex flex-row flex-wrap text-center">
            {!! Form::label('address', '現居地址（必填）') !!}
            {!! Form::text('address', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
            focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
        </div>

        <div class="flex flex-row flex-wrap text-center">
            {!! Form::label('belongsTo', '服務單位（選填）') !!}
            {!! Form::text('belongsTo', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
            focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
        </div>

        <div class="flex flex-row flex-wrap text-center">
            {!! Form::label('title', '職別（選填）') !!}
            {!! Form::text('title', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
            focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
        </div>

        <div class="flex flex-row flex-wrap text-center">
            {!! Form::label('name', '姓名（必填）') !!}
            {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
            focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
        </div>

        <div class="flex flex-row flex-wrap text-center">
            {!! Form::label('tid', '身分證（必填）') !!}
            {!! Form::text('tid', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
            focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
        </div>

        <div class="flex flex-row flex-wrap text-center">
            {!! Form::label('phone', '市內電話（選填）') !!}
            {!! Form::text('phone', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
            focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
        </div>
        <div class="flex flex-row flex-wrap text-center">
            {!! Form::label('mobile', '行動電話（選填）') !!}
            {!! Form::text('mobile', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
            focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
        </div>

        <div class="flex flex-row flex-wrap text-center">
            {!! Form::label('email', '電子郵件（選填）') !!}
            {!! Form::text('email', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
            focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
        </div>

        <div class="flex flex-row flex-wrap text-center">
            {!! Form::label('dp_subjects[]', '教授科目（選填）') !!}
            <table>
                <thead>
                    <tr>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">科目</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">師資類型</th>
                        <th class="p-2 font-normal text-left border-r last:border-r-0">通過日期</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dpSubjects as $dpSubject)
                    <tr>
                        <td class="p-2 border-r last:border-r-0">{{ $dpSubject->name }}</td>
                        <td class="p-2 border-r last:border-r-0">{!! Form::select("dp_subjects[{$dpSubject->id}]", [null
                            => '', '基本師資' => '基本師資', '種子師資' =>
                            '種子師資'], $data->dpTeacherSubjects->keyBy('dp_subject_id')->has($dpSubject->id) ?
                            $data->dpTeacherSubjects->keyBy('dp_subject_id')->get($dpSubject->id)->type : null, ['class'
                            => 'dp-subjects-selector', 'disabled']) !!}</td>
                        <td class="p-2 border-r last:border-r-0">{!! Form::date("pass_date[{$dpSubject->id}]",
                            $data->dpTeacherSubjects->keyBy('dp_subject_id')->has($dpSubject->id) ?
                            $data->dpTeacherSubjects->keyBy('dp_subject_id')->get($dpSubject->id)->pass_date : null,
                            ['class' => 'form-control pass-date-input', 'disabled']) !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex flex-row flex-wrap text-center">
            {!! Form::label('content', '學經歷專長（選填）') !!}
            {!! Form::hidden('content-filter', '<p><br></p>') !!}
            {!! Form::textarea('content', null, ['class' => 'js-wysiwyg p-4 border-gray-300 rounded-md shadow-sm
            focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'rows' => 10]) !!}
        </div>
        <div class="flex flex-row flex-wrap text-center">
            @if($data->files)
            {!! Form::label('files[]', '附件（選填）') !!}
            @if(class_basename($data->files) === 'Collection')
            {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
            <div class="mb-6 xl:w-full">
                @foreach($data->files as $file)
                <div class="flex flex-row items-center justify-start w-full p-2 bg-mainLight well"
                    data-id="{{ $file->id }}">
                    <a href="{{ url($file->path) }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                        $file->name }}</a>
                    <span
                        class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                </div>
                @endforeach
            </div>
            @endif
            @endif
            {!! Form::file('files[]', ['multiple' => true,'accept'=>'.pdf, .doc, .docx,
            .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
        </div>

        {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center
        items-center h-10 bg-mainCyanDark
        rounded']) !!}
        {!! Form::close() !!}

    </div>
    {!! Form::close() !!}
</div>

@endsection

@section('js')
@parent
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
<script>
    if ($('html').hasClass('ie8') === false) {
            tinymce.init({
                selector: '.js-wysiwyg',
                language: 'zh_TW',
                branding: false,
                plugins: 'autolink image link media table hr advlist lists  help anchor wordcount searchreplace visualblocks visualchars charmap emoticons code paste',
                // plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
                toolbar1: 'formatselect fontselect | bold italic underline strikethrough forecolor backcolor | link image media | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat',
                image_advtab: true,
                height: 500,
                font_formats: '新細明體=PMingLiU; 標楷體=DFKai-sb; 微軟正黑體=Microsoft JhengHei; Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats',
                paste_data_images: true,
                paste_as_text: true
            });
        }
        $('.js-remove-file').on('click', function (event) {
            var file, id, removedFiles, removedFilesInput;
            event.preventDefault();
            removedFilesInput = $('#js-removed-files');
            removedFiles = JSON.parse(removedFilesInput.val());
            file = $(this).parents('.well');
            id = file.data('id');
            removedFiles.push(id);
            removedFilesInput.val(JSON.stringify(removedFiles));
            return file.remove();
        });
</script>
@endsection