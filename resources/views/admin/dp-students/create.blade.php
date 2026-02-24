@extends('admin.layouts.dashboard', [
    'heading' => '防災士培訓 > 防災士資料管理',
    'breadcrumbs' => [
        ['防災士培訓', route('admin.dp-students.index')],
        '新增受訓者與防災士資料'
    ]
])

@section('title', '新增受訓者與防災士')

@section('inner_content')
    <div x-data="{
        showDeleteModel:false,
    }" class="flex flex-row items-start justify-start w-full">
        {!! Form::open([
        'method' => 'POST',
        'route' => 'admin.dp-students.store',
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
                        {!! Form::label('TID', '身份證字號（必填）') !!}
                        {!! Form::text('TID', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'required'
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
                        {!! Form::label('certificate', '證書編號') !!}
                        {!! Form::text('certificate', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('birth', '出生年月日（必填）') !!}
                        {!! Form::text('birth', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'placeholder' => '範例：19880606',
        'required'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('gender', '性別（必填）') !!}
                        <div class="flex flex-row flex-wrap text-center">
                            <label class="radio-inline">{!! Form::radio('gender', '男', null, [
        'required',
        'class' =>
            'border-gray-300
                                rounded-full bg-white text-mainCyanDark'
    ]) !!}男</label>
                            <label class="radio-inline">{!! Form::radio('gender', '女', null, [
        'required',
        'class' =>
            'border-gray-300
                                rounded-full bg-white text-mainCyanDark'
    ]) !!}女</label>
                        </div>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('field', '工作領域') !!}
                        {!! Form::select('field', $fields, '', [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('education', '最高學歷') !!}
                        {!! Form::text('education', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('service', '服務單位') !!}
                        {!! Form::text('service', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('title', '職稱') !!}
                        {!! Form::text('title', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap space-x-4 text-center">
                        {!! Form::label('Yesno', '參與防災意願') !!}
                        <div class="flex flex-row flex-wrap space-x-2 text-center">
                            <label class="radio-inline">{!! Form::radio('willingness', '1', false, [
        'class' =>
            'border-gray-300
                                rounded-full bg-white text-mainCyanDark'
    ]) !!}有</label>
                            <label class="radio-inline">{!! Form::radio('willingness', '0', false, [
        'class' =>
            'border-gray-300
                                rounded-full bg-white text-mainCyanDark'
    ]) !!}無</label>
                        </div>
                    </div>


                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('phone', '市內電話（選填）') !!}
                        {!! Form::text('phone', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'placeholder' => '範例：02-81234567',
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('mobile', '行動電話（必填）') !!}
                        {!! Form::text('mobile', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'placeholder' => '範例：0912345678',
        'required'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('email', 'E-mail（必填）') !!}
                        {!! Form::text('email', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'required'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('address', '居住地址（必填）') !!}
                        {!! Form::text('address', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'required'
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
                        {!! Form::label('community', '所屬村里或社區（選填）') !!}
                        {!! Form::text('community', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('county_id', '所屬縣市（必填）') !!}
                        {!! Form::select('county_id', $counties, request('county_id'), [
        'class' => 'h-12 px-4
                        border-gray-300
                        rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                        w-full',
        'required'
    ])
                        !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('unit_first_course', '受訓單位（必填）') !!}
                        {!! Form::text('unit_first_course', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md
                        shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'required'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('date_first_finish', '證書發放日期（必填）') !!}
                        {!! Form::text('date_first_finish', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'placeholder' => '格式範例：2018-06-01',
        'required'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center" style="display:none">
                        {!! Form::label('unit_second_course', '證書有效期限') !!}
                        {!! Form::text('unit_second_course', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                        focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'placeholder' => '格式範例：2018-06-01'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('file', '證書上載') !!}
                        {!! Form::file('files[]', [
        'multiple' => true,
        'term' => 2,
        'accept' => '.pdf, .doc, .docx,
                        .jpg, .jpeg, .png, .gif, .zip, .rar, .txt, .csv, .xlsx, .odf, .mp4, .mov'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('plan', '培訓計畫名稱（必填）') !!}
                        {!! Form::text('plan', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'required'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('score_academic', '學科測驗成績（必填）') !!}
                        {!! Form::input('number', 'score_academic', null, [
        'class' => 'h-12 px-4 border-gray-300 rounded-md
                        shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full',
        'required'
    ]) !!}
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('physical_pass', '術科測驗成績是否合格') !!}
                        <div class="flex flex-row flex-wrap text-center">
                            <label class="radio-inline">{!! Form::radio('physical_pass', 1, false, [
        'class' =>
            'border-gray-300
                                rounded-full bg-white text-mainCyanDark'
    ]) !!}合格</label>
                            <label class="radio-inline">{!! Form::radio('physical_pass', 0, false, [
        'class' =>
            'border-gray-300
                                rounded-full bg-white text-mainCyanDark'
    ]) !!}不合格</label>
                        </div>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('pass', '認證結果是否合格') !!}
                        <div class="flex flex-row flex-wrap text-center">
                            <label class="radio-inline">{!! Form::radio('pass', 1, false, [
        'class' => 'border-gray-300
                                rounded-full bg-white text-mainCyanDark'
    ]) !!}合格</label>
                            <label class="radio-inline">{!! Form::radio('pass', 0, false, [
        'class' => 'border-gray-300
                                rounded-full bg-white text-mainCyanDark'
    ]) !!}不合格</label>
                        </div>
                    </div>

                    <div class="flex flex-row flex-wrap text-center">
                        {!! Form::label('dp_subjects[]', '參訓情形') !!}
                        <table>
                            <thead>
                                <tr>
                                    <th class="p-2 font-normal text-left border-r last:border-r-0">科目</th>
                                    <th class="p-2 font-normal text-left border-r last:border-r-0">參訓</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dpSubjects as $dpSubject)
                                                            <tr>
                                                                <td class="p-2 border-r last:border-r-0">{{ $dpSubject->name }}&nbsp;&nbsp;</td>
                                                                <td class="p-2 border-r last:border-r-0">{!! Form::checkbox(
                                        'dp_subjects[]',
                                        $dpSubject->id,
                                        null,
                                        [
                                            'class' => 'border-gray-300 rounded-sm bg-white
                                                                    text-mainCyanDark'
                                        ]
                                    )
                                                                    !!}</td>
                                                            </tr>
                                @endforeach
                            </tbody>
                        </table>
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
