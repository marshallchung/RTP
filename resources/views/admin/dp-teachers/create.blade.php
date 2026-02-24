@extends('admin.layouts.dashboard', [
    'heading' => '防災士培訓 > 師資資料庫管理',
    'breadcrumbs' => [
        ['師資資料庫管理', route('admin.dp-teachers.index')],
        '新增'
    ]
])

@section('title', '新增師資資料')

@section('inner_content')
    <div x-data="{
        showDeleteModel:false,
        updatePassDateInput(e){
            let is_seed = e.target.value=='種子師資';
            if (is_seed) {
                e.target.closest('tr').querySelector('.pass-date-input').removeAttribute('disabled');
            }else{
                e.target.closest('tr').querySelector('.pass-date-input').setAttribute('disabled', '');
            }
        }
    }" class="flex flex-row items-start justify-start w-full" x-init="$nextTick(() => {
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
        document.querySelectorAll('.dp-subjects-selector').forEach(function(selecter) {
            selecter.addEventListener('change',updatePassDateInput);
            selecter.dispatchEvent(new Event('change', { 'bubbles': true }));
        });
     })">
        {!! Form::open([
        'method' => 'POST',
        'route' => 'admin.dp-teachers.store',
        'files' => true,
        'class' => 'flex flex-row
        w-full items-start justify-start px-4'
    ]) !!}
        <div class="relative w-full max-w-4xl">
            <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
                <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">新增
                </div>
                <div class="flex flex-col w-full p-5 m-0 space-y-6 bg-white">
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('location', '居住縣市（選填）') !!}
                        {!! Form::select('location', $counties, null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md
                        shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('address', '現居地址（選填）') !!}
                        {!! Form::text('address', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('addressId', '地址識別碼（選填）') !!}
                        {!! Form::text('addressId', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('belongsTo', '服務單位（選填）') !!}
                        {!! Form::text('belongsTo', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('title', '職別（選填）') !!}
                        {!! Form::text('title', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('name', '姓名（必填）') !!}
                        {!! Form::text('name', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'required'
    ]) !!}
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('tid', '身分證（必填）') !!}
                        {!! Form::text('tid', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'required'
    ]) !!}
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('phone', '市內電話（選填）') !!}
                        {!! Form::text('phone', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('mobile', '行動電話（選填）') !!}
                        {!! Form::text('mobile', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('email', '電子郵件（選填）') !!}
                        {!! Form::text('email', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
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
                                                                <td class="p-2 border-r last:border-r-0">{!!
                                        Form::select(
                                            "dp_subjects[{$dpSubject->id}]",
                                            [
                                                null => '',
                                                '基本師資' => '基本師資',
                                                '種子師資'
                                                => '種子師資'
                                            ],
                                            null,
                                            [
                                                '@change' => 'updatePassDateInput',
                                                'class' => 'dp-subjects-selector
                                                                    h-12 px-4 w-36 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                                                                    focus:ring-cyan-200 focus:ring-opacity-50'
                                            ]
                                        ) !!}</td>
                                                                <td class="p-2 border-r last:border-r-0">{!! Form::date(
                                        "pass_date[{$dpSubject->id}]",
                                        null,
                                        [
                                            'class' => 'pass-date-input h-12 px-4 border-gray-300 rounded-md shadow-sm
                                                                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
                                        ]
                                    )
                                                                    !!}</td>
                                                            </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('content', '學經歷專長（選填）') !!}
                        {!! Form::hidden('content-filter', '<p><br></p>') !!}
                        {!! Form::textarea('content', null, [
        'class' => 'js-wysiwyg p-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'rows' => 10
    ])
                        !!}
                    </div>
                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('files[]', '附件（選填）') !!}
                        {!! Form::file('files[]', [
        'multiple' => true,
        'accept' => '.pdf, .doc, .docx,
                        .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov'
    ]) !!}
                    </div>
                    {!! Form::submit('送出', [
        'class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex
                    justify-center
                    items-center h-10 bg-mainCyanDark
                    rounded'
    ]) !!}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('scripts')
    @parent
@endsection
