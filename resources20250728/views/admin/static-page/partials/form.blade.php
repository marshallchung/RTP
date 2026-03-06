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
 })">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">編輯</div>
        <div class="flex flex-col items-start justify-start w-full p-5 m-0 space-y-4 bg-white">
            <div class="flex flex-row flex-wrap w-full text-center">
                {!! Form::label('id', 'ID') !!}
                {!! Form::text('id', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                w-full','readonly','required']) !!}
                <div class="help-block">系統自動配發網址參數</div>
            </div>
            @if($canManageAll)
            <div class="flex flex-row flex-wrap w-full text-center">
                {!! Form::label('user_id', '管理者') !!}
                {!! Form::select('user_id', $userOptions, null, ['class' => 'h-12 px-4 border-gray-300 rounded-md
                shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
            </div>
            @endif

            <div class="flex flex-row flex-wrap w-full text-center">
                {!! Form::label('title', '標題') !!}
                {!! Form::text('title', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
            </div>

            <div class="flex flex-row flex-wrap w-full text-center">
                {!! Form::label('content', '內容') !!}
                {!! Form::hidden('content-filter', '<p><br></p>') !!}
                {!! Form::textarea('content', null, ['class' => 'js-wysiwyg p-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'rows' => 10]) !!}
            </div>
        </div>
    </div>
</div>
<div class="relative xl:w-25/100 xl:float-left md:w-1/3">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
            發表
        </div>
        <div class="p-5 m-0 bg-white">
            {!! Form::submit('送出', ['class' => 'w-full text-white flex justify-center items-center h-10 cursor-pointer
            bg-mainCyanDark hover:bg-teal-400
            rounded']) !!}
        </div>
        @if (isset($showDelete) && $showDelete)
        <a @click="showDeleteModel=true" data-toggle="modal" data-target="#js-delete-modal"
            class="border-t py-2.5 px-5 rounded-br-sm rounded-bl-sm bg-white flex flex-row justify-start items-center  w-full cursor-pointer text-mainBlueDark">刪除</a>
        @endif
    </div>
</div>

@section('scripts')
<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection