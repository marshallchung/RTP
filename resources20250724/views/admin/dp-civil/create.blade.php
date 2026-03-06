@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 社團法人臺灣防災教育訓練學會',
'breadcrumbs' => [
['防災士培訓機構', route('admin.dp-civil.index')],
'新增社團法人臺灣防災教育訓練學會'
]
])

@section('title', '新增社團法人臺灣防災教育訓練學會')

@section('inner_content')
{!! Form::open(['method' => 'POST', 'route' => 'admin.dp-civil.store']) !!}
<div class="md:w-67/100 xl:w-75/100" x-data="" x-init="$nextTick(() => {
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
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">新增</div>
        <div class="p-5 m-0 bg-white">

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('name', '名稱（必填）') !!}
                {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('phone', '連絡電話（必填）') !!}
                {!! Form::text('phone', null, [
                'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                focus:ring-cyan-200 focus:ring-opacity-50 w-full',
                'required',
                'placeholder' => '範例：02-81234567',
                ]) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('address', '機構地址（必填）') !!}
                {!! Form::text('address', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('business', '辦理業務（必填）') !!}
                {!! Form::hidden('content-filter', '<p><br></p>') !!}
                {!! Form::textarea('business', null, ['class' => 'js-wysiwyg p-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'rows' => 10]) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('front_man', '代表人（必填）') !!}
                {!! Form::text('front_man', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('url', '網址（必填）') !!}
                {!! Form::url('url', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
            </div>

            {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center
            items-center h-10 bg-mainCyanDark
            rounded']) !!}
        </div>
    </div>
</div>
</div>
{!! Form::close() !!}
<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection