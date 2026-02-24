<div class="flex-1 pr-6" x-data="" x-init="$nextTick(() => {
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
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">編輯</div>
        <div class="flex flex-col items-start justify-start w-full p-5 m-0 space-y-4 bg-white">
            <div class="flex flex-row flex-wrap w-full text-center">
                {!! Form::label('title', '標題') !!}
                {!! Form::text('title', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
            </div>

            <div class="flex flex-row flex-wrap w-full text-center">
                {!! Form::label('title', '排序') !!}
                {!! Form::input('number', 'position', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'min' => 1]) !!}
            </div>

            <div class="flex flex-row flex-wrap w-full text-center">
                {!! Form::label('title', '分類') !!}
                {!! Form::select('sort', $sorts, null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
            </div>

            <div class="flex flex-row flex-wrap w-full text-center">
                {!! Form::label('content', '內容') !!}
                {!! Form::hidden('content-filter', '<p><br></p>') !!}
                {!! Form::textarea('content', null, ['class' => 'js-wysiwyg p-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'rows' => 10]) !!}
            </div>

            @if(isset($files))
            <div class="flex flex-col items-start justify-start w-full space-y-4">
                {!! Form::label('files[]', '附件') !!}
                @if(class_basename($files) === 'Collection')
                {!! Form::hidden('removed_files', '[]', ['id' => 'js-removed-files']) !!}
                <div class="mb-6 xl:w-full">
                    @foreach($files as $file)
                    <div class="flex flex-row items-center justify-start p-2 bg-mainLight well"
                        data-id="{{ $file->id }}">
                        <a href="/{{ $file->path }}" target="_blank" class="flex-1 text-left text-mainBlueDark">{{
                            $file->name }}</a>
                        <span
                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 js-remove-file bg-rose-600 hover:bg-rose-500">刪除</span>
                    </div>
                    @endforeach
                </div>
                @endif
                {!! Form::file('files[]', ['multiple' => true,'accept'=>'.pdf, .doc, .docx,
                .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
            </div>
            @endif
        </div>
    </div>
</div>
<div class="relative w-1/3 xl:w-1/4">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">發表</div>
        <div class="flex flex-col p-5 space-y-6 bg-white">
            <div class="flex flex-row flex-wrap text-center">
                <label class="checkbox-inline">
                    {!! Form::hidden('active', 0) !!}
                    {!! Form::checkbox('active', 1, null, ['class' => 'border-gray-300 rounded-sm bg-white
                    text-mainCyanDark']) !!}
                    <span class="text-sm text-mainAdminTextGrayDark">是否上線</span>
                </label>
            </div>
            {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center
            items-center h-10 bg-mainCyanDark
            rounded']) !!}
        </div>
        @if (isset($showDelete))
        <div class="border-t py-2.5 px-5 rounded-br-sm rounded-bl-sm bg-white">
            <a @click="showDeleteModel=true" class="cursor-pointer text-mainBlueDark">刪除</a>
        </div>
        @endif
    </div>
</div>

@section('scripts')
<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection