@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 培訓課程管理',
'breadcrumbs' => [
['培訓課程管理', route('admin.dp-courses.index')],
'新增'
]
])

@section('title', '新增師資資料')

@section('inner_content')

<div class="flex flex-row items-start justify-start w-full" x-data="{
    dpcounties:{{ json_encode($dpcounties) }},
    dptraining:{{ json_encode($dptraining) }},
    option_list:{{ json_encode($dptraining) }},
    selected:'training',
    typeChange(){
        if(this.selected==='training'){
            this.option_list=this.dptraining;
        }else{
            this.option_list=this.dpcounties;
        }
    },
    showDeleteModel:false,
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
 })">
    <div class="flex flex-row items-start justify-start w-full px-4">
        <div class="relative w-full max-w-4xl">
            <div class="relative w-full my-6 bg-white border border-gray-200 rounded-sm">
                <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">
                    新增
                </div>
                <div class="flex flex-col w-full space-y-6 bg-white">
                    {!! Form::open(['method' => 'POST', 'route' => 'admin.dp-courses.store', 'files' =>
                    true,'class'=>'flex flex-col w-full p-5 m-0 space-y-6 bg-white']) !!}
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('county_id', '管理單位') !!}
                        {!! Form::select('county_id', $counties, request('county_id'), ['class' => 'h-12 px-4
                        border-gray-300
                        rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                        w-full']) !!}
                    </div>

                    <div class="flex flex-row items-center justify-start w-full space-x-4">
                        {!! Form::label('organizer', '主辦單位') !!}
                        <select x-model="selected" @change="typeChange"
                            class="h-12 px-4 border-gray-300 rounded-md shadow-sm w-36 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50">
                            <option value="training">培訓機構</option>
                            <option value="county">縣市單位</option>
                        </select>
                        <select
                            class="flex-1 h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                            id="organizer" name="organizer">
                            <template x-for="[key, value] of Object.entries(option_list)">
                                <option :value="value"
                                    x-text="value=='消防署'?'內政部消防署':(value.length==3?value + '政府':value)"></option>
                            </template>
                        </select>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('name', '培訓名稱') !!}
                        {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required'])
                        !!}
                    </div>

                    <div class="flex items-center justify-start form-row">
                        <div class="form-group col-md-6">
                            {!! Form::label('date_from', '課程期間（起）') !!}
                            <input type="date" name="date_from" id="date_from"
                                class="inline-block w-auto align-middle bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                                required>
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('date_to', '（迄）') !!}
                            <input type="date" name="date_to" id="date_to"
                                class="inline-block w-auto align-middle bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                                required>
                        </div>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('contact_person', '聯絡人') !!}
                        {!! Form::text('contact_person', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md
                        shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('phone', '連絡電話') !!}
                        {!! Form::text('phone', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('email', '電子郵件') !!}
                        {!! Form::text('email', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('url', '報名連結') !!}
                        {!! Form::text('url', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                    </div>

                    <div class="flex flex-row flex-wrap space-x-4 text-center">
                        <div class="flex flex-row items-center space-x-1">
                            {!! Form::checkbox('stop_signup', 1, null, ['class' => 'border-gray-300 rounded-sm bg-white
                            text-mainCyanDark']) !!}
                            {!! Form::label('stop_signup', '報名截止') !!}
                        </div>
                        <div class="flex flex-row items-center space-x-1">
                            {!! Form::checkbox('exclusive', 1, null, ['class' => 'border-gray-300 rounded-sm bg-white
                            text-mainCyanDark']) !!}
                            {!! Form::label('exclusive', '專班辦理') !!}
                        </div>
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('content', '培訓計畫摘要') !!}
                        {!! Form::hidden('content-filter', '<p><br></p>') !!}
                        {!! Form::textarea('content', null, ['class' => 'js-wysiwyg p-4 border-gray-300 rounded-md
                        shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'rows' =>
                        10]) !!}
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('files[]', '課程表上傳') !!}
                        {!! Form::file('files[]', ['multiple' => true,'accept'=>'.pdf, .doc, .docx,
                        .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov']) !!}
                    </div>

                    {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex
                    justify-center
                    items-center h-10 bg-mainCyanDark
                    rounded']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection