@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 課程抵免審查',

'breadcrumbs' => [
['課程抵免審查', route('admin.dp-waivers-review.index')],
'審查'
]
])

@section('title', $data->title)

@section('inner_content')
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
        {!! Form::model($data, ['route' => ['admin.dp-waivers-review.update', $data->id], 'method' => 'put', 'files' =>
        true]) !!}
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">編輯</div>
        <div class="p-5 m-0 bg-white">
            <div class="flex flex-row flex-wrap text-center">
                <label>身分證字號</label>
                <p>{{ $data->dpScore->dpStudent->TID ?? null}}</p>
            </div>
            <div class="flex flex-row flex-wrap text-center">
                <label>姓名</label>
                <p>{{ $data->dpScore->dpStudent->name ?? null}}</p>
            </div>
            <div class="flex flex-row flex-wrap text-center">
                <label>欲抵免防災士課程內容</label>
                <p>{{ $data->dpScore->dpCourse->name ?? null }}</p>
            </div>
            <div class="flex flex-row flex-wrap text-center">
                <label>參與其他防災課程名稱</label>
                <p>{{ $data->name }}</p>
            </div>
            <div class="flex flex-row flex-wrap text-center">
                <label>檔案</label>
                <ul class="list-none">
                    @foreach($data->files as $file)
                    <li><a href="{{ url($file->path) }}" target="_blank">{{ $file->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="flex flex-row flex-wrap text-center">
                <label>辦理單位</label>
                <p>{{ $data->dpScore->author->name ?? null }}</p>
            </div>
            <div class="flex flex-row flex-wrap text-center">
                <label>辦理時間</label>
                <p>{{ $data->created_at }}</p>
            </div>


            <div class="flex flex-row flex-wrap text-center">
                <label>審查結果</label>
                <p>
                    <label class="radio-inline">{!! Form::radio('review_result', 1,false, ['class' => 'border-gray-300
                        rounded-full bg-white text-mainCyanDark']) !!}通過</label>
                    <label class="radio-inline">{!! Form::radio('review_result', 0,false, ['class' => 'border-gray-300
                        rounded-full bg-white text-mainCyanDark']) !!}不通過</label>
                </p>
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('review_comment', '審查意見') !!}
                {!! Form::hidden('content-filter', '<p><br></p>') !!}
                {!! Form::textarea('review_comment', null, ['class' => 'js-wysiwyg p-4 border-gray-300 rounded-md
                shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'rows' =>
                10]) !!}
            </div>

            {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center
            items-center h-10 bg-mainCyanDark
            rounded']) !!}
            {!! Form::close() !!}
        </div>

        {!! Form::model($data, ['method' => 'DELETE', 'route' => ['admin.dp-teachers.destroy', $data->id]]) !!}
        @include('admin.layouts.partials.genericDeleteForm')
        {!! Form::close() !!}
    </div>
</div>

<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection